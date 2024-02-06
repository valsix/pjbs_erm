<?php class Mt_risk_kajian_risikoModel extends _Model{
	public $table = "mt_risk_kajian_risiko";
	public $pk = "id_kajian_risiko";
	function __construct(){
		parent::__construct();
	}

	function isKegiatan($id_kajian_risiko){
		return $this->conn->GetOne("select * from mt_risk_kajian_risiko where id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko)." and jenis_sasaran = '2'");
	}

	public function selectbyparamkajianrisikodetil($id, $mode="", $statement= ""){
		if(!$id){
			return array();
		}

		$statement= "";

		$sql = "
		select *
		from ".$this->table."detil a
		where 1=1
		and a.".$this->pk." = '".$id."' ".$statement."
		order by a.".$this->pk.", a.nourut";
		// echo $sql;exit;

		if($mode == "array")
		{
			$ret= $this->conn->GetArray($sql);
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
