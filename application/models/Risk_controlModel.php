<?php class Risk_controlModel extends _Model{
	public $table = "risk_control";
	public $pk = "id_control";
	public $order_default = "m.id_risiko, m.no, m.nama asc";
	function __construct(){
		parent::__construct();
	}

	function SqlCombo($id_risiko=null, $id_control=null){
		$where = " where 1=1 ";

		if($id_risiko)
			$where .= " and id_risiko = ".$this->conn->escape($id_risiko);

		if($id_control)
			$where .= " and id_control <> ".$this->conn->escape($id_control);

		return "select {$this->pk} as key, {$this->label} as val from {$this->table} $where order by key";
	}

	function GetCombo($id_risiko=null, $id_control=null){
		$sql = $this->SqlCombo($id_risiko, $id_control);
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['key']] = $r['val'];
		}
		return $data;
	}

	public function GetNo($id_risiko=null){
		return $this->conn->GetOne("select nvl(max(no),0)+1 from {$this->table} where id_risiko = ".$this->conn->escape($id_risiko));
	}

	public function SelectGrid($arr_param=array(), $str_field="m.*,m.nama as nama_aktifitas, j.nama as nama_pic")
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

		$str_condition = "where 1=1 and is_close<>1";
		$str_order = "";
		if(!empty($arr_params['filter']))
		{
			$str_condition .= " and ".$arr_params['filter'];
		}
		if(!empty($arr_params['order']))
		{
			$str_order = "order by ".$arr_params['order'];
		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}
		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				".$this->table." m
				left join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				{$str_condition}
				{$str_order} ");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from
				".$this->table." m
				left join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				{$str_condition}
				{$str_order} ",$arr_params['limit'],$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			".$this->table." m
				left join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
			{$str_condition}
		");

		return $arr_return;
	}
}