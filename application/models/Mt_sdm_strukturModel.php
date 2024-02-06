<?php class Mt_sdm_strukturModel extends _Model{
	public $table = "mt_sdm_struktur";
	public $pk = "id_struktur";
	function __construct(){
		parent::__construct();
	}

	public function SelectGrid($arr_param=array(), $str_field="*")
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

		$str_condition = "";

		if(!empty($arr_params['filter']))
		{
			$str_condition = "where ".$arr_params['filter'];
		}

		$str_order = "";

		if(!empty($arr_params['order']))
		{
			$str_order = "order by ".$arr_params['order'];
		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}

		$rows = $this->conn->GetArray("
			select a.tgl_akhir_efektif, id_struktur, id_struktur as id, id_struktur_parent as id_parent, '['||trim(kode)||'] '||nama as nama, urutan
			from mt_sdm_struktur a
			{$str_condition}
			order by urutan");

		$arr_return['rows'] = array();

		$this->GenerateSort($rows, "id_parent", "id", "nama", $arr_return['rows'], $id_parent);

		return $arr_return;
	}

	public function sort($id){
		$row = $this->GetByPk($id);

		$tgl_efektif = $row['tgl_mulai_efektif'];
		$urutan = $row['urutan'];

		$sql = "select * from mt_sdm_struktur 
		where '$tgl_efektif' 
		between nvl(tgl_mulai_efektif,'$tgl_efektif')
		and nvl(tgl_akhir_efektif,'$tgl_efektif')
		and (urutan >= $urutan or urutan is null)
		and id_struktur <> ".$this->conn->escape($id).
		" order by urutan";
		$rows = $this->conn->GetArray($sql);

		foreach ($rows as $r) {
			$urutan++;
			$id_struktur = $r['id_struktur'];
			$sql = "update mt_sdm_struktur set urutan = $urutan where id_struktur = $id_struktur";
			$this->conn->Execute($sql);
		}

	}

	public function GetCombo($key=null, $q=null, $tgl_efektif=null){

		$tgl_efektif = date('d-m-Y');
		
		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$id_parent = null;

		$sql = "select * from mt_sdm_struktur 
		where '$tgl_efektif' 
		between nvl(tgl_mulai_efektif,'$tgl_efektif')
		and nvl(tgl_akhir_efektif,'$tgl_efektif')";

		if($q)
			$sql .= " and  lower(nama) like '%$q%'";

		if($key)
			$sql .= " and id_struktur = ".$this->conn->escape($key);

		$sql .= " order by urutan";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$this->GenerateTree($rows, "id_struktur_parent", "id_struktur", "nama", $ret, $id_parent);

		$return = array(''=>'-pilih-');
		foreach ($ret as $r) {
			$return[$r['id_struktur']] = str_replace(" &amp; ", " ", $r['nama']);
		}

		return $return;		
	}

	## get combo to make filter based on parent id
	public function GetComboFilter($parent_key=null, $tgl_efektif=null){

		$tgl_efektif = date('d-m-Y');
		
		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$id_parent = null;

		$sql = "select * from mt_sdm_struktur 
		where '$tgl_efektif' 
		between nvl(tgl_mulai_efektif,'$tgl_efektif')
		and nvl(tgl_akhir_efektif,'$tgl_efektif')";

		if($parent_key) {			
			$sql .= " and id_struktur_parent = ".$this->conn->escape($parent_key);
			$sql .= " or id_struktur = ".$this->conn->escape($parent_key);
		}

		$sql .= " order by urutan";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$this->GenerateTree($rows, "id_struktur_parent", "id_struktur", "nama", $ret, $id_parent);

		$return = array(''=>'-pilih-');
		foreach ($ret as $r) {
			$return[$r['id_struktur']] = str_replace(" &amp; ", " ", $r['nama']);
		}

		return $return;		
	}

	function sync(){
		$cek1 = $this->conn->GetOne("select count(1) from mt_sdm_struktur where tgl_akhir_efektif is null");
		$cek2 = $this->conn->GetOne("select count(1) from (select b.urutan, b.id_struktur, b.kode, b.nama from mt_sdm_struktur b
			join hirarki_jabatan@ellipse a on (case when subdit_ket is null then kd_direktorat else kd_subdit end)=b.kode
			where b.tgl_akhir_efektif is null and a.kd_staff<='07'
			group by b.urutan, b.id_struktur, b.kode, b.nama) a");

		if($cek2==$cek1)
			return false;

		return $this->conn->Execute("update mt_sdm_struktur set tgl_akhir_efektif = sysdate where tgl_akhir_efektif is null;

			insert into mt_sdm_struktur (kode,nama, tgl_mulai_efektif, urutan)
			select c.*, sysdate, rownum from (select kode, nama from(
			select distinct kode, nama, kd_direktorat, kd_subdit from (select 
			kd_direktorat, kd_subdit, case when subdit_ket is null then kd_direktorat else kd_subdit end as kode, nvl(subdit_ket, direktorat_ket) as nama
			from hirarki_jabatan@ellipse
			where kd_staff <= '07') a) b
			order by kd_direktorat, kd_subdit) c;

			MERGE INTO mt_sdm_struktur D
			   USING (select b.id_struktur id_parent, a.id_struktur from(
			select b.urutan, b.id_struktur, b.kode, b.nama, max(position_id) position_id, min(superior_id) superior_id
			from mt_sdm_struktur b
			join hirarki_jabatan@ellipse a on (case when subdit_ket is null then kd_direktorat else kd_subdit end)=b.kode
			where b.tgl_akhir_efektif is null and a.kd_staff<='07'
			group by b.urutan, b.id_struktur, b.kode, b.nama) a
			join hirarki_jabatan@ellipse c on a.superior_id = c.position_id
			join mt_sdm_struktur b on (case when subdit_ket is null then kd_direktorat else kd_subdit end)=b.kode
			where b.tgl_akhir_efektif is null and c.kd_staff<='07'
			group by a.urutan, b.id_struktur, b.kode, b.nama, a.id_struktur, a.kode, a.nama) S
			   ON (D.id_struktur = S.id_struktur and s.id_parent <> d.id_struktur)
			   WHEN MATCHED THEN UPDATE SET D.id_struktur_parent = s.id_parent where d.tgl_akhir_efektif is null;

			MERGE INTO mt_sdm_unit D
			   USING master_unit@ellipse S
			   ON (D.table_code = S.table_code)
			   WHEN NOT MATCHED THEN INSERT (D.table_code, D.table_desc)
			     VALUES (S.table_code, S.table_desc);

			update mt_sdm_jabatan set tgl_mulai_efektif = sysdate-5 where tgl_akhir_efektif is null;

			insert into mt_sdm_jabatan (nama, id_unit, id_struktur, is_pimpinan, position_id, tgl_mulai_efektif)
			select a.pos_title, 
			a.kd_unit, 
			b.id_struktur, 
			case 
			when lower(a.pos_title) like '%manajer%' 
			or lower(a.pos_title) like '%ketua%' 
			or lower(a.pos_title) like '%kepala%' 
			or lower(a.pos_title) like '%direktur%' 
			then 1 else 0 end as is_pimpinan,
			a.position_id, 
			sysdate
			from mt_sdm_struktur b
			join hirarki_jabatan@ellipse a on (case when subdit_ket is null then kd_direktorat else kd_subdit end)=b.kode
			where b.tgl_akhir_efektif is null and a.kd_staff<='07' and a.kd_unit in (select table_code from mt_sdm_unit);");
	}
}