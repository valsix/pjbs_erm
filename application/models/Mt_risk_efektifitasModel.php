<?php class Mt_risk_efektifitasModel extends _Model{
	public $table = "mt_risk_efektifitas";
	public $pk = "id_efektifitas";
	function __construct(){
		parent::__construct();
	}

	public function getKetEfektifitas()
	{
		$status_lampiran = 1;
		$status_explanation = 1;
		$sql = "select id_efektifitas, nama, need_lampiran, need_explanation from mt_risk_efektifitas";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		foreach($rows as $r){
			$ret[$r['id_efektifitas']] = $r;
		}

		return $ret;
	}
}
