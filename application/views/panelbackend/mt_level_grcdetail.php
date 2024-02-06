<div class="col-sm-6">
	<?php 
	$from = UI::createTextBox('kode',$row['kode'],'5','5',$edited,'form-control ',"style='width:50px'");
	echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");

	$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
	echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");

	$from = UI::createTextBox('deskripsi',$row['deskripsi'],'200','100',$edited,'form-control ',"style='width:100%'");
	echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi");

	// $from = UI::createTextBox('folder',$row['folder'],'200','100',$edited,'form-control ',"style='width:100%'");
	// echo UI::createFormGroup($from, $rules["folder"], "folder", "Folder");
	?>
</div>

<div class="col-sm-6">
	<?php 
	$from = UI::showButtonMode("save", null, $edited);
	echo UI::createFormGroup($from, null, null, null, true);
	?>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="area-konten">
			<div class="tab-content">
				<table id="table" class='table table-hover table-bordered'>
					<thead>
						<tr>
							<th>Folder Dokumen GRC</th>
							<th>Used</th>
						</tr>
					</thead>
					<tbody>
						<?
						$maxdata= $row['reqtable'];
						for($i=0; $i<$maxdata; $i++)
						{
							$rowdetilid= $row['reqdatatable'][$i]['rowdetilid'];
							$id_katalogtool= $row['reqdatatable'][$i]['id_katalogtool'];
							$id_lokasiasal= $row['reqdatatable'][$i]['id_lokasiasal'];
							$rowkunci= $id_katalogtool."-".$id_lokasiasal;

							$infodisplay= "";
							$infostatus= $row['reqdatatable'][$i]['status'];
							if($row['reqdatatable'][$i]['status'] == "hapus")
								$infodisplay= "none";
						?>
						<tr id="tr--<?=$i?>" style="display: <?=$infodisplay?>;">
							<?php
							$formdetil= "";

							// $form= UI::createTextBox('reqdatatable['.$i.'][id_fd_dok_pendukung_grc]',$row['reqdatatable'][$i]['id_fd_dok_pendukung_grc'],'','',$edited,$class='form-control id_fd_dok_pendukung_grc'.$i.'',"style='width:100%; display:inline;'", '');

							$form= UI::createSelect('reqdatatable['.$i.'][id_dok_pendukung_grc]',$mtdokgrcarr,$row['reqdatatable'][$i]['id_dok_pendukung_grc'],$edited,$class='form-control id_dok_pendukung_grc'.$i.'',"style='width:100%;'");
							echo "<td>".$form."</td>";

							$infodisplay= "";
							if($infostatusheader != "")
								$infodisplay= "none";

							$formdetil= '<input type="hidden" name="reqdatatable['.$i.'][rowdetilid]" id="rowdetilid'.$i.'" value="'.$rowdetilid.'" />
							<input type="hidden" name="reqdatatable['.$i.'][rowkunci]" id="rowkunci'.$i.'" value="'.$rowkunci.'" />
							';
							if($edited)
							{
								// $formdetil.= ' <span style="cursor:pointer; display: '.$infodisplay.';" id="iconhapus'.$i.'" class="glyphicon glyphicon-remove-circle"></span><input type="hidden" name="reqdatatable['.$i.'][status]" id="status'.$i.'" value="'.$infostatus.'" />';
							}
							
							// $form= UI::createCheckBox('reqdatatable['.$i.'][status_aktif]',1,$row['reqdatatable'][$i]['status_aktif'],null,$edited,$class='form-control sa_check status_aktif'.$i.'',"style='width:80%;'").$formdetil;

							$form= UI::createSelect('reqdatatable['.$i.'][status_aktif]',$status,$row['reqdatatable'][$i]['status_aktif'],$edited,$class='form-control status_aktif'.$i.'',"style='width:80%;'").$formdetil;
							echo "<td>".$form."</td>";
							?>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
				<?php
				if($edited)
				{
				?>
					<!-- <div style="margin-top: 10px">
						<button type="button" class="btn waves-effect btn-sm btn-danger" onclick="addrow()"><span class="glyphicon glyphicon-plus-sign"></span> Add</button>
					</div> -->
				<?
				}
				?>
				<input type="hidden" name="reqtable" id="reqtable" value="<?=$maxdata?>" />

			</div>
		</div>
		
	</div>
</div>

<!-- <script type="text/javascript">
	
	detilajax= "mt_level_grcdetail";

	function addrow(tipe='')
	{
		vurl= "";
		rownum= defnum($("#reqtable").val());

		vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f='+detilajax+'&i='+rownum;

		if(vurl !== "")
		{
			rownum= parseFloat(rownum) + 1;
			$("#reqtable").val(rownum);

			$.ajax({
				'url': vurl
				, beforeSend: function () {
					// $(".preloader-wrapper").show();
				}
				,'success': function(datahtml) {
					// console.log(datahtml);
					$('#table').append(datahtml);
					hapusparam();

					$('[id^="reqdatatable"]').on("change", function (e) {
						// var data= e.params.data;
						infoid= $(this).attr('id');
						// console.log(infoid);return false;
						if (typeof window.setvalidasicheck === 'function')
						{
							setvalidasicheck(infoid);
						}
					});
				}
			});
		}
	}

	$('[id^="iconhapus"]').click(function(e) {
		infoid= $(this).attr('id');
	    infoid= infoid.replace("iconhapus", "");
	    $("#status"+infoid).val("hapus");
	    $("#tr--"+infoid).hide();

	    labeltotalparam();
	});

	$('[id^="reqdatatable"]').on("change", function (e) {
		// var data= e.params.data;
		infoid= $(this).attr('id');
		// console.log(infoid);return false;
		if (typeof window.setvalidasicheck === 'function')
		{
			setvalidasicheck(infoid);
		}
	});

	function hapusparam(vid)
	{
	  	$('[id^="iconhapus"]').click(function(e) {
		    infoid= $(this).attr('id');
		    infoid= infoid.replace("iconhapus", "");
		    $("#status"+infoid).val("hapus");
		    $("#tr--"+infoid).hide();
		    // console.log(infoid);

		    labeltotalparam();
	  	});

	  	// $(".datepicker").datetimepicker({format: "DD-MM-YYYY",useCurrent:false});
	  	// $(".datetimepicker").datetimepicker({format: "DD-MM-YYYY HH:mm:ss",useCurrent:false});

	  	// $(".select2, select.form-control").select2({
	    // 	placeholder: '-pilih-',
	    // 	allowClear: true
	  	// });

	  	// // tambahan format rupiah
	  	// $(".rupiah").autoNumeric('init', {aSep: '.', aDec: ','});

	}

	function labeltotalparam()
	{
		labeltotal= 0;
		$('[id^="tr--"]').each(function(index, value) {
			if ( $(this).css('display') == 'none'){}
			else
			{
				labeltotal= parseInt(labeltotal) + 1;
				$(".nourut--"+index).val(labeltotal);
			}
			// console.log( index + ": " + value +";"+this.id);
		});
		$("#labeltotal").text(labeltotal);
	}

	function setvalidasicheck(infoid)
	{
		arrinfo= getidinfo(infoid, "reqdatatable");
		console.log(arrinfo);
		indexid= arrinfo.indexid;
		indexname= arrinfo.indexname;

		
		// if (indexname=='status_aktif') 
		// {
		// 	$('.sa_check').attr('checked', false); // Unchecks it

		// 	$('.'+indexname+indexid).attr('checked', true); // Checks it
		// }
	}

	function getidinfo(infoid, infoparams)
	{
		infoid= infoid.replace(infoparams, "");
		infoid= String(infoid).split("]");
		indexid= infoid[0].replace("[", "");
		indexname= infoid[1].replace("[", "");

		var infodetil= {};
		infodetil.indexid= indexid;
		infodetil.indexname= indexname;
		return infodetil;
	}

	function defnum(vnum)
	{
		if(typeof vnum == "undefined" || vnum == "")
		{
			vnum= "0";
		}
		return vnum;
	}
</script> -->