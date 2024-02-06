<?php
class UI {

	private $auth = array();

	public static function FormGroup($array=array()){
		return self::createFormGroup($array['form'],$array['rule'],$array['name'],$array['label'],$array['onlyone'],$array['sm_label'], $array['edited']);
	}

	public static function createFormGroup($form=null, $rule=null, $name=null, $label=null, $onlyone=false, $sm_label=4, $edited=true){

		if(!$form)
			return;

		if($onlyone){

		if($edited && $rule)
			$form_error = form_error($name);

			$ret= '
<div class="form-group '.(($form_error)?'has-error':'').'">';
if($label){
$ret .= '
	<label for="'.$name.'" class="col-sm-12" style="margin-bottom:12px;margin-top:15px;">
		'.$label;
		if(strstr($rule['rules'],'required')!==false && $edited){
			$ret .= '&nbsp;<span style="color:#dd4b39">*</span>';
		}

		$ret .= '
	</label>';
}
	$ret .= '
	<div class="col-sm-12">
		'.$form.'
		<span style="color:#dd4b39; font-size:11px; '.(($form_error)?'':'display: none').'" id="info_'.$name.'">
		'.$form_error.'
		</span>
	</div>
</div>';
			return $ret;
		}

		$sm_form = 12 - $sm_label;
		if(!$rule['rules']){

		$ret= '
<div class="form-group">
	<label for="'.$name.'" class="col-sm-'.$sm_label.' control-label">
		'.$label.'
	</label>
	<div class="col-sm-'.$sm_form.'">'.$form.'
	</div>
</div>';
		return $ret;

		}

		if($edited)
			$form_error = form_error($name);

		$ret= '
<div class="form-group '.(($form_error)?'has-error':'').'">
	<label for="'.$name.'" class="col-sm-'.$sm_label.' control-label">
		'.$label;
		if(strstr($rule['rules'],'required')!==false && $edited){
			$ret .= '&nbsp;<span style="color:#dd4b39">*</span>';
		}

		$ret .= '
	</label>
	<div class="col-sm-'.$sm_form.'">'.$form;
			$ret .= '
			<span style="color:#dd4b39; font-size:11px; '.(($form_error)?'':'display: none').'" id="info_'.$name.'">
			'.$form_error.'
			</span>';
		$ret .= '
	</div>
</div>';
		return $ret;
	}

	public static function createFormGroupPlain($form=null, $rule=null, $name=null, $label=null, $onlyone=false, $edited=true){

		if(!$form)
			return;

		if($onlyone){

			$ret= '
<div class="form-group">
	<div class="col-sm-input">
	'.$form.'
	</div>
</div>';
			return $ret;
		}

		if(!$rule['rules']){

		$ret= '
<div class="form-group">
	<label for="'.$name.'" class="col-sm-2 control-label" style="text-align: left;">
		'.$label.'
	</label>
	<div class="col-sm-input">'.$form.'
	</div>
</div>';
		return $ret;

		}

		if($edited)
			$form_error = form_error($name);

		$ret= '
<div class="form-group '.(($form_error)?'has-error':'').'">
	<label for="'.$name.'" class="col-sm control-label">
		'.$label;
		if(strstr($rule['rules'],'required')!==false && $edited){
			$ret .= '&nbsp;<span style="color:#dd4b39">*</span>';
		}

		$ret .= '
	</label>
	<div class="col-sm-input">'.$form;
			$ret .= '
			<span style="color:#dd4b39; font-size:11px; '.(($form_error)?'':'display: none').'" id="info_'.$name.'">
			'.$form_error.'
			</span>';
		$ret .= '
	</div>
</div>';
		return $ret;
	}

	public static function createTextArea($nameid,$value='',$rows='',$cols='',$edit=true,$class='form-control',$add='') {
        //if (empty($class))
        //    $class = 'control_style';

		if(!empty($edit)) {
			$ta = '<div class="form-line"><textarea wrap="soft" name="'.$nameid.'" id="'.$nameid.'"';
			if($class != '') $ta .= ' class="'.$class.'"';
			if($rows != '') $ta .= ' rows="'.$rows.'"';
			if($cols != '') $ta .= ' cols="'.$cols.'"';
			if($add != '') $ta .= ' '.$add;
			$ta .= '>';
			if($value != '') $ta .= $value;
			$ta .= '</textarea></div>';
		}
		else if($value == '')
			$ta = '<i style="color:#aaa" class="read_detail">kosong</i>';
		else
			$ta = "<span class='read_detail'>".nl2br($value)."</span>";

		return $ta;
	}

	public static function createTextEditor($nameid,$value='',$rows='',$cols='',$edit=true,$class='form-control',$add='') {
        //if (empty($class))
        //    $class = 'control_style';

		if(!empty($edit)) {
			$ta = '<div class="form-line"><textarea wrap="soft" name="'.$nameid.'" id="'.$nameid.'"';
			if($class != '') $ta .= ' class="'.$class.'"';
			if($rows != '') $ta .= ' rows="'.$rows.'"';
			if($cols != '') $ta .= ' cols="'.$cols.'"';
			if($add != '') $ta .= ' '.$add;
			$ta .= '>';
			if($value != '') $ta .= $value;
			$ta .= '</textarea></div>';
		}
		else if($value == '')
			$ta = '<i style="color:#aaa" class="read_detail">kosong</i>';
		else
			$ta = "<span class='read_detail'>".($value)."</span>";

		return $ta;
	}

	// membuat textbox
	public static function TextBox($arr=array()) {

		return self::createTextBox($arr['name'],$arr['value'],$arr['maxlength'],$arr['size'],$arr['edited'],$arr['class'],$arr['add']);
	}

	// membuat textbox
	public static function createTextBox($nameid,$value='',$maxlength='',$size='',$edit=true,$class='form-control',$add='') {
        //if (empty($class))
        //    $class = 'control_style';

		if(!empty($edit)) {
			$tb = '<div class="form-line"><input type="text" name="'.$nameid.'" id="'.$nameid.'"';
			if($value != '') $tb .= ' value="'.$value.'"';
			if($class != '') $tb .= ' class="'.$class.'"';
			if($maxlength != '') $tb .= ' maxlength="'.$maxlength.'"';
			if($size != '') $tb .= ' size="'.$size.'"';
			if($add != '') $tb .= ' '.$add;
			$tb .= '></div>';
		}
		else if($value == '')
			$tb = '<i style="color:#aaa" class="read_detail">kosong</i>';
		else{
			$class = str_replace('form-control', '', $class);
			if(strstr($class, "datepicker")!==false)
				$tb = "<span class='$class read_detail'>".Eng2Ind($value)."</span>";
			else
				$tb = "<span class='$class read_detail'>".$value."</span>";
		}

		return $tb;
	}

	// membuat texthidden
	public static function createTextHidden($nameid,$value='',$edit=true, $add='') {

		if(!empty($edit)) {
			$tb = '<input type="hidden" name="'.$nameid.'" id="'.$nameid.'"';
			if($value != '') $tb .= ' value="'.$value.'"';
			if($add != '') $tb .= ' '.$add;
			$tb .= '>';
		}

		return $tb;
	}

	// membuat textbox
	public static function createTextDate($nameid,$value='',$maxlength='',$size='',$edit=true,$class='form-control',$add='') {
        //if (empty($class))
        //    $class = 'control_style';

		if(!empty($edit)) {
			$tb = '<input type="text" name="'.$nameid.'" id="'.$nameid.'"';
			if($value != '') $tb .= ' value="'.$value.'"';
			$tb .= ' class="datepicker '.$class.'"';
			if($maxlength != '') $tb .= ' maxlength="'.$maxlength.'"';
			if($size != '') $tb .= ' size="'.$size.'"';
			if($add != '') $tb .= ' '.$add;
			$tb .= '>';
		}
		else if($value == '')
			$tb = '<i style="color:#aaa" class="read_detail">kosong</i>';
		else
			$tb = "<span class='read_detail'>".$value."</span>";

		return $tb;
	}

