<?php class Risk_risikoModel extends _Model{
	public $table = "risk_risiko";
	public $pk = "id_risiko";
	public $order_default = "id_scorecard, no, nama asc";
	function __construct(){
		parent::__construct();
	}

	public function SelectGrid($arr_param=array(), $str_field="r.*, nvl(r.inheren_kemungkinan,0)||nvl(r.inheren_dampak,0) as inheren, nvl(r.control_kemungkinan_penurunan,0)||nvl(r.control_dampak_penurunan,0) as control, nvl(r.residual_target_kemungkinan,0)||nvl(r.residual_target_dampak,0) as risidual")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'order' => '',
			'filter' => ''
		);
		foreach($arr_param as $key=>$val){
			$arr_params[$key]=$val;
		}

		$arr_params['page'] = ($arr_params['page']/$arr_params['limit'])+1;

		$str_condition = " where 1=1 /*and (status_risiko !='2' or status_risiko is null) */";
		$str_order = "";
		if(!empty($arr_params['filter']))
		{
			$str_condition .= " and ".$arr_params['filter'];
		}
		if(!empty($arr_params['order']))
		{

			list($nama, $od) = explode(" ",$arr_params['order']);
			
			if($nama=='nama'){
				$arr_params['order'] = "to_number(regexp_substr($nama, '\d+')) $od, $nama $od";
			}

			$str_order = "order by ".$arr_params['order'];

		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}


		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko")){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$str_condition .= " and a.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			)";
		}

		$sql = "select
				{$str_field}
				from
				".$this->table." r
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				left join mt_sdm_jabatan j on s.owner = j.id_jabatan";

		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select * from ($sql) a
				{$str_condition}
				{$str_order}");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select * from ($sql) a
				{$str_condition}
				{$str_order}",$arr_params['limit'],$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			($sql) a
			{$str_condition}
		");

		return $arr_return;
	}

	public function SelectGridStatus($arr_param=array(), $str_field="r.*, nvl(r.inheren_kemungkinan,0)||nvl(r.inheren_dampak,0) as inheren, nvl(r.control_kemungkinan_penurunan,0)||nvl(r.control_dampak_penurunan,0) as control, nvl(r.residual_target_kemungkinan,0)||nvl(r.residual_target_dampak,0) as risidual")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'order' => '',
			'filter' => ''
		);
		foreach($arr_param as $key=>$val){
			$arr_params[$key]=$val;
		}

		$arr_params['page'] = ($arr_params['page']/$arr_params['limit'])+1;

		$str_condition = " where 1=1 ";
		$str_order = "";
		if(!empty($arr_params['filter']))
		{
			$str_condition .= " and ".$arr_params['filter'];
		}
		if(!empty($arr_params['order']))
		{

			list($nama, $od) = explode(" ",$arr_params['order']);
			
			if($nama=='nama'){
				$arr_params['order'] = "to_number(regexp_substr($nama, '\d+')) $od, $nama $od";
			}
			
			$str_order = "order by ".$arr_params['order'];
		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}

		$str_condition1 = "";
		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko")){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			if($arr_params['tipe']){
				$str_condition1 .= " and m.penanggung_jawab = ".$this->conn->escape($owner)." and (m.status_konfirmasi != '2')";
				$str_condition1 .= " and not(r.id_status_pengajuan = '1' and m.status_konfirmasi='0') ";
			}else{
				$str_condition1 .= " and s.owner=".$this->conn->escape($owner);
			}
		}
		if($arr_params['tipe']==1){

			$sql = "select
				{$str_field}, m.nama as nama_mitigasi, m.id_mitigasi, m.status_konfirmasi
				from
				".$this->table." r
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				join risk_mitigasi m on r.id_risiko = m.id_risiko and m.is_control <> '1'
				left join mt_sdm_jabatan j on m.penanggung_jawab = j.id_jabatan 
				where s.owner <> m.penanggung_jawab and status_risiko = '1' $str_condition1";
		}elseif($arr_params['tipe']==2){
//dewangga 13-09-2023 menambahi kondisi where m.is_close = 0
			$sql = "select
				{$str_field}, m.nama as nama_control, m.id_control, m.status_konfirmasi
				from
				".$this->table." r
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				join risk_control m on r.id_risiko = m.id_risiko
				left join mt_sdm_jabatan j on m.penanggung_jawab = j.id_jabatan 
				where m.is_close = 0 and s.owner <> m.penanggung_jawab and status_risiko = '1' $str_condition1";
		}else{

			$sql = "select
				{$str_field}
				from
				".$this->table." r
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				left join mt_sdm_jabatan j on s.owner = j.id_jabatan 
				where 1=1 and status_risiko = '1' $str_condition1";
		}

		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select * from ($sql) a
				{$str_condition}
				{$str_order}");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select * from ($sql) a
				{$str_condition}
				{$str_order}",$arr_params['limit'],$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			($sql) a
			{$str_condition}
		");

		return $arr_return;
	}

	public function GetByPk($id){
		if(!$id){
			return array();
		}

		$where = "";
		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko")){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$where = " and (r.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			) or r.id_risiko in (
				select id_risiko from risk_mitigasi
				where penanggung_jawab = ".$this->conn->escape($owner)."
			))";
		}

		$sql = "select * from ".$this->table." r where {$this->pk} = ".$this->conn->qstr($id).$where;
		$ret = $this->conn->GetRow($sql);

		if(!$ret)
			$ret = array();

		$ret['is_finish'] = $this->CekFinish($id);

		$id_sasaran_strategis = $ret['id_sasaran_strategis'];
		$id_sasaran_kegiatan = $ret['id_sasaran_kegiatan'];

		$rowskpi = $this->conn->GetArray("select 
			k.* 
			from risk_risiko_kpi s join risk_kpi k on s.id_kpi = k.id_kpi 
			where id_risiko = ".$this->conn->escape($id));

		$ret['id_kpi'] = array();
		$idkpiarr=array();
		foreach ($rowskpi as $rkpi) {
			$ret['id_kpi'][$rkpi['id_kpi']] = $rkpi['id_kpi'];
			$idkpiarr[] = $rkpi['id_kpi'];
		}

		$addwhere = "";
		if(($idkpiarr)){
			$addwhere = " or k.id_kpi in (".implode(",", $idkpiarr).")";
		}

		$ret['kpi_strategis'] = $this->conn->GetArray("select 
			k.* 
			from risk_sasaran_strategis_kpi s join risk_kpi k on s.id_kpi = k.id_kpi 
			where (id_sasaran_strategis = ".$this->conn->escape($id_sasaran_strategis)." $addwhere)");

		$ret['kpi_kegiatan'] = $this->conn->GetArray("select 
			k.* 
			from risk_sasaran_kegiatan_kpi s 
			join risk_kpi k on s.id_kpi = k.id_kpi 
			where (id_sasaran_kegiatan = ".$this->conn->escape($id_sasaran_kegiatan)." $addwhere)");

		if($ret['id_risiko_sebelum'])
			$ret['risiko_old'] = $this->conn->GetRow("select hambatan_kendala from risk_risiko where id_risiko = ".$this->conn->escape($ret['id_risiko_sebelum']));

		return $ret;
	}

	public function getListKertasKerja($param=array())
	{
		$sql = "
		select
		rs.nama as scorecard,
		rr.id_risiko,
		rr.id_status_pengajuan,
		rr.status_risiko,
		rr.nomor as kode_risiko,
		rss.nama as sasaran_strategis,
		rsk.nama as sasaran_kegiatan,
		rr.nama as risiko,
		rr.penyebab as penyebab,
		rr.dampak as dampak,
		mdj.nama as risk_owner,
		rr.inheren_kemungkinan as inheren_kemungkinan1,
		rr.inheren_dampak as inheren_dampak1,
		mrki.kode as inheren_kemungkinan,
		rr.id_kriteria_kemungkinan as kategori_kemungkinan,
		mrdi.kode as inheren_dampak,
		mrkd.nama as kategori_dampak,
		mrki.kode || mrdi.kode as level_risiko_inheren,
		rc.id_control,
		rc.nama as nama_kontrol,
		rc.menurunkan_dampak_kemungkinan as control_menurunkan,
		rc.is_efektif as control_efektif,
		rr.control_kemungkinan_penurunan,
		rr.control_dampak_penurunan,
		mrkc.kode as kemungkinan_paskakontrol,
		mrdc.kode as dampak_paskakontrol,
		mrkc.kode || mrdc.kode as level_risiko_paskakontrol,
		rm.id_mitigasi,
		rm.nama as nama_mitigasi,
		rm.menurunkan_dampak_kemungkinan as mitigasi_menurunkan,
		rm.dead_line as waktu_pelaksanaan,
		rm.biaya as biaya_mitigasi,
		rm.cba as cba_mitigasi,
		msj.nama as penanggungjawab_mitigasi,
		rm.id_status_progress as capaian_mitigasi,
		rr.residual_target_kemungkinan,
		rr.residual_target_dampak,
		mrkm.kode as kemungkinan_paskamitigasi,
		mrdm.kode as dampak_paskamitigasi,
		mrkm.kode || mrdm.kode as level_risiko_paskamitigasi,
		rm.is_efektif as mitigasi_efektif,
		mrkrsd.kode as kemungkinan_rdual,
		mrdrsd.kode as dampak_rdual,
		mrkrsd.kode || mrdrsd.kode as level_risiko_residual,
		rr.progress_capaian_kinerja as capaian_mitigasi_evaluasi,
		rr.hambatan_kendala as hambatan_kendala,
		rr.penyesuaian_tindakan_mitigasi as penyesuaian_mitigasi,
		rr.kode_aktifitas||' '||rr.nama_aktifitas as aktifitas,
		pnp.kode||' '||pnp.nama as nama_proses,
		pkp.kode||' '||pkp.nama as kelompok_proses,
		pk.kode||' '||pk.nama as kategori_proses,
		sp.prosentase as progress
		from risk_risiko rr
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		left join mt_pb_nama_proses pnp on rs.id_nama_proses = pnp.id_nama_proses
		left join mt_pb_kelompok_proses pkp on pnp.id_kelompok_proses = pkp.id_kelompok_proses
		left join mt_pb_kategori pk on pkp.id_kategori = pk.id_kategori
		left join risk_sasaran_kegiatan rsk on rsk.id_sasaran_kegiatan = rr.id_sasaran_kegiatan
		left join risk_sasaran_strategis rss on rss.id_sasaran_strategis = rr.id_sasaran_strategis
		left join mt_sdm_jabatan mdj on trim(mdj.id_jabatan) = trim(rs.owner)
		left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan AND rr.TGL_RISIKO BETWEEN mrki.SK_TANGGAL_AWAL AND mrki.SK_TANGGAL_AKHIR
		left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak AND rr.TGL_RISIKO BETWEEN mrdi.SK_TANGGAL_AWAL AND mrdi.SK_TANGGAL_AKHIR
		left join risk_control rc on rc.id_risiko = rr.id_risiko
		left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan AND rr.TGL_RISIKO BETWEEN mrkc.SK_TANGGAL_AWAL AND mrkc.SK_TANGGAL_AKHIR
		left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan AND rr.TGL_RISIKO BETWEEN mrdc.SK_TANGGAL_AWAL AND mrdc.SK_TANGGAL_AKHIR
		left join risk_mitigasi rm on rm.id_risiko = rr.id_risiko and (rm.is_control = '0' or rm.is_control is null)
		left join mt_status_progress sp on rm.id_status_progress = sp.id_status_progress
		left join mt_sdm_jabatan msj on msj.id_jabatan = rm.penanggung_jawab
		left join mt_risk_kemungkinan mrkm on mrkm.id_kemungkinan = rr.residual_kemungkinan_evaluasi AND rr.TGL_RISIKO BETWEEN mrkm.SK_TANGGAL_AWAL AND mrkm.SK_TANGGAL_AKHIR
		left join mt_risk_dampak mrdm on mrdm.id_dampak = rr.residual_dampak_evaluasi AND rr.TGL_RISIKO BETWEEN mrdm.SK_TANGGAL_AWAL AND mrdm.SK_TANGGAL_AKHIR
		left join mt_risk_kemungkinan mrkrsd on mrkrsd.id_kemungkinan = rr.residual_target_kemungkinan AND rr.TGL_RISIKO BETWEEN mrkrsd.SK_TANGGAL_AWAL AND mrkrsd.SK_TANGGAL_AKHIR
		left join mt_risk_dampak mrdrsd on mrdrsd.id_dampak = rr.residual_target_dampak AND rr.TGL_RISIKO BETWEEN mrdrsd.SK_TANGGAL_AWAL AND mrdrsd.SK_TANGGAL_AKHIR
		left join mt_risk_kriteria_dampak mrkd on mrkd.id_kriteria_dampak = rr.id_kriteria_dampak 
		";

		if($param['jenis'] && $param['tingkat']){
			switch ($param['jenis']) {
				case '1':
					$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = rr.inheren_dampak 
				    AND rr.TGL_RISIKO BETWEEN mx.SK_TANGGAL_AWAL AND mx.SK_TANGGAL_AKHIR
					and mx.id_kemungkinan = rr.inheren_kemungkinan
					and mx.id_tingkat = ".$this->conn->escape($param['tingkat']);
					break;
				case '2':
					$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = rr.control_dampak_penurunan 
				    AND rr.TGL_RISIKO BETWEEN mx.SK_TANGGAL_AWAL AND mx.SK_TANGGAL_AKHIR
					and mx.id_kemungkinan = rr.control_kemungkinan_penurunan
					and mx.id_tingkat = ".$this->conn->escape($param['tingkat']);
					break;
				case '3':
					$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = rr.residual_target_dampak 
				    AND rr.TGL_RISIKO BETWEEN mx.SK_TANGGAL_AWAL AND mx.SK_TANGGAL_AKHIR
					and mx.id_kemungkinan = rr.residual_target_kemungkinan
					and mx.id_tingkat = ".$this->conn->escape($param['tingkat']);
					break;
			}
		}

		$wherearr = array();

		if($param['id_kajian_risiko']=='semua')
			unset($param['id_kajian_risiko']);

		if(!$param['id_scorecard'])
			$param['id_scorecard'] = array(0);

		if($param['id_kajian_risiko']){
			$this->conn->escape_string($param['id_scorecard']);
			$wherearr[] = "rs.id_scorecard in ('".implode("','", $param['id_scorecard'])."')";
		}

		if($param['id_kajian_risiko'])
			$wherearr[] = "rs.id_kajian_risiko = ".$this->conn->escape($param['id_kajian_risiko']);

		if($param['kd_subdit'])
			$wherearr[] = " mdj.kd_subdit = ".$this->conn->escape($param['kd_subdit']);

		$wherearr1 = array();

		if($param['bulan']){
			$wherearr1[] = "to_char(nvl(rr.tgl_risiko,sysdate),'YYYYMM') <= ".$this->conn->escape($param['tahun'].$param['bulan'])." and (to_char(nvl(rr.tgl_close-1,sysdate),'YYYYMM') >= ".$this->conn->escape($param['tahun'].$param['bulan'])." or rr.tgl_close is null)";
		}else{
			if($param['tahun'])
				$wherearr1[] = "to_char(nvl(rr.tgl_risiko,sysdate),'YYYY') <= ".$this->conn->escape($param['tahun']);

			if($param['status_risiko']===null)
				$wherearr1[] = "rr.STATUS_RISIKO = '1'";
		}

		if($param['status_risiko']=='0')
			$wherearr1[] = "rr.STATUS_RISIKO = '0'";
		elseif($param['status_risiko']=='1')
			$wherearr1[] = "rr.STATUS_RISIKO <> '0'";

		if($param['id_sasaran_strategis']=='semua')
			unset($param['id_sasaran_strategis']);

		if($param['id_sasaran_strategis'])
			$wherearr1[] = "rr.id_sasaran_strategis = ".$this->conn->escape($param['id_sasaran_strategis']);

		if($param['status_risiko']===null)
			$add_islock = "/*rr.is_lock != '0' and */";

		if(($wherearr1)){
			$time = "temp".time();
			$this->conn->Execute("create table $time as 
			select max(id_risiko) as id
			from risk_risiko rr
			where exists (
				select 1 from (
					select 
					max(id_risiko) id
					from risk_risiko rr
					where ".implode(" and ", $wherearr1)."
					group by id_scorecard, nomor_asli
				) a where rr.id_risiko = a.id 
			)
			group by nvl(merge,id_risiko)
			");

			$wherearr[] = " exists (
				select 1 from ($time) a where rr.id_risiko = a.id 
			) ";

		}

		$where = " where $add_islock 1=1 ";
		if(($wherearr))
			$where.= " and ".implode(" and ", $wherearr);

		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko")){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$where .= " and rr.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			)";
		}

		$sql .= $where;
		$sql .= " order by rr.id_sasaran_strategis, rs.owner, rr.id_risiko, rc.no, rm.no";
		$ret = $this->conn->GetRows($sql);

		$this->getPenyebabDampak($ret);

		if(!$ret)
			$ret = array();

		/*$str = get_key_str($ret, 'id_control',"','");

		$rows = $this->conn->GetArray("select *
			from risk_control_efektifitas ce
			where ce.id_control in ('$str')");

		$rows_efektifitas = array();
		foreach ($rows as $r) {
			$rows_efektifitas[$r['id_control']]["efektif_".$r['id_efektifitas']] = $r['is_iya'];
		}

		foreach ($ret as $key => $value) {
			if($rows_efektifitas[$value['id_control']])
				$ret[$key] = array_merge($ret[$key],$rows_efektifitas[$value['id_control']]);
		}*/

		$temparr = array();
		foreach($ret as &$r){
			if($r['id_control'] && !$temparr[$r['id_control']]){
				$temparr[$r['id_control']] = $this->conn->GetArray("select *
				from risk_control_efektifitas ce
				where ce.id_control = ".$this->conn->escape($r['id_control']));
			}

			foreach($temparr[$r['id_control']]  as $r1){
				$r["efektif_".$r1['id_efektifitas']] = $r1['is_iya'];
			}
		}

		$temparr = array();
		foreach($ret as $r){
			if($r['id_mitigasi'])
				$temparr[$r['id_risiko']][]=$r['progress'];
		}

		$temparr1 = array();
		foreach($ret as $r){
			if($r['id_control'])
				$temparr1[$r['id_risiko']][]=($r['control_efektif']=='1'?1:0);
		}

		foreach($ret as &$r){
			if($temparr[$r['id_risiko']])
				$r['ratarataprogress'] = array_sum($temparr[$r['id_risiko']])/count($temparr[$r['id_risiko']]);
			
			if($temparr1[$r['id_risiko']])
				$r['ratarataefektif'] = array_sum($temparr1[$r['id_risiko']])/count($temparr1[$r['id_risiko']])*100;
		}

		if($time)
			$this->conn->Execute("drop table $time");

		return $ret;
	}

	public function getPenyebabDampak(&$rows=array()){
		foreach($rows as &$r){
			$rowsp = $this->conn->GetArray("select * from risk_risiko_penyebab where id_risiko = ".$this->conn->escape($r['id_risiko']));

			if($rowsp){
				$temparr = array();
				$i[0] = 0;
				$i[1] = 0;
				$i[2] = 0;
				foreach($rowsp as $r1){
					$i[(int)$r['jenis']]++;
					$temparr[(int)$r['jenis']][] = $i[(int)$r['jenis']].". ".$r1['nama'];
				}

				$penyebab = null;
				foreach($temparr as $k=>$rs){
					if($k==1)
						$penyebab .= "<b>Internal</b>";
					elseif($k==2)
						$penyebab .= "<b>Eksternal</b>";

					$penyebab .= implode("\n", $rs);
				}

				$r['penyebab'] = $penyebab;
			}

			$rowsp = $this->conn->GetArray("select * from risk_risiko_dampak where id_risiko = ".$this->conn->escape($r['id_risiko']));

			if($rowsp){
				$j=0;
				$temparr = array();
				foreach($rowsp as $r1){
					$j++;
					$temparr[] = $j.". ".$r1['nama'];
				}

				$dampak = implode("\n", $temparr);

				if($dampak)
					$r['dampak'] = $dampak;
			}
		}
	}

	public function _filterRisk($id_kajian_risiko=null, $tahun=null, $bulan=null, $id_scorecardarr=null){

		if(!$tahun)
			$tahun = date('Y');

		if($bulan){
			$where = " and to_char(nvl(r.tgl_risiko,sysdate),'YYYYMM') <= ".$this->conn->escape($tahun.$bulan)." and to_char(nvl(r.tgl_close-1,sysdate),'YYYYMM')>=".$this->conn->escape($tahun.$bulan);
		}else{
			if($tahun)
				$where = " and to_char(nvl(r.tgl_risiko,sysdate),'YYYY') <= ".$this->conn->escape($tahun);

			$where = " and r.STATUS_RISIKO = '1'";
		}

		$where1 = " and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko);
		
		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko")){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$where .= " and r.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			)";
		}

		if($id_scorecardarr){
			$where .= " and r.id_scorecard in (".implode(", ", $id_scorecardarr).")";
		}

		return array($where, $where1);
	}

	public function getCountAll($id_kajian_risiko=null, $tahun=null, $bulan=null, $id_scorecardarr=null)
	{
		list($where, $where1) = $this->_filterRisk($id_kajian_risiko, $tahun, $bulan, $id_scorecardarr);

		$sql = "select count(1) as j
		from risk_risiko r
		join risk_scorecard s on r.id_scorecard = s.id_scorecard
		where 1=1 /*r.is_lock != '0' and r.status_risiko = '1'*/ ".$where.$where1."
		group by s.id_kajian_risiko";

		$ret = array();
		$ret['total_risiko'] = $this->conn->GetOne($sql);

		$sql = "select avg(PROSENTASE)
		from risk_risiko r
		join risk_scorecard s on r.id_scorecard = s.id_scorecard
		join risk_mitigasi m on r.id_risiko = m.id_risiko
		join mt_status_progress p on m.id_status_progress = p.id_status_progress
		where 1=1 ".$where.$where1."
		group by s.id_kajian_risiko";

		$ret['progress_mitigasi'] = round($this->conn->GetOne($sql),2);

		$sql = "select count(case when is_efektif = 1 then 1 else null end)/count(1)*100
		from risk_risiko r
		join risk_scorecard s on r.id_scorecard = s.id_scorecard
		join risk_control c on r.id_risiko = c.id_risiko
		where 1=1 ".$where.$where1."
		group by s.id_kajian_risiko";

		$ret['control_efektif'] = round($this->conn->GetOne($sql),2);

		return $ret;
	}

	public function GetGrafikTaksonomi()
	{
		$where = " where 1=1 ";
		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko") && $_SESSION[SESSION_APP]['login']){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$where .= " and rr.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			)";
		}


		$tgl_efektif = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		list($tgl, $bln, $tahun) = explode("-",$tgl_efektif);

		$where .= " and rr.STATUS_RISIKO = '1' 
		and to_char(nvl(rr.tgl_risiko,sysdate),'YYYY') = ".$this->conn->escape($tahun);

		$valuarr = $this->conn->GetList("select count(1) val, id_taksonomi_objective as key
			from risk_risiko rr 
			join mt_taksonomi_risiko tr on rr.id_taksonomi_risiko = tr.id_taksonomi_risiko
			join mt_taksonomi_area ta on tr.id_taksonomi_area = ta.id_taksonomi_area
			$where and rr.id_taksonomi_risiko is not null
			group by id_taksonomi_objective
			order by id_taksonomi_objective");

		$objectivearr = $this->conn->GetList("select id_taksonomi_objective as key, nama as val from mt_taksonomi_objective");

		$rows = array();
		foreach($objectivearr as $k=>$v){
			$rows[] = array("category"=>$v, "value"=>(int)$valuarr[$k]);
		}
		return $rows;
	}

	public function GetTingkatTahunan()
	{
		$where = " where 1=1 ";
		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko") && $_SESSION[SESSION_APP]['login']){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$where .= " and r.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			)";
		}


		$tgl_efektif = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		list($tgl, $bln, $tahun) = explode("-",$tgl_efektif);

		$where .= " and r.STATUS_RISIKO = '1' 
		and to_char(nvl(r.tgl_risiko,sysdate),'YYYY') <= ".$this->conn->escape($tahun);

		$rows = $this->conn->GetArray("select round(avg(nvl(tingkat,0)),2) as value, data 
			from (select to_char(nvl(r.tgl_risiko,sysdate),'YYYY') data,
			tr.rating*dr.rating as tingkat
			from risk_risiko r
			left join Mt_risk_kemungkinan tr on tr.id_kemungkinan = r.CONTROL_KEMUNGKINAN_PENURUNAN
			left join Mt_risk_dampak dr on dr.id_dampak = r.CONTROL_DAMPAK_PENURUNAN
			$where and id_taksonomi_risiko is not null) a
			group by data
			order by data");
		return $rows;
	}

	public function GetTrendTaksonomi($tahun, $id_taksonomi=null)
	{
		$where = " where 1=1 ";
		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko") && $_SESSION[SESSION_APP]['login']){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$where .= " and r.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			)";
		}

		$where .= " and r.STATUS_RISIKO = '1' 
		and to_char(nvl(r.tgl_risiko,sysdate),'YYYY') = ".$this->conn->escape($tahun);

		if(!$id_taksonomi){
			$rows = $this->conn->GetArray("select 
				round(avg(nvl(tingkat,0)),2) as value, label, id_taksonomi_objective 
				from (
					SELECT
			            ta.id_taksonomi_objective,
			            t1.nama AS label,
			            rk.rating * rd.rating AS tingkat
			        FROM
			            risk_risiko              r
			            LEFT JOIN mt_risk_kemungkinan      rk ON rk.id_kemungkinan = r.control_kemungkinan_penurunan
			            LEFT JOIN mt_risk_dampak           rd ON rd.id_dampak = r.control_dampak_penurunan
			            JOIN mt_taksonomi_risiko      tr ON r.id_taksonomi_risiko = tr.id_taksonomi_risiko
			            JOIN mt_taksonomi_area        ta ON tr.id_taksonomi_area = ta.id_taksonomi_area
			            LEFT JOIN mt_taksonomi_objective   t1 ON ta.id_taksonomi_objective = t1.id_taksonomi_objective
					$where and r.id_taksonomi_risiko is not null
				) a
				group by label, id_taksonomi_objective
				order by id_taksonomi_objective");
		}else{
			$where .= " and t1.id_taksonomi_objective = ".$this->conn->escape($id_taksonomi);

			$rows = $this->conn->GetArray("select 
				round(avg(nvl(tingkat,0)),2) as value, label, id_taksonomi_area 
				from (
					select tr.id_taksonomi_area,
					t1.nama as label,
					rk.rating*rd.rating as tingkat
					from risk_risiko r
					left join Mt_risk_kemungkinan rk on rk.id_kemungkinan = r.CONTROL_KEMUNGKINAN_PENURUNAN
					left join Mt_risk_dampak rd on rd.id_dampak = r.CONTROL_DAMPAK_PENURUNAN
					join mt_taksonomi_risiko tr on r.id_taksonomi_risiko = tr.id_taksonomi_risiko
					left join mt_taksonomi_area t1 on tr.id_taksonomi_area = t1.id_taksonomi_area
					$where and r.id_taksonomi_risiko is not null
				) a
				group by label, id_taksonomi_area
				order by id_taksonomi_area");
		}

		return $rows;
	}

	public function getListRiskProfile($param=array())
	{
		$where = "";
		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko") && $_SESSION[SESSION_APP]['login']){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$where .= " and rr.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			)";
		}


		$filarr['i']="mrdi.rating is not null and mrki.rating is not null";
		$filarr['c']="mrdc.rating is not null and mrkc.rating is not null";
		$filarr['r']="mrdr.rating is not null and mrkr.rating is not null";

		$arr['i'] = "mrdi.rating desc, mrki.rating desc";
		$arr['c'] = "mrdc.rating desc, mrkc.rating desc";
		$arr['r'] = "mrdr.rating desc, mrkr.rating desc";

		$sql = "select
			rr.*,
			mrki.kode || mrdi.kode as level_risiko_inheren,
			mrkc.kode || mrdc.kode as level_risiko_control,
			mrkr.kode || mrdr.kode as level_residual_evaluasi,
			mrke.kode || mrde.kode as level_residual_evaluasi1,
			msj.nama as risk_owner,
			ss.nama as sasaran_strategis,
			sk.nama as sasaran_kegiatan,
			rs.id_nama_proses,
			rs.id_status_proyek,
			rr.kode_aktifitas||' '||rr.nama_aktifitas as aktifitas,
			pnp.kode||' '||pnp.nama as nama_proses,
			pkp.kode||' '||pkp.nama as kelompok_proses,
			pk.kode||' '||pk.nama as kategori_proses
			from risk_risiko rr
			left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
			left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan AND rr.TGL_RISIKO BETWEEN mrki.SK_TANGGAL_AWAL AND mrki.SK_TANGGAL_AKHIR

			left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak AND rr.TGL_RISIKO BETWEEN mrdi.SK_TANGGAL_AWAL AND mrdi.SK_TANGGAL_AKHIR and to_date('".$param['tahun'].$param['bulan']."', 'YYYYMM') between mrdi.sk_tanggal_awal and mrdi.sk_tanggal_akhir

			left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan AND rr.TGL_RISIKO BETWEEN mrkc.SK_TANGGAL_AWAL AND mrkc.SK_TANGGAL_AKHIR

			left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan AND rr.TGL_RISIKO BETWEEN mrdc.SK_TANGGAL_AWAL AND mrdc.SK_TANGGAL_AKHIR and to_date('".$param['tahun'].$param['bulan']."', 'YYYYMM') between mrdc.sk_tanggal_awal and mrdc.sk_tanggal_akhir

			left join mt_risk_kemungkinan mrkr on mrkr.id_kemungkinan = rr.residual_target_kemungkinan AND rr.TGL_RISIKO BETWEEN mrkr.SK_TANGGAL_AWAL AND mrkr.SK_TANGGAL_AKHIR

			left join mt_risk_dampak mrdr on mrdr.id_dampak = rr.residual_target_dampak AND rr.TGL_RISIKO BETWEEN mrdr.SK_TANGGAL_AWAL AND mrdr.SK_TANGGAL_AKHIR and to_date('".$param['tahun'].$param['bulan']."', 'YYYYMM') between mrdr.sk_tanggal_awal and mrdr.sk_tanggal_akhir

			left join mt_risk_kemungkinan mrke on mrke.id_kemungkinan = rr.residual_kemungkinan_evaluasi AND rr.TGL_RISIKO BETWEEN mrke.SK_TANGGAL_AWAL AND mrke.SK_TANGGAL_AKHIR

			left join mt_risk_dampak mrde on mrde.id_dampak = rr.residual_dampak_evaluasi AND rr.TGL_RISIKO BETWEEN mrde.SK_TANGGAL_AWAL AND mrde.SK_TANGGAL_AKHIR and to_date('".$param['tahun'].$param['bulan']."', 'YYYYMM') between mrde.sk_tanggal_awal and mrde.sk_tanggal_akhir
			
			left join mt_sdm_jabatan msj on msj.id_jabatan = rs.owner
			left join risk_sasaran_strategis ss on rr.id_sasaran_strategis = ss.id_sasaran_strategis
			left join risk_sasaran_kegiatan sk on rr.id_sasaran_kegiatan = sk.id_sasaran_kegiatan
			left join mt_pb_nama_proses pnp on rs.id_nama_proses = pnp.id_nama_proses
			left join mt_pb_kelompok_proses pkp on pnp.id_kelompok_proses = pkp.id_kelompok_proses
			left join mt_pb_kategori pk on pkp.id_kategori = pk.id_kategori";

		if($param['id_kajian_risiko']=='semua')
			unset($param['id_kajian_risiko']);

		if(!$param['all']){
			if(!$param['id_scorecard'])
				$param['id_scorecard'] = array(0);

			if($param['id_kajian_risiko']){
				$this->conn->escape_string($param['id_scorecard']);
				$wherearr[] = "rs.id_scorecard in ('".implode("','", $param['id_scorecard'])."')";
			}
		}elseif($param['id_scorecard'])
			$wherearr[] = "rs.id_scorecard = ".$this->conn->escape($param['id_scorecard']);

		if($param['id_kajian_risiko'])
			$wherearr[] = "rs.id_kajian_risiko = ".$this->conn->escape($param['id_kajian_risiko']);

		$wherearr1 = array();

		if($param['bulan']&&$param['tanggal']){
			$wherearr1[] = "to_char(nvl(rr.tgl_risiko,sysdate),'YYYYMMDD') <= ".$this->conn->escape($param['tahun'].$param['bulan'].$param['tanggal'])." and to_char(nvl(rr.tgl_close-1,sysdate),'YYYYMMDD') >= ".$this->conn->escape($param['tahun'].$param['bulan'].$param['tanggal']);
		}elseif($param['bulan']){
			$wherearr1[] = "to_char(nvl(rr.tgl_risiko,sysdate),'YYYYMM') <= ".$this->conn->escape($param['tahun'].$param['bulan'])." and to_char(nvl(rr.tgl_close-1,sysdate),'YYYYMM') >= ".$this->conn->escape($param['tahun'].$param['bulan']);
		}else{
			if($param['tahun'])
				$wherearr1[] = "to_char(nvl(rr.tgl_risiko,sysdate),'YYYY') <= ".$this->conn->escape($param['tahun']);

			$wherearr1[] = "rr.STATUS_RISIKO = '1'";
		}

		if($param['id_sasaran_strategis']=='semua')
			unset($param['id_sasaran_strategis']);

		if($param['id_sasaran_strategis'])
			$wherearr1[] = "rr.id_sasaran_strategis = ".$this->conn->escape($param['id_sasaran_strategis']);

		if(($wherearr1)){
			$time = "temp".time();
			$this->conn->Execute("create table $time as 
			select max(id_risiko) as id
			from risk_risiko rr
			where exists (
				select 1 from (
					select 
					max(id_risiko) id
					from risk_risiko rr
					where ".implode(" and ", $wherearr1)."
					group by id_scorecard, nomor_asli
				) a where rr.id_risiko = a.id 
			)
			group by nvl(merge,id_risiko)
			");

			$wherearr[] = " exists (
				select 1 from ($time) a where rr.id_risiko = a.id 
			) ";

		}

		$sql.= " where 1=1 /*and rr.is_lock != '0'*/ ".$where;
		if(($wherearr))
			$sql.= " and ".implode(" and ", $wherearr);

		if(!$param['top'])
			$param['top'] = 10;


		if($param['rating']){
			$filterrating = array();
			foreach (str_split($param['rating']) as $k => $v) {
				if($filarr[$v])
					$filterrating[] = $filarr[$v];

				break;
			}

			$sql.= " and ".implode(" and ", $filterrating);

			$orderrating = array();

			if($param['order']){
				$orderrating[] = str_replace("desc","desc nulls last",$arr[$param['order']]);
			}else{
				foreach (str_split($param['rating']) as $k => $v) {
					if($arr[$v])
						$orderrating[] = $arr[$v];
				}
			}
		}

		if(!($orderrating))
			return array();

		$sql.=" order by ".implode(",", $orderrating).", nvl(urutan,0) asc";



		$sql = "select * from($sql) a where ROWNUM <= ".(int)$param['top'];
		

		//echo $sql;
		$ret = $this->conn->GetRows($sql);

		$this->getPenyebabDampak($ret);

		if(!$ret)
			$ret = array();

		foreach($ret as &$r){
			if($r['nama_merge'])
				$r['nama'] = $r['nama_merge'];

			if($r['merge']){
				$r['risk_owner'] = $this->conn->GetListStr("select c.nama as val 
					from risk_risiko a
					join risk_scorecard b on a.id_scorecard = b.id_scorecard
					join mt_sdm_jabatan c on b.owner = c.id_jabatan 
					where a.merge = ".$this->conn->escape($r['merge']));
			}
		}

		if($time)
			$this->conn->Execute("drop table $time");

		return $ret;
	}

	function GetRisikoBySasaran($idKajianRisiko, $idSasaranStrategis=null, $id_scorecardarr=array()){
		$where = "";
		if(!$this->ci->Access('view_all_direktorat',"panelbackend/risk_risiko")){
			$owner = $_SESSION[SESSION_APP]['pic'];

			if(!$owner){
				$owner = '0';
			}
			$where .= " and rr.id_scorecard in (
				select id_scorecard
				from risk_scorecard
				where id_kajian_risiko = '3'
				or (
				id_kajian_risiko <> '3'
				and
				owner=".$this->conn->escape($owner)."
				)
				union
				select id_scorecard
				from risk_scorecard_view
				where id_jabatan = ".$this->conn->escape($owner)."
			)";
		}

		if(is_array($id_scorecardarr) && count($id_scorecardarr))
			$where .= " and rr.id_scorecard in (".implode(",", $id_scorecardarr).")";

		$sql = "
		select rss.nama as sasaran_strategis, rr.nama, rr.ID_SCORECARD, rr.ID_RISIKO, rr.penyebab as penyebab_risiko, rr.dampak as dampak_risiko, nvl(rr.inheren_kemungkinan,0)||nvl(rr.inheren_dampak,0) as inheren, nvl(rr.control_kemungkinan_penurunan,0)||nvl(rr.control_dampak_penurunan,0) as control, nvl(rr.residual_target_kemungkinan,0)||nvl(rr.residual_target_dampak,0) as risidual
		from risk_risiko rr
		left join risk_sasaran_strategis rss on rss.ID_SASARAN_STRATEGIS = rr.ID_SASARAN_STRATEGIS
		left join risk_scorecard rs on rs.ID_SCORECARD = rr.ID_SCORECARD
		left join mt_risk_kajian_risiko mrkr on mrkr.ID_KAJIAN_RISIKO = rs.ID_KAJIAN_RISIKO
		where rs.id_kajian_risiko = ".$this->conn->escape($idKajianRisiko)." and rr.is_lock != '0' and rr.ID_SASARAN_STRATEGIS = ".$this->conn->escape($idSasaranStrategis).$where;


		$ret = $this->conn->GetRows($sql);

		if(!$ret)
			$ret = array();

		return $ret;
	}

	private function CekFinish($id){

		return (int)$this->conn->GetOne("
			select s.open_evaluasi
			from risk_scorecard s
			join risk_risiko r on s.id_scorecard = r.id_scorecard
			where id_risiko = ".$this->conn->escape($id)."
		");
		/*$open_evaluasi = $this->config->item('open_evaluasi');

		if($open_evaluasi)
			return 1;

		return 0;*/

		/*$cek = $this->conn->GetOne("
			select count(1) 
			from risk_mitigasi r
			join mt_status_progress p on r.id_status_progress = p.id_status_progress
			where id_risiko = ".$this->conn->escape($id)
		);

		if(!$cek)
			return 0;

		return !$this->conn->GetOne("
			select count(1) 
			from risk_mitigasi r
			join mt_status_progress p on r.id_status_progress = p.id_status_progress
			where id_risiko = ".$this->conn->escape($id)."
			and p.prosentase <>  100"
		);*/
	}

	public function GetRatingDKRisiko($id){
		if(!$id){
			return array();
		}
		$sql = "select r.*, tr.RATING as rating_kemungkinancr, dr.RATING as rating_dampakcr, mtr.RATING as rating_tingkatrisikors, mdr.RATING as rating_dampakrisikors
						from RISK_RISIKO r
						left join Mt_risk_kemungkinan tr on tr.id_kemungkinan = r.CONTROL_KEMUNGKINAN_PENURUNAN
						left join Mt_risk_dampak dr on dr.id_dampak = r.CONTROL_DAMPAK_PENURUNAN
						left join Mt_risk_kemungkinan mtr on mtr.id_kemungkinan = r.residual_target_kemungkinan
						left join Mt_risk_dampak mdr on mdr.id_dampak = r.RESIDUAL_TARGET_DAMPAK
						where r.ID_RISIKO = ".$this->conn->escape($id);
		$ret = $this->conn->GetRow($sql);

		if(!$ret)
			$ret = array();

		$ret['is_finish'] = $this->CekFinish($id);

		$id_sasaran_strategis = $ret['id_sasaran_strategis'];
		$id_sasaran_kegiatan = $ret['id_sasaran_kegiatan'];

		$ret['kpi_strategis'] = $this->conn->GetOne("select kpi from risk_sasaran_strategis where id_sasaran_strategis = ".$this->conn->escape($id_sasaran_strategis));
		$ret['kpi_kegiatan'] = $this->conn->GetOne("select kpi from risk_sasaran_kegiatan where id_sasaran_kegiatan = ".$this->conn->escape($id_sasaran_kegiatan));

		return $ret;
	}

	//for membuat no risiko otomatis berdasarkan kajian risiko
	public function getNomorRisiko($id=null, $idsasaran=null, $tgl_risiko=null, $kode_aktifitas=null, $isformat=false)
	{
		if(!$tgl_risiko)
			$tgl_risiko = date("d-m-Y");

		$sql = "select trim(kr.kode) ||' __koderka__'||' '|| trim(nvl(kd_subdit, kd_direktorat)) ||' __strategis__ ' as no_risiko
				from risk_scorecard s
				left join mt_risk_kajian_risiko kr on kr.id_kajian_risiko = s.id_kajian_risiko
				join mt_sdm_jabatan j on trim(s.owner) = trim(j.id_jabatan)
				where s.id_scorecard = ".$this->conn->escape($id);

		$rs = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = ".$this->conn->escape($id));

		if($rs['id_parent_scorecard']){
			$rs = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = ".$this->conn->escape($rs['id_parent_scorecard']));
			$kode_r = substr($rs['nama'], 0,3);
		}
		
		if($rs['id_parent_scorecard']){
			$rs = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = ".$this->conn->escape($rs['id_parent_scorecard']));
			$kode_r = substr($rs['nama'], 0,3);
		}
		
		if($rs['id_parent_scorecard']){
			$rs = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = ".$this->conn->escape($rs['id_parent_scorecard']));
			$kode_r = substr($rs['nama'], 0,3);
		}

		if($rs['id_parent_scorecard']){
			$rs = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = ".$this->conn->escape($rs['id_parent_scorecard']));
			$kode_r = substr($rs['nama'], 0,3);
		}

		if($rs['id_parent_scorecard']){
			$rs = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = ".$this->conn->escape($rs['id_parent_scorecard']));
			$kode_r = substr($rs['nama'], 0,3);
		}



		$format = trim($this->conn->GetOne($sql));

		$kode_sasaran = $this->conn->GetOne("select kode from risk_sasaran_strategis where id_sasaran_strategis = ".$this->conn->escape($idsasaran));

		$format = str_replace("__strategis__", trim(str_replace("PS", "", strtoupper($kode_sasaran))), $format);

		$format = str_replace("__koderka__", $kode_r, $format);

		if($kode_aktifitas)
			$format .= ' '.$kode_aktifitas;

		$format .= ' '.substr($tgl_risiko, -2);

		if($isformat)
			return $format;

		$autoincrement = "select max(nomor_asli) as nomor from risk_risiko where nomor_asli like '$format%' and STATUS_RISIKO ='1' and id_scorecard = ".$this->conn->escape($id);


		$formatAutoIncrement = $this->conn->GetOne($autoincrement);

		$nomor = (int)str_replace($format,'',$formatAutoIncrement);
		$cek = explode(".", $nomor);

		if(($cek)>1)
			$nomor = $cek[0];

		$nomor++;

		$ret = $format.str_pad($nomor,2,'0',STR_PAD_LEFT);

		return $ret;
	}

	// get combo untuk kajian risiko operasional
	function GetComboDashboard($id_kajian_risiko=null,$tgl_efektif=null) {
		if(!$tgl_efektif)
			$tgl_efektif = date("d-m-Y");

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		if($tgl_efektif)
			$filter = " and '$tgl_efektif' between nvl(tgl_mulai_efektif,'$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif')";

		if($id_kajian_risiko == 'semua')
			$sql = "select id_scorecard, nama from risk_scorecard where id_parent_scorecard is null ".$filter." order by id_scorecard";
		else
			$sql = "select id_scorecard, nama from risk_scorecard where id_parent_scorecard is null and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko).$filter." order by id_scorecard";

		$rows = $this->conn->GetArray($sql);

		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['id_scorecard']] = $r['nama'];
		}
		return $data;
	}

}
