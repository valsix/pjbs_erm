<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th style="width: 500px">Nama Mitigasi</th>
			<th>Penanggung Jawab</th>
			<th>Progress</th>
			<th>Deadline</th>
		</tr>
	</thead>
	<?php foreach($rows as $r){ ?>
		<tr>
			<td><?=$r['nama']?></td>
			<td><?=$r['jabatan']?></td>
			<td><?=($r['prosentase']?$r['prosentase'].'%':'')?> <?=$r['progress']?></td>
			<td><?=Eng2Ind($r['dead_line'])?></td>
		</tr>
	<?php } ?>
</table>