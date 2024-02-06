<?php class Mt_issueModel extends _Model{
	public $table = "mt_issue";
	public $pk = "id_issue";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}