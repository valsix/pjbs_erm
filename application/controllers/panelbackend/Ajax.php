<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Ajax extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	public function notif(){
		$data = $this->auth->GetTask();
		echo json_encode($data);
	}

	public function matrix(){
		$this->data['ajax'] = true;
		$this->load->model("Risk_risikoModel","model");
		$this->load->model("Risk_scorecardModel","mscorecard");

		$tgl_efektif = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$this->data['id_scorecard'] = $id_scorecard = $this->get['id_scorecard'];
		$this->data['id_kajian_risiko'] = $id_kajian_risiko = $this->get['id_kajian_risiko'];
		$this->data['id_scorecard_sub'] = $id_scorecard_sub = $this->get['id_scorecard_sub'];
		$this->data['tahun'] = $tahun = $this->get['tahun'];
		$this->data['bulan'] = $bulan = $this->get['bulan'];
		$this->data['tanggal'] = $tanggal = $this->get['tanggal'];
		$top = $this->get['top'];

		$scorecardarr = $this->model->GetComboDashboard($id_kajian_risiko, $tgl_efektif);

		$id_scorecardarr = array();
		if($id_scorecard){

			$scorecardsubarr = $this->mscorecard->GetComboChild($id_scorecard);

			if($scorecardsubarr[$id_scorecard_sub] && $id_scorecard_sub){
				$id_scorecardarr = $this->mscorecard->GetChild($id_scorecard_sub);
			}else{
				$id_scorecardarr = $this->mscorecard->GetChild($id_scorecard);
			}
		}

		list($tgl, $bln, $thn) = explode("-",$tgl_efektif);

		if($tahun<>$thn && $tahun){
			$thn = $tahun;
			$bln = '12';
			$tgl = '31';
		}

		if(!$top)
			$top = $this->config->item('risk_top_risiko');

		if(!$top)
			$top = 10;

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
		$this->PartialView("panelbackend/matrixprint");
	}

	public function risikosasaran($kode = null,$idKajianRisiko = null, $id_scorecard=null){
		$this->load->model("Risk_risikoModel",'risikosasaran');
		$this->load->model("Risk_scorecardModel","modelscorecard");

		$mtjeniskajianrisikoarr = $this->data['mtjeniskajianrisikoarr'];
		unset($mtjeniskajianrisikoarr['']);

		if(!$idKajianRisiko)
			$idKajianRisiko = array_keys($mtjeniskajianrisikoarr)[0];

		if($_SESSION[SESSION_APP]['tgl_efektif'])
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		else
			$tgl_efektif = date('d-m-Y');


		$idSasaranStrategis = $this->conn->GetOne("select id_sasaran_strategis 
			from risk_sasaran_strategis 
			where kode = ".$this->conn->escape(trim($kode))."
			and '$tgl_efektif' between nvl(tgl_mulai_efektif,sysdate) and nvl(tgl_akhir_efektif,sysdate)");

		if($idKajianRisiko)
			$scorecardarr = $this->risikosasaran->GetComboDashboard($idKajianRisiko, $tgl_efektif);

		if($id_scorecard)
			$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard);

		$data = $this->risikosasaran->GetRisikoBySasaran($idKajianRisiko, $idSasaranStrategis, $id_scorecardarr);

		echo "<script>function callRisiko(reset) {
		if(reset!=1){
        	var id_scorecard = \$('#id_scorecard').val();
		}
        else{
        	var id_scorecard = 0;
        }
        var id_kajian_risiko = \$('#id_kajian_risiko').val();
      
  $.ajax({
    dataType: 'html',
    url:'".base_url("panelbackend/ajax/risikosasaran")."/$kode/'+id_kajian_risiko+'/'+id_scorecard,
    success:function(response) {
      $('#datarisikostrategis').html(response);
    }
  })
}</script>";

		echo "Kajian Risiko : ".UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr, $idKajianRisiko, true, 'form-control select2', "onchange='callRisiko(1)' style='width:auto'");
		if(is_array($scorecardarr) && count($scorecardarr))
			echo "Risk Profile : ".UI::createSelect('id_scorecard',$scorecardarr, ($scorecardarr[$id_scorecard]?$id_scorecard:null), true, 'form-control select2', "onchange='callRisiko()' style='width:auto'");

		echo "<table class='table table-stripped'>
			<thead>
				<tr>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left' width='10'>No</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Nama Risiko</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Penyebab Risiko</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Dampak Risiko</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Inheren Risk</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Current Risk</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Targeted Residual Risk</th>
				</tr>
			</thead>
			<tbody>";
			$no=1;
	    foreach($data as $r => $val){
	      echo "<tr>";
	      echo "<td>".$no++."</td>";
	      echo "<td ><a href='".site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]")."' target='_BLANK'>$val[nama]</a></td>";
				echo "<td >".nl2br($val['penyebab_risiko'])."</td>";
				echo "<td >".nl2br($val['dampak_risiko'])."</td>";
				echo labeltingkatrisiko($val['inheren']);
				echo labeltingkatrisiko($val['control']);
				echo labeltingkatrisiko($val['residual']);

				echo "</tr>";
	    }
	    if(!$data){
	        echo "<tr><td  colspan='7'>Data kosong</td></tr>";
	    };
		echo "
			<tbody>
			</table>";

	}


	public function set_toggle(){
		$_SESSION[SESSION_APP]['toggle'] = ($this->get['collapse']?0:1);
	}

	public function listjabatan($jabatan=null, $subdit=null){
		$data = array("results"=>array());

		$q = $_GET['q'];

		if($q){
			$this->load->model("Mt_sdm_jabatanModel",'mjabatan');

			$result = $this->mjabatan->GetCombo(null, $q);

			$arr = array();

			foreach ($result as $key => $value) {
				$arr[] = array("id"=>$key,"text"=>$value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function listjabatandirektorat($jabatan=null, $subdit=null){
		$data = array("results"=>array());

		$q = $_GET['q'];

		if($q){
			$this->load->model("Mt_sdm_jabatanModel",'mjabatan');

			$result = $this->mjabatan->GetComboDirektorat(null, $q);

			$arr = array();

			foreach ($result as $key => $value) {
				$arr[] = array("id"=>$key,"text"=>$value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function listpegawai($jabatan=null, $subdit=null){
		$data = array("results"=>array());

		$q = $this->conn->escape_str(strtolower($_GET['q']));
		$jabatan = $this->conn->escape(trim(urldecode($jabatan)));
		$subdit = $this->conn->escape(trim(urldecode($subdit)));

		if($q){
			$sql = "select nid as id, nama as text
				from mt_sdm_karyawan
				where 1=1 ";

			$page_ctrl = $_SERVER['HTTP_REFERER'];

			$sql .= " and  lower(nama) like '%$q%' and rownum <= 10";

			$data['results'] = $this->conn->GetArray($sql);
		}

		echo json_encode($data);
	}

	public function listtaksonomi(){
		$filter = " where 1=1 ";

		if($this->post['id_taksonomi_objective'])
			$filter .= " and b.id_taksonomi_objective = ".$this->conn->escape($this->post['id_taksonomi_objective']);

		if($this->post['id_taksonomi_area'])
			$filter .= " and b.id_taksonomi_area = ".$this->conn->escape($this->post['id_taksonomi_area']);

		if($this->post['nama'])
			$filter .= " and trim(lower(a.nama)) like '%".strtolower(trim($this->post['nama']))."%'";
		
		$rowsrisiko = $this->conn->GetArray("select a.* 
			from mt_taksonomi_risiko a 
			left join mt_taksonomi_area b on a.id_taksonomi_area = b.id_taksonomi_area
			$filter
			order by b.id_taksonomi_objective, b.id_taksonomi_area");

		$filter = " where 1=1 ";

		if($this->post['id_taksonomi_objective'])
			$filter .= " and b.id_taksonomi_objective = ".$this->conn->escape($this->post['id_taksonomi_objective']);

		$rowsarea = $this->conn->GetArray("select * from mt_taksonomi_area b $filter");
		$rowsobjective = $this->conn->GetArray("select * from mt_taksonomi_objective");

		$tempobjective = array();
		$objectivearr = array(''=>'');
		foreach($rowsobjective as $r){
			$tempobjective[$r['id_taksonomi_objective']] = $r;
			$objectivearr[$r['id_taksonomi_objective']] = $r['nama'];
		}

		$temparea = array();
		$areaarr = array(''=>'');
		foreach($rowsarea as $r){
			$temparea[$r['id_taksonomi_area']] = $r;
			$areaarr[$r['id_taksonomi_area']] = $r['nama'];
		}

		$rows = array();
		foreach($rowsrisiko as $r){
			$area = $temparea[$r['id_taksonomi_area']];
			$objective = $tempobjective[$area['id_taksonomi_objective']];

			$r['objective'] = $objective;
			$r['area'] = $area;

			$rows[] = $r;
		}

		echo "<table class='table table-bordered no-margin table-hover table-risiko'>";

		echo "<tr>";
		echo "<th>Taksonomi</th>";
		echo "<th>Area</th>";
		echo "<th>Risiko & Permasalahan</th>";
		echo "<th rowspan='2'></th>";
		echo "</tr>";

		echo "<tr>";
		echo "<th>".UI::createSelect('id_taksonomi_objective_filter',$objectivearr, $this->post['id_taksonomi_objective'], true, 'form-control', "onchange='callTaksonomi()' style='width:100%'")."</th>";
		echo "<th>".UI::createSelect('id_taksonomi_area_filter',$areaarr, $this->post['id_taksonomi_area'], true, 'form-control', "onchange='callTaksonomi()' style='width:100%'")."</th>";
		echo "<th>".UI::createTextBox("nama_filter",$this->post['nama'],null,null,true,'form-control', "onchange='callTaksonomi()' style='width:100%'")."</th>";
		echo "</tr>";

		/*echo "<tr>";
		echo "<th>Kode</th>";
		echo "<th>Nama</th>";
		echo "<th>Kode</th>";
		echo "<th>Nama</th>";
		echo "<th>Kode</th>";
		echo "<th>Nama</th>";
		echo "</tr>";*/

		foreach($rows as $r){
			echo "<tr>";

			/*echo "<td>";
			echo $r['objective']['kode'];
			echo "</td>";*/
			echo "<td>";
			echo $r['objective']['nama'];
			echo "</td>";

			/*echo "<td>";
			echo $r['area']['kode'];
			echo "</td>";*/
			echo "<td>";
			echo $r['area']['nama'];
			echo "</td>";

			/*echo "<td>";
			echo $r['kode'];
			echo "</td>";*/
			echo "<td>";
			echo $r['nama'];
			echo "</td>";
			echo "<td><button type='button' class='btn btn-xs btn-primary' onclick='pilihTaksonomi(".$r['id_taksonomi_risiko'].")'>Pilih</button></td>";

			echo "</tr>";
		}
		echo "</table>";
		echo "<div style='text-align:center'><br/><small>Apabila tidak menemukan risiko yang sesuai silahkan tambahkan risiko baru dengan klik tombol dibawah</small><br/><button type='button' class='btn btn-sm btn-success' onclick='opennew()'>Tambah Baru</button></div>";
	}

	public function detail_merge(){
		$merge = $this->post['merge'];

		$rows = $this->conn->GetArray("select
			rr.*,
			mrki.kode || mrdi.kode as level_risiko_inheren,
			mrkc.kode || mrdc.kode as level_risiko_control,
			mrkr.kode || mrdr.kode as level_residual_evaluasi,
			mrke.kode || mrde.kode as level_residual_evaluasi1,
			msj.nama as risk_owner,
			rs.id_nama_proses,
			rs.id_status_proyek
			from risk_risiko rr
			left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
			left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan
			left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak
			left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan
			left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan
			left join mt_risk_kemungkinan mrkr on mrkr.id_kemungkinan = rr.residual_target_kemungkinan
			left join mt_risk_dampak mrdr on mrdr.id_dampak = rr.residual_target_dampak
			left join mt_risk_kemungkinan mrke on mrke.id_kemungkinan = rr.residual_kemungkinan_evaluasi
			left join mt_risk_dampak mrde on mrde.id_dampak = rr.residual_dampak_evaluasi
			left join mt_sdm_jabatan msj on msj.id_jabatan = rs.owner
			where rr.merge = ".$this->conn->escape($merge));


	    $rs_matrix = $this->data['mtriskmatrix'];
	    $data = array(array());
	    foreach($rs_matrix as $k => $v){
	      $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
	    }

	    $no=1;
	    $top_inheren = array();
	    $top_paska_kontrol = array();
	    $top_paska_mitigasi = array();
	    echo "<table class='table table-bordered table-hover dataTable'>
	    <thead>
		    <tr>
		    	<th width='10px'>No</th>
		    	<th>Nama Risiko</th>
		    	<th>Risk Owner</th>
		    	<th>Inheren Risk</th>
		    	<th>Current Risk</th>
		    	<th>Targeted Risidual Risk</th>
		    </tr>
	    </thead>";
	    foreach($rows as $r => $val){
	      	echo "<tr>";
	      	echo "<td style='text-align:center'>".$no++."</td>";
	      	echo "<td>";
        	echo "<a href='".site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]")."' target='_BLANK'>$val[nama]</a>";
	      	echo "</td>";
	      	echo "<td style='text-align:center'>$val[risk_owner]</td>";

	        $bg = $data[$val['inheren_dampak']][$val['inheren_kemungkinan']]['warna'];
	        echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_risiko_inheren]</td>";

			$bg = $data[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']]['warna'];
			echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_risiko_control]</td>";

			$bg = $data[$val['residual_target_dampak']][$val['residual_target_kemungkinan']]['warna'];
			echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_residual_evaluasi]</td>";

			echo "</tr>";
	    }

	    echo "</table>";
	}
}
