<script>
    var counter=0;
    $(function() {
	    "use strict";
	    $(document).ready(function() {      

	        setTimeout(function() {          
	          var start = $("#load_more").attr("data-start");   
	          load_data(start,false,false);
	        }, 1000);


	        $(document).on('click', '#load_more', function(e) {
	          var start = $("#load_more").attr("data-start");   
	          load_data(start,false,true);
	        });

	        $(document).on('click', '#search_submit', function(e) {
	          var start = '0';
	          load_data(start,true,false);
	        });

	        $(document).on('change', '#rows_number', function(event) {
	        	event.preventDefault();
	        	var rows_number = $(this).val();
	        	$("#load_more").attr("data-limit",rows_number);
	        	var start = '0';
	        	load_data(start,true,false);
	        });


	        function load_data(start,reset,popmessage) 
	        {
	          var limit = $("#load_more").attr("data-limit");        
	          var search = $("#search").val();
	          var iframe = "<?php echo $iframe;?>";
	          var channel_id = "<?php echo $channel_id;?>";
	          var load_src = "<?php echo $load_src;?>";
	          $("#waiting").show();
	          if(reset)
	          {
	          	$("#search_submit").addClass("btn-progress");
	          	counter = 0;
	          }
	          $.ajax({
	            url: base_url+'social_accounts/youtube/'+channel_id+"/"+iframe+"/"+load_src,
	            type: 'POST',
	            dataType : 'JSON',
	            data: {start:start,limit:limit,search:search},
	              success:function(response)
	              {
	                $("#waiting").hide();
	                $("#nodata").hide();
	                $("#search_submit").removeClass("btn-progress");

	                counter += response.found; 
	                $("#load_more").attr("data-start",counter); 
	                if(!reset)  $("#load_data").append(response.html);
	                else $("#load_data").html(response.html);
	                
	                if(response.found!='0') $("#load_more").show();                
	                else 
	                {
	                  $("#load_more").hide();
	                  if(popmessage) 
	                  {
	                    iziToast.error({title: '<?php echo $this->lang->line("Error"); ?>',message: "<?php echo $this->lang->line('No data found') ?>",position: 'bottomRight'});
	                    $("#nodata").hide();
	                  }
	                  else $("#nodata").show();
	                }
	              }
	          });
	        }

	    });
    });
</script>


