<?php class Public_sys_groupModel extends _Model{
	public $table = "public_sys_group";
	public $pk = "group_id";
	public $label = "name";
	function __construct(){
		parent::__construct();
	}
}