"use strict";
$(document).ready(function() {

	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = yyyy + '-' + mm + '-' + dd + ' 12:00';


	var perscroll;
	var table = $("#mytable").DataTable({
	    serverSide: true,
	    processing:true,
	    bFilter: true,
	    order: [[ 1, "desc" ]],
	    pageLength: 10,
	    ajax: 
	    {
	        "url": base_url+'social_accounts/upload_video_list_data',
	        "type": 'POST',
	    },
	    language: 
	    {
	      url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
	    },
	    dom: '<"top"f>rt<"bottom"lip><"clear">',
	    columnDefs: [
	      {
	      	targets:[1],
	      	visible:false
	      },
	      {
	          targets: '',
	          className: 'text-center'
	      },
	      {
	          targets: [0,3,4,7,8],
	          sortable: false
	      },

	      {

	      	 targets :[2],
	      	 render: function( data, type, row, meta )
	      	 {

	      	      var channel_visit = '<a class="" data-toggle="tooltip" title="'+global_lang_visit_channel+'" href="https://www.youtube.com/channel/'+row[2]+'" target="_BLANK">'+row[2]+'</a>';
	      	      return channel_visit;

	      	 }
	      },

	      {
	      	  targets: [3],
	      	  render: function ( data, type, row, meta ) 
	      	  {    
	      	  	  	var string = '';
	      	  		if (row[3] !=null) 
	      	  		{
	      	  			 string = '<a class="" data-toggle="tooltip" title="'+global_lang_watch_video+'" href="https://www.youtube.com/watch?v='+row[3]+'" target="_BLANK">https://www.youtube.com/watch?v='+row[3]+'</a>';
	      	  			 
	      	  		}
	      	  		else
	      	  		{
	      	  			 string = global_lang_not_applicable;
	      	  			
	      	  		}
	      	     
	      	      return string;


	      	  		 
	      	  }

	      },

	      {
	      	  targets: [4],
	      	  render: function ( data, type, row, meta ) 
	      	  {
	      	     var title = '';	
	      	  	 if (data !='') 
	      	  	 {	
	      	  	 	var substring = data.length;
	      	  	 	var dot = ''; 
	      	  	 	if(substring>25)
	      	  	 		 dot = '...';
	      	  	 	else
	      	  	 		 dot = '';

					title = '<div style="min-width:100px"><span title="'+data+'"">'+data.substring(0,25)+''+dot+' </span></div>';
	      	  	 }
	      	  	 else
	      	  	 {
	      	  	 	title = '<div style="min-width:100px"><span title="'+upload_lang_error_msg8+'"> '+upload_lang_error_msg9+'</span></div>';
	      	  	 }
	      	  	 return title;
	      	  		
	      	  }
	      },
	      
	      {

	      	targets: [7],
	      	render : function( data, type, row, meta)
	      	{
	      		
	      		var status = '';
	      		if(row[7] == '2')
	      		{
					status = '<div style="min-width:100px"><span title="'+global_lang_completed+'" class="text-success"><i class="fa fa-check-circle green"></i> '+global_lang_completed+'</span></div>';
	      		}
	      		else if(row[7] == '1')
	      		{
					status = '<div style="min-width:100px"><span title="'+global_lang_processing+'" class="text-muted"><i class="fa fa-spinner green"></i> '+global_lang_processing+'</span></div>';
	      		}
	      		else
	      		{
	      			status = '<div style="min-width:100px"><span class="text-warning" title="'+global_lang_pending+'"><i class="fas fa-times-circle"></i> '+global_lang_pending+'</span></div>';
	      		}
	      		return status;

	      	}
	      },

		  {
	      	  targets: [8],
	      	  render: function ( data, type, row, meta ) 
	      	  {		
	      	  		$("[data-toggle='tooltip']").tooltip();
	      	  		var string = '';

	      	     	if(row[7]=='0')
	      	     	  	string += '<a class="btn btn-circle btn-outline-warning edit_campaign" href="#" data-toggle="tooltip" title="'+global_lang_edit+'" campaign_id="' + row[1] +'"><i class="fas fa-edit"></i></a>&nbsp;';
	      	     	string += '<a class="btn btn-circle btn-outline-danger delete_campaign" href="#" data-toggle="tooltip" title="'+global_lang_delete+'" campaign_id="' + row[1] +'"><i class="fas fa-trash-alt"></i></a>';

	      	     	string += '&nbsp;<a class="btn btn-circle btn-danger show_error" href="#" data-toggle="tooltip" title="'+global_lang_error+'" campaign_id="' + row[1] +'"><i class="fas fa-bug"></i></a>';

	      	  		return string;  
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





	$(document).on('click', '.edit_campaign', function(event) {
		event.preventDefault();
		
		$(this).removeClass('btn-outline-warning');
		$(this).children('i').removeClass('fas fa-edit');
		$(this).addClass('btn-warning btn-progress');
		var campaign_id = $(this).attr('campaign_id');
		
		$.ajax({
			url: base_url+'social_accounts/scheduled_video_edit',
			type: 'POST',
			dataType: 'json',
			context: this,
			data: {campaign_id: campaign_id},
			success: function(response) {

				$(this).removeClass('btn-warning btn-progress');
				$(this).children('i').addClass('fas fa-edit');
				$(this).addClass('btn-outline-warning');
				if (response.result_status == 'success') {

					$("#submit_type").val('edit');
					$("#campaign_id_on_modal").val(response[0].id);
					$("#title").val(response[0].title);
					$("#description").val(response[0].description);
					$("#tags").val(response[0].tags);
					$("#schedule_time").val(response[0].upload_time);
					$("#category").val(response[0].category).change();
					$("#channel_id").val(response[0].channel_id).change();
					$("#time_zone").val(response[0].time_zone).change();

					if (response[0].privacy_type == 'public') 
						$('#video_type1').prop('checked', true); 
					else if (response[0].privacy_type == 'private')
						$('#video_type2').prop('checked', true); 
					else if (response[0].privacy_type == 'unlisted') 
						$('#video_type3').prop('checked', true); 

					$("#create_campaign_submit").html('<i class="fa fa-save"></i> '+upload_lang_update_video);
					$("#scheduled_video_modal").modal();
				}
				else if (response.result_status == 'failed') 
					swal(global_lang_error, global_lang_something_went_wrong, 'error');

				
				
			}
		});
		
	});
	
	$(document).on('click', '#create_campaign_submit', function(event) {
		event.preventDefault();

		$(this).addClass('btn-progress');
		var that = $(this);
		var form_data = new FormData($("#schedule_video_edit_form")[0]);

		$.ajax({
			url: base_url+'social_accounts/scheduled_video_edit_action',
			type: 'POST',
			dataType: 'json',
			data: form_data,
			chache: false,
			processData: false,
			contentType: false,
			success: function(response) {

				if (response.type == 'empty_field') 
					swal(global_lang_error, response.field , 'error');
				else if(response.type =='success')
				{
					swal(global_lang_success, upload_lang_success_msg, 'success').then(function() {
						$("#scheduled_video_modal").modal('hide');
					});
					table.draw();
				}
			
				else if(response.type=='failed') swal(global_lang_error, global_lang_something_went_wrong , 'error');
				else if (response.type =='fail') swal(global_lang_error, global_lang_something_went_wrong , 'error');	
				$(that).removeClass('btn-progress');


		
			}
		});
		
	});


	$(document).on('click', '.delete_campaign', function(event) {
		event.preventDefault();
		
		swal({
          title: global_lang_campaign_delete,
          text: global_lang_campaign_delete_confirmation,
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) 
          {
              $(this).parent().prev().addClass('btn-progress');

              var campaign_id = $(this).attr('campaign_id');

              $.ajax({
                context: this,
                type:'POST' ,
                url:base_url+"social_accounts/delete_schedule_video_campaign",
                dataType: 'json',
                data:{campaign_id : campaign_id},
                success:function(response){ 

                   $(this).parent().prev().removeClass('btn-progress');
                   if(response.status == '1')
                     swal(global_lang_success, response.message, 'success');
                   else
                     swal(global_lang_error, response.message, 'error');
                   

                   table.draw();
                }
              });
          } 
        });
	});	

    $(document).on('click', '.show_error', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "youtube_video_upload";
        var that = this;
        show_error_response(campaign_id,table_name,that);	          
	});


	$('.datetimepicker2').daterangepicker({
		locale: {format: 'YYYY-MM-DD hh:mm'},
		singleDatePicker: true,
		timePicker: true,
		timePicker24Hour: true,
		drops: "up"
	});



});