<script>
$(function() {
	"use strict";
	$(document).ready(function() {

		$(".filter_message_block").css('display', 'none');

		$(".inputtags").tagsinput('items');


		$(".offensive_keywords_block").hide();
		$(".filter_message_block").hide();


		$(document).on('click', '#set_auto_reply_button', function(event) {
			event.preventDefault();
			
			$("#video_id").val($(this).attr('video_id'));
			$("#channel_id").val($(this).attr('channel_id'));
			$("#submit_type").val($(this).attr('submit_type'));

			var submit_type = $(this).attr('submit_type');

			if (submit_type == 'edit') {

				$("#create_campaign_and_save_as_template").hide();

				$(".set_auto_reply_info_block").html('<div class="text-center waiting_spinner_edit_video_css" id="waiting"><i class="fas fa-spinner fa-spin blue font_size_60px"></i></div>');

				$.ajax({
					url: '<?php echo base_url("responder/auto_reply_info_for_edit") ?>',
					type: 'POST',
					data: {video_id: $(this).attr('video_id')},
					success: function(response){
						$(".set_auto_reply_info_block").html(response);
						$("#create_campaign").html('<i class="far fa-edit"></i> <?php echo $this->lang->line("Edit Campaign"); ?>');
					}
				});
				
			} 
			else {

				$('.select2').select2();
				let final_string = '<div class="form-group"><label for="campaign_name"> <?php echo $this->lang->line("Campaign Name")?> </label><input id="campaign_name" name="campaign_name" class="form-control" type="text"></div><div class="row"><div class="col-12 col-md-6"><div class="form-group"><label for="use_saved_template" > <?php echo $this->lang->line('Do you want to use saved template?');?></label><br><label class="custom-switch mt-2"><input id="use_saved_template" type="checkbox" value="1" class="custom-switch-input" name="use_saved_template" checked><span class="custom-switch-indicator"></span><span class="custom-switch-description"><?php echo $this->lang->line('Yes');?></span></label></div></div></div><div id="saved_template_block"><div class="form-group"><label for="saved_template_list"> <?php echo $this->lang->line("Saved templates")?></label><?php echo preg_replace("/[\r\n]+/", "", form_dropdown('saved_template_id', $saved_templates, '0', 'id="saved_template_list" class="form-control select2"')) ; ?></div> </div><div id="raw_template_block"><div class="row"><div class="col-12 col-md-6"><div class="form-group"><label for="delete_offensive_comment" > <?php echo $this->lang->line('Do you want to delete offensive comments ?');?></label><br><label class="custom-switch mt-2"><input id="delete_offensive_comment" type="checkbox" name="delete_offensive_comment" value="1" class="custom-switch-input"><span class="custom-switch-indicator"></span><span class="custom-switch-description"><?php echo $this->lang->line('Yes');?></span></label></div></div></div><div class="form-group offensive_keywords_block"><label for="offensive_keywords"> <?php echo $this->lang->line("Offensive keywords (press enter to separate words)")?></label><textarea id="offensive_keywords" name="offensive_keywords" class="form-control inputtags"></textarea></div> <div class="row"><div class="col-12 col-md-6"><div class="form-group"><label for="" > <?php echo $this->lang->line('Reply Type');?></label><div class="custom-switches-stacked mt-2"><div class="row"> <div class="col-6 col-md-4"><label class="custom-switch"><input type="radio" name="reply_type" value="generic" checked class="custom-switch-input"><span class="custom-switch-indicator"></span><span class="custom-switch-description"><?php echo $this->lang->line('Generic'); ?></span></label></div><div class="col-6 col-md-4"><label class="custom-switch"><input type="radio" name="reply_type" value="filter" class="custom-switch-input"><span class="custom-switch-indicator"></span><span class="custom-switch-description"><?php echo $this->lang->line('By Filter'); ?></span></label></div></div></div></div> </div><div class="col-12 col-md-6"><div class="form-group"><label for="multiple_reply" > <?php echo $this->lang->line('Enable multiple reply');?></label><br><label class="custom-switch mt-2"><input id="multiple_reply" type="checkbox" name="multiple_reply" value="1" class="custom-switch-input"><span class="custom-switch-indicator"></span><span class="custom-switch-description"><?php echo $this->lang->line('Enable');?></span></label></div></div> </div><div class="form-group generic_message_block"><label for="generic_message"> <?php echo $this->lang->line("Message for generic reply.")?></label><textarea id="generic_message" name="generic_message" class="form-control"></textarea></div> <div class="filter_message_block"><div class="card card-info single_card"><div class="card-header"><h4><?php echo $this->lang->line("Filter Reply"); ?></h4><div class="card-header-action"><button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> <?php echo $this->lang->line('Remove'); ?></button></div></div><div class="card-body"><div class="form-group"><label for="filter_words"> <?php echo $this->lang->line("Filter Word")?> </label><input name="filter_words[]"class="form-control filter_word_input" type="text"></div><div class="form-group"><label for="filter_message"> <?php echo $this->lang->line("Message for filter")?></label><textarea name="filter_message[]" class="form-control"></textarea></div> </div></div><div class="clearfix add_more_button_block"><input type="hidden" id="content_block" value="1"><input type="hidden" id="odd_or_even" value="odd"><button class="btn btn-outline-primary float-right" id="add_more_filter_button"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Add more filter') ?></button></div><div class="form-group"><label for="not_found_filter_message"> <?php echo $this->lang->line("Message for no match")?></label><textarea id="not_found_filter_message" name="not_found_filter_message" class="form-control"></textarea></div> </div></div>';

				$(".set_auto_reply_info_block").html(final_string);
				$("#create_campaign").html('<i class="fa fa-save"></i> <?php echo $this->lang->line("Create Campaign"); ?>');

				
				$(".inputtags").tagsinput('items');

				$(".offensive_keywords_block").hide();
				$(".filter_message_block").hide();
				$("#raw_template_block").hide();

				$("#create_campaign_and_save_as_template").hide();

			}

		});


		$(document).on('click', '#delete_offensive_comment', function(event) {

			if (!this.checked) {
				$(".offensive_keywords_block").hide();
			} else {
				$(".offensive_keywords_block").show();
			}
		});


		$(document).on('click', '#use_saved_template', function(event) {

			if (!this.checked) {

				$("#saved_template_block").hide();
				$("#raw_template_block").show();
				$("#create_campaign_and_save_as_template").show();
			} else {

				$("#create_campaign_and_save_as_template").hide();
				$("#raw_template_block").hide();
				$("#saved_template_block").show();
			}
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
				
				var div_string = '<div class="card '+card_class+' single_card"><div class="card-header"><h4><?php echo $this->lang->line("Filter Reply"); ?></h4><div class="card-header-action"><button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> <?php echo $this->lang->line('Remove'); ?></button></div></div><div class="card-body"><div class="form-group"><label for="filter_words"> <?php echo $this->lang->line("Filter Word")?> </label><input name="filter_words[]"  class="form-control filter_word_input" type="text"></div><div class="form-group"><label for="filter_message"> <?php echo $this->lang->line("Message for filter")?></label><textarea name="filter_message[]" class="form-control"></textarea></div></div></div>';

				$(".add_more_button_block").before(div_string);
				$("#odd_or_even").val(next_block);
			}
			else
				$("#add_more_filter_button").attr('disabled', 'true');

		});

		$(document).on('keypress', '.filter_word_input', function(event) {
			if(event.which == 13) {
				event.preventDefault();
			}
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
			auto_reply_submit(false);			
		});		

		$(document).on('click', '#create_campaign_and_save_as_template', function(event) {			
			event.preventDefault();
			auto_reply_submit(true);
		});

		$('#set_auto_reply_modal').on('hidden.bs.modal', function () { 
			location.reload(); 
		});


		$('#set_auto_reply_modal').on('shown.bs.modal', function () { 
			$("#autoReplyResponseDiv").html(''); 
		});

		$('#edit_yt_video_modal').on('shown.bs.modal', function () { 
			$("#editVideoReplyResponseDiv").html(''); 
			$(document).off('focusin.modal');
		});

		$('#rank_track_set_modal').on('shown.bs.modal', function () { 
			$("#rankSubmitVideoReplyResponseDiv").html(''); 
		});

		$('#rank_track_report_modal').on('shown.bs.modal', function () { 
			$("#rankReportVideoReplyResponseDiv").html(''); 
		});

		




		// $(document).on('change', '#content_block', function(event) {
		// 	// event.preventDefault();
			
		// 	console.log('something');
		// 	let content_block_val = $('#content_block').val();

		// 	if (content_block_val == '0') {
		// 		$("#add_more_filter_button").click();
		// 	}
		// });


		// $(document).on('change', '#offensive_keywords', function(event) {
			
		// 	let content_block_val = $('#content_block').val();

		// 	if (content_block_val == '0') {
		// 		console.log(content_block_val);
		// 		$("#add_more_filter_button").click();
		// 	}
		// });

	});
});

