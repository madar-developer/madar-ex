var base_url = "http://madarex.sa/";

function reqPrice(This){
  var done = '<i class="fa fa-check"></i>';
  var loading = '<img src="assets/img/loading.gif" width="80" />';

  $("#req-price").find('#resp_modal_msg').html('');
  $("#req-price").find('.loading').html(loading);
  $("#req-price").modal('show');

  $.post(base_url + 'Api/functions.php?action=reqPrice',$(This).serialize(), function (data) {
    $("#req-price").find('.loading').html(done);
    $("#req-price").find('#resp_modal_msg').html(data);
  });

}

function followPayload(This){
  var done = 'assets/status-icons/80.png';
  var loading = 'assets/img/loading.gif';
  $("#follow-num").modal('show');
  var payload_id = $(This).find('input[name="payload_id"]').val();
  $("#follow-num").find("#payload_id").html(payload_id);
  $("#follow-num").find('.status').find("img").attr('src',loading);
  $("#follow-num").find('.status').find(".resp").html('جارى الاستعلام عن الطلب');

  $.post(base_url + 'Api/functions.php?action=followPayload',$(This).serialize(), function (data) {
      $("#follow-num").find('.status').find("img").attr('src',done);
      $("#follow-num").find('.status').find(".resp").html(data);
  });
}

function subscribe(This){
  var done = '<i class="fa fa-check"></i>';
  var loading = '<img src="assets/img/loading.gif" width="80" />';

  $("#req-price").find('#resp_modal_msg').html('');
  $("#req-price").find('.loading').html(loading);
  $("#req-price").modal('show');

  $.post(base_url + 'Api/functions.php?action=subscribe',$(This).serialize(), function (data) {
    $("#req-price").find('.loading').html(done);
    $("#req-price").find('#resp_modal_msg').html(data);
  });
}



	function Delete(table, col, id,conf=1) {

    var loading = '<img src="/assets/img/loading.gif" width="30" />';
		if(conf==1)
		del = confirm('هل تريد حذف هذا البند نهائيا ؟');
		else
		del = 1;

		if (del)
		{
      var cols = $("#item_"+table+"_"+id).find('td').length;
      if(cols)
      $("#item_"+table+"_"+id).html('<td colspan="'+cols+'" align="center">'+loading+'</td>');
      else
      $("#item_"+table+"_"+id).html('<div align="center">'+loading+'</div>');


			//$('#item_'+table+'_'+id).html('<img src="' + public+'images/w8.gif" class="w8" width="20">');
			$.post(base_url + 'Api/functions.php?action=delete',{table:table,col:col,id:id}, function (data) {
				$('#item_'+table+'_'+id).remove();
			});
			return true;
		}
		else
				return false;
	}
