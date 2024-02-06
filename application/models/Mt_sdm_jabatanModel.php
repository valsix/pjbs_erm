<?php class Mt_sdm_jabatanModel extends _Model{
	public $table = "mt_sdm_jabatan";
	public $pk = "id_jabatan";
	function __construct(){
		parent::__construct();
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

		if(!empty($arr_params['filter']))
		{
			$str_condition = "where ".$arr_params['filter'];
		}

		$str_order = "";

		if(!empty($arr_params['order']))
		{
			$str_order = "order by ".$arr_params['order'];
		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$tgl_efektif = date('d-m-Y');
		else
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		$rows = $this->conn->GetArray("
			select 
			a.*
			from mt_sdm_jabatan a
			{$str_condition}
			order by position_id");

		$arr_return['rows'] = array();

		$id_parent = null;
		$this->GenerateSort($rows, "superior_id", "position_id", "nama", $arr_return['rows'], $id_parent);

		return $arr_return;
	}

	public function GetCombo($key=null, $q=null){

		$sql = "select d.*
		from mt_sdm_jabatan d
		where status <> 'UN'";

		if($q)
			$sql .= " and  lower(d.nama) like '%$q%'";

		if($key)
			$sql .= " and id_jabatan = ".$this->conn->escape($key);

		$sql .= " order by position_id";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$id_parent = null;
		$this->GenerateTree($rows, "superior_id", "position_id", "nama", $ret, $id_parent);

		$return = array();
		foreach ($ret as $r) {
			if(trim($r['id_unit'])=='KP')
				$return[$r['id_jabatan']] = str_replace(" &amp; ", " ", $r['nama'])." - ".$r['position_id'];
			else
				$return[$r['id_jabatan']] = str_replace(" &amp; ", " ", $r['nama'])." ".$r['unit_ket']." - ".$r['position_id'];
		}

		return $return;		
	}
}