"use strict";
$(document).ready(function() {

	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = yyyy + '-' + mm + '-' + dd + ' 12:00';

	var perscroll;
	var perscroll1;

	var table = $("#mytable").DataTable({
	    serverSide: true,
	    processing:true,
	    bFilter: true,
	    order: [[ 4, "desc" ]],
	    pageLength: 10,
	    ajax: 
	    {
	        "url": base_url+'responder/auto_like_comment_campaigns',
	        "type": 'POST',  
	    },
	    language: 
	    {
	      url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
	    },
	    dom: '<"top"f>rt<"bottom"lip><"clear">',
	    columnDefs: [
	      {
	    	  targets: [1],
	    	  visible: false
	      },
	      {
	          targets: [4,5,6,7],
	          className: 'text-center'
	      },
	      {
	          targets: [0,4,6,7],
	          sortable: false
	      },
	      {
	      	  targets: [5],
	      	  render: function ( data, type, row, meta ) 
	      	  {
	      	     	var string = '';

	      	     	if (data == '0')
	      	     		string = '<span class="text-success"><i class="fa fa-check"></i> '+global_lang_active+'</span>';
	      	     	else if (data == '1')
	      	     		string = '<span class="text-warning">'+global_lang_processing+'</span>';
	      	     	else if (data == '2')
	      	     		string = '<span class="text-success">'+global_lang_completed+'</span>';

	      	  		return string;  
	      	  }

	      },
	      {
	      	  targets: [7],
	      	  render: function ( data, type, row, meta ) 
	      	  {
	      	     	$("[data-toggle='tooltip']").tooltip();
	      	     	var string = '<div style="width:180px;"><a class="btn btn-circle btn-outline-info view_report" data-toggle="tooltip" href="#" title="'+global_lang_report+'" campaign_id="' + row[1] +'"><i class="fas fa-eye"></i></a>&nbsp;';
	      	     	string += '<a class="btn btn-circle btn-outline-warning edit_campaign" data-toggle="tooltip" href="#" title="'+global_lang_edit+'" campaign_id="' + row[1] +'"><i class="fas fa-edit"></i></a>&nbsp;';
	      	     	string += '<a class="btn btn-circle btn-outline-danger delete_campaign" data-toggle="tooltip" href="#" title="'+global_lang_delete+'" campaign_id="' + row[1] +'"><i class="fas fa-trash-alt"></i></a>&nbsp;';
	      	     	string += '<a class="btn btn-circle btn-danger show_error" data-toggle="tooltip" href="#" title="'+global_lang_error+'" campaign_id="' + row[1] +'"><i class="fas fa-bug"></i></a></div>';

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


	function clear_campaign_create_input_field() {

		$("#submit_type").val('add');
		$("#campaign_id_on_modal").val('');

		$("#campaign_name").val('');
		$("#auto_comment_template").val("0").change();
		$("#user_channel_id").val("0").change();

		$("input[value='keyword']").click();
		$("#keyword_block").css('display', 'block');
		$("#channel_block").css('display', 'none');
		$("#keywords").val('');
		$("#channels").val('');

		$('#enable_auto_like').prop('checked', false); 

		$("#max_activity").val('5');
		// $("#delay").val('');

		$("input[value='date']").click();
		$("#date_block").css('display', 'block');
		$("#activity_block").css('display', 'none');
		$("#expire_date").val('');
		$("#expire_activity").val('');

		$("#create_campaign_submit").html('<i class="fa fa-save"></i> '+global_lang_campaign_create);

	}
	
	$(document).on('click', '.create_campaign', function(event) {
		event.preventDefault();
		
		clear_campaign_create_input_field();
		// $("#user_channel_id").prop('disabled',false);
		$('.hide_on_edit').show();
		$("#auto_like_comment_modal").modal();
	});


	$(document).on('click', 'input[value="keyword"]', function(event) {
		
		$("#keyword_block").css('display', 'block');
		$("#channel_block").css('display', 'none');
	});

	$(document).on('click', 'input[value="channel"]', function(event) {
		
		$("#keyword_block").css('display', 'none');
		$("#channel_block").css('display', 'block');
	});

	$(document).on('click', 'input[value="date"]', function(event) {
		
		$("#date_block").css('display', 'block');
		$("#activity_block").css('display', 'none');
	});

	$(document).on('click', 'input[value="no_of_activity"]', function(event) {
		
		$("#date_block").css('display', 'none');
		$("#activity_block").css('display', 'block');
	});


	$(document).on('click', '#create_campaign_submit', function(event) {
		event.preventDefault();

		$(this).addClass('btn-progress');
		var that = $(this);
		var form_data = new FormData($("#auto_like_comment_form")[0]);

		$.ajax({
			url: base_url+'responder/create_auto_like_comment_campaign',
			type: 'POST',
			dataType: 'json',
			data: form_data,
			chache: false,
			processData: false,
			contentType: false,
			success: function(response) {

				if (response.type == 'empty_field') 
					swal(global_lang_error, response.field , 'error');
				else if (response.type == 'failed')
					swal(global_lang_error, response.message , 'error');
				else if (response.type == 'success') {

					if (response.requested == 'add')
						swal(global_lang_success, global_lang_campaign_created_successfully , 'success');
					else if (response.requested == 'edit')
						swal(global_lang_success, global_lang_campaign_updated_successfully , 'success');
					
					table.draw();
					// location.reload();
					$("#auto_like_comment_modal").modal('hide');
					
				}

				$(that).removeClass('btn-progress');


				// console.log(response);
			}
		});
		
	});


	$(document).on('click', '.edit_campaign', function(event) {
		event.preventDefault();
		
		$(this).addClass('btn-warning btn-progress');
		$(this).children('i').removeClass('fas fa-edit');
		$(this).removeClass('btn-outline-warning');
		var that = $(this);
		var campaign_id = $(this).attr('campaign_id');
		$('.hide_on_edit').hide();
		// $(this).removeClass('btn-progress');
		
		$.ajax({
			url: base_url+'responder/edit_auto_like_comment_campaign_data',
			type: 'POST',
			dataType: 'json',
			data: {campaign_id: campaign_id},
			success: function(response) {


				if (response.result_status == 'success') {

					$("#submit_type").val('edit');
					$("#campaign_id_on_modal").val(response[0].id);

					$("#campaign_name").val(response[0].campaign_name);
					$("#auto_comment_template").val(response[0].auto_comment_template_id).change();
					$("#user_channel_id").val(response[0].channel_id).change();

					if (response[0].keyword_or_channel == 'keyword') {

						$("input[value='keyword']").click();
						$("#keyword_block").css('display', 'block');
						$("#channel_block").css('display', 'none');
						$("#keywords").val(response[0].keywords);
						$("#channels").val('');
					}
					else if (response[0].keyword_or_channel == 'channel') {

						$("input[value='channel']").click();
						$("#keyword_block").css('display', 'none');
						$("#channel_block").css('display', 'block');
						$("#keywords").val('');
						$("#channels").val(response[0].channels);
					}

					if (response[0].auto_like == '1')
						$('#enable_auto_like').prop('checked', true); 

					$("#max_activity").val(response[0].max_activity_per_day);
					// $("#delay").val(response[0].delay_per_activity);

					if (response[0].expire_type == 'date') {

						$("input[value='date']").click();
						$("#date_block").css('display', 'block');
						$("#activity_block").css('display', 'none');
						$("#expire_date").val(response[0].expire_date);
						$("#expire_activity").val('');
					}
					else if (response[0].expire_type == 'no_of_activity') {

						$("input[value='no_of_activity']").click();
						$("#date_block").css('display', 'none');
						$("#activity_block").css('display', 'block');
						$("#expire_date").val('');
						$("#expire_activity").val(response[0].campaign_expire_max_activity);
					}

					$("#create_campaign_submit").html('<i class="fa fa-save"></i> '+global_lang_campaign_edit);
					$("#auto_like_comment_modal").modal();
				}
				else if (response.result_status == 'failed') 
					swal(global_lang_error, global_lang_something_went_wrong, 'error');

				$(that).removeClass('btn-warning btn-progress');
				$(that).children('i').addClass('fas fa-edit');
				$(that).addClass('btn-outline-warning');
				
			}
		});
		
	});
	var table1 = '';

	$(document).on('click', '.view_report', function(event) {
		event.preventDefault();


		$(this).addClass('btn-info btn-progress');
		$(this).removeClass('btn-outline-info');
		var that = $(this);

		setTimeout(function() {
		    $(that).removeClass('btn-info btn-progress');
		    $(that).addClass('btn-outline-info');
		},1000);
		var campaign_id = $(this).attr('campaign_id');
		$("#auto_like_comment_campagin_id").val(campaign_id);
	    $("#auto_like_comment_report_modal").modal();

	    if (table1 == '') {

	    	setTimeout(function () {

		    	table1 = $("#myReportTable").DataTable({
		    	    serverSide: true,
		    	    processing:true,
		    	    bFilter: true,
		    	    order: [[ 6, "desc" ]],
		    	    pageLength: 10,

		    	    ajax: {
		    	        "url": base_url+'responder/auto_like_comment_campaign_report',
		    	        "type": 'POST',
		    	        data: function ( d )
		    	        {
		    	            d.auto_like_comment_campagin_id = $('#auto_like_comment_campagin_id').val();
		    	        } 
		    	    },
		    	    language: 
		    	    {
		    	      url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
		    	    },
		    	    dom: '<"top"f>rt<"bottom"lip><"clear">',
		    	    columnDefs: [
		    	      {
		    	          targets: '',
		    	          className: 'text-center'
		    	      },
		    	      {
		    	          targets: [0],
		    	          sortable: false
		    	      }
		    	     
		    	    ],
				    fnInitComplete:function(){ // when initialization is completed then apply scroll plugin
				    	if(areWeUsingScroll)
				    	{
				    		if (perscroll1) perscroll1.destroy();
				    		perscroll1 = new PerfectScrollbar('#myReportTable_wrapper .dataTables_scrollBody');
				    	}
				    },
				    scrollX: 'auto',
				    fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
				    	if(areWeUsingScroll)
				    	{ 
				    		if (perscroll1) perscroll1.destroy();
				    		perscroll1 = new PerfectScrollbar('#myReportTable_wrapper .dataTables_scrollBody');
				    	}
				    }
		    	});
	    	}, 500);
	    	
	    }
	    else {
	    	setTimeout(function () {
	    		table1.draw();
	    	}, 500);
	    	
	    }
		
	
		
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
              $(this).addClass('btn-danger');
              $(this).addClass('btn-progress');
              $(this).removeClass('btn-outline-danger');

              var campaign_id = $(this).attr('campaign_id');

              $.ajax({
                context: this,
                type:'POST' ,
                url: base_url+"responder/delete_auto_like_comment_campaign",
                dataType: 'json',
                data:{campaign_id : campaign_id},
                success:function(response){ 

                   $(this).parent().prev().removeClass('btn-progress');
                   if(response.status == '1')
                   iziToast.success({title: global_lang_success,message: response.message,position: 'bottomRight'});
                   else
                   iziToast.error({title: global_lang_error,message: response.message,position: 'bottomRight'});
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
        var table_name = "auto_like_comment";
        var that = this;
        show_error_response(campaign_id,table_name,that);	          
	});

	$(document).on('click', '.show_error_report', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "auto_like_comment_campaign_prepared";
        var that = this;
        show_error_response(campaign_id,table_name,that);	          
	});

	$(document).on('click', '.view_comment', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "auto_like_comment_campaign_prepared";
        var that = this;
        show_error_response(campaign_id,table_name,that,'comment_text','info');	          
	});	


	$('.datetimepicker2').daterangepicker({
		locale: {format: 'YYYY-MM-DD hh:mm'},
		singleDatePicker: true,
		timePicker: true,
		timePicker24Hour: true,
		drops: "up"
	});



});
