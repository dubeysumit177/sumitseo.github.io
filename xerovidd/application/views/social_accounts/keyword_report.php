<?php include("application/views/include/col-3_css.php"); ?>
<?php
$drop_menu ='<a href="javascript:;" id="date_range" class="btn btn-outline-primary daterange-btn icon-left btn-icon"><i class="fas fa-calendar"></i> '.$this->lang->line("Choose Date").'</a><input type="hidden" id="date_range_val" name="date_range_val">';
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/system/select2_100.css');?>">

<section class="section">
	<div class="section-header">
		<h1><i class="fab fa-youtube"></i> <?php echo $page_title;?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo $this->lang->line("Video Rank Tracking");?></div>
			<div class="breadcrumb-item"><?php echo $page_title;?></div>
		</div>
	</div>
</section>
	
<div class="row multi_layout">

	<div class="col-12 col-md-4 col-lg-3 collef">
		<div class="card main_card">
			<form method="POST" id="rank_search_form">
				<div class="card-header">
					<div class="col-12 padding-0">
						<h4><i class="fa fa-search"></i> <?php echo $this->lang->line("Search"); ?></h4>
						</div>

					</div>
					<div class="card-body">

						<div class="form-group">
							<label>
								<?php echo $this->lang->line("Select Keyword"); ?> *
							</label>
							<?php $keywords['']=$this->lang->line("select keyword"); ?>
							<?php echo form_dropdown('keyword', $keywords,set_value('keyword'), 'class="form-control select2" id="keyword"' ); ?> 
						</div>

						<div class="form-group">
							<label><?php echo $this->lang->line('From date'); ?> *</label>
							<input type="text" class="form-control datepicker" autocomplete="off" name="from_date" id="from_date" placeholder="<?php echo $this->lang->line('From date'); ?>">


						</div>
						<div class="form-group">
							<label><?php echo $this->lang->line('To date'); ?> *</label>
							<input type="text" class="form-control datepicker" autocomplete="off" name="to_date" id="to_date" placeholder="<?php echo $this->lang->line('To date'); ?>">
						</div>
					</div>

					<div class="card-footer">
						<button class="btn btn-primary btn-lg" id="search_btn" type="submit"><i class="fa fa-search"></i> <?php echo $this->lang->line("Search");?></button>
					</div>

				</form>
			</div>          
		</div>

		<div class="col-12 col-md-8 col-lg-9 colmid">
			<div id="custom_spinner"></div>
			<div id="middle_column_content">

				<div class="card">
					<div class="card-header">
						<h4> <i class="fa fa-youtube"></i> <?php echo $this->lang->line('Search Results'); ?></h4>

					</div>
				</div>
				<div class="col-12 col-sm-6 col-md-6 col-lg-12" id="nodata">

					<div class="empty-state">
						<img class="img-fluid height_150px" src="<?php echo base_url("assets/img/drawkit/revenue-graph-colour.svg"); ?>" src=" " alt="image">
						<h2 class="mt-0"><?php echo $this->lang->line("Search Youtube videos rank through left sidebar filter"); ?></h2>

					</div>

				</div>
			</div>
		</div>
	</div>


<script src="<?php echo base_url('assets/js/system/keyword_report.js');?>"></script>

