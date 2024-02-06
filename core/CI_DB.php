<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_DB extends TEMP_DB {
	private $conn = false;
	private $sql = false;
	private $rs = false;

	public $sysTimeStamp = "sysdate";
	public $debug = false;
	public $debug_str = false;

	public function __construct($params)
	{
		parent::__construct($params);
	}

	public function simple_query($sql){
		$display_error = ini_get("display_errors");
		if(strtolower($display_error)=='1' or strtolower($display_error)=='on' ){
			ini_set("display_errors","off");
		}

		$ret = parent::simple_query($sql);

		if(strtolower($display_error)=='1' or strtolower($display_error)=='on' ){
			ini_set("display_errors","on");
		}

		return $ret;
	}

	public function query($sql, $binds = FALSE, $return_object = NULL)
	{
		if ($sql === '')
		{
			log_message('error', 'Invalid query: '.$sql);
			return ($this->db_debug) ? $this->display_error('db_invalid_query') : FALSE;
		}
		elseif ( ! is_bool($return_object))
		{
			$return_object = ! $this->is_write_type($sql);
		}

		// Verify table prefix and replace if necessary
		if ($this->dbprefix !== '' && $this->swap_pre !== '' && $this->dbprefix !== $this->swap_pre)
		{
			$sql = preg_replace('/(\W)'.$this->swap_pre.'(\S+?)/', '\\1'.$this->dbprefix.'\\2', $sql);
		}

		// Compile binds if needed
		if ($binds !== FALSE)
		{
			$sql = $this->compile_binds($sql, $binds);
		}

		// Is query caching enabled? If the query is a "read type"
		// we will load the caching class and return the previously
		// cached query if it exists
		if ($this->cache_on === TRUE && $return_object === TRUE && $this->_cache_init())
		{
			$this->load_rdriver();
			if (FALSE !== ($cache = $this->CACHE->read($sql)))
			{
				return $cache;
			}
		}

		// Save the query for debugging
		if ($this->save_queries === TRUE)
		{
			$this->queries[] = $sql;
		}

		if($this->debug){
			echo "<hr/>SQL : ".$sql."<br/>";
		}

		// Start the Query Timer
		$time_start = microtime(TRUE);

		// Run the Query
		if (FALSE === ($this->result_id = $this->simple_query($sql)))
		{
			if ($this->save_queries === TRUE)
			{
				$this->query_times[] = 0;
			}

			// This will trigger a rollback if transactions are being used
			if ($this->_trans_depth !== 0)
			{
				$this->_trans_status = FALSE;
			}

			// Grab the error now, as we might run some additional queries before displaying the error
			$error = $this->error();

	

			if($this->debug){
				echo "<span style='color:red'>ERROR SQL : ".$error['message'] ."</span>";
			}

			if($this->save_queries === true && $error){
				$k=key($this->queries);
				$this->errors[$k] = $error;
			}

			$status = ini_get("display_errors");
			if((strtolower($status) == 'on' or $status == '1') && !$this->debug && $this->db_debug){
				// We call this function in order to roll-back queries
				// if transactions are enabled. If we don't call this here
				// the error message will trigger an exit, causing the
				// transactions to remain in limbo.
				while ($this->_trans_depth !== 0)
				{
					$trans_depth = $this->_trans_depth;
					$this->trans_complete();
					if ($trans_depth === $this->_trans_depth)
					{
						log_message('error', 'Database: Failure during an automated transaction commit/rollback!');
						break;
					}
				}

				// Display errors
				return $this->display_error(array('Error Number: '.$error['code'], $error['message'], $sql));
			}

			return FALSE;
		}

		// Stop and aggregate the query time results
		$time_end = microtime(TRUE);
		$this->benchmark += $time_end - $time_start;

		if ($this->save_queries === TRUE)
		{
			$this->query_times[] = $time_end - $time_start;
		}

		// Increment the query counter
		$this->query_count++;

		// Will we have a result object instantiated? If not - we'll simply return TRUE
		if ($return_object !== TRUE)
		{
			// If caching is enabled we'll auto-cleanup any existing files related to this particular URI
			if ($this->cache_on === TRUE && $this->cache_autodel === TRUE && $this->_cache_init())
			{
				$this->CACHE->delete();
			}

			return TRUE;
		}

		// Load and instantiate the result driver
		$driver		= $this->load_rdriver();

		$extenddriver = str_replace("CI_", config_item('subclass_prefix'), $driver);
		$pathextenddriver = APPPATH."/core/".$extenddriver.".php";

		if(file_exists($pathextenddriver)){
			
			if(!class_exists($extenddriver))
				include $pathextenddriver;

			$RES = new $extenddriver($this);
		}
		else
			$RES = new $driver($this);

		// Is query caching enabled? If so, we'll serialize the
		// result object and save it to a cache file.
		if ($this->cache_on === TRUE && $this->_cache_init())
		{
			// We'll create a new instance of the result object
			// only without the platform specific driver since
			// we can't use it with cached data (the query result
			// resource ID won't be any good once we've cached the
			// result object, so we'll have to compile the data
			// and save it)
			$CR = new CI_DB_result($this);
			$CR->result_object	= $RES->result_object();
			$CR->result_array	= $RES->result_array();
			$CR->num_rows		= $RES->num_rows();

			// Reset these since cached objects can not utilize resource IDs.
			$CR->conn_id		= NULL;
			$CR->result_id		= NULL;

			$this->CACHE->write($sql, $CR);
		}

		return $RES;
	}

    public function Execute($sql){
    	return $this->query($sql);
    }

    public function PageArray($sql, $limit, $page){
    	if(!$sql)
    		return false;

    	$start = ($page-1)*$limit;

    	$this->qb_limit = $limit;
    	$this->qb_offset = $start;

    	$sql = $this->_limit($sql);

    	return $this->GetArray($sql);
    }

	public function GetOne($sql){
		$rs = $this->query($sql);

		if(!$rs)
			return false;

		$row = $rs->row_array();
		if(!$row)
			return false;

		foreach ($row as $key => $value) {
			return $value;
		}

		return false;
	}
	
	public function GetRow($sql){
		$rs = $this->query($sql);
		
		if(!$rs)
			return false;

		$row = $rs->row_array();

		return $row;
	}
	
	public function GetArray($sql=false){
		$rs = $this->query($sql);
		
		if(!$rs)
			return false;

		return $row = $rs->result_array();
	}

	public function GetList($sql=false){
		$rs = $this->query($sql);
		
		if(!$rs)
			return false;

		$rows = $rs->result_array();

		$ret = array();

		foreach ($rows as $r) {
			if($r['key'])
				$ret[$r['key']] = $r['val'];
			else
				$ret[] = $r['val'];
		}

		return $ret;
	}
	public function GetListStr($sql=false){
		$ret = $this->GetList($sql);

		if(count($ret))
			return implode(",", $ret);

		return false;
	}

	public function GetKeys($rows, $key){
		$ret = array();
		foreach($rows as $row){
			$ret[] = $row[$key];
		}
		return $ret;
	}

	public function GetRows($sql){
		return $this->GetArray($sql);
	}

	public function Structure($rs){
		$rows = $this->field_data($rs);

		$ret = array();
		foreach ($rows as $val) {
			$ret[$val->name]=array(
				'name'=>$val->name,
				'max_length	'=>$val->max_length,
				'type'=>$val->type,
			);
		}

		return $ret;
	}

	public function InsertSQL($table=false, $arr_data=array()){
		if(!count($arr_data) or !$table)
			return false;

		$cols = $this->Structure($table);

		$ci = get_instance();
		$date_format = $ci->config->item("date_format");

		$keys = array();
		$values = array();
		foreach ($cols as $key => $value) {
			if($arr_data[$key]===null)
				continue;


			if(!$arr_data[$key] && $value['type']=='DATE')
				continue;

			switch ($value['type']){
				case "DATE":
					$values[] = "to_date(".$this->qstr($arr_data[$key]).",'$date_format')";
				break;
				default:
					$values[] = $this->qstr($arr_data[$key]);
				break;
			}
			
			$keys[] = $key;

		}

		if(!count($keys))
			return false;

		$columnstr = implode(",", $keys);
		$valuestr = implode(",", $values);

		$sql = "insert into $table ($columnstr) values ($valuestr)";
		
		return $sql;
	}

	public function UpdateSQL($table=false, $arr_data=array(), $strcondition=false){
		if(!count($arr_data) or !$table)
			return false;

		$cols = $this->Structure($table);

		$ci = get_instance();
		$date_format = $ci->config->item("date_format");

		if($strcondition)
			$strcondition = " where ".$strcondition;

		$updatearr = array();
		foreach ($cols as $key => $value) {
			if($arr_data[$key]===null)
				continue;

			if(!$arr_data[$key] && $value['type']=='DATE')
				$updatearr[] = $key." = null ";
			else{
				switch ($value['type']){
					case "DATE":
						$updatearr[] = $key." = to_date(".$this->qstr($arr_data[$key]).",'$date_format')";
					break;
					default:
						$updatearr[] = $key." = ". $this->qstr($arr_data[$key]);
					break;
				}
			}
			$keys[] = $key;
		}

		if(!count($updatearr))
			return false;

		$updatestr = implode(",", $updatearr);

		$sql = "update $table set $updatestr $strcondition";

		return $sql;
	}

	public function goInsert($table=false, $arr_data=array()){

		$sql = $this->InsertSQL($table, $arr_data);
		if(!$sql)
			return false;

		return $this->query($sql);
	}

	public function goUpdate($table=false, $arr_data=array()){

		$sql = $this->UpdateSQL($table, $arr_data);
		if(!$sql)
			return false;
		
		return $this->query($sql);
	}

	public function qstr($val){
		if($val===null)
			return 'null';
		
		if(!is_string($val))
			return "'".$val."'";

		$val = $this->escape($val);

		if(preg_match('/^\'{{+.+}}\'$/', $val)){
			$val = rtrim(ltrim($val,'\'{{'),'}}\'');
		}

		return $val;
	}

	public function escape_string(&$data){

		$temp = $data;
		if(is_array($temp)){
			foreach ($temp as $key => $value) {
				$this->escape_string($value);
				$data[$key] = $value;
			}
		}else{
			$data = trim($this->escape($temp),"'");
		}
		return $data;
	}

	public function StartTrans(){
		return $this->trans_start();
	}

	public function CompleteTrans(){
		$this->trans_complete();
	}

	public function GetError(){
		return $this->error;
	}

	public function GetDebugStr(){
		return $this->debug_str;
	}
}