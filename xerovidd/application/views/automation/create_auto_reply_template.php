<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fas fa-reply-all"></i> <?php echo $this->lang->line('Auto Reply Template'); ?></h1>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><a href="<?php echo base_url("responder/template_manager"); ?>"><?php echo $this->lang->line('Template Manager'); ?></a></div>
		  <div class="breadcrumb-item">
		  	  <a href="<?php echo base_url('responder/auto_reply_template'); ?>"><?php echo $this->lang->line('Auto Reply Template'); ?></a>
		  </div>
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Create Template'); ?></div>
		</div>
	</div>

	<div class="section-body">
		<div class="row">
		  	<div class="col-12">
		    	<div class="card">

		    		<div class="card-body">
		    			<form id="auto_reply_templete_form" action="#" method="post">
		    				<input type="hidden" name="action_type" value="create">
				    		<!-- template name -->
				    		<div class="form-group">
				    			<label for="campaign_name"> <?php echo $this->lang->line("Name")?> </label>
				    			<input id="name" name="name" class="form-control" type="text">
				    		</div>  

				    		<!-- delete offensive word -->
				    		<div class="row">
				    		    <div class="col-12 col-md-6">
					    		    <div class="form-group">
						    		    <label for="delete_offensive_comment" > <?php echo $this->lang->line('Do you want to delete offensive comments ?');?>
						    		    </label><br>
						    		    <label class="custom-switch mt-2">
						    		        <input id="delete_offensive_comment" type="checkbox" name="delete_offensive_comment" value="1" class="custom-switch-input">
						    		        <span class="custom-switch-indicator"></span>
						    		        <span class="custom-switch-description"><?php echo $this->lang->line('Yes');?></span>
						    		    </label>
					    		    </div>
				    		    </div>
				    		</div>	

				    		<!-- offensive words -->
				    		<div class="form-group" id="offensive_keywords_block">
					    		<label for="offensive_keywords"> <?php echo $this->lang->line("Offensive keywords (press enter to separate words)")?>
					    		</label>
					    		<textarea id="offensive_keywords" name="offensive_keywords" class="form-control inputtags"></textarea>
				    		</div> 


				    		<!-- reply type & multiple reply-->
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
							    		                <span class="custom-switch-description"><?php echo $this->lang->line('Filter'); ?></span>
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


				    		<!-- generic message block -->
	    					<div class="form-group generic_message_block">
								<label for="generic_message"> <?php echo $this->lang->line("Message for generic reply.")?></label>
								<textarea id="generic_message" name="generic_message" class="form-control"></textarea>
	    					</div> 

							
							<!-- filter message block -->
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
    					</form>
		    		</div>

		    		<div class="card-footer">
		    			<button id="create_template" type="button" class="btn btn-lg btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('Create Template'); ?></button>
		    			<a href="<?php echo base_url('responder/auto_reply_template'); ?>" class="btn btn-lg float-right btn-secondary cancel_template" ><i class="fas fa-times"></i> <?php echo $this->lang->line('Cancel'); ?></a>
		    		</div>
		    	</div>
		    </div>
		</div>
	</div>

</section>

<script src="<?php echo base_url('assets/js/system/auto_reply_template_create.js');?>"></script>