	// membuat textbox
	public static function createAutoComplate($nameid,$value=array(),$url,$maxlength='',$size='',$edit=true,$class='form-control',$add='') {
        //if (empty($class))
        //    $class = 'control_style';

		if(!empty($edit)) {
			$tb = '<input autocomplete="off" type="text" name="name'.$nameid.'" id="name'.$nameid.'"';
			if($value['label'] != '') $tb .= ' value="'.$value['label'].'"';
			if($class != '') $tb .= ' class="'.$class.'"';
			if($maxlength != '') $tb .= ' maxlength="'.$maxlength.'"';
			if($size != '') $tb .= ' size="'.$size.'"';
			if($add != '') $tb .= ' '.$add;
			$tb .= '>';

			$tb .= '<input type="hidden" name="'.$nameid.'" id="'.$nameid.'"';
			if($value['id']) $tb .= ' value="'.$value['id'].'"';
			$tb .='/>';

			$tb .= '<script>
			$(function(){
				$("#'.$nameid.'").autocomplete("'.base_url($url).'");
			});
			</script>';
		}
		else if($value['label'] == '')
			$tb = '<i style="color:#aaa" class="read_detail">kosong</i>';
		else
			$tb = "<span class='read_detail'>".$value['label']."</span>";


		return $tb;
	}

	// membuat textbox 'file',$row['nama_file'], base_url("panelbackend/preview_file/$row[id_buku]"), base_url("panelbackend/delete_file/$row[id_buku]"), $edited, false, 'form-control'

	public static function InputFile($array=array()){
		$default = array(
			"edit"=>false,
			"ispreview"=>false,
			"class"=>"form-control",
			"add"=>"style=\"width:auto\"",
		);
		foreach ($default as $key => $value) {
			if($array[$key]===null)
				$array[$key] = $value;
		}
		return self::createInputFile($array['nameid'], $array['nama_file'], $array['url_preview'], $array['url_delete'], $array['edit'], $array['ispreview'], $array['class'], $array['add'], $array['extarr']);
	}
	public static function createInputFile($nameid, $nama_file='', $url_preview='', $url_delete='', $edit=true, $ispreview=false, $class='form-control', $add='style="width:auto"', $extarr=array()) {
        //if (empty($class))
        //    $class = 'control_style';

		if(!empty($edit) && (!$nama_file or !$url_delete)) {
			$accept = "";
			$tb = '';
			if(($extarr)){
				$accept = 'accept="'.implode(', ', $extarr).'"';
				$tb .= '<span class="label label-info">.'.implode(', .', $extarr).'</span>';
			}
			$tb .= '<input type="file" name="'.$nameid.'" id="'.$nameid.'"';
			if($class != '') $tb .= ' class="'.$class.'"';
			if($add != '') $tb .= ' '.$add;
			$tb .= $accept;
			$tb .= '>';
		}
		else if($nama_file == '')
			$tb = '<i style="color:#aaa" class="read_detail">kosong</i>';



		if($ispreview && $url_preview && $nama_file){
			$tb .= "<img src='$url_preview'/>";
		}
		if($nama_file){
		$tb .= "<div style='clear:both'></div>";
		if($url_preview){
			$tb .= "<a target='_blank' href='$url_preview'>$nama_file</a> ";
		}else{
			$tb .= "$nama_file&nbsp; ";
		}
		if(!empty($edit) && $nama_file && $url_delete) {
			$tb .= "<a href='$url_delete'><span class='glyphicon glyphicon-remove' style='color:red'></span></a> ";
		}
		$tb .= "<div style='clear:both'></div>";
		}
		return $tb;
	}

	// membuat textbox
	public static function createTextNumber($nameid,$value='',$maxlength='',$size='',$edit=true,$class='form-control',$add='') {
        //if (empty($class))
        //    $class = 'control_style';

		if(!empty($edit)) {
			$tb = '<div class="form-line"><input type="number" name="'.$nameid.'" id="'.$nameid.'"';
			if($value != '') $tb .= ' value="'.$value.'"';
			if($class != '') $tb .= ' class="'.$class.'"';
			if($maxlength != '') $tb .= ' maxlength="'.$maxlength.'"';
			if($size != '') $tb .= ' size="'.$size.'"';
			if($add != '') $tb .= ' '.$add;
			$tb .= '></div>';
		}
		else if($value == '')
			$tb = '<i style="color:#aaa" class="read_detail">kosong</i>';
		else
			$tb = "<span class='read_detail'>".rupiah($value)."</span>";

		return $tb;
	}

	// membuat textbox
	public static function createTextPassword($nameid,$value='',$maxlength='',$size='',$edit=true,$class='form-control',$add='') {
        //if (empty($class))
        //    $class = 'control_style';

		if(!empty($edit)) {
			$tb = '<div class="form-line"><input type="password" name="'.$nameid.'" id="'.$nameid.'"';
			if($value != '') $tb .= ' value="'.$value.'"';
			if($class != '') $tb .= ' class="'.$class.'"';
			if($maxlength != '') $tb .= ' maxlength="'.$maxlength.'"';
			if($size != '') $tb .= ' size="'.$size.'"';
			if($add != '') $tb .= ' '.$add;
			$tb .= '></div>';
		}
		else if($value == '')
			$tb = '<i style="color:#aaa" class="read_detail">kosong</i>';
		else
			$tb = "<span class='read_detail'>".$value."</span>";

		return $tb;
	}

	// membuat combo box
	public static function createSelect($nameid,$arrval='',$value='',$edit=true,$class='form-control',$add='',$emptyrow=false) {

		if(!$edit)
			$arrval[''] = '<i>belum diisi</i>';

		if(!empty($edit)) {
			if($nameid=='list_limit')
				$slc = '<div class="form-line"><select style="width:auto" data-placeholder="Pilih..." tabindex="2" name="'.$nameid.'" id="'.$nameid.'"';
			else
				$slc = '<div class="form-line"><select style="width:100%" data-placeholder="Pilih..." tabindex="2" name="'.$nameid.'" id="'.$nameid.'"';
			$slc .= ' class="'.(($class != '')?$class:'').'"';
			if($add != '') $slc .= ' '.$add;
			$slc .= ">\n";
			if($emptyrow)
				$slc .= '<option></option>'."\n";
			if(is_array($arrval)) {
				foreach($arrval as $key => $val) {
					$slc .= '<option value="'.$key.'"'.(!strcasecmp($value,$key) ? ' selected' : '').'>';
					$slc .= $val.'</option>'."\n";
				}
			}
			$slc .= '</select></div>';
		}
		else {
			if(is_array($arrval)) {
				foreach($arrval as $key => $val) {
					if(!strcasecmp($value,$key)) {
						$slc = "<span class='read_detail'>".$val."</span>";
						break;
					}
				}
			}
			if(!isset($slc))
				$slc = '&nbsp;';
		}

		return $slc;
	}

		// membuat combo box
		public static function createSelectMultiple($nameid,$arrval='',$arrvalue=array(),$edit=true,$class='form-control',$add='',$emptyrow=false) {
			if(!is_array($arrvalue))$arrvalue = array($arrvalue);
			if(!empty($edit)) {
				$slc = '<div class="form-line">
				<select tabindex="4" multiple name="'.$nameid.'" id="'.$nameid.'"';
				$slc .= ' class="chosen-select '.(($class != '')?$class:'').'"';
				if($add != '') $slc .= ' '.$add;
				$slc .= ">\n";
				if($emptyrow)
					$slc .= '<option></option>'."\n";
				if(is_array($arrval)) {
					foreach($arrval as $key => $val) {
						$slc .= '<option value="'.$key.'"'.(in_array($key,$arrvalue) ? ' selected' : '').'>';
						$slc .= $val.'</option>'."\n";
					}
				}
				$slc .= '</select>
				</div>';
			}
			else {
				$value_d = array();
				if(is_array($arrval)) {
					foreach($arrval as $key => $val) {
						if(in_array($key,$arrvalue)) {
							$value_d[] = $val;
						}
					}
				}
				$slc .= "<span class='read_detail'>".implode(', ', $value_d)."</span>";
				if(!isset($slc))
					$slc = '&nbsp;';
			}

			return $slc;
		}
			// membuat combo box
			public static function createSelectMultipleAutocomplate($nameid,$arrval='',$arrvalue=array(),$edit=true,$class='form-control',$add='',$emptyrow=false) {
				if(!is_array($arrvalue))$arrvalue = array($arrvalue);
				if(!empty($edit)) {
					$slc = '<div class="form-line"><select  data-ajax--data-type="json" tabindex="4" multiple name="'.$nameid.'" id="'.$nameid.'"';
					$slc .= ' class="chosen-select '.(($class != '')?$class:'').'"';
					if($add != '') $slc .= ' '.$add;
					$slc .= ">\n";
					if($emptyrow)
						$slc .= '<option></option>'."\n";
					if(is_array($arrval)) {
						foreach($arrval as $key => $val) {
							$slc .= '<option value="'.$key.'"'.(in_array($key,$arrvalue) ? ' selected' : '').'>';
							$slc .= $val.'</option>'."\n";
						}
					}
					$slc .= '</select></div>';
				}
				else {
					$value_d = array();
					if(is_array($arrval)) {
						foreach($arrval as $key => $val) {
							if(in_array($key,$arrvalue)) {
								$value_d[] = $val;
							}
						}
					}
					$slc .= "<span class='read_detail'>".implode(', ', $value_d)."</span>";
					if(!isset($slc))
						$slc = '&nbsp;';
				}

				return $slc;
			}

