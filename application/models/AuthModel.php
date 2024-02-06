<?php class AuthModel extends _Model{
	function __construct(){
		parent::__construct();
	}
	public function Login($usernamea="", $passworda="")
	{
		$username = $this->conn->qstr($usernamea);
		$password = $this->conn->qstr(sha1(md5($passworda)));
		$data = $this->GetRow("
		select * from public_sys_user
		where username=$username and password=$password
		and is_active = '1'
		");
		if($this->sso['auth_page'] && !$data['user_id']){
			return $this->autoLogin($usernamea, $passworda);
		}elseif($data)
		{
			$this->SetLogin($data);

			return array('success'=>'login berhasil');
		}
		return array('error'=>'login gagal');
	}

	public function LoginAs($user_id="")
	{
		$user_id = $this->conn->qstr($user_id);
		$data = $this->GetRow("
		select * from public_sys_user
		where user_id=$user_id
		and is_active = '1'
		");
		if($data)
		{

			$loginas = $_SESSION[SESSION_APP];
			unset($_SESSION[SESSION_APP]);
			$_SESSION[SESSION_APP]['loginas'] = $loginas;

			$this->SetLogin($data);

			return array('success'=>'login success');
		}
		return array('error'=>'login filed');
	}

	public function SqlTask(){

		$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
		$owner = $_SESSION[SESSION_APP]['pic'];
		$user_id = $_SESSION[SESSION_APP]['user_id'];

		if(!$owner)
			$owner = '0';

		$filterstatusarr = array();
		#notif untuk koordinator
		if($this->ci->Access("pengajuan","panelbackend/risk_risiko")){
			$filterstatusarr[] = " (t.id_status_pengajuan in (4,5) and s.owner = ".$this->conn->escape($owner).")";
		}
		#notif untuk owner
		if($this->ci->Access("penerusan","panelbackend/risk_risiko")){
			$filterstatusarr[] = " (t.id_status_pengajuan in (4,2,5) and s.owner = ".$this->conn->escape($owner).")";
		}
		#notif untuk reviewer
		if($this->ci->Access("persetujuan","panelbackend/risk_risiko")){
			$filterstatusarr[] = " t.id_status_pengajuan in (3,7)";
		}

		$filterstatusarr[] = " (untuk = ".$this->conn->escape($id_jabatan).")";

		if(($filterstatusarr)){
			$where .= " and (".implode(" or ", $filterstatusarr).")";
		}

		$where .= " and t.created_by <> ".$this->conn->escape($user_id);

		$sql = "
		from risk_task t
		left join risk_risiko r on t.id_risiko = r.id_risiko
		left join risk_scorecard s on r.id_scorecard = s.id_scorecard
		left join public_sys_user u on t.created_by = u.user_id
		left join public_sys_group g on t.group_id = g.group_id
		where 1=1 and t.is_pending != '1' ".$where;

		return $sql;
	}

	public function PenerimaByStatus($id_risiko,$id_status_pengajuan=null, $untuk=null){

		$where = "";

		if($untuk){
			$where .= " and u.id_jabatan = ".$this->conn->escape($untuk);
		}else{
			$owner = $this->conn->GetOne("select s.owner
				from risk_risiko r
				join risk_scorecard s on r.id_scorecard = s.id_scorecard
				where r.id_risiko=".$this->conn->escape($id_risiko));

			if($owner && $id_status_pengajuan!='3'){
				$bawahanarr = $this->GetChildJabatan($owner);

				$bawahanstr = "";
				if($bawahanarr)
					$bawahanstr = " or u.id_jabatan in (".implode(", ",$bawahanarr).")";

				$where .= " and (u.id_jabatan = '{$owner}' or a.name='view_all_direktorat' $bawahanstr)";
			}

			#notif untuk koordinator
			if($id_status_pengajuan=='5' or $id_status_pengajuan=='4')
				$where .= " and (a.name='pengajuan' or a.name='penerusan') ";

			#notif untuk owner
			if($id_status_pengajuan=='2')
				$where .= " and a.name='penerusan' ";

			#notif untuk reviewer
			if($id_status_pengajuan=='3' or $id_status_pengajuan=='7')
				$where .= " and a.name='persetujuan' ";
		}

		$user_id = $_SESSION[SESSION_APP]['user_id'];

		$where .= " and u.user_id <> ".$this->conn->escape($user_id);

		return $this->conn->GetArray("select distinct u.email
				from public_sys_group g
				join public_sys_group_menu gm on g.group_id=gm.group_id
				join public_sys_group_action ga on gm.group_menu_id = ga.group_menu_id
				join public_sys_menu m on gm.menu_id = m.menu_id
				join public_sys_action a on ga.action_id = a.action_id
				join public_sys_user u on g.group_id = u.group_id
				where m.url = 'panelbackend/risk_risiko' 
				and u.is_notification = '1' 
				and u.email is not null $where");
	}

	private function GetDisableTab($id_risiko){
		$sql = "select id_task as val ".$this->SqlTask()." and t.id_risiko = ".$this->conn->escape($id_risiko)." and rownum =1 ";

		$owner = $_SESSION[SESSION_APP]['pic'];


		$ctrl = $this->ci->ctrl;


		$cek = $this->conn->GetOne($sql);
		if(!$cek)
			return array();

		$data = array();

		$cek = $this->conn->GetListStr($sql." and status = '1' and t.id_status_pengajuan is not null");
		if($cek){
			$data[] = 'risk_mitigasi';
			$data[] = 'risk_evaluasi';

			if($ctrl=='risk_control'){
				$this->conn->Execute("update risk_task set status = '2' where id_task in ($cek)");

				$data = array();
				$data[] = 'risk_evaluasi';
			}

			return $data;
		}

		$cek = $this->conn->GetListStr($sql." and status = '2' and t.id_status_pengajuan is not null");
		if($cek){
			//$data[] = 'risk_evaluasi';

			if($ctrl=='risk_mitigasi'){
				$this->conn->Execute("update risk_task set status = '3' where id_task in ($cek) and untuk is null");
				$data = array();
			}

			return $data;
		}

		return $data;
	}

	private function GetDisableTabGrc($id_risiko){
		$sql = "select id_task as val ".$this->SqlTask()." and t.id_risiko = ".$this->conn->escape($id_risiko)." and rownum =1 ";

		$owner = $_SESSION[SESSION_APP]['pic'];


		$ctrl = $this->ci->ctrl;


		$cek = $this->conn->GetOne($sql);
		if(!$cek)
			return array();

		$data = array();

		$cek = $this->conn->GetListStr($sql." and status = '1' and t.id_status_pengajuan is not null");
		if($cek){
			$data[] = 'risk_mitigasi_grc';
			$data[] = 'risk_evaluasi_grc';

			if($ctrl=='risk_control_grc'){
				$this->conn->Execute("update risk_task set status = '2' where id_task in ($cek)");

				$data = array();
				$data[] = 'risk_evaluasi_grc';
			}

			return $data;
		}

		$cek = $this->conn->GetListStr($sql." and status = '2' and t.id_status_pengajuan is not null");
		if($cek){
			//$data[] = 'risk_evaluasi';

			if($ctrl=='risk_mitigasi_grc'){
				$this->conn->Execute("update risk_task set status = '3' where id_task in ($cek) and untuk is null");
				$data = array();
			}

			return $data;
		}

		return $data;
	}

	public function GetTask(){
		$ci = $this->ci;
    	$status_arr = $ci->data['mtstatusarr'];

		$sql = $this->SqlTask();

		$sql_content = "select * from (select 
		t.id_task,
		r.nama as nama_risiko, 
		t.id_status_pengajuan, 
		t.url, 
		t.status,
		t.deskripsi, 
		u.name as nama_user, 
		g.name as nama_group, 
		to_char(t.created_date,'YYYY-MM-DD HH:MI:SS') as created_date, 
		to_char(sysdate,'YYYY-MM-DD HH:MI:SS') as n 
		".$sql." order by id_task desc) a where rownum <= 10";


		$rows = $this->conn->GetArray($sql_content);

		/*
		1:draft
		2:diajukan ke owner
		3:diteruskan ke reviewer
		4:dikembalikan
		5:disetujui
		6:menunggu konfirmasi
		*/
		$iconarr = array(
			''=>"flag",
			'1'=>"short-text",
			'2'=>"flag",
			'3'=>"flag",
			'4'=>"backspace",
			'5'=>"done",
			'6'=>"flag",
		);

		$bgarr = array(
			''=>"orange",
			'1'=>"grey",
			'2'=>"orange",
			'3'=>"orange",
			'4'=>"red",
			'5'=>"green",
			'6'=>"orange",
		);

		$content = array();
		foreach ($rows as $r) {
			
			if((int)$r['status']==0)
	        	$info = "<b>".$r['nama_risiko']."</b><br/><i>".$r['deskripsi']."</i>";
	        else
	        	$info = $r['nama_risiko']."<br/><i>".$r['deskripsi']."</i>";

			$content[] = array(
					'bg'=>$bgarr[$r['id_status_pengajuan']],
					'icon'=>$iconarr[$r['id_status_pengajuan']],
					'info'=>$info,
					'time'=>waktu_lalu($r['created_date'], $r['n']),
					'url'=>"panelbackend/home/task/$r[id_task]", 
					'user'=>ucwords(strtolower($r['nama_user']))." (".ucwords(strtolower($r['nama_group'])).")"
				);
		}

		$sql_count = "select count(1) ".$sql." and t.status = '0' ";

		$count = $this->conn->GetOne($sql_count);


		$data = array(
			'count'=>$count,
			'content'=>$content
		);

		return $data;
	}

	private function SetLogin($data=array(),$tokenarr=array()){
		$data = (array)$data;

		$data['login']=true;
		unset($data['password']);



		if($data['KODE_GROUP']){
			$data['group_id'] = $data['KODE_GROUP'];
		}

		$data['nama_group'] = $this->conn->GetOne("select name from public_sys_group where group_id=".$this->conn->escape($data['group_id']));

		if($data['PEGAWAI']){
			$data['name'] = $data['PEGAWAI'];
		}

		$temp = $data;
		foreach($temp as $k=>$v){
			$k = strtolower($k);
			$data[$k] = $v;
			$_SESSION[SESSION_APP][$k]=$v;
		}

		foreach ($tokenarr as $k=>$v) {
			$_SESSION[SESSION_APP][$k]=$v;
		}

		if($data['id_jabatan']){

			$pic = $this->GetPicParent($data['id_jabatan']);

			$_SESSION[SESSION_APP]['pic'] = $pic;

			$child_jabatan = $this->GetChildJabatan($pic);

			$_SESSION[SESSION_APP]['child_jabatan'] = array_unique($child_jabatan);

		}


		$menuarr = $this->GetMenuArr();
		$_SESSION[SESSION_APP]['menu'] = $menuarr;

		$datenow = $this->conn->sysTimeStamp;
		$this->conn->Execute("
		update public_sys_user
		set last_ip = '{$_SERVER['REMOTE_ADDR']}', last_login = $datenow
		where username = '{$data['username']}'");
	}

	private function GetPicParent($id_jabatan){
		if(!$id_jabatan)
			return null;

		$pic = $this->conn->GetOne("select owner from risk_scorecard where owner = ".$this->conn->escape($id_jabatan));

		if(!$pic){
			$id_jabatan = $this->conn->GetOne("select id_jabatan 
				from mt_sdm_jabatan a 
				where exists (select 1 from mt_sdm_jabatan b where b.superior_id = a.position_id and b.id_jabatan = ".$this->conn->escape($id_jabatan).")");

			$pic= $this->GetPicParent($id_jabatan);
		}

		return $pic;
	}

	public function GetChildJabatan($id_jabatan=null, $is_self = true){
		$jabatan = array();

		if($id_jabatan){
			if($is_self)
				$jabatan[] = $id_jabatan;

			$rowschild = $this->conn->GetArray("select id_jabatan 
				from mt_sdm_jabatan a 
				where exists (select 1 from mt_sdm_jabatan b where b.position_id = a.superior_id and b.id_jabatan = ".$this->conn->escape($id_jabatan).")");

			foreach ($rowschild as $r) {
				$jabatan1 = $this->GetChildJabatan($r['id_jabatan']);
				
				$jabatan[] = $r['id_jabatan'];
				$jabatan = array_merge($jabatan, $jabatan1);
			}
		}

		return $jabatan;
	}

	public function GetMenu($data=null, $ul="<ul class=\"list\"> <li class=\"header\">MAIN NAVIGATION</li>", &$child_active='',$ischild=false){

		if(!$data){
			$start = true;
			$data = $_SESSION[SESSION_APP]['menu'];
		}

		if($data)
		{
			$fulluri = current_url();
			$ret.="\n $ul \n ";
			foreach($data as $row){

				$url = $row['url'];
				if(!$ischild)
					$icon = $row['icon'];

				$active = "";
				$str = str_replace(array('/index','/detail','/edit','/add'), '', $fulluri);

				$sub_pr = array_keys($this->_subRisiko());
				$str = str_replace($sub_pr, 'risk_scorecard', $str);

				$find = str_replace(array('/detail','/index'), '', $url);

				if(strpos($str, $find)!==false)
					$child_active = $active = "active";


				$child_active1 = '';
				$sub = '';
				if(($row['sub']))
					$sub = $this->GetMenu($row['sub'],"<ul class=\"ml-menu\">", $child_active1,1);


				if($sub){
					$ret.= "<li class=\"$child_active1\">\n";
					$ret.="<a href='".$url."' class='menu-toggle'>\n<i class='material-icons'>$icon</i> <span>".$row['label']."</span>\n</a>\n";
					$ret.=$sub;
				}else{
					$ret.= "<li class=\"$active\">\n";
					$ret.="<a href='".$url."'><i class='material-icons'>$icon</i> <span>".$row['label']."</span>";

					if(trim($row['label'])=='Kajian Risiko' && !$active)
						$ret .= '&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-left tunjuk"></span>';
					elseif(trim($row['label'])=='Taksonomi Baru'){
						#taksonomi baru
						$baru = $this->conn->GetOne("select count(1) from mt_taksonomi_risiko where id_taksonomi_area is null");
						if($baru)
							$ret .= '<span class="label label-danger">'.$baru.'</span>';
						else
							$ret .= '<span class="label label-success">'.$baru.'</span>';
					}

					$ret.="</a>\n";
				}
				$ret.="</li>\n";
			}

			if($start){
				$ret.='';
				// $ret.='<li><hr/>
				// <div class="copyright oke">
				// 	<small>
				// 		<center>'.config_item('copyright').'</center>
				// 	</small>
				// </div><br/></li>';
			}

			$ret.="</ul>";
		}
		return $ret;
	}

	private function _subRisiko($view="detail", $id_scorecard=null, $id_risiko=null, $finish=false){

		if($id_risiko){
			$return = array(
				"risk_sasaran_kegiatan"=>array("url"=>site_url("panelbackend/risk_sasaran_kegiatan/index/$id_scorecard/$id_risiko"),"class"=>"done", "label"=>"Sasaran"),
				"risk_risiko"=>array("url"=>site_url("panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko"),"class"=>"done", "label"=>"Identifikasi Risiko"),
				"risk_control"=>array("url"=>site_url("panelbackend/risk_control/index/$id_risiko"),"label"=>"Analisis Risiko",'class'=>'done'),
				"risk_mitigasi"=>array("url"=>site_url("panelbackend/risk_mitigasi/index/$id_risiko"),"label"=>"Penanganan Risiko",'class'=>'done'),
			);
		}else{
			$return = array(
				"risk_sasaran_kegiatan"=>array("url"=>site_url("panelbackend/risk_sasaran_kegiatan/index/$id_scorecard/$id_risiko"),"class"=>"done", "label"=>"Sasaran"),
				"risk_risiko"=>array("url"=>site_url("panelbackend/risk_risiko/index/$id_scorecard"),"class"=>"disabled", "label"=>"Identifikasi Risiko"),
				"risk_control"=>array("url"=>site_url("panelbackend/risk_control/index/$id_risiko"),"label"=>"Analisis Risiko",'class'=>'disabled'),
				"risk_mitigasi"=>array("url"=>site_url("panelbackend/risk_mitigasi/index/$id_risiko"),"label"=>"Penanganan Risiko",'class'=>'disabled'),
			);

		$return += array(
			"risk_high_info"=>array("url"=>site_url("panelbackend/risk_high_info/detail/$id_risiko"),"label"=>"Informasi Data Pendukung",'class'=>'disabled')
		);
		}

		if($finish){
			$return += array(
				"risk_evaluasi"=>array("url"=>site_url("panelbackend/risk_evaluasi/detail/$id_scorecard/$id_risiko"),"label"=>"Pemantauan & Peninjauan",'class'=>'done')
			);
		}else{
			$return += array(
				"risk_evaluasi"=>array("url"=>site_url("panelbackend/risk_evaluasi/detail/$id_scorecard/$id_risiko"),"label"=>"Pemantauan & Peninjauan",'class'=>'disabled')
			);
		}

		if($id_risiko){
			

		$return += array(
			"risk_high_info"=>array("url"=>site_url("panelbackend/risk_high_info/detail/$id_risiko"),"label"=>"Informasi Data Pendukung",'class'=>'done')
		);
		}

		return $return;
	}

	private function _subRisikoGrc($view="detail", $id_scorecard=null, $id_risiko=null, $finish=false){

		if($id_risiko){
			$return = array(
				"risk_sasaran_kegiatan_grc"=>array("url"=>site_url("panelbackend/risk_sasaran_kegiatan_grc/index/$id_scorecard/$id_risiko"),"class"=>"done", "label"=>"Sasaran"),
				"risk_risiko_grc"=>array("url"=>site_url("panelbackend/risk_risiko_grc/detail/$id_scorecard/$id_risiko"),"class"=>"done", "label"=>"Identifikasi Risiko"),
				"risk_control_grc"=>array("url"=>site_url("panelbackend/risk_control_grc/index/$id_risiko"),"label"=>"Analisis Risiko",'class'=>'done'),
				"risk_mitigasi_grc"=>array("url"=>site_url("panelbackend/risk_mitigasi_grc/index/$id_risiko"),"label"=>"Penanganan Risiko",'class'=>'done'),
			);
		}else{
			$return = array(
				"risk_sasaran_kegiatan_grc"=>array("url"=>site_url("panelbackend/risk_sasaran_kegiatan_grc/index/$id_scorecard/$id_risiko"),"class"=>"done", "label"=>"Sasaran"),
				"risk_risiko_grc"=>array("url"=>site_url("panelbackend/risk_risiko_grc/index/$id_scorecard"),"class"=>"disabled", "label"=>"Identifikasi Risiko"),
				"risk_control_grc"=>array("url"=>site_url("panelbackend/risk_control_grc/index/$id_risiko"),"label"=>"Analisis Risiko",'class'=>'disabled'),
				"risk_mitigasi_grc"=>array("url"=>site_url("panelbackend/risk_mitigasi_grc/index/$id_risiko"),"label"=>"Penanganan Risiko",'class'=>'disabled'),
			);

		$return += array(
			"risk_high_info"=>array("url"=>site_url("panelbackend/risk_high_info/detail/$id_risiko"),"label"=>"Informasi Data Pendukung",'class'=>'disabled')
		);
		}

		if($finish){
			$return += array(
				"risk_evaluasi"=>array("url"=>site_url("panelbackend/risk_evaluasi/detail/$id_scorecard/$id_risiko"),"label"=>"Pemantauan & Peninjauan",'class'=>'done')
			);
		}else{
			$return += array(
				"risk_evaluasi"=>array("url"=>site_url("panelbackend/risk_evaluasi/detail/$id_scorecard/$id_risiko"),"label"=>"Pemantauan & Peninjauan",'class'=>'disabled')
			);
		}

		if($id_risiko){
			

		$return += array(
			"risk_high_info"=>array("url"=>site_url("panelbackend/risk_high_info/detail/$id_risiko"),"label"=>"Informasi Data Pendukung",'class'=>'done')
		);
		}

		return $return;
	}

	public function GetTabScorecard($view="detail", $id=null, $id_risiko=null, $is_finish=false, $is_no_kegiatan=false, $is_info=true, $is_notab=true, $is_peluang=false){
		if($is_notab && !$is_no_kegiatan)
			return null;
		if($view=='add'){
			$view = 'edit';
		}elseif($view!='detail' && $view!='edit')
			$view = 'detail';

		if(!$is_notab){
			$data = $this->_subRisiko($view, $id, $id_risiko, $is_finish);
		}else{
			$data = array(
				"risk_risiko"=>array("url"=>site_url("panelbackend/risk_risiko/index/$id"),"class"=>"done", "label"=>"Risiko")
			);
		}
		
		$fulluri = current_url();

		if(!$is_info)
			unset($data['risk_high_info']);

		if($is_no_kegiatan){
			$data = array_merge(array("proses"=>array("url"=>site_url("panelbackend/risk_risiko/proses/$id/$id_risiko"),"class"=>"done", "label"=>"Alur Proses")),$data);
			unset($data['risk_sasaran_kegiatan']);
			unset($data['risk_mitigasi']);

			if(strpos($fulluri,'risk_sasaran_kegiatan') or strpos($fulluri,'risk_mitigasi')){
				$v = $view;
				if(!$id_risiko && ($v=='detail' or $v=='edit'))
					$v = "edit";

				redirect("panelbackend/risk_risiko/$v/$id");
			}
		}

		$disabledarr = $this->GetDisableTab($id_risiko);
		

		$ret.="<ul role='tablist'>";
		$i=0;
		foreach($data as $k=>$row){

			if($is_peluang){
				if(in_array($k, array("risk_control","risk_evaluasi","risk_high_info")))
					continue;

				if($k=='risk_mitigasi')
					$row['label'] = "TINDAK LANJUT PELUANG";
				else
					$row['label'] = str_replace("RISIKO", "PELUANG", strtoupper($row['label']));
			}

			$url = trim($row['url'],"/");

			if(in_array($k, $disabledarr) && !$is_peluang)
				$row['class'] = "disabled";

			$active = "";

			$str = str_replace(array('edit','detail','index','add'),'',$fulluri);

			$find = str_replace(array('edit','detail','index','add'),'',$url);
			list($http,$find,$buang) = explode("//", $find);

			if(strpos($str, $find)!==false && !(strpos($str, "proses")!==false && $k=='risk_risiko'))
				$active = "current";

			if($fulluri==$url)
				$active = "current";

			if($active)
				$ret.= "<li role=\"tab\" class=\"{$row['class']} current\" aria-disabled=\"false\" aria-selected=\"true\">\n";
			else
				$ret.= "<li role=\"tab\" class=\"{$row['class']}\" aria-disabled=\"true\" aria-selected=\"false\">\n";

			if($row['class']=='disabled')
				$ret.="<a id=\"wizard_horizontal-t-$i\" disabled='disabled' aria-controls=\"wizard_horizontal-p-$i\">".$row['label']." </a>\n";
			else
				$ret.="<a id=\"wizard_horizontal-t-$i\" href=\"$url\" aria-controls=\"wizard_horizontal-p-$i\">".$row['label']." </a>\n";

			$ret.="</li>\n";
			$i++;
		}

		$ret.="</ul>";
		return $ret;
	}
	public function GetTabScorecardGrc($view="detail", $id=null, $id_risiko=null, $is_finish=false, $is_no_kegiatan=false, $is_info=true, $is_notab=true, $is_peluang=false){
		if($is_notab && !$is_no_kegiatan)
			return null;
		if($view=='add'){
			$view = 'edit';
		}elseif($view!='detail' && $view!='edit')
			$view = 'detail';

		if(!$is_notab){
			$data = $this->_subRisikoGrc($view, $id, $id_risiko, $is_finish);
		}else{
			$data = array(
				"risk_risiko_grc"=>array("url"=>site_url("panelbackend/risk_risiko_grc/index/$id"),"class"=>"done", "label"=>"Risiko")
			);
		}
		
		$fulluri = current_url();

		if(!$is_info)
			unset($data['risk_high_info']);

		if($is_no_kegiatan){
			$data = array_merge(array("proses"=>array("url"=>site_url("panelbackend/risk_risiko_grc/proses/$id/$id_risiko"),"class"=>"done", "label"=>"Alur Proses")),$data);
			unset($data['risk_sasaran_kegiatan_grc']);
			unset($data['risk_mitigasi_grc']);

			if(strpos($fulluri,'risk_sasaran_kegiatan_grc') or strpos($fulluri,'risk_mitigasi_grc')){
				$v = $view;
				if(!$id_risiko && ($v=='detail' or $v=='edit'))
					$v = "edit";

				redirect("panelbackend/risk_risiko_grc/$v/$id");
			}
		}

		$disabledarr = $this->GetDisableTabGrc($id_risiko);
		

		$ret.="<ul role='tablist'>";
		$i=0;
		foreach($data as $k=>$row){

			if($is_peluang){
				if(in_array($k, array("risk_control","risk_evaluasi","risk_high_info")))
					continue;

				if($k=='risk_mitigasi_grc')
					$row['label'] = "TINDAK LANJUT PELUANG";
				else
					$row['label'] = str_replace("RISIKO", "PELUANG", strtoupper($row['label']));
			}

			$url = trim($row['url'],"/");

			if(in_array($k, $disabledarr) && !$is_peluang)
				$row['class'] = "disabled";

			$active = "";

			$str = str_replace(array('edit','detail','index','add'),'',$fulluri);

			$find = str_replace(array('edit','detail','index','add'),'',$url);
			list($http,$find,$buang) = explode("//", $find);

			if(strpos($str, $find)!==false && !(strpos($str, "proses")!==false && $k=='risk_risiko_grc'))
				$active = "current";

			if($fulluri==$url)
				$active = "current";

			if($active)
				$ret.= "<li role=\"tab\" class=\"{$row['class']} current\" aria-disabled=\"false\" aria-selected=\"true\">\n";
			else
				$ret.= "<li role=\"tab\" class=\"{$row['class']}\" aria-disabled=\"true\" aria-selected=\"false\">\n";

			if($row['class']=='disabled')
				$ret.="<a id=\"wizard_horizontal-t-$i\" disabled='disabled' aria-controls=\"wizard_horizontal-p-$i\">".$row['label']." </a>\n";
			else
				$ret.="<a id=\"wizard_horizontal-t-$i\" href=\"$url\" aria-controls=\"wizard_horizontal-p-$i\">".$row['label']." </a>\n";

			$ret.="</li>\n";
			$i++;
		}

		$ret.="</ul>";
		return $ret;
	}
	private function GetMenuArr($parent_id=null){
		$ret = '';
		$group_id = $_SESSION[SESSION_APP]['group_id'];
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		$filter = ($parent_id==null)?'b.parent_id is null':'b.parent_id = '.$parent_id;
		if($user_id == 1)
		{
			$strSQL = " SELECT b.*
						FROM public_sys_menu b
						WHERE visible = '1' and $filter
						ORDER BY b.sort";
		}else{
			$filter .= " and a.group_id =".$group_id;
			$strSQL = "	SELECT b.*
						FROM public_sys_group_menu a
						LEFT JOIN public_sys_menu b ON a.menu_id = b.menu_id
						WHERE b.visible = '1' and $filter
						ORDER BY b.sort";
		}
		$data = $this->GetArray($strSQL);

		$ret = array();
		if($data)
		{
			foreach($data as $row){
				//if($row['label']=='Setting')
				//	$ret=array_merge($ret,$this->GetMenuCmsArr());

				$url = '#';
				if($row['url']!=''){
					$url = base_url($row['url']);
				}

				$icon = 'folder';
				if($row['iconcls']){
					$icon = $row['iconcls'];
				}

				$sub = $this->GetMenuArr($row['menu_id']);
				$ret[] = array(
					"label"=>$row["label"],
					"icon"=>$icon,
					"url"=>$url,
					"sub"=>$sub,
				);
			}
		}
		return $ret;
	}

	private function GetMenuCmsArr($parent=false){
		if(!$parent){
			$param = "where parent_halaman is null";
		}else{
			$param = "where parent_halaman = '$parent'";
		}
		$data = $this->GetArray("select * from contents_page_halaman $param order by urutan");

		$ret = array();
		if($data){

			foreach ($data as $key => $value) {
				$icon = 'file';

				$sub = array();
				# code...
				switch($value['jenis']){
					case 1:
						$sub = $this->GetMenuCmsArr($value['halaman']);
						$url = "#";
					break;
					case 2:
						$url = base_url("panelbackend/pageone/index/{$value['halaman']}");
					break;
					case 3:
						$url = base_url("panelbackend/page/index/{$value['halaman']}");;
					break;
					case 4:
						$url = base_url("panelbackend/{$value['halaman']}");
					break;
				}

				$ret[] = array(
					"label"=>$value['nama'],
					"icon"=>$icon,
					"url"=>$url,
					"sub"=>$sub,
				);
			}
		}
		return $ret;
	}

	public function GetAction($url, $type){
		$group_id = $_SESSION[SESSION_APP]['group_id'];
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		if($user_id == 1){
			$strSQL = "
				SELECT b.name
				from public_sys_action b
				LEFT JOIN public_sys_menu d ON b.menu_id=d.menu_id
				WHERE type = '$type' and b.visible = '1' AND d.url='$url'";
		}else{
			$strSQL = "
				SELECT b.name
				FROM public_sys_group_action a
				LEFT JOIN public_sys_action b ON a.action_id=b.action_id
				LEFT JOIN public_sys_group_menu c ON a.group_menu_id=c.group_menu_id
				LEFT JOIN public_sys_menu d ON c.menu_id=d.menu_id
				WHERE type = '$type'  and b.visible = '1' AND c.group_id = $group_id AND d.url='$url'";
		}

		$respons = $this->GetArray($strSQL);
		$respon = array();
		foreach($respons as $row)
		{
			$respon[]=$row['name'];
		}
		return $respon;
	}

	public function GetAccessRole($url=""){
		$group_id = $_SESSION[SESSION_APP]['group_id'];

		$sql = "
			SELECT
			    nvl(b.name,'index') as name
			FROM
			    public_sys_menu d
			        LEFT JOIN
			    public_sys_group_menu c ON c.menu_id = d.menu_id
			        left join
			    public_sys_group_action a ON a.group_menu_id = c.group_menu_id
			        LEFT JOIN
			    public_sys_action b ON a.action_id = b.action_id
			WHERE c.group_id = '$group_id' AND d.url='$url'";
		$data = $this->GetArray($sql);
		$return = array();
		foreach ($data as $key => $value) {
			# code...
			$return[$value['name']]=1;
		}

		if(($return)){

			$return['index']=1;
			$return['detail']=1;
			$return['lst']=1;
			$return['reset']=1;
			$return['preview_file']=1;
			$return['preview']=1;
			$return['print']=1;
			$return['selesai']=1;
			$return['go_print']=1;
			$return['open_file']=1;

			if($return['add'] or $return['edit']){
				$return['save']=1;
				$return['batal']=1;
				$return['import']=1;
				$return['download_template']=1;
				$return['upload_file']=1;
				$return['export_list']=1;
				$return['import_list']=1;
				$return['delete_file']=1;
			}

			if($return['delete']){
				$return['delete_file']=1;
			}
		}
		
		return $return;
	}

	public function GetAccessRole1($url="",$action=""){
		$group_id = $_SESSION[SESSION_APP]['group_id'];
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		if($user_id == 1){
			return true;
		}
		$return = false;
		$action = strtolower(str_replace("_action","",$action));
		if($action == 'index'){
			$filter_action = '';
		}else{
			$filter_action = " AND b.name='$action'";
		}
		if(preg_match("/index/",$action)) $filter_action = "";
		$sql = "
			SELECT 1
			FROM public_sys_group_action a
			LEFT JOIN public_sys_action b ON a.action_id=b.action_id
			LEFT JOIN public_sys_group_menu c ON a.group_menu_id=c.group_menu_id
			LEFT JOIN public_sys_menu d ON c.menu_id=d.menu_id
			WHERE c.group_id = '$group_id' AND d.url='$url' $filter_action";
		$return = $this->GetOne($sql);
		return (bool)$return;
	}

	public function statistikVisitor($limit=30){
		$sql = "select * from (select *
		from contents_statistik_pengunjung
		order by tanggal desc limit $limit) a order by tanggal asc";
		$rows = $this->conn->GetArray($sql);

		$data = array();
		$ticks = array();
		foreach ($rows as $key => $value) {
			# code...
			$data[]=array($key, $value['jumlah']);
			$ticks[]=array($key, Eng2Ind($value['tanggal']));
		}

		$ret['data'] = json_encode($data);
		$ret['ticks'] = json_encode($ticks);
		return $ret;
	}

	#active directory sso PJBS
	public function autoAuthenticate($username,$credential)
    {
        ini_set ('soap.wsdl_cache_enabled', 0);
        $wsdl = $this->config->item("url_portal").'/index.php/portal_login?wsdl';
        $CI = $this->ci;

        $cl = new SoapClient($wsdl);
        $rv = $cl->loginToken($this->config->item("id_portal"), $username, $credential);
        if($rv->RESPONSE == "1")
        {
	    	$tokenarr = array(
	    		'username'=>$username,
	    		'credential'=>$credential,
	    	);

	    	return $this->autoSetSession($rv, $tokenarr);
        }
        else
            return $rv;

    }

    public function autoGroupAuthenticate($username,$credential, $groupId)
    {
        ini_set ('soap.wsdl_cache_enabled', 0);
        $wsdl = $this->config->item("url_portal").'/index.php/portal_login?wsdl';
        $CI = $this->ci;

        $cl = new SoapClient($wsdl);
        $rv = $cl->loginGroup($this->config->item("id_portal"), $username, $credential, $groupId);
        if($rv->RESPONSE == "1")
        {
	    	$tokenarr = array(
	    		'username'=>$username,
	    		'credential'=>$credential,
	    	);

	    	return $this->autoSetSession($rv, $tokenarr);
        }
        else
            return $rv;

    }

    private function autoSetSession($rv, $tokenarr){

    	$rv = (array)$rv;



		$nid = $rv['NID'];

		$data_karyawan = $this->GetRow("
		select * from mt_sdm_karyawan
		where trim(nid) = ".$this->conn->escape(trim($nid)));

		$jabatan = $data_karyawan['kdjabatan'];

		$data_jabatan = $this->GetRow("
		select * from mt_sdm_jabatan
		where trim(position_id) = ".$this->conn->escape(trim($jabatan)));

		$user_id = $this->GetOne("
		select user_id from public_sys_user
		where nid=".$this->conn->escape($nid));

		$record = array();
		$record['username'] = $rv['NID'];
		$record['nid'] = $rv['NID'];
		$record['name'] = $rv['PEGAWAI'];
		$record['password'] = sha1(md5(time()));
		$record['email'] = $data_karyawan['email'];
		$record['is_active'] = '1';
		$record['group_id'] = $rv['KODE_GROUP'];
		$record['is_notification'] = '1';
		$record['id_jabatan'] = $data_jabatan['id_jabatan'];


		/*if($nid=='8814038BK' or $nid=='9014140KP'){
			echo "<pre>";
			print_r($user_id);
			print_r($data_karyawan);
			print_r($data_jabatan);
			print_r($record);
			die();
		}*/

		if($user_id)
			$ret = $this->conn->goUpdate("public_sys_user", $record, "user_id = ".$this->conn->escape($user_id));
		else
	        $ret = $this->conn->goInsert('public_sys_user', $record);

		if($record)
			$rv = array_merge($rv, $record);

		if($data_jabatan)
			$rv = array_merge($rv, $data_jabatan);

		if($data_karyawan)
			$rv = array_merge($rv, $data_karyawan);

		if($rv)
		{
        	$this->SetLogin($rv,$tokenarr);
            return $rv;
        }else{
        	return false;
        }

    }

    public function autoLogin($username=null, $password=null){
    	ini_set ('soap.wsdl_cache_enabled', 0);
        $wsdl = $this->config->item("url_portal").'/index.php/portal_login?wsdl';
		
		$cl = new SoapClient($wsdl);
		
		$rv = $cl->loginAplikasi($this->config->item("id_portal"), $username, $password);
		
		$credential = $cl->getToken($username);

		if(!$credential)
			return array("error"=>"Login gagal pastikan username dan password sama dengan di portal.pjbservices.com");

		if($rv->RESPONSE == "1"){

        	$tokenarr = array(
        		'username'=>$username,
        		'credential'=>$credential,
        	);

        	$this->autoSetSession($rv, $tokenarr);

			return array('success'=>'login berhasil');
		}
		elseif($rv->RESPONSE == "PAGE"){
			return array(
				'success'=>'login berhasil', 
				'link'=>$this->config->item("url_portal")."/".$rv->RESPONSE_LINK."/?reqNID=".$rv->NID."&reqAplikasiId=".$rv->APLIKASI_ID
			);
		}
		else
			return array("error"=>"Login gagal pastikan username dan password sama dengan di portal.pjbservices.com");
    }
}
