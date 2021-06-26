"use strict";
Dropzone.autoDiscover = false;
$("#dropzone").dropzone({ 
	url: base_url+"social_accounts/video_upload_files",
	maxFilesize:global_lang_video_upload_limit,
	uploadMultiple:false,
	paramName:"file",
	createImageThumbnails:true,
	acceptedFiles: ".mov,.mpeg4,.mp4,.avi,.wmv,.mpegps,.flv,.3gpp,.webm",
	maxFiles:1,
	addRemoveLinks:true,
	success:function(file, response){
		$("#video_url").val(eval(response));
	},
	removedfile: function(file) {
		var name = $("#video_url").val();
		if(name !="")
		{
			$(".dz-preview").remove();
			$.ajax({
				type: 'POST',
				url: base_url+'social_accounts/video_delete_files',
				data: {op: "delete",name: name},
				success: function(data){
					$("#video_url").val('');
					console.log('success: ' + data);
				}
			});
		}
		else
		{
			$(".dz-preview").remove();
		}

	},
});

$(document).ready(function() {

	function search_in_div(obj, target_row, display_div, search_field){

		var filter=$(obj).val().toUpperCase();

		target_row = '.' + target_row;
		display_div = '.' + display_div;
		search_field = '.' + search_field;


		$(target_row).each(function(index, el) {
			
			var content = $(el).find(search_field).text().trim();

			if (content.toUpperCase().indexOf(filter) > -1) {
			  $(this).css('display','');
			}
			else $(this).css('display','none');
		});
	}

	
	$(document).on('click', '.channel_list_item', function(event) {
		event.preventDefault();
		
		/* add active class */
		$(".channel_list_item").each(function(index, el) {
			$(el).removeClass('active');
		});
		$(this).addClass('active');
		var channel_table_id = $(this).attr('channel_table_id');

		$.ajax({
			url: base_url+"home/set_youtube_channel_session",
			type: 'POST',
			data: {channel_table_id: channel_table_id},
			success: function(response){
				
			}
		});
		
	});


	$(document).on('click', '#submit_btn', function(event) {
		event.preventDefault();
		
		var channel_id = $('ul#channel_list_ul').find('li.active').attr('channel_id');
		if(channel_id == undefined)
			channel_id= '';
		var title = $("#title").val();
		var description = $("#description").val();
		var tags = $("#tags").val();
		var category = $("#category").val();
		var privacy_type = $("input[name='video_type']:checked").val();
		if (privacy_type == undefined)
			privacy_type ='';
		var time_zone = $("#time_zone").val();
		var schedule_time = $("#schedule_time").val();
		var video_url = $("#video_url").val();

		if(title == '')
		{
			swal(global_lang_error, upload_lang_error_msg1, 'error');
			return false;
		}

		if(channel_id == '')
		{
			swal(global_lang_error, upload_lang_error_msg2, 'error');
			return false;
		}

		if(category == '')
		{
			swal(global_lang_error, upload_lang_error_msg3, 'error');
			return false;
		}

		if(privacy_type =='')
		{
			swal(global_lang_error, upload_lang_error_msg4, 'error');
			
			return false;
		}

		if(time_zone == '')
		{
			swal(global_lang_error, upload_lang_error_msg5, 'error');
			return false;
		}

		if(schedule_time == '')
		{
			swal(global_lang_error, upload_lang_error_msg6, 'error');
			return false;
		}
		if(video_url == '')
		{
			swal(global_lang_error, upload_lang_error_msg7, 'error');
			return false;
		}
		$("#submit_btn").addClass('btn-progress');
		$.ajax({
			type:'POST' ,
			url:base_url+"social_accounts/youtube_video_upload_action",
			data: {channel_id:channel_id,privacy_type:privacy_type,title:title,description:description,tags:tags,category:category,time_zone:time_zone,schedule_time:schedule_time,video_url:video_url},
			dataType: 'json',
			success:function(response){

					if (response.status == 'title') 
					{
						swal(global_lang_error, upload_lang_error_msg1, 'error');
					}						
					if (response.status == 'channel_id') 
					{
						swal(global_lang_error, upload_lang_error_msg2, 'error');
					}
					else if(response.status == 'category')
					{
						swal(global_lang_error, upload_lang_error_msg3, 'error');
					}
					else if(response.status == 'privacy_type')
					{
						swal(global_lang_error, upload_lang_error_msg4, 'error');
					}
					else if(response.status == 'time_zone')
					{
						swal(global_lang_error, upload_lang_error_msg5, 'error');
					}
					else if(response.status == 'schedule_time')
					{
						swal(global_lang_error, upload_lang_error_msg6, 'error');
					}

					else if(response.status == 'file_name')
					{
						swal(global_lang_error, upload_lang_error_msg7, 'error');
					}
					else if(response.status == 'success')
					{
						$("#submit_btn").removeClass('btn-progress');
						swal(global_lang_success, upload_lang_success_msg, 'success').then(function() {
						    window.location = base_url+'social_accounts/upload_video_list';
						});

					
					}
					else if(response.status == 'error')
					{	
						$("#submit_btn").removeClass('btn-progress');
						swal(global_lang_error, response.message, 'error');
					}

		}
		});
	});


});

