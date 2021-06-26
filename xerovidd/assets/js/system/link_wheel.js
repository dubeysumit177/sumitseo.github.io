"use strict";
$(document).ready(function() {

	var perscroll;
	var perscroll1;

	var table = $("#mytable").DataTable({
	    serverSide: true,
	    processing:true,
	    bFilter: true,
	    order: [[ 1, "desc" ]],
	    pageLength: 10,
	    ajax: 
	    {
	        "url": base_url+'link_wheel/link_wheel_campaign_data',
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
	          targets: [3,4,5,6],
	          className: 'text-center'
	      },
	      {
	          targets: [6],
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

	$(document).on('click', '.show_error', function(event) {
		event.preventDefault();
        $(this).addClass('btn-progress');
        var campaign_id = $(this).attr('campaign_id');
        var table_name = "youtube_link_wheel";
        var that = this;
        show_error_response(campaign_id,table_name,that);	          
	});


	$(document).on('click', '.remove_wheel', function(event) {
		event.preventDefault();
		
		swal({
          title: link_wheel_lang_remove_wheel,
          text: global_lang_campaign_delete_confirmation,
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) 
          {
              $(this).removeClass('btn-outline-danger');
              $(this).addClass('btn-danger');
              $(this).addClass('btn-progress');

              var href = $(this).attr('href');

              $.ajax({
                context: this,
                type:'POST' ,
                url:href,
                dataType: 'json',
                data:{},
                success:function(response){ 
                   $(this).removeClass('btn-progress');
                   $(this).removeClass('btn-danger');
                   $(this).addClass('btn-outline-danger');
                   if(response.status == '1') swal(global_lang_success, response.message, 'success');
                   else swal(global_lang_error, response.message, 'error');                   

                   table.draw();
                }
              });
          } 
        });
	});	


});
