<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-toolbox"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
			<div class="breadcrumb-item active"><a href="<?php echo base_url('admin/settings'); ?>"><?php echo $this->lang->line("Settings"); ?></a></div>
			<div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>

	<?php $this->load->view('admin/theme/message'); ?>

	<?php $save_button = '<div class="card-footer bg-whitesmoke">
	                      <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> '.$this->lang->line("Save").'</button>
	                      <button class="btn btn-secondary btn-lg float-right" onclick=\'goBack("admin/settings")\' type="button"><i class="fa fa-remove"></i> '. $this->lang->line("Cancel").'</button>
	                    </div>'; ?>
	
	<form class="form-horizontal text-c" enctype="multipart/form-data" action="<?php echo site_url().'admin/general_settings_action';?>" method="POST">	
		<div class="section-body">
			<div id="output-status"></div>
			<div class="row">
				<div class="col-md-8">					
					<div class="card" id="brand">

						<div class="card-header">
							<h4><i class="fas fa-flag"></i> <?php echo $this->lang->line("Brand"); ?></h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-globe"></i> <?php echo $this->lang->line("Application Name");?> </label>
										<input name="product_name" value="<?php echo $this->config->item('product_name');?>"  class="form-control" type="text">		          
										<span class="red"><?php echo form_error('product_name'); ?></span>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-compress"></i> <?php echo $this->lang->line("Application Short Name");?> </label>
										<input name="product_short_name" value="<?php echo $this->config->item('product_short_name');?>"  class="form-control" type="text">
										<span class="red"><?php echo form_error('product_short_name'); ?></span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for=""><i class="fas fa-tag"></i> <?php echo $this->lang->line("Slogan");?> </label>
								<input name="slogan" value="<?php echo $this->config->item('slogan');?>"  class="form-control" type="text">
								<span class="red"><?php echo form_error('slogan'); ?></span>
							</div>

							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-briefcase"></i> <?php echo $this->lang->line("Company Name");?></label>
										<input name="institute_name" value="<?php echo $this->config->item('institute_address1');?>"  class="form-control" type="text">	
										<span class="red"><?php echo form_error('institute_name'); ?></span>
									</div>
								</div>

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-map-marker"></i> <?php echo $this->lang->line("Company Address");?></label>
										<input name="institute_address" value="<?php echo $this->config->item('institute_address2');?>"  class="form-control" type="text">
										<span class="red"><?php echo form_error('institute_address'); ?></span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-envelope"></i> <?php echo $this->lang->line("Company Email");?> *</label>
										<input name="institute_email" value="<?php echo $this->config->item('institute_email');?>"  class="form-control" type="email">
										<span class="red"><?php echo form_error('institute_email'); ?></span>
									</div>  
								</div>

								<div class="col-12 col-md-6">	
									<div class="form-group">
										<label for=""><i class="fa fa-mobile"></i> <?php echo $this->lang->line("Company Phone");?></label>
										<input name="institute_mobile" value="<?php echo $this->config->item('institute_mobile');?>"  class="form-control" type="text">
										<span class="red"><?php echo form_error('institute_mobile'); ?></span>
									</div>
								</div>
							</div>
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="preference">
						<div class="card-header">
							<h4><i class="fas fa-tasks"></i> <?php echo $this->lang->line("Preference"); ?></h4>
						</div>
						<div class="card-body">

				            <div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
						             	<label for=""><i class="fa fa-language"></i> <?php echo $this->lang->line("Language");?></label>            			
				               			<?php
										$select_lan="english";
										if($this->config->item('language')!="") $select_lan=$this->config->item('language');
										echo form_dropdown('language',$language_info,$select_lan,'class="form-control select2" id="language"');  ?>		          
				             			<span class="red"><?php echo form_error('language'); ?></span>
						            </div>
						        </div>

						        <div class="col-12 col-md-6">
						            <div class="form-group">
						             	<label for=""><i class="fa fa-clock-o"></i> <?php echo $this->lang->line("Time Zone");?></label>          			
				               			<?php	$time_zone['']=$this->lang->line('Time Zone');
										echo form_dropdown('time_zone',$time_zone,$this->config->item('time_zone'),'class="form-control select2" id="time_zone"');  ?>		          
				             			<span class="red"><?php echo form_error('time_zone'); ?></span>
						            </div>
						        </div>
					        </div>						
						   

				            <div class="form-group">
				             	<label for="email_sending_option"><i class="fa fa-at"></i> <?php echo $this->lang->line('Email Sending Option');?></label> 
		               			<?php
		               			$email_sending_option= $this->config->item('email_sending_option');
		               			if($email_sending_option == '') $email_sending_option = 'php_mail';
		               			?>
								<div class="row">
									<div class="col-12 col-md-6">
										<label class="custom-switch">
										  <input type="radio" name="email_sending_option" value="php_mail" class="custom-switch-input" <?php if($email_sending_option=='php_mail') echo 'checked'; ?>>
										  <span class="custom-switch-indicator"></span>
										  <span class="custom-switch-description"><?php echo $this->lang->line('Use PHP Email Function'); ?></span>
										</label>
									</div>
									<div class="col-12 col-md-6">
										<label class="custom-switch">
										  <input type="radio" name="email_sending_option" value="smtp" class="custom-switch-input" <?php if($email_sending_option=='smtp') echo 'checked'; ?>>
										  <span class="custom-switch-indicator"></span>
										  <span class="custom-switch-description"><?php echo $this->lang->line('Use SMTP Email'); ?>
										  	&nbsp;:&nbsp;<a href="<?php echo base_url('admin/smtp_settings');?>" class="float-right"> <?php echo $this->lang->line("SMTP Setting"); ?> </a></span>
										</label>
									</div>
								</div>
		             			<span class="red"><?php echo form_error('email_sending_option'); ?></span>
				           </div>

   						    <div class="row">

   						        <div class="col-12 col-md-6">
   						        	
   						        	<div class="form-group">
   						        	  <label for=""><i class="fa fa-shield"></i> <?php echo $this->lang->line('Force HTTPS?');?></label>
   						        	  <br>
   						        	  <?php	
   						        	  $force_https = $this->config->item('force_https');
   						        	  if($force_https == '') $force_https='0';
   						        	  ?>
   						        	  <label class="custom-switch mt-2">
   						        	    <input type="checkbox" name="force_https" value="1" class="custom-switch-input"  <?php if($force_https=='1') echo 'checked'; ?>>
   						        	    <span class="custom-switch-indicator"></span>
   						        	    <span class="custom-switch-description"><?php echo $this->lang->line('Yes');?></span>
   						        	    <span class="red"><?php echo form_error('force_https'); ?></span>
   						        	  </label>
   						        	</div>
   						        </div>

   					           	<div class="col-12 col-md-6">
   					           		<div class="form-group">
   					           		<label for=""><i class="fa fa-tv"></i> <?php echo $this->lang->line('Display Signup Page?');?></label>
   					           			<br>
   					           		  <?php	
   					           		  $enable_signup_form = $this->config->item('enable_signup_form');
           		               			if($enable_signup_form == '') $enable_signup_form='1';
   					           		  ?>
   					           		  <label class="custom-switch mt-2">
   					           		    <input type="checkbox" name="enable_signup_form" value="1" class="custom-switch-input"  <?php if($enable_signup_form=='1') echo 'checked'; ?>>
   					           		    <span class="custom-switch-indicator"></span>
   					           		    <span class="custom-switch-description"><?php echo $this->lang->line('Yes');?></span>
   					           		    <span class="red"><?php echo form_error('enable_signup_form'); ?></span>
   					           		  </label>
   					           		</div>        				           	
   					            </div>
   					        </div>

						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="logo-favicon">
						<div class="card-header">
							<h4><i class="fas fa-images"></i> <?php echo $this->lang->line("Logo & Favicon"); ?></h4>
						</div>
						<div class="card-body">			             	

			             	<div class="row">
			             		<div class="col-6">
 					             	<label for=""><i class="fas fa-image"></i> <?php echo $this->lang->line("logo");?> (png)</label>
 					             	<div class="custom-file">
 			                            <input type="file" name="logo" class="custom-file-input">
 			                            <label class="custom-file-label"><?php echo $this->lang->line("Choose File"); ?></label>
 			                            <small><?php echo $this->lang->line("Max Dimension");?> : 700 x 200, <?php echo $this->lang->line("Max Size");?> : 500KB </small>	          
 			                            <span class="red"> <?php echo $this->session->userdata('logo_error'); $this->session->unset_userdata('logo_error'); ?></span>
 			                         </div>
			             		</div>
			             		<div class="col-6 my-auto text-center">
			             			<img class="img-fluid" src="<?php echo base_url().'assets/img/logo.png';?>" alt="Logo"/>
			             		</div>
			             	</div>

			             	<div class="row">
			             		<div class="col-6">
 					             	<label for=""><i class="fas fa-portrait"></i> <?php echo $this->lang->line("Favicon");?> (png)</label>
 					             	<div class="custom-file">
 			                            <input type="file" name="favicon" class="custom-file-input">
 			                            <label class="custom-file-label"><?php echo $this->lang->line("Choose File"); ?></label>
 			                            <small><?php echo $this->lang->line("Dimension");?> : 100 x 100, <?php echo $this->lang->line("Max Size");?> : 50KB </small>	          
 			                            <span class="red"> <?php echo $this->session->userdata('favicon_error'); $this->session->unset_userdata('favicon_error'); ?></span>
 			                         </div>
			             		</div>
			             		<div class="col-6 my-auto text-center">
			             			<img class="img-fluid max_width_50px" src="<?php echo base_url().'assets/img/favicon.png';?>" alt="Favicon"/>
			             		</div>
			             	</div>
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="master-password">
						<div class="card-header">
							<h4><i class="fab fa-keycdn"></i> <?php echo $this->lang->line("Master Password"); ?></h4>
						</div>
						<div class="card-body">
				           <div class="form-group">
				             	<label for=""><i class="fa fa-key"></i> <?php echo $this->lang->line("Master Password (will be used for login as user)");?></label>
		               			<input name="master_password" value="******"  class="form-control" type="text">
		             			<span class="red"><?php echo form_error('master_password'); ?></span>
				           </div>
           				   <div class="form-group d-none">
           		             	<label for=""><i class="fab fa-google"></i> <?php echo $this->lang->line("Give access to user to set their own Youtube APP");?></label>            			
                          			<?php
                          		$language_info = array('yes' => $this->lang->line("Yes"), 'no' => $this->lang->line("No"));
           						$select_lan="yes";
           						if($this->config->item('own_youtube_app_access')!="") $select_lan=$this->config->item('own_youtube_app_access');
           						echo form_dropdown('youtube_app_access',$language_info,$select_lan,'class="form-control select2" id="youtube_app_access"');  ?>		          
                        			<span class="red"><?php echo form_error('youtube_app_access'); ?></span>
           		           </div>
						</div>
						<?php echo $save_button; ?>
					</div>

					<?php if($this->session->userdata('license_type') == 'double') { ?>
					<div class="card" id="support-desk">
						<div class="card-header">
							<h4><i class="fas fa-headset"></i> <?php echo $this->lang->line("Support Desk"); ?></h4>
						</div>
						<div class="card-body">
			           		<div class="form-group">
			           		  <?php	
		               			$enable_support = $this->config->item('enable_support');
		               			if($enable_support == '') $enable_support='1';
		               		  ?>
			           		  <label class="custom-switch mt-2">
			           		    <input type="checkbox" name="enable_support" value="1" class="custom-switch-input"  <?php if($enable_support=='1') echo 'checked'; ?>>
			           		    <span class="custom-switch-indicator"></span>
			           		    <span class="custom-switch-description"><?php echo $this->lang->line('Enable Support Desk for Users');?></span>
			           		    <span class="red"><?php echo form_error('enable_support'); ?></span>
			           		  </label>
			           		</div>
						</div>
						<?php echo $save_button; ?>
					</div>
					<?php } ?>

					<div class="card" id="file-upload">
						<div class="card-header">
							<h4><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line("File Upload"); ?></h4>
						</div>
						<div class="card-body">
			              	<div class="row">
			              		<div class="col-12 col-md-6">
      				              	<div class="form-group">
      					             	<label for=""><i class="fa fa-image"></i> <?php echo $this->lang->line("Image File (MB)");?></label>
      			             			<?php 
      				             			$image_upload_limit=$this->config->item('image_upload_limit');
      				             			if($image_upload_limit=="") $image_upload_limit=1; 
      			             			?>
      			               			<input name="image_upload_limit" value="<?php echo $image_upload_limit;?>"  class="form-control" type="number" min="1" required>		          
      			             			<span class="red"><?php echo form_error('image_upload_limit'); ?></span>
      					            </div>
			              		</div>
			              		<div class="col-12 col-md-6">
      				              	<div class="form-group">
      					             	<label for=""><i class="fa fa-video"></i> <?php echo $this->lang->line("Video File (MB)");?></label>
      			             			<?php 
      				             			$video_upload_limit=$this->config->item('video_upload_limit');
      				             			if($video_upload_limit=="") $video_upload_limit=20; 
      			             			?>
      			               			<input name="video_upload_limit" value="<?php echo $video_upload_limit;?>"  class="form-control" type="number" min="1" required>		          
      			             			<span class="red"><?php echo form_error('video_upload_limit'); ?></span>
      					            </div>
			              		</div>
			              	</div>	

				            <div class="row">
			              		<div class="col-12 col-md-6">
			              			<div class="form-group">
						             	<label for=""><i class="fas fa-volume-up"></i> <?php echo $this->lang->line("Audio File (MB)");?></label>
				             			<?php 
					             			$audio_upload_limit=$this->config->item('audio_upload_limit');
					             			if($audio_upload_limit=="") $audio_upload_limit=5; 
				             			?>
				               			<input name="audio_upload_limit" value="<?php echo $audio_upload_limit;?>"  class="form-control" type="number" min="1" required>		          
				             			<span class="red"><?php echo form_error('audio_upload_limit'); ?></span>
						            </div>
			              		</div>

			              		<div class="col-12 col-md-6">
      					            <div class="form-group">
      					             	<label for=""><i class="far fa-file-archive"></i> <?php echo $this->lang->line("Other File (MB)");?></label>
      			             			<?php 
      				             			$file_upload_limit=$this->config->item('file_upload_limit');
      				             			if($file_upload_limit=="") $file_upload_limit=20; 
      			             			?>
      			               			<input name="file_upload_limit" value="<?php echo $file_upload_limit;?>"  class="form-control" type="number" min="1" required>		          
      			             			<span class="red"><?php echo form_error('file_upload_limit'); ?></span>
      					            </div>
			              		</div>
			              	</div>	
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="auto-comment-reply">
						<div class="card-header">
							<h4><i class="fas fa-reply-all"></i> <?php echo $this->lang->line("Auto Comment Reply"); ?></h4>
						</div>
						<div class="card-body">
			              	<div class="row">
			              		<div class="col-12 col-lg-6">
      				              	<div class="form-group">
      					             	<label for=""><?php echo $this->lang->line("No. of campaigns per cron job");?></label>
      			             			<?php 
      				             			$no_of_campaign_per_cron_job_comment_auto_reply=$this->config->item('no_of_campaign_per_cron_job_comment_auto_reply');

      				             			if($no_of_campaign_per_cron_job_comment_auto_reply=="") $no_of_campaign_per_cron_job_comment_auto_reply=5; 
      			             			?>
      			               			<input name="no_of_campaign_per_cron_job_comment_auto_reply" value="<?php echo $no_of_campaign_per_cron_job_comment_auto_reply;?>"  class="form-control" type="number" min="1" required>		          
      			             			<span class="red"><?php echo form_error('no_of_campaign_per_cron_job_comment_auto_reply'); ?></span>
      					            </div>
			              		</div>
			              		<div class="col-12 col-lg-6">
      				              	<div class="form-group">
      					             	<label for=""><?php echo $this->lang->line("No. of replies per cron job");?></label>
      			             			<?php 
      				             			$no_of_reply_per_cron_job_comment_auto_reply=$this->config->item('no_of_reply_per_cron_job_comment_auto_reply');

      				             			if($no_of_reply_per_cron_job_comment_auto_reply=="") $no_of_reply_per_cron_job_comment_auto_reply=5; 
      			             			?>
      			               			<input name="no_of_reply_per_cron_job_comment_auto_reply" value="<?php echo $no_of_reply_per_cron_job_comment_auto_reply;?>"  class="form-control" type="number" min="1" required>		          
      			             			<span class="red"><?php echo form_error('no_of_reply_per_cron_job_comment_auto_reply'); ?></span>
      					            </div>
			              		</div>
			              		<div class="col-12 col-lg-6">
      				              	<div class="form-group">
      					             	<label for=""><?php echo $this->lang->line("No. of old comments to be replied");?><br>&nbsp;</label>
      			             			<?php 
      				             			$no_of_old_comment_to_reply_comment_auto_reply=$this->config->item('no_of_old_comment_to_reply_comment_auto_reply');

      				             			if($no_of_old_comment_to_reply_comment_auto_reply=="") $no_of_old_comment_to_reply_comment_auto_reply=20; 
      			             			?>
      			               			<input name="no_of_old_comment_to_reply_comment_auto_reply" value="<?php echo $no_of_old_comment_to_reply_comment_auto_reply;?>"  class="form-control" type="number" min="20" max="100" required>		          
      			             			<span class="red"><?php echo form_error('no_of_old_comment_to_reply_comment_auto_reply'); ?></span>
      					            </div>
			              		</div>
			              		<div class="col-12 col-lg-6">
      				              	<div class="form-group">
      					             	<label for=""><?php echo $this->lang->line("Delay between replies (seconds)");?><br><small><?php echo $this->lang->line("0 means random");?></small></label>
      			             			<?php 
      				             			$delay_between_reply_comment_auto_reply=$this->config->item('delay_between_reply_comment_auto_reply');

      				             			if($delay_between_reply_comment_auto_reply=="") $delay_between_reply_comment_auto_reply=0; 
      			             			?>
      			               			<input name="delay_between_reply_comment_auto_reply" value="<?php echo $delay_between_reply_comment_auto_reply;?>"  class="form-control" type="number" min="0" required>		          
      			             			<span class="red"><?php echo form_error('delay_between_reply_comment_auto_reply'); ?></span>
      					            </div>
			              		</div>
			              	</div>	
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="app-approval">
						<div class="card-header">
							<h4><i class="fas fa-check-circle"></i> <?php echo $this->lang->line("App Approval"); ?> <a data-toggle="tooltip" data-placement="top" class="pointer text-primary" title="<?php echo $this->lang->line('Hide this feature while creating demo screencast and until your app is submitted for review.');?>"><i class="fas fa-info-circle"></i></a></h4>
						</div>
						<div class="card-body">
			              	<div class="row">			              		
					           	<div class="col-12 col-md-6">
					           		<div class="form-group">
					           		<label for=""><i class="fa fa-eye"></i> <?php echo $this->lang->line('Show rank tracking feature');?></label>
					           			<br>
					           		  <?php	
					           		  $show_rank_tracking_feature = $this->config->item('show_rank_tracking_feature');
        		               			if($show_rank_tracking_feature == '') $show_rank_tracking_feature='1';
					           		  ?>
					           		  <label class="custom-switch mt-2">
					           		    <input type="checkbox" name="show_rank_tracking_feature" value="1" class="custom-switch-input"  <?php if($show_rank_tracking_feature=='1') echo 'checked'; ?>>
					           		    <span class="custom-switch-indicator"></span>
					           		    <span class="custom-switch-description"><?php echo $this->lang->line('Yes');?></span>
					           		    <span class="red"><?php echo form_error('show_rank_tracking_feature'); ?></span>
					           		  </label>
					           		</div>        				           	
					            </div>
			              	</div>	
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="server-status">
						<div class="card-header">
							<h4><i class="fas fa-server"></i> <?php echo $this->lang->line("Server Status"); ?></h4>
						</div>
						<div class="card-body">
							<?php
							  $list1=$list2="";
							  if(function_exists('ini_get'))
							  {
							    $make_dir = (!function_exists('mkdir')) ? $this->lang->line("Not working"):$this->lang->line("Working");
							    $zip_archive = (!class_exists('ZipArchive')) ? $this->lang->line("Not Enabled"):$this->lang->line("Enabled");
							    $list1 .= "<li class='list-group-item'><b>mkdir()</b> : ".$make_dir."</li>"; 
						        $list1 .= "<li class='list-group-item'><b>upload_max_filesize</b> : ".ini_get('upload_max_filesize')."</li>";   
							    $list1 .= "<li class='list-group-item'><b>max_input_time</b> : ".ini_get('max_input_time')."</li>";
							    $list2 .= "<li class='list-group-item'><b>ZipArchive</b> : ".$zip_archive."</li>";  
						        $list2 .= "<li class='list-group-item'><b>post_max_size</b> : ".ini_get('post_max_size')."</li>"; 
							    $list2 .= "<li class='list-group-item'><b>max_execution_time</b> : ".ini_get('max_execution_time')."</li>";
							   }
							  ?>
							  <div class="row">
							  	<div class="col-12 col-md-6">								  		
									<ul class="list-group">
										<?php echo $list1; ?>
									</ul>
							  	</div>
							  	<div class="col-12 col-md-6">
							  		<ul class="list-group">
							  			<?php echo $list2; ?>
									</ul>
							  	</div>
							  </div>
						</div>
					</div>	
				</div>

				<div class="col-md-4 d-none d-sm-block">
					<div class="sidebar-item">
						<div class="make-me-sticky">
							<div class="card">
								<div class="card-header">
									<h4><i class="fas fa-columns"></i> <?php echo $this->lang->line("Sections"); ?></h4>
								</div>
								<div class="card-body">
									<ul class="nav nav-pills flex-column settings_menu">
										<li class="nav-item"><a href="#brand" class="nav-link"><i class="fas fa-flag"></i> <?php echo $this->lang->line("Brand"); ?></a></li>
										<li class="nav-item"><a href="#preference" class="nav-link"><i class="fas fa-tasks"></i> <?php echo $this->lang->line("Preference"); ?></a></li>
										<li class="nav-item"><a href="#logo-favicon" class="nav-link"><i class="fas fa-images"></i> <?php echo $this->lang->line("Logo & Favicon"); ?></a></li>
										<li class="nav-item"><a href="#master-password" class="nav-link"><i class="fab fa-keycdn"></i> <?php echo $this->lang->line("Master Password"); ?></a></li>
										<?php if($this->session->userdata('license_type') == 'double') { ?>
										<li class="nav-item"><a href="#support-desk" class="nav-link"><i class="fas fa-headset"></i> <?php echo $this->lang->line("Support Desk"); ?></a></li>
										<?php } ?>
										<li class="nav-item"><a href="#file-upload" class="nav-link"><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line("File Upload"); ?></a></li>	
										<li class="nav-item"><a href="#auto-comment-reply" class="nav-link"><i class="fas fa-reply-all"></i> <?php echo $this->lang->line("Auto Comment Reply"); ?></a></li>								
										<li class="nav-item"><a href="#app-approval" class="nav-link"><i class="fas fa-check-circle"></i> <?php echo $this->lang->line("App Approval"); ?></a></li>								
										<li class="nav-item"><a href="#server-status" class="nav-link"><i class="fas fa-server"></i> <?php echo $this->lang->line("Server Status"); ?></a></li>								
									</ul>
								</div>						
							</div>
							
						</div>
					</div>
				</div>				
			</div>
		</div>
	</form>
</section>


<script src="<?php echo base_url('assets/js/system/general_settings.js');?>"></script>