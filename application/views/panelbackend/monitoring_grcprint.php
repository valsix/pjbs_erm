<?

	?>
	

	<table cellpadding="2" width="100%" class="table-label" border="1">
		<thead>
			<tr>
		        <th width="30" colspan="4">List File GRC</th>

		        <?php
		        $rowsfolder = $this->conn->GetArray("select * from mt_fd_dok_pendukung_grc where status = 1");
		        foreach($rowsfolder as $r){
		        ?>
		        <th rowspan="2" align="center" style="width: 50px;"><?=$r['alias']?></th>
		        <?php } ?>

		        <!-- <th rowspan="2">Hasil Petikan Radir</th> -->
		        <th rowspan="2">Divisi Pemrakarsa</th>
		        <!-- <th rowspan="2">PIC RTH</th> -->
		        <th rowspan="2">Progress/Status</th>
		    </tr>
		    <tr>
		        <th>Berita Acara</th>
		        <th>Start Date</th>
		        <th>Nama Program</th>
		        <!-- <th>Hasil Keputusan Radir</th> -->
		        <th>Level</th>
		        
		    </tr>
		</thead>

		<tbody>
			<?php
			$i = $page;
    foreach($list['rows'] as $rows){
        $i++;
        ?>

        <tr>
        <?php 
        echo "<td>$rows[nomor_ba_grc]</td>";
        echo "<td>$rows[tgl_mulai_efektif]</td>";
        echo "<td>$rows[nama]</td>";
        // echo "<td>$rows[hasil_keputusan_radir]</td>";
        echo "<td>$rows[id_level_grc]</td>";
        ?>
        <?php
    	// echo "<td style='text-align:right'>";
        // echo UI::showMenuMode('inlist', $rows[$pk]);
    	// echo "</td>";
        // echo "</tr>";
    }
    if(!$list['rows']){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
			?>
		</tbody>
	</table>

	

	<br><br><br>
	<?
// }


?>






<style type="text/css">
    td{
        padding: 1px !important;
        font-size: 11px !important;
        vertical-align: top !important;
    }
    th{
        padding: 5px !important;
        font-size: 11px !important;
    }
    thead th{
        text-align: center;
        vertical-align: middle !important;
    }
</style>