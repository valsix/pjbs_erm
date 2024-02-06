<?php class Risk_mitigasiModel extends _Model{
	public $table = "risk_mitigasi";
	public $pk = "id_mitigasi";
	public $order_default = "m.no, m.nama asc";
	
	function __construct(){
		parent::__construct();
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

		$str_condition = "where 1=1 and not(is_control = '1' or is_close = '1') ";
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
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				{$str_condition}
				{$str_order} ");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from
				".$this->table." m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				{$str_condition}
				{$str_order} ",$arr_params['limit'],$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			".$this->table." m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
			{$str_condition}
		");

		return $arr_return;
	}

	public function SelectGridOverdue($arr_param=array(), $str_field="m.*,m.nama as nama_aktifitas, j.nama as nama_pic, r.nomor as kode_risiko, r.nama as nama_risiko, r.id_scorecard")
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

		$str_condition = "where 1=1 and is_control <> '1' and dead_line<=sysdate and id_status_progress <> 4";
		$str_order = "";
		if(!empty($arr_params['filter']))
		{
			$str_condition .= " and ".$arr_params['filter'];
		}
		if(!empty($arr_params['order']))
		{
			$str_order = "order by r.id_risiko, ".$arr_params['order'];
		}else{
			$str_order = "order by r.id_risiko";
		}
		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				".$this->table." m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				join risk_risiko r on m.id_risiko = r.id_risiko
				{$str_condition}
				{$str_order} ");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from
				".$this->table." m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				join risk_risiko r on m.id_risiko = r.id_risiko
				join risk_scorecard s on r.id_scorecard = s.id_scorecard
				{$str_condition}
				{$str_order} ",$arr_params['limit'],$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			".$this->table." m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				join risk_risiko r on m.id_risiko = r.id_risiko
				join risk_scorecard s on r.id_scorecard = s.id_scorecard
			{$str_condition}
		");

		return $arr_return;
	}
}