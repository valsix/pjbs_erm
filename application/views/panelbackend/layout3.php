
<div id="container" class="container" <?=($width_page?"style='max-width:$width_page'":"")?>>
<center>
	<div class="notshow">
	<?php if($excel!==false){ ?>
	<a download="<?=$page_title?>.xls" class="btn btn-sm btn-primary" href="#" onclick="return ExcellentExport.excel(this, 'datatable', '<?=$page_title?>');">
	<span class="glyphicon glyphicon-th"></span> Excel</a>
    &nbsp;
    <?php }?>
	<a class="btn btn-sm btn-primary" onclick="window.print()">
	<span class="glyphicon glyphicon-print"></span>
	Print
	</a>
	</div>
</center>

<div id="datatable">
	<?php if(!$no_header){ ?>
	<div class="header">
	<table border="0" style="padding: 0px; margin: 0px;" width="100%">
	<tr>
		<td width="10px"></td>
		<td width="100px" align="left">
		<img src="<?=base_url()?>assets/img/logo.jpg" width="70px">
		</td>
		<td>
		<b>
		<b>
		<h4 style="font-weight: bold;">
		<?=$this->config->item("company_name")?>
		</h4>
		</b>
		<small>
		<?=$this->config->item("company_address")?>
		</small>
		</b>
		</td>
	</tr>
	<tr>
		<td width="10px"></td>
		<td colspan="2">
			<table border="0" width="100%">
				<tr style="border-top:1px solid #555;border-bottom:1px solid #555;">
					<td width="30%" align="left"><small>Telepon : <?=$this->config->item("company_telp")?></small></td>
					<td width="30%" align="center"><small>Faksimile : <?=$this->config->item("company_fax")?></small></td>
					<td width="30%" align="right"><small>Email : <?=$this->config->item("company_email")?></small></td>
				</tr>
			</table>
		</td>
	</tr>
	</table>
	</div>
	<ins><h5 style="padding:10px;text-align: center;">
	<b><?=$page_title?></b>
	</h5></ins>
	<?php } ?>
<?php echo $content1;?>
</div>
</div>
<script src="<?php echo base_url()?>assets/js/excellentexport.min.js"></script>
<style>
		.notshow{
			margin-top: 10px;
		}
	@media print{
		.notshow{
			display: none;
		}
		body{
			margin: 0px;
			padding: 10px 5px;
		}
		html{
			margin:0px;
			padding:0px;
		}
	}
#container{
	width: 100%;
    font-size:14px;
    font-family:Arial, Helvetica, sans-serif;
}
td,th{
    padding: 3px;
    font-size: 12px;
    vertical-align:text-center;
}
.h4, .h5, .h6, h4, h5, h6, hr {
    margin-top: 5px;
    margin-bottom: 5px;
}
.tableku {
    margin-top: 20px;
    width:100%;
    border:1px solid #555;
}
.tableku td{border: 1px solid #555;
    padding: 0px 3px;
	vertical-align: top;
}
.tableku thead th{
	border:1px solid #555;
	border-bottom:2px  solid #555;
	padding:0px 3px;
}
.tableku th{
	border:1px solid #555;
	padding:0px 3px;
}
.tableku thead, .tableku1 thead{
	border:1px solid #555;
	page-break-before: always;
}
hr{
	border-color:#555;
}
.tableku1 {
    margin-top: 10px;
    width:100%;
    border:1px solid #555;
}
.tableku1 td{
    border:1px solid #555;
	padding:3px 5px;   
	vertical-align: top;
}
.tableku1 thead th{
	border:1px solid #555;
	border-bottom:2px  solid #555;
	padding:3px 5px;    
	text-align: center;
}
.tableku1 th{
	border:1px solid #555;
	padding:0px 3px;
	text-align: center;
}

</style>
