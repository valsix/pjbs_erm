<?php class Mt_status_progress_grcModel extends _Model{
	public $table = "mt_status_progress_grc";
	public $pk = "id_status_progress_grc";
	function __construct(){
		parent::__construct();
	}

	// function SqlCombo(){
	// 	return "select {$this->pk} as key, prosentase || '% ' ||{$this->label} as val from {$this->table} order by prosentase asc";
	// }

	// function GetCombo(){
	// 	$sql = $this->SqlCombo();
	// 	$rows = $this->conn->GetArray($sql);
	// 	$data = array(''=>'-pilih-');
	// 	foreach ($rows as $r) {
	// 		$data[$r['key']] = $r['val'];
	// 	}
	// 	return $data;
	// }
}