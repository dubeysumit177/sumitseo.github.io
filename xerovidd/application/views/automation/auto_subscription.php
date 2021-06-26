<link rel="stylesheet" href="<?php echo base_url('assets/css/system/select2_100.css');?>">
<?php
	$drop_menu ='<a href="javascript:;" id="date_range" class="btn btn-outline-primary daterange-btn icon-left btn-icon"><i class="fas fa-calendar"></i> '.$this->lang->line("Choose Date").'</a><input type="hidden" id="date_range_val" name="date_range">';

?>

<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-user-check"></i> <?php echo $this->lang->line('Auto Subscription Campaign'); ?></h1>
		<div class="section-header-button">
			<a href="" class="btn btn-primary create_campaign"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Create Campaign'); ?></a>
	    </div>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><a href="<?php echo base_url("social_accounts/channel_manager"); ?>"><?php echo $this->lang->line('Channel Manager'); ?></a></div>
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Auto Subscription Campaign'); ?></div>
		</div>
	</div>


	<?php 
		if (isset($auto_subscription_infos[0])) {

			$today_max = $auto_subscription_infos[0]['today_auto_subscribed'] + $auto_subscription_infos[0]['today_auto_unsubscribed'];

			$weekly_max = $auto_subscription_infos[0]['this_week_auto_subscribed'] + $auto_subscription_infos[0]['this_week_auto_unsubscribed'];

			$monthly_max = $auto_subscription_infos[0]['this_month_auto_subscribed'] + $auto_subscription_infos[0]['this_month_auto_unsubscribed'];

			$today_max = max($today_max,1);
			$weekly_max = max($weekly_max,1);
			$monthly_max = max($monthly_max,1);
		}
	?>


	<div class="section-body">

		<div class="row">
		  <div class="col-12">
		    <div class="card">
		      <div class="card-body data-card">
		        <div class="table-responsive2">
		          <table class="table table-bordered" id="mytable">
		            <thead>
		              <tr>
		                <th>#</th>      
		                <th><?php echo $this->lang->line("Campaign ID"); ?></th>      
		                <th><?php echo $this->lang->line("Campaign Name"); ?></th>      
		                <th><?php echo $this->lang->line("Channel"); ?></th>      
		                <th><?php echo $this->lang->line("Created at"); ?></th>
		                <th class="width_100px"><?php echo $this->lang->line("Status"); ?></th>
		                <th><?php echo $this->lang->line("Total Activity"); ?></th>
		                <th class="min_width_190px"><?php echo $this->lang->line("Actions"); ?></th>
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


