<?php
class _Model extends CI_Model{
	public $conn;
	public $table;
	protected $pk;
	protected $label="nama";
	protected $kode="kode";
	protected $arr_no_quote=array();
	public $arrNoquote=array();
	public $order_default;
	protected $str_condition;
	function __construct(){
        parent::__construct();
        $this->conn = $this->db;

        $this->ci = get_instance();
		$this->sso = $this->config->item('sso');
	}

    public function Execute($sql){
    	return $this->conn->Execute($sql);
    }

	public function GetOne($sql){
		return $this->conn->GetOne($sql);
	}

	public function GetOneArray($sql){
		$data = $this->conn->GetArray($sql);
		if(!$data)
			return false;

		$return = array();
		$key = key($data[0]);
		foreach ($data as $i => $value) {
			# code...
			$return[$i] = $value[$key];
		}
		return $return;
	}

	public function GetRow($sql){
		return $this->conn->GetRow($sql);
	}

	public function GetArray($sql){
		return $this->conn->GetArray($sql);
	}

	public function GOne($field="*",$addsql=""){
		$sql = "select {$field} from ".$this->table." {$addsql}";
		return $this->conn->GetOne($sql);
	}

	public function GRow($field="*",$addsql=""){
		$sql = "select {$field} from ".$this->table." {$addsql}";
		return $this->conn->GetRow($sql);
	}

	public function GArray($field="*",$addsql=""){
		$sql = "select {$field} from ".$this->table." {$addsql}";
		return $this->conn->GetArray($sql);
	}

	public function GetByPk($id){
		if(!$id){
			return array();
		}
		$sql = "select * from ".$this->table." where {$this->pk} = ".$this->conn->qstr($id);
		$ret = $this->conn->GetRow($sql);

		if(!$ret)
			$ret = array();

		return $ret;
	}

	public function Insert($arr_data=array()){
		$return = false;

        $sql = $this->conn->InsertSQL($this->table, $arr_data);

        if($sql){
		    $ret = $this->conn->Execute($sql);
		    if($ret){
		   		$info_nama = 'Insert <b>'.$arr_data['nama'].'</b>  ';

				$return['success']="$info_nama berhasil.";
		    }
		}else{
			$return['error']="Insert gagal.";
		}

		if(!empty($this->pk) && isset($return['success'])){
			$return['data']=$this->conn->GetRow("
				select a.*
				from ".$this->table." a
				where ".$this->pk." = (select max(b.".$this->pk.")  from ".$this->table." b)");
		}

		return $return;
	}

	public function Update($arr_data=array(),$str_condition=""){
		$return = false;

		$sql = $this->conn->UpdateSQL($this->table, $arr_data, $str_condition);
		if($sql){
		    $ret = $this->conn->Execute($sql);

		    if($ret){
		   		$info_nama = 'Update <b>'.$arr_data['nama'].'</b>  ';
				$return['success']="$info_nama berhasil.";
		    }

		}else{
			$return['error']="Update gagal.";
		}

		return $return;
	}

	public function CheckUpdated(){
		return $this->conn->GetOne('select 1 from '.$this->table.' '.$this->str_condition);
	}

	public function Delete($str_condition=""){
		$return = false;
		// check condition
		if(!empty($str_condition))
		{
			$str_condition = " where {$str_condition}";
		}

		// define sql
		$sql = "delete from ".$this->table." {$str_condition}";

	    $ret = $this->conn->Execute($sql);

	    if($ret){
	   		$info_nama = 'Delete <b>'.$arr_data['nama'].'</b>  ';
			$return['success']="$info_nama berhasil.";
	    }

		return $return;
	}

	public function SelectGrid($arr_param=array(), $str_field="*")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'order' => '',
			'filter' => ''
		);
		foreach($arr_param as $key=>$val){
			$arr_params[$key]=$val;
		}

		$arr_params['page'] = ($arr_params['page']/$arr_params['limit'])+1;

		$str_condition = "";
		$str_order = "";
		if(!empty($arr_params['filter']))
		{
			$str_condition = "where ".$arr_params['filter'];
		}
		if(!empty($arr_params['order']))
		{
			$str_order = "order by ".$arr_params['order'];
		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}else{
			$str_order = "order by ".$this->pk." asc";
		}
		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				".$this->table."
				{$str_condition}
				{$str_order} ");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from
				".$this->table."
				{$str_condition}
				{$str_order} ",$arr_params['limit'],$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			".$this->table."
			{$str_condition}
		");

