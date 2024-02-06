function ReplaceString(oldS,newS,fullS) 
{ 
  // Replaces oldS with newS in the string fullS   
  for (var i=0; i<fullS.length; i++) 
  {      
    if (fullS.substring(i,i+oldS.length) == oldS) 
    {         
      fullS = fullS.substring(0,i)+newS+fullS.substring(i+oldS.length,fullS.length)      
    }   
  }   
  return fullS
}

function FormatAngkaNumber(value)
{

    var a = value;
        var nilai = ReplaceString('.','',a);
        var nilai = ReplaceString(',','.',nilai);
        return nilai;    
}

function FormatCurrency(num) 
{
  num = num.toString().replace(/\$|\,/g,'');
  if(isNaN(num))
  num = "0";
  sign = (num == (num = Math.abs(num)));
  num = Math.floor(num*100+0.50000000001);
  cents = num%100;
  num = Math.floor(num/100).toString();
  if(cents<10)
  cents = "0" + cents;
  for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
  {
    num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
  }
  if(cents != "00")
    return (((sign)?'':'-') +  num + ',' + cents);
  else
    return (((sign)?'':'-') +  num);
}

function getidinfo(infoid, infoparams)
{
  infoid= infoid.replace(infoparams, "");
  infoid= String(infoid).split("]");
  indexid= infoid[0].replace("[", "");
  indexname= infoid[1].replace("[", "");

  var infodetil= {};
  infodetil.indexid= indexid;
  infodetil.indexname= indexname;
  return infodetil;
}
  
function defnum(vnum)
{
  if(typeof vnum == "undefined" || vnum == "")
  {
    vnum= "0";
  }
  return vnum;
}

function defnull(vnum)
{
  if(typeof vnum == "undefined" || vnum == "")
  {
    vnum= "";
  }
  return vnum;
}

function hapusparam(vid)
{
  $('[id^="iconhapus'+vid+'"]').click(function(e) {
    infoid= $(this).attr('id');
    infoid= infoid.replace("iconhapus"+vid, "");
    $("#status"+vid+infoid).val("hapus");
    $("#tr-"+vid+"-"+infoid).hide();
    // console.log(infoid);

    labeltotalparam(vid);
  });

  $(".datepicker").datetimepicker({format: "DD-MM-YYYY",useCurrent:false});
  $(".datetimepicker").datetimepicker({format: "DD-MM-YYYY HH:mm:ss",useCurrent:false});

  $(".select2, select.form-control").select2({
    placeholder: '-pilih-',
    allowClear: true
  });

  // tambahan format rupiah
  $(".rupiah").autoNumeric('init', {aSep: '.', aDec: ','});

}

function labeltotalparam(vid)
{
    labeltotal= 0;
    $('[id^="tr-'+vid+'-"]').each(function(index, value) {
      if ( $(this).css('display') == 'none'){}
      else
      {
        labeltotal= parseInt(labeltotal) + 1;
        $(".nourut-"+vid+"-"+index).val(labeltotal);
      }
      // console.log( index + ": " + value +";"+this.id);
    });
    $("#labeltotal"+vid).text(labeltotal);
}

callstatuschecked();
function callstatuschecked()
{
  $('input[id^="statuschecked"]').change(function() {
  infoid= $(this).attr('id');
  valinfoid= infoid.replace("statuschecked", "");
  valid= valinfoid.replace(/[^\d.]/g, '' );
  // console.log(infoid);
  if($(this).prop('checked'))
  {
    $(this).prop('checked', true);
    $(this).val("1");
    // $("#statusvalchecked"+valinfoid).val("1");

    //untuk cek fungsi changeo pada setiap view
    infovall= $(".jumlahusul"+valinfoid).val();
    $('.jumlahusul'+valinfoid).on('change keyup input', function() {
      if (typeof window.changeo === 'function')
      {
        changeo(valinfoid, infovall);
      }
    });

    if (valinfoid!='general'+valid) 
    {
      $(".jumlahusul"+valinfoid).prop("readonly", true);
      $(".jumlahusul"+valinfoid).attr('readonly','readonly');
      $(".jumlahusul"+valinfoid).val('1');

      //untuk cek fungsi changeo pada setiap view
      if (typeof window.changeo === 'function')
      {
        changeo(valinfoid, infovall);
      }
    }
    else
    {
      $(".jumlahusul"+valinfoid).prop("readonly", false);
      $(".jumlahusul"+valinfoid).removeAttr('readonly');
    }

    $(".kuantiti_baik"+valinfoid).prop("readonly", false);
    $(".kuantiti_baik"+valinfoid).removeAttr('readonly');
    $(".kuantiti_rusak"+valinfoid).prop("readonly", false);
    $(".kuantiti_rusak"+valinfoid).removeAttr('readonly');
    $(".kuantiti_hilang"+valinfoid).prop("readonly", false);
    $(".kuantiti_hilang"+valinfoid).removeAttr('readonly');
  }
  else
  {
    $(this).prop('checked', false);
    $(this).val("");
    // $("#statusvalchecked"+valinfoid).val("");
    $(".jumlahusul"+valinfoid).prop("readonly", true);
    $(".jumlahusul"+valinfoid).attr('readonly','readonly');
    $(".jumlahusul"+valinfoid).val('');

    $(".kuantiti_baik"+valinfoid).prop("readonly", true);
    $(".kuantiti_baik"+valinfoid).attr('readonly','readonly');
    $(".kuantiti_baik"+valinfoid).val('');
    $(".kuantiti_rusak"+valinfoid).prop("readonly", true);
    $(".kuantiti_rusak"+valinfoid).attr('readonly','readonly');
    $(".kuantiti_rusak"+valinfoid).val('');
    $(".kuantiti_hilang"+valinfoid).prop("readonly", true);
    $(".kuantiti_hilang"+valinfoid).attr('readonly','readonly');
    $(".kuantiti_hilang"+valinfoid).val('');
  }
  });

  $(".select2, select.form-control").select2({
    placeholder: '-pilih-',
    allowClear: true
  });
}

