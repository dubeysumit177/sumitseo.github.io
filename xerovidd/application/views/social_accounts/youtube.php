<link rel="stylesheet" href="<?php echo base_url('assets/css/system/select2_100.css');?>">
<section class="section <?php if($iframe=='1') echo 'm-2';?>">
  <?php if ($iframe!='1'): ?>	
  <div class="section-header">
	<h1><i class="fab fa-youtube"></i> <?php echo $this->lang->line('Channel Videos'); ?></h1>
	<div class="section-header-breadcrumb">
	  <div class="breadcrumb-item"><a href="<?php echo base_url("social_accounts"); ?>"><?php echo $this->lang->line('Social Accounts'); ?></a></div>
	  <div class="breadcrumb-item"><?php echo $this->lang->line("YouTube");?></a></div>
	  <div class="breadcrumb-item"><a href="<?php echo "https://youtube.com/channel/".$channel_id; ?>"><?php echo $channel_title; ?></a></div>
	</div>
  </div>
  <?php endif; ?>
  <div class="section-body">

    <div class="row">      
      <div class="col-12 col-md-7 <?php if($iframe=='1') echo 'p-0 pl-2 pr-2';?>">
        <div class="input-group mb-3" id="searchbox">
            <div class="input-group-prepend">
                <select name="rows_number" class="selectric form-control" id="rows_number">
      	            <option value="12"><?php echo $this->lang->line('12 items'); ?></option>
      	            <option value="25"><?php echo $this->lang->line('25 items'); ?></option>
      	            <option value="50"><?php echo $this->lang->line('50 items'); ?></option>
      	            <option value="100"><?php echo $this->lang->line('100 items'); ?></option>
      	            <option value="500"><?php echo $this->lang->line('500 items'); ?></option>
                </select>
             </div>
          <input type="text" class="form-control" id="search" autofocus placeholder="<?php echo $this->lang->line('Search...'); ?>" aria-label="" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn btn-primary" id="search_submit" type="button"><i class="fas fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-5 <?php if($iframe=='1') echo 'p-0 pl-2 pr-2';?>">
        	  <?php if($iframe!='1' || $load_src=='video_manager') { ?>        				
				<div class="btn-group dropleft float-right">
					<button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  <?php echo $this->lang->line("Options"); ?>  </button>
					<div class="dropdown-menu dropleft"> 
						<a class="dropdown-item has-icon pointer" id="channel_analytics"  href="<?php echo base_url('social_accounts/channel_analytics/'. $table_id); ?>" target="_BLANK"><i class="fas fa-chart-bar"></i> <?php echo $this->lang->line("Channel Analytics"); ?></a>
						<?php if($this->session->userdata('user_type') == 'Admin' || in_array(18,$this->module_access)) : ?>
						<a class="dropdown-item has-icon pointer" id="link_wheel"><i class="fas fa-dharmachakra"></i> <?php echo $this->lang->line("Create Link Wheel"); ?></a>
						<?php endif; ?>
					</div> 
				</div>
        	  <?php } 

        	  else if($iframe=='1' && $load_src=="playlist_manager") {
        	   	echo '<button id="add_selected_videos" type="button" channel_id="'.$channel_id.'" playlist_id="'.$selected_playlist_id.'" class="btn btn-lg btn-outline-primary float-right"><i class="fas fa-plus-circle"></i> '.$this->lang->line("Add to Playlist").'</button>';
        	  }  ?>

      </div>
      <div class="col-12 <?php if($iframe=='1') echo 'p-0 pl-2 pr-2';?>"><div id="videoListResponseDiv"></div></div>
    </div>

    <div class="activities d-block">
        <div class="row" id="load_data"></div>      
    </div> 


    <div class="text-center margin_30_0px width_100" id="waiting">
      <i class="fas fa-spinner fa-spin blue font_size_60px"></i>
    </div>  

    <div class="card d_none" id="nodata">
      <div class="card-body">
        <div class="empty-state">
          <img class="img-fluid height_300px" src="<?php echo base_url('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
          <h2 class="mt-0"><?php echo $this->lang->line("We could not find any data.") ?></h2>
        </div>
      </div>
    </div>
 

    <button class="btn btn-outline-primary float-right mb-4 d_none" id="load_more" data-limit="12" data-start="0"><i class="fas fa-book-reader"></i> <?php echo $this->lang->line("Load More"); ?></button>
      
  </div>
</section>


