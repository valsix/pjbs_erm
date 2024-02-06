<?php
class _DB_oci8_driver extends CI_DB_oci8_driver {
	
	public function __construct($params)
	{
		parent::__construct($params);
	}


	public function field_data($table)
	{
		if (strpos($table, '.') !== FALSE)
		{
			sscanf($table, '%[^.].%s', $owner, $table);
		}
		else
		{
			$owner = $this->username;
		}

		$sql = 'SELECT COLUMN_NAME, DATA_TYPE, CHAR_LENGTH, DATA_PRECISION, DATA_LENGTH, DATA_DEFAULT, NULLABLE
			FROM ALL_TAB_COLUMNS
			WHERE UPPER(OWNER) = '.$this->escape(strtoupper($owner)).'
				AND UPPER(TABLE_NAME) = '.$this->escape(strtoupper($table));

		if (($query = $this->query($sql)) === FALSE)
		{
			return FALSE;
		}
		$query = $query->result_object();

		$retval = array();
		for ($i = 0, $c = count($query); $i < $c; $i++)
		{
			$retval[$i]			= new stdClass();
			$retval[$i]->name		= strtolower($query[$i]->column_name);
			$retval[$i]->type		= $query[$i]->data_type;

			$length = ($query[$i]->char_length > 0)
				? $query[$i]->char_length : $query[$i]->data_precision;
			if ($length === null)
			{
				$length = $query[$i]->data_length;
			}
			$retval[$i]->max_length		= $length;

			$default = $query[$i]->data_default;
			if ($default === null && $query[$i]->nullable === 'n')
			{
				$default = '';
			}
			$retval[$i]->default = $default;
		}

		return $retval;
	}
}