<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-dharmachakra"></i> <?php echo $this->lang->line('Video Link Wheel'); ?></h1>
		<div class="section-header-button">
			<a href="<?php echo base_url('social_accounts/video_manager') ?>" class="btn btn-primary create_campaign"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Create Campaign'); ?></a>
	    </div>
		<div class="section-header-breadcrumb">
		 <div class="breadcrumb-item"><?php echo $this->lang->line('Reporting'); ?></div>
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Video Link Wheel'); ?></div>
		</div>
	</div>


	<div class="section-body">
	    <div class="card">
	      <div class="card-body data-card">
	        <div class="table-responsive2">
	          <table class="table table-bordered" id="mytable">
	            <thead>
	              <tr>
	                <th>#</th>
	                <th></th>
	                <th><?php echo $this->lang->line("Wheel Name"); ?></th>      
	                <th><?php echo $this->lang->line("Wheel Type"); ?></th>
	                <th><?php echo $this->lang->line("Status"); ?></th>
	                <th><?php echo $this->lang->line("Last Updated"); ?></th>
	                <th class="min_width_100px"><?php echo $this->lang->line("Actions"); ?></th>
	              </tr>
	            </thead>
	            <tbody>
	            </tbody>
	          </table>
	        </div>             
	      </div>

	    </div>

	</div>

</section>


<script src="<?php echo base_url('assets/js/system/link_wheel.js');?>"></script>


