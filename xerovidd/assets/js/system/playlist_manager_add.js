"use strict";
  	$(document).ready(function() {
  		$(document).on('click', '#add_selected_videos', function(event) {
  			event.preventDefault();

  			var that = $(this);

  			var videos = [];

  	      	$.each($("input[name='selected_videos']:checked"), function(){            
  	      	      videos.push($(this).val());
  	      	});

  	      	var number_videos = videos.length;

  	      	if(number_videos<=0)
  	      	{
  	      		showMessage("danger","#videoListResponseDiv",playlist_manager_lang_select_videos);
  	      		return false;
  	      	}		

  	
        	   $(this).addClass('btn-primary btn-progress');
        	   $(this).removeClass('btn-outline-primary');
        	   
        	   var channel_id = $(this).attr('channel_id');
        	   var playlist_id = $(this).attr('playlist_id');
   
             $.ajax({
            	url: base_url+'social_accounts/add_playlist_video',
            	type: 'POST',
            	dataType: 'json',
            	context: this,
            	data: {videos: videos, channel_id: channel_id, playlist_id: playlist_id},
            	success: function (response) {

            		$(this).removeClass('btn-primary btn-progress');
            		$(this).addClass('btn-outline-primary');

            		if (response.type == 'error')
            		showMessage("danger","#videoListResponseDiv",response.message);
            		else if (response.type = 'success')
            		showMessage("success","#videoListResponseDiv", response.message);

            		$(this).removeClass('btn-primary');
            		$(this).removeClass('btn-progress');
        	  		$(this).addClass('btn-outline-primary');
            	}
  	       });

  		});

  	});