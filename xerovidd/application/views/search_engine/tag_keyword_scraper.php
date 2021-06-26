<?php $this->load->view("search_engine/style.php"); ?>

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-tags"></i> <?php echo $page_title;?></h1>
    <div class="section-header-breadcrumb">
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

            <input type="text" class="form-control" value="<?php echo $video_id ?>" name="video_id" id="video_id" placeholder="<?php echo $this->lang->line('video id...'); ?>">
            <div id="pwindicator" class="pwindicator pw-very-weak">

              <div class="label"><a class="no_decoration" href="#" data-toggle="modal" data-target="#video_id_find_modal"><?php echo $this->lang->line('How to find videos id ?'); ?></a></div>

            </div>
          </div>



        </div>

        <div class="card-footer">
          <button class="btn btn-primary btn-lg" id="search_btn" type="submit"><i class="fas fa-search"></i> <?php echo $this->lang->line("Search");?></button>
          
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
          <div class='card-header-action d_inline' id="tag_download_div">
            <div class='badges'>
              <span class='badge badge-primary'></span>
            </div>                    
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

        <div class="empty-state">
          <img class="img-fluid height_250px" src="<?php echo base_url("assets/img/drawkit/revenue-graph-colour.svg"); ?>">
          <h2 class="mt-0"><?php echo $this->lang->line("Search Youtube Tag or keyword through left sidebar search option"); ?></h2>

        </div>

      </div>
    </div>
  </div>
</div>
<?php } ?>

<div class="modal fade" id="video_id_find_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg_fefefe">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('How to find YouTube video ID?'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ol>
          <li><?php echo $this->lang->line('Visit any youTube video example :'); ?> <a target="_BLANK" href="https://www.youtube.com/watch?v=NXqmvFy9cXE">https://www.youtube.com/watch?v=NXqmvFy9cXE</a> <br><img class="img-thumbnail img-responsive" src="<?php echo base_url("assets/img/video_id.png") ?>"><br><br></li>
          <li><?php echo $this->lang->line('Look at the url , you will find the ID at the end of the url. example: Video ID of the example ur is NXqmvFy9cXE'); ?></b></li>
        </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>

      </div>
    </div>
  </div>
</div>


<script src="<?php echo base_url('assets/js/system/tag_scraper.js');?>"></script>
<script src="<?php echo base_url();?>assets/js/page/clipboard.min.js" type="text/javascript"></script>


