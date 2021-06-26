"use strict";
$(document).ready(function() {

	$(document).ready(function(){
		var channels = $("#channel_list_ul").children();
		$(channels[0]).click();
	});
	
	$(document).on('click', '.channel_list_item', function(event) {
		event.preventDefault();
		
		/* add active class */
		$(".channel_list_item").each(function(index, el) {
			$(el).removeClass('active');
		});
		$(this).addClass('active');

		var channel_id = $(this).attr('channel_id');

		var iframe_src =  base_url+'social_accounts/youtube_channel/'+channel_id+"/1/video_manager";
	    var iframe_height = $(this).attr('data-height');
	    $("iframe").attr('src',iframe_src).show();
		
	});

});