$('input[id^="reqtabcheckall"]').change(function() {
  infoid= $(this).attr('id');
  infoid= infoid.replace("reqtabcheckall", "")
  // console.log(infoid);

  if($(this).prop('checked'))
  {
    $(this).prop('checked', true);
    $('input[id^="statuschecked'+infoid+'"]').each(function(){
      infoiddetil= $(this).attr('id');
      valinfoiddetil= infoiddetil.replace("statuschecked", "");
      valid= valinfoiddetil.replace(/[^\d.]/g, '' );

      $(this).prop('checked', true);
      $(this).val("1");
      // $("#statusvalchecked"+valinfoiddetil).val("1");

      //untuk cek fungsi changeo pada setiap view
      infovall= $(".jumlahusul"+valinfoiddetil).val();
      $('.jumlahusul'+valinfoiddetil).on('change keyup input', function() {
        if (typeof window.changeo === 'function')
        {
         changeo(valinfoiddetil, infovall);
        }
      });

      if (valinfoiddetil!='general'+valid) 
      {
        $(".jumlahusul"+valinfoiddetil).prop("readonly", true);
        $(".jumlahusul"+valinfoiddetil).attr('readonly','readonly');
        $(".jumlahusul"+valinfoiddetil).val('1');

        //untuk cek fungsi changeo pada setiap view
        if (typeof window.changeo === 'function')
        {
          changeo(valinfoiddetil, infovall);
        }
      }
      else
      {
        $(".jumlahusul"+valinfoiddetil).prop("readonly", false);
        $(".jumlahusul"+valinfoiddetil).removeAttr('readonly');
      }

      $(".kuantiti_baik"+valinfoiddetil).prop("readonly", false);
      $(".kuantiti_baik"+valinfoiddetil).removeAttr('readonly');
      $(".kuantiti_rusak"+valinfoiddetil).prop("readonly", false);
      $(".kuantiti_rusak"+valinfoiddetil).removeAttr('readonly');
      $(".kuantiti_hilang"+valinfoiddetil).prop("readonly", false);
      $(".kuantiti_hilang"+valinfoiddetil).removeAttr('readonly');
    });
  }
  else
  {
    $(this).prop('checked', false);
    $('input[id^="statuschecked'+infoid+'"]').each(function(){
      infoiddetil= $(this).attr('id');
      valinfoiddetil= infoiddetil.replace("statuschecked", "")
      $(this).prop('checked', false);
      $(this).val("");
      // $("#statusvalchecked"+valinfoiddetil).val("");
      $(".jumlahusul"+valinfoiddetil).prop("readonly", true);
      $(".jumlahusul"+valinfoiddetil).attr('readonly','readonly');
      $(".jumlahusul"+valinfoiddetil).val('');

      $(".kuantiti_baik"+valinfoiddetil).prop("readonly", true);
      $(".kuantiti_baik"+valinfoiddetil).attr('readonly','readonly');
      $(".kuantiti_baik"+valinfoiddetil).val('');      
      $(".kuantiti_rusak"+valinfoiddetil).prop("readonly", true);
      $(".kuantiti_rusak"+valinfoiddetil).attr('readonly','readonly');
      $(".kuantiti_rusak"+valinfoiddetil).val('');      
      $(".kuantiti_hilang"+valinfoiddetil).prop("readonly", true);
      $(".kuantiti_hilang"+valinfoiddetil).attr('readonly','readonly');
      $(".kuantiti_hilang"+valinfoiddetil).val('');
    });
  }
});

$('[id^="reqdatatable"]').on("select2:select", function (e) {
  var data= e.params.data;
  infoid= $(this).attr('id');
  // console.log(infoid);

  if (typeof window.setvalidasicheck === 'function')
  {
    setvalidasicheck(infoid, data);
  }
});