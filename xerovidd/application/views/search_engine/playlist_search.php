<?php $this->load->view("search_engine/style.php"); ?>

<?php
$drop_menu ='<a href="javascript:;" id="date_range" class="btn btn-outline-primary daterange-btn icon-left btn-icon"><i class="fas fa-calendar"></i> '.$this->lang->line("Choose Date").'</a><input type="hidden" id="date_range_val" name="date_range_val">';

?>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-list"></i> <?php echo $page_title;?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("Search Engine");?></div>
      <div class="breadcrumb-item"><?php echo $page_title;?></div>
    </div>
  </div>
</section>

<?php 
if(!empty($no_app_error)) echo $no_app_error;
else
{ ?>
<div class="row multi_layout">

  <div class="col-12 col-md-4 col-lg-3 collef">
    <div class="card main_card no_shadow">
      <form method="POST" id="video_search_form_data">
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
                <input type="radio" name="limit" id="limit" value="50" class="selectgroup-input" checked>
                <span class="selectgroup-button"><?php echo $this->lang->line('50'); ?></span>
              </label>
              <label class="selectgroup-item">
                <input type="radio" name="limit" id="limit" value="100" class="selectgroup-input">
                <span class="selectgroup-button"><?php echo $this->lang->line('100'); ?></span>
              </label>
              <label class="selectgroup-item">
                <input type="radio" name="limit" id="limit" value="150" class="selectgroup-input">
                <span class="selectgroup-button"><?php echo $this->lang->line('150'); ?></span>
              </label>
              <label class="selectgroup-item">
                <input type="radio" name="limit" id="limit" value="200" class="selectgroup-input">
                <span class="selectgroup-button"><?php echo $this->lang->line('200'); ?></span>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label><?php echo $this->lang->line('Channel ID'); ?></label>
            <input type="text" value="<?php echo $channel_id; ?>" class="form-control" name="channel_id" id="channel_id" placeholder="<?php echo $this->lang->line('Channel ID'); ?>">
          </div>

          <div class="form-group">
            <label class="d-block"><?php echo $this->lang->line('Date'); ?></label>
            <?php echo $drop_menu; ?>
          </div>

        </div>

        <div class="card-footer">
          <button class="btn btn-primary btn-lg" id="search_btn" type="submit"><i class="fas fa-search"></i> <?php echo $this->lang->line("Search");?></button>
          <!-- <button class="btn btn-secondary btn-md float-right" onclick="goBack('search_engine/playlist')" type="button"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Cancel"); ?></button> -->
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
          <img class="img-fluid height_250px" src="<?php echo base_url("assets/img/drawkit/revenue-graph-colour.svg"); ?>" alt="image">
          <h2 class="mt-0"><?php echo $this->lang->line("Search YouTube playlist using the filtering options."); ?></h2>

        </div>

      </div>
    </div>
  </div>
</div>
<?php } ?>



<?php include("application/views/search_engine/playlist_search_js.php"); ?>
<script src="<?php echo base_url();?>assets/js/page/clipboard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/scrollreveal/scrollreveal.js" type="text/javascript"></script>

