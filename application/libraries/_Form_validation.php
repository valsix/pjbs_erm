<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/form_validation.html
 */
class _Form_validation extends CI_Form_validation{

	public function __construct($rules = array())
	{
		parent::__construct($rules);
	}

	public function is_unique($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);

		$pk = $this->CI->pk;

		$v_pk = $this->CI->data['idpk'];

		if($v_pk!==null)
			$v_pk = $this->CI->data['row'][$pk];

		$filter = array($field => $str);
		if($v_pk)
			$filter += array(strtoupper($pk)." !=" => $v_pk);

		return isset($this->CI->db)
			? ($this->CI->db->limit(1)->get_where($table, $filter)->num_rows() === 0)
			: FALSE;
	}
	/**
	 * Numeric
	 *
	 * @param	string
	 * @return	bool
	 */
	public function numeric($str)
	{
		$str = Rupiah2Number($str);
		
		return (bool) preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $str);

	}
}