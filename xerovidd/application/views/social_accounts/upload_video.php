<?php $this->load->view("include/upload_js");?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/system/upload_video.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/system/select2_100.css');?>">



<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-cloud-download-alt"></i> <?php echo $page_title;?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="<?php echo base_url("social_accounts/channel_manager"); ?>"><?php echo $this->lang->line('Channel Manager'); ?></a></div>
			<div class="breadcrumb-item"><a href="<?php echo base_url("social_accounts/upload_video_list"); ?>"><?php echo $this->lang->line('Video Uploader'); ?></a></div>
			<div class="breadcrumb-item"><?php echo $page_title;?></div>
		</div>
	  </div>
</section>


<?php 
if($no_app_error!="") echo $no_app_error;
else if(empty($channel_info))
{ ?>
	 
<div class="card" id="nodata">
  <div class="card-body">
    <div class="empty-state">
      <img class="img-fluid height_200px" src="<?php echo base_url('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
      <h2 class="mt-0"><?php echo $this->lang->line("We could not find any data.") ?></h2>
      <a href="<?php echo base_url('social_accounts/index'); ?>" class="btn btn-outline-primary mt-4"><i class="fa fa-cloud-download-alt"></i> <?php echo $this->lang->line("Import Account");?></a>
    </div>
  </div>
</div>

<?php 
}
else
{ ?>
<form method="POST" id="video_search_form_data">
	<div class="row multi_layout">
		
		<div class="col-12 col-md-5 col-lg-4 collef">
		
			<div class="card main_card">
				
				<div class="card-header">
					<div class="col-6 padding-0">
						<h4><i class="fas fa-newspaper"></i> <?php echo $this->lang->line("Channels"); ?></h4>
					</div>
					<div class="col-6 padding-0">            
						<input type="text" class="form-control float-right" id="search_channel_list" onkeyup="search_in_ul(this,'channel_list_ul')" autofocus placeholder="<?php echo $this->lang->line('Search...'); ?>">
					</div>
				</div>
				<div class="card-body padding-0">
					<div class="makeScroll">
						<ul class="list-group" id="channel_list_ul">
							<?php $i=0; foreach($channel_info as $value) { ?> 
								<li  class="list-group-item <?php if($i==0) echo 'active'; ?> channel_list_item" channel_table_id="<?php echo $value['id']; ?>" channel_id="<?php echo $value['channel_id']; ?>">
									<div class="row">
										<div class="col-3 col-md-2"><img width="45px" class="rounded-circle" src="<?php echo $value['profile_image']; ?>"></div>
										<div class="col-9 col-md-10">
											<h6 class="channel_name"><?php echo $value['title']; ?></h6>
											<span class="gray"><?php echo $value['channel_id']; ?></span>
											
										</div>
									</div>
								</li> 
								<?php $i++; } ?>                
							</ul>
						</div>
				</div>
			</div>          
		</div>

		<div class="col-12 col-md-7 col-lg-4 colmid">
		
			<div class="card main_card">
				<div class="card-header p-2">
					<div class="col-12 padding-0">
						<h4><i class="fas fa-video"></i> <?php echo $this->lang->line("Video Property"); ?></h4>
					</div>
				
				</div>
				<div class="card-body p-2">
					
					<div class="form-group">
						<label class="form-label"><?php echo $this->lang->line('Video Title'); ?></label>
						<input type="text" class="form-control" name="title" id="title" value="<?php echo set_value('title'); ?>" placeholder="<?php echo $this->lang->line('Video Title...'); ?>">
					 </div>

					<div class="form-group">
	                  <label for="video"> <?php echo $this->lang->line('Video Description'); ?></label>
	                  <textarea id="description" name="description" class="form-control inputtags" spellcheck="false"><?php echo set_value('description'); ?></textarea>
	                </div>

	                <div class="form-group">
	                	<label class="form-label"><?php echo $this->lang->line('Video Tags'); ?></label>
	                	<input type="text" class="form-control inputtags" name="tags" id="tags" value="<?php echo set_value('tags') ?>" placeholder="<?php echo $this->lang->line('Example : needed,HD,High'); ?>">
	                </div>

	                <div class="form-group">
	                	<label class="form-label"> <?php echo $this->lang->line('Video Category') ?></label>
	                	<?php
				          	$get_video_category_list[''] = 'Please Select ';
				          	echo form_dropdown('category',$get_video_category_list,set_value('category'),' class="form-control select2" id="category"'); 
				          ?>
	                </div>

	                <div class="form-group">
	                 	<label class="form-label"><?php echo $this->lang->line('Privacy Type'); ?></label>
	                 	<div class="selectgroup selectgroup-pills">
	                 		<label class="selectgroup-item">
	                 			<input type="radio" name="video_type" value="public" id="video_type" class="selectgroup-input">
	                 			<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('public'); ?>"> <?php echo $this->lang->line('public'); ?></span>
	                 		</label>
	                 		<label class="selectgroup-item">
	                 			<input type="radio" name="video_type"  value="private" id="video_type" class="selectgroup-input">
	                 			<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Private'); ?>"> <?php echo $this->lang->line('Private'); ?></span>
	                 		</label>
	                 		<label class="selectgroup-item">
	                 			<input type="radio" name="video_type"  value="unlisted" id="video_type" class="selectgroup-input">
	                 			<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('unlisted'); ?>"> <?php echo $this->lang->line('Unlisted'); ?></span>
	                 		</label>

	                 	</div>
	                </div>



				</div>

			</div>

		</div>

		<div class="col-12 col-md-12 col-lg-4 colend padding-0">
			<div class="card main_card">
				<div class="card-header p-2">
					<div class="col-12 padding-0">
						<h4><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line("Video Upload"); ?></h4>
					</div>
				
				</div>



				<div class="card-body p-2">
					<div class="form-group">
                    	<label class="form-label"> <?php echo $this->lang->line('Time Zone') ?></label>
                    	<?php
    			          	$time_zone_list[''] = $this->lang->line('Select');
    			          	echo form_dropdown('time_zone',$time_zone_list,$this->config->item("time_zone"),' class="form-control select2" id="time_zone"'); 
    			          ?>
                    </div>

                    <div class="form-group">
                      <label class="form-label"> <?php echo $this->lang->line('Schedule Time') ?></label>
                      <input type="text" class="form-control datepicker_x" name="schedule_time" id="schedule_time" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    </div>
					
					<div class="form-group">
						<label class="form-label"> <?php echo $this->lang->line('Video File') ?></label>
						    <div id="dropzone" class="dropzone dz-clickable">
						        <div class="dz-default dz-message">
						        	<input class="form-control" name="video_url" id="video_url" placeholder="" type="hidden">
						            <span class="font_size_20px"><i class="fas fa-cloud-upload-alt font_size_35px c6777ef_color"></i> <?php echo $this->lang->line('Upload'); ?></span>
						        </div>
						     </div>
					</div>
                	
				</div>

				<div class="card-footer p-2">
					<button class="btn btn-primary btn-lg" id="submit_btn" type="submit"><i class="fa fa-save"></i> <?php echo $this->lang->line("Create Campaign");?></button>
					<button class="btn btn-secondary btn-lg float-right" onclick="goBack('social_accounts/upload_video_list')" type="button"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Cancel"); ?></button>

				</div>

			</div>

			
		</div>
		
	</div>
</form>

<?php } ?>

<script src="<?php echo base_url('assets/js/system/upload_video.js');?>"></script>
