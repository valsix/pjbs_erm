<?php class Mt_risk_matrixModel extends _Model{
	public $table = "mt_risk_matrix";
	public $pk = "id_dampak";
	public $order_default = "id_dampak desc, id_kemungkinan asc";
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

		// $str_condition= str_replace("id_katalogtoolmaterial", "a.id_katalogtoolmaterial", $str_condition);
		// $str_condition= str_replace("and 0='21'", "", $str_condition);
		// echo $str_condition;exit;

		$table = "
		(
			select
				m.*,
				td.nama, td.warna
			from ".$this->table." m
			join mt_risk_tingkat td on m.id_tingkat = td.id_tingkat
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

	function getMatrix(){
		$sql = "select m.*, 
		td.nama, 
		td.warna, 
		rk.kode as kode_kemungkinan, 
		rd.kode as kode_dampak
		from mt_risk_matrix m
		join mt_risk_tingkat td on m.id_tingkat = td.id_tingkat
		join mt_risk_kemungkinan rk on m.id_kemungkinan = rk.id_kemungkinan
		join mt_risk_dampak rd on m.id_dampak = rd.id_dampak
		";

		return $this->conn->GetArray($sql);
	}

	function getTingkat($rs=null){
		$arr = array();
		foreach ($rs as $r) {
			$arr[$r['id_kemungkinan']][$r['id_dampak']] = $r['id_tingkat'];
		}

		return $arr;
	}

	function minRiskApertite(){
		return $this->GetOne("select min(id_tingkat) from mt_risk_matrix where css is not null");
	}

	public function GetByPk($id_kemungkinan=null, $id_dampak=null){
		if(!$id_kemungkinan or !$id_dampak){
			return array();
		}
		$sql = "select * from ".$this->table." where id_kemungkinan = ".$this->conn->escape($id_kemungkinan).
            	" and id_dampak = ".$this->conn->escape($id_dampak);
		$ret = $this->conn->GetRow($sql);

		if(!$ret)
			$ret = array();

		return $ret;
	}
}