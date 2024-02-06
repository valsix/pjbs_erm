<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('lib/synology-api/vendor/autoload.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */
  class settingSynology{

	
	var $SYNOLOGY_URL; 
	var $SYNOLOGY_USER;
	var $SYNOLOGY_PASS;
	var $SYNOLOGY_FOLDER;
	var $synology;

    /******************** CONSTRUCTOR **************************************/

    function __construct() {
		$CI =& get_instance();
		$this->SYNOLOGY_URL 	= $CI->config->item("SYNOLOGY_URL");
		$this->SYNOLOGY_USER 	= $CI->config->item("SYNOLOGY_USER");
		$this->SYNOLOGY_PASS 	= $CI->config->item("SYNOLOGY_PASS");
		$this->SYNOLOGY_FOLDER 	= $CI->config->item("SYNOLOGY_FOLDER");

		$this->synology = new Synology\Applications\FileStation($this->SYNOLOGY_URL, 443, 'https', 2);
		$this->synology->connect($this->SYNOLOGY_USER, $this->SYNOLOGY_PASS, 'default');
    }
	
	public function createFolder($folderName, $subFolder="")
	{

		$synologyClass = $this->synology;
		$result = $synologyClass->createFolder($this->SYNOLOGY_FOLDER.(($subFolder) == "" ? "" : "/".$subFolder), $folderName);

		$result = json_decode(json_encode($result), true);

		if(empty($result))
			return false;
		else
			return $result["folders"][0]["isdir"]; 
	}



	public function uploadFile($localPath, $subFolder="", $renameFile="")
	{

		$synologyClass = $this->synology;
		$result = $synologyClass->upload($synologyClass->getSessionId(), $this->SYNOLOGY_FOLDER.(($subFolder) == "" ? "" : "/".$subFolder), $localPath, $renameFile);
		
		if(empty($result))
			return false;
		else
			return true; 
	}

	public function viewFile($filename, $subFolder="")
	{
		$synologyClass = $this->synology;
		
		$file = $synologyClass->download($this->SYNOLOGY_FOLDER.(($subFolder) == "" ? "" : "/".$subFolder).'/'.$filename);
		header('Content-Type: '.$this->get_mime_type($filename));
		header('Content-Length: ' . strlen($file));
		header('Content-Disposition: inline; filename="'.$filename.'"');
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');
		ini_set('zlib.output_compression','0');

		die($file);
		exit;
	}


	function get_mime_type($filename) {
	    $idx = explode( '.', $filename );
	    $count_explode = count($idx);
	    $idx = strtolower($idx[$count_explode-1]);

	    $mimet = array( 
	        'txt' => 'text/plain',
	        'htm' => 'text/html',
	        'html' => 'text/html',
	        'php' => 'text/html',
	        'css' => 'text/css',
	        'js' => 'application/javascript',
	        'json' => 'application/json',
	        'xml' => 'application/xml',
	        'swf' => 'application/x-shockwave-flash',
	        'flv' => 'video/x-flv',

	        // images
	        'png' => 'image/png',
	        'jpe' => 'image/jpeg',
	        'jpeg' => 'image/jpeg',
	        'jpg' => 'image/jpeg',
	        'gif' => 'image/gif',
	        'bmp' => 'image/bmp',
	        'ico' => 'image/vnd.microsoft.icon',
	        'tiff' => 'image/tiff',
	        'tif' => 'image/tiff',
	        'svg' => 'image/svg+xml',
	        'svgz' => 'image/svg+xml',

	        // archives
	        'zip' => 'application/zip',
	        'rar' => 'application/x-rar-compressed',
	        'exe' => 'application/x-msdownload',
	        'msi' => 'application/x-msdownload',
	        'cab' => 'application/vnd.ms-cab-compressed',

	        // audio/video
	        'mp3' => 'audio/mpeg',
	        'qt' => 'video/quicktime',
	        'mov' => 'video/quicktime',

	        // adobe
	        'pdf' => 'application/pdf',
	        'psd' => 'image/vnd.adobe.photoshop',
	        'ai' => 'application/postscript',
	        'eps' => 'application/postscript',
	        'ps' => 'application/postscript',

	        // ms office
	        'doc' => 'application/msword',
	        'rtf' => 'application/rtf',
	        'xls' => 'application/vnd.ms-excel',
	        'ppt' => 'application/vnd.ms-powerpoint',
	        'docx' => 'application/msword',
	        'xlsx' => 'application/vnd.ms-excel',
	        'pptx' => 'application/vnd.ms-powerpoint',


	        // open office
	        'odt' => 'application/vnd.oasis.opendocument.text',
	        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	    );

	    if (isset( $mimet[$idx] )) {
	     return $mimet[$idx];
	    } else {
	     return 'application/octet-stream';
	    }
	 }


}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $synologyAPI = new settingSynology();

?>