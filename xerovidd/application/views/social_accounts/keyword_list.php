<section class="section">
	<div class="section-header">
		<h1><i class="far fa fa-trophy"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-button">
		<a href="#" class="btn btn-primary create_campaign"><i class="fas fa-plus-circle"></i> <?php echo $this->lang->line('Create Campaign'); ?></a>
	    </div>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Reporting'); ?></div>
		  <div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>


	<div class="section-body">




		<div class="row">
		  <div class="col-12 col-lg-12">
		    <div class="card">
		      <div class="card-body data-card">
		        <div class="table-responsive2">
		          <table class="table table-bordered" id="mytable">
		            <thead>
		              <tr>
		                <th>#</th>      
		                <th><?php echo $this->lang->line("ID"); ?></th>      
		                <th class="width_180px"><?php echo $this->lang->line("Name"); ?></th>      
		                <th><?php echo $this->lang->line("Keyword"); ?></th>      
		                <th><?php echo $this->lang->line("Video ID"); ?></th>
		                <th><?php echo $this->lang->line("Add Date"); ?></th>
		                <th class="width_100px"><?php echo $this->lang->line("Play"); ?></th>
		                <th class="width_180px"><?php echo $this->lang->line("Actions"); ?></th>
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

<div class="modal fade" id="rank_track_set_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content bg_fefefe">
	    <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('Youtube Video Tracking Settings'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">
	        <form id="rank_tracker_set_form" action="" method="post">
				
				      
 
				  <div class="form-group">
				  	<label><?php echo $this->lang->line('Keyword'); ?></label>
				  	<input type="text" class="form-control" name="keyword" id="keyword">
				  </div>
				  <div class="form-group">
				  	<label><?php echo $this->lang->line('Name'); ?></label>
				  	<input type="text" class="form-control" name="name" id="name">
				  </div>
				  <div class="form-group">
				  	<label><?php echo $this->lang->line('Video ID'); ?></label>
				  	<input type="text" class="form-control" name="video_id" id="video_id">
				  </div>

				  <div class="form-group">
                    <div class="control-label"><?php echo $this->lang->line('Show in dashboard '); ?>&nbsp;<small><?php echo $this->lang->line(" only one video will be shown on dashboard"); ?></small></div>
                    <label class="custom-switch mt-2">
                      <input type="checkbox" name="mark_for_dashboard" class="custom-switch-input">
                      <span class="custom-switch-indicator"></span>
                      <span class="custom-switch-description"><?php echo $this->lang->line("Mark it"); ?></span>
                    </label>
                  </div>	


	

	        </form>
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
	        <button id="rank_submit" type="button" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> <?php echo $this->lang->line('Save'); ?></button>
	    </div>
    </div>
  </div>
</div>


<div class="modal fade" id="rank_track_report_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg_fefefe">
	    <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('YouTube Rank Tracking Report'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body pb-0">
	        <form id="rank_tracker_report_form" action="" method="post">
				
				<input type="hidden" id="keyword_id" name="keyword">      
 					
 					<div class="row">
 						<div class="form-group col-12 col-md-6">
 							
 							<input type="text" class="form-control datepicker" autocomplete="off" name="from_date" id="from_date" placeholder="<?php echo $this->lang->line('From date'); ?>">
 						</div>

 						<div class="form-group col-md-6 col-12">
 						
 							<input type="text" class="form-control datepicker" autocomplete="off" name="to_date" id="to_date" placeholder="<?php echo $this->lang->line('To date'); ?>">
 						</div>
 					</div>
	        </form>
	        <div id="rankReportVideoReplyResponseDiv"></div>
	        <div id="rank_report"></div>
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
	        <button id="search_rank" type="button" class="btn btn-primary btn-lg"><i class="fas fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
	    </div>
    </div>
  </div>
</div>




<script src="<?php echo base_url('assets/js/system/keyword_list.js');?>"></script>


