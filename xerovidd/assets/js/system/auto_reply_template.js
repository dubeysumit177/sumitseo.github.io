"use strict";
$(document).ready(function() {
	
	var perscroll;
	var perscroll1;
	var table = $("#mytable").DataTable({
	    serverSide: true,
	    processing:true,
	    bFilter: true,
	    order: [[ 2, "desc" ]],
	    pageLength: 10,
	    ajax: {
	        "url": base_url+'responder/auto_reply_template_data',
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
	          targets: [1,3,4,5,7],
	          className: 'text-center'
	      },
	      {
	          targets: [0,3,7],
	          sortable: false
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

	$(document).on('click', '.delete_campaign', function(event) {
		event.preventDefault();

		swal({
		  	title: global_lang_are_you_sure,
		  	text: auto_reply_template_delete_confirmation,
		  	icon: "warning",
		  	buttons: true,
		  	dangerMode: true,
		})
		.then((willDelete) => {

			if (willDelete) {
			    
				let template_id = $(this).attr('template_id');
				$(this).addClass('btn-danger');
              	$(this).addClass('btn-progress');
                $(this).removeClass('btn-outline-danger');

				$.ajax({
					context: this,
					url: base_url+'responder/delete_auto_reply_template',
					type: 'POST',
					dataType: 'json',
					data: {template_id: template_id},
					success: function (response) {

						$(this).removeClass('btn-progress');

						if (response.status == 'success') {

							iziToast.success({
							    title: global_lang_success,
							    message: auto_reply_template_successfully_deleted,
							});
							
							table.draw();
						} else if (response.status == 'error') {

							iziToast.error({
							    title: global_lang_error,
							    message: auto_reply_template_delete_permission_denied,
							});
						}
					}
				});
			}
		});
		
	});
	
});
