<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fas fa-reply-all"></i> <?php echo $this->lang->line('Auto Reply Template'); ?></h1>
		<div class="section-header-button">
			<a href="<?php echo base_url('responder/create_auto_reply_template') ?>" class="btn btn-primary create_campaign"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Create Template'); ?></a>
	    </div>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><a href="<?php echo base_url("responder/template_manager"); ?>"><?php echo $this->lang->line('Template Manager'); ?></a></div>
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Auto Reply Template'); ?></div>
		</div>
	</div>

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
		                <th><?php echo $this->lang->line("ID"); ?></th>      
		                <th><?php echo $this->lang->line("Name"); ?></th>      
		                <th><?php echo $this->lang->line("Offensive	Comment ?"); ?></th>      
		                <th><?php echo $this->lang->line("Reply Type"); ?></th>      
		                <th><?php echo $this->lang->line("Multiple Reply ?"); ?></th>      
		                <th><?php echo $this->lang->line("Created At"); ?></th>
		                <th class="min_width_180px"><?php echo $this->lang->line("Actions"); ?></th>
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


<script src="<?php echo base_url('assets/js/system/auto_reply_template.js');?>"></script>