function auto_reply_submit(save_as_template)
{	
	$("#create_campaign").addClass('btn-progress');
	var form_data = new FormData($("#auto_reply_create_campaign")[0]);

	if(save_as_template)form_data.append('save_as_template', '1');

	$.ajax({
		url: '<?php echo base_url('responder/auto_reply_create_campaign'); ?>',
		type: 'POST',
		dataType: 'json',
		data: form_data,
		contentType: false,
		cache:false,
		processData: false,
		success: function(response){

			// var link="<?php echo base_url('social_accounts/youtube/'.$table_id); ?>"; 
			$("#create_campaign").removeClass('btn-progress');

			if (response.status == 'insufficient_data') {

				if (response.field == 'campaign_name') {
					showMessage("danger","#autoReplyResponseDiv","<?php echo $this->lang->line('Please set the Campaign Name'); ?>");
				}
				else if (response.field == 'empty_generic_message') {
					showMessage("danger","#autoReplyResponseDiv","<?php echo $this->lang->line('Please provide a message for generic reply.'); ?>");
				}
				else if (response.field == 'filter_message_combination') {
					showMessage("danger","#autoReplyResponseDiv","<?php echo $this->lang->line('Please complete all the combination of filter message and response.'); ?>");
				}
				else if (response.field == 'empty_not_found_filter_message') {
					showMessage("danger","#autoReplyResponseDiv","<?php echo $this->lang->line('Please provide a message for not match found on filter message.'); ?>");
				}
				else if (response.field == 'template_not_found') {
					showMessage("danger","#autoReplyResponseDiv","<?php echo $this->lang->line('Please select a template.'); ?>");
				}

				// setTimeout(function() {location.reload(); }, 500); 

			}
			else if (response.status == 'success') {

				if (response.type == 'add') {
					showMessage("success","#autoReplyResponseDiv","<?php echo $this->lang->line('Campaign has been created successfully.'); ?>");
				} else if (response.type == 'edit') {
					showMessage("success","#autoReplyResponseDiv","<?php echo $this->lang->line('Campaign has been updated successfully.'); ?>");
				}
				setTimeout(function() {location.reload(); }, 2000); 
			}
			else if (response.status == 'failed') {						
				showMessage("danger","#autoReplyResponseDiv",response.message);
				// setTimeout(function() {location.reload(); }, 2000);
			}					
		}
	});
}
</script>

