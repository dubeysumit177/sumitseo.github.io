"use strict";
function celar_playlist_form() {

	$("#playlist_title").val('');
	$("#playlist_description").val('');
	$("#playlist_tags").val('');
	$('.inputtags').tagsinput('removeAll');
	$("#playlist_privacy_type").val("0").change();
	$("#playlist_dropdown_text").html('');
	$("#create_playlist_submit").html('<i class="fas fa-plus-circle"></i> '+playlist_manager_lang_create_playlist);
}

$(document).ready(function() {


	$(document).ready(function(){
		var channels = $("#channel_list_ul").children();
		$(channels[0]).click();
	});


	$(".inputtags").tagsinput('items');

	
	$(document).on('click', '.channel_list_item', function(event) {
		event.preventDefault();

		var waiting_div_content = '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div>';
		$("#middle_column").html(waiting_div_content);
		$("#right_column").html(waiting_div_content);
		
		/* add active class */
		$(".channel_list_item").each(function(index, el) {
			$(el).removeClass('active');
		});
		$(this).addClass('active');

		var channel_id = $(this).attr('channel_id');
		var channel_table_id = $(this).attr('channel_table_id');

		$.ajax({
			url: base_url+'social_accounts/channels_playlist_info',
			type: 'POST',
			dataType: 'json',
			data: {channel_id: channel_id, channel_table_id: channel_table_id},
			success: function(response) 
			{
				$("#middle_column").html(response.content);

				if (response.has_playlist == true) {

					$(".playlist_item").each(function(index, el) {
						
						if (index == 0) $(el).click();
						else return false;
					});
				}
				else {

					var right_content = '';
					$("#right_column").html(right_content);
				}


			}
		});
		
	});


	$(document).on('click', '.playlist_item', function(event) {
		event.preventDefault();

		var waiting_div_content = '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div>';
		$("#right_column").html(waiting_div_content);
		
		$(".playlist_item").each(function(index, el) {
			$(el).removeClass('active');
		});
		$(this).addClass('active');

		var playlist_id = $(this).attr('playlist_id');
		var channel_id = $("#add_playlist_button").attr('channel_id');
		
		if (playlist_id == '') {
			/* only show headers & operation is restricted for this playlist */
		}
		else {

			$.ajax({
				context : this,
				url: base_url+'social_accounts/playlists_video_info',
				type: 'POST',
				dataType: 'JSON',
				data: {playlist_id: playlist_id, channel_id: channel_id},
				success: function(response) {
					$("#right_column").html(response.video_info);
					$(this).find('.number_of_videos').html(response.video_count);
				}
			});
			
		}
	});


	$(document).on('click', '#add_playlist_button', function(event) {
		event.preventDefault();
		$(this).removeClass('btn-outline-primary');
		$(this).addClass('btn-primary btn-progress')
		var that = $(this);
		setTimeout(function(){
			$(that).addClass('btn-outline-primary');
			$(that).removeClass('btn-primary btn-progress');

		},1000);

		celar_playlist_form();
		var channel_id = $(this).attr('channel_id');

		$("#playlist_channel_id").val(channel_id);
		$("#playlist_playlist_id").val('');

		$("#add_playlist_modal").modal();
	});



	$(document).on('click', '#create_playlist_submit', function(event) {
		event.preventDefault();
		
		
		var form_data = new FormData($("#add_playlist_form")[0]);

		$(this).addClass('btn-progress');
		var that = $(this);

		$.ajax({
			url: base_url+'social_accounts/create_user_playlist',
			type: 'POST',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			success: function (response) {
				
				$(that).removeClass('btn-progress');
				if (response.type == 'error') 
					swal(global_lang_error, response.message , 'error').then((value) => {
                     $("#add_playlist_modal").modal('hide');
                    });
				else if (response.type == 'success')
					swal(global_lang_success, response.message , 'success').then((value) => {
                     $("#add_playlist_modal").modal('hide');
                     var channel_id = $("#add_playlist_button").attr('channel_id');
                     $(".channel_list_item").each(function(index, el) {

                     	if (channel_id == $(el).attr('channel_id'))
                     		$(el).click();
                     });
                    });
				
			}
		});
		
	});


	$(document).on('click', '.edit_playlist', function(event) {
		event.preventDefault();
		event.stopPropagation();

		var channel_id = $("#add_playlist_button").attr('channel_id');
		var playlist_id = $(this).find('.playlist_id').val();
		var title = $(this).find('.title').val();
		var description = $(this).find('.description').val();
		var tags = $(this).find('.tags').val();

		
		$("#playlist_channel_id").val(channel_id);
		$("#playlist_playlist_id").val(playlist_id);
		$("#playlist_title").val(title);
		$("#playlist_description").val(description);
		$("#playlist_tags").val(tags);
		$("#playlist_dropdown_text").html("<small>("+playlist_manager_lang_we_dont_have_this+")</small>");
		$("#playlist_privacy_type").val("0").change();


		tags = tags.split(',');
		var temp = '';

		$('.inputtags').tagsinput('removeAll');
		$.each(tags, function(index, el) {$('.inputtags').tagsinput('add', el);});

		$("#create_playlist_submit").html('<i class="fa fa-save"></i> '+playlist_manager_lang_edit_playlist);

		$("#add_playlist_modal").modal();

	});


	$(document).on('click', '.visit_playlist', function(event) {
		event.preventDefault();
		event.stopPropagation();
		var url = $(this).attr('href'); 
		window.open(url, '_blank');
	});

	$(document).on('click', '.delete_playlist', function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		var playlist_id = $(this).find('.playlist_id').val();
		var channel_id = $("#add_playlist_button").attr('channel_id');

		swal({
          title: global_lang_are_you_sure,
          text: playlist_manager_lang_delete_confirmation,
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) 
          {
              $(this).parent().prev().addClass('btn-progress');	              

              $.ajax({
                url:base_url+"social_accounts/delete_playlist",
                type:'POST' ,
                dataType: 'json',
                data:{playlist_id : playlist_id, channel_id: channel_id},
                success:function(response){ 

                   $(this).parent().prev().removeClass('btn-progress');

                   if(response.type == 'success')
                     swal(global_lang_success, response.message, 'success').then((value) => {
                     var channel_id = $("#add_playlist_button").attr('channel_id');
                     $(".channel_list_item").each(function(index, el) {

                     	if (channel_id == $(el).attr('channel_id'))
                     		$(el).click();
                     });
                    });
                   else if (response.type == 'error')  swal(global_lang_error, response.message, 'error');
                   

                   
                }
              });
          } 
        });
	});		


	$(document).on('click', '.search_video', function(event) {

		event.preventDefault();
		var channel_id = $(this).attr('channel_id');
		var iframe_src = base_url+'social_accounts/video/'+"0/1";
		$("#search_video_modal_body iframe").attr('src',iframe_src).show();
		$("#search_video_modal").modal();			
	});	

	$(document).on('click', '.own_video', function(event) {
		event.preventDefault();
		var channel_id = $(this).attr('channel_id');
		var iframe_src = base_url+"social_accounts/youtube_channel/"+channel_id+"/1/playlist_manager";
		$("#search_video_modal_body iframe").attr('src',iframe_src).show();
		$("#search_video_modal").modal();
		
	});

	$('#search_video_modal').on("hidden.bs.modal", function (e) {
		var playlist_id = $(".playlist_item.active").attr("playlist_id");
		setTimeout(function(){$(".playlist_item[playlist_id="+playlist_id+"]").click(); }, 1000);
	});
	

	$(document).on('click', '.delete_video', function(event) {
		event.preventDefault();
		
		var playlist_item_id = $(this).attr('playlist_item_id');

		var channel_id = $("#parent_info").attr('channel_id');
		var playlist_id = $("#parent_info").attr('playlist_id');

		var that = $(this);


		swal({
          title: global_lang_are_you_sure,
          text: playlist_manager_lang_remove_confirmation,
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) 
          {
          	  $(that).find('i').addClass('fa-spin fa-spinner');
          	  $(that).find('i').removeClass('fa-trash-alt');

              $.ajax({
              	url: base_url+'social_accounts/delete_playlist_video',
              	type: 'POST',
              	dataType: 'json',
              	data: {playlist_item_id: playlist_item_id, channel_id: channel_id, playlist_id: playlist_id},
              	success: function (response) {

              		if (response.type == 'error')
              			swal(global_lang_error, response.message, 'error');
              		else if (response.type = 'success'){

              			swal(global_lang_success, response.message, 'success');  

          				setTimeout(function(){$(".playlist_item[playlist_id="+playlist_id+"]").click(); }, 1000);
              		}
              		$(that).find('i').removeClass('btn-progress');
              	}
              });
              
          }
        });


	});
});
