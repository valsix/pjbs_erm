<?php class Mt_sdm_unitModel extends _Model{
	public $table = "mt_sdm_unit";
	public $pk = "table_code";
	public $label = "'['||trim(table_code)||'] '||table_desc";
	function __construct(){
		parent::__construct();
	}
}