<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Home extends _adminController{
	public $limit = 5;
	public $limit_arr = array('5','10','30','50','100');

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout";

		$this->load->model("Risk_risikoModel","model");
		$this->load->model("Risk_scorecardModel","modelscorecard");
		$this->load->model("Risk_arsip_filesModel","modelfile");
		$this->plugin_arr = array(
			'upload'
		);
		$this->data['configfile'] = $this->config->item('file_upload_config');
		$this->data['is_allow_tgl_efektif'] = false;
	}

	function strategi_map($id){
		echo file_get_contents(APPPATH."views/panelbackend/_strategimap".$id.".php");
	}

	function strategis(){
		redirect("panelbackend/home/index");
	}

	private function _efektif(){
		$tahun = $this->post['tahun'];
		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		list($tgl, $bln, $thn) = explode("-",$tgl_efektif);

		if($tahun<>$thn && $tahun){
			$thn = $tahun;
			$bln = '12';
			$tgl = '31';
		}

		$this->data['thn'] = $thn;
		$this->data['tahun'] = $thn;
		$this->data['bln'] = $bln;
		$this->data['tgl'] = $tgl;
	}

	private function _datamatrix(){

		$this->data['id_kajian_risiko'] = $id_kajian_risiko = $this->post['id_kajian_risiko'];
		$this->data['id_scorecard_sub'] = $this->data['id_scorecard'] = $id_scorecard = $this->post['id_scorecard'];

		$this->data['id_scorecardarr'] = $this->modelscorecard->GetChild($id_scorecard, $thn, $id_kajian_risiko);

		$id_scorecardarr = $this->data['id_scorecardarr'];

		$thn = $this->data['thn'];
		$bln = $this->data['bln'];
		$tgl = $this->data['tgl'];

		$top = $this->post['top'];

		if(!$top)
			$top = $this->config->item('risk_top_risiko');

		if(!$top)
			$top = 10;

		$this->data['top'] = $top;

		$order = $this->config->item('risk_order_risiko');

		if(!$order)
			$order = 'c';

		$param = array(
			"rating"=>"icr",
			"id_kajian_risiko"=>$id_kajian_risiko,
			"top"=>$top,
			"all"=>(!(bool)$id_scorecardarr),
			"id_scorecard"=>$id_scorecardarr,
			"tahun"=>$thn,
			"bulan"=>$bln,
			"tanggal"=>$tgl,
			"order"=>$order
		);

		foreach (str_split($param['rating']) as $key => $value) {
			$this->data['rating'][$value] = 1;
		}

		$this->data['rows'] = $this->model->getListRiskProfile($param);

		$this->data['statusarr'] = array('default'=>$this->config->item('default_status'),'good'=>$this->config->item('good_status'), 'bad'=>$this->config->item('bad_status'));

		$this->getKesimpulan();
	}

	private function _dashboardmatrix(){
		$this->data['id_class'] = $this->post['id_class'];
		$this->data['id_kajian_risiko'] = $id_kajian_risiko = $this->post['id_kajian_risiko'];
		$this->data['id_scorecard_sub'] = $this->data['id_scorecard'] = $id_scorecard = $this->post['id_scorecard'];
		$this->data['id_scorecardarr'] = $this->modelscorecard->GetChild($id_scorecard, $thn, $id_kajian_risiko);

		$this->data['rk'] = $this->conn->GetRow("select id_kajian_risiko, nama, keterangan from mt_risk_kajian_risiko where id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko ));

		$thn = $this->data['thn'];
		$bln = $this->data['bln'];
		$tgl = $this->data['tgl'];

		$this->data['scorecardarr'] = $this->modelscorecard->GetCombo(null, null, $thn, $id_kajian_risiko);

		$this->data['scorecard_name'] = array();
		if($this->data['id_scorecard']){
			$temp = $this->modelscorecard->GetComboParent($this->data['id_scorecard']);

			foreach($temp as $k=>$v){
				$this->data['scorecard_name'][$k]="<strong>".trim(trim($v),".")."</strong>";
			}
		}

		$this->data['total'] = $this->model->getCountAll($id_kajian_risiko, $thn, $bln, $this->data['id_scorecardarr']);

		if($id_kajian_risiko==3){
			$id_scorecardarr = $this->modelscorecard->GetChild(207, $thn, $id_kajian_risiko);
			$total = $this->model->getCountAll($id_kajian_risiko, $thn, $bln, $id_scorecardarr);
			$this->data['total']['progress_mitigasi_rjpp'] = $total['progress_mitigasi'];
			$this->data['total']['control_efektif_rjpp'] = $total['control_efektif'];
			$this->data['total']['total_risiko_rjpp'] = $total['total_risiko'];

			$id_scorecardarr = $this->modelscorecard->GetChild(283, $thn, $id_kajian_risiko);
			$total = $this->model->getCountAll($id_kajian_risiko, $thn, $bln, $id_scorecardarr);
			$this->data['total']['progress_mitigasi_rkap'] = $total['progress_mitigasi'];
			$this->data['total']['control_efektif_rkap'] = $total['control_efektif'];
			$this->data['total']['total_risiko_rkap'] = $total['total_risiko'];
		}

		if($id_kajian_risiko==4){
			$id_scorecardarr = $this->modelscorecard->GetChild(526, $thn, $id_kajian_risiko);
			$total = $this->model->getCountAll($id_kajian_risiko, $thn, $bln, $id_scorecardarr);
			$this->data['total']['progress_mitigasi_probis'] = $total['progress_mitigasi'];
			$this->data['total']['control_efektif_probis'] = $total['control_efektif'];
			$this->data['total']['total_risiko_probis'] = $total['total_risiko'];

			list($where, $where1) = $this->model->_filterRisk($id_kajian_risiko, $thn, $bln, $id_scorecardarr);

			$this->data['rows_probis'] = $this->conn->GetArray("select a.id_kategori, a.nama, 
				sum(case when c.is_efektif = 1 then 1 else null end)/sum(1)*100 as efektif
				from mt_pb_kategori a
				left join mt_pb_kelompok_proses b on a.id_kategori = b.id_kategori
				left join mt_pb_nama_proses np on b.id_kelompok_proses = np.id_kelompok_proses
				left join risk_scorecard rc on np.id_nama_proses = rc.id_nama_proses $where1
				left join risk_risiko r on rc.id_scorecard = r.id_scorecard $where
				left join risk_control c on r.id_risiko = c.id_risiko
				where 1=1
				group by a.id_kategori, a.nama
				order by TO_NUMBER(SUBSTR(a.nama, 0, INSTR(a.nama, '.')-1),'99')");

			$id_scorecardarr = $this->modelscorecard->GetChild(527, $thn, $id_kajian_risiko);
			$total = $this->model->getCountAll($id_kajian_risiko, $thn, $bln, $id_scorecardarr);
			$this->data['total']['progress_mitigasi_om'] = $total['progress_mitigasi'];
			$this->data['total']['control_efektif_om'] = $total['control_efektif'];
			$this->data['total']['total_risiko_om'] = $total['total_risiko'];

			$id_scorecardarr = $this->modelscorecard->GetChild(528, $thn, $id_kajian_risiko);
			$total = $this->model->getCountAll($id_kajian_risiko, $thn, $bln, $id_scorecardarr);
			$this->data['total']['progress_mitigasi_proyek'] = $total['progress_mitigasi'];
			$this->data['total']['control_efektif_proyek'] = $total['control_efektif'];
			$this->data['total']['total_risiko_proyek'] = $total['total_risiko'];

			$this->data['rows_project_run'] = $this->conn->GetArray("select * from risk_scorecard 
				where id_parent_scorecard = 528 and id_status_proyek = 2 and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko));

			$this->data['omrows'] = $this->conn->GetArray("select 
				id_parent_scorecard, a.id_status_unit, count(1) as value, b.nama as label
				from risk_scorecard a
				join mt_status_unit b on a.id_status_unit = b.id_status_unit
				where id_parent_scorecard = 527 and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko)."
				group by id_parent_scorecard, a.id_status_unit, b.nama");

			$this->data['total_om'] = 0;
			foreach($this->data['omrows'] as $r){
				$this->data['total_om'] += (int)$r['value'];
			}

			$filter_tahun = " and to_char(nvl(a.tgl_mulai_efektif,sysdate),'YYYY') <= ".$this->conn->escape($thn)." and to_char(nvl(a.tgl_akhir_efektif-1,sysdate),'YYYY')>=".$this->conn->escape($thn);

			$this->data['projectrows'] = $this->conn->GetArray("select 
				id_parent_scorecard, a.id_status_proyek, count(1) as value, b.nama as label
				from risk_scorecard a
				join mt_status_proyek b on a.id_status_proyek = b.id_status_proyek
				where id_parent_scorecard = 528 and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko).$filter_tahun."
				group by id_parent_scorecard, a.id_status_proyek, b.nama");

			$this->data['total_proyek'] = 0;
			foreach($this->data['projectrows'] as $r){
				$this->data['total_proyek'] += (int)$r['value'];
			}
		}

		if($id_kajian_risiko==5){

			$filter_tahun = " and to_char(nvl(a.tgl_mulai_efektif,sysdate),'YYYY') <= ".$this->conn->escape($thn)." and to_char(nvl(a.tgl_akhir_efektif-1,sysdate),'YYYY')>=".$this->conn->escape($thn);

			$this->data['daftar_pekerjaan'] = $this->conn->GetArray("select a.*, b.nama as status, b.warna 
				from risk_scorecard a
				left join mt_status_proyek b on a.id_status_proyek = b.id_status_proyek
				where id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko).$filter_tahun." order by id_scorecard desc");
		}

		// if($_SESSION[SESSION_APP]['group_id']==1)
			$this->PartialView("panelbackend/_dashboardmatrixadmin".$id_kajian_risiko);
		/*else
			$this->PartialView("panelbackend/_dashboardmatrix".$id_kajian_risiko);*/
	}

	private function _dashboardissue(){
		$tahun = $this->post['tahun'];
		$jenis = $this->post['jenis'];

		$rows = $this->conn->GetArray("select a.*, b.nama as issue from mt_program_strategis a join mt_issue b on a.id_issue = b.id_issue
			where jenis = ".$this->conn->escape($jenis)." and b.tahun = ".$this->conn->escape($tahun)."order by a.id_issue, a.id_program_strategis");


		echo "<table class='table'><thead><tr><th>Issue</th><th>Program Strategis</th></tr></thead>";
		foreach($rows as $r){
			echo "<tr>";
			echo "<td>";
			echo $r['issue'];
			echo "</td>";
			echo "<td>";
			echo $r['nama'];
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit();

	}

	private function _dashboardom(){
		$thn = $this->data['thn'];
		$bln = $this->data['bln'];
		$id_status_unit = $this->post['id_status_unit'];
		$id_kajian_risiko = $this->post['id_kajian_risiko'];
		$id_scorecard = $this->post['id_scorecard'];

		$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard, $thn, $id_kajian_risiko);;

		list($where, $where1) = $this->model->_filterRisk($id_kajian_risiko, $thn, $bln, $id_scorecardarr);

		if($id_status_unit)
			$filter_status = " and rs.id_status_unit = ".$this->conn->escape($id_status_unit);

		$rows = $this->conn->GetArray("select rs.id_scorecard, rs.nama, su.nama as nama_unit, count(r.id_risiko) as jumlah_risiko
			from risk_scorecard rs 
			join mt_status_unit su on rs.id_status_unit = su.id_status_unit
			left join risk_risiko r on rs.id_scorecard = r.id_scorecard $where1 $where
			where rs.id_parent_scorecard = ".$this->conn->escape($id_scorecard).$filter_status."
			group by rs.id_scorecard, rs.nama, su.nama");


		echo "<table class='table'><thead><tr><th width='10px'>No</th><th>Nama Unit</th><th>Status Unit</th><th>Jumlah Risiko</th></tr></thead>";
		$no=1;
		foreach($rows as $r){
			echo "<tr>";
			echo "<td>".($no++)."</td>";
			echo "<td><a href='".site_url("panelbackend/risk_risiko/index/".$r['id_scorecard'])."'>";
			echo $r['nama'];
			echo "</a></td>";
			echo "<td>";
			echo $r['nama_unit'];
			echo "</td>";
			echo "<td>";
			echo $r['jumlah_risiko'];
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit();

	}

	private function _dashboardproyek(){
		$thn = $this->data['thn'];
		$bln = $this->data['bln'];
		$id_status_proyek = $this->post['id_status_proyek'];
		$id_kajian_risiko = $this->post['id_kajian_risiko'];
		$id_scorecard = $this->post['id_scorecard'];

		$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard, $thn, $id_kajian_risiko);;

		list($where, $where1) = $this->model->_filterRisk($id_kajian_risiko, $thn, $bln, $id_scorecardarr);

		if($id_status_proyek)
			$filter_status = " and rs.id_status_proyek = ".$this->conn->escape($id_status_proyek);

		$filter_tahun = " and to_char(nvl(rs.tgl_mulai_efektif,sysdate),'YYYY') <= ".$this->conn->escape($thn)." and to_char(nvl(rs.tgl_akhir_efektif-1,sysdate),'YYYY')>=".$this->conn->escape($thn);

		$rows = $this->conn->GetArray("select rs.id_scorecard, rs.nama, su.nama as nama_unit, count(r.id_risiko) as jumlah_risiko
			from risk_scorecard rs 
			join mt_status_proyek su on rs.id_status_proyek = su.id_status_proyek
			left join risk_risiko r on rs.id_scorecard = r.id_scorecard $where1 $where
			where rs.id_parent_scorecard = ".$this->conn->escape($id_scorecard).$filter_status.$filter_tahun."
			group by rs.id_scorecard, rs.nama, su.nama");


		echo "<table class='table'><thead><tr><th width='10px'>No</th><th>Nama Proyek</th><th>Status Proyek</th><th>Jumlah Risiko</th></tr></thead>";
		$no=1;
		foreach($rows as $r){
			echo "<tr>";
			echo "<td>".($no++)."</td>";
			echo "<td><a href='".site_url("panelbackend/risk_risiko/index/".$r['id_scorecard'])."'>";
			echo $r['nama'];
			echo "</a></td>";
			echo "<td>";
			echo $r['nama_unit'];
			echo "</td>";
			echo "<td>";
			echo $r['jumlah_risiko'];
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit();

	}

	private function _dashboardtable(){

		$this->data['id_class'] = $this->post['id_class'];

		$this->_datamatrix();

		if($this->data['id_kajian_risiko']==3){
			foreach($this->data['rows'] as &$r){
				$rs = $this->conn->GetRow("select 
					sum(case when sp.prosentase = 100 then 1 else null end) as complete,
					sum(case when sp.prosentase = 100 then null else 1 end) as progress,
					avg(sp.prosentase) as average
					from risk_mitigasi rm
					join mt_status_progress sp on rm.id_status_progress = sp.id_status_progress
					where is_control=0 
					and id_risiko = ".$this->conn->escape($r['id_risiko'])."
					group by id_risiko");
				if(!$rs)
					$rs = array();
				$r = array_merge($r, $rs);
			}
			$this->PartialView("panelbackend/_dashboardtablestr");
		}
		elseif($this->data['id_kajian_risiko']==4){
			foreach($this->data['rows'] as &$r){
				$rs = $this->conn->GetRow("select avg(sp.prosentase) as average
					from risk_mitigasi rm
					join mt_status_progress sp on rm.id_status_progress = sp.id_status_progress
					where is_control=0 
					and id_risiko = ".$this->conn->escape($r['id_risiko'])."
					group by id_risiko");
				if(!$rs)
					$rs = array();
				$r = array_merge($r, $rs);
				$rs = $this->conn->GetRow("select 
					sum(case when rc.is_efektif = 1 then 1 else null end) as efektif,
					sum(case when rc.is_efektif = 1 then null else 1 end) as tidak_efektif
					from risk_control rc
					where id_risiko = ".$this->conn->escape($r['id_risiko'])."
					group by id_risiko");
				if(!$rs)
					$rs = array();
				$r = array_merge($r, $rs);
			}
			$this->PartialView("panelbackend/_dashboardtableopr");
		}
		elseif($this->data['id_kajian_risiko']==5){
			$this->data['rowsmitigasi'] = array();

			foreach($this->data['rows'] as $r){
				$this->data['rowsmitigasi'][$r['id_risiko']] = $this->conn->GetArray("select rm.*
					from risk_mitigasi rm
					where is_control=0 
					and id_risiko = ".$this->conn->escape($r['id_risiko']));
			}
			
			$this->PartialView("panelbackend/_dashboardtablepro");
		}
	}

	private function _dashboardtaksonomi(){

		$this->data['totalrisiko'] = $this->model->GetGrafikTaksonomi();

		$this->PartialView("panelbackend/_dashboardtaksonomi");
	}

	private function _dashboardtingkattahunan(){

		$this->data['tingkattahunan'] = $this->model->GetTingkatTahunan();

		$this->PartialView("panelbackend/_dashboardtingkattahunan");
	}

	private function _dashboardtrendtaksonomi(){

		$this->data['tahun'] = $this->post['tahun'];
		$this->data['id_taksonomi'] = $this->post['id_taksonomi'];
		$this->data['nama_taksonomi'] = $this->conn->GetOne("select nama from mt_taksonomi_objective where id_taksonomi_objective = ".$this->conn->escape($this->post['id_taksonomi']));
		$this->data['trentaksonomi'] = $this->model->GetTrendTaksonomi($this->data['tahun'], $this->data['id_taksonomi']);
		$this->PartialView("panelbackend/_dashboardtrendtaksonomi");
	}

	private function _dashboardarsip(){
		$rows = $this->conn->GetArray("select id_arsip_files as id, client_name as name from risk_arsip_files");

		$row = array();
		foreach($rows as $r){
			$row['id'][]=$r['id'];
			$row['name'][]=$r['name'];
		}

		echo UI::createUploadMultiple('file', $row, $this->data['page_ctrl'], $this->Access("view_all_direktorat","panelbackend/risk_risiko"));
	}

	private function get_kesimpulan(){
		$this->data['id_class'] = $this->post['id_class'];
		$this->_datamatrix();
		$this->PartialView("panelbackend/_dashboardkesimpulan");
	}

	function Index($page=null) 
	{
		$this->data['tingkattahunan'] = $this->model->GetTingkatTahunan();
		$this->data['id_kajian_risiko'] = $id_kajian_risiko = $this->post['id_kajian_risiko'];
		$this->data['id_scorecard_sub'] = $this->data['id_scorecard'] = $id_scorecard = $this->post['id_scorecard'];
		$this->data['is_dashboard'] = true;
		$this->_efektif();

		if($this->post['act']=="get_kajian_risiko"){
			$this->_dashboardmatrix();
			exit();
		}

		if($this->post['act']=="get_table"){
			$this->_dashboardtable();
			exit();
		}

		if($this->post['act']=="get_issue"){
			$this->_dashboardissue();
			exit();
		}

		if($this->post['act']=="get_om"){
			$this->_dashboardom();
			exit();
		}

		if($this->post['act']=="get_proyek"){
			$this->_dashboardproyek();
			exit();
		}

		if($this->post['act']=="get_arsip"){
			$this->_dashboardarsip();
			exit();
		}

		if($this->post['act']=="get_taksonomi"){
			$this->_dashboardtaksonomi();
			exit();
		}

		if($this->post['act']=="get_tingkattahunan"){
			$this->_dashboardtingkattahunan();
			exit();
		}

		if($this->post['act']=="get_trendtaksonomi"){
			$this->_dashboardtrendtaksonomi();
			exit();
		}

		if($this->post['act']=='reset_kesimpulan'){
			$this->resetKesimpulan();
			exit();
		}

		if($this->post['act']=='save_kesimpulan'){
			$this->saveKesimpulan();
			exit();
		}

		if($this->post['act']=='get_kesimpulan'){
			$this->get_kesimpulan();
			exit();
		}

		$this->data['pengumumanarr'] = $this->conn->GetArray("select m.id_msg, msg 
			from risk_msg m 
			join risk_msg_penerima p on m.id_msg = p.id_msg
			where is_read = '0' 
			and id_user = ".$this->conn->escape($_SESSION[SESSION_APP]['user_id'])." order by m.id_msg desc");

		$rows = $this->conn->GetArray("select * from mt_maturity where tahun >= ".(date('Y')-10)." order by tahun");

		$this->data['matury_level'] = array();

		foreach($rows as $k=>$v){
			if(!$v['realisasi'])
				unset($v['realisasi']);

			$this->data['matury_level'][$k]=$v;
		}
		// if($_SESSION[SESSION_APP]['group_id']==1)
			$this->View("panelbackend/home5");
		/*else
			$this->View("panelbackend/home");*/
	}

	private function getKesimpulan(){
		if($this->data['id_scorecard_sub'])
			$this->data['kesimpulan'] = $this->conn->GetRow("select * from risk_kesimpulan where id_scorecard = ".$this->conn->escape($this->data['id_scorecard_sub'])." and tahun = ".$this->conn->escape($this->data['tahun']));
		
		if(($this->data['id_scorecard'] && !$this->data['kesimpulan'] && !$this->data['id_scorecard_sub']) or !$this->data['kesimpulan'])
			$this->data['kesimpulan'] = $this->conn->GetRow("select * from risk_kesimpulan where id_scorecard = ".$this->conn->escape($this->data['id_scorecard'])." and tahun = ".$this->conn->escape($this->data['tahun']));

		if(($this->data['id_kajian_risiko'] && !$this->data['kesimpulan'] && !$this->data['id_scorecard_sub'] && !$this->data['id_scorecard']) or !$this->data['kesimpulan'])
			$this->data['kesimpulan'] = $this->conn->GetRow("select * from risk_kesimpulan where id_scorecard is null and id_kajian_risiko = ".$this->conn->escape($this->data['id_kajian_risiko'])." and tahun = ".$this->conn->escape($this->data['tahun']));

		if(!$this->data['kesimpulan'])
			$this->data['kesimpulan'] = array("keterangan"=>$this->config->item("default_condition"), "status"=>"default");
	}

	private function resetKesimpulan(){
		$record = array();

		$id_scorecardarr = $this->modelscorecard->GetChild($this->data['id_scorecard'], $this->data['tahun'], $this->data['id_kajian_risiko']);

		$ratarataprogress = $this->conn->GetOne("select 
					avg(sp.prosentase) as average
					from risk_mitigasi rm
					join mt_status_progress sp on rm.id_status_progress = sp.id_status_progress
					join risk_risiko r on rm.id_risiko = r.id_risiko
					where is_control=0 
					and r.id_scorecard in (".implode(",",$id_scorecardarr).")
					");

		if($ratarataprogress>=80)
			$status = "good";
		else
			$status = "bad";
		
		if($status=="bad")
			$record["keterangan"] = $this->config->item("bad_condition");
		
		if($status=="good")
			$record["keterangan"] = $this->config->item("good_condition");

		if($status=="default")
			$record["keterangan"] = $this->config->item("default_condition");

		$record["status"] = $status;
		$record["tahun"] = $this->post['tahun'];
		$record["id_kajian_risiko"] = $this->data['id_kajian_risiko'];

		if($this->data['id_scorecard_sub'])
			$record['id_scorecard'] = $this->data['id_scorecard_sub'];

		if($this->data['id_scorecard'])
			$record['id_scorecard'] = $this->data['id_scorecard'];
		
		$this->_upsertKesimpulan($record);
	}

	private function saveKesimpulan(){
		$record = array();
		$record["keterangan"] = $this->post['keterangan'];
		$record["status"] = $this->post['status'];
		$record["tahun"] = $this->post['tahun'];
		$record["id_kajian_risiko"] = $this->data['id_kajian_risiko'];

		if($this->data['id_scorecard_sub'])
			$record['id_scorecard'] = $this->data['id_scorecard_sub'];

		if($this->data['id_scorecard'])
			$record['id_scorecard'] = $this->data['id_scorecard'];

		$this->_upsertKesimpulan($record);
	}

	private function _upsertKesimpulan($record=array()){
		$this->getKesimpulan();
		unset($this->post['act']);
		$_SESSION[SESSION_APP]['kes']=$this->post;
		if($this->data['kesimpulan']['id_kesimpulan']){
			$ret = $this->conn->goUpdate("risk_kesimpulan",$record,"id_kesimpulan = ".$this->conn->escape($this->data['kesimpulan']['id_kesimpulan']));
		}else{

			$record['created_date'] = "{{sysdate}}";
			$ret = $this->conn->goInsert("risk_kesimpulan",$record);
		}

		echo '{"success":'.$ret.'}';
		exit();
	}

	function msg($id_msg=null){
		$this->conn->Execute("update risk_msg_penerima set is_read = '1' 
			where id_msg = ".$this->conn->escape($id_msg)." 
			and id_user = ".$this->conn->escape($_SESSION[SESSION_APP]['user_id']));

		redirect("panelbackend/home");
	}

	function task($id_task=null){
		$row = $this->conn->GetRow("select * from risk_task where id_task = ".$this->conn->escape($id_task));

		$url = $row['url'];

		if(strstr($url,'risk_risiko')!==false)
			$status = 1;

		if(strstr($url,'risk_control')!==false)
			$status = 2;

		if(strstr($url,'risk_mitigasi')!==false)
			$status = 3;

		if(strstr($url,'risk_evaluasi')!==false)
			$status = 4;

		$this->conn->Execute("update risk_task set status = '$status' where id_task = ".$this->conn->escape($id_task));

		redirect($url);
	}

	function Loginasback(){
		if(!$_SESSION[SESSION_APP]['loginas'])
			redirect('panelbackend');

		$loginas = $_SESSION[SESSION_APP]['loginas'];
		unset($_SESSION[SESSION_APP]);
		$_SESSION[SESSION_APP] = $loginas;

		redirect('panelbackend');
	}

	function Profile(){
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		$this->access_role['save']=1;
		$this->access_role['batal']=1;
		$this->data['page_title'] = 'Profile';

		$this->load->model("Public_sys_userModel","model");
		$this->load->model("Mt_sdm_jabatanModel","mjabatan");
		$this->load->library('form_validation');

		$id=$_SESSION[SESSION_APP]['user_id'];
		$this->data['edited'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->data['jabatanarr'] = array();
		$this->mjabatan->GetComboLost($this->data['row']['id_jabatan'],$this->data['jabatanarr']);

		$this->data['rules'] = array(
		   'nama'=>array(
				 'field'   => 'name',
				 'label'   => 'Nama',
				 'rules'   => 'required'
			  ),
		   'email'=>array(
				 'field'   => 'email',
				 'label'   => 'Email',
				 'rules'   => 'valid_email|required'
			  ),
		   'confirmpassword'=>array(
				'field'   => 'confirmpassword',
				'label'   => 'Password',
				'rules'   => 'callback_checkconfirm'
			),
		   'oldpassword'=>array(
		   		'field'	=> 'oldpassword',
		   		'label' => 'Password lama',
		   		'rules' => 'callback_checkoldpassword'
		   	)
		);

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$valid = $this->_isValidProfile();
			if(!$valid){
				$this->View('panelbackend/profile');
				return;
			}

			$record = array();
			$record['name'] = $this->post['name'];
			$record['email'] = $this->post['email'];
			$record['is_notification'] = (int)$this->post['is_notification'];

			if(!empty($this->post['password']))
			{
				$record['password']=sha1(md5($this->post['password']));
			}

            $this->_setLogRecord($record,$id);

			if ($id) {
				$return = $this->model->Update($record, "user_id = $id");
				if ($return) {
					SetFlash('suc_msg', $return['success']);
					redirect("panelbackend/home/profile");
				}
				else {
					$this->data['row'] = $record;
					$this->data['err_msg'] = "Data gagal diubah";
				}
			}
		}

		$this->View('panelbackend/profile');
	}

    public function checkoldpassword($str)
    {
    	if(!$this->post['password'])
    		return true;

    	if($this->data['row']['password']<>sha1(md5($str))){
            $this->form_validation->set_message('checkoldpassword', 'Password lama salah');
            return FALSE;
    	}

    	return true;
    }

    public function checkconfirm($str)
    {
    	if(!$this->post['password'])
    		return true;

    	if($str<>$this->post['password']){
            $this->form_validation->set_message('checkconfirm', 'Konfirmasi password salah');
            return FALSE;
    	}

    	return true;
    }

	function _isValidProfile(){

		$rules = array_values($this->data['rules']);

		$this->form_validation->set_rules($rules);

		$error_msg = '';
		if ($this->form_validation->run() == FALSE)
		{
			$error_msg .= validation_errors();
		}

		if($error_msg){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$this->data['row'] = array_merge($this->data['row'],$this->post);
			return false;
		}

		return true;
	}

	function wf(){
		$full_path = FCPATH."assets/doc/WF.pdf";

		header("Content-Type: application/pdf");
		header("Content-Disposition: inline; filename='wf.pdf'");
		echo file_get_contents($full_path);
		die();
	}

	function ug(){
		if($this->data['is_administrator'])
			$full_path = FCPATH."assets/doc/UGA.pdf";
		elseif($this->data['is_coordinator'])
			$full_path = FCPATH."assets/doc/UGC.pdf";
		elseif($this->data['is_owner'])
			$full_path = FCPATH."assets/doc/UGO.pdf";
		elseif($this->data['is_reviewer'])
			$full_path = FCPATH."assets/doc/UGR.pdf";
		elseif($this->data['is_bod'])
			$full_path = FCPATH."assets/doc/UGB.pdf";
		elseif($this->data['is_audit'])
			$full_path = FCPATH."assets/doc/UGU.pdf";
		elseif($this->data['is_kepatuhan'])
			$full_path = FCPATH."assets/doc/UGK.pdf";

		header("Content-Type: application/pdf");
		header("Content-Disposition: inline; filename='ug.pdf'");
		echo file_get_contents($full_path);
		die();
	}
}
