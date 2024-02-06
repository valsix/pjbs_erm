$(function(){
	var menubar = $("#menubar");
	var tabcontent = $('#tabcontent');
	$.fn.datebox.defaults.formatter = function(date){
		var y=date.getFullYear();
		var m=date.getMonth()+1;
		var d=date.getDate();
		return d+"-"+m+"-"+y;
	}
	$.fn.datebox.defaults.parser = function(s){
		if (!s) return new Date();
		var ss = s.split('-');
		var d = parseInt(ss[0],10);
		var m = parseInt(ss[1],10);
		var y = parseInt(ss[2],10);
		if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
			return new Date(y,m-1,d);
		} else {
			return new Date();
		}
	}
	tabcontent.tabs('add',{
		title:"DASHBOARD",
		href:"backend/dashboard/index/DASHBOARD/icon-graph",
		cache:true
	});
	menubar.tree({
		onBeforeExpand:function(node){
			// $(node.target).addClass("headermenubar");
			var node1 = menubar.tree('getRoots');
			var node2 = menubar.tree('getParent',node.target);
			for(var i=0; i<node1.length; i++){
				if(node2 == null || (node1[i].target != node2.target))
				{	
					menubar.tree('collapse',node1[i].target);
				}
			}
		},
		onSelect:function(node){
			$(".headermenubar").removeClass("headermenubar");
			menubar.tree('expand',node.target);
			var node1 = menubar.tree('getRoots');
			var node2 = menubar.tree('getParent',node.target);
			for(var i=0; i<node1.length; i++){
				if(node2 != null && node1[i].target != node2.target)
				{
					$(node2.target).addClass("headermenubar");
				}
			}
		},
		onBeforeCollapse:function(node){
			// $(node.target).removeClass("headermenubar");
		}
	});
	$("#menubar .tree-node").click(function(){
		var _this = $(this);
		var url = _this.attr('url');
		var icon = _this.attr('icon');
		var title = _this.children('.tree-title').text();
		if(url){
			$('.easyui-window, .easyui-dialog').window('destroy');
			var a = tabcontent.tabs('tabs');
			if(a.length > 0){
				var b = a.length;
				for(i=b;i>=0;i--){
					tabcontent.tabs('close',(i)-1);
				}
			}
			tabcontent.tabs('add',{
				title:title,
				href:url+"/"+title+"/"+icon,
				cache:true
			});
		}
	});
	$("a[url]").click(function(){
			var _this = $(this);
			var url = _this.attr("url");
			var icon = _this.attr("icon");
			var title = _this.text();
			if(_this.attr("title"))title = _this.attr("title");
			if(_this.attr("newtab")){
				$("#tabcontent").addtab({
					title:title,
					href:url+"/"+title+"/"+icon,
				});
			}else{
				$('.easyui-window, .easyui-dialog').window('destroy');
				tabcontent.tabs('close',0);
				tabcontent.tabs('add',{
					title:title,
					cache:true,
					href:url+"/"+title+"/"+icon,
				});
			}
			return false;
		}
	);
});
$.deletedata=function(param){
	if($.confirm("Want to delete data ?")){
		$.ajax({
			url:param.url,
			type:"post",
			dataType:"json",
			data:param.data,
			beforeSend:function(){
				$.messager.progress({
					title:'Please waiting',
					msg:'Loading data...'
				});
			},
			error:function(xml, status, error){
				$.messager.progress('close');
				$.messager.alert('Error',status,'error');
			},
			success:function(respon){
				$.messager.progress('close');
				if(respon.success)
				{
					param.success(respon);
					$.messager.show({  
						title:'Success',  
						msg:respon.success, 
					});
				}
				else
				{
					$.messager.alert('Error',respon.error,'error');
				}
			}
		});
	}
	return false;
}
$.getdata=function(param){
	$.ajax({
		url:param.url,
		type:"post",
		dataType:"json",
		data:param.data,
		beforeSend:function(){
			$.messager.progress({
				title:'Please waiting',
				msg:'Loading data...'
			});
		},
		error:function(xml, status, error){
			$.messager.progress('close');
			$.messager.alert('Error',status,'error');
		},
		success:function(respon){
			$.messager.progress('close');
			if(respon.error)
			{
				$.messager.alert('Error',respon.error,'error');
			}
			else
			{	
				param.success(respon);
			}
		}
	});
	return false;
}
$.fn.ajaxsubmit=function(cb){
	if($.confirm("Want to save data ?")){
		var _this = $(this);
		$.ajax({
			url:_this.attr("action"),
			type:_this.attr("method"),
			dataType:"json",
			data:_this.serialize(),
			beforeSend:function(){
				$.messager.progress({
					title:'Please waiting',
					msg:'Loading data...'
				});
			},
			error:function(xml, status, error){
				$.messager.progress('close');
				$.messager.alert('Error',status,'error');
			},
			success:function(respon){
				$.messager.progress('close');
				if(respon.success)
				{
					$.messager.show({  
						title:'Success',  
						msg:respon.success,  
					});
					if(cb!=null)
					{
						return cb(respon);
					}
				}
				else
				{
					$.messager.alert('Error',respon.error,'error');
				}
			}
		});
	}
	return false;
};
$.fn.iframesubmit=function(cb){
	if($.confirm("Want to save data ?")){
		var _this = $(this);
		$.messager.progress({
			title:'Please waiting',
			msg:'Loading data...'
		});
		_this.form("submit",{
			onLoadError:function(){
				$.messager.progress('close');
				$.messager.alert('Error',"Error",'error');
			},
			success:function(respon){
				console.log(respon);
				$.messager.progress('close');
				try{
					respon = $.parseJSON(respon);
					if(respon.success)
					{
						$.messager.show({  
							title:'Success',  
							msg:respon.success,  
						});
						if(cb!=null)
						{
							return cb(respon);
						}
					}
					else
					{
						$.messager.alert('Error',respon.error,'error');
					}
				}catch(e){
					$.messager.alert('Error',"Error",'error');
				}
			}
		});
	}
}

