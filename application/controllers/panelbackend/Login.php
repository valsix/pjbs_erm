<?php
class Login extends _Controller{
	function __construct(){
		$this->xss_clean = true;
		parent::__construct();
	}
	function Index(){
		$this->helper("s");
		
		if($_SESSION[SESSION_APP]['login'])
		{
			$this->log("Login");
			if($_SESSION[SESSION_APP]['curr_page']){
				redirect($_SESSION[SESSION_APP]['curr_page']);
			}else{
				redirect('panelbackend/home');
			}
		}
		else
		{
			$this->SetToken();
			$this->PartialView('panelbackend/login');
		}
	}
	function Auth(){
		// print_r('cek token dari setToken');
		// print_r($this->data);

		// $this->CekToken();

		$this->load->model("AuthModel","auth");

		echo json_encode($this->auth->Login($this->post['username'],$this->post['password']));
		$this->log("Login");
	}

	public function autologin()
    {
		$this->load->model("AuthModel","auth");
        //$this->load->model('usermodel');
        $reqGroupId = $this->get["reqGroupId"];
        if($reqGroupId == "")
            $respon = $this->auth->autoAuthenticate($this->get['reqUser'],$this->get['reqToken']);
        else
            $respon = $this->auth->autoGroupAuthenticate($this->get['reqUser'],$this->get['reqToken'], $reqGroupId);

		if($respon->RESPONSE == "1")
			redirect('panelbackend');   //Lemparkan ke halaman index user
		elseif($respon->RESPONSE == "PAGE")
		{
            /*$username=$this->get['reqUser'];
            $tgl=date('d-m-Y');
            $ip= $_SERVER['REMOTE_ADDR'];

            $data_nama = $this->usermodel->get_nama_karyawan($username);

            $data_counter = array(
	            'IP'=>$ip,
	            'NID'=>$username,
	            'NAMA'=>$data_nama,
	            'TANGGAL'=>$tgl
            );
            $this->usermodel->insert_hit_counter($data_counter);*/
	        echo "<script>
                top.location.href = 'http://portal.pjbservices.com/".$respon->RESPONSE_LINK."/?reqNID=".$respon->NID."&reqAplikasiId=".$respon->APLIKASI_ID."';
                </script>";
         }
         else
         {
            $_SESSION[SESSION_APP]['error_login'] = trim($respon->RESPONSE_MESSAGE,'.')." lewat <a href='http://portal.pjbservices.com'>portal.pjbservices.com</a>";
			redirect('panelbackend/login');
         } //End of else
    }
	function Logout(){
		$this->log("Login Out");
		$_SESSION[SESSION_APP]['login']=false;
		unset($_SESSION[SESSION_APP]);
		redirect('panelbackend');
	}
}
