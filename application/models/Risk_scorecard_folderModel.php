<?php class Risk_scorecard_folderModel extends _Model{
	public $table = "risk_scorecard_folder";
	public $pk = "id_scorecard_folder";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}

	// function SqlCombo(){

	// 	if(!$tgl_efektif)
	// 		$tgl_efektif = date("d-m-Y");

		
	// 	if($_SESSION[SESSION_APP]['tgl_efektif']){
	// 		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
	// 	}

	// 	return "select {$this->pk} as key, {$this->label} as val from {$this->table} where '$tgl_efektif' between nvl(tgl_mulai_efektif,'$tgl_efektif') and nvl(tgl_akhir_efektif,'$tgl_efektif') order by key";
	// }

	public function selectbyparam($mode="", $statement=""){
		// if(!$id){
		// 	return array();
		// }

		$sql = "
		select 
			a.*, b.nama
		from ".$this->table." a
		left join mt_fd_dok_pendukung_grc b on b.id_dok_pendukung_grc = a.id_dok_pendukung_grc
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