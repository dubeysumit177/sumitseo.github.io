"use strict";
$("document").ready(function(){
	$("#from_date").val('');
	$("#to_date").val('');

	$(document).on('click', '#search_btn', function(event) {
		event.preventDefault();

		var form_data = new FormData($("#rank_search_form")[0]);

		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var keyword = $("#keyword").val();
		if(keyword == "")
		{
			swal(global_lang_error, keyword_rank_lang_select_keyword,"error");
			return false;
		}
		if(from_date == "")
		{
			swal(global_lang_error, global_lang_select_from_date,"error");
			return false;
		}
		if(to_date == "")
		{
			swal(global_lang_error, global_lang_select_to_date,"error");
			return false;
		}


		$('#middle_column_content').html("");
		$("#search_btn").addClass('btn-progress');


		$("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
		$.ajax({
			type:'POST' ,
			url:base_url+"social_accounts/keyword_position_report_data",
			data: form_data,
			contentType: false,
			cache:false,
			processData: false,
			success:function(response){
				if (response == '')
					swal(global_lang_error, global_lang_something_went_wrong,"error");
				$("#search_btn").removeClass('btn-progress');
				
				$("#custom_spinner").html("");
				$("#middle_column_content").html(response);

			}
		});

	});


});