<?php include("application/views/social_accounts/youtube_js.php"); ?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/system/youtube.css');?>">


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
					
					<div class="form-group">
						<label for="campaign_name"> <?php echo $this->lang->line("Campaign Name")?> </label>
						<input id="campaign_name" name="campaign_name" class="form-control" type="text">
					</div>  

					<div class="row">
						<div class="col-12 col-md-6">
						    <div class="form-group">
						      	<label for="use_saved_template" > <?php echo $this->lang->line('Do you want to use saved template?');?></label><br>
							    <label class="custom-switch mt-2">
							        <input id="use_saved_template" type="checkbox" value="1" class="custom-switch-input" name="use_saved_template" checked>
							        <span class="custom-switch-indicator"></span>
							        <span class="custom-switch-description"><?php echo $this->lang->line('Yes');?></span>
							    </label>
						    </div>
						</div>
					</div>	

					<div id="saved_template_block">
						<div class="form-group">
						  <label for="saved_template_list"> <?php echo $this->lang->line("Saved templates")?></label>
						  <?php echo preg_replace("/[\r\n]+/", "\n", form_dropdown('saved_template_id', $saved_templates, '0', 'id="saved_template_list" class="form-control select2"')) ; ?>
						  
						</div> 
					</div>

					
					<div id="raw_template_block">
						<div class="row">
						  <div class="col-12 col-md-6">
						    <div class="form-group">
						      <label for="delete_offensive_comment" > <?php echo $this->lang->line('Do you want to delete offensive comments ?');?></label><br>
						      <label class="custom-switch mt-2">
						        <input id="delete_offensive_comment" type="checkbox" name="delete_offensive_comment" value="1" class="custom-switch-input">
						        <span class="custom-switch-indicator"></span>
						        <span class="custom-switch-description"><?php echo $this->lang->line('Yes');?></span>
						      </label>
						    </div>
						  </div>
						</div>	


						<div class="form-group offensive_keywords_block">
						  <label for="offensive_keywords"> <?php echo $this->lang->line("Offensive keywords (press enter to separate words)")?></label>
						  <textarea id="offensive_keywords" name="offensive_keywords" class="form-control inputtags"></textarea>
						</div> 


						<div class="row">
						  <div class="col-12 col-md-6">
						    <div class="form-group">
						      <label for="" > <?php echo $this->lang->line('Reply Type');?></label>
						        <div class="custom-switches-stacked mt-2">
						          <div class="row">   
						            <div class="col-6 col-md-4">
						              <label class="custom-switch">
						                <input type="radio" name="reply_type" value="generic" checked class="custom-switch-input">
						                <span class="custom-switch-indicator"></span>
						                <span class="custom-switch-description"><?php echo $this->lang->line('Generic'); ?></span>
						              </label>
						            </div>                        
						            <div class="col-6 col-md-4">
						              <label class="custom-switch">
						                <input type="radio" name="reply_type" value="filter" class="custom-switch-input">
						                <span class="custom-switch-indicator"></span>
						                <span class="custom-switch-description"><?php echo $this->lang->line('By Filter'); ?></span>
						              </label>
						            </div>
						          </div>                                  
						        </div>
						    </div> 
						  </div>

						  <div class="col-12 col-md-6">
						    <div class="form-group">
						      <label for="multiple_reply" > <?php echo $this->lang->line('Enable multiple reply');?></label><br>
						      <label class="custom-switch mt-2">
						        <input id="multiple_reply" type="checkbox" name="multiple_reply" value="1" class="custom-switch-input">
						        <span class="custom-switch-indicator"></span>
						        <span class="custom-switch-description"><?php echo $this->lang->line('Enable');?></span>
						      </label>
						    </div>
						  </div>             
						</div>


						<div class="form-group generic_message_block">
						  <label for="generic_message"> <?php echo $this->lang->line("Message for generic reply.")?></label>
						  <textarea id="generic_message" name="generic_message" class="form-control"></textarea>
						</div> 


						<div class="filter_message_block">

							<div class="card card-info single_card">
								<div class="card-header">
				                    <h4><?php echo $this->lang->line("Filter Reply"); ?></h4>
					                <div class="card-header-action">
			                          <button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> <?php echo $this->lang->line('Remove'); ?></button>
			                        </div>
				                </div>
				                <div class="card-body">
				                    <div class="form-group">
				                    	<label for="filter_words"> <?php echo $this->lang->line("Filter Word")?> </label>
				                    	<input name="filter_words[]"  class="form-control filter_word_input" type="text">
				                    </div>  

				                    <div class="form-group">
				                      <label for="filter_message"> <?php echo $this->lang->line("Message for filter")?></label>
				                      <textarea name="filter_message[]" class="form-control"></textarea>
				                    </div> 
				                </div>
			                </div>


							<div class="clearfix add_more_button_block">
								<input type="hidden" id="content_block" value="1">
								<input type="hidden" id="odd_or_even" value="odd">
								<button class="btn btn-outline-primary float-right" id="add_more_filter_button"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Add more filter') ?></button>
							</div>

							<div class="form-group">
							  <label for="not_found_filter_message"> <?php echo $this->lang->line("Message for no match")?></label>
							  <textarea id="not_found_filter_message" name="not_found_filter_message" class="form-control"></textarea>
							</div> 

						</div>
					</div>
				</div>

	

	        </form>
	        <div id="autoReplyResponseDiv"></div>
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>

	        <button id="create_campaign" type="button" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> <?php echo $this->lang->line('Create Campaign'); ?></button>
	        <button id="create_campaign_and_save_as_template" type="button" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> <?php echo $this->lang->line('Create Campaign and save as template'); ?></button>
	    </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit_yt_video_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg_fefefe">
	    <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('Edit Video'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">
	        <form id="edit_video_form_data" action="" method="post">
				
				<input type="hidden" id="edit_video_id" name="video_id">      
				<input type="hidden" id="edit_channel_id" name="channel_id">      
				    
				<div class="edit_video_info_block">
				</div>
	

	        </form>
	        <div id="editVideoReplyResponseDiv" class="mt-2"></div>
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
	        <button id="update_video" type="button" class="btn btn-primary btn-lg"><i class="fas fa-edit"></i> <?php echo $this->lang->line('Update Video'); ?></button>
	    </div>
    </div>
  </div>
</div>

<div class="modal fade" id="rank_track_set_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg_fefefe">
	    <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('YouTube Rank Tracking'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">
	        <form id="rank_tracker_set_form" action="" method="post">
				
				<input type="hidden" id="rank_video_id" name="video_id">


				  <div class="form-group">
				  	<label><?php echo $this->lang->line('Tracking Name'); ?></label>
				  	<input type="text" class="form-control" name="name" id="name">
				  </div>   
 
				  <div class="form-group">
				  	<label><?php echo $this->lang->line('Keyword'); ?></label>
				  	<input type="text" class="form-control" name="keyword" id="keyword">
				  </div>
	

	        </form>
	        <div id="rankSubmitVideoReplyResponseDiv"></div>
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



<?php $this->load->view("include/playlist_add_js"); ?>





<div class="modal fade" id="modal_wheel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-mega" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> <i class="fas fa-dharmachakra"></i> <?php echo $this->lang->line("Video Link Wheel"); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modalBody">
				<a class="btn-block btn btn-sm btn-light" data-toggle="modal" href='#what_is_wheel'><?php echo $this->lang->line("What is Video Link Wheel (VLW) and how it works?"); ?></a><br/>
				<div id="show_message" class="text-center"></div>

				<div class="form-group">
				  <label for="wheel_name"><?php echo $this->lang->line("Wheel Name"); ?> *</label><br/>
				  <input type="text" id="wheel_name" required class="form-control"/>
				</div>

				<div class="form-group">
				  <label for="money_video_id"><?php echo $this->lang->line("Money Video IDs"); ?> <?php echo $this->lang->line("(comma seperated)"); ?></label><br/>
				  <select id="money_video_id" name="money_video_id[]" multiple="true"></select>
				</div>

				<div class="form-group">
				  <label for="wheel_type"><?php echo $this->lang->line("Wheel type"); ?> *</label><br/>

				  <div class="custom-control custom-radio custom-control-inline">
				    <input type="radio" id="open" name="wheel_type" class="custom-control-input wheel_type" value="open">
				    <label class="custom-control-label" for="open"><?php echo $this->lang->line("Open");?></label>
				  </div>

				   <div class="custom-control custom-radio custom-control-inline">
				    <input type="radio" id="close" name="wheel_type" class="custom-control-input wheel_type" value="close" checked>
				    <label class="custom-control-label" for="close"><?php echo $this->lang->line("Close");?></label>
				  </div>

				</div>

				<div class="form-group">
					<div id="linkWheelResponseDiv"></div>
				</div>

				<div class="form-group">
				  <label></label><br/>
				   <button type="button" id="submit_wheel" class="btn btn-primary float-left btn-lg"><i class="fas fa-check-circle"></i> <?php echo $this->lang->line("Create Link Wheel"); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="what_is_wheel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-mega" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> <i class="fas fa-dharmachakra"></i> <?php echo $this->lang->line("How video link wheel works?"); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul>
				  <li><?php echo $this->lang->line("Video Link Wheel (VLW) is a SEO technique based on backlink to increase video audience. Our video link wheeler is developed for youtube video linking."); ?> 
				  </li>
				  <li><?php echo $this->lang->line("In closed VLW technique, youtube videos of same channel or different channles are linked to each other in circular manner, like video A links video B, B links C and finaly C links A."); ?> <br/><img class="img-fluid" src="<?php echo base_url("assets/img/close_vlw.png");?>"></li>
				  <li><?php echo $this->lang->line("In open VLW technique, youtube videos of same channel or different channles are also linked to each other but not in circular manner, like video A links video B and B links C (C does not link A)."); ?><br/><img class="img-fluid" src="<?php echo base_url("assets/img/open_vlw.png");?>"></li>
				  <li><?php echo $this->lang->line("You can make wheel for many videos of diferent channels as well as can consider 10 main promotional videos or money videos. Each video of a wheel contains backlink of main promotional videos or money videos."); ?></li>            
				  <li><?php echo $this->lang->line("Link wheeling is very easy. Just select youtube videos and give you money video ids into the form and click 'Create Link Wheel'."); ?></li>
				
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
			</div>
		</div>
	</div>
</div>