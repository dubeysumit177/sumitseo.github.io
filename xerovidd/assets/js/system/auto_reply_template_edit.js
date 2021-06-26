"use strict";
$(document).ready(function() {
	$(document).on("keypress", "#offensive_keywords", function(event) { 
	    if(event.which == 13) event.preventDefault();
	});

	$(document).on('click', '#delete_offensive_comment', function(event) {

		if (!this.checked) {
			$("#offensive_keywords_block").hide();
		} else {
			$("#offensive_keywords_block").show();
		}
	});
	$(document).on('click', 'input[name="reply_type"]', function(event) {

		let checked_value = $('input[name="reply_type"]:checked').val();

		if (checked_value == 'generic') {

			$(".filter_message_block").hide();
			$(".generic_message_block").show();
		} else if (checked_value == 'filter') {

			$(".generic_message_block").hide();
			$(".filter_message_block").show();
		}
	});
	/* filter message section start */
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

	$(document).on('click', '.remove_div', function(event) {
		event.preventDefault();
		
		var parent_div = $(this).parent().parent().parent();
		$(parent_div).remove();

		var content_amount = parseInt($("#content_block").val(), 10);
		$("#content_block").val(content_amount - 1);
		$("#add_more_filter_button").removeAttr('disabled');
	});
	/* filter message section end */


	$(document).on('click', '.cancel_template', function(event) {
		event.preventDefault();
		
		swal({
			title: global_lang_are_you_sure,
			text: auto_reply_template_cancel_confirmation,
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			
			if (willDelete) {
			    window.location.href = base_url+'responder/auto_reply_template';
			}
		});
	});

	
	$(document).on('click', '#create_template', function(event) {
		event.preventDefault();
		
		let form_data = new FormData($("#auto_reply_templete_form")[0]);
		$(this).addClass('btn-progress');
		var that = $(this);

		$.ajax({
			url: base_url+'responder/auto_reply_template_action',
			type: 'POST',
			dataType: 'json',
			cache: false,
			processData: false,
			contentType: false,
			data: form_data,
			success: function(response) {

				$(that).removeClass('btn-progress');

				if (response.status == 'error') {
					swal(global_lang_error, response.message, "error");
				} else if (response.status == 'success') {

					swal(global_lang_success, response.message, "success");
					setTimeout(function () {
						window.location.href = base_url+'responder/auto_reply_template';
					}, 500);
					
				}
			}
		});
	});
});