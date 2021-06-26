<section class="section">
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-stats">
          <div class="card-stats-title"><?php echo $this->lang->line("Channel statistics"); ?> 
          </div>
          <div class="card-stats-items">
            <div class="card-stats-item">
              <div class="card-stats-item-count"><?php echo $channel_infos[0]['view_count'] ?></div>
              <div class="card-stats-item-label"><?php echo $this->lang->line("Views"); ?></div>
            </div>
            <div class="card-stats-item">
              <div class="card-stats-item-count"><?php echo $channel_infos[0]['video_count'] ?></div>
              <div class="card-stats-item-label"><?php echo $this->lang->line("Videos"); ?></div>
            </div>
            <div class="card-stats-item">
              <div class="card-stats-item-count"><?php echo $channel_infos[0]['subscriber_count'] ?></div>
              <div class="card-stats-item-label"><?php echo $this->lang->line("Subscribers"); ?></div>
            </div>
          </div>
        </div>
        <div class="card-icon shadow-primary bg-primary">
          <i class="fas fa-tv"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo $this->lang->line("Channels"); ?></h4>
          </div>
          <div class="card-body">
            <?php echo $channel_count; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-chart">
          <canvas id="comment_reply-chart" height="80"></canvas>
        </div>
        <div class="card-icon shadow-primary bg-primary">
          <i class="fas fa-reply-all"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo $this->lang->line("Comment Reply"); ?></h4>
          </div>
          <div class="card-body">
            <?php echo $comment_reply_stat['total_count']; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-chart">
          <canvas id="subscription-chart" height="80"></canvas>
        </div>
        <div class="card-icon shadow-primary bg-primary">
          <i class="fas fa-coins"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo $this->lang->line("Subscription"); ?></h4>
          </div>
          <div class="card-body">
            <?php echo $subscription_stat['total_count']; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h4><?php echo $this->lang->line("Keyword Ranks"); ?></h4>
        </div>
        <div class="card-body">
          <canvas id="myChart" height="135"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card gradient-bottom">
        <div class="card-header">
          <h4><?php echo $this->lang->line("Popular Playlists"); ?></h4>
          <div class="card-header-action">
            <a href="<?php echo base_url('social_accounts/playlist_manager'); ?>" class="btn btn-danger"><?php echo $this->lang->line("View More"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
        <div class="card-body" id="top-5-scroll">
          <ul class="list-unstyled list-unstyled-border">
            <?php foreach ($popular_playlist as $single_playlist): ?>
              
              <li class="media">
                <img class="mr-3 rounded" width="55" height="55" src="<?php echo $single_playlist['thumbnails']; ?>" alt="product">
                <div class="media-body">
                  <div class="float-right"><div class="font-weight-600 text-muted text-small"><?php echo date('jS M Y', strtotime($single_playlist['published_at'])); ?></div></div>
                  <div class="media-title"><?php echo (strlen($single_playlist['title']) > 15) ? substr($single_playlist['title'], 0, 15) : $single_playlist['title']; ?></div>
                  <div class="mt-1">
                    <div class="budget-price">
                      <div class="budget-price-square bg-primary" data-width="20%"></div>
                      <div class="budget-price-label"><?php echo $single_playlist['itemCount']; ?></div>
                    </div>
                  </div>
                </div>
              </li>

            <?php endforeach ?>
          </ul>
        </div>
        <div class="card-footer pt-3 d-flex justify-content-center">
          <div class="budget-price justify-content-center">
            <div class="budget-price-square bg-primary" data-width="20"></div>
            <div class="budget-price-label"><?php echo $this->lang->line("Video count"); ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4><?php echo $this->lang->line("Best Videos"); ?></h4>
        </div>
        <div class="card-body min_height_310px">
          <div class="owl-carousel owl-theme" id="products-carousel">
            <?php foreach ($popular_videos as $single_video): ?>
              
              <div>
                <div class="product-item pb-3">
                  <div class="product-image">
                    <img alt="image" src="<?php echo $single_video['image_link']; ?>" class="img-fluid height_80px">
                  </div>
                  <div class="product-details">
                    <div class="product-name"><?php echo (strlen($single_video['title']) > 15) ? substr($single_video['title'], 0, 15).'...' : $single_video['title']; ?></div>
                    <div class="text-muted text-small"><?php echo $single_video['likeCount']; ?> <?php echo $this->lang->line("Likes"); ?></div>
                    <div class="product-cta">
                      <a  target="_BLANK" href="<?php echo 'https://www.youtube.com/watch?v='. $single_video['video_id'] ?>" class="btn btn-outline-primary"> <?php echo $this->lang->line("Watch Video"); ?></a>
                    </div>
                  </div>  
                </div>
              </div>

            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4><?php echo $this->lang->line("Recent Uploaded Videos"); ?></h4>
        </div>
        <div class="card-body min_height_310px">
          <ul class="list-unstyled list-unstyled-border">
            <?php foreach ($latest_uploaded_videos as $single_video): ?>
              
              <li class="media">
                <img class="mr-3 rounded-circle" width="50" src="<?php echo $single_video['profile_image']; ?>" alt="avatar">
                <div class="media-body">
                  <!-- <div class="float-right text-primary">Now</div> -->
                  <div class="media-title"><?php echo (strlen($single_video['title']) > 15) ? substr($single_video['title'], 0, 15).'...' : $single_video['title']; ?></div>
                  <span class="text-small text-muted"><?php echo (strlen($single_video['description']) > 100) ? substr($single_video['title'], 0, 100).'...' : $single_video['description']; ?></span>
                </div>
              </li>

            <?php endforeach ?>
          </ul>
          <div class="text-center pt-1 pb-1">
            <a href="<?php echo base_url('social_accounts/upload_video_list'); ?>" class="btn btn-primary btn-lg btn-round">
              <?php echo $this->lang->line("View All"); ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4><?php echo $this->lang->line("Latest Comment Reply"); ?></h4>
          <div class="card-header-action">
            <a href="<?php echo base_url('responder/auto_reply_campaign'); ?>" class="btn btn-danger"><?php echo $this->lang->line("View More"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
        <div class="card-body p-0 min_height_375px">
          <div class="table-responsive table-invoice">
            <table class="table table-striped">
              <tr>
                <th><?php echo $this->lang->line("Comment Author"); ?></th>
                <th><?php echo $this->lang->line("Comment Text"); ?></th>
                <th><?php echo $this->lang->line("Reply Text"); ?></th>
                <th><?php echo $this->lang->line("Replied at"); ?></th>
              </tr>

              <?php foreach ($last_comment_replies as $single_reply): ?>
                
                <tr>
                  <td  class="font-weight-600"><?php echo $single_reply['comment_author']; ?></td>
                  <td><?php echo $single_reply['comment_text']; ?></td>
                  <td><?php echo $single_reply['reply_to_be_given']; ?></td>
                  <td><?php echo date('jS F y, H:i', strtotime($single_reply['replied_at'])); ?></td>
                </tr>

              <?php endforeach ?>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-hero">
        <div class="card-header">
          <div class="card-icon">
            <i class="fas fa-cloud-upload-alt"></i>
          </div>
          <h4><?php echo (count($upload_video_campaigns) > 0) ? count($upload_video_campaigns) : "0"; ?></h4>
          <div class="card-description"><?php echo $this->lang->line("Video Uploading Campaigns"); ?></div>
        </div>
        <div class="card-body p-0 min_height_290px">
          <div class="tickets-list">
            <?php foreach ($upload_video_campaigns as $single_campaign): ?>
              
              <a href="<?php echo ($single_campaign['video_id'] == '') ? "#" : 'https://www.youtube.com/watch?v='.$single_campaign['video_id']; ?>" class="ticket-item">
                <div class="ticket-title">
                  <h4><?php echo $single_campaign['title']; ?></h4>
                </div>
                <div class="ticket-info">
                  <div class="font-weight-600 <?php echo ($single_campaign['upload_status'] == '1') ? 'text-success' : 'text-warning'; ?>">
                    <?php echo ($single_campaign['upload_status'] == '1') ? $this->lang->line("Completed") : $this->lang->line("Pending"); ?>
                  </div>
                  <div class="bullet"></div>
                  <div class="text-primary"><?php echo date('jS M y', strtotime($single_campaign['upload_time'])); ?></div>
                </div>
              </a>

            <?php endforeach ?>
            <a href="<?php echo base_url('social_accounts/upload_video_list'); ?>" class="ticket-item ticket-more">
              <?php echo $this->lang->line("View All"); ?> <i class="fas fa-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<script src="<?php echo base_url(); ?>assets/js/page/index.js"></script>
<?php include("application/views/include/dashboard_js.php"); ?>