<?php class Mt_pb_kelompok_prosesModel extends _Model{
	public $table = "mt_pb_kelompok_proses";
	public $pk = "id_kelompok_proses";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}

	function SqlCombo(){

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		return "select {$this->pk} as key, {$this->label} as val from {$this->table} where '$tgl_efektif' between nvl(tgl_mulai_efektif, '$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif') order by key";
	}
}