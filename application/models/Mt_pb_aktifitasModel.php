<?php class Mt_pb_aktifitasModel extends _Model{
	public $table = "mt_pb_aktifitas";
	public $pk = "id_aktifitas";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}

	function SqlCombo($id_nama_proses=null){
		$where = '';
		if($id_nama_proses)
			$where = " where id_nama_proses = $id_nama_proses ";

		return "select {$this->pk} as key, {$this->label} as val from {$this->table} $where order by key";
	}

	function GetCombo($id_nama_proses=null){
		$sql = $this->SqlCombo($id_nama_proses);
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['key']] = $r['val'];
		}
		return $data;
	}
}