<?php
class _DB_oci8_result extends CI_DB_oci8_result {

	public function __construct(&$driver_object)
	{
		parent::__construct($driver_object);
	}

	protected function _fetch_assoc()
	{
		$id = ($this->curs_id) ? $this->curs_id : $this->stmt_id;
		$row = oci_fetch_assoc($id);

		if(!$row)
			return $row;

		$ret = array();

		foreach ($row as $key => $value) {
			$ret[strtolower($key)] = trim($value);
		}

		return $ret;
	}

	protected function _fetch_object($class_name = 'stdClass')
	{
		$row = ($this->curs_id)
			? oci_fetch_object($this->curs_id)
			: oci_fetch_object($this->stmt_id);

		if($row){
			$temp = array();
			foreach ($row as $key => $value) {
				$temp[$key] = trim($value);
			}

			foreach ($temp as $k=>$v) {
				$row->{strtolower($k)} = $v;
				unset($row->{$k});
			}
		}

		if ($class_name === 'stdClass' OR ! $row)
		{
			return $row;
		}

		$class_name = new $class_name();
		foreach ($row as $key => $value)
		{
			$class_name->$key = $value;
		}

		return $class_name;
	}
}