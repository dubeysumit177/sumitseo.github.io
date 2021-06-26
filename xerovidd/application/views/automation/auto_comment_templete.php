<link rel="stylesheet" href="<?php echo base_url('assets/css/system/auto_comment_template.css');?>">

<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-comment-dots"></i> <?php echo $this->lang->line('Auto Comment template'); ?></h1>
		<div class="section-header-button">
		<a href="" class="btn btn-primary create_campaign"><i class="fas fa-plus-circle"></i> <?php echo $this->lang->line('Create template'); ?></a>
	    </div>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><a href="<?php echo base_url("responder/template_manager"); ?>"><?php echo $this->lang->line('Template Manager'); ?></a></div>
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Auto Comment Template'); ?></div>
		</div>
	</div>


	<div class="section-body">

		<div class="row">      

		  <div class="col-12 col-md-12 col-lg-7">
			<form action="<?php echo base_url('responder/auto_comment_template/'); ?>" method="post">
			    <div class="input-group mb-3" id="searchbox">
			      <div class="input-group-prepend">
			          <select name="rows_number" class="selectric form-control" id="rows_number">
				            <option value="10" <?php if ($per_page == 10) echo 'selected'; ?>><?php echo $this->lang->line('10 items'); ?></option>
				            <option value="25" <?php if ($per_page == 25) echo 'selected'; ?>><?php echo $this->lang->line('25 items'); ?></option>
				            <option value="50" <?php if ($per_page == 50) echo 'selected'; ?>><?php echo $this->lang->line('50 items'); ?></option>
				            <option value="100" <?php if ($per_page == 100) echo 'selected'; ?>><?php echo $this->lang->line('100 items'); ?></option>
				            <option value="500" <?php if ($per_page == 500) echo 'selected'; ?>><?php echo $this->lang->line('500 items'); ?></option>
				            <option value="all" <?php if ($per_page == 'all') echo 'selected'; ?>><?php echo $this->lang->line('All items'); ?></option>
			          </select>
			       </div>

			      <input name="search_value" type="text" class="form-control templete_search" placeholder="<?php echo $this->lang->line('Type...'); ?>" aria-label="" aria-describedby="basic-addon2" value="<?php echo $search_value; ?>">

			      <div class="input-group-append">
			        <button class="btn btn-primary" id="search_submit" type="submit"><i class="fas fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
			      </div>

			    </div>
			</form>
		  </div>

		  
		</div><br>

		<?php if (count($templetes) > 0): ?>
			
			<div class="row">
			<?php foreach ($templetes as $key => $templete): ?>
				
				<div class="col-12 col-md-6 col-lg-4">
					<div class="card">

		                <div class="card-header">
		                    <h4><?php echo date('jS F y', strtotime($templete['created_at'])) ?>
		                    	</h4>
		                    <div class="card-header-action">
		                      <div class="btn-group">
		                        <a href="#" class="btn btn-outline-primary edit_campaign" campaign_id="<?php echo $templete['id']; ?>" title="<?php echo $this->lang->line('Edit'); ?>"><i class="fas fa-edit"></i> </a>
		                        <a href="#" class="btn btn-outline-primary delete_campaign" campaign_id="<?php echo $templete['id']; ?>" title="<?php echo $this->lang->line('Delete'); ?>"><i class="fas fa-trash-alt"></i> </a>
		                      </div>
		                    </div>
		                </div>

		                <div class="card-body">
		                    <p class="text-center"><?php echo (strlen($templete['campaign_name']) > 45) ? substr($templete['campaign_name'], 0, 45).'...' : $templete['campaign_name']; ?></p>
		                </div>

	                </div>
				</div>

			<?php endforeach ?>
			</div>

			<div class="float-right">
				<?php echo $page_links; ?>
			</div>

		<?php endif ?>

	</div>

</section>

<div class="modal fade" id="set_auto_comment_templete_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg_fefefe">
	    <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('Auto Comment Template'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body" id="auto_comment_templete_modal_body">

	    	<form action="" method="post" id="comment_templete_form">
	    		
		        <input type="hidden" name="submit_type" value="add" id="submit_type">
		        <input type="hidden" name="campaign_id" id="campaign_id_on_modal">

		        <div class="form-group">
		        	<label for="campaign_name"> <?php echo $this->lang->line("Campaign Name")?> </label>
		        	<input id="campaign_name" name="campaign_name" class="form-control" type="text">
		        </div><br>

		        <div id="comments_section">

					<div class="clearfix add_more_button_block">
						<input type="hidden" id="content_block" value="1">
						<input type="hidden" id="odd_or_even" value="odd">
						<button class="btn btn-outline-primary float-right" id="add_more_message_button"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Add more message') ?></button>
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



<script src="<?php echo base_url('assets/js/system/auto_comment_template.js');?>"></script>