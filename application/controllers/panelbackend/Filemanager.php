<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Filemanager extends _adminController{

	private $root;
	private $upload_dir;

	private $base_url;
	private $thumbs_dir;
	private $thumbs_ico;
	private $MaxSizeUpload;
	private $image_max_width;
	private $image_max_height;
	private $image_resizing;
	private $image_width;
	private $image_height;
	private $delete_file;
	private $create_folder;
	private $delete_folder;
	private $upload_files;
	private $ext_img;
	private $ext_file;
	private $ext_video;
	private $ext_music;
	private $ext_misc;
	private $ext;

	public function __construct(){
		parent::__construct();

		$config = $this->config->item('file_manager');

		$this->data['root'] = $this->root = $config["root"];
		$this->data['upload_dir'] = $this->upload_dir = $config['upload_dir'];
		$this->data['thumbs_dir'] = $this->thumbs_dir = $config['thumbs_dir'];
		$this->data['thumbs_ico'] = $this->thumbs_ico = $config['thumbs_ico'];
		$this->data['base_url'] = $this->base_url = host();
		$this->data['MaxSizeUpload'] = $this->MaxSizeUpload = $config['max_size_upload'];
		$this->data['image_max_width'] = $this->image_max_width = file_manager_image('image_max_width');
		$this->data['image_max_height'] = $this->image_max_height = file_manager_image('image_max_height');
		$this->data['image_resizing'] = $this->image_resizing = file_manager_image('image_resizing');
		$this->data['image_width'] = $this->image_width = file_manager_image('image_width');
		$this->data['image_height'] = $this->image_height = file_manager_image('image_height');
		$this->data['delete_file'] = $this->delete_file = file_manager_permit('delete_file');
		$this->data['create_folder'] = $this->create_folder = file_manager_permit('create_folder');
		$this->data['delete_folder'] = $this->delete_folder = file_manager_permit('delete_folder');
		$this->data['upload_files'] = $this->upload_files = file_manager_permit('upload_files');
		$this->data['ext_img'] = $this->ext_img = file_manager_ext('ext_img');
		$this->data['ext_file'] = $this->ext_file = file_manager_ext('ext_file');
		$this->data['ext_video'] = $this->ext_video = file_manager_ext('ext_video');
		$this->data['ext_music'] = $this->ext_music = file_manager_ext('ext_music');
		$this->data['ext_misc'] = $this->ext_misc = file_manager_ext('ext_misc');
		$this->data['ext'] = $this->data['ext_img'];
		if($_GET['type']==2 || $_GET['type']==0){ 
			$this->data['ext'] = $this->ext = file_manager_ext();
		}
		$typearrexttemp = array();
		foreach ($this->data['ext'] as $key => $value) {
			# code...
			$ext = trim($value,'.');
			$mime = mime($ext);
			if(!is_array($mime)){
				$mime = array($mime);
			}
			$typearrexttemp = array_merge($typearrexttemp,$mime);
		}
		$this->data['typearr'] = $typearrexttemp;
	}

	public function Index($page=0){
		$this->data['multipleselect'] = $_GET['multipleselect'];
		$this->PartialView("panelbackend/filemanager");
	}

	public function Upload(){
		 
		$storeFolder = $_POST['path'];
		 
		if (!empty($_FILES) && $this->upload_files) {
		     
		    $tempFile = $_FILES['file']['tmp_name'];   
		    $type = $_FILES['file']['type'];
		    
			if(!in_array($type, $this->data['typearr'])){
				return false;
			}
		      
		    $targetPath = $this->root.$this->upload_dir.$storeFolder; 
		    $targetPathThumb = $this->root.$this->thumbs_dir. $storeFolder; 

		    if(!file_exists($this->root.$this->upload_dir))
		    	mkdir($this->root.$this->upload_dir);

		    if(!file_exists($this->root.$this->thumbs_dir))
		    	mkdir($this->root.$this->thumbs_dir);
		     
		    $targetFile =  $targetPath. $_FILES['file']['name']; 
		    $targetFileThumb =  $targetPathThumb. $_FILES['file']['name']; 
		    move_uploaded_file($tempFile,$targetFile);
		    
		    if(in_array(substr(strrchr($_FILES['file']['name'],'.'),1),$this->ext_img)) 
		    	$is_img=true;
		    else 
		    	$is_img=false;

		    if($is_img){
		    	echo $targetFile."=>".$targetFileThumb;
				create_img_gd($targetFile, $targetFileThumb, 230, 136);

				$imginfo =getimagesize($targetFile);
				$srcWidth = $imginfo[0];
				$srcHeight = $imginfo[1];
				
				if($this->image_resizing){
					
				    if($this->image_width==0){
						if($this->image_height==0){
						    $this->image_width=$srcWidth;
						    $this->image_height =$srcHeight;
						}else{
						    $this->image_width=$this->image_height*$srcWidth/$srcHeight;
					    }
				    }elseif($this->image_height==0){
						$this->image_height =$this->image_width*$srcHeight/$srcWidth;
				    }
				    $srcWidth=$this->image_width;
				    $srcHeight=$this->image_height;
				    create_img_gd($targetFile, $targetFile, $this->image_width, $this->image_height);
				}
				
				//max resizing limit control
				$resize=false;
				if($this->image_max_width!=0 && $srcWidth >$image_max_width){
				    $resize=true;
				    $srcHeight=$this->image_max_width*$srcHeight/$srcWidth;
				    $srcWidth=$this->image_max_width;
				}
				
				if($this->image_max_height!=0 && $srcHeight >$image_max_height){
				    $resize=true;
				    $srcWidth =$this->image_max_height*$srcWidth/$srcHeight;
				    $srcHeight =$this->image_max_height;
				}
				if($resize)
				    create_img_gd($targetFile, $targetFile, $srcWidth, $srcHeight);	
			    
		    }
		}
		if(isset($_POST['submit'])){
		    $query = http_build_query(array(
		        'type'      => $_POST['type'],
		        'lang'      => $_POST['lang'],
		        'subfolder' => $_POST['subfolder'],
		        'popup'     => $_POST['popup'],
		        'field_id'  => $_POST['field_id'],
		        'editor'    => $_POST['editor'],
		        'fldr'      => $_POST['fldr'],
		    ));
			redirect("$this->page_ctrl?".$query);
		}
	}

	public function Download(){
		$path=$this->root.$_POST['path'];
		$name=$_POST['name'];

		if(strpos($path,$this->upload_dir)===FALSE) die('wrong path');

		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header("Content-Type: application/octet-stream");
		header("Content-Length: " .(string)(filesize($path)) );
		header('Content-Disposition: attachment; filename="'.($name).'"');
		readfile($path);
		exit;
	}

	public function Createfolder(){
		$file_path=$this->root.$this->upload_dir.$_POST['path'];
		$thumbs_path=$this->root.$this->thumbs_dir.$_POST['path'];

		if(strpos($file_path, $this->upload_dir)===false) die('wrong path');

		create_folder($file_path);
		create_folder($thumbs_path);
	}

	public function Deletefolder(){
		$file_path=$this->root.$this->upload_dir.$_POST['path'];
		$thumbs_path=$this->root.$this->thumbs_dir.$_POST['path'];

		if(!file_exists($file_path) and !file_exists($thumbs_path)) die('wrong path');

		delete_folder($file_path);
		delete_folder($thumbs_path);
	}

	public function Deletefile(){
		$file_path=$this->root.$this->upload_dir.$_POST['path'];
		$thumbs_path=$this->root.$this->thumbs_dir.$_POST['path'];

		if(!file_exists($file_path) and !file_exists($thumbs_path)) die('wrong path');

		unlink($file_path);
		unlink($thumbs_path);
	}

}