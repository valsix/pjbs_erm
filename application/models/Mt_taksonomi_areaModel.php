<?php class Mt_taksonomi_areaModel extends _Model{
	public $table = "mt_taksonomi_area";
	public $pk = "id_taksonomi_area";
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
}