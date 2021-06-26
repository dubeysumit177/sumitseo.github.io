
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-cloud-download-alt"></i> <?php echo $this->lang->line('YouTube Channel Import'); ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url('social_accounts/index'); ?>"><?php echo $this->lang->line('Social Accounts'); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>
  <div class="section-body">
    <?php if($no_app=='0') 
    { ?>
      <div class="hero bg-primary text-white">
        <div class="hero-inner">
          <h2><?php echo $this->lang->line("You are just one step away !"); ?></h2>
          <p class="lead"><?php echo $this->lang->line("Click the button below and import your YouTube channel."); ?></p>
          <br>
          <div class="row">
            <div class="col-12 col-lg-3">
              <?php echo $youtube_login_button; ?>
            </div>
          </div>
        </div>
      </div>
    <?php 
    } 
    else 
    { ?>
      <div class="row">
        <div class="col-12">
          <?php echo $youtube_login_button; ?>
        </div>
      </div>
    <?php 
    } ?>

  </div>
</section>

<?php include("application/views/social_accounts/google_login_panel_style.php"); ?>