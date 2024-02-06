<?php class Risk_risikoModel extends _Model{
	public $table = "risk_risiko";
	public $pk = "id_risiko";
	function __construct(){
		parent::__construct();
	}

	public function SelectGrid($arr_param=array(), $str_field="r.*, t.id_tingkat as inheren, t2.id_tingkat as control, t3.id_tingkat as risidual, j.id_struktur")
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

		$str_condition = " where 1=1 and (status_risiko !='2' or status_risiko is null) ";
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
				left join mt_risk_matrix t on r.inheren_dampak = t.id_dampak and r.inheren_kemungkinan = t.id_kemungkinan
				left join mt_risk_matrix t2 on r.control_dampak_penurunan = t2.id_dampak and r.control_kemungkinan_penurunan = t2.id_kemungkinan
				left join mt_risk_matrix t3 on r.residual_target_dampak = t3.id_dampak and r.residual_target_kemungkinan = t3.id_kemungkinan
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

	public function SelectGridStatus($arr_param=array(), $str_field="r.*, t.id_tingkat as inheren, t2.id_tingkat as control, t3.id_tingkat as risidual, j.id_struktur")
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
		if($arr_params['tipe']){

			$sql = "select
				{$str_field}, m.nama as nama_mitigasi, m.id_mitigasi, m.status_konfirmasi
				from
				".$this->table." r
				left join mt_risk_matrix t on r.inheren_dampak = t.id_dampak and r.inheren_kemungkinan = t.id_kemungkinan
				left join mt_risk_matrix t2 on r.control_dampak_penurunan = t2.id_dampak and r.control_kemungkinan_penurunan = t2.id_kemungkinan
				left join mt_risk_matrix t3 on r.residual_target_dampak = t3.id_dampak and r.residual_target_kemungkinan = t3.id_kemungkinan
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				join risk_mitigasi m on r.id_risiko = m.id_risiko
				left join mt_sdm_jabatan j on m.penanggung_jawab = j.id_jabatan 
				where s.owner <> m.penanggung_jawab and status_risiko = '1' $str_condition1";
		}else{

			$sql = "select
				{$str_field}
				from
				".$this->table." r
				left join mt_risk_matrix t on r.inheren_dampak = t.id_dampak and r.inheren_kemungkinan = t.id_kemungkinan
				left join mt_risk_matrix t2 on r.control_dampak_penurunan = t2.id_dampak and r.control_kemungkinan_penurunan = t2.id_kemungkinan
				left join mt_risk_matrix t3 on r.residual_target_dampak = t3.id_dampak and r.residual_target_kemungkinan = t3.id_kemungkinan
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
		rr.id_risiko,
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
		mrdi.kode as inheren_dampak,
		mrkd.nama as kategori,
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
		rr.penyesuaian_tindakan_mitigasi as penyesuaian_mitigasi
		from risk_risiko rr
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		left join risk_sasaran_kegiatan rsk on rsk.id_sasaran_kegiatan = rr.id_sasaran_kegiatan
		left join risk_sasaran_strategis rss on rss.id_sasaran_strategis = rr.id_sasaran_strategis
		left join mt_sdm_jabatan mdj on trim(mdj.id_jabatan) = trim(rs.owner)
		left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan
		left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak
		left join risk_control rc on rc.id_risiko = rr.id_risiko
		left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan
		left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan
		left join risk_mitigasi rm on rm.id_risiko = rr.id_risiko
		left join mt_sdm_jabatan msj on msj.id_jabatan = rm.penanggung_jawab
		left join mt_risk_kemungkinan mrkm on mrkm.id_kemungkinan = rr.residual_kemungkinan_evaluasi
		left join mt_risk_dampak mrdm on mrdm.id_dampak = rr.residual_dampak_evaluasi
		left join mt_risk_kemungkinan mrkrsd on mrkrsd.id_kemungkinan = rr.residual_target_kemungkinan
		left join mt_risk_dampak mrdrsd on mrdrsd.id_dampak = rr.residual_target_dampak
		left join mt_risk_kriteria_dampak mrkd on mrkd.id_kriteria_dampak = rr.id_kriteria_dampak
		";

		if($param['jenis'] && $param['tingkat']){
			switch ($param['jenis']) {
				case '1':
					$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = rr.inheren_dampak 
					and mx.id_kemungkinan = rr.inheren_kemungkinan
					and mx.id_tingkat = ".$this->conn->escape($param['tingkat']);
					break;
				case '2':
					$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = rr.control_dampak_penurunan 
					and mx.id_kemungkinan = rr.control_kemungkinan_penurunan
					and mx.id_tingkat = ".$this->conn->escape($param['tingkat']);
					break;
				case '3':
					$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = rr.residual_target_dampak 
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

		$wherearr1 = array();

		if($param['bulan']){
			$wherearr1[] = "to_char(nvl(rr.tgl_risiko,sysdate),'YYYYMM') <= ".$this->conn->escape($param['tahun'].$param['bulan']);
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

			$list_str = $this->conn->GetListStr("select 
			max(id_risiko) as val
			from risk_risiko rr
			where rr.is_lock = '1' and ".implode(" and ", $wherearr1)."
			group by nomor_asli, id_scorecard");

			if($list_str)
				$wherearr[] = " rr.id_risiko in ($list_str) ";
			else
				$wherearr[] = " rr.id_risiko in (0) ";

		}

		$where = " where rr.is_lock = '1' and 1=1 ";
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
		$sql .= " order by rs.owner, rr.id_risiko, rc.nama, rm.nama";
		$ret = $this->conn->GetRows($sql);

		if(!$ret)
			$ret = array();

		$str = get_key_str($ret, 'id_control',"','");

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
		}

		return $ret;
	}

	public function getCountAll($id_kajian_risiko=null, $tahun=null, $bulan=null)
	{
		if(!$tahun)
			$tahun = date('Y');

		/*if(!$bulan)
			$bulan = date('m');
*/
		/*$where = " and to_char(nvl(r.tgl_risiko,sysdate),'YYYY') = ".$this->conn->escape($tahun);

		$where .= " and to_char(nvl(r.tgl_risiko,sysdate),'MM') <= ".$this->conn->escape($bulan);*/



		if($bulan){
			$where = " and to_char(nvl(r.tgl_risiko,sysdate),'YYYYMM') <= ".$this->conn->escape($tahun.$bulan)." and to_char(nvl(r.tgl_close,sysdate),'YYYYMM')>=".$this->conn->escape($tahun.$bulan);
		}else{
			if($tahun)
				$where = " and to_char(nvl(r.tgl_risiko,sysdate),'YYYY') <= ".$this->conn->escape($tahun);

			$where = " and r.STATUS_RISIKO = '1'";
		}

		$where1 = " and r.id_scorecard in (select id_scorecard from risk_scorecard where id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko).")";
		
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

		$sql = "select count(r.id_risiko) as j, t.nama
		from mt_risk_matrix m
		join mt_risk_tingkat t on t.id_tingkat = m.id_tingkat
		left join risk_risiko r on r.inheren_kemungkinan = m.id_kemungkinan and r.inheren_dampak = m.id_dampak and r.is_lock = '1' /*and r.status_risiko = '1'*/ ".$where.$where1."
		group by t.nama,t.id_tingkat
		order by t.id_tingkat desc";

		$rows = $this->conn->GetArray($sql);

		$ret = array();
		$ret['total_inheren'] = 0;
		foreach ($rows as $r) {
			$ret['inheren'][$r['nama']] = $r['j'];
			$ret['total_inheren']+=$r['j'];
		}

		$sql = "select count(r.id_risiko) as j, t.nama
		from mt_risk_tingkat t 
		join mt_risk_matrix m on t.id_tingkat = m.id_tingkat
		left join risk_risiko r on r.control_kemungkinan_penurunan = m.id_kemungkinan and r.control_dampak_penurunan = m.id_dampak and r.is_lock = '1' /*and r.status_risiko = '1'*/ ".$where.$where1."
		group by t.nama,t.id_tingkat
		order by t.id_tingkat desc";

		$rows = $this->conn->GetArray($sql);
		$ret['total_control'] = 0;
		foreach ($rows as $r) {
			$ret['control'][$r['nama']] = $r['j'];
			$ret['total_control']+=$r['j'];
		}

		$sql = "select count(r.id_risiko) as j, t.nama
		from mt_risk_tingkat t 
		join mt_risk_matrix m on t.id_tingkat = m.id_tingkat
		left join risk_risiko r on r.residual_target_kemungkinan = m.id_kemungkinan and r.residual_target_dampak = m.id_dampak and r.is_lock = '1' /*and r.status_risiko = '1'*/ ".$where.$where1."
		group by t.nama,t.id_tingkat
		order by t.id_tingkat desc";

		$rows = $this->conn->GetArray($sql);
		$ret['total_residual'] = 0;
		foreach ($rows as $r) {
			$ret['residual'][$r['nama']] = $r['j'];
			$ret['total_residual']+=$r['j'];
		}

		$sql = "select count(1) as j, s.id_kajian_risiko
		from risk_risiko r
		join risk_scorecard s on r.id_scorecard = s.id_scorecard
		where r.is_lock = '1' /*and r.status_risiko = '1'*/ ".$where."
		group by s.id_kajian_risiko";

		$rows = $this->conn->GetArray($sql);
		$ret['total_risiko_kajian'] = array();
		foreach ($rows as $r) {
			$ret['total_risiko_kajian'][$r['id_kajian_risiko']]=$r['j'];
		}

		return $ret;
	}

	public function getListRiskProfile($param=array())
	{
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
			msj.nama as risk_owner,
			ss.nama as sasaran_strategis,
			sk.nama as sasaran_kegiatan
			from risk_risiko rr
			left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
			left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan
			left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak
			left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan
			left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan
			left join mt_risk_kemungkinan mrkr on mrkr.id_kemungkinan = rr.residual_target_kemungkinan
			left join mt_risk_dampak mrdr on mrdr.id_dampak = rr.residual_target_dampak
			left join mt_sdm_jabatan msj on msj.id_jabatan = rs.owner
			left join risk_sasaran_strategis ss on rr.id_sasaran_strategis = ss.id_sasaran_strategis
			left join risk_sasaran_kegiatan sk on rr.id_sasaran_kegiatan = sk.id_sasaran_kegiatan";

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

		if($param['bulan']){
			$wherearr1[] = "to_char(nvl(rr.tgl_risiko,sysdate),'YYYYMM') <= ".$this->conn->escape($param['tahun'].$param['bulan'])." and to_char(nvl(rr.tgl_close,sysdate),'YYYYMM') >= ".$this->conn->escape($param['tahun'].$param['bulan']);
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
			$list_str = $this->conn->GetListStr("select 
			max(id_risiko) as val
			from risk_risiko rr
			where ".implode(" and ", $wherearr1)."
			group by nomor_asli, id_scorecard");

			if($list_str)
				$wherearr[] = " rr.id_risiko in ($list_str) ";
			else
				$wherearr[] = " rr.id_risiko in (0) ";

		}

		$sql.= " where 1=1 /*and rr.is_lock = '1'*/ ".$where;
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
				$orderrating[] = $arr[$param['order']];
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
		
		$ret = $this->conn->GetRows($sql);

		if(!$ret)
			$ret = array();

		return $ret;
	}

	function GetRisikoBySasaran($idKajianRisiko, $idSasaranStrategis){
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

		$sql = "
		select rss.nama as sasaran_strategis, rr.nama, rr.ID_SCORECARD, rr.ID_RISIKO, rr.penyebab as penyebab_risiko, rr.dampak as dampak_risiko, m1.id_tingkat as inheren, m2.id_tingkat as control, m3.id_tingkat as residual
		from risk_risiko rr
		left join risk_sasaran_strategis rss on rss.ID_SASARAN_STRATEGIS = rr.ID_SASARAN_STRATEGIS
		left join risk_scorecard rs on rs.ID_SCORECARD = rr.ID_SCORECARD
		left join mt_risk_kajian_risiko mrkr on mrkr.ID_KAJIAN_RISIKO = rs.ID_KAJIAN_RISIKO
		left join mt_risk_matrix m1
		on rr.inheren_kemungkinan = m1.id_kemungkinan and rr.inheren_dampak = m1.id_dampak
		left join mt_risk_matrix m2
		on rr.control_kemungkinan_penurunan = m2.id_kemungkinan and rr.control_dampak_penurunan = m2.id_dampak
		left join mt_risk_matrix m3
		on rr.residual_target_kemungkinan = m3.id_kemungkinan and rr.residual_target_dampak = m3.id_dampak
		where rs.id_kajian_risiko = ".$this->conn->escape($idKajianRisiko)." and rr.is_lock = '1' and rr.ID_SASARAN_STRATEGIS = ".$this->conn->escape($idSasaranStrategis).$where;


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

		$sql = "select trim(kr.kode) ||' __koderka__'||' '|| trim(st.kode) ||' __strategis__ ' as no_risiko
				from risk_scorecard s
				left join mt_risk_kajian_risiko kr on kr.id_kajian_risiko = s.id_kajian_risiko
				join mt_sdm_jabatan j on trim(s.owner) = trim(j.id_jabatan)
				join mt_sdm_struktur st on j.id_struktur = st.id_struktur
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

		$sql = "select id_scorecard, nama from risk_scorecard where id_parent_scorecard is null and id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko).$filter;

		$rows = $this->conn->GetArray($sql);

		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['id_scorecard']] = $r['nama'];
		}
		return $data;

	}

}
