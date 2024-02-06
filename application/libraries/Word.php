<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once  APPPATH.'libraries/PhpOffice/Autoloader.php';
use PhpOffice\Autoloader as Autoloader;
Autoloader::register();

class Word extends Autoloader {
	public $templateProcessor=null;
	private $filetemp=null;
	function template($filetemp=null){
		$this->filetemp = $filetemp;
		$this->templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filetemp);
	}

	function phpword(){
		return new \PhpOffice\PhpWord\PhpWord();
	}

	function download($filename=null){
		if(!$filename)
			$filename = $this->filetemp;
		
		$file = microtime();
		$this->templateProcessor->saveAs($file);
		// ob_clean();

		header("Content-Description: File Transfer");
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');

		echo file_get_contents($file);
		unlink($file);
	}	
}