<?php class Mt_folder_dokgrcModel extends _Model{
	public $table = "mt_fd_dok_pendukung_grc";
	public $pk = "id_dok_pendukung_grc";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}

	function SqlCombo(){

		if(!$tgl_efektif)
			$tgl_efektif = date("d-m-Y");

		
		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		return "select {$this->pk} as key, {$this->label} as val from {$this->table} where '$tgl_efektif' between nvl(tgl_mulai_efektif,'$tgl_efektif') and nvl(tgl_akhir_efektif,'$tgl_efektif') order by key";
	}

	public function selectbyparamfolder($mode="", $statement=""){
		// if(!$id){
		// 	return array();
		// }

		$sql = "
		select 
			a.id_dok_pendukung_grc key,
		    a.nama val
		from ".$this->table." a
		where 1=1 ".$statement."
		and a.status = 1
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