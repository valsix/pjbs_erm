
 <div class="modal fade" id="kriteriaKemungkinan" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="kriteriaKemungkinanLabel">Kriteria Kemungkinan</h4>
                        </div>
                        <div class="modal-body">

<table class="table table-stripped table-bordered">
	<thead>
		<tr>
      	<th >Kode</th>
			<th>Tingkat</th>
			<th>Probabilitas</th>
			<th>Deskripsi Kualitatif</th>
			<th>Insiden Sebelumnya</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$rowkemungkinan = $this->conn->GetArray("select * from mt_risk_kemungkinan order by kode desc");
		foreach ($rowkemungkinan as $r) {
			echo "<tr>";
      echo "<td>";
			echo $r['kode'];
			echo "</td>";
			echo "<td>";
			echo $r['nama'];
			echo "</td>";
			echo "<td>";
			if($editedheader1){
				echo "<button type='button' class='btn btn-xs btn-default' onclick='setkemungkinan($r[id_kemungkinan],1)'>pilih</button> ";
			}
			echo $r['probabilitas'];
			echo "</td>";
			echo "<td>";
			if($editedheader1){
				echo "<button type='button' class='btn btn-xs btn-default' onclick='setkemungkinan($r[id_kemungkinan],2)'>pilih</button> ";
			}
			echo $r['deskripsi_kualitatif'];
			echo "</td>";
			echo "<td>";
			if($editedheader1){
				echo "<button type='button' class='btn btn-xs btn-default' onclick='setkemungkinan($r[id_kemungkinan],3)'>pilih</button> ";
			}
			echo $r['insiden_sebelumnya'];
			echo "</td>";
			echo "</tr>";
		}
		?>
	</tbody>
</table>
</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>

<div class="modal fade" id="kriteriaDampak" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="kriteriaDampakLabel">Kriteria Dampak</h4>
                        </div>
                        <div class="modal-body">

<table class="table table-stripped table-bordered">
	<thead>
		<tr>
            <th width="30" rowspan="2">Kode</th>
			<th width="30%">Ketegori/Parameter Risiko</th>
			<?php
			$rowskategori = $this->conn->GetArray("select * from mt_risk_dampak order by id_dampak");
			foreach($rowskategori as $r){
			?>
			<th align="center"><?=$r['kode']." - ".$r['nama']?></th>
			<?php } ?>
		</tr>
    <tr>
      <th>Rating</th>
      <?php
			$rowskategori = $this->conn->GetArray("select * from mt_risk_dampak order by id_dampak");
			foreach($rowskategori as $r){
			?>
			<th align="center"><?=(float)$r['rating']?></th>
			<?php } ?>
    </tr>
	</thead>
	<tbody>
		<?php
		$rowkriteria = $this->conn->GetArray("select * from mt_risk_kriteria_dampak order by kode, id_kriteria_dampak");

		$return = array();

		$this->model->GenerateTree($rowkriteria, "id_induk", "id_kriteria_dampak", "nama", $return);

		$tempp = $this->conn->GetArray("select * from mt_risk_kriteria_dampak_detail");
		$kriteriadetail = array();
		foreach ($tempp as $r) {
			$kriteriadetail[$r['id_kriteria_dampak']][$r['id_dampak']] = $r['keterangan'];
		}

		foreach ($return as $r) {
			echo "<tr>";
			echo "<td>";
			echo $r['kode'];
			echo "</td>";
			echo "<td>";
			echo $r['nama'];
			echo "</td>";

			foreach ($rowskategori as $r1) {
				echo "<td>";
				if($editedheader1 && $kriteriadetail[$r['id_kriteria_dampak']][$r1['id_dampak']]){
					echo "<button type='button' class='btn btn-xs btn-default' onclick='setdampak($r1[id_dampak],$r[id_kriteria_dampak])'>pilih</button> ";
				}
				echo $kriteriadetail[$r['id_kriteria_dampak']][$r1['id_dampak']];
				echo "</td>";
			}
			echo "</tr>";
		}
		?>
	</tbody>
</table>
</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
<?php if($editedheader1){ ?>
	<script type="text/javascript">
		function setkemungkinan(id_kemungkinan, id_kriteria){
			var inheren_kemungkinan = $("#inheren_kemungkinan");
			var id_kriteria_kemungkinan = $("#id_kriteria_kemungkinan");

			if(inheren_kemungkinan.length>0 && id_kriteria_kemungkinan.length>0){
				inheren_kemungkinan.val(id_kemungkinan);
				id_kriteria_kemungkinan.val(id_kriteria);
				inheren_kemungkinan.change();
				id_kriteria_kemungkinan.change();
			}

			var residual_target_kemungkinan = $("#residual_target_kemungkinan");

			if(residual_target_kemungkinan.length>0){
				residual_target_kemungkinan.val(id_kemungkinan);
				residual_target_kemungkinan.change();
			}

			var control_kemungkinan_penurunan = $("#control_kemungkinan_penurunan");

			if(control_kemungkinan_penurunan.length>0){
				control_kemungkinan_penurunan.val(id_kemungkinan);
				control_kemungkinan_penurunan.change();
			}

			$("#kriteriaKemungkinan").modal('toggle');
		}
		function setdampak(id_dampak, id_kriteria){
			var inheren_dampak = $("#inheren_dampak");
			var id_kriteria_dampak = $("#id_kriteria_dampak");

			if(inheren_dampak.length>0 && id_kriteria_dampak.length>0){
				inheren_dampak.val(id_dampak);
				id_kriteria_dampak.val(id_kriteria);
				inheren_dampak.change();
				id_kriteria_dampak.change();
			}

			var residual_target_dampak = $("#residual_target_dampak");

			if(residual_target_dampak.length>0){
				residual_target_dampak.val(id_dampak);
				residual_target_dampak.change();
			}

			var control_dampak_penurunan = $("#control_dampak_penurunan");

			if(control_dampak_penurunan.length>0){
				control_dampak_penurunan.val(id_dampak);
				control_dampak_penurunan.change();
			}
			
			$("#kriteriaDampak").modal('toggle');
		}
	</script>
<?php } ?>