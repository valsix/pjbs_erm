<?php class Mt_sdm_subditModel extends _Model{
	public $table = "mt_sdm_subdit";
	public $pk = "table_code";
	public $label = "table_desc";
	function __construct(){
		parent::__construct();
	}

	function GetComboByDirektorat($id_direktorat=null){
		if(!$id_direktorat)
			return array();

		$id_direktorat = trim($this->conn->escape_string($id_direktorat));

		$sql = "select {$this->pk} as key, {$this->label} as val from {$this->table} where trim(table_code) like '$id_direktorat%' order by key";

		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['key']] = $r['val'];
		}
		return $data;
	}
}