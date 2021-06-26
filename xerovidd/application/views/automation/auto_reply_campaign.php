<link rel="stylesheet" href="<?php echo base_url('assets/css/system/auto_reply_campaign.css');?>">
<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fas fa-reply-all"></i> <?php echo $this->lang->line('Auto Reply Campaign'); ?></h1>
		<div class="section-header-button">
			<a href="<?php echo base_url('social_accounts/video_manager') ?>" class="btn btn-primary create_campaign"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Create Campaign'); ?></a>
	    </div>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Reporting'); ?></div>
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Auto Reply Campaign'); ?></div>
		</div>
	</div>

	<div class="section-body">
		<div class="row">
		  <div class="col-12">
		    <div class="card">

		      <div class="card-body data-card">            
		        <div class="table-responsive2">
		          <table class="table table-bordered" id="mytable">
		            <thead>
		              <tr>
		                <th>#</th>      
		                <th><?php echo $this->lang->line("ID"); ?></th>      
		                <th><?php echo $this->lang->line("Campaign Name"); ?></th>      
		                <th><?php echo $this->lang->line("Video ID"); ?></th>      
		                <th><?php echo $this->lang->line("Channel"); ?></th>      
		                <th><?php echo $this->lang->line("Status"); ?></th>
		                <th><?php echo $this->lang->line("Last Proccesed at"); ?></th>
		                <th class="min_width_230px"><?php echo $this->lang->line("Actions"); ?></th>
		              </tr>
		            </thead>
		            <tbody>
		            </tbody>
		          </table>
		        </div>             
		      </div>

		    </div>
		  </div>
		</div>
	</div>
</section>


<div class="modal fade" id="set_auto_reply_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg_fefefe">
	    <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('Set Auto Reply'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">
	        <form id="auto_reply_create_campaign" action="" method="post">
				
				<input type="hidden" id="video_id" name="video_id">      
				<input type="hidden" id="channel_id" name="channel_id">      
				<input type="hidden" id="submit_type" name="submit_type">      

				<div class="set_auto_reply_info_block">
					
				</div>

	        </form>
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close"); ?></button>
	        <button id="create_campaign" type="button" class="btn btn-primary btn-lg"><?php echo $this->lang->line('Create Campaign'); ?></button>
	    </div>
    </div>
  </div>
</div>


<div class="modal fade" id="report_details_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg_fefefe">
	    <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('Report Details'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">

			<div class="report_details_body">

				<div class="row">
				  <div class="col-12">
				    <div class="card no_shadow">

				      <div class="card-body data-card p-0">            
				        <div class="table-responsive2">
				          <table class="table table-bordered" id="myReportTable">
				          	<input type="hidden" id="report_campagin_id" name="report_campagin_id">
				            <thead>
				              <tr>
				                <th>#</th>
				                <th><?php echo $this->lang->line("ID"); ?></th>
				                <th><?php echo $this->lang->line("Comment Author"); ?></th>
				                <th><?php echo $this->lang->line("Offensive"); ?></th>
				                <th><?php echo $this->lang->line("Status"); ?></th>
				                <th><?php echo $this->lang->line("Replied at"); ?></th>
				                <th class="min_width_130px"><?php echo $this->lang->line("Actions"); ?></th>
				              </tr>
				            </thead>
				            <tbody>
				            </tbody>
				          </table>
				        </div>             
				      </div>

				    </div>
				  </div>
				</div>
			</div>

	        
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close"); ?></button>
	    </div>
    </div>
  </div>
</div>


<?php include("application/views/automation/auto_reply_campaign_js.php"); ?>