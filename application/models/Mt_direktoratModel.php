<?php class Mt_direktoratModel extends _Model{
	public $table = "mt_direktorat";
	public $pk = "id_direktorat";
	function __construct(){
		parent::__construct();
	}

	function GetCombo($page_ctrl=null){
		
		$id_parent = null;

		if(Access("view_all_direktorat",$page_ctrl)){
			$sql = "select * from mt_direktorat order by id_direktorat";
		}else{

			$id_parent = $_SESSION[SESSION_APP]['direktorat'];
			$childarr = $_SESSION[SESSION_APP]['child_direktorat'];

			if(!$childarr)
				$childarr = array();

			$this->conn->escape_string($childarr);

			$childastr = "'".implode("','", $childarr)."'";

			$sql = "select * from mt_direktorat where ID_DIREKTORAT in ($childastr)  order by id_direktorat";
		}

		$rows = $this->conn->GetArray($sql);

		$ret = array();
		
		$this->GenerateTree($rows, "id_parent_direktorat", "id_direktorat", "nama", $ret, $id_parent);

		$return = array(''=>'-pilih-');
		foreach ($ret as $r) {
			$return[$r['id_direktorat']] = $r['nama'];
		}

		return $return;
	}
}