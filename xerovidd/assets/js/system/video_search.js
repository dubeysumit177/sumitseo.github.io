"use strict";
setTimeout(function(){ 
	$("#mytable_filter").append(drop_menu); 
	$('#date_range').daterangepicker({
		ranges: {
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month'  : [moment().startOf('month'), moment().endOf('month')],
			'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate  : moment()
	}, function (start, end) {
		$('#date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
	});
}, 2000);
$("document").ready(function(){
	$(document).on('click', '#search_btn', function(event) {
		event.preventDefault();

		var form_data = new FormData($("#video_search_form_data")[0]);

		var keyword = $("#keyword").val();
		var channel_id = $("#channel_id").val();
		if(keyword == '' && channel_id=="")
		{
			swal(global_lang_error, channel_search_lang_enter_keyword, 'error');
			return false;
		}


		$('#middle_column_content').html("");
		$("#search_btn").addClass('btn-progress');


		$("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');

		$.ajax({
			type:'POST' ,
			url:base_url+"search_engine/youtube_video_search_action",
			data: form_data,
			contentType: false,
			cache:false,
			processData: false,
			success:function(response){

				$("#search_btn").removeClass('btn-progress');
				$("#custom_spinner").html("");
				$("#middle_column_content").html(response);

			}
		});

	});

});

$("document").ready(function(){
	var channel_id = $("#channel_id").val();
	if(channel_id !='')	$("#search_btn").click();
});
