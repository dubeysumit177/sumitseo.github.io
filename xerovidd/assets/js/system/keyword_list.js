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
	        "url": base_url+'social_accounts/rank_keyword_list_data',
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
	          targets: [0,2,4,6,7],
	          sortable: false
	      },

	      {

	      	 targets :[6],
	      	 render: function( data, type, row, meta )
	      	 {
	      	 	  // $('[data-toggle="tooltip"]').tooltip(); 
	      	      var video_play = '<a class="youtube" href="https://www.youtube.com/embed/'+row[4]+'"><i class="fas fa-play-circle"></i> '+global_lang_play+'</a>';
	      	       return video_play;

	      	 }
	      },

	      {
	      	  targets: [7],
	      	  render: function ( data, type, row, meta ) 
	      	  {    
	      	  	  $('[data-toggle="tooltip"]').tooltip();	
	      	  	  var delete_button = '<a class="btn btn-circle btn-outline-danger delete_campaign" data-toggle="tooltip" href="#" title="'+global_lang_delete+'" campaign_id="'+row[1]+'"><i class="fas fa-trash-alt"></i></a>';

	      	  	  delete_button += '&nbsp;<a class="btn btn-circle btn-outline-primary rank_track_report" keyword_id="'+row[1]+'" data-toggle="tooltip" href="#" title="'+global_lang_report+'"><i class="fas fa-eye"></i></a>';

	      	     
	      	      return delete_button;


	      	  		 
	      	  }

	      },


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


	$(document).on('click', '.create_campaign', function(event) {
		event.preventDefault();

		$("#rank_track_set_modal").modal();

	});

	$(document).on('click', '.rank_track_report', function(event) {
		event.preventDefault();

		$("#keyword_id").val($(this).attr('keyword_id'));
		$("#rank_track_report_modal").modal();

	});

	$(document).on('click','#search_rank', function(event){
		event.preventDefault();
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		if(from_date == "")
		{
			showMessage("danger","#rankReportVideoReplyResponseDiv",global_lang_select_from_date);
			return false;
		}
		if(to_date == "")
		{
			showMessage("danger","#rankReportVideoReplyResponseDiv",global_lang_select_to_date);
			return false;
		}
		$('#search_rank').addClass('btn-progress');
		var form_data = new FormData($("#rank_tracker_report_form")[0]);

		$.ajax({
			url: base_url+'social_accounts/keyword_position_report_data',
			type: 'POST',
			data: form_data,
			contentType: false,
			cache:false,
			processData: false,
			success: function(response){
				if (response == '')
				showMessage("danger","#rankReportVideoReplyResponseDiv",keyword_rank_lang_didnt_set_rank);
				$("#rank_report").html(response);
				$('#search_rank').removeClass('btn-progress');
				// $('#search_rank').attr("disabled", true);

			}
		});

	});

	$(document).on('click', '.youtube', function(event) {
		event.preventDefault();

		var width=$(window).width();
		var a;
		var b;

		if(width<400) a=90;
		else a= 55;

		b= 9*a/16;
		var iframe_width=width*a/100;
		var iframe_height=iframe_width*b/a;
		     
		      $(".youtube").colorbox({
		        iframe:true, 
		        innerWidth:iframe_width, 
		        innerHeight:iframe_height,
		        href: function () {
		          return $(this).attr("href");
		        }
		      });

	});
	
	$(document).on('click', '#rank_submit', function(event){
		event.preventDefault();

		$(this).addClass('btn-progress');
		var keyword = $('#keyword').val();
		if(keyword == "")
		{
			swal(global_lang_error, keyword_rank_lang_enter_keyword,"error");
			$(this).removeClass('btn-progress');
			return false;
		}

		var video_id = $('#video_id').val();
		if(video_id =="")
		{
			swal(global_lang_error, keyword_rank_lang_enter_video_id,"error");
			$(this).removeClass('btn-progress');
			return false;
		}

		var form_data = new FormData($("#rank_tracker_set_form")[0]);

		$.ajax({
			url: base_url+'social_accounts/keyword_tracking_settings_action',
			type: 'POST',
			dataType: 'JSON',
			data: form_data,
			context: this,
			contentType: false,
			cache: false,
			processData: false,
			success: function(response){
				$(this).removeClass('btn-progress');
				if(response.status == 'keyword')
					swal(global_lang_error,response.message,'error');
				else if(response.status == 'video')
					swal(global_lang_error,response.message,'error');
				else if(response.status == 'update')
					swal(global_lang_success,response.message,'success').then((value) => {
                     $("#rank_track_set_modal").modal('hide');  
                     table.draw();
                    });
				else if(response.status == '1')
					swal(global_lang_success,response.message,'success').then((value) => {
                     $("#rank_track_set_modal").modal('hide');  
                     table.draw();
                    });
				else if(response.status == '0')
					swal(global_lang_error,response.message,'error');

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
                url:base_url+"social_accounts/delete_rank_tracker_campaign",
                dataType: 'json',
                data:{campaign_id : campaign_id},
                success:function(response){ 

                   $(this).parent().prev().removeClass('btn-progress');
                   if(response.status == '1')
                     swal(global_lang_success, response.message, 'success').then((value) => {
                     table.draw();
                    });
                   else
                     swal(global_lang_error, response.message, 'error');
                   

                   table.draw();
                }
              });
          } 
        });
	});

	$('.datetimepicker2').daterangepicker({
		locale: {format: 'YYYY-MM-DD hh:mm'},
		singleDatePicker: true,
		timePicker: true,
		timePicker24Hour: true,
		drops: "up"
	});
});
