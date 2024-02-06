<?php class Risk_sasaran_strategisModel extends _Model{
	public $table = "risk_sasaran_strategis";
	public $pk = "id_sasaran_strategis";
	function __construct(){
		parent::__construct();
	}

	function GetNama($id_sasaran_strategis=null){
		return $this->conn->GetOne("select nama from risk_sasaran_strategis where id_sasaran_strategis = ".$this->conn->escape($id_sasaran_strategis));
	}

	function SqlCombo($owner=null, $tgl_efektif=null, $tahun=null){
		$filter = "";
		if($owner)
			$filter = " and p.id_jabatan = ".$this->conn->escape($owner);

		if($tahun)
			$filter = " and '$tahun' between nvl(to_char(tgl_mulai_efektif,'YYYY'),'$tahun') and nvl(to_char(tgl_akhir_efektif,'YYYY'),'$tahun')";
		elseif($tgl_efektif)
			$filter = " and '$tgl_efektif' between nvl(tgl_mulai_efektif,'$tgl_efektif') and nvl(tgl_akhir_efektif,'$tgl_efektif')";

		return "select s.{$this->pk} as key, s.{$this->label} as val from {$this->table} s
		left join risk_sasaran_strategis_pic p on s.id_sasaran_strategis = p.id_sasaran_strategis  where 1=1 $filter order by key";
	}

	function GetCombo($owner=null, $tgl_efektif=null, $tahun=null){

		if(!$tgl_efektif)
			$tgl_efektif = date("d-m-Y");

		
		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$sql = $this->SqlCombo($owner, $tgl_efektif, $tahun);
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['key']] = $r['val'];
		}
		return $data;
	}
}
