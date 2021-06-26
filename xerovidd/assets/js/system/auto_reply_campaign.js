"use strict";
$(document).ready(function() {
	
	var perscroll;
	var perscroll1;
	var table = $("#mytable").DataTable({
	    serverSide: true,
	    processing:true,
	    bFilter: true,
	    order: [[ 2, "desc" ]],
	    pageLength: 10,
	    ajax: {
	        "url": base_url+'responder/auto_reply_campaign_data',
	        "type": 'POST'
	    },
	    language: 
	    {
	      url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
	    },
	    dom: '<"top"f>rt<"bottom"lip><"clear">',
	    columnDefs: [
	      {
	          targets: [1],
	          visible: false
	      },
	      {
	          targets: [1,3,4,5,6,7],
	          className: 'text-center'
	      },
	      {
	          targets: [0,3,7],
	          sortable: false
	      },
	      {
	      		targets: [3],
	      		render: function (data, type, row, meta) {

	      			return '<a href="https://www.youtube.com/watch?v='+ data + '" target="_BLANK">'+data+'</a>'
	      		}
	      }
	    ],
	    fnInitComplete:function(){ // when initialization is completed then apply scroll plugin
	    	if(areWeUsingScroll)
	    	{
	    		if (perscroll) perscroll.destroy();
	    		perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
	    	}
	    },
	    scrollX: 'auto',
	    fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
	    	if(areWeUsingScroll)
	    	{ 
	    		if (perscroll) perscroll.destroy();
	    		perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
	    	}
	    }
	});


	
	if (search_video != '')
		table.search(search_video).draw();


	$(document).on('click', '.show_error', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "auto_reply_campaign";
        var that = this;
        show_error_response(campaign_id,table_name,that);	          
	});

	$(document).on('click', '.show_error_report', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "auto_reply_campaign_report";
        var that = this;
        show_error_response(campaign_id,table_name,that);	          
	});

	$(document).on('click', '.show_comment_text', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "auto_reply_campaign_report";
        var that = this;
        show_error_response(campaign_id,table_name,that,'comment_text','info',auto_reply_campaign_comment_text);	          
	});

	$(document).on('click', '.show_reply_text', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "auto_reply_campaign_report";
        var that = this;
        show_error_response(campaign_id,table_name,that,'reply_to_be_given','info',auto_reply_campaign_reply_text);	          
	});


	var table1 = '';

	$(document).on('click', '.report_details', function(event) {
		event.preventDefault();

		var campaign_id = $(this).attr('campaign_id');
		$("#report_campagin_id").val(campaign_id);
		$("#report_details_modal").modal();
		
		if (table1 == '') {

			setTimeout(function() {

				table1 = $("#myReportTable").DataTable({
				    serverSide: true,
				    processing:true,
				    bFilter: true,
				    order: [[ 1, "desc" ]],
				    pageLength: 10,

				    ajax: {
				        "url": base_url+'responder/view_campaign_report',
				        "type": 'POST',
				        data: function ( d )
				        {
				            d.report_campagin_id = $('#report_campagin_id').val();
							
				        } 
				    },
				    language: 
				    {
				      url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
				    },
				    dom: '<"top"f>rt<"bottom"lip><"clear">',
				    columnDefs: [
				      {
				          targets: [3,4,5,6],
				          className: 'text-center'
				      },
				      {
				          targets: [0,4,6],
				          sortable: false
				      },
				      {
				          targets: [1],
				          visible: false
				      }
				      
				     
				    ],
				    fnInitComplete:function(){ // when initialization is completed then apply scroll plugin
				    	if(areWeUsingScroll)
				    	{
				    		if (perscroll1) perscroll1.destroy();
				    		perscroll1 = new PerfectScrollbar('#myReportTable_wrapper .dataTables_scrollBody');
				    	}

				    },
				    scrollX: 'auto',
				    fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
				    	if(areWeUsingScroll)
				    	{ 
				    		if (perscroll1) perscroll1.destroy();
				    		perscroll1 = new PerfectScrollbar('#myReportTable_wrapper .dataTables_scrollBody');
				    	}
				    }
				});
			}, 100);

			
		}
		else {
			setTimeout(function() {
				table1.draw();
			}, 100);
			
		}

		
	});

	$(document).on('click', '.edit_campaign', function(event) {
		event.preventDefault();

		$(this).removeClass('btn-outline-warning');
		$(this).addClass('btn-warning btn-progress');
		var that = $(this);

		var video_id = $(this).attr('video_id');
		var channel_id = $(this).attr('channel_id');

		$("#video_id").val(video_id);
		$("#channel_id").val(channel_id);
		$("#submit_type").val('edit');

		$(".set_auto_reply_info_block").html(auto_reply_campaign_waiting_gif);

		$("#set_auto_reply_modal").modal();

		$.ajax({
			url: base_url+"responder/auto_reply_info_for_edit",
			type: 'POST',
			data: {video_id: video_id},
			success: function(response) {
				$(".set_auto_reply_info_block").html(response);
				$("#create_campaign").html('<i class="fa fa-save"></i> '+global_lang_campaign_edit);

				$(that).removeClass('btn-warning btn-progress');
				$(that).addClass('btn-outline-warning');
			}
		});
		
	});

	$(document).on('change', 'input[type=radio][name=reply_type]', function(event) {
		event.preventDefault();

		var reply_type = $(this).val();

		if (reply_type == 'filter') {

			$(".generic_message_block").css('display', 'none');
			$(".filter_message_block").css('display', 'block');
		}
		else if (reply_type == 'generic') {

			$(".generic_message_block").css('display', 'block');
			$(".filter_message_block").css('display', 'none');
		}
	});

	$(document).on('click', '#add_more_filter_button', function(event) {
		event.preventDefault();

		var content_amount = parseInt($("#content_block").val(), 10);

		if (content_amount < 20) {

			$("#content_block").val(content_amount + 1);

			var current_block = $("#odd_or_even").val();
			var card_class = '';
			var next_block = '';

			if (current_block == 'odd') {
				card_class = 'card-primary';
				next_block = 'even';
			}
			else if (current_block == 'even') {
				card_class = 'card-info';
				next_block = 'odd';
			}
			
			var div_string = '<div class="card '+card_class+' single_card"><div class="card-header"><h4>'+auto_reply_campaign_filter_reply+'</h4><div class="card-header-action"><button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> '+global_lang_remove+'</button></div></div><div class="card-body"><div class="form-group"><label for="filter_words"> '+auto_reply_campaign_filter_word+' </label><input name="filter_words[]"  class="form-control filter_word_input" type="text"></div><div class="form-group"><label for="filter_message"> '+auto_reply_campaign_message_for_filter+'</label><textarea name="filter_message[]" class="form-control"></textarea></div></div></div>';

			$(".add_more_button_block").before(div_string);
			$("#odd_or_even").val(next_block);
		}
		else
			$("#add_more_filter_button").attr('disabled', 'true');

	});

	$(document).on('keypress', '.filter_word_input', function(event) {
		if(event.which == 13) event.preventDefault();
	});

	$(document).on('click', '.remove_div', function(event) {
		event.preventDefault();
		
		var parent_div = $(this).parent().parent().parent();
		$(parent_div).remove();

		var content_amount = parseInt($("#content_block").val(), 10);
		$("#content_block").val(content_amount - 1);
		$("#add_more_filter_button").removeAttr('disabled');
	});

	$(document).on('click', '#create_campaign', function(event) {
		event.preventDefault();
		$("#create_campaign").addClass('btn-progress');
		var video_id = $("#video_id").val();
		var form_data = new FormData($("#auto_reply_create_campaign")[0]);

		$.ajax({
			url: base_url+'responder/auto_reply_create_campaign',
			type: 'POST',
			dataType: 'json',
			data: form_data,
			contentType: false,
			cache:false,
			processData: false,
			success: function(response){
				$("#create_campaign").removeClass('btn-progress');
				if (response.status == 'insufficient_data') {

					if (response.field == 'campaign_name') {

						swal(global_lang_error, auto_reply_campaign_set_campaign_name, 'error');
					}
					else if (response.field == 'empty_generic_message') {

						swal(global_lang_error, auto_reply_campaign_provide_generic_reply, 'error');
					}
					else if (response.field == 'filter_message_combination') {

						swal(global_lang_error, auto_reply_campaign_provide_filter_reply, 'error');
					}
					else if (response.field == 'empty_not_found_filter_message') {

						swal(global_lang_error, auto_reply_campaign_provide_no_match_reply, 'error');
					}

				}
				else if (response.status == 'success') {

					swal(global_lang_success, global_lang_campaign_updated_successfully, 'success').then(() => {$('#set_auto_reply_modal').modal('hide');});
				}
				else if (response.status == 'failed') {

					swal(global_lang_error, global_lang_something_went_wrong, 'error');
				}

				$('#set_auto_reply_modal').on('hidden.bs.modal', function () { 
					table.draw(); 
				});
			}
		});
		
	});

	$(document).on('click', '.delete_campaign', function(event) {
		event.preventDefault();
		
		var campaign_id = $(this).attr('campaign_id');

		swal({
		  title: global_lang_are_you_sure,
		  text: auto_reply_campaign_delete_confirmation,
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {

		  	$.ajax({
		  		url: base_url+'responder/delete_auto_reply_campaign',
		  		type: 'POST',
		  		dataType: 'json',
		  		data: {campaign_id: campaign_id},
		  		success: function(response) {
		  			
		  			if (response.status == 'success') {

		  				swal(global_lang_success, response.message, 'success');
		  				table.draw();
		  			}
		  			else if (response.status == 'failed')
		  				swal(global_lang_error, response.message, 'error');


		  		}
		  	});

		  }
		});


		
	});


	$(document).on('click', '.campaign_state', function(event) {
		event.preventDefault();
		
		swal({
		  title: global_lang_are_you_sure,
		  text: global_lang_campaign_campaign_state_confirmation,
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		    if (willDelete) {

				let campaign_id = $(this).attr('campaign_id');
				
				$.ajax({
					url: base_url+'responder/change_auto_reply_campaign_staus',
					type: 'POST',
					dataType: 'json',
					data: {campaign_id: campaign_id},
					success: function (response) {

						if (response.status == "success") {
							iziToast.success({title: '',message: response.message,position: 'bottomRight'});
						} else {
							iziToast.error({title: '',message: response.message,position: 'bottomRight'});
						}

						table.draw();
					}
				});
		    }
		});
		
	});

});