<script>
$(function() {
	"use strict";
	$(document).ready(function() {
   		
		$(document).on('change', 'input[type=checkbox][name=localization_has]', function(event) {
			event.preventDefault();
			if(this.checked) $('#localizations_id').fadeIn('slow');
			else $('#localizations_id').fadeOut('slow');	
		});

		$(document).on('click', '#edit_yt_video', function(event) {
			event.preventDefault();
			
			$("#edit_video_id").val($(this).attr('video_id'));
			$("#edit_channel_id").val($(this).attr('channel_id'));
	        $(".edit_video_info_block").html('<div class="text-center waiting_spinner_edit_video_css" id="waiting"><i class="fas fa-spinner fa-spin blue font_size_60px"></i></div>');

				$.ajax({
					url: '<?php echo base_url("social_accounts/edit_yt_video") ?>',
					type: 'POST',
					data: {video_id: $(this).attr('video_id'),channel_id:$(this).attr('channel_id')},
					success: function(response){
						$(".edit_video_info_block").html(response);
						$("#update_video").html('<i class="fas fa-edit"></i> <?php echo $this->lang->line("Update Video"); ?>');
					}
				});
				
			

		});
		


		$(document).on('click', '#video_edit_add_more', function(event) {
			event.preventDefault();

			var content_amount = parseInt($("#video_edit_content_block").val(), 10);

			if (content_amount < 20) {

				$("#video_edit_content_block").val(content_amount + 1);

				var current_block = $("#video_edit_odd_or_even").val();
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


				var language_list='<select name="localization_lang[]" class="form-control select2"><option value="ar-XA">Arabic</option><option value="bg">Bulgarian</option><option value="hr">Hindi</option><option value="cs">Czech</option><option value="da">Danish</option><option value="de">German</option><option value="el">Greek</option><option value="en" selected="selected">English</option><option value="et">Estonian</option><option value="es">Spanish</option><option value="fi">Finnish</option><option value="fr">French</option><option value="in">Indonesian</option><option value="ga">Irish</option><option value="hu">Hungarian</option><option value="he">Hebrew</option><option value="it">Italian</option><option value="ja">Japanese</option><option value="ko">Korean</option><option value="lv">Latvian</option><option value="lt">Lithuanian</option><option value="nl">Dutch</option><option value="no">Norwegian</option><option value="pl">Polish</option><option value="pt">Portuguese</option><option value="sv">Swedish</option><option value="ro">Romanian</option><option value="ru">Russian</option><option value="sr-CS">Serbian</option><option value="sk">Slovak</option><option value="sl">Slovenian</option><option value="th">Thai</option><option value="tr">Turkish</option><option value="uk-UA">Ukrainian</option><option value="zh-chs">Chinese (Simplified)</option><option value="zh-cht">Chinese (Traditional)</option></select>';
				
				var div_string = '<div class="card ' + card_class + ' single_card"><div class="card-header"><h4><?php echo $this->lang->line("Localizations"); ?></h4><div class="card-header-action"><button class="btn btn-outline-secondary video_edit_remove_div"><i class="fas fa-times"></i><?php echo $this->lang->line('Remove'); ?></button></div></div><div class="card-body"><div class="form-group"><label for="" ><?php echo $this->lang->line('Language'); ?></label>' + language_list + '</div><div class="form-group"><label for="title"> <?php echo $this->lang->line("Title"); ?> </label><input name="localization_title[]"  class="form-control" type="textbox" value="" id="textbox1"></div><div class="form-group"><label for="description"><?php echo $this->lang->line("Description"); ?></label><textarea name="localization_description[]" class="form-control" id="textarea1"></textarea></div> </div></div>';

				$(".video_edit_add_more_button_block").before(div_string);
				$("#video_edit_odd_or_even").val(next_block);
			}
			else
				$("#video_edit_add_more").attr('disabled', 'true');

		});

		$(document).on('click', '.video_edit_remove_div', function(event) {
			event.preventDefault();
			
			var parent_div = $(this).parent().parent().parent();
			$(parent_div).remove();

			var content_amount = parseInt($("#video_edit_content_block").val(), 10);

			$("#video_edit_content_block").val(content_amount - 1);
			$("#video_edit_add_more").removeAttr('disabled');
		});

		$(document).on('click', '#update_video', function(event) {
			event.preventDefault();

			$(this).addClass('btn-progress');
			var form_data = new FormData($("#edit_video_form_data")[0]);

			$.ajax({
				context: this,
				url: '<?php echo base_url('social_accounts/update_video_data'); ?>',
				type: 'POST',
				dataType: 'json',
				data: form_data,
				contentType: false,
				cache:false,
				processData: false,
				success: function(response){

					$(this).removeClass('btn-progress');

					// var link="<?php echo base_url('social_accounts/youtube/'.$table_id); ?>"; 

					if (response.status == 'empty') 
					{

						if (response.field == 'video_title') {
							showMessage("danger","#editVideoReplyResponseDiv","<?php echo $this->lang->line('Please set the video title'); ?>");
						}

						if (response.field == 'video_description') {
							showMessage("danger","#editVideoReplyResponseDiv","<?php echo $this->lang->line('Please set the video description'); ?>");
						}

						if (response.field == 'video_language') {
							showMessage("danger","#editVideoReplyResponseDiv","<?php echo $this->lang->line('Please select a language'); ?>");
						}

						if (response.field == 'video_privacy_status') {
							showMessage("danger","#editVideoReplyResponseDiv","<?php echo $this->lang->line('Please select a privacy'); ?>");
						}

						if (response.field == 'localizations_titles') {
							showMessage("danger","#editVideoReplyResponseDiv","<?php echo $this->lang->line('You have missing localization title somewhere'); ?>");
						}	

						if (response.field == 'localizations_descriptions') {
							showMessage("danger","#editVideoReplyResponseDiv","<?php echo $this->lang->line('You have missing localization description somewhere'); ?>");
						}

						if (response.field == 'localizations_lan') {
							showMessage("danger","#editVideoReplyResponseDiv","<?php echo $this->lang->line('You can not use localization language duplicate'); ?>");
						}

					} else if (response.status == 'success') {

						    showMessage("success","#editVideoReplyResponseDiv","<?php echo $this->lang->line('Video information has successfully updated.'); ?>");
		                     setTimeout(function() {location.reload(); }, 2000);					

						// $('#edit_yt_video_modal').on('hidden.bs.modal', function () { 
						// });

					}
					else if (response.status == 'failed') {
						showMessage("danger","#editVideoReplyResponseDiv",response.message);
					}

				}
			});
			
		});


		$(document).on('click','.rank_track_set', function(event){
			event.preventDefault();

		   $("#rank_video_id").val($(this).attr('video_id'));

		   var video_id = $(this).attr('video_id');
		
			$.ajax({

				url: '<?php echo base_url('social_accounts/get_rank_tracker_data'); ?>',
				type: 'POST',
				dataType: 'JSON',
				data: {video_id:video_id},
				success: function(response){

					$('#keyword').val(response.keyword);
					$('#name').val(response.name);

				}

			});


		});
		$(document).on('click', '#rank_submit', function(event){
			event.preventDefault();

			var keyword = $('#keyword').val();
			if(keyword == "")
			{
				showMessage("danger","#rankSubmitVideoReplyResponseDiv","<?php echo $this->lang->line('Please Enter Keyword'); ?>");
				return false;
			}

			$(this).addClass('btn-progress');
			var form_data = new FormData($("#rank_tracker_set_form")[0]);

			$.ajax({
				context: this,
				url: '<?php echo base_url('social_accounts/keyword_tracking_settings_action'); ?>',
				type: 'POST',
				dataType: 'JSON',
				data: form_data,
				contentType: false,
				cache:false,
				processData: false,
				success: function(response){

					$(this).removeClass('btn-progress');

					if(response.status == 'keyword')
						showMessage("danger","#rankSubmitVideoReplyResponseDiv",response.message);
					else if(response.status == 'video')
						showMessage("danger","#rankSubmitVideoReplyResponseDiv",response.message);
					else if(response.status == 'update') {
						showMessage("success","#rankSubmitVideoReplyResponseDiv",response.message);
						setTimeout(function() {location.reload();}, 2000);
					}
					else if(response.status == '1') {
						 showMessage("success","#rankSubmitVideoReplyResponseDiv",response.message);
						 setTimeout(function() {location.reload();}, 2000);
					}
					else if(response.status == '0') {
		                showMessage("danger","#rankSubmitVideoReplyResponseDiv",response.message);
					}

				}
			});

		});

		$(document).on('click','.rank_track_report', function(event){
			event.preventDefault();

			var video_id = $(this).attr('video_id');
			$.ajax({

				url:'<?php echo base_url('social_accounts/search_keyword_id'); ?>',
				type: 'POST',
				dataType: 'JSON',
				data: {video_id:video_id},
				success: function(response){
					$("#keyword_id").val(response.id);
				}

			});

		});
		$("#rank_track_report_modal").on("hidden.bs.modal", function(){
		     // $(this).find('form').trigger('reset');
		     $("#from_date").val("<?php echo date('Y-m-d'); ?>");
			 $("#to_date").val("<?php echo date('Y-m-d'); ?>");
		     $("#search_rank").removeAttr("disabled");
		     $('#rank_report').html('');
		});

		$(document).on('click','#search_rank', function(event){
			event.preventDefault();
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			if(from_date == "")
			{
				showMessage("danger","#rankReportVideoReplyResponseDiv","<?php echo $this->lang->line('Please select from date'); ?>");
				return false;
			}
			if(to_date == "")
			{
				showMessage("danger","#rankReportVideoReplyResponseDiv","<?php echo $this->lang->line('Please select to date'); ?>");
				return false;
			}
			$('#search_rank').addClass('btn-progress');
			var form_data = new FormData($("#rank_tracker_report_form")[0]);

			$.ajax({
				url: '<?php echo base_url('social_accounts/keyword_position_report_data'); ?>',
				type: 'POST',
				data: form_data,
				contentType: false,
				cache:false,
				processData: false,
				success: function(response){
					if (response == '')
					showMessage("danger","#rankReportVideoReplyResponseDiv","<?php echo $this->lang->line('You did not set rank tracker for this video yet.'); ?>");
					$("#rank_report").html(response);
					$('#search_rank').removeClass('btn-progress');
					// $('#search_rank').attr("disabled", true);

				}
			});

		});

	});
});
</script>

