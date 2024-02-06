<?php
	function CountDownArr(){
        $count_down_arr = array(
        	""=>"-semua status-",
        	"Review Admindal"=>"Review Admindal",
        	"Proses Pengadaan"=>"Proses Pengadaan",
        	"Proyek Berjalan"=>"Proyek Berjalan",
        	"Kontrak Terbayar"=>"Kontrak Terbayar",
        );
        return $count_down_arr;
	}

	function Toolbar($param=array()){
		$params = array(
			'toolbar'=>array('refresh'),
			'prefix'=>"on_"
		);
		foreach($param as $key=>$val)
		{
			$params[$key]=$val;
		}
		$prefix = $params['prefix'];
		$arrtoolbar = array(
			'add'=>		"<li><a href=\"#\" title=\"Add\" class=\"add\" onclick=\"{$prefix}add()\">Add</a></li>",
			'edit'=>	"<li><a href=\"#\" title=\"Edit\" class=\"edit\" onclick=\"{$prefix}edit()\">Edit</a></li>",
			'delete'=>	"<li><a href=\"#\" title=\"Delete\" class=\"delete\" onclick=\"{$prefix}delete()\">Delete</a></li>",
			'close'=>	"<li><a href=\"#\" title=\"Close\" class=\"close\" onclick=\"{$prefix}close()\">Close</a></li>",
			'refresh'=>	"<li><a href=\"#\" title=\"Refresh\" class=\"refresh\" onclick=\"{$prefix}refresh()\">Refresh</a></li>",
			'save'=>	"<li><a href=\"#\" title=\"Save\" class=\"save\" onclick=\"{$prefix}save()\">Save</a></li>",
			'save_new'=>	"<li><a href=\"#\" title=\"Save New\" class=\"save-new\" onclick=\"{$prefix}save_new()\">Save New</a></li>",
			'save_close'=>	"<li><a href=\"#\" title=\"Save Close\" class=\"save-close\" onclick=\"{$prefix}save_close()\">Save Close</a></li>",
			'cancel'=>	"<li><a href=\"#\" title=\"Cancel\" class=\"cancel\" onclick=\"{$prefix}cancel()\">Cancel</a></li>",
			'excel'=>	"<li><a href=\"#\" title=\"Excel\" class=\"excel\" onclick=\"{$prefix}excel()\">Excel</a></li>",
			'pdf'=>		"<li><a href=\"#\" title=\"PDF\" class=\"pdf\" onclick=\"{$prefix}pdf()\">PDF</a></li>",
			'print'=>	"<li><a href=\"#\" title=\"Print\" class=\"print\" onclick=\"{$prefix}print()\">Print</a></li>",
		);
		$arrfunction = array(
			'add'=>		"{$prefix}add()",
			'edit'=>	"{$prefix}edit()",
			'delete'=>	"{$prefix}delete()",
			'close'=>	"{$prefix}close()",
			'refresh'=>	"{$prefix}refresh()",
			'save'=>	"{$prefix}save()",
			'save_new'=>"{$prefix}save_new()",
			'save_close'=>"{$prefix}save_close()",
			'cancel'=>	"{$prefix}cancel()",
			'excel'=>	"{$prefix}excel()",
			'pdf'=>		"{$prefix}pdf()",
			'print'=>	"{$prefix}print()",
		);
		$js="";
		echo "<ul class=\"toolbar\">";
		foreach($params['toolbar'] as $key=>$val){
			$val = trim($val);
			if(!$arrtoolbar[$val]){
				echo "<li><a href=\"#\" title=\"".ucwords($val)."\" class=\"$val\" onclick=\"{$prefix}{$val}()\">".ucwords($val)."</a></li>";
				$js .="function {$prefix}{$val}(){alert(\"{$prefix}{$val}()\")} ";
			}
			else{
				echo $arrtoolbar[$val];
				$js .="function {$arrfunction[$val]}{alert(\"$arrfunction[$val]\")} ";
			}

		}
		echo "</ul>";
		echo "<script>";
		echo $js;
		echo "</script>";
	}

	function Button($param=array()){
		$params = array(
			'toolbar'=>array('refresh'),
			'prefix'=>"on_btn_"
		);
		foreach($param as $key=>$val)
		{
			$params[$key]=$val;
		}
		$prefix = $params['prefix'];
		$arrtoolbar = array(
			'add'=>		" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}add()\">Add</a> ",
			'edit'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}edit()\">Edit</a> ",
			'delete'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}delete()\">Delete</a> ",
			'close'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}close()\">Close</a> ",
			'refresh'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}refresh()\">Refresh</a> ",
			'save'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}save()\">Save</a> ",
			'save_new'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}save_new()\">Save New</a> ",
			'save_close'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}save_close()\">Save Close</a> ",
			'cancel'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}cancel()\">Cancel</a> ",
			'excel'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}excel()\">Excel</a> ",
			'pdf'=>		" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}pdf()\">PDF</a> ",
			'print'=>	" <a class=\"easyui-linkbutton\" iconCls=\"icon-ok\" onclick=\"{$prefix}print()\">Print</a> ",
		);
		$arrfunction = array(
			'add'=>		"{$prefix}add()",
			'edit'=>	"{$prefix}edit()",
			'delete'=>	"{$prefix}delete()",
			'close'=>	"{$prefix}close()",
			'refresh'=>	"{$prefix}refresh()",
			'save'=>	"{$prefix}save()",
			'save_new'=>"{$prefix}save_new()",
			'save_close'=>"{$prefix}save_close()",
			'cancel'=>	"{$prefix}cancel()",
			'excel'=>	"{$prefix}excel()",
			'pdf'=>		"{$prefix}pdf()",
			'print'=>	"{$prefix}print()",
		);
		$js="";
		foreach($params['toolbar'] as $key=>$val){
			$val = trim($val);
			if(!$arrtoolbar[$val])
				continue;

			echo $arrtoolbar[$val];
			$js .="function {$arrfunction[$val]}{alert(\"$arrfunction[$val]\")} ";
		}
		echo "<script>";
		echo $js;
		echo "</script>";
	}