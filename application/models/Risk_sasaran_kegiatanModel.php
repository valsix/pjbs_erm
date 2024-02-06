<?php class Risk_sasaran_kegiatanModel extends _Model{
	public $table = "risk_sasaran_kegiatan";
	public $pk = "id_sasaran_kegiatan";
	function __construct(){
		parent::__construct();
	}


	function SqlCombo($id_sasaran_strategis=null){
		$where = ' where 1=1 ';

		if($id_sasaran_strategis)
			$where .= " and id_sasaran_strategis = ".$this->conn->escape($id_sasaran_strategis);

		return "select {$this->pk} as key, {$this->label} as val from {$this->table} $where order by key";
	}

	function GetCombo($id_sasaran_strategis=null){
		
		if(!$id_sasaran_strategis)
			return array();

		$sql = $this->SqlCombo($id_sasaran_strategis);
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['key']] = $r['val'];
		}
		return $data;
	}
}