	// membuat combo box
	public static function createSelectKategori($nameid,$arrval='',$value='',$edit=true,$class='form-control',$add='',$emptyrow=false) {
		if(!empty($edit)) {
			$slc = '<select name="'.$nameid.'" id="'.$nameid.'"';
			if($class != '') $slc .= ' class="'.$class.'"';
			if($add != '') $slc .= ' '.$add;
			$slc .= ">\n";
			if($emptyrow)
				$slc .= '<option></option>'."\n";
			if(is_array($arrval)) {
				foreach($arrval as $key => $val) {
					$slc .= '<option value="'.$key.'"'.(!strcasecmp($value,$key) ? ' selected' : '').'>';
					$slc .= $val.'</option>'."\n";
				}
			}
			$slc .= '</select>';
		}
		else {
			if(is_array($arrval)) {
				foreach($arrval as $key => $val) {
					if(!strcasecmp($value,$key)) {
						$slc = $val;
						break;
					}
				}
			}
			if(!isset($slc))
				$slc = '&nbsp;';
		}

		return $slc;
	}

	// membuat textbox
	public static function createCheckBox($nameid,$valuecontrol='',$value='',$label='label',$edit=true,$class='',$add='') {
        //if (empty($class))
        //    $class = 'control_style';


		$tb = '<input type="checkbox" name="'.$nameid.'" id="'.$nameid.'"';
		if($valuecontrol != '') {
			$tb .= ' value="'.$valuecontrol.'"';
			if ($value == $valuecontrol)
				$tb .= ' checked ';
		}
		if($class != '') $tb .= ' class="'.$class.'"';
		if($add != '') $tb .= ' '.$add;
		if(!$edit)
			$tb .= ' disabled ';
		$tb .= '>';

		$tb .= "<label for='$nameid' style='margin: 0px;
    padding: 0px 25px;'><b>$label</b></label>";

		return $tb;
	}

	// membuat radio button
	public static function createRadio($nameid,$arrval='',$value='',$edit=true,$br=false,$class='form-control',$add='') {
        //if (empty($class))
        //    $class = 'control_style';

		$radio = '';

		if(!empty($edit)) {
			if(is_array($arrval)) {
				foreach($arrval as $key => $val) {
					$radio .= '<input type="radio" name="'.$nameid.'" id="'.$nameid.'_'.$key.'" value="'.$key.'"'.(!strcasecmp($value,$key) ? ' checked' : '').' '.$add.'>';
					$radio .= '<label for="'.$nameid.'_'.$key.'"> '.$val.'</label>'.($br ? '<br>' : '&nbsp;&nbsp;')."\n";
				}
			}
		}
		else {
			if(is_array($arrval)) {
				foreach($arrval as $key => $val) {
					if(!strcasecmp($value,$key)) {
						$radio = "<span class='read_detail'>".$val."</span>";
						break;
					}
				}
			}
		}

		return $radio;
	}

	public static function showPaging($paging, $page, $limit_arr, $limit, $list){
		if(!$list['total'])
			return;

		$batas_atas = $page+1;
		$batas_bawah = $batas_atas+($limit-1);
		if($batas_bawah>$list['total']){
			$batas_bawah = $list['total'];
		}
		?>
		<div class="row">
			<div class="col-sm-5" style="margin-bottom: 0px">
				<div class="dataTables_info dataTables_length">
					Perhalaman
					<?php
					foreach($limit_arr as $k=>$v){$limit_arr1[$v]=$v;}
					echo self::createSelect('list_limit',$limit_arr1,$limit, true, 'form-control input-sm', 'onchange="goLimit()"');
					?>
					Menampilkan <?=$batas_atas?> sampai <?=$batas_bawah?> dari total <?=$list['total']?> data

				</div>
			</div>
			<div class="col-sm-7" style="margin-bottom: 0px">
				<div class="dataTables_paginate paging_simple_numbers">
		  			<ul class="pagination">
						<?=$paging?>
					</ul>
				</div>
			</div>
		</div>

		<script>
		    function goLimit(){
		        $("#act").val('list_limit');
		        $("#main_form").submit();
		    }

		</script>
		<?php
	}

	public static function showPagingCms($paging, $page, $limit_arr, $limit, $list){
		if(!$list['total'])
			return;

		$batas_atas = (($page-1)*$limit)+1;
		$batas_bawah = $batas_atas+($limit-1);
		if($batas_bawah>$list['total']){
			$batas_bawah = $list['total'];
		}
		?>
		<nav>
  <ul class="pagination" style="display:inline">

			<?=$paging?>

		</ul>&nbsp;&nbsp;
			<?php
			foreach($limit_arr as $k=>$v){$limit_arr1[$v]=$v;}
			echo " Perhalaman ".self::createSelect('list_limit',$limit_arr1,$limit, true, 'dropdown',
			'onchange="goLimit()"
			style="display: inline;
			height: 23px;
			color: #666;
			padding: 4px 4px 4px;
			font-size: 14px;
			background-color: #fff;
			border-radius: 2px;
			-webkit-box-sizing: content-box;
			-moz-box-sizing: content-box;
			box-sizing: content-box;
			margin-top: 0px;
			border-color: #ddd;"'
			);
			?>

			<div style="float:right">Menampilkan <?=$batas_atas?> sampai <?=$batas_bawah?> dari <?=$list['total']?> data</div>
		</nav>
		<div style="clear:both"></div>
		<script>
		    function goLimit(){
		        jQuery("#act").val('list_limit');
		        jQuery("#main_form").submit();
		    }

		</script>
		<?php
	}

