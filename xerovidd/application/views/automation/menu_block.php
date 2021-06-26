<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-th-large"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
     
      <?php  if($this->session->userdata('user_type') == 'Admin' || in_array(6,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-reply-all"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Auto Reply Template"); ?></h4>
            <p><?php echo $this->lang->line(""); ?></p>
            <a href="<?php echo base_url("responder/auto_reply_template"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php  if($this->session->userdata('user_type') == 'Admin' || in_array(12,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-comment-dots"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Auto Comment Template"); ?></h4>
            <p><?php echo $this->lang->line(""); ?></p>
            <a href="<?php echo base_url("responder/auto_comment_template"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?> 

     

    </div>
  </div>
</section>

