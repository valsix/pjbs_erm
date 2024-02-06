<?php class Risk_scorecardModel extends _Model{
	public $table = "risk_scorecard";
	public $pk = "id_scorecard";
	function __construct(){
		parent::__construct();
	}

	
	function GetList($id_kajian_risiko=null, $tgl_efektif=null, $id_parent=null, $is_no_window = false, $tahun=null, $want_evaluasi=false, $statement=''){
		
		$tgl_efektif = $this->tgl_efektif($tahun);

		$filter = "";
		if($tahun)
			$filter = " and '$tahun' between nvl(to_char(a.tgl_mulai_efektif,'YYYY'),'$tahun') and nvl(to_char(a.tgl_akhir_efektif,'YYYY'),'$tahun')";
		elseif($tgl_efektif)
			$filter = " and '$tgl_efektif' between nvl(a.tgl_mulai_efektif,'$tgl_efektif') and nvl(a.tgl_akhir_efektif,'$tgl_efektif')";
		
		if($is_no_window){
			$sql = "select a.nomor_ba_grc, a.id_status_unit, a.id_nama_proses, 0 as navigasi, a.id_status_proyek, a.id_proyek, a.on_cost, a.on_time, a.on_spec, a.on_safety, a.id_scorecard, a.nama, 
			a.id_scorecard as id,
			a.id_parent_scorecard as id_parent, a.owner, a.open_evaluasi
			from 
			risk_scorecard a
			left join mt_sdm_jabatan s on a.owner = s.id_jabatan
			where id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko)."
			".$statement."
			$filter";
		}else{
			$sql = "select a.nomor_ba_grc, a.id_status_unit, a.id_nama_proses, a.id_proyek, a.id_status_proyek, a.navigasi, a.id_scorecard, a.on_cost, a.on_time, a.on_spec, a.on_safety,  a.nama, 
			a.id_scorecard as id,
			a.id_parent_scorecard as id_parent, a.owner, a.open_evaluasi
			from 
			risk_scorecard a
			left join mt_sdm_jabatan s on a.owner = s.id_jabatan
			where id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko)."
			".$statement."
			$filter";
		}

		/*if($want_evaluasi!==false){
			if($want_evaluasi)
				$sql .= " and (open_evaluasi = '0' or open_evaluasi is null)";
			else
				$sql .= " and open_evaluasi = '1'";
		}*/

		$sql.="
		order by cast(a.nomor_ba_grc_inp as integer), a.id_status_unit, s.position_id, id";

		$rows = $this->conn->GetArray($sql);

		$ret = array();
		$this->GenerateSort($rows, "id_parent", "id", "nama", $ret, $id_parent);

		return $ret;
	}


	function GenerateSort(&$row, $colparent, $colid, $collabel, &$return=array(), $valparent=null, &$i=0, $level=0, $is_space=false){
		$level++;
		foreach ($row as $key => $value) {
			if((int)trim($value[$colparent])==(int)trim($valparent)){
				unset($row[$key]);

				$return[$i]=$value;

				$i++;
				
				if($value['navigasi']<>'1')
					$this->GenerateSort($row, $colparent, $colid, $collabel, $return, $value[$colid], $i, $level);
			}
		}
	}

	public function GetByPk($id){
		if(!$id){
			return array();
		}

		$where = " {$this->pk} = ".$this->conn->qstr($id);

		$sql = "select s.*, k.jenis_sasaran from ".$this->table." s join mt_risk_kajian_risiko k on s.id_kajian_risiko = k.id_kajian_risiko where ".$where;
		$ret = $this->conn->GetRow($sql);

		if(!$ret)
			$ret = array();


		if(!$this->ci->Access('view_all_direktorat','panelbackend/risk_scorecard')){

			$childarr = $_SESSION[SESSION_APP]['child_jabatan'];

			$owner = $ret['owner'];
			
			if(!in_array($owner, $childarr)){
				$this->ci->data['edited'] = false;
				$this->ci->data['editedheader'] = false;
				$this->ci->data['editedheader1'] = false;
				$this->ci->access_role['add'] = false;
				$this->ci->access_role['edit'] = false;
				$this->ci->access_role['delete'] = false;
			}
		}

		$ret['broadcrumscorecard'] = $this->GetComboParent($ret['id_scorecard']);

		unset($ret['broadcrumscorecard'][$ret['id_scorecard']]);

		return $ret;
	}

	public function GetCombo($key=null, $q=null, $tahun=null, $id_kajian_risiko=null, $tgl_efektif=null, $statement=""){

		if(!$tgl_efektif)
			$tgl_efektif = $this->tgl_efektif($tahun);

		$id_parent = null;

		$sql = "select * from risk_scorecard 
		where '$tgl_efektif' 
		between nvl(tgl_mulai_efektif,'$tgl_efektif')
		and nvl(tgl_akhir_efektif,'$tgl_efektif') ".$statement;

		if($q)
			$sql .= " and  lower(nama) like '%$q%'";

		if($key)
			$sql .= " and id_scorecard = ".$this->conn->escape($key);

		if($id_kajian_risiko)
			$sql .= " and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko);

		$sql .= " order by id_parent_scorecard, id_scorecard";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$this->GenerateTree($rows, "id_parent_scorecard", "id_scorecard", "nama", $ret, $id_parent);

		$return = array(''=>'-pilih-');
		foreach ($ret as $r) {
			$return[$r['id_scorecard']] = str_replace(" &amp; ", " ", $r['nama']);
		}

		return $return;		
	}

	public function GetComboGrc($key=null, $q=null, $tahun=null, $id_kajian_risiko=null, $tgl_efektif=null, $statement=""){

		if(!$tgl_efektif)
			$tgl_efektif = $this->tgl_efektif($tahun);

		$id_parent = null;

		$sql = "select * from risk_scorecard 
		where '$tgl_efektif' 
		between nvl(tgl_mulai_efektif,'$tgl_efektif')
		and nvl(tgl_akhir_efektif,'$tgl_efektif') 
		and is_grc is not null".$statement;

		if($q)
			$sql .= " and  lower(nama) like '%$q%'";

		if($key)
			$sql .= " and id_scorecard = ".$this->conn->escape($key);

		if($id_kajian_risiko)
			$sql .= " and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko);

		$sql .= " order by id_parent_scorecard, id_scorecard";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$this->GenerateTree($rows, "id_parent_scorecard", "id_scorecard", "nama", $ret, $id_parent);

		$return = array(''=>'-pilih-');
		foreach ($ret as $r) {
			$return[$r['id_scorecard']] = str_replace(" &amp; ", " ", $r['nama']);
		}

		return $return;		
	}

	private function tgl_efektif($tahun=null){
		$tgl_efektif = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		list($tgl, $bln, $thn) = explode("-",$tgl_efektif);

		if($tahun <> $thn && $tahun){
			$thn = $tahun;
			$bln = '12';
			$tgl = '31';

			$tgl_efektif = $tgl."-".$bln."-".$thn;
		}

		return $tgl_efektif;
	}

	public function GetChild($id_parent=null, $tahun=null, $id_kajian_risiko=null, $tgl_efektif=null){

		if(!$tgl_efektif)
			$tgl_efektif = $this->tgl_efektif($tahun);

		if($id_parent)
			$add_filter = " and id_parent_scorecard = ".$this->conn->escape($id_parent);
		else
			$add_filter = " and id_parent_scorecard is null";

		if($id_kajian_risiko)
			$add_filter .= " and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko);

		$rows = $this->conn->GetArray("select id_scorecard 
		from risk_scorecard 
		where  
		'$tgl_efektif' 
		between nvl(tgl_mulai_efektif,'$tgl_efektif')
		and nvl(tgl_akhir_efektif,'$tgl_efektif')
		$add_filter");
		$ret = array();

		if($id_parent)
			$ret[] = $id_parent;

		if($rows)
			foreach($rows as $r){
			$ret1 = $this->GetChild($r['id_scorecard'], $tahun, $id_kajian_risiko);
			$ret = array_merge($ret, $ret1);
		}

		return $ret;
	}

	public function GetComboChild($id_parent=null,$is_tree=true, $tgl_efektif=null){
		$id_scorecardarr = $this->GetChild($id_parent, null, null, $tgl_efektif);
		if(!$id_scorecardarr)
			return array();

		$rows = $this->conn->GetArray("select 
			nama, 
			id_scorecard as id,
			id_parent_scorecard as id_parent
			from risk_scorecard where id_scorecard in (".implode(",", $id_scorecardarr).")");

		if(!$rows)
			return array();

		$ret = array();
		if($is_tree)
			$this->GenerateTree($rows, "id_parent", "id", "nama", $ret, $id_parent);
		else
			$ret = $rows;

		$data = array(''=>'-pilih-');
		foreach ($ret as $r) {
			$data[$r['id']] = $r['nama'];
		}
		unset($data[$id_parent]);

		if(($data)==1)
			return array();
		
		return $data;
	}

	public function GetComboParent($id_child=null){
		$row = $this->conn->GetRow("select id_scorecard, id_parent_scorecard, nama from risk_scorecard where id_scorecard = ".$this->conn->escape($id_child));
		if(!$row)
			return array();
		
		$ret = array();
		if($row['id_parent_scorecard']){
			$ret = $this->GetComboParent($row['id_parent_scorecard']);
		}

		$ret[$id_child] = $row['nama'];

		return $ret;
	}

	public function SelectGridGrc($arr_param=array(), $str_field="*")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'order' => '',
			'filter' => ''
		);

		// print_r($arr_param);exit;
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
		}

		$str_condition.= " and a.is_grc = 1 and a.navigasi = 0";
		// echo $str_condition;exit;

		$table = "
		(
			select
				a.*
			from ".$this->table." a
			".$str_condition."
		) a";
		// echo $table;exit;

		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				".$table."
				{$str_condition}
				{$str_order} ");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from
				".$table."
				{$str_condition}
				{$str_order} ",$arr_params['limit'],$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			".$table."
			{$str_condition}
		");

		return $arr_return;
	}

	public function SelectGridPrintGrc($arr_param=array(), $str_field="*")
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

		$str_condition.= " and a.is_grc = 1 and a.navigasi = 0";
		// echo $str_condition;exit;

		$table = "
		(
			select
				a.*
			from ".$this->table." a
			".$str_condition."
		) a";
		// echo $table;exit;

		$arr_return['rows'] = $this->conn->GetArray("
			select
			{$str_field}
			from
			".$table."
			{$str_condition}
			{$str_order} ");

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			".$table."
			{$str_condition}
		");

		return $arr_return;
	}
}
