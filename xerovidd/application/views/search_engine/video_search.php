<?php $this->load->view("search_engine/style.php"); ?>

<?php
$drop_menu ='<a href="javascript:;" id="date_range" class="btn btn-outline-primary daterange-btn icon-left btn-icon"><i class="fas fa-calendar"></i> '.$this->lang->line("Choose Date").'</a><input type="hidden" id="date_range_val" name="date_range_val">';

?>

<?php if ($iframe!='1'): ?>

<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-video"></i> <?php echo $page_title;?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo $this->lang->line("Search Engine");?></div>
			<div class="breadcrumb-item"><?php echo $page_title;?></div>
		</div>
	</div>
</section>
	
<?php endif ?>

<?php 
if(!empty($no_app_error)) echo $no_app_error;
else
{ ?>
<?php if($iframe=='1'):?><div class="row"><div class="col-12 mt-3"><div id="videoListResponseDiv"></div></div></div><?php endif; ?>
<div class="row multi_layout">
	<div class="col-12 col-md-4 col-lg-3 collef">
		<div class="card main_card no_shadow">
			<form method="POST" id="video_search_form_data">
				
				<input type="hidden" name="is_iframe" value="<?php echo $iframe;?>">
				

				<div class="card-header">
					<div class="col-12 padding-0">
						<h4><i class="fas fa-search"></i> <?php echo $this->lang->line("Search"); ?></h4>
					</div>

				</div>
				<div class="card-body">

					<div class="form-group">

						<input type="text" class="form-control" name="keyword" id="keyword" autofocus  placeholder="<?php echo $this->lang->line('Keyword...'); ?>">
					</div>

					<div class="form-group">
						<label class="form-label"><?php echo $this->lang->line('Results Limit'); ?></label>
						<div class="selectgroup w-100">
							<label class="selectgroup-item">
								<input type="radio" name="limit"  value="50" class="selectgroup-input" checked>
								<span class="selectgroup-button"><?php echo $this->lang->line('50'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="limit"  value="100" class="selectgroup-input">
								<span class="selectgroup-button"><?php echo $this->lang->line('100'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="limit"  value="150" class="selectgroup-input">
								<span class="selectgroup-button"><?php echo $this->lang->line('150'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="limit"  value="200" class="selectgroup-input">
								<span class="selectgroup-button"><?php echo $this->lang->line('200'); ?></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label> <?php echo $this->lang->line('Order') ?></label>
						<select class="form-control select2 width_100" id="order" name="order">
							<option value="date"><?php echo $this->lang->line('Date'); ?></option>	
							<option value="rating"><?php echo $this->lang->line('Rating'); ?></option>	
							<option value="relevance" selected><?php echo $this->lang->line('Relevance'); ?></option>	
							<option value="title"><?php echo $this->lang->line('Title'); ?></option>	
							<option value="videoCount"><?php echo $this->lang->line('Video Count'); ?></option>	
							<option value="viewCount"><?php echo $this->lang->line('View Count'); ?></option>	
						</select>
					</div>	

					<div class="form-group">
						<label class="d-block"><?php echo $this->lang->line('Date'); ?></label>
						<?php echo $drop_menu; ?>


					</div>

					<?php if ($iframe!='1'): ?>

					<div class="form-group">
						<label><?php echo $this->lang->line('Location'); ?></label><br>
						<div class="input-group">
						  <input id="location_lat" name="location_lat" type="input" placeholder="<?php echo $this->lang->line("Latitude"); ?>" class="form-control" />
						<input id="location_long" name="location_long" type="input" placeholder="<?php echo $this->lang->line("Longitude"); ?>" class="form-control" />
						</div>
						<a href="https://www.latlong.net/" target="_BLANK"><?php echo $this->lang->line('How to find latitude & longitude?'); ?></a>
					</div>

					<?php endif ?>

					<div class="form-group">
						<label> <?php echo $this->lang->line('Radius') ?></label>
						<select class="form-control select2 width_100" id="radius" name="radius">
							<option value=""><?php echo $this->lang->line('Select Radius'); ?></option>	
							<option value="10km"><?php echo $this->lang->line('10 km'); ?></option>	
							<option value="25km"><?php echo $this->lang->line('25 km'); ?></option>	
							<option value="50km"><?php echo $this->lang->line('50 km'); ?></option>	
							<option value="100km"><?php echo $this->lang->line('100 km'); ?></option>	
							<option value="200km"><?php echo $this->lang->line('200 km'); ?></option>	
							<option value="500km"><?php echo $this->lang->line('500 km'); ?></option>	
							<option value="1000km"><?php echo $this->lang->line('1000 km'); ?></option>	
						</select>
					</div>

					<div class="form-group">
						<label><?php echo $this->lang->line('Channel ID'); ?></label>
						<input type="text" class="form-control" name="channel_id" id="channel_id" value="<?php echo $channel_id; ?>" placeholder="<?php echo $this->lang->line('Channel ID'); ?>">
					</div>

					<div class="form-group">
						<label class="form-label"><?php echo $this->lang->line('Broadcast Type'); ?></label>
						<div class="selectgroup selectgroup-pills">
							<label class="selectgroup-item">
								<input type="radio" name="event_type" value="completed"  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Completed'); ?>"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('Completed'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="event_type"  value="live"  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Live'); ?>"><i class="fa fa-globe"></i> <?php echo $this->lang->line('Live'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="event_type"  value="upcoming"  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Upcoming'); ?>"><i class="fas fa-cloud-rain"></i> <?php echo $this->lang->line('Upcoming'); ?></span>
							</label>

						</div>
					</div>

					<div class="form-group">
						<label class="form-label"><?php echo $this->lang->line('Video Type'); ?></label>
						<div class="selectgroup selectgroup-pills">
							<label class="selectgroup-item">
								<input type="radio" name="video_type" value=""  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Any'); ?>"> <?php echo $this->lang->line('Any'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="video_type"  value="episode"  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Episode'); ?>"> <?php echo $this->lang->line('Episode'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="video_type"  value="movie"  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Movie'); ?>"> <?php echo $this->lang->line('Movie'); ?></span>
							</label>

						</div>
					</div>

					<div class="form-group">
						<label class="form-label"><?php echo $this->lang->line('Duration'); ?></label>
						<div class="selectgroup w-100">
							<label class="selectgroup-item">
								<input type="radio" name="duration" value="" class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Any'); ?>"> <?php echo $this->lang->line('Any'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="duration" value="long" class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Long'); ?>"> <?php echo $this->lang->line('Long'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="duration" value="medium" class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Medium'); ?>"> <?php echo $this->lang->line('Medium'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="duration" value="short" class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Short'); ?>"> <?php echo $this->lang->line('Short'); ?></span>
							</label>
						</div>
					</div>



					<div class="form-group">
						<div class="form-label"><?php echo $this->lang->line('Dimension'); ?></div>
						<div class="selectgroup-item top_10px">
							<label class="custom-switch">
								<input type="radio" name="dimension" value=""  class="custom-switch-input">
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description" title="<?php echo $this->lang->line('Any'); ?>"><?php echo $this->lang->line('Any'); ?></span>
							</label>
							<label class="custom-switch">
								<input type="radio" name="dimension" value="2d"  class="custom-switch-input">
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description" title="<?php echo $this->lang->line('2D'); ?>"><?php echo $this->lang->line('2D'); ?></span>
							</label>
							<label class="custom-switch">
								<input type="radio" name="dimension" value="3d"  class="custom-switch-input">
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description" title="<?php echo $this->lang->line('3D'); ?>"><?php echo $this->lang->line('3D'); ?></span>
							</label>
						</div>
					</div>


					<div class="form-group">
						<div class="form-label"><?php echo $this->lang->line('Defination'); ?></div>
						<div class="selectgroup-item top_10px">
							<label class="custom-switch">
								<input type="radio" name="defination" value="" class="custom-switch-input">
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description" title="<?php echo $this->lang->line('Any'); ?>"><?php echo $this->lang->line('Any'); ?></span>
							</label>
							<label class="custom-switch">
								<input type="radio" name="defination" value="high" class="custom-switch-input">
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description" title="<?php echo $this->lang->line('HD'); ?>"><?php echo $this->lang->line('HD'); ?></span>
							</label>
							<label class="custom-switch">
								<input type="radio" name="defination" value="standard" class="custom-switch-input">
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description" title="<?php echo $this->lang->line('Standard'); ?>"><?php echo $this->lang->line('Standard'); ?></span>
							</label>
						</div>
					</div>


					<div class="form-group">
						<label class="form-label"><?php echo $this->lang->line('license'); ?></label>
						<div class="selectgroup selectgroup-pills">
							<label class="selectgroup-item">
								<input type="radio" name="license" value=""  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Any'); ?>"> <?php echo $this->lang->line('Any'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="license"  value="creativeCommon"  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Creative Common'); ?>"> <?php echo $this->lang->line('Creative Common'); ?></span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="license"  value="youtube"  class="selectgroup-input">
								<span class="selectgroup-button selectgroup-button-icon" title="<?php echo $this->lang->line('Standard YouTube'); ?>"> <?php echo $this->lang->line('Standard'); ?></span>
							</label>

						</div>
					</div>
				</div>

				<div class="card-footer">
					<button class="btn btn-primary btn-lg" id="search_btn" type="submit"><i class="fas fa-search"></i> <?php echo $this->lang->line("Search");?></button>
				</div>

			</form>
		</div>          
	</div>

	<div class="col-12 col-md-8 col-lg-9 colmid">
		<div id="custom_spinner"></div>
		<div id="middle_column_content" class="bg_ffffff">

			<div class="card">
				<div class="card-header">
					<h4> <i class="fas fa-video"></i> <?php echo $this->lang->line('Search Results'); ?></h4>

				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

				<div class="empty-state">
					<img class="img-fluid height_250px" src="<?php echo base_url("assets/img/drawkit/revenue-graph-colour.svg"); ?>" src=" " alt="image">
					<h2 class="mt-0"><?php echo $this->lang->line("Please first search YouTube videos using the filtering options."); ?></h2>

				</div>

			</div>
		</div>
	</div>
</div>

<?php } ?>


<?php $this->load->view("include/playlist_add_js"); ?>




<?php include("application/views/search_engine/video_search_js.php"); ?>
<script src="<?php echo base_url();?>assets/js/page/clipboard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/scrollreveal/scrollreveal.js" type="text/javascript"></script>

