<?php class Mt_level_grcModel extends _Model{
	public $table = "mt_level_grc";
	public $pk = "id_level_grc";
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

	public function selectbyparamfolderlevelgrc($statementparam="", $statementjoin="", $mode=""){
		// if(!$id){
		// 	return array();
		// }

		// if(empty($idreservasi))
		// 	$idreservasi= -1;

		// $statement= "";
		// if (!empty($jenis)) {
		// 	$param["jenis"]= $jenis;
		// 	$jenis= globalfungsi::jenistool($param);

		// 	$statement= " and a.id_katalogtool in
		// 	(
		// 		select id_katalogtool from mt_katalogtool where id_kategori in (select kategori_tool_id from mt_kategori_tool where kode = '".$jenis."')
		// 		--and is_delete = '0'
		// 	)
		// 	";
		// }

		// $statementparam.= $statement;
		$sql = "
		select 
			b.id_level_grcdetil, b.id_level_grc, b.status, a.id_dok_pendukung_grc, a.nama
		from mt_fd_dok_pendukung_grc a
		left join mt_level_grcdetil b on b.id_dok_pendukung_grc = a.id_dok_pendukung_grc and b.status = 1 ".$statementjoin."
		where 1=1 and a.status = 1 ".$statementparam."
		order by a.id_dok_pendukung_grc
		";
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

	public function selectbyparamfolderscorecard($statementparam="", $statementjoin="", $mode=""){
		// if(!$id){
		// 	return array();
		// }

		// if(empty($idreservasi))
		// 	$idreservasi= -1;

		// $statement= "";
		// if (!empty($jenis)) {
		// 	$param["jenis"]= $jenis;
		// 	$jenis= globalfungsi::jenistool($param);

		// 	$statement= " and a.id_katalogtool in
		// 	(
		// 		select id_katalogtool from mt_katalogtool where id_kategori in (select kategori_tool_id from mt_kategori_tool where kode = '".$jenis."')
		// 		--and is_delete = '0'
		// 	)
		// 	";
		// }

		// $statementparam.= $statement;
		$sql = "
		select 
			a.id_dok_pendukung_grc, a.nama, b.id_scorecard_folder, b.id_scorecard
		from
		mt_fd_dok_pendukung_grc a
		left join risk_scorecard_folder b on b.id_dok_pendukung_grc = a.id_dok_pendukung_grc ".$statementjoin."
		where a.status = 1 ".$statementparam."
		order by a.id_dok_pendukung_grc
		";
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