<script type="text/javascript">
	"use strict";
	var drop_menu = '<?php echo $drop_menu;?>';
	setTimeout(function(){ 
	  $("#mytable_filter").append(drop_menu); 
	  $('#date_range').daterangepicker({
	    ranges: {
	      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	      'This Month'  : [moment().startOf('month'), moment().endOf('month')],
	      'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	    },
	    startDate: moment().subtract(29, 'days'),
	    endDate  : moment()
	  }, function (start, end) {
	    $('#date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
	  });
	}, 2000);
</script>
<script src="<?php echo base_url('assets/js/system/channel_search.js');?>"></script>