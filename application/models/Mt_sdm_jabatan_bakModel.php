<?php class Mt_sdm_jabatanModel extends _Model{
	public $table = "mt_sdm_jabatan";
	public $pk = "id_jabatan";
	public $label = "nama";

	function __construct(){
		parent::__construct();
	}

	function GetCombo($key=null, $q=null, $direktorat=null, $subdit=null, $need_access = true){

		$q = $this->conn->escape_str(strtolower($q));

		$sql = "select id_jabatan as id, nama as text
			from mt_sdm_jabatan
			where 1=1 ";

		/*$page_ctrl = 'panelbackend/risk_risiko';

		if($page_ctrl=='panelbackend/risk_risiko' or $page_ctrl=='panelbackend/risk_scorecard' or $page_ctrl=='panelbackend/risk_sasaran_kegiatan'){

			$positionarr = $this->conn->GetList("select distinct trim(id_jabatan) as val from risk_sasaran_strategis_pic");

			$positionstr = implode("','", $positionarr);

			$sql .= " and trim(id_jabatan) in ('$positionstr') ";
		}*/

		/*if(!$this->ci->Access('view_all_direktorat',$page_ctrl) && $need_access){
			$childarr = $_SESSION[SESSION_APP]['child_jabatan'];

			if(!$childarr)
				$childarr = array();

			$this->conn->escape_string($childarr);
			
			$childastr = implode("','", $childarr);
			$sql .= " and id_jabatan in ('$childastr')";
		}*/

		if($q)
			$sql .= " and  lower(nama) like '%$q%'";

		if($key)
			$sql .= " and id_jabatan = ".$this->conn->escape($key);

		if($direktorat)
			$sql .= " and direktorat = ".$this->conn->escape($direktorat);

		if($subdit)
			$sql .= " and subdit = ".$this->conn->escape($subdit);

		$sql .= " and rownum <= 10";

		$return = $this->conn->GetArray($sql);

		$arr = array();
		foreach ($return as $r) {
			$arr[$r['id']] = $r['text'];
		}

		return $arr;
	}

	function GetComboDirektorat($key=null, $q=null, $direktorat=null, $subdit=null, $need_access = true){

		$q = $this->conn->escape_str(strtolower($q));

		$sql = "select id_jabatan as id, 
			case when subditket is not null
			then subditket 
			else direktoratket
			end
			as text
			from mt_sdm_jabatan j
			where 1=1 ";

		$page_ctrl = 'panelbackend/risk_risiko';

		if($page_ctrl=='panelbackend/risk_risiko' or $page_ctrl=='panelbackend/risk_scorecard' or $page_ctrl=='panelbackend/risk_sasaran_kegiatan'){

			$positionarr = $this->conn->GetList("select distinct trim(id_jabatan) as val from risk_sasaran_strategis_pic");

			$positionstr = implode("','", $positionarr);

			$sql .= " and trim(id_jabatan) in ('$positionstr') ";
		}

		if(!$this->ci->Access('view_all_direktorat',$page_ctrl) && $need_access){
			$childarr = $_SESSION[SESSION_APP]['child_jabatan'];

			if(!$childarr)
				$childarr = array();

			$this->conn->escape_string($childarr);
			
			$childastr = implode("','", $childarr);
			$sql .= " and id_jabatan in ('$childastr')";
		}

		if($q)
			$sql .= " and  (
		lower(nama) like '%$q%'
		or
		lower(direktorat) like '%$q%'
		or
		lower(subditket) like '%$q%'
		)";

		if($key)
			$sql .= " and id_jabatan = ".$this->conn->escape($key);

		if($direktorat)
			$sql .= " and direktorat = ".$this->conn->escape($direktorat);

		if($subdit)
			$sql .= " and subdit = ".$this->conn->escape($subdit);

		$sql .= " and rownum <= 10";

		$return = $this->conn->GetArray($sql);

		$arr = array();
		foreach ($return as $r) {
			$arr[$r['id']] = $r['text'];
		}

		return $arr;
	}
}