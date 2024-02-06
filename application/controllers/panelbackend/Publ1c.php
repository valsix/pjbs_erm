<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Publ1c extends _adminController{

	public $private = false;
	public function __construct(){
		parent::__construct();
	}

	public function index($page=1){
		echo "update risk_risiko set status_risiko = '0' where status_risiko is null;";
		$this->conn->Execute("update risk_risiko set status_risiko = '0' where status_risiko is null");
		die();
	}

	public function send_email(){
		$subject = $_REQUEST['subject'];
		$body = $_REQUEST['body'];
		$recipients = $_REQUEST['recipients'];

		if(!$subject || !$body || !$recipients)
			die();

		$config = $this->config->item('email_config');
		$this->load->library('email',$config);

		$this->email->from($config['from'],$config['fromlabel']);
		$this->email->reply_to($config['reply_to'],$config['replylabel']);
		if($config['recipients'])
			$this->email->to($config['recipients']);
		else
			$this->email->to($recipients);
		$this->email->subject($subject);
		$this->email->message($body);

		if($config['extra'])
			$this->email->bcc($config['extra']);

		$this->email->send();

		if($this->email->print_debugger()){
			show_error($this->email->print_debugger());
			file_put_contents(APPPATH."./logs/email_error", $this->email->print_debugger()."\n",FILE_APPEND);
		}
	}

	public function test(){
		$subject = $_REQUEST['subject'];
		$body = $_REQUEST['body'];
		$recipients = $_REQUEST['recipients'];

		if(!$subject || !$body || !$recipients)
			die();

		$config = $this->config->item('email_config');

		$config['smtp_host'] = "tls://mail.ptpjbs.com";
		$config['smtp_port'] = "465";
		$config['smtp_pass'] = "123456";

		$this->load->library('email',$config);

		$this->email->from($config['from'],$config['fromlabel']);
		$this->email->reply_to($config['reply_to'],$config['replylabel']);
		if($config['recipients'])
			$this->email->to($config['recipients']);
		else
			$this->email->to($recipients);
		$this->email->subject($subject);
		$this->email->message($body);

		if($config['extra'])
			$this->email->bcc($config['extra']);

		$this->email->send();

		if($this->email->print_debugger()){
			show_error($this->email->print_debugger());
			file_put_contents(APPPATH."./logs/email_error", $this->email->print_debugger()."\n",FILE_APPEND);
		}
	}
}
