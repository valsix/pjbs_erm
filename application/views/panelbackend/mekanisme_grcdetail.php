<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
    				<div class="pull-left">
                    <h2>
                    <?=$page_title?>
                    </h2>
                    </div>
                    <div class="pull-right">
                            <?php echo UI::showButtonMode($mode, $rowheader['id_scorecard'])?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="body">
                    <?=FlashMsg()?>

					<div class="col-sm-6">

						<?php 
						$from = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$rowheader['id_kajian_risiko'],false,'form-control ',"style='width:auto; max-width:100%;'");
						echo UI::createFormGroup($from, $rules["id_kajian_risiko"], "id_kajian_risiko", "Lingkup Kajian Risiko", false, 4, $editedheader);

						if ($rowheader['navigasi']!='1') 
						{
							$from = UI::createTextBox('nomor_ba_grc_inp',$row['nomor_ba_grc_inp'],'6','10',$edited,'form-control ',"style='width:30%'");
							$from.= UI::createTextBox('nomor_ba_grc_din',$no_dinamis,'100','100',false,'form-control ',"style='width:70%'");
							$formdetil= '<input type="hidden" name="nomor_ba_grc" value="'.$row['nomor_ba_grc'].'" /> <input type="hidden" name="nomor_ba_grc_din" value="'.$no_dinamis.'" />';
							echo UI::createFormGroup($from.$formdetil, $rules["nomor_ba_grc_inp"], "nomor_ba_grc_inp", "Nomor Berita Acara GRC");

							if($this->access_role['view_all_direktorat'] && $editedheader)
							{
								if($rowheader['navigasi']===null)
								    $rowheader['navigasi'] = 0;

								$from = UI::createSelect('navigasi',$tipescorecard, $rowheader['navigasi'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control ',"style='width:auto; max-width:100%;'' onchange='goSubmit()'");
								echo UI::createFormGroup($from, $rules["navigasi"], "navigasi", "Tipe Scorecard", false, 4, ($this->access_role['view_all_direktorat'] && $editedheader));

								if(!$rowheader['navigasi'])
								{
									$from = UI::createSelect('id_unit',$unitarr, $rowheader['id_unit'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
									echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit", false, 4, ($this->access_role['view_all_direktorat'] && $editedheader));
								}
							}

							if(!$rowheader['navigasi']){
								$from = UI::createSelect('owner',$ownerarr,$rowheader['owner'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control select2'," onchange='goSubmit(\"set_value\")'");
								echo UI::createFormGroup($from, $rules["owner"], "owner", "Divisi Pemrakarsa", false, 4, ($this->access_role['view_all_direktorat'] && $editedheader));

								// if($rowheader['id_kajian_risiko']=='4'){
								// $from = UI::createSelect('id_nama_proses',$mtpbnamaprosesarr, $rowheader['id_nama_proses'],$editedheader,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
								// echo UI::createFormGroup($from, $rules["id_nama_proses"], "id_nama_proses", "Nama Proses", false, 2, $editedheader);
								// }

								// if($rowheader['id_kajian_risiko']=='5'){
								// $from = UI::createSelect('id_status_proyek',$mtstatusproyekarr, $rowheader['id_status_proyek'],$editedheader,'form-control ',"style='width:auto; max-width:100%;'");
								// echo UI::createFormGroup($from, $rules["id_status_proyek"], "id_status_proyek", "Status Proyek", false, 2, $editedheader);
								// }
							}

							$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
							echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Program");

							$from = UI::createTextBox('tanggal_dok_ba',($row['tanggal_dok_ba']?$row['tanggal_dok_ba']:date('d-m-Y')),'10','10',$edited,'form-control datepicker',"style='width:100px'");
							echo UI::createFormGroup($from, $rules["tanggal_dok_ba"], "tanggal_dok_ba", "Tanggal (Dok BA)");

							$from = UI::createTextBox('sasaran_kpi',$row['sasaran_kpi'],'200','100',$edited,'form-control ',"style='width:100%'");
							echo UI::createFormGroup($from, $rules["sasaran_kpi"], "sasaran_kpi", "Sasaran KPI");

							$from = UI::createTextBox('klasifikasi_program',$row['klasifikasi_program'],'200','100',$edited,'form-control ',"style='width:100%'");
							echo UI::createFormGroup($from, $rules["klasifikasi_program"], "klasifikasi_program", "Klasifikasi Program");

							$from = UI::createTextNumber('estimasi_biaya',$row['estimasi_biaya'],'200','100',$edited,'form-control ',"style='width:100%'");
							echo UI::createFormGroup($from, $rules["estimasi_biaya"], "estimasi_biaya", "Estimasi Biaya");

							$from = UI::createTextBox('sumber_dana',$row['sumber_dana'],'200','100',$edited,'form-control ',"style='width:100%'");
							echo UI::createFormGroup($from, $rules["sumber_dana"], "sumber_dana", "Sumber Dana");
						}
						else
						{
							if($this->access_role['view_all_direktorat'] && $editedheader)
							{
								if($rowheader['navigasi']===null)
								    $rowheader['navigasi'] = 0;

								$from = UI::createSelect('navigasi',$tipescorecard, $rowheader['navigasi'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control ',"style='width:auto; max-width:100%;'' onchange='goSubmit()'");
								echo UI::createFormGroup($from, $rules["navigasi"], "navigasi", "Tipe Scorecard", false, 4, ($this->access_role['view_all_direktorat'] && $editedheader));
							}

							$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
							echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Program");

							if($this->access_role['view_all_direktorat'] && $editedheader)
							{
								$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',($this->access_role['view_all_direktorat'] && $editedheader),'form-control datepicker',"style='width:100px'");
								echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 4, ($this->access_role['view_all_direktorat'] && $editedheader));

								$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',($this->access_role['view_all_direktorat'] && $editedheader),'form-control datepicker',"style='width:100px'");
								echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 4, ($this->access_role['view_all_direktorat'] && $editedheader));
							}
						}
						?>

					</div>
					<div class="col-sm-6">

						<?php 
						if ($rowheader['navigasi']!='1') 
						{
							if($this->access_role['view_all_direktorat'] && $editedheader)
							{
								$from = UI::createSelect('id_parent_scorecard',$scorecardarr,$rowheader['id_parent_scorecard'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control ',"style='width:auto; max-width:100%;'");
								echo UI::createFormGroup($from, $rules["id_parent_scorecard"], "id_parent_scorecard", "Induk Scorecard", false, 4, $editedheader);

								$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',($this->access_role['view_all_direktorat'] && $editedheader),'form-control datepicker',"style='width:100px'");
								echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 4, ($this->access_role['view_all_direktorat'] && $editedheader));

								$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',($this->access_role['view_all_direktorat'] && $editedheader),'form-control datepicker',"style='width:100px'");
								echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 4, ($this->access_role['view_all_direktorat'] && $editedheader));
							}

							$from = UI::createTextBox('hasil_keputusan_radir',$row['hasil_keputusan_radir'],'200','100',$edited,'form-control ',"style='width:100%'");
							echo UI::createFormGroup($from, $rules["hasil_keputusan_radir"], "hasil_keputusan_radir", "Hasil Keputusan Radir");

							$from = UI::createSelect('id_level_grc',$mtlevelgrcarr, $rowheader['id_level_grc'],$edited,'form-control ',"style='width:auto; max-width:50px;' onchange='goSubmit()'");
							echo UI::createFormGroup($from, $rules["id_level_grc"], "id_level_grc", "Level GRC");

							// $from = UI::createTextBox('divisi_pemrakarsa_id',$row['divisi_pemrakarsa_id'],'200','100',$edited,'form-control ',"style='width:100%'");
							// echo UI::createFormGroup($from, $rules["divisi_pemrakarsa_id"], "divisi_pemrakarsa_id", "Divisi Pemrakarsa");

							// $from = UI::createTextBox('pic_rth_id',$row['pic_rth_id'],'200','100',$edited,'form-control ',"style='width:100%'");
							$from = UI::createSelect('id_pic_rth',$mtpicrtharr, $row['id_pic_rth'],$edited,'form-control ',"style='width:auto; max-width:50px;' ");
							echo UI::createFormGroup($from, $rules["id_pic_rth"], "id_pic_rth", "PIC RTH");

							$from = UI::createSelect('id_status_progress_grc',$pregressarr, $row['id_status_progress_grc'],$edited,'form-control ',"style='width:auto; max-width:50px;' ");
							echo UI::createFormGroup($from, $rules["id_status_progress_grc"], "id_status_progress_grc", "Progress/Status");

							$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,'form-control',"");
							echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");

							$from = UI::createTextBox('progress_mitigasi',$row['progress_mitigasi'],'200','100',false,'form-control ',"style='width:100%'");
							echo UI::createFormGroup($from, $rules["progress_mitigasi"], "progress_mitigasi", "Progress Mitigasi");
						}

						// $from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',$edited,'form-control datepicker',"style='width:100px'");
						// echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif");
						?>
									

						<?php 
						// $from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',$edited,'form-control datepicker',"style='width:100px'");
						// echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif");
						?>
										

						
					</div>

					<hr/>
					<div class="row">
						<div class="col-md-12">
							<?php
							if (!$rowheader['navigasi']) 
							{
								?>
								<div class="area-konten">
									<div class="tab-content">
										<table id="table" class='table table-hover table-bordered'>
											<thead>
												<tr>
													<th>Used</th>
													<th>Folder Dokumen GRC</th>
												</tr>
											</thead>
											<tbody>
												<?
												$maxdata= $row['reqtable'];
												for($i=0; $i<$maxdata; $i++)
												{
													$infostatuschecked= $row['reqdatatable'][$i]['statuschecked'];
													$statuschecked= ""; $statusreadonly= "readonly";
													if($infostatuschecked == "1")
													{
														$statuschecked= "checked"; $statusreadonly= "";
													}

													// $rowdetilid= $row['reqdatatable'][$i]['rowdetilid'];
													// $id_katalogtool= $row['reqdatatable'][$i]['id_katalogtool'];
													// $id_lokasiasal= $row['reqdatatable'][$i]['id_lokasiasal'];
													// $rowkunci= $id_katalogtool."-".$id_lokasiasal;

													// $infodisplay= "";
													// $infostatus= $row['reqdatatable'][$i]['status'];
													// if($row['reqdatatable'][$i]['status'] == "hapus")
													// 	$infodisplay= "none";
												?>
												<tr id="tr--<?=$i?>" style="display: <?=$infodisplay?>;">
													<?php
													$formdetil= "";

													$form= UI::createCheckBox('reqdatatable['.$i.'][statuschecked]',1,$infostatuschecked,null,$edited,$class='form-control sa_check statuschecked'.$i.'',"style='width:80%;'").$formdetil;

													// $form= '<input style="display:'.$display.'" type="checkbox" '.$statuschecked.' name="reqdatatable['.$i.'][statuschecked]" id="statuscheckedgeneral'.$i.'" value="'.$infostatuschecked.'" '.$disabled.' />';
													echo "<td>".$form."</td>";

													$infodisplay= "";
													if($infostatusheader != "")
														$infodisplay= "none";

													$formdetil= '
													<input type="hidden" name="reqdatatable['.$i.'][id_dok_pendukung_grc]" id="id_dok_pendukung_grc'.$i.'" value="'.$row['reqdatatable'][$i]['id_dok_pendukung_grc'].'" />
													';
													if($edited)
													{
														// $formdetil.= ' <span style="cursor:pointer; display: '.$infodisplay.';" id="iconhapus'.$i.'" class="glyphicon glyphicon-remove-circle"></span><input type="hidden" name="reqdatatable['.$i.'][status]" id="status'.$i.'" value="'.$infostatus.'" />';
													}

													$form= UI::createSelect('reqdatatable['.$i.'][id_dok_pendukung_grc]',$mtdokgrcarr,$row['reqdatatable'][$i]['id_dok_pendukung_grc'],false,$class='form-control id_dok_pendukung_grc'.$i.'',"style='width:100%;'").$formdetil;
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
								<?
							}
							?>
							
							
						</div>
					</div>

					<div class="row"> 
						<!-- <div class="col-sm-12"> -->
							<div class="pull-right col-md-12">
								<?php 
								$from = UI::showButtonMode("save", null, $edited);
								echo UI::createFormGroup($from, null, null, null, true);
								?>
							</div>
						<!-- </div> -->
					</div>
					
				</div>
            </div>
        </div>
    </div>
</div>

<!-- <input type="hidden" name="vstatuspaket" id="vstatuspaket" value="<?=$row['vstatuspaket']?>" />
<input type="hidden" name="vstatuspekerjaan" id="vstatuspekerjaan" value="<?=$row['vstatuspekerjaan']?>" />
<input type="hidden" name="vstatuslokasiasalparent" id="vstatuslokasiasalparent" value="<?=$row['vstatuslokasiasalparent']?>" />
<input type="hidden" name="vstatuslokasitujuanparent" id="vstatuslokasitujuanparent" value="<?=$row['vstatuslokasitujuanparent']?>" /> -->

<!-- <script type="text/javascript">
	detilajax= "mekanismegrcdetail";

	$('#id_lokasiasal').change(function(e) {
		vstatuspaket= $("#vstatuspaket").val();
		vstatuspekerjaan= $("#vstatuspekerjaan").val();
		idjenisreservasi= $("#id_jenisreservasi").val();
		valinfoid= $(this).val();

		if(vstatuspekerjaan == "1")
		{
			// valinfoid= $(this).val();
			// console.log(valinfoid);

			vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f=datalokasiparent&v='+valinfoid;
			// console.log(vurl);
	    	$.ajax({
				'url': vurl
				, beforeSend: function () {
					// $(".preloader-wrapper").show();
				}
				,'success': function(datahtml) {
					datahtml= JSON.parse(datahtml);
					// console.log(datahtml);

					var arrmtlokasidetil= [];
					$.each(datahtml, function( index, value ) {
						// console.log(index);
						// console.log(value);
						var infodetil= {};
						infodetil.id= index;
						infodetil.text= value;
						arrmtlokasidetil.push(infodetil);
					});
					// console.log(arrmtlokasidetil);

					$('#id_lokasitujuan').empty();
					$('#id_lokasitujuan').select2({
						data: arrmtlokasidetil,
						placeholder: '-pilih-',
						allowClear: true
					});
					$("#id_lokasitujuan").val("");
					$("#id_lokasitujuan").trigger('change');
				}
			});
			/*
			$("#id_lokasitujuan").select2({
				ajax: {
					url: "<?=site_url('panelbackend/ajax/lokasiparent')?>?k="+valinfoid,
					dataType: 'json'
				}
			});*/
		}
		else
		{
			/*$('#id_lokasitujuan').empty();
			$('#id_lokasitujuan').select2({
				data: arrmtlokasi,
				placeholder: '-pilih-',
				allowClear: true
			});
			$("#id_lokasitujuan").val("");
			$("#id_lokasitujuan").trigger('change');*/
		}
	});


	$('#id_lokasitujuan').change(function(e) {
		idjenisreservasi= $("#id_jenisreservasi").val();
		valinfoid=$("#id_lokasiasal").val();

		//untuk tf ke lokasi gd utama
		if (idjenisreservasi=='21' && valinfoid) 
		{
			// console.log(valinfoid);

	    	Swal.fire({
				title: 'Apakah anda yakin ?',
				text: "Data detail Reservasi akan diperbarui sesuai dengan Lokasi Asal yang dipilih !",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yakin',
				cancelButtonText: 'Tidak'
			}).then((result) => {
				if (result.isConfirmed) {
					// valinfo= idjenisreservasi;
					// console.log(valinfo);
			    	// valinfoid= valinfo[0].id;
			    	// valinfotext= valinfo[0].text;

			    	tanggalkebutuhan= $("#tanggal_kebutuhan").val();
					lamakebutuhan= $("#lama_kebutuhan").val();

					idlokasiasal= textlokasiasal= idlokasitujuan= textlokasitujuan= "";
					if(typeof $('#id_lokasiasal').select2('data')[0] == "undefined"){}
					else
					{
						idlokasiasal= $('#id_lokasiasal').select2('data')[0].id;
						textlokasiasal= $('#id_lokasiasal').select2('data')[0].text;
					}
					if(typeof $('#id_lokasitujuan').select2('data')[0] == "undefined"){}
					else
					{
						idlokasitujuan= $('#id_lokasitujuan').select2('data')[0].id;
						textlokasitujuan= $('#id_lokasitujuan').select2('data')[0].text;
					}

			    	vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f='+detilajax+'&idjenisreservasi='+idjenisreservasi+'&tanggalkebutuhan='+tanggalkebutuhan+'&lamakebutuhan='+lamakebutuhan+'&idlokasiasal='+idlokasiasal+'&textlokasiasal='+textlokasiasal+'&idlokasitujuan='+idlokasitujuan+'&textlokasitujuan='+textlokasitujuan;
			    	$.ajax({
						'url': vurl
						, beforeSend: function () {
							// $(".preloader-wrapper").show();
						}
						,'success': function(datahtml) {
							datahtml= JSON.parse(datahtml);
							// console.log(datahtml);

							$('#reqtablegeneral').val(datahtml["generaljumlah"]);
							$('#labeltotalgeneral').text(datahtml["generaljumlah"]);
							$('#tablegeneral tbody').html(datahtml["general"]);
							$('#reqtablespesifik').val(datahtml["spesifikjumlah"]);
							$('#labeltotalspesifik').text(datahtml["spesifikjumlah"]);
							$('#tablespesifik tbody').html(datahtml["spesifik"]);
							$('#reqtablespesial').val(datahtml["spesialjumlah"]);
							$('#labeltotalspesial').text(datahtml["spesialjumlah"]);
							$('#tablespesial tbody').html(datahtml["spesial"]);

							$(".datepicker").datetimepicker({format: "DD-MM-YYYY",useCurrent:false});
							$(".datetimepicker").datetimepicker({format: "DD-MM-YYYY HH:mm:ss",useCurrent:false});

							$(".select2, select.form-control").select2({
								placeholder: '-pilih-',
								allowClear: true
							});

							hapusparam("general");
							hapusparam("spesifik");
							hapusparam("spesial");

							$('[id^="reqdatatable"]').on("select2:select", function (e) {
								var data= e.params.data;
								infoid= $(this).attr('id');
								if (typeof window.setvalidasicheck === 'function')
								{
									setvalidasicheck(infoid, data);
								}
							});
							
						}
					});
				}
			})
		
		}
	});

	$('#id_jenisreservasi').change(function(e) {
		$("#vstatuspaket, #vstatuspekerjaan, #vstatuslokasiasalparent, #vstatuslokasitujuanparent").val("");

		if($(this).val() == "")
		{
			showhidejenisreservasi("");
		}
		else
		{
			valinfo= $("#id_jenisreservasi").select2('data');
			valinfoid= valinfo[0].id;

			showhidejenisreservasidata(jenisreservasiarr, valinfoid);

			/*infocari= valinfoid;
			vjenisreservasiarr= jenisreservasiarr.filter(item => item.id_jenisreservasi === infocari);
			// console.log(vjenisreservasiarr);
			vstatuspaket= vjenisreservasiarr[0].status_paket;
			vstatuspekerjaan= vjenisreservasiarr[0].status_pekerjaan;
			vstatuslokasiasalparent= vjenisreservasiarr[0].status_lokasi_asal_parent;
			vstatuslokasitujuanparent= vjenisreservasiarr[0].status_lokasi_tujuan_parent;
			
			$("#vstatuspaket").val(vstatuspaket);
			$("#vstatuspekerjaan").val(vstatuspekerjaan);
			$("#vstatuslokasiasalparent").val(vstatuslokasiasalparent);
			$("#vstatuslokasitujuanparent").val(vstatuslokasitujuanparent);
			// console.log(vstatuspaket);
			// console.log(vstatuspekerjaan);

			if(vstatuspaket == "" && vstatuspekerjaan == "")
				showhidejenisreservasi("");
			else if(vstatuspaket == "1" && vstatuspekerjaan == "")
				showhidejenisreservasi("paket");
			else if(vstatuspaket == "" && vstatuspekerjaan == "1")
				showhidejenisreservasi("pekerjaan");
			else
				showhidejenisreservasi("all");*/
		}
	});

	$('#id_pakettool').change(function(e) {
		// console.log($(this).val());
		if($(this).val() == ""){}
		else
		{
	    	Swal.fire({
				title: 'Apakah anda yakin ?',
				text: "Data detail Reservasi akan diperbarui sesuai dengan paket tool yang dipilih !",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yakin',
				cancelButtonText: 'Tidak'
			}).then((result) => {
				if (result.isConfirmed) {
					valinfo= $("#id_pakettool").select2('data');
					// console.log(valinfo);
			    	valinfoid= valinfo[0].id;
			    	valinfotext= valinfo[0].text;

			    	tanggalkebutuhan= $("#tanggal_kebutuhan").val();
					lamakebutuhan= $("#lama_kebutuhan").val();

					idlokasiasal= textlokasiasal= idlokasitujuan= textlokasitujuan= "";
					if(typeof $('#id_lokasiasal').select2('data')[0] == "undefined"){}
					else
					{
						idlokasiasal= $('#id_lokasiasal').select2('data')[0].id;
						textlokasiasal= $('#id_lokasiasal').select2('data')[0].text;
					}
					if(typeof $('#id_lokasitujuan').select2('data')[0] == "undefined"){}
					else
					{
						idlokasitujuan= $('#id_lokasitujuan').select2('data')[0].id;
						textlokasitujuan= $('#id_lokasitujuan').select2('data')[0].text;
					}

			    	vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f='+detilajax+'&idpakettool='+valinfoid+'&tanggalkebutuhan='+tanggalkebutuhan+'&lamakebutuhan='+lamakebutuhan+'&idlokasiasal='+idlokasiasal+'&textlokasiasal='+textlokasiasal+'&idlokasitujuan='+idlokasitujuan+'&textlokasitujuan='+textlokasitujuan;
			    	$.ajax({
						'url': vurl
						, beforeSend: function () {
							// $(".preloader-wrapper").show();
						}
						,'success': function(datahtml) {
							datahtml= JSON.parse(datahtml);
							// console.log(datahtml);

							$('#reqtablegeneral').val(datahtml["generaljumlah"]);
							$('#labeltotalgeneral').text(datahtml["generaljumlah"]);
							$('#tablegeneral tbody').html(datahtml["general"]);
							$('#reqtablespesifik').val(datahtml["spesifikjumlah"]);
							$('#labeltotalspesifik').text(datahtml["spesifikjumlah"]);
							$('#tablespesifik tbody').html(datahtml["spesifik"]);
							$('#reqtablespesial').val(datahtml["spesialjumlah"]);
							$('#labeltotalspesial').text(datahtml["spesialjumlah"]);
							$('#tablespesial tbody').html(datahtml["spesial"]);

							$(".datepicker").datetimepicker({format: "DD-MM-YYYY",useCurrent:false});
							$(".datetimepicker").datetimepicker({format: "DD-MM-YYYY HH:mm:ss",useCurrent:false});

							$(".select2, select.form-control").select2({
								placeholder: '-pilih-',
								allowClear: true
							});

							hapusparam("general");
							hapusparam("spesifik");
							hapusparam("spesial");

							$('[id^="reqdatatable"]').on("select2:select", function (e) {
								var data= e.params.data;
								infoid= $(this).attr('id');
								if (typeof window.setvalidasicheck === 'function')
								{
									setvalidasicheck(infoid, data);
								}
							});
							
						}
					});
				}
			})
		}

    });

	$('[id^="iconhapusgeneral"]').click(function(e) {
		hapusparam("general");
	});

	$('[id^="iconhapusspesifik"]').click(function(e) {
		hapusparam("spesifik");
	});

	$('[id^="iconhapusspesial"]').click(function(e) {
		hapusparam("spesial");
	});

	function addrow(tipe='')
	{
		vurl= "";
		rownum= defnum($("#reqtable"+tipe).val());
		tanggalkebutuhan= $("#tanggal_kebutuhan").val();
		lamakebutuhan= $("#lama_kebutuhan").val();
		// idjenisreservasi= $("#id_jenisreservasi").val();

		idlokasiasal= textlokasiasal= "";
		if(typeof $('#id_lokasiasal').select2('data')[0] == "undefined"){}
		else
		{
			idlokasiasal= $('#id_lokasiasal').select2('data')[0].id;
			textlokasiasal= $('#id_lokasiasal').select2('data')[0].text;
		}

		idlokasitujuan= textlokasitujuan= "";
		if(typeof $('#id_lokasitujuan').select2('data')[0] == "undefined"){}
		else
		{
			idlokasitujuan= $('#id_lokasitujuan').select2('data')[0].id;
			textlokasitujuan= $('#id_lokasitujuan').select2('data')[0].text;
		}

		if (tipe=='general') 
		{
			mtkatalogtoolarr= '<?=JSON_encode($mtkatalogtoolgeneralarr)?>';
			mtlokasiarr= '<?=JSON_encode($mtlokasiarr)?>';
			vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f='+detilajax+'&m='+tipe+'&i='+rownum+'&idlokasiasal='+idlokasiasal+'&idlokasitujuan='+idlokasitujuan+'&tanggalkebutuhan='+tanggalkebutuhan+'&lamakebutuhan='+lamakebutuhan;
		}
		else if (tipe=='spesifik') 
		{
			mtkatalogtoolarr= '<?=JSON_encode($mtkatalogtoolspesifikarr)?>';
			mtlokasiarr= '<?=JSON_encode($mtlokasiarr)?>';
			vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f='+detilajax+'&m='+tipe+'&i='+rownum+'&idlokasiasal='+idlokasiasal+'&idlokasitujuan='+idlokasitujuan+'&tanggalkebutuhan='+tanggalkebutuhan+'&lamakebutuhan='+lamakebutuhan;
		}
		else if (tipe=='spesial') 
		{
			mtkatalogtoolarr= '<?=JSON_encode($mtkatalogtoolspesialarr)?>';
			mtlokasiarr= '<?=JSON_encode($mtlokasiarr)?>';
			vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f='+detilajax+'&m='+tipe+'&i='+rownum+'&idlokasiasal='+idlokasiasal+'&idlokasitujuan='+idlokasitujuan+'&tanggalkebutuhan='+tanggalkebutuhan+'&lamakebutuhan='+lamakebutuhan;
		}

		if(vurl !== "")
		{
			rownum= parseFloat(rownum) + 1;
			$("#reqtable"+tipe).val(rownum);

			$.ajax({
				'url': vurl
				, beforeSend: function () {
					// $(".preloader-wrapper").show();
				}
				,'success': function(datahtml) {
					// console.log(datahtml);
					$('#table'+tipe).append(datahtml);
					hapusparam(tipe);
					labeltotalparam(tipe);

					$('[id^="reqdatatable"]').on("select2:select", function (e) {
						var data= e.params.data;
						infoid= $(this).attr('id');
						if (typeof window.setvalidasicheck === 'function')
						{
							setvalidasicheck(infoid, data);
						}
					});
				}
			});
		}
	}

	function setvalidasicheck(infoid, data)
	{
		arrinfo= getidinfo(infoid, "reqdatatable");
		console.log(arrinfo);
		indexid= arrinfo.indexid;
		indexname= arrinfo.indexname;

		m= "";
		if(indexid.indexOf('spesifik') >= 0)
		{
			m= "spesifik";
		}
		else if(indexid.indexOf('spesial') >= 0) 
		{
			m= "spesial";
		}

		if(indexid.indexOf('spesifik') >= 0 || indexid.indexOf('spesial') >= 0)
		{
			// m= "";
			// if(indexid.indexOf('spesifik') >= 0)
			// {
			// 	m= "spesifik";
			// }
			// else if(indexid.indexOf('spesial') >= 0) 
			// {
			// 	m= "spesial";
			// }

			if(indexname == "id_katalogtool" || indexname == "id_lokasiasal")
			{
				checkidkatalogtool= $('.checkidkatalogtool'+indexid).val();
				checkidlokasiasal= $('.checkidlokasiasal'+indexid).val();
				// console.log(checkidkatalogtool+"--"+checkidlokasiasal);
				// rowkuncigeneral= checkidkatalogtoolgeneral+"-"+checkidlokasiasalgeneral;
				// console.log(rowkuncigeneral);

				vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f=datainvtool&m='+m+'&k='+checkidkatalogtool+'&l='+checkidlokasiasal;
				// console.log(vurl);
		    	$.ajax({
					'url': vurl
					, beforeSend: function () {
						// $(".preloader-wrapper").show();
					}
					,'success': function(datahtml) {
						datahtml= JSON.parse(datahtml);
						// console.log(datahtml);

						var arrnotool= [];
						$.each(datahtml, function( index, value ) {
							// console.log(index);
							// console.log(value);
							var infodetil= {};
							infodetil.id= index;
							infodetil.text= value;
							arrnotool.push(infodetil);
						});
						// console.log(arrnotool);

						$(".checknotool"+indexid).empty();
						$(".checknotool"+indexid).select2({
							data: arrnotool,
							placeholder: '-pilih-',
							allowClear: true
						});
						$(".checknotool"+indexid).val("");
						$(".checknotool"+indexid).trigger('change');
						$("#rowkunci"+indexid).val("");
					}
				});
		    }
		    else if(indexname == "no_tool")
		    {
		    	checkidkatalogtool= $('.checkidkatalogtool'+indexid).val();
				checkidlokasiasal= $('.checkidlokasiasal'+indexid).val();
				checknotool= $('.checknotool'+indexid).val();
				rowkunci= checkidkatalogtool+"-"+checkidlokasiasal+"-"+checknotool;
				// console.log("#rowkunci"+indexid+";"+rowkunci);
				$("#rowkunci"+indexid).val(rowkunci);
		    }
		}
		else
		{
			if(indexname == "id_katalogtool" || indexname == "id_lokasiasal")
			{
				checkidkatalogtoolgeneral= $('.checkidkatalogtoolgeneral'+indexid).val();
				checkidlokasiasalgeneral= $('.checkidlokasiasalgeneral'+indexid).val();
				rowkuncigeneral= checkidkatalogtoolgeneral+"-"+checkidlokasiasalgeneral;
				// console.log(rowkuncigeneral);
				$("#rowkuncigeneral"+indexid).val(rowkuncigeneral);
			}
		}


		//untuk grouptool
		if(indexname == "id_grouptool")
		{
			checkidlokasiasal= $('.checkidlokasiasal'+indexid).val();
			if (m=="") 
			{
				checkidlokasiasal= $('.checkidlokasiasalgeneral'+indexid).val();
			}

			idgrouptool= $('.id_grouptool'+indexid).val();

			// console.log(checkidkatalogtool+"--"+checkidlokasiasal);
			// rowkuncigeneral= checkidkatalogtoolgeneral+"-"+checkidlokasiasalgeneral;
			// console.log(rowkuncigeneral);

			vurl= '<?=base_url("panelbackend/Ajaxtable/ajaxdetil")?>?f=datakatalogtool&m='+m+'&k='+idgrouptool+'&l='+checkidlokasiasal+'&a=reservasitool';
			// console.log(vurl);
	    	$.ajax({
				'url': vurl
				, beforeSend: function () {
					// $(".preloader-wrapper").show();
				}
				,'success': function(datahtml) {
					datahtml= JSON.parse(datahtml);
					// console.log(datahtml);

					var arrgrouptool= [];
					$.each(datahtml, function( index, value ) {
						// console.log(index);
						// console.log(value);
						var infodetil= {};
						infodetil.id= index;
						infodetil.text= value;
						arrgrouptool.push(infodetil);
					});
					// console.log(arrgrouptool);

					if (m=="spesial" || m=="spesifik") 
					{
						$(".checkidkatalogtool"+indexid).empty();
						$(".checkidkatalogtool"+indexid).select2({
							data: arrgrouptool,
							placeholder: '-pilih-',
							allowClear: true
						});
						$(".checkidkatalogtool"+indexid).val("");
						$(".checkidkatalogtool"+indexid).trigger('change');
						// $("#rowkunci"+indexid).val("");
					}
					else
					{
						$(".checkidkatalogtoolgeneral"+indexid).empty();
						$(".checkidkatalogtoolgeneral"+indexid).select2({
							data: arrgrouptool,
							placeholder: '-pilih-',
							allowClear: true
						});
						$(".checkidkatalogtoolgeneral"+indexid).val("");
						$(".checkidkatalogtoolgeneral"+indexid).trigger('change');
						// $("#rowkunci"+indexid).val("");
					}
				}
			});
	    }


		// $('.toolid'+indexid).val(null).trigger('change');

		/*checkidkatalogtoolgeneral;checkidlokasiasalgeneral

		$('.checkidkatalogtool-general').each(function(){
			infoid= $(this).attr('id');
			infoval= $(this).val();
			console.log(infoid+"-"+infoval);
		});

		nourut-general-2*/

		/*$('.id_katalogtool1').empty();
		// $('.id_katalogtool1').val("");
		$('.id_katalogtool1').select2({
			// data: arrlokasiasalpekerjaan,
			placeholder: '-pilih-',
			allowClear: true
		});*/

		/*$('.checkidkatalogtool-general').each(function(){
			infoid= $(this).attr('id');
			infoval= $(this).val();
			console.log(infoid+"-"+infoval);
		});*/

		/*$('.checkidlokasiasal-general').each(function(){
			infoid= $(this).attr('id');
			infoval= $(this).val();
			console.log(infoid+"-"+infoval);
		});*/
		

		/*Swal.fire({
			icon: 'error',
			title: 'Duplikasi data...',
			text: '',
			// footer: '<a href="">Why do I have this issue?</a>'
		})*/

		// indexid
		
		/*if(indexname == "consumableid")
		{
			infosatuan= data.satuan;
			infospesifikasi= data.spesifikasi;
			console.log(infospesifikasi);

			$('.satuan'+indexid).text(infosatuan);
			$('.spesifikasi'+indexid).text(infospesifikasi);
		}*/
	}

	/*$('.checkidkatalogtool-general').change(function(e) {
		alert("sd");
		// checkidkatalogtool-general
	});*/
	

	$('#tanggal_kebutuhan').on('dp.change', function(e)
	{ 
		status= "<?=$row['status']?>";

		if (status !== '2') 
		{
			tgl_butuh= $(this).val();
			tgl_reservasi= $('#tanggal_reservasitool').val();

			if (tgl_butuh < tgl_reservasi) 
			{
				$(this).val("");
				Swal.fire({
					title: 'Warning !',
					text: "Tanggal Kebutuhan tidak boleh kurang dari Tanggal Reservasi Tool !",
					icon: 'warning',
					showCancelButton: false,
					confirmButtonColor: '#d33'
				})
			}
		}		
	})
</script> -->

	<!-- TABLE SCROLL -->
	<!-- <script type="text/javascript" src="<?=base_url()?>lib/jquery.tableScroll-master/jquery.tablescroll.js"></script> -->
	<script type="text/javascript" src="<?=base_url()?>assets/js/globalfungsi.js"></script>

	<script>
	jQuery(document).ready(function($)
	{
		// $('#tablegeneral,#tablespesifik,#tablespesial').tableScroll({height:150});
		// $('#tablegeneral').tableScroll({height:150});
		// $('#tablespesifik').tableScroll({height:150});
	});
</script>