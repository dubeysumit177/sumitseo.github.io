"use strict";
$(document).ready(function() {

	$(document).on('click', '#stop_import', function(event) {
		event.preventDefault();
		swal("Demo Restriction",'Account import as admin has been restricted in this demo, because if you import channel as admin you will not be able to unlink it. If you want to check with your own channel then sign up as member and import your channel there.', 'error');
	});
	
	$(document).on('click', '.delete_channel', function(event) {
		event.preventDefault();
		
		var table_id = $(this).attr('table_id');
		var social_media = $(this).attr('social_media');
		var that = $(this);

		swal({
          title: global_lang_are_you_sure,
          text: account_list_delete_confirmation,
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) 
          {

          	  $(that).find('i').addClass('fa fa-spin fa-spinner');
          	  $(that).find('i').removeClass('fas fa-trash-alt red');
              
              $.ajax({
                type:'POST' ,
                url: base_url+'social_accounts/delete_social_accounts',
                dataType: 'json',
                data:{table_id : table_id, social_media: social_media},
                success:function(response){ 

                   if(response.type == 'success')
                   {
                   	swal(global_lang_success, response.message, 'success');
                   	location.reload();
                   }
                   else
                   swal(global_lang_error, response.message, 'error');	                   

                   $(that).find('i').removeClass('fa fa-spin fa-spinner');
                   $(that).find('i').addClass('fas fa-trash-alt red');	                   
                }
              });
          } 
        });
	});

});
