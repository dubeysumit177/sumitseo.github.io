<link rel="stylesheet" href="<?php echo base_url('assets/css/system/select2_100.css');?>">
<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line('Scheduled Video Uploader'); ?></h1>
		<div class="section-header-button">
		<a href="<?php echo base_url('social_accounts/upload_video'); ?>" class="btn btn-primary"><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line('Upload New Video'); ?></a>
	    </div>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><a href="<?php echo base_url("social_accounts/channel_manager"); ?>"><?php echo $this->lang->line('Channel Manager'); ?></a></div>
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Video Uploader'); ?></div>
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
		                <th class="width_180px"><?php echo $this->lang->line("Channel ID"); ?></th>      
		                <th><?php echo $this->lang->line("Video Link"); ?></th>      
		                <th><?php echo $this->lang->line("Title"); ?></th>
		                <th><?php echo $this->lang->line("Time Zone"); ?></th>
		                <th><?php echo $this->lang->line("Scheduled Time"); ?></th>
		                <th class="width_100px"><?php echo $this->lang->line("Status"); ?></th>
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

<div class="modal fade" role="dialog" id="scheduled_video_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	    <div class="modal-header">
	        <h5 class="modal-title"><?php echo $this->lang->line('Scheduled Video Edit'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body" id="auto_like_comment_modal_body">
	        
	    	<form action="" method="post" id="schedule_video_edit_form">
	    		
		        <input type="hidden" name="submit_type" value="add" id="submit_type">
		        <input type="hidden" name="campaign_id" id="campaign_id_on_modal">

		        <div class="row">
		        	<div class="col-12">
		        		<div class="form-group">
		        			<label for="title"> <?php echo $this->lang->line("Video title")?> </label>
		        			<input id="title" name="title" class="form-control" type="text">
		        		</div>
		        	</div>

		        </div>

		        <div class="row">
		        	<div class="col-12">
		        		<div class="form-group">
		        			<label for="description"> <?php echo $this->lang->line("Video description")?> </label>
		        			<textarea id="description" name="description" class="form-control inputtags" spellcheck="false"></textarea>
		        		</div>
		        	</div>

		        </div>



		        <div class="row">
		        	<div class="col-12 col-md-6">
		        		<div class="form-group">
		        			<label for="channel_id"> <?php echo $this->lang->line("Channel")?> </label>
		        			<?php 

		        			echo form_dropdown('channel_id', $channel_dropdown, '', 'disabled id="channel_id" class="form-control select2"'); ?>
		        		</div>
		        	</div>
		        	<div class="col-12 col-md-6">
		        		<div class="form-group">
		        			<label for="category"> <?php echo $this->lang->line("Video category")?> </label>
		                	<?php
					          	$get_video_category_list[''] = 'Please Select ';
					          	echo form_dropdown('category',$get_video_category_list,'',' class="form-control select2" id="category"'); 
					          ?>
		        		</div>
		        	</div>
		        </div>

		        <div class="row">
		        	<div class="col-12 col-md-6">
		        		<div class="form-group">
		        			<label for="time_zone">  <?php echo $this->lang->line('Time Zone') ?> </label>
		                	<?php
					          	$time_zone_list[''] = 'Please Select ';
					          	echo form_dropdown('time_zone',$time_zone_list,set_value('time_zone'),' class="form-control select2" id="time_zone"'); 
					          ?>
		        		</div>
		        	</div>
		        	<div class="col-12 col-md-6">
		        		<div class="form-group">
		        			<label for="tags"> <?php echo $this->lang->line("Video tags")?> <small><?php echo $this->lang->line("comma separeted"); ?></small> </label>
		        			<input id="tags" name="tags" class="form-control" type="text">
		        		</div>
		        	</div>
		        </div>

				<div class="row">
				  <div class="col-12 col-md-6">
				    <div class="form-group">
				    	<label class="form-label"><?php echo $this->lang->line('Privacy Type'); ?></label>
				    	<div class="selectgroup selectgroup-pills">
				    		<label class="selectgroup-item">
				    			<input type="radio" name="video_type" value="public" id="video_type1" class="selectgroup-input">
				    			<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('public'); ?>"> <?php echo $this->lang->line('public'); ?></span>
				    		</label>
				    		<label class="selectgroup-item">
				    			<input type="radio" name="video_type" value="private"  id="video_type2" class="selectgroup-input">
				    			<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Private'); ?>"> <?php echo $this->lang->line('Private'); ?></span>
				    		</label>
				    		<label class="selectgroup-item">
				    			<input type="radio" name="video_type" value="unlisted" id="video_type3" class="selectgroup-input">
				    			<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('unlisted'); ?>"> <?php echo $this->lang->line('Unlisted'); ?></span>
				    		</label>

				    	</div>
				    </div> 
				  </div>

				  <div class="col-12 col-md-6">

				    <div class="form-group" id="date_block">
				    	<label for="expire_date"> <?php echo $this->lang->line("Schedule Time")?> <small></label>
				    	<input id="schedule_time" name="schedule_time" class="form-control datetimepicker2" type="text">
				    </div>  

				    
				  </div>             
				</div>
	        </form>

	    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
        <button id="create_campaign_submit" type="button" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> <?php echo $this->lang->line('Update Schedule Video'); ?></button>
      </div>
    </div>
  </div>
</div>



<script src="<?php echo base_url('assets/js/system/upload_video_list.js');?>"></script>


