	
<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nomor',$row['nomor'],'45','45',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nomor"], "nomor", "Nomor");
?>

<?php 
$from = UI::createTextBox('status_bayar',$row['status_bayar'],'45','45',false,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["status_bayar"], "status_bayar", "Status Bayar");
?>

<?php 
$from = UI::createTextBox('tgl_kontrak',$row['tgl_kontrak'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_kontrak"], "tgl_kontrak", "Tgl. Kontrak");
?>

<?php 
$from = UI::createTextBox('tgl_aktual_minta_no_kontrak',$row['tgl_aktual_minta_no_kontrak'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_aktual_minta_no_kontrak"], "tgl_aktual_minta_no_kontrak", "Tgl. Aktual Minta NO Kontrak");
?>

<?php 
$from = UI::createTextBox('tgl_aktual_kontrak_ttd',$row['tgl_aktual_kontrak_ttd'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_aktual_kontrak_ttd"], "tgl_aktual_kontrak_ttd", "Tgl. Aktual Kontrak TTD");
?>

</div>
<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nilai',$row['nilai'],'22','22',$edited,$class='form-control rupiah',"style='text-align:right; width:100%' min='0' max='1.0E+22' step='1'");
echo UI::createFormGroup($from, $rules["nilai"], "nilai", "Nilai");
?>
				
<?php 
$from = UI::createTextBox('levering_tgl_mulai',$row['levering_tgl_mulai'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["levering_tgl_mulai"], "levering_tgl_mulai", "Levering Tgl. Mulai");
?>

<?php 
$from = UI::createTextBox('levering_tgl_terakhir',$row['levering_tgl_terakhir'],'10','10',$edited,$class='form-control datepicker',"style='width:100px; display:inline'").($edited?" *diisi di tanggal akhir atau hari":"");
echo UI::createFormGroup($from, $rules["levering_tgl_terakhir"], "levering_tgl_terakhir", "Levering Tgl. Terakhir");
?>

<?php 
$from = UI::createTextNumber('levering_jumlah_hari',$row['levering_jumlah_hari'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:100px; display:inline' min='0' max='10000000000' step='1'").($edited?" *diisi di tanggal akhir atau hari":"");
echo UI::createFormGroup($from, $rules["levering_jumlah_hari"], "levering_jumlah_hari", "Levering Jumlah Hari");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>

<?php if($row[$pk]){ ?>
<div class="col-sm-12">
	<hr/>
	<div class="col-sm-6">
		<?php 
		$from = '';
		if($files){
			foreach($files as $r){
				$from .= UI::InputFile(
				array(
					"nameid"=>"file",
					"edit"=>$edited,
					"nama_file"=>$r['client_name'],
					"url_preview"=>site_url("panelbackend/scm_po/preview_file/".$r['id_scm_po_files']),
					"url_delete"=>site_url("panelbackend/scm_po/delete_file/".$row[$pk].'/'.$r['id_scm_po_files']),
					)
				);
			}
		}
		if($edited){
			$from .= UI::InputFile(
			array(
				"nameid"=>"file",
				"edit"=>$edited,
				"extarr"=>explode("|",$configfile['allowed_types']),
				"add"=>"onchange='goSubmit(\"save_file\",\"#main_form\")'"
				)
			);
		}
		echo UI::createFormGroup($from, $rules["file"], "file", "File Lampiran");
		?>
	</div>
</div>
<?php }?>

<?php if($edited) { ?>
<script type="text/javascript">
	
	$("#nomor").change(cek_nilai);
	$("#nilai").change(cek_levering);
	$("#levering_jumlah_hari").change(function(){
		var j = $(this).val();
		var start = $("#levering_tgl_mulai").val();
		var end = moment(start, "DD-MM-YYYY").add(j, 'day');
		end = moment(end).format("DD-MM-YYYY");
		$("#levering_tgl_terakhir").val(end);
	});

	$(function(){

		cek_nilai();
		cek_levering();

		$("#tgl_kontrak").datetimepicker({
			format:"DD-MM-YYYY"
		}).on("dp.change", function(){
			var v = $("#tgl_kontrak").val();
			$("#levering_tgl_mulai").val(v);
			cek_nilai();
		});

		$("#levering_tgl_mulai").datetimepicker({
			format:"DD-MM-YYYY"
		}).on("dp.change", _calDay);
		
		$("#levering_tgl_terakhir").datetimepicker({
			format:"DD-MM-YYYY"
		}).on("dp.change", _calDay);
	});

	function _calDay(){
		var start = $("#levering_tgl_mulai").val();
		start = moment(start, "DD-MM-YYYY");
		var end = $("#levering_tgl_terakhir").val();
		end = moment(end, "DD-MM-YYYY");
		var jumlah = CalDay(start, end);
		$("#levering_jumlah_hari").val(jumlah);
	}

	function cek_nilai(){
		var v1 = $("#nomor").val();
		var v2 = $("#tgl_kontrak").val();
		if(v1=='' || v2==''){
			$("#nilai").attr("readonly","readonly");
			$("#nilai").val('');
		}else{
			$("#nilai").removeAttr("readonly");
		}
		cek_levering();
	}

	function cek_levering(){
		var v1 = $("#nomor").val();
		var v2 = $("#tgl_kontrak").val();
		var v3 = $("#nilai").val();
		if(v1=='' || v2=='' || v3==''){
			$("#levering_tgl_mulai").attr("readonly","readonly");
			//$("#levering_tgl_mulai").val('');
			$("#levering_tgl_terakhir").attr("readonly","readonly");
			$("#levering_tgl_terakhir").val('');
			$("#levering_jumlah_hari").attr("readonly","readonly");
			$("#levering_jumlah_hari").val('');
		}else{
			$("#levering_tgl_mulai").removeAttr("readonly");
			$("#levering_tgl_terakhir").removeAttr("readonly");
			$("#levering_jumlah_hari").removeAttr("readonly");
		}
	}
</script>
<?php }?>