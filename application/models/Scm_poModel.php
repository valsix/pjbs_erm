<?php class Scm_poModel extends _Model{
	public $table = "scm_po";
	public $pk = "id_scm_pr";
	function __construct(){
		parent::__construct();
	}

	public function GetByPk($id){
		if(!$id){
			return array();
		}
		$sql = "select a.*, b.no_po, b.status as status_bayar 
		from ".$this->table." a
		join status_vo b on a.nomor = b.no_spk
		where {$this->pk} = ".$this->conn->qstr($id);
		$ret = $this->conn->GetRow($sql);

		if(!$ret)
			$ret = array();

		return $ret;
	}
}