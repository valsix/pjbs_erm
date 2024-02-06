<?php class Risk_kegiatanModel extends _Model{
	public $table = "risk_kegiatan";
	public $pk = "id_kegiatan";
	function __construct(){
		parent::__construct();
	}



	function SqlCombo($id_scorecard=null){
		$where = '';
		if($id_scorecard)
			$where = " where id_scorecard = $id_scorecard ";

		return "select {$this->pk} as key, {$this->label} as val from {$this->table} $where order by key";
	}

	function GetCombo($id_scorecard=null){
		$sql = $this->SqlCombo($id_scorecard);
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['key']] = $r['val'];
		}
		return $data;
	}
}