<?php class Risk_logModel extends _Model{
	public $table = "risk_log";
	public $pk = "activity_time";
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
		$str_order = "";
		if(!empty($arr_params['filter']))
		{
			$str_condition = "where ".str_replace("group_id", "t.group_id", $arr_params['filter']);
		}
		if(!empty($arr_params['order']))
		{
			$str_order = "order by ".$arr_params['order'];
		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}
		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select t.*, to_char(t.activity_time,'dd-mm-yyyy hh:ii:ss') as created_date1, u.name as nama_user
				from 
				".$this->table." t
				join public_sys_user u on t.created_by = u.user_id
				{$str_condition} 
				{$str_order} ");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select t.*, to_char(t.activity_time,'dd-mm-yyyy hh:ii:ss') as created_date1, u.name as nama_user
				from 
				".$this->table." t
				join public_sys_user u on t.created_by = u.user_id
				{$str_condition} 
				{$str_order} ",$arr_params['limit'],$arr_params['page']
			);
		}
		
		$arr_return['total'] = static::GetOne("
			select 
			count(*) as total 
			from 
			".$this->table." t
			{$str_condition}
		");
		
		return $arr_return;
	}
}