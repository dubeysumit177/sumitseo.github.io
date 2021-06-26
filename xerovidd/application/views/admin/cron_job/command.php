<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-clipboard-list"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>      
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
                    
        <?php
      $text= $this->lang->line("Generate API Key");
      $get_key_text=$this->lang->line("Get Your API Key");
      if(isset($api_key) && $api_key!="")
      {
        $text=$this->lang->line("Re-generate API Key");
        $get_key_text=$this->lang->line("Your API Key");
      }
      if($this->is_demo=='1') $api_key='xxxxxxxxxxxxxxxxxxxxxxxxxx';
      ?>

      <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'cron_job/get_api_action';?>" method="GET">
              <div class="card-body">
                <h4><?php echo $api_key; ?></h4>
                <?php if($api_key=="") echo $this->lang->line("Every cron url must contain the API key for authentication purpose. Generate your API key to see the cron job list."); ?>
              </div>
              <div class="card-footer bg-whitesmoke">
                <button type="submit" name="button" class="btn btn-primary btn-lg btn <?php if($this->is_demo=='1') echo 'disabled';?>"><i class="fas fa-redo"></i> <?php echo $text; ?></button>
              </div>
            </div>
        </form>


      <?php
      if($api_key!="") 
      { ?>
                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-clock"></i> <?php echo $this->lang->line("Membership Expiration Alert & Delete Junk Data");?> <code><?php echo $this->lang->line("Once/Day"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/every_day")."/".$api_key; ?></span></code></pre>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-clock"></i> <?php echo $this->lang->line("Auto Reply Action & Video Upload");?> <code><?php echo $this->lang->line("Once/2 Minutes"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/every_2_minutes")."/".$api_key; ?></span></code></pre>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-clock"></i> <?php echo $this->lang->line("Auto Like,Comment & Subscription");?> <code><?php echo $this->lang->line("Once/5 Minutes"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/every_5_minutes")."/".$api_key; ?></span></code></pre>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-clock"></i> <?php echo $this->lang->line("Auto Reply Prepare, Rank Tracking & Link Wheel");?> <code><?php echo $this->lang->line("Once/10 Minutes"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".site_url("cron_job/every_10_minutes")."/".$api_key; ?></span></code></pre>
                  </div>
                </div>
                

      <?php }?>
    </div>
  </div>
  </div>
</section>