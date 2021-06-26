<?php 
	$this->load->view("include/upload_js");
	$this->load->view("include/col-3_css"); 		
?>


<section class="section">
	<div class="section-header">
		<h1><i class="fa fa-video"></i> <?php echo $this->lang->line("Video Manager");?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="<?php echo base_url("social_accounts/channel_manager"); ?>"><?php echo $this->lang->line('Channel Manager'); ?></a></div>
			<div class="breadcrumb-item"><?php echo $this->lang->line("Video Manager");?></div>
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

		<div class="col-12 col-md-12 col-lg-3 collef">
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

		<div class="col-12 col-md-12 col-lg-9 colend" id="right_column">
			<?php 
			echo '
			<div id="right_column_content">
				    <div class="card main_card">
				        <div class="card-header padding-left-10 padding-right-10">
				            <div class="col-12 padding-0 d_inherit">
				              <h4><i class="fab fa-youtube"></i> '. $this->lang->line("Videos") .'</h4>
				            </div>
				            
				        </div>

				        <div class="card-body p-2">
				           	<iframe src="" frameborder="0" class="d_none" width="100%" onload="resizeIframe(this)"></iframe>
				           	<br><br>
				        </div>
				    </div>
				</div>';
			?>
		</div>

<?php } ?>





<script src="<?php echo base_url('assets/js/system/video_manager.js');?>"></script>