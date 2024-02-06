<?php class Mt_risk_kriteria_dampakModel extends _Model{
	public $table = "mt_risk_kriteria_dampak";
	public $pk = "id_kriteria_dampak";
	function __construct(){
		parent::__construct();
	}
	function GetCombo(){

		$id_parent = null;

		$sql = "select * from mt_risk_kriteria_dampak order by kode, id_kriteria_dampak";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$this->GenerateTree($rows, "id_induk", "id_kriteria_dampak", "nama", $ret, $id_parent);

		$return = array(''=>'-pilih-');
		foreach ($ret as $r) {
			$return[$r['id_kriteria_dampak']] = $r['nama'];
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

		$rows = $this->conn->GetArray("
			select *
			from mt_risk_kriteria_dampak a
			{$str_condition}
			order by kode, id_kriteria_dampak");

		$keys = $this->conn->GetKeys($rows, "id_kriteria_dampak");

		if($keys){
			$rowsdetail = $this->conn->GetArray("select * 
			from mt_risk_kriteria_dampak_detail
			where id_kriteria_dampak in (".implode(",", $keys).")");
			$temparr = array();
			foreach($rowsdetail as $r){
				$temparr[$r['id_kriteria_dampak']][$r['id_dampak']] = $r['keterangan'];
			}

			foreach ($rows as $k=>$r) {
				if(!$temparr[$r['id_kriteria_dampak']])
					$temparr[$r['id_kriteria_dampak']] = array();

				$rows[$k] = $temparr[$r['id_kriteria_dampak']]+$r;
			}
		}

		$arr_return['rows'] = array();
		
		$this->GenerateSort($rows, "id_induk", "id_kriteria_dampak", "nama", $arr_return['rows'], $id_parent,$i=0,0,true);

		return $arr_return;
	}
}
