<?php class Risk_scorecardgrc_filesModel extends _Model{
	public $table = "risk_scorecardgrc_files";
	public $pk = "id_scorecardgrc_files";
	function __construct(){
		parent::__construct();
	}

	public function selectbyparam($mode="", $statement=""){
		// if(!$id){
		// 	return array();
		// }

		$sql = "
		select 
			a.*
		from ".$this->table." a
		where 1=1 ".$statement."
		order by a.".$this->pk;
		// echo $sql;exit;

		if($mode == "array")
		{
			$ret= $this->conn->GetArray($sql);
		}
		elseif($mode=='list')
		{
			$ret= $this->conn->GetList($sql);
		}
		else
		{
			$ret= $this->conn->GetRow($sql);
		}

		if(!$ret)
			$ret = array();

		return $ret;
	}
}
