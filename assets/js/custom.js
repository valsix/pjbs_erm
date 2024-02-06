function CalDay(start, end){
	var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
	var firstDate = new Date(start);
	var secondDate = new Date(end);

	var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
	
	return diffDays;
}

/*$(window).scroll(function() {
  sessionStorage.scrollTop = $(window).scrollTop();
});*/

$(document).ready(function() {
  if (sessionStorage.scrollTop != "undefined") {
    $(window).scrollTop(sessionStorage.scrollTop);
  }

  sessionStorage.scrollTop = 0;


/*  $("input[type='number']").keyup(function(){
  		var v = addCommas(validDigits($(this).val()));
  		$(this).val(v);
  });*/
});

function goSubmitRequired(act, id_required, id_form){
  if(id_form==undefined)
    id_form = '#main_form';
  
  if(id_required!=undefined){
    var cek = $(id_required).val();
    if(cek.trim()==''){
      alert("Data harus isi lengkap");
      return false;
    }
  }

  $(id_form+" #act").val(act);
  sessionStorage.scrollTop = $(window).scrollTop();
  $(id_form).submit();
}

function goSubmit(act,id_form){
  if(id_form==undefined)
    id_form = '#main_form';
  
  $(id_form+" #act").val(act);
  sessionStorage.scrollTop = $(window).scrollTop();
  $(id_form).submit();
  
    return false;
}

function  goSubmitX(act,id_form, respon) {
  if(id_form==undefined)
    id_form = '#main_form';

  var datax = $(id_form).serialize();
  datax.act = act;

  $.ajax({
    type:"post",
    url:current_url(),
    data:datax,
    success:function(ret){
      respon(ret);
    }
  });
}

function goSubmitConfirm(act,id_form){
  if(id_form==undefined)
    id_form = '#main_form';
  
  if(confirm("Apakah Anda akan melanjutkan ?")){
    $(id_form+" #act").val(act);
    sessionStorage.scrollTop = $(window).scrollTop();
    $(id_form).submit();
  }


    return false;
}

function goSubmitValue(act,key,id_form){
  if(id_form==undefined)
    id_form = '#main_form';
  
  if(confirm("Apakah Anda akan melanjutkan ?")){
    sessionStorage.scrollTop = $(window).scrollTop();
    $(id_form+" #act").val(act);
    $(id_form+" #key").val(key);
    $(id_form).submit();
  }

    return false;
}

function goGo(go,act,id_form){
  if(id_form==undefined)
    id_form = '#main_form';

  if(act==undefined)
    act = 'save';

  $(id_form).attr("target","_blank");

  $("#go").val(go);

  goSubmit(act,id_form)

   $("#go").val('');

  $(id_form).removeAttr("target");
  //return false;
}

function addCommas(n){
    var rx=  /(\d+)(\d{3})/;
    return String(n).replace(/^\d+/, function(w){
        while(rx.test(w)){
            w= w.replace(rx, '$1,$2');
        }
        return w;
    });
}

function validDigits(n, dec){
    n= n.replace(/[^\d\.]+/g, '');
    var ax1= n.indexOf('.'), ax2= -1;
    if(ax1!= -1){
        ++ax1;
        ax2= n.indexOf('.', ax1);
        if(ax2> ax1) n= n.substring(0, ax2);
        if(typeof dec=== 'number') n= n.substring(0, ax1+dec);
    }
    return n;
}


var run_task = function (){ 
    $.ajax({
      url:site_url('panelbackend/ajax/notif'),
      success:function(d){
        try{
          $("#task_count").text(d.count);
          var task_data = '';
          for(i=0;i<d.content.length;i++){
            var d1 = d.content[i];
              task_data +="<li>"
                +"  <a href=\""+site_url(d1.url)+"\" class=\"waves-effect waves-block\">"
                +"      <div class=\"icon-circle bg-"+d1.bg+"\">"
                +"          <i class=\"material-icons\">"+d1.icon+"</i>"
                +"      </div>"
                +"      <div class=\"menu-info\">"
                +"          <p class=\"info\">"+d1.info+"</p>"
                +"          <p>"
                +"              <i class=\"material-icons\">access_time</i> "+d1.time
                +"              <i class=\"material-icons\">account_circle</i> "+d1.user
                +"          </p>"
                +"      </div>"
                +"  </a>"
              +"</li>";
          }
          $("#task_data").html(task_data);
        }catch(e){

        }
      },
      dataType:'json'
    });
  };

$(function(){
  setInterval(run_task, 3000);
});


$(function () {

    $('[rel="tooltip"]').tooltip(); 
    
      $(document).on('show.bs.modal', '.modal', function (event) {
          var zIndex = 1040 + (10 * $('.modal:visible').length);
          $(this).css('z-index', zIndex);
          setTimeout(function() {
              $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
          }, 0);
      });


});

