<link rel="stylesheet" href="<?php echo base_url('assets/css/system/facebook_settings.css');?>">

<section class="section">
	<div class="section-header">
		   <h1><i class="fab fa-facebook"></i> <?php echo $page_title; ?></h1>
		   <div class="section-header-breadcrumb">
		     <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
		     <div class="breadcrumb-item"><a href="<?php echo base_url('social_apps/settings'); ?>"><?php echo $this->lang->line("Social Apps"); ?></a></div>
		     <div class="breadcrumb-item"><?php echo $page_title; ?></div>
		   </div>
	</div>

	
 	<?php $this->load->view('admin/theme/message'); ?>


	<div class="row">
        <div class="col-12">
            <div class="card">
              <div class="card-body">
                  <b><?php echo $this->lang->line("App Domain")."</b> : <span class='blue'>".get_domain_only(base_url()); ?></span><br/>
                  <b><?php echo $this->lang->line("Site URL")." :</b> <span class='blue'>".base_url(); ?> </span><br/><br>
                  <b><?php echo $this->lang->line("Privacy Policy URL")." :</b> <span class='blue'>".base_url('home/privacy_policy'); ?> </span><br/>
                  <b><?php echo $this->lang->line("Terms of Service URL")." :</b> <span class='blue'>".base_url('home/terms_use'); ?> </span><br/><br>
                
              	   <b><?php echo $this->lang->line("Valid OAuth Redirect URI")." </b>: <br><span class='blue'>".base_url("home/facebook_login_back"); ?></span>
              
              </div>
            </div>
        </div>
    </div>

    <div class="row">
    	<div class="col-12 col-md-8 col-lg-8">
    		<div class="card">
    			<div class="card-header">
    				<h4> <i class="fas fa-list-ul"></i> <?php echo $this->lang->line('Apps List'); ?></h4>
    			</div>

    			<div class="card-body data-card">
    				<div class="table-responsive2">
    				  <table class="table table-bordered" id="mytable">
    				    <thead>
    				      <tr>
    				        <th>#</th>      
    				        <th><?php echo $this->lang->line("Table ID"); ?></th>     
    				        <th><?php echo $this->lang->line("App Name"); ?></th>      
    				        <th><?php echo $this->lang->line("App ID"); ?></th>      
    				        <th><?php echo $this->lang->line("App Secret"); ?></th>
    				        <th><?php echo $this->lang->line("Status"); ?></th>
    				        <th><?php echo $this->lang->line("Action"); ?></th>
    				      </tr>
    				    </thead>
    				    <tbody>
    				    </tbody>
    				  </table>
    				</div>       
    			</div>
    		</div>
    	</div>

    	<div class="col-12 col-md-4 col-lg-4"  id="app_info_card">
    		<div class="card">
    				
    			<div class="card-header">
    				<div class="col-12 padding-0">
    					<h4><i class="fas fa-info-circle"></i> <?php echo $this->lang->line("App Details"); ?></h4>
    				</div>

    			</div>
    			<div class="card-body">

    				<form method="POST" id="facebook_config_form_data" action="">

    					<input type="hidden" name="submit_type" value="add" id="submit_type">
    					<input type="hidden" name="table_id" id="table_id_info">

    					<div class="form-group">
    					    <label for=""><i class="fas fa-file-signature"></i> <?php echo $this->lang->line("App Name");?> </label>
    					    <input name="app_name" id="app_name"  class="form-control" type="text">
    					</div>

    					<div class="form-group">
		                    <label for=""><i class="far fa-id-card"></i>  <?php echo $this->lang->line("App ID");?></label>
		                    <input name="app_id" id="app_id" class="form-control" type="text"> 
		                </div>


		                <div class="form-group">
		                  <label for=""><i class="fas fa-key"></i>  <?php echo $this->lang->line("App Secret");?></label>
		                  <input name="app_secret" id="app_secret" class="form-control" type="text">
		                </div>

		                <div class="form-group">
		                	<label class="custom-switch mt-2">
		                		<input type="checkbox" name="status" id="app_status" value="1" class="custom-switch-input">
		                		<span class="custom-switch-indicator"></span>
		                		<span class="custom-switch-description"><?php echo $this->lang->line('Active');?></span>
		                	</label>
		                </div>
    					

    				</form>


    				<button class="btn btn-primary btn-md" id="add_app_btn"><i class="fa fa-plus"></i> <?php echo $this->lang->line("Add App");?></button>


    			</div>
    		</div>    
    	</div>
    </div>
	   				

</section>


<script src="<?php echo base_url('assets/js/system/facebook_settings.js');?>"></script>