"use strict";
setTimeout(function(){ 

	$('#date_range').daterangepicker({
		ranges: {
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month' : [moment().startOf('month'), moment().endOf('month')],
			'Last Month' : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate : moment()
	}, function (start, end) {
		$('#date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
	});
}, 2000);
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
	        "url": base_url+'responder/auto_channel_subscription_campaigns',
	        "type": 'POST'
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
	          targets: '',
	          className: 'text-center'
	      },
	      {
	          targets: [0,4,7],
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
	      	     	var string = '<div style="width:180px;"><a class="btn btn-circle btn-outline-info view_report" href="#" data-toggle="tooltip" title="'+global_lang_report+'" campaign_id="' + row[1] +'"><i class="fas fa-eye"></i></a>&nbsp;';
	      	     	string += '<a class="btn btn-circle btn-outline-warning edit_campaign" href="#" data-toggle="tooltip" title="'+global_lang_edit+'" campaign_id="' + row[1] +'"><i class="fas fa-edit"></i></a>&nbsp;';
	      	     	string += '<a class="btn btn-circle btn-outline-danger delete_campaign" href="#" data-toggle="tooltip" title="'+global_lang_delete+'" campaign_id="' + row[1] +'"><i class="fas fa-trash-alt"></i></a>&nbsp;';
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
		$("#user_channel_id").val("0").change();

		$("#keywords").val('');
		$("#date_range_val").val('');

		$('#enable_auto_unsubscribe').prop('checked', false); 

		$("#max_activity").val('5');
		$("#delay").val('');

		$("input[value='date']").click();
		$("#date_block").css('display', 'block');
		$("#activity_block").css('display', 'none');
		$("#expire_date").val('');
		$("#expire_activity").val('');

		$("#create_campaign_submit").html('<i class="fas fa-save"></i> '+global_lang_campaign_create);

	}


	
	$(document).on('click', '.create_campaign', function(event) {
		event.preventDefault();
		
		clear_campaign_create_input_field();
		// $("#user_channel_id").prop('disabled',false);
		$('.hide_on_edit').show();
		$("#auto_subscription_modal").modal();
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
		var form_data = new FormData($("#auto_subscription_form")[0]);

		$.ajax({
			url: base_url+'responder/create_auto_channel_subscription_campaign',
			type: 'POST',
			dataType: 'json',
			data: form_data,
			chache: false,
			processData: false,
			contentType: false,
			success: function(response) {

				// console.log(response);

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
					$("#auto_subscription_modal").modal('hide');

					
				}

				$(that).removeClass('btn-progress');


				// console.log(response);
			}
		});
		
	});

	$(document).on('click', '.show_error', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "auto_channel_subscription";
        var that = this;
        show_error_response(campaign_id,table_name,that);	          
	});

	$(document).on('click', '.show_error_report', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "auto_channel_subscription_prepared";
        var that = this;
        show_error_response(campaign_id,table_name,that);	          
	});



	$(document).on('click', '.edit_campaign', function(event) {
		event.preventDefault();
		
		$(this).addClass('btn-warning btn-progress');
		$(this).children('i').removeClass('fas fa-edit');
		$(this).removeClass('btn-outline-warning');
		var campaign_id = $(this).attr('campaign_id');
		$('.hide_on_edit').hide();
		
		$.ajax({
			url: base_url+'responder/edit_auto_channel_subscription_campaign_data',
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

					$("#campaign_name").val(response[0].campaign_name);
					$("#user_channel_id").val(response[0].channel_id).change();

					
					$("#keywords").val(response[0].keywords);
					$("#date_range").val(response[0].date_range);
					

					if (response[0].auto_unsubscribe == '1')
						$('#enable_auto_unsubscribe').prop('checked', true); 

					$("#max_activity").val(response[0].max_activity_per_day);
					$("#delay").val(response[0].delay_per_activity);

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
					$("#auto_subscription_modal").modal();
				}
				else if (response.result_status == 'failed') 
					swal(global_lang_error, global_lang_something_went_wrong, 'error');

				
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
                url:base_url+"responder/delete_auto_channel_subscription_campaign",
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
		
		$("#auto_subscribe_unsubscribe_campagin_id").val(campaign_id);
	    $("#auto_subscribe_unsubscribe_modal").modal();


	    if (table1 == '') {

	    	setTimeout(function () {

		    	table1 = $("#myReportTable").DataTable({
		    	    serverSide: true,
		    	    processing:true,
		    	    bFilter: true,
		    	    order: [[ 2, "desc" ]],
		    	    pageLength: 10,

		    	    ajax: {
		    	        "url": base_url+'responder/auto_subscribe_unsubcribe_channel_report',
		    	        "type": 'POST',
		    	        data: function ( d )
		    	        {
		    	            d.auto_subscribe_unsubscribe_campagin_id = $('#auto_subscribe_unsubscribe_campagin_id').val();
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
		    	          targets: [0,3,5],
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

	$('.datetimepicker2').daterangepicker({
		locale: {format: 'YYYY-MM-DD hh:mm'},
		singleDatePicker: true,
		timePicker: true,
		timePicker24Hour: true,
		drops: "up"
	});


	$(document).on('click', '.subscribe_unsubscribe', function(event) {
		event.preventDefault();
		var action = $(this).attr("action");
		var title = auto_subscription_unsubscribe_channel;
		var desc = auto_subscription_unsubscribe_confirmation;
		var next_class = 'btn btn-outline-success btn-circle subscribe_unsubscribe';
		var next_icon = 'fas fa-user-check';
		var next_title = auto_subscription_subscribe;
		var next_action = 'subscribe';

		if(action=="subscribe")
		{
			var title = auto_subscription_subscribe_channel;
			var desc = auto_subscription_subscribe_confirmation;
			next_class = 'btn btn-outline-danger btn-circle subscribe_unsubscribe';
			next_icon = 'fas fa-user-slash';
			next_title = auto_subscription_unsubscribe;
			next_action = 'unsubscribe';
		}

		swal({
          title: title,
          text: desc,
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
	        if (willDelete) {

				let campaign_id = $(this).attr('campaign_id');

				$(this).attr('class','btn btn-circle btn-light btn-progress');

				$.ajax({
					context: this,
					url: base_url+'responder/subscribe_unsubscribe_channel',
					type: 'POST',
					dataType: 'json',
					data: {campaign_id: campaign_id,action: action},
					success: function (response) {
						// console.log(response);
						$(this).removeClass('btn-progress');

						if (response.status == 'success') {
							swal(global_lang_success,  response.message , 'success');
						} else if (response.status == 'error') {
							swal(global_lang_error,  response.message , 'error');
						}
						$(this).attr('class',next_class);
						$(this).attr('title',next_title);
						$(this).attr('action',next_action);
						$(this).children('i').attr('class',next_icon);
					}
				});
	        }
		
	    });
		
	});


});