		return $arr_return;
	}

	public function SelectGridPrint($arr_param=array(), $str_field="*")
	{
		$arr_return = array();
		$arr_params = array(
			'order' => '',
			'filter' => ''
		);
		foreach($arr_param as $key=>$val){
			$arr_params[$key]=$val;
		}

		$str_condition = "";
		$str_order = "";
		if(!empty($arr_params['filter']))
		{
			$str_condition = "where ".$arr_params['filter'];
		}
		if(!empty($arr_params['order']))
		{
			$str_order = "order by ".$arr_params['order'];
		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}

		$arr_return['rows'] = $this->conn->GetArray("
			select
			{$str_field}
			from
			".$this->table."
			{$str_condition}
			{$str_order} ");

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			".$this->table."
			{$str_condition}
		");

		return $arr_return;
	}


	function GenerateTree(&$row, $colparent, $colid, $collabel, &$return=array(), $valparent=null, &$i=0, $level=0){
		$level++;
		foreach ($row as $key => $value) {
			# code...
			if(trim($value[$colparent])==trim($valparent) && ($value[$colparent] or $valparent===null)){
				
				unset($row[$key]);

				$space = '';
				for($k=1; $k<$level; $k++){
					$space .='&nbsp;➥&nbsp;';
				}

				$value[$collabel] = $space.$value[$collabel];
				$return[$i]=$value;

				$i++;
				$this->GenerateTree($row, $colparent, $colid, $collabel, $return, $value[$colid], $i, $level);
			}
		}

		if($row && $level==1)
			$return = array_merge($return, $row);
	}


	function GenerateSort(&$row, $colparent, $colid, $collabel, &$return=array(), $valparent=null, &$i=0, $level=0, $is_space = false){
		$level++;
		foreach ($row as $key => $value) {
			# code...
			if(trim($value[$colparent])==trim($valparent) && ($value[$colparent] or $valparent===null)){
				unset($row[$key]);

				$space = '';
				if($is_space){
					for($k=1; $k<$level; $k++){
						$space .='&nbsp;➥&nbsp;';
					}
				}

				$value[$collabel] = $space.$value[$collabel];
				$return[$i]=$value;

				$i++;
				$this->GenerateSort($row, $colparent, $colid, $collabel, $return, $value[$colid], $i, $level, $is_space);
			}
		}

		if($row && $level==1)
			$return = array_merge($return, $row);
	}

	function MaxRow(){
		$sql = "select * from ".$this->table." order by {$this->pk} desc limit 1";
		return $this->conn->GetRow($sql);
	}

	function SqlCombo(){
		return "select {$this->pk} as key, {$this->label} as val from {$this->table} order by key";
	}

	function SqlComboNew($whereinfo=""){
		return "select {$this->pk} as key, {$this->label} as val from {$this->table} {$whereinfo} order by key";
	}

	function SqlComboDK(){
		return "select {$this->pk} as key, {$this->kode} as kode, {$this->label} as val from {$this->table} order by key";
	}

	function GetCombo(){
		$sql = $this->SqlCombo();
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['key'])] = $r['val'];
		}
		return $data;
	}

	function GetComboNew($infolabel="", $whereinfo=""){
		if(!empty($infolabel))
		{
			$this->label= $infolabel;
		}

		if(!empty($whereinfo))
		{
			$whereinfo= "where ".$whereinfo;
		}

		$sql = $this->SqlComboNew($whereinfo);
		// echo $sql;exit;
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['key'])] = $r['val'];
		}
		return $data;
	}

	function GetComboDK(){
		$sql = $this->SqlComboDK();
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['key']] = $r['kode']." - ".$r['val'];
		}
		return $data;
	}

	function GetComboLost($id=null, &$arr_combo=array(), $attarr = array()){
		if($arr_combo[$id])
			return;
		
		$label = $this->label;
		$pk = $this->pk;
		$table = $this->table;

		if($attarr['label'])
			$label = $attarr['label'];

		if($attarr['pk'])
			$pk = $attarr['pk'];

		if($attarr['table'])
			$table = $attarr['table'];

		$arr_combo[$id] = $this->conn->GetOne("select {$label} from {$table} where {$pk} = ".$this->conn->escape($id));
	}
}