	public static function showHeader($header, $filter_arr, $list_sort, $list_order, $is_filter=true, $is_sort = true, $is_no = true){

		$ci = get_instance();
		if($is_filter){
	?>
	      <tr id="first-row">
	      	<?php if($is_no){ ?>
	        <td></td>
	        <?php } ?>
	        <?php foreach($header as $rows){
	        	if($rows['field']){
	        		$rows['name'] = $rows['field'];
	        	}
	        	$edited = true;
	        	if($rows['filter']===false){
	        		$edited = $rows['filter'];
	        		$filter_arr[$rows['name']] = '&nbsp';
	        	}
	        	switch ($rows['type']) {
	        		case 'list':
	        			echo "<td style='width:$rows[width];'><div class='form-group'>".self::createSelect("list_search_filter[".$rows['name']."]",$rows['value'],$filter_arr[$rows['name']],$edited,'form-control',"style='max-width:$rows[width];'")."</div></td>";
	        			break;

	        		case 'date':
	        			echo "<td></td>";
	            		//echo "<td style='position:relative;width:$rows[width];'><div class='form-group'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
	        			break;

	        		case 'datetime':
	        			echo "<td></td>";
	            		//echo "<td style='position:relative;width:$rows[width];'><div class='form-group'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datetimepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
	        			break;

	        		case 'number':
	            		echo "<td style='position:relative;width:$rows[width];'><div class='form-group'>".self::createTextNumber("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control',"style='max-width:$rows[width];'")."</div></td>";
	        			break;

	        		default:
	            		echo "<td style='width:$rows[width];'><div class='form-group'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
	        			break;
	        	}
	        }
	        ?>
	        <td style='text-align:left; width:70px'>
	        <button type="submit" class='btn btn-default btn-sm' title="Filter">
			<span class="glyphicon glyphicon-search"></span>
	        </button>
	        <button type="button" class="btn waves-effect btn-sm btn-default" onclick="goReset()" title="Reset">
	        <span class="glyphicon glyphicon-refresh"></span>
	        </button>  	       
	        </td>
	      </tr>
	      <?php }
	      if($is_sort){
	      ?>
	      <tr>
	      	<?php if($is_no){ ?>
	        <th style="width:10px">#</th>
	        <?php } ?>
	        <input type='hidden' name='list_sort' id='list_sort'>
	        <input type='hidden' name='list_order' id='list_order'>
	        <?php foreach($header as $rows){
	        	if($rows['type']=='list' or $rows['type']=='implodelist'){
		        	   echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
		        }else{
	        		if($rows['type']=='number'){
		        	   $align = "text-align:right;";
		        	}
		        	$add_label = $rows['add_label'];

		            if($list_sort==$rows['name']){
		                if(trim($list_order)=='asc'){
		                    $order = 'desc';
		                }else{
		                    $order = 'asc';
		                }

		               if($add_label){
		               echo "<th style='$align max-width:$rows[width];' class='sorting_".$order."' > $add_label <a href='javascript:void(0)' onclick=\"goSort('{$rows['name']}','$order')\" style='color:#fff;text-decoration:none'>$rows[label]</a> </th>";
			           }else{
		               echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting_".$order."' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</th>";
			           }
		            }else{
		               if($add_label){
		        	   echo "<th style='$align max-width:$rows[width];' class='sorting'> $add_label <a href='javascript:void(0)' onclick=\"goSort('{$rows['name']}','asc')\"style='color:#fff;text-decoration:none'>$rows[label]</a></th>";
		        		}else{
		        	   echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</th>";
		        		}
		            }
		        }
	        }
	        ?>
	        <th></th>
	      </tr>
	      <?php }else{ ?>
	      <tr>
	      	<?php if($is_no){ ?>
	        <th style="width:10px">#</th>
	        <?php } ?>
	        <?php foreach($header as $rows){
        	   echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
	        }
	        ?>
	        <th></th>
	      </tr>
	      <?php } ?>

	      <?php if($is_sort or $is_filter){ ?>
	    <script>
		    $(function(){
		        $("#main_form").submit(function(){
		            if($("#act").val()==''){
		                goSearch();
		            }
		        });
		    });

		    function goSort(name, order){
		        $("#list_sort").val(name);
		        $("#list_order").val(order);
		        $("#act").val('list_sort');
		        $("#main_form").submit();
		    }

		    function goSearch(){
		        $("#act").val('list_search');
		        $("#main_form").submit();
		    }

			function goReset(){
				$("#act").val('list_reset');
				$("#main_form").submit();
			}
	    </script>
    <?php
    	}
	}

	public static function showHeaderTree($header, $filter_arr, $list_sort, $list_order, $is_filter=true){

		$ci = get_instance();
		if($is_filter){
	?>
	      <tr>
	        <?php foreach($header as $rows){
	        	if($rows['field']){
	        		$rows['name'] = $rows['field'];
	        	}
	        	$edited = true;
	        	if($rows['filter']===false){
	        		$edited = $rows['filter'];
	        		$filter_arr[$rows['name']] = '&nbsp';
	        	}
	        	switch ($rows['type']) {
	        		case 'list':
	        			echo "<td style='width:$rows[width];'><div class='form-group'>".self::createSelect("list_search_filter[".$rows['name']."]",$rows['value'],$filter_arr[$rows['name']],$edited,'form-control',"style='max-width:$rows[width];'")."</div></td>";
	        			break;

	        		case 'date':
	            		echo "<td style='position:relative;width:$rows[width];'><div class='form-group'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
	        			break;

	        		case 'datetime':
	            		echo "<td style='position:relative;width:$rows[width];'><div class='form-group'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datetimepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
	        			break;

	        		case 'number':
	            		echo "<td style='position:relative;width:$rows[width];'><div class='form-group'>".self::createTextNumber("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control',"style='max-width:$rows[width];'")."</div></td>";
	        			break;

	        		default:
	            		echo "<td style='width:$rows[width];'><div class='form-group'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
	        			break;
	        	}
	        }
	        ?>
	        <td style='text-align:left; width:150px'>
	        <button type="submit" class='btn btn-primary btn-xs'>
			<span class="glyphicon glyphicon-search"></span>
	        Filter
	        </button>
	        <?=self::getButton('reset',null, $add, "btn-xs")?>
	        </td>
	      </tr>
	      <?php }?>
	      <tr>
	        <input type='hidden' name='list_sort' id='list_sort'>
	        <input type='hidden' name='list_order' id='list_order'>
	        <?php foreach($header as $rows){
	        	if($rows['type']=='list' or $rows['type']=='implodelist'){
		        	   echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
		        }else{
	        		if($rows['type']=='number'){
		        	   $align = "text-align:right;";
		        	}

		            if($list_sort==$rows['name']){
		                if(trim($list_order)=='asc'){
		                    $order = 'desc';
		                }else{
		                    $order = 'asc';
		                }
		               echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting_".$order."' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</th>";
		            }else{
		        	   echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</th>";
		            }
		        }
	        }
	        ?>
	        <th></th>
	      </tr>
	    <script>
		    $(function(){
		        $("#main_form").submit(function(){
		            if($("#act").val()==''){
		                goSearch();
		            }
		        });
		    });

		    function goSort(name, order){
		        $("#list_sort").val(name);
		        $("#list_order").val(order);
		        $("#act").val('list_sort');
		        $("#main_form").submit();
		    }

		    function goSearch(){
		        $("#act").val('list_search');
		        $("#main_form").submit();
		    }
	    </script>
    <?php
	}

	public static function showHeaderFix($headerrows, $filter_arr, $list_sort, $list_order, &$header){
		$ci = get_instance();
		if(!$headerrows['rows']){
			$headerrows['rows'] = array($headerrows);
		}
	      ?>
        <?php
        foreach($headerrows['rows'] as $k=>$head){
        	echo "<tr>";
        	if($k==0){
        		echo "<th style='vertical-align:middle' rowspan='".count($headerrows['rows'])."'>#</th>";
        	}
	        foreach($head as $rows){

	        	$add = $rows['add'];

	        	if($rows['align'])
	        	   $align = "text-align:{$rows['align']};";

	        	if($rows['width'])
	        	   $width = "width:{$rows['width']};";

	        	if($rows['type']!=='head')
	        		$header[] = $rows;

	        	$rowspan = '';
	        	if($rows['rowspan'])
	        		$rowspan = "rowspan='{$rows['rowspan']}'";

	        	$colspan = '';
	        	if($rows['colspan'])
	        		$colspan = "colspan='{$rows['colspan']}'";

	        	if($rows['type']=='list' or $rows['type']=='head' or $rows['type']=='implodelist')
	        	   echo "<th $add $colspan $rowspan style='vertical-align: middle;$width $align'>$rows[label]</th>";
		        else{

		        	$align = $row['align'];

	        		if($rows['type']=='number')
		        	   $align = "text-align:right;";

		            if($list_sort==$rows['name']){
		                if(trim($list_order)=='asc')
		                    $order = 'desc';
		                else
		                    $order = 'asc';

		               echo "<th $add $colspan $rowspan style='vertical-align: middle;$width $align cursor:pointer;' class='sorting_".$order."' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</th>";
		            }else
		        	   echo "<th $add $colspan $rowspan style='vertical-align: middle;$width $align cursor:pointer;' class='sorting' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</th>";
		        }
	        }
	        echo "</tr>";
	    }
        ?>

	      <tr class="filter-table">
	        <td></td>
	        <?php foreach($header as $rows){
	        	if($rows['field']){
	        		$rows['name'] = str_replace(".", "_____", $rows['field']);
	        	}
	        	$edited = true;
	        	if($rows['filter']===false){
	        		$edited = $rows['filter'];
	        		$filter_arr[$rows['name']] = '&nbsp';
	        	}
	        	switch ($rows['type']) {
	        		case 'list':
	        			echo "<td style='width:$rows[width];'>".self::createSelect("list_search_filter[".$rows['name']."]",$rows['value'],$filter_arr[$rows['name']],$edited,'form-control',"style='width:$rows[width];'")."</td>";
	        			break;

	        		case 'date':
	            		echo "<td style='position:relative;width:$rows[width];'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</td>";
	        			break;

	        		case 'datetime':
	            		echo "<td style='position:relative;width:$rows[width];'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datetimepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</td>";
	        			break;

	        		case 'implodelist':
	            		echo "<td style='position:relative;width:$rows[width];'></td>";
	        			break;

	        		case 'number':
	            		echo "<td style='position:relative;width:$rows[width];'>".self::createTextNumber("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control',"style='max-width:$rows[width];'")."</td>";
	        			break;

	        		default:
	            		echo "<td style='width:$rows[width];'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</td>";
	        			break;
	        	}
	        }
	        ?>
	        </tr>

        <input type='hidden' name='list_sort' id='list_sort'>
        <input type='hidden' name='list_order' id='list_order'>
	    <script>
		    $(function(){
		        $("#main_form").submit(function(){
		            if($("#act").val()==''){
		                goSearch();
		            }
		        });
		    });

		    function goSort(name, order){
		        $("#list_sort").val(name);
		        $("#list_order").val(order);
		        $("#act").val('list_sort');
		        $("#main_form").submit();
		    }

		    function goSearch(){
		        $("#act").val('list_search');
		        $("#main_form").submit();
		    }
	    </script>
    <?php
	}


	public static function showHeaderFront($header, $filter_arr, $list_sort, $list_order){
	?>
	      <tr>
	        <td></td>
	        <?php foreach($header as $rows){
	        	switch ($rows['type']) {
	        		case 'list':
	        			echo "<td>".self::createSelect("list_search[".$rows['name']."]",$rows['value'],$filter_arr[$rows['name']],true,'text_input hint','style="width:100%;padding: 6px 0px 6px 10px;" onchange="goSearch()"')."</td>";
	        			break;

	        		default:
	            		echo "<td>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',true,'text_input hint','style="width:100%;padding: 6px 0px 6px 10px;" placeholder="Search '.$rows['label'].'..."')."</td>";
	        			break;
	        	}
	        }
	        ?>
	      </tr>
	      <tr>
	        <th style="width:10px">#</th>
	        <input type='hidden' name='list_sort' id='list_sort'>
	        <input type='hidden' name='list_order' id='list_order'>
	        <?php foreach($header as $rows){
	            if($list_sort==$rows['name']){
	                if(trim($list_order)=='asc'){
	                    $order = 'desc';
	                }else{
	                    $order = 'asc';
	                }
	               echo "<th style='width:$rows[width]'><a href='#' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</a></th>";
	            }else{
	        	   echo "<th style='width:$rows[width]'><a href='#' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</a></th>";
	            }
	        }
	        ?>
	      </tr>
	    <script>
		    jQuery(function(){
		        jQuery("#main_form").submit(function(){
		            if(jQuery("#act").val()==''){
		                goSearch();
		            }
		        });
		    });

		    function goSort(name, order){
		        jQuery("#list_sort").val(name);
		        jQuery("#list_order").val(order);
		        jQuery("#act").val('list_sort');
		        jQuery("#main_form").submit();
		    }

		    function goSearch(){
		        jQuery("#act").val('list_search');
		        jQuery("#main_form").submit();
		    }
	    </script>
    <?php
	}

	public static function showButtonMode($mode, $key=null, $edited=false, $add='', $class='', $access_role=null, $page_escape=null) {

		$ci = get_instance();

		$str = '';
		if(($ci->addbuttons) && $mode!='save'){
			foreach ($ci->addbuttons as $k => $value) {
				$str .= self::getButton($value,$key, $add, $class, false, false, $access_role, $page_escape);
			}
		}

		if(($ci->buttons) && $mode!='save'){
			foreach ($ci->buttons as $k => $value) {
				$str .= self::getButton($value,$key, $add, $class, false, false, $access_role, $page_escape);
			}
			return $str;
		}

		if(strstr($mode,"|")!==false){
			$modearr = explode("|", $mode);

			if(($modearr)){
				$str = "";
				foreach ($modearr as $v) {
					$str .= self::getButton($v, $key, $add, $class, false, false, $access_role, $page_escape);
				}
				return $str;
			}
		}

		if ($mode === 'lst' || $mode === 'index' || $mode === 'daftar') {
			$str .= self::getButton('add',null, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if($mode == 'edit_detail'){

			if($edited)
				$str .= "";
			else
				$str .= self::getButton('edit', $key, $add, $class, false, false, $access_role, $page_escape);

			return $str;
		}

		if ($mode === 'oneedit'){

			$str .= self::getButton('detail', $key, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'onedetail'){

			$str .= self::getButton('edit', $key, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'edit') {
			//$str .= self::getButton('save');
			//$str .= self::getButton('batal', $key);

			$str .= self::getButton('lst',null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('add',null, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'add') {
			//$str .= self::getButton('save');
			$str .= self::getButton('lst',null, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'detail') {
			$str .= self::getButton('lst',null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('add',null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('edit', $key, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('delete', $key, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'save' && $edited) {
			$str .= self::getButton('save',null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('batal', $key, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'save_back' && $edited) {
			$str .= self::getButton('save',null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('lst', $key, $add, $class, 'Back', false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'save_detail' && $edited) {
			$str .= self::getButton('save',null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('detail', $key, $add, $class, 'Detail', false, $access_role, $page_escape);
			return $str;
		}

		if($mode == 'blank'){
			return $str;
		}
	}

	public static function showButtonModeRisiko($mode, $key=null, $edited=false, $add='', $class='', $access_role=null, $page_escape=null) {
		if(!$access_role)
			return false;

		$ci = get_instance();

		$str = '';
		if(($ci->addbuttons)){
			foreach ($ci->addbuttons as $k => $value) {
				$str .= self::getButton($value,$key, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			}
		}

		if(($ci->buttons)){
			foreach ($ci->buttons as $k => $value) {
				$str .= self::getButton($value,$key, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			}
			return $str;
		}

		if ($mode === 'lst' || $mode === 'index' || $mode === 'daftar') {
			$str .= self::getButton('add',null, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'oneedit'){

			$str .= self::getButton('detail', $key, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'onedetail'){

			$str .= self::getButton('edit', $key, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'edit') {
			//$str .= self::getButton('save');
			//$str .= self::getButton('batal', $key);

			//$str .= self::getButton('lst',null, $add, $class,'List  Risiko', 'goListRisiko()', $access_role, $page_escape);
			//$str .= self::getButton('add',null, $add, $class,'Add  Risiko','goAddRisiko()', $access_role, $page_escape);
			//return $str;
			return;
		}

		if ($mode === 'add') {
			//$str .= self::getButton('save');
			//$str .= self::getButton('lst',null, $add, $class,'List  Risiko', 'goListRisiko()', $access_role, $page_escape);
			//return $str;
			return;
		}

		if ($mode === 'detail') {
			//$str .= self::getButton('lst',null, $add, $class,'List  Risiko', 'goListRisiko()', $access_role, $page_escape);
			//$str .= self::getButton('add',null, $add, $class,'Add  Risiko','goAddRisiko()', $access_role, $page_escape);
			$str .= self::getButton('edit', $key, $add, $class, 'Edit  Risiko', 'goEditRisiko()', $access_role, $page_escape);
			$str .= self::getButton('delete', $key, $add, $class, 'Delete  Risiko', 'goDeleteRisiko()', $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'save' && $edited) {
			$str .= self::getButton('save',null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('batal', $key, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if($mode == 'blank'){
			return $str;
		}
	}

	public static function Button($array=array()){

		$default = array(
			"key"=>null,
			"add"=>'',
			"class"=>'btn-sm',
			"label"=>false,
			"action"=>false,
			"access_role"=>false,
			"page_escape"=>false,
		);
		foreach ($default as $key => $value) {
			if($array[$key]===null)
				$array[$key] = $value;
		}

		return self::getButton($array['id'], $array['key'], $array['add'], $array['class'], $array['label'], $array['action'], $array['access_role'], $array['page_escape']);
	}

	public static function getButton($id, $key=null, $add='', $class='btn-lg', $label=false, $action=false, $access_role=null, $page_escape=null) {

		$ci = get_instance();

		if(!$page_escape)
			$page_escape = array_values($ci->page_escape);

		if(!$access_role)
			$access_role = $ci->access_role;

		$tempid = $id;

		if($id=='detail')
			$tempid = 'index';

		if(
			$ci->private == true
			&&
			!$access_role[$id]
			&&
			!in_array($ci->page_ctrl, $page_escape)
			&&
			!$ci->is_super_admin
			&&
			!in_array($id, $ci->addbuttons)
		){
			return false;
		}

		if($ci->data['add_param']){
			$add_param = '/'.$ci->data['add_param'];
		}


		if ($id === 'add') {
			return ' <button type="button" '.$add.' class="btn waves-effect '.$class.' btn-primary" onclick="'.($action?$action:'goAdd()').'" ><span class="glyphicon glyphicon-plus"></span> '.($label?$label:'Add New').'</button> '.(!$action?'
			<script>
		    function goAdd(){
		        window.location = "'.base_url($ci->page_ctrl."/add".$add_param).'";
		    }
		    </script>':'');
		}

		if ($id === 'import') {
			return '<button type="button" '.$add.' class="btn waves-effect '.$class.' btn-primary" onclick="'.($action?$action:'goImport()').'" ><span class="glyphicon glyphicon-import"></span> '.($label?$label:'Import').'</button>'.(!$action?'
			<script>
		    function goImport(){
		        window.location = "'.base_url($ci->page_ctrl."/import".$add_param).'";
		    }
		    </script>':'');
		}

		if ($id === 'edit' && $key) {
			return ' <button type="button" '.$add.' class="btn waves-effect '.$class.' btn-warning" onclick="'.($action?$action:'goEdit(\''.$key.'\')').'" ><span class="glyphicon glyphicon-edit"></span> '.(($label!==false)?$label:'Edit').'</button> '.(!$action?'
			<script>
		    function goEdit(id){
		        window.location = "'.base_url($ci->page_ctrl."/edit".$add_param).'/"+id;
		    }
		    </script>':'');
		}

		if ($id === 'detail' && $key) {
			return '<button type="button" '.$add.' class="btn waves-effect '.$class.' btn-warning" onclick="'.($action?$action:'goDetail(\''.$key.'\')').'" ><span class="glyphicon glyphicon-eye-open"></span> '.($label?$label:'Detail').'</button> '.(!$action?'
			<script>
		    function goDetail(id){
		        window.location = "'.base_url($ci->page_ctrl."/detail".$add_param).'/"+id;
		    }
		    </script>':'');
		}

		if ($id === 'delete' && $key) {
			return '<button type="button" '.$add.' class="btn waves-effect '.$class.' btn-danger" onclick="'.($action?$action:'goDelete(\''.$key.'\')').'" ><span class="glyphicon glyphicon-remove"></span> '.($label!==false?$label:'Delete').'</button> '.(!$action?'
			<script>
		    function goDelete(id){
		        if(confirm("Apakah Anda yakin akan menghapus ?")){
		            window.location = "'.base_url($ci->page_ctrl."/delete".$add_param).'/"+id;
		        }
		    }
		    </script>':'');
		}

		if ($id === 'lst' || $id === 'index') {
			return '<button type="button" '.$add.' class="btn waves-effect '.$class.' btn-success" onclick="'.($action?$action:'goList()').'" ><span class="glyphicon glyphicon-arrow-left"></span> '.($label?$label:'Back').'</button>  '.(!$action?'
			<script>
			function goList(){
			window.location = "'.base_url($ci->page_ctrl."/index".$add_param).'";
			}
			</script>':'');
		}

		if ($id === 'save') {
			return '<button type="submit" class="btn-save btn '.$class.' btn-success" onclick="'.($action?$action:'goSave()').'" ><span class="glyphicon glyphicon-floppy-save"></span> '.($label?$label:'Save').'</button>'.(!$action?'
			<script>
			function goSave(){
				$(".btn-save").attr("disabled","disabled");
		      	$("#act").val(\'save\');
				$("#main_form").submit();
			}
			</script>':'');
		}

		if ($id === 'batal') {
			return '<button type="submit" class="btn waves-effect '.$class.' btn-default" onclick="'.($action?$action:'goBatal(\''.$key.'\')').'" ><span class="glyphicon glyphicon-repeat"></span> '.($label?$label:'Cancel').'</button> '.(!$action?'
			<script>
			function goBatal(){
				$("#act").val(\'reset\');
				$("#main_form").submit();
			}
			</script>':'');
		}

		if ($id === 'print') {
			return '<button type="button" class="btn waves-effect '.$class.' btn-primary" onclick="'.($action?$action:'goPrint(\''.$key.'\')').'" ><span class="glyphicon glyphicon-print"></span> '.($label?$label:'Print').'</button> '.(!$action?'
			<script>
			function goPrint(){
		        $("#act").val("list_search");
				window.open("'.base_url($ci->page_ctrl."/go_print".$add_param).'/?"+$("#main_form").serialize(),"_blank");
			}
			</script>':'');
		}

		if ($id === 'expportexcel') {
			return '<script src="'.base_url().'assets/js/excellentexport.min.js"></script>
			&nbsp;<a download="export-excel.xls" class="btn waves-effect btn-sm btn-primary" href="#" onclick="return ExcellentExport.excel(this, \'table-export\', \'Export Excel\',\'filter-table\');"><i class="fa fa-file-excel-o"></i> Excel</a>&nbsp;';
		}

		if ($id === 'reset') {
			return '<button type="button" '.$add.' class="btn waves-effect '.$class.' btn-default" onclick="'.($action?$action:'goReset()').'" ><span class="glyphicon glyphicon-refresh"></span> '.($label?$label:'Reset').'</button>  '.(!$action?'
			<script>
			function goReset(){
				$("#act").val(\'list_reset\');
				$("#main_form").submit();
			}
			</script>':'');
		}

		if ($id === 'applyfilter') {
			return '<button type="button" '.$add.' class="btn waves-effect '.$class.' btn-warning" onclick="'.($action?$action:'goSearch()').'" ><span class="glyphicon glyphicon-search"></span>'.($label?$label:'Terapkan Filter').'</button>  '.(!$action?'
			<script>
		    function goSearch(){
		        jQuery("#act").val("list_search");
		        jQuery("#main_form").submit();
		    }
			</script>':'');
		}

		if ($id === 'filter') {
			return '<button type="button" '.$add.' class="btn waves-effect '.$class.' btn-warning" onclick="'.($action?$action:'goSearch()').'" ><span class="glyphicon glyphicon-search"></span> '.($label?$label:'Filter').'</button>  '.(!$action?'
			<script>
		    function goSearch(){
		        jQuery("#act").val("list_filter");
		        jQuery("#main_form").submit();
		    }
			</script>':'');
		}

	}

    function token_page(){
    	$ci = get_instance();
		$token_page = substr(md5(microtime()),rand(0,26),5);
		$ci->session->SetPage('_token',$token_page);
		return $token_page;
    }

    public static function createForm($rows=array()){

		if($rows['field']){
		$rows['name'] = $rows['field'];
		}
		$edited = true;

		if(!$rows['width'])
			$rows['width'] = "400px";

		switch ($rows['type']) {
		case 'list':
		  $form = self::createSelect("list_search_filter[".$rows['name']."]",$rows['value'],null,$edited,'form-control',"style='max-width:$rows[width];'");
		  break;

		case 'date':
		  $form = self::createTextBox("list_search[".$rows['name']."]",null,'','',$edited,'form-control datepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'");
		  break;

		case 'datetime':
		  $form = self::createTextBox("list_search[".$rows['name']."]",null,'','',$edited,'form-control datetimepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'");
		  break;

		case 'number':
		  $form = self::createTextNumber("list_search[".$rows['name']."]",null,'','',$edited,'form-control',"style='max-width:$rows[width];'");
		  break;

		default:
		  $form = self::createTextBox("list_search[".$rows['name']."]",null,'','',$edited,'form-control','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'");
		  break;
		}

		return $form;
    }
    public static function createStatusRisiko($status_risiko, $edited=false){
		$ci = &get_instance();
		$row = $ci->data['rowheader1'];
		$scorecardarr = $ci->data['scorecardarr'];

		$riskapertite = $ci->data['riskapertite'];
		$riskmatrixtingkat = $ci->data['riskmatrixtingkat'];

		$tingkat = $riskmatrixtingkat[$row['residual_kemungkinan_evaluasi']][$row['residual_dampak_evaluasi']];

		$is_close = (bool)($tingkat<$riskapertite);

		$edited = (bool)($edited && $ci->access_role['edit']);

		switch ($status_risiko) {
			case '0':
				$from = "Status Risiko <span class='label label-default'>CLOSED</span>";
				break;
			case '2':
				$from = "Status Risiko <span class='label label-warning'>BERLANJUT</span>";
				break;

			default:

    		if($row['id_risiko_sebelum']){
				$from = "Status Risiko <span class='label label-success'>BERLANJUT</span>";
			}else{
				$from = "Status Risiko <span class='label label-success'>OPEN</span>";
			}
				break;
		}

		if($ci->Access('close','panelbackend/risk_risiko') && $edited && $row['id_status_pengajuan']=='5'){

			if($status_risiko!=='0' && $ci->Access('view_all_direktorat','panelbackend/risk_risiko')){
				$from .= "<br/><br/><table width='100%'>";
				if($is_close){
					$from .= "<tr><td>".UI::createFormGroup(UI::createTextBox('tgl_close',null,'','',true,'form-control datepickerstart'), $rules["tgl_close"], "tgl_close", "Tgl. Close", true)."</td></tr>";
					$from .= "<tr><td><a data-toggle='modal' data-target='#closemodal' class='btn btn-danger' onclick=\"$('#status_risiko').val(0)\"><span class=\"glyphicon glyphicon glyphicon-floppy-saved\"></span> CLOSE</a></td></tr>";

					$from .= "<tr><td ><hr/></td></tr>";
				}
				$from .= "<tr><td  >".UI::createFormGroup(UI::createTextBox('tgl_risiko',$ci->post['tgl_risiko'],'','',true,'form-control datepicker',"onchange='goSubmit(\"set_tgl_risiko\")'"), $rules["tgl_risiko"], "tgl_risiko", "Tgl. Risiko", true)."</td></tr>";
				$from .= "<tr><td  >".UI::createFormGroup(UI::createSelect('id_scorecard',$scorecardarr,$row['id_scorecard'],true), $rules["id_scorecard"], "id_scorecard", "Scorecard", true)."</td></tr>";
				$from .= "<tr><td  ><a data-toggle='modal' data-target='#closemodal' class='btn btn-success'' onclick=\"$('#status_risiko').val(2)\"><span class=\"glyphicon glyphicon-floppy-save\"></span> BERLANJUT</a> </td></tr></table>";


				$from .= '<div class="modal fade" id="closemodal" tabindex="-1" role="dialog">
		                <div class="modal-dialog" role="document">
		                    <div class="modal-content">
		                        <div class="modal-header">
		                            <h4 class="modal-title" id="defaultModalLabel">Keterangan<span style="color:red">*</span></h4>
		                        </div>
		                        <div class="modal-body"><div class="form-group" style="margin:0px">';
		        $from .= UI::createTextArea('status_keterangan',null,'','',true,$class='form-control status_keterangan'," placeholder='ketik disini untuk menambah keterangan'");
		        $from .= "<input type='hidden' name='status_risiko' id='status_risiko'/>";
		        $from .='</div>
		                        </div>
		                        <div class="modal-footer">
		                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
		                            <button type="button" class="btn btn-link waves-effect" onclick="goSubmitRequired(\'status_risiko\',\'.status_keterangan\')">SEND</button>
		                        </div>
		                    </div>
		                </div>
		            </div>';
			}
		}

    	$from .="
    	<div style='margin-top:7px'>";

    	if($row['id_risiko_sebelum']){
        	$from .="
        	<a href='".site_url("panelbackend/risk_risiko/log_history/$row[id_risiko]")."' target='_blank'>
        	ARSIP RISIKO
        	</a>";
        }

        if($row['status_keterangan']){
        	$from .="
        	&nbsp;&nbsp;|&nbsp;&nbsp;
        	<a href='javascript:void(0);' onclick='$(\"#keterangan_status\").toggle(100)'>
        	KETERANGAN
        	</a>
        	<div id='keterangan_status' style='display:none; padding-top:0px;'>";
	        $from .= $row['status_keterangan'];
	        $from .="</div>";
	    }
        $from .="
        </div>";

		return $from;
    }

    #nameid untuk nama halaman (sesuaikan dengan id_pk halaman contoh id_scorecard jadi nameidnya scorecard)
    #value untuk value id_status_pengajuan
    #id untuk id dari halaman yang akan diajukan
    #history untuk history pengajuan2 sebelumnya
	public static function createStatusPengajuan($nameid,$value='',$id=null, $edited=false) {

		$ci = &get_instance();

		$edited = (bool)($edited && $ci->access_role['edit'] && $ci->data['rowheader1']['residual_target_dampak'] && $ci->data['rowheader1']['residual_target_kemungkinan']);

		$rows = $ci->data['task_'.$nameid];

		$page = "panelbackend/risk_".$nameid;


		if(!$id)
			$value = 1;

		$ta = "Status Pengajuan ".labelstatus($value);

		switch ($value){
			case '1':
			case '4':
			case '5':
			#posisi koordinator
			if($ci->Access('pengajuan',$page) && $edited && $value==1){
			$ta .= "<br/><br/><a class='btn btn-sm btn-warning waves-effect' data-toggle='modal' onclick='$(\"#id_status_pengajuan".$nameid."\").val(2)' data-target='#pengajuanmodal".$nameid."'>AJUKAN KE OWNER <span class='glyphicon glyphicon-chevron-right'></span></a>";
			}
			if($ci->Access('penerusan',$page) && $edited){
			$ta .= "<br/><br/><a class='btn btn-sm btn-success waves-effect' data-toggle='modal' onclick='$(\"#id_status_pengajuan".$nameid."\").val(3)' data-target='#pengajuanmodal".$nameid."'>TERUSKAN KE REVIEWER <span class='glyphicon glyphicon-forward'></span></a>";
			}

			break;
			case '2':
			#posisi owner
			if($ci->Access('penerusan',$page) && $edited){
			$ta .= "<br/><br/><a class='btn btn-sm btn-warning waves-effect' data-toggle='modal' onclick='$(\"#id_status_pengajuan".$nameid."\").val(4)' data-target='#pengajuanmodal".$nameid."'><span class='glyphicon glyphicon-chevron-left'></span>KEMBALIKAN</a> ";
			$ta .= " <a class='btn btn-sm btn-success waves-effect' data-toggle='modal' onclick='$(\"#id_status_pengajuan".$nameid."\").val(3)' data-target='#pengajuanmodal".$nameid."'>SETUJUI DAN TERUSKAN KE REVIEWER <span class='glyphicon glyphicon-forward'></span></a>";
			}

			break;

			case '3':
			if($ci->Access('persetujuan',$page) && $edited){
			$ta .= "<br/><br/><a class='btn btn-sm btn-warning waves-effect' data-toggle='modal' onclick='$(\"#id_status_pengajuan".$nameid."\").val(4)' data-target='#pengajuanmodal".$nameid."'><span class='glyphicon glyphicon-chevron-left'></span>KEMBALIKAN</a> ";
			$ta .= " <a class='btn btn-sm btn-primary waves-effect' data-toggle='modal' onclick='$(\"#id_status_pengajuan".$nameid."\").val(5)' data-target='#pengajuanmodal".$nameid."'> <span class='glyphicon glyphicon-ok'></span> DISETUJUI </a>";
			}
			break;
		}

		if(($ci->Access('pengajuan',$page) or $ci->Access('persetujuan',$page) or $ci->Access('penerusan',$page)) && $edited){
		$ta .= '<div class="modal fade" id="pengajuanmodal'.$nameid.'" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Keterangan<span style="color:red">*</span></h4>
                        </div>
                        <div class="modal-body"><div class="form-group" style="margin:0px">';
        $ta .= UI::createTextArea('keterangan['.$nameid.']',null,'','',true,$class='form-control keterangan'.$nameid," placeholder='ketik disini untuk menambah keterangan'");
        $ta .= "<input type='hidden' name='id_status_pengajuan[$nameid]' id='id_status_pengajuan".$nameid."'/>";
        $ta .= "<input type='hidden' name='id[$nameid]' value='$id'/>";
        $ta .='</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                            <button type="button" class="btn btn-link waves-effect" onclick="goSubmitRequired(\'task'.$nameid.'\',\'.keterangan'.$nameid.'\')">SEND</button>
                        </div>
                    </div>
                </div>
            </div>';
        }

        if($rows){
        	$status_arr = $ci->data['mtstatusarr'];
        	$ta .="<div style='margin-top: 7px;'>
        	<a href='javascript:void(0);' onclick='$(\"#kettask$nameid\").toggle(100)'>RIWAYAT PENGAJUAN</a><div id='kettask$nameid' style='display:none; padding-top:0px;'>";
	        foreach ($rows as $r) {
	        	$ta .= "<b>".ucwords(strtolower($r['nama_user']))." (".ucwords(strtolower($r['nama_group'])).")</b><br/><i>".$r['deskripsi']."</i> <span class='glyphicon glyphicon-arrow-right'></span> ".$status_arr[$r['id_status_pengajuan']]."<hr style='margin:5px 0px;'/>";
	        }
	        $ta .="</div></div>";
		}

		return $ta;
	}
    #nameid untuk nama halaman (sesuaikan dengan id_pk halaman contoh id_scorecard jadi nameidnya scorecard)
    #value untuk value id_status_pengajuan
    #id untuk id dari halaman yang akan diajukan
    #history untuk history pengajuan2 sebelumnya
	public static function createKonfirmasi($id, $rows=array(), $status_konfirmasi=null, $edited=false) {

		if(!$id)
			return;
		
		$nameid = 'mitigasi';
		$mtstatusarr = array('4'=>'Ditolak','5'=>'Disetujui');

		if(!$status_konfirmasi)
			$ta = '<span class="label label-warning">DALAM KONFIRMASI</span>';
		elseif($status_konfirmasi==1)
			$ta = '<span class="label label-success">DISETUJUI</span>';
		elseif($status_konfirmasi==2)
			$ta = '<span class="label label-danger">DITOLAK</span>';


		if(!$rows)
			return $ta."<br/><br/>";

		$ci = &get_instance();

		$edited = (bool)($edited && $ci->access_role['edit']);

		if($edited && !$status_konfirmasi){
		$ta .= "<br/><br/><a class='btn btn-sm btn-warning waves-effect' data-toggle='modal' onclick='$(\"#id_status_pengajuan".$nameid."\").val(4)' data-target='#pengajuanmodal".$nameid."'><span class='glyphicon glyphicon-chevron-left'></span> TOLAK </a> ";
		$ta .= " <a class='btn btn-sm btn-primary waves-effect' onclick='goSubmit(\"approve_mitigasi\")'> <span class='glyphicon glyphicon-ok'></span> SETUJUI </a>";

		$ta .= '<div class="modal fade" id="pengajuanmodal'.$nameid.'" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Keterangan</h4>
                        </div>
                        <div class="modal-body"><div class="form-group" style="margin:0px">';
        $ta .= UI::createTextArea('keterangan['.$nameid.']',null,'','',true,$class='form-control'," placeholder='ketik disini untuk menambah keterangan'");
        $ta .= "<input type='hidden' name='id_status_pengajuan[$nameid]' id='id_status_pengajuan".$nameid."'/>";
        $ta .= "<input type='hidden' name='id[$nameid]' value='$id'/>";
        $ta .='</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                            <button type="button" class="btn btn-link waves-effect" onclick="goSubmit(\'task'.$nameid.'\')">SEND</button>
                        </div>
                    </div>
                </div>
            </div>';
        }

        if($rows){
        	$ta .="<div style='margin-top: 7px;'>
        	<a href='javascript:void(0);' onclick='$(\"#kettask$nameid\").toggle(100)'>RIWAYAT PERSETUJUAN</a><div id='kettask$nameid' style='display:none; padding-top:0px;'>";
	        foreach ($rows as $r) {
	        	$ta .= "<b>".ucwords(strtolower($r['nama_user']))."</b><br/><i>".$r['deskripsi']."</i><hr style='margin:5px 0px;'/>";
	        }
	        $ta .="</div></div>";
		}

		return $ta;
	}

	public static function tingkatRisiko($idkemungkinan, $iddampak, $data, $edited, $is_lg = true){
		$nkemungkinan = (int)$data[$idkemungkinan];
		$ndampak = (int)$data[$iddampak];

		$ci = &get_instance();
		$mtriskmatrixarr = $ci->data['mtriskmatrixarr'];

		$matrixarr = array();
		foreach ($mtriskmatrixarr as $r) {
			if($is_lg)
				$matrixarr[$r['id_dampak']][$r['id_kemungkinan']] = "<h4><span class='label bg-$r[warna]' style='background-color:$r[warna]'>{$r['kode_kemungkinan']}{$r['kode_dampak']}</span></h4>";
			else
				$matrixarr[$r['id_dampak']][$r['id_kemungkinan']] = "<span class='label bg-$r[warna]' style='background-color:$r[warna]'>{$r['kode_kemungkinan']}{$r['kode_dampak']}</span>";

		}

		if($edited)
			$nameid = $idkemungkinan."span";
		else
			$nameid = "span";

		$tingkat = $matrixarr[$ndampak][$nkemungkinan];

		$str = '';

		if($tingkat)
			$str .= "<span id='$nameid'>$tingkat</span>";
		elseif($is_lg)
			$str .= "<span id='$nameid'></span>";
		else
			$str .= "<i><small>(kosong)</small></i>";


		if($edited){
			$str .= "<script>
			function $nameid(k,d){ ";
			foreach ($matrixarr as $k=>$rows) {
				foreach ($rows as $k1 => $v) {
					$str .= "
					if(k==$k1 && d==$k){
						return \"".$matrixarr[$k][$k1]."\";
					}";
				}
			}
			$str .="
			}

			$('#$idkemungkinan, #$iddampak').change(function(){
				var k = $('#$idkemungkinan').val();
				var d = $('#$iddampak').val();
				var nameid = $nameid(k,d);
				$('#$nameid').html(nameid);
			});
			</script>";
		}

		return $str;
	}

	public static function createInfo($id=null, $title=text, $text=null, $class="model-xs", $is_plain=false){

		$ret = '<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog">
                <div class="modal-dialog '.$class.'" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="'.$id.'Label" style="color: #333;">
                            '.$title.'
                            </h4>
                        </div>
                        <div class="modal-body" style="font-weight: 100;">
                        '.$text.'
						</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>';
        if($is_plain){
        	$ret .= '<a href="javascript:void()" data-toggle="modal" style="color:#fff" data-target="#'.$id.'"><span class="glyphicon glyphicon-info-sign"></span></a>';
        }else{
        	echo $ret;
        	$ret = '<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#'.$id.'"><span class="glyphicon glyphicon-info-sign"></span></button>';
        }


		return $ret;
	}	
}
