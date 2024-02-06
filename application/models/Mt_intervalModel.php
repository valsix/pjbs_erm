<?php class Mt_intervalModel extends _Model{
	public $table = "mt_interval";
	public $pk = "id_interval";
	function __construct(){
		parent::__construct();
	}

	function GetCombo(){
		$sql = "select * from mt_interval order by id_interval";
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['id_interval']] = $r['nama'];
		}
		return $data;
	}
}
