<button type="button" class="btn btn-info waves-effect" data-toggle="modal" data-target="#sasaranStrategis"><span class="glyphicon glyphicon-info-sign"></span>Sasaran Strategis</button>
 <div class="modal fade" id="sasaranStrategis" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="sasaranStrategisLabel">Sasaran Strategis</h4>
              </div>
              <div class="modal-body">

<table class="table table-stripped table-bordered">
	<thead>
		<tr>
      <th>Nama Sasaran Strategis</th>
      <th>PIC Sasaran Strategis</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$row_sasaran_strategis = $this->conn->GetArray("select
                        rss.ID_SASARAN_STRATEGIS,
                        rss.NAMA as nama_sasaran_strategis,
                        rss.KPI, rss.KPI_DESKRIPSI,
                        rssp.id_jabatan,
                        msj.nama as jabatan
                        from RISK_SASARAN_STRATEGIS rss
                        join RISK_SASARAN_STRATEGIS_PIC rssp on rssp.ID_SASARAN_STRATEGIS = rss.ID_SASARAN_STRATEGIS
                        left join MT_SDM_JABATAN msj on msj.id_jabatan = rssp.id_jabatan"
                      );
		foreach ($row_sasaran_strategis as $r) {
			echo "<tr>";
      echo "<td>";
			echo $r['nama_sasaran_strategis'];
			echo "</td>";
      echo "<td>";
      echo $r['jabatan'];
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
