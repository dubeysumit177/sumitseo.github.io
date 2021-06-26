<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-eye"></i> <?php echo $this->lang->line("Link Wheel Details"); ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url("link_wheel"); ?>"><?php echo $this->lang->line('Link Wheel Report'); ?></a></div>
    </div>
  </div>
  <div class="row">
    <?php if($data['error']!='') {?>
      <div class="col-12">
        <div class="alert alert-danger alert-has-icon">
          <div class="alert-icon"><i class="fas fa-times-circle"></i></div>
          <div class="alert-body">
            <div class="alert-title"><?php echo $this->lang->line("Error"); ?></div>
            <?php echo $data['error']; ?>
          </div>
        </div>
      </div>
    <?php } ?>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-primary">
          <i class="fas fa-dharmachakra"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo $this->lang->line("Wheel Name"); ?></h4>
          </div>
          <div class="card-body">
            <small><?php echo $data["wheel_name"]?></small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-primary">
          <i class="fas fa-columns"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo $this->lang->line("Wheel Type"); ?></h4>
          </div>
          <div class="card-body">
            <small><?php echo ucfirst($data["wheel_type"]);?></small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-primary">
          <i class="fas fa-compass"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo $this->lang->line("Status"); ?></h4>
          </div>
          <div class="card-body">
            <small>
	            <?php 
		            if($data["wheel_type"]=="0") $status="<span class='text-warning'>".$this->lang->line('Pending')."</span>";
		            else if($data["wheel_type"]=="1") $status="<span class='text-muted'>".$this->lang->line('Processing')."</span>";
		            else $status="<span class='text-success'>".$this->lang->line('Completed')."</span>";
		            echo $status;
	            ?>
            </small>
          </div>
        </div>
      </div>
    </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-clock"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4><?php echo $this->lang->line("Last Updated"); ?></h4>
              </div>
              <div class="card-body">
                <small><?php echo date('j M y H:i',strtotime($data["last_updated_at"]));?></small>
              </div>
            </div>
          </div>
        </div>
  </div>

  <?php
      	$money_videos=json_decode($data["money_video_id"],true);
		$video_ids=json_decode($data["video_ids"],true);
   ?>

  <div class="row">
  	<div class="col-12 col-md-6">
  		<div class="card card-hero">
  		    <div class="card-header">
  		      <div class="card-icon">
  		        <i class="fas fa-dharmachakra"></i>
  		      </div>
  		      <h4><?php echo count($video_ids); ?></h4>
  		      <div class="card-description"><?php echo $this->lang->line("Wheel Videos"); ?></div>
  		    </div>
  		    <div class="card-body p-0 nicescroll height_515px">
  		      <div class="tickets-list">
  		      	<?php
    			$sl=0;
  		      	foreach ($video_ids as $key => $value) 
  		      	{
  		      		$sl++;
  		      		$link="https://www.youtube.com/watch?v=".$value;

  		      		echo '
			      	<a href="'.$link.'" class="ticket-item" target="_BLANK">
	                    <div class="ticket-title">
	                      <h4>'.$sl.". ".$this->lang->line("Video ID").' : '.$value.'</h4>
	                    </div>
	                    <div class="ticket-info">
	                      <div>'.$link.'</div>
	                    </div>
	                 </a>';
  		      	}
  		      	?>  		      	
  		      	</div>
  		    </div>
  		</div>
  	</div>

  	  	<div class="col-12 col-md-6">
  	  		<div class="card card-hero">
  	  		    <div class="card-header">
  	  		      <div class="card-icon">
  	  		        <i class="fas fa-dollar-sign"></i>
  	  		      </div>
  	  		      <h4><?php echo count($money_videos); ?></h4>
  	  		      <div class="card-description"><?php echo $this->lang->line("Money Videos"); ?></div>
  	  		    </div>
  	  		    <div class="card-body p-0 nicescroll height_515px">
  	  		      <div class="tickets-list">
  	  		      	<?php
  	    			$sl=0;
  	  		      	foreach ($money_videos as $key => $value) 
  	  		      	{
  	  		      		$sl++;
  	  		      		$link="https://www.youtube.com/watch?v=".$value;

  	  		      		echo '
  				      	<a href="'.$link.'" class="ticket-item" target="_BLANK">
  		                    <div class="ticket-title">
  		                      <h4>'.$sl.". ".$this->lang->line("Video ID").' : '.$value.'</h4>
  		                    </div>
  		                    <div class="ticket-info">
  		                      <div>'.$link.'</div>
  		                    </div>
  		                 </a>';
  	  		      	}
  	  		      	?>  		      	
  	  		      	</div>
  	  		    </div>
  	  		</div>
  	  	</div>
  </div>

</section>