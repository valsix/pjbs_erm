<?php class Risk_taskModel extends _Model{
	public $table = "risk_task";
	public $pk = "id_task";
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


		$ci = $this->ci;
		$sql = $ci->auth->SqlTask();

    	$status_arr = $ci->data['mtstatusarr'];

		$sql_content = "select 
		t.id_task,
		t.status,
		r.nama as nama_risiko, 
		t.id_status_pengajuan, 
		t.url, 
		t.deskripsi, 
		u.name as nama_user, 
		g.name as nama_group, 
		to_char(t.created_date,'YYYY-MM-DD HH:MI:SS') as created_date, to_char(sysdate,'YYYY-MM-DD HH:MI:SS') as n ".$sql." order by id_task desc";

		$rows = $this->conn->PageArray($sql_content,
			$arr_params['limit'],$arr_params['page']
		);


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
			if(!$r['status'])
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

		$sql_count = "select count(1) ".$sql;

		$arr_return['rows'] = $content;
		$arr_return['total'] = static::GetOne($sql_count);

		return $arr_return;
	}
}