<script type="text/javascript">
$(function() {
	"use strict";
	$(document).ready(function() {
		$(document).on('click', '#link_wheel', function(event) {
			event.preventDefault();

			var that = $(this);

			var videos = [];

	      	$.each($("input[name='selected_videos']:checked"), function(){            
	      	      videos.push($(this).val());
	      	});

	      	var number_videos = videos.length;

	      	if(number_videos<=0)
	      	{
	      		iziToast.error({title: '<?php echo $this->lang->line("Error"); ?>',message: '<?php echo $this->lang->line("Please select videos first.");?>',position: 'topRight'});
	      		return false;
	      	}

	      	if($('#money_video_id').hasClass("select2-hidden-accessible")) $('#money_video_id').select2('destroy');
  			$("#money_video_id").select2({
  	          placeholder: "",
  	          tags: true,
  	          tokenSeparators: [','," "]
  	        }).val('').trigger('change');
  	        $("#wheel_name").val('');

	      	$("#modal_wheel").modal();
		});

		$('.modal').on("hidden.bs.modal", function (e) { 
		    if ($('.modal:visible').length) { 
		        $('body').addClass('modal-open');
		    }
		});		

		$(document).on('click', '#submit_wheel', function(event) {
			event.preventDefault();
		    var wheel_name=$("#wheel_name").val();                 
		    var wheel_type=$(".wheel_type:checked").val();                 
		    var money_video_id=$("#money_video_id").val();         
		    
    		var videos = [];

          	$.each($("input[name='selected_videos']:checked"), function(){            
          	      videos.push($(this).val());
          	});

		    if(wheel_name=="")
		    {
		      showMessage("danger","#linkWheelResponseDiv","<?php echo $this->lang->line('Please provide wheel name'); ?>");
		      return;
		    }

		    if(wheel_type=="")
		    {
		      showMessage("danger","#linkWheelResponseDiv","<?php echo $this->lang->line('Please choose a wheel type'); ?>");
		      return;
		    }

		    $(this).addClass('btn-progress');
		    $.ajax({
		    type:'POST' ,
		    url: "<?php echo site_url(); ?>link_wheel/create_wheel",
		    data:{wheel_type:wheel_type,videos:videos,wheel_name:wheel_name,money_video_id:money_video_id},
		    dataType:"JSON",
		    success:function(response){
		      $("#submit_wheel").removeClass('btn-progress');                
		      if(response.status=="1")
		      {
		          showMessage("success","#linkWheelResponseDiv",response.message);		          
		          setTimeout(function() {$("#modal_wheel").modal('hide'); }, 2000);

		      }
		      else
		      {                
		          showMessage("error","#linkWheelResponseDiv",response.message);         
		      }
		    }
		  });   
		});

		$('#modal_wheel').on('shown.bs.modal', function () { 
			$("#linkWheelResponseDiv").html(''); 
		});


	});
});
</script>
