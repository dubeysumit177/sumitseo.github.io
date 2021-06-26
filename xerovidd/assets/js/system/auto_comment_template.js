"use strict";
$(document).ready(function() {
	
	$(document).on('click', '.create_campaign', function(event) {
		event.preventDefault();

		$('.single_card').each(function(index, el) {
			$(el).remove();
		});

		$("#campaign_name").val('');
		$("#create_campaign_submit").html('<i class="fa fa-save"></i> '+global_lang_campaign_create);

		$('.add_more_button_block').before('');

		$('.add_more_button_block').before('<div class="card card-info single_card"><div class="card-header"><h4>'+auto_comment_template_comment_message+'</h4><div class="card-header-action"><button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> '+global_lang_remove+'</button></div></div><div class="card-body"><div class="form-group"><label for="comment_message"> '+auto_comment_template_message_for_comment+'</label><textarea name="comment_message[]" class="form-control"></textarea></div></div></div>');
		
		$("#set_auto_comment_templete_modal").modal();
	});


	$(document).on('click', '#add_more_message_button', function(event) {
		event.preventDefault();
		
		var content_block = $("#content_block").val();
		content_block = parseInt(content_block, 10) + 1;
		var odd_or_even = $("#odd_or_even").val();
		var card_type = '';

		

		if (content_block < 6) {

			if (odd_or_even == 'odd') {

				$("#odd_or_even").val('even');
				card_type = 'primary';
			}
			else {

				$("#odd_or_even").val('odd');
				card_type = 'info';
			}

			$('.add_more_button_block').before('<div class="card card-' + card_type + ' single_card"><div class="card-header"><h4>'+auto_comment_template_comment_message+'</h4><div class="card-header-action"><button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> '+global_lang_remove+'</button></div></div><div class="card-body"><div class="form-group"><label for="comment_message"> '+auto_comment_template_message_for_comment+'</label><textarea name="comment_message[]" class="form-control"></textarea></div></div></div>');

			$("#content_block").val(content_block);
		}
		else 
			$("#add_more_message_button").attr('disabled', 'true');

	});


	$(document).on('click', '.remove_div', function(event) {
		event.preventDefault();
		
		var parent_div = $(this).parent().parent().parent();
		$(parent_div).remove();

		var content_amount = parseInt($("#content_block").val(), 10);
		$("#content_block").val(content_amount - 1);
		$("#add_more_message_button").removeAttr('disabled');
	});


	$(document).on('click', '#create_campaign_submit', function(event) {
		event.preventDefault();
		
		$(this).addClass('btn-progress');
		var that = $(this);

		var form_data = new FormData($("#comment_templete_form")[0]);
		var submit_type = $("#submit_type").val();

		var creat_campaign_text = $("#create_campaign_submit").html();
		$("#create_campaign_submit").html(creat_campaign_text);

		$.ajax({
			url: base_url+'responder/create_auto_comment_template',
			type: 'POST',
			data: form_data,
			contentType: false,
			cache: false,
			processData: false,
			success: function(response) {
				$(that).removeClass('btn-progress');
				if (response == 1) {

					if (submit_type == 'add')
						swal(global_lang_success, global_lang_campaign_created_successfully, 'success');
					else if (submit_type == 'edit')
						swal(global_lang_error, global_lang_campaign_updated_successfully, 'success');


					var link=base_url+'responder/auto_comment_template'; 
					setTimeout(function () {window.location.assign(link);}, 500);  

					// $('#set_auto_comment_templete_modal').on('hidden.bs.modal', function () { 
					// });
				}
				else if (response == 'message not found') 
					swal(global_lang_error, auto_comment_template_provide_one, 'error');

				$("#create_campaign_submit").html(creat_campaign_text);

			}
		});
		
	});


	$(document).on('click', '.edit_campaign', function(event) {
		event.preventDefault();
		$(this).removeClass('btn-outline-primary');
		$(this).addClass('btn-primary btn-progress')
		var that = $(this);
		var campaign_id = $(this).attr('campaign_id');

		var loading = '<img src="'+base_url+'assets/pre-loader/color/Preloader_9.gif" class="center-block" height="30" width="30">';
		$("#auto_comment_templete_modal_body").html(loading);
		$("#set_auto_comment_templete_modal").modal();

		$.ajax({
			url: base_url+'responder/edit_auto_comment_template_data',
			type: 'POST',
			data: {campaign_id: campaign_id},
			success: function(response){
				$(that).addClass('btn-outline-primary');
				$(that).removeClass('btn-primary btn-progress');
				if (response != 'error') {

					$("#create_campaign_submit").html('<i class="fas fa-save"></i> '+auto_comment_template_edit);
					$("#auto_comment_templete_modal_body").html(response);
				}
				else {

					$("#auto_comment_templete_modal_body").html('<form action="" method="post" id="comment_templete_form"><input type="hidden" name="submit_type" value="add" id="submit_type"><input type="hidden" name="campaign_id" id="campaign_id_on_modal"><div class="form-group"><label for="campaign_name"> '+global_lang_campaign_name+' </label><input id="campaign_name" name="campaign_name" class="form-control" type="text"> </div><br><div id="comments_section"><div class="clearfix add_more_button_block"><input type="hidden" id="content_block" value="1"><input type="hidden" id="odd_or_even" value="odd"><button class="btn btn-outline-primary float-right" id="add_more_message_button"><i class="fa fa-plus-circle"></i> '+global_lang_add_more+'</button></div></div></form>');
					$("#set_auto_comment_templete_modal").modal('hide');

					swal(global_lang_error, global_lang_something_went_wrong, 'error');
				}
			}
		});
			
	});


	$(document).on('click', '.delete_campaign', function(event) {
		event.preventDefault();
		
		var campaign_id = $(this).attr('campaign_id');
		var button_html = $(this).html();

		$(this).html('<i class="fas fa-spinner fa-spin"></i> ');
		var that = $(this);

		swal({
		    title: global_lang_are_you_sure,
		    text: global_lang_delete_confirmation,
		    icon: 'warning',
		    buttons: true,
		    dangerMode: true,
		  })
		  .then((willDelete) => {
		    if (willDelete) {

		    	$.ajax({
		    		url: base_url+'responder/delete_auto_comment_template',
		    		type: 'POST',
		    		data: {campaign_id: campaign_id},
		    		success: function(response) {

		    			if (response == 1) {

	    					swal(global_lang_campaign_deleted_successfully, {icon: 'success',});

	    					setTimeout(function(){
	    					 	var link=base_url+'responder/auto_comment_template'; 
	    					 	window.location.assign(link); 
	    					}, 1500);
	    				  	
		    			}
		    			else if (response == '0') {

		    				swal(global_lang_error, global_lang_something_went_wrong, 'error');
		    			}
		    		}


		    	});   	
		    
		    }			    
		    $(that).html('<i class="fas fa-trash-alt"></i>');
		  });

	});



	$(document).on('change', '#rows_number', function(event) {
		event.preventDefault();
		$("#search_submit").click();
	});

	$(document).on('keypress', '.templete_search', function(event) {
		if(event.which == 13) event.preventDefault();
	});	

});

