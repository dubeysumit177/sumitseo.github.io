<script src="<?php echo base_url();?>assets/js/page/clipboard.min.js" type="text/javascript"></script>
<?php $this->load->view("search_engine/style.php"); ?>

<section class="section">
  <div class="section-header">
    <h1><i class="fab fa-youtube"></i> <?php echo $page_title; ?> </h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("Search Engine");?></div>
      <div class="breadcrumb-item"><a href="<?php echo base_url('search_engine/playlist'); ?>"><?php echo $this->lang->line('Playlist Search'); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>
</section>

<div class="row multi_layout">
  <div class="col-12 col-md-12 col-lg-12 colmid">
    <div id="custom_spinner"></div>

    <div id="middle_column_content">
      <?php echo $output; ?>
    </div>
  </div>
</div>

<script src="<?php echo base_url();?>plugins/scrollreveal/scrollreveal.js" type="text/javascript"></script>