$.confirm = function(m){
	return confirm(m);
}

$.gridselected = function(grid){
	var data = grid.datagrid('getSelected');
	if(!data){
		$.messager.alert('Info','Silahkan memilih data terlebih dahulu','info');
		return false;
	}
	return data;
}

$.action=function(cb){
	if(!cb.confirm){cb.confirm = "Run this action;";}
	if($.confirm(cb.confirm)){
		$.ajax({
			url:cb.url,
			type:"post",
			dataType:"json",
			data:cb.data,
			beforeSend:function(){
				$.messager.progress({
					title:'Please waiting',
					msg:'Loading data...'
				});
			},
			error:function(xml, status, error){
				$.messager.progress('close');
				$.messager.alert('Error',status,'error');
			},
			success:function(respon){
				$.messager.progress('close');
				if(respon.success)
				{
					$.messager.show({  
						title:'Success',  
						msg:respon.success, 
					});
					if(cb.success)
					{
						return cb.success();
					}
				}
				else
				{
					$.messager.alert('Error',respon.error,'error');
				}
			}
		});
	}
	return false;
}
$.fn.addtab=function(options){
	var _this = $(this);
	var ops = $.extend(true,{title:"",href:undefined,closable:true,cache:true},options);
	if(ops.href)
	{
		if (_this.tabs('exists', ops.title)){
			_this.tabs('select', ops.title);
		} else {
			_this.tabs('add',ops);
		}
	}
}
$.fn.closetab=function(){
	var _this = $(this);
	var tab = _this.tabs('getSelected');
	if (tab){
		var index = _this.tabs('getTabIndex', tab);
		$('.easyui-window, .easyui-dialog').window('destroy');
		_this.tabs('close', index);
	}
}