<div class="modal fade" role="dialog" id="auto_subscription_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	    <div class="modal-header">
	        <h5 class="modal-title"><?php echo $this->lang->line('Auto Subscription Campaign'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body" id="auto_like_comment_modal_body">
	        
	    	<form action="" method="post" id="auto_subscription_form">
	    		
		        <input type="hidden" name="submit_type" value="add" id="submit_type">
		        <input type="hidden" name="campaign_id" id="campaign_id_on_modal">

		        <div class="row">
		        	<div class="col-12">
		        		<div class="form-group">
		        			<label for="campaign_name"> <?php echo $this->lang->line("Campaign Name")?> </label>
		        			<input id="campaign_name" name="campaign_name" class="form-control" type="text">
		        		</div>
		        	</div>

		        </div>

		        <div class="row hide_on_edit">
		        	<div class="col-12">
		        		<div class="form-group">
		        			<label for="user_channel_id"> <?php echo $this->lang->line("Channel")?>
		        				<a data-toggle="tooltip" data-placement="top" class="pointer text-primary" title='<?php echo $this->lang->line("Auto subscribe as this channel.") ?>'><i class='fas fa-info-circle'></i></a>	
		        			</label>
		        			<?php echo form_dropdown('user_channel_id', $channel_dropdown, '', 'id="user_channel_id" class="form-control select2"'); ?>
		        		</div>
		        	</div>
		        </div>


				
				<div class="row">
				  <div class="col-12 col-md-5">
				    <div class="form-group">
				    	<label for="keywords"> <?php echo $this->lang->line("Keywords")?> <small><?php echo $this->lang->line("comma separeted"); ?></small>
				    		<a data-toggle="tooltip" data-placement="top" class="pointer text-primary" title='<?php echo $this->lang->line("System will take a keyword randomly but will make sure it is least used.");?>'><i class='fas fa-info-circle'></i></a>
				    	</label>
				    	<input id="keywords" name="keywords" class="form-control" type="text" placeholder="">
				    </div>   
				  </div>

				  <div class="col-12 col-md-4">
					<div class="form-group">						
						<label class=""><?php echo $this->lang->line("Daily Max Activity");?>
						<a data-toggle="tooltip" data-placement="top" class="pointer text-primary" title='<?php echo $this->lang->line("No of subscription activity per day. Value can range from 1 to 50. Please be careful, do not spam. Larger number of activities per day can marked as spam by YouTube.");?>'><i class='fas fa-info-circle'></i></a>	
						</label>
						<input id="max_activity" name="max_activity" class="form-control" type="text" value="5">
					</div>
				  </div>
				  <div class="col-12 col-md-3">
				    <div class="form-group">
				    	<label for="date_range"> <?php echo $this->lang->line("Published Date")?> 
				    		<a data-toggle="tooltip" data-placement="top" class="pointer text-primary" title='<?php echo $this->lang->line("Channel creation date range.");?>'><i class='fas fa-info-circle'></i></a>
				    	</label><br>

				    	<a href="javascript:;" id="date_range" class="btn btn-outline-primary daterange-btn icon-left btn-icon"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Choose Date"); ?></a>
				    	<input type="hidden" id="date_range_val" name="date_range">
				    </div>   
				  </div>
				</div>

				<div class="row">
				  <div class="col-12 col-md-5">
				    <div class="form-group">
				      <label for="" > <?php echo $this->lang->line("Expire Type");?>
				      	<a data-toggle="tooltip" data-placement="top" class="pointer text-primary" title='<?php echo $this->lang->line("When the campaign will stop? After a certain number of activity or after a certain date.");?>'><i class='fas fa-info-circle'></i></a>
				      </label>
				        <div class="custom-switches-stacked mt-2">
				          <div class="row">   
				            <div class="col-6 col-md-4">
				              <label class="custom-switch">
				                <input type="radio" name="expire_type" value="date" checked class="custom-switch-input">
				                <span class="custom-switch-indicator"></span>
				                <span class="custom-switch-description"><?php echo $this->lang->line("Date"); ?></span>
				              </label>
				            </div>  
				                            
				            <div class="col-6 col-md-4">
				              <label class="custom-switch">
				                <input type="radio" name="expire_type" value="no_of_activity" class="custom-switch-input">
				                <span class="custom-switch-indicator"></span>
				                <span class="custom-switch-description"><?php echo $this->lang->line("Activity"); ?></span>
				              </label>
				            </div>
				          </div>                                  
				        </div>
				    </div> 
				  </div>

				  <div class="col-12 col-md-7">

				    <div class="form-group" id="date_block">
				    	<label for="expire_date"> <?php echo $this->lang->line("Expiry Date")?> <small></label>
				    	<input id="expire_date" name="expire_date" class="form-control datepicker_x" type="text">
				    </div>  

				    <div class="form-group d_none" id="activity_block">
				    	<label for="expire_activity"> <?php echo $this->lang->line("Activity")?> </label>
				    	<input id="expire_activity" name="expire_activity" class="form-control" type="text" placeholder="">
				    </div>  
				    
				  </div>             
				</div>
	        </form>

	    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
        <button id="create_campaign_submit" type="button" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> <?php echo $this->lang->line('Create Campaign'); ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="auto_subscribe_unsubscribe_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-mega" role="document">
    <div class="modal-content bg_fefefe">
	    <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('Auto Subscription Report'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">

			<div class="report_details_body">

				<div class="row">
				  <div class="col-12">
				    <div class="card no_shadow">

				      <div class="card-body data-card p-0">            
				        <div class="table-responsive2">
				          <table class="table table-bordered" id="myReportTable">
				          	<input type="hidden" id="auto_subscribe_unsubscribe_campagin_id" name="auto_subscribe_unsubscribe_campagin_id">
				            <thead>
				              <tr>
				                <th>#</th>         
				                <th><?php echo $this->lang->line("Targeted Channel"); ?></th>      
				                <th><?php echo $this->lang->line("Status"); ?></th>      
				                <th><?php echo $this->lang->line("Actions"); ?></th>      
				                <th><?php echo $this->lang->line("Scheduled at"); ?></th>    
				                <th><?php echo $this->lang->line("Action Time"); ?></th>      

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

	        
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
	    </div>
    </div>
  </div>
</div>


<?php include("application/views/automation/auto_subscription_js.php"); ?>