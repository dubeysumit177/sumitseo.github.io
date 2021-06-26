<?php 
	$this->load->view("include/upload_js"); 		
	$this->load->view("include/col-3_css"); 		
?>


<section class="section">
	<div class="section-header">
		<h1><i class="fa fa-list"></i> <?php echo $this->lang->line("Playlist Manager");?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="<?php echo base_url("social_accounts/channel_manager"); ?>"><?php echo $this->lang->line('Channel Manager'); ?></a></div>
			<div class="breadcrumb-item"><?php echo $this->lang->line("Playlist Manager");?></div>
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
	<div class="row multi_layout">

		<div class="col-12 col-md-5 col-lg-3 collef">
		  <div class="card main_card">
		    <div class="card-header padding-left-10 padding-right-10">
		      <div class="col-7 padding-0">
		        <h4><i class="fas fa-tv"></i> <?php echo $this->lang->line("Channel"); ?></h4>
		      </div>
		      <div class="col-5 padding-0">            
		        <input type="text" class="form-control float-right" id="search_channel_list" onkeyup="search_in_ul(this,'channel_list_ul')" autofocus placeholder="<?php echo $this->lang->line('Search...'); ?>">
		      </div>
		    </div>
		    <div class="card-body padding-0">
		      <div class="makeScroll">
		      	<ul class="list-group" id="channel_list_ul">
		      	  <?php $i=0; foreach($channel_info as $value) { ?> 
		      	    <li class="list-group-item pl-3 pr-3 <?php if($i==0) echo 'active'; ?> channel_list_item" channel_table_id="<?php echo $value['id']; ?>" channel_id="<?php echo $value['channel_id']; ?>">
		      	      <div class="row">
		      	        <div class="col-3 col-md-2"><img width="45px" class="rounded-circle" src="<?php echo $value['profile_image']; ?>"></div>
		      	        <div class="col-9 col-md-10">
		      	          <h6 class="channel_name"><?php echo $value['title']; ?></h6>
		      	          <small class=""><?php echo $value['channel_id']; ?></small>
		      	          </div>
		      	        </div>
		      	    </li> 
		      	    <?php $i++; } ?>                
		      	</ul>
		      </div>
		    </div>
		  </div>          
		</div>

		<div class="col-12 col-md-7 col-lg-4 colmid" id="middle_column">

		</div>

		<div class="col-12 col-md-12 col-lg-5 colend" id="right_column">

		</div>

<?php } ?>



<div class="modal fade" role="dialog" id="add_playlist_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	    <div class="modal-header">
	        <h5 class="modal-title"><?php echo $this->lang->line('Add Playlist'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body" id="add_playlist_modal_body">
	        
	    	<form action="" method="post" id="add_playlist_form">
	    		
	    		<input type="hidden" id="playlist_channel_id" name="channel_id" value="">
	    		<input type="hidden" id="playlist_playlist_id" name="playlist_id" value="">

		        <div class="row">
		        	<div class="col-12">
		        		<div class="form-group">
		        			<label for="playlist_title"> <?php echo $this->lang->line("Title")?> <span class="red">*</span></label>
		        			<input id="playlist_title" name="title" class="form-control" type="text">
		        		</div>
		        	</div>
		        </div>

		        <div class="row">
		        	<div class="col-12">
		        		<div class="form-group">
		        			<label for="playlist_description"> <?php echo $this->lang->line("Description")?> </label>
		        			<textarea id="playlist_description" name="description" class="form-control"></textarea>
		        		</div>
		        	</div>
		        </div>

		        <div class="row">
		        	<div class="col-12">
		        		<div class="form-group">
		        			<label for="playlist_privacy_type"> <?php echo $this->lang->line("Privacy Type")?> </label> <span id="playlist_dropdown_text"></span>
		        			<select name="privacy_type"  class="form-control" id="playlist_privacy_type">
		        				<option value="0"><?php echo $this->lang->line("Please select anyone"); ?></option>
		        				<option value="public"><?php echo $this->lang->line("Public"); ?></option>
		        				<option value="private"><?php echo $this->lang->line("Private"); ?></option>
		        				<option value="unlisted"><?php echo $this->lang->line("Unlisted"); ?></option>
		        			</select>
		        		</div>
		        	</div>
		        </div>


		        <div class="row">
		        	<div class="col-12">
		        		<div class="form-group">
		        			<label for="playlist_tags"> <?php echo $this->lang->line("Tags")?> <small><?php echo $this->lang->line("Press enter for another tag"); ?></small> </label>
		        			<input type="text" class="form-control inputtags" id="playlist_tags" name="tags">
		        		</div>
		        	</div>
		        </div>

	        </form>

	    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
        <button id="create_playlist_submit" type="button" class="btn btn-primary btn-lg"><?php echo $this->lang->line('Create Playlist'); ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" role="dialog" id="search_video_modal">
  <div class="modal-dialog modal-full" role="document">
    <div class="modal-content">
	    <div class="modal-header">
	        <h5 class="modal-title" id="video_search_modal_title"><?php echo $this->lang->line('Add Videos to Playlist'); ?></h5>

	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body" id="search_video_modal_body">	        
	    	<iframe src="" frameborder="0" class="d_none" width="100%" onload="resizeIframe(this)"></iframe>
	    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
        
      </div>
    </div>
  </div>
</div>



<script>

	

</script>



<link rel="stylesheet" href="<?php echo base_url('assets/css/system/playlist_manager.css');?>">
<script src="<?php echo base_url('assets/js/system/playlist_manager.js');?>"></script>