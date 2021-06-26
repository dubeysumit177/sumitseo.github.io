<link rel="stylesheet" href="<?php echo base_url('assets/css/system/account_list.css');?>">
<section class="section">
	<div class="section-header">
		<h1><i class="far fa-user-circle"></i> <?php echo $this->lang->line('Social Accounts'); ?></h1>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Social Accounts'); ?></div>
		</div>
	</div>

	<?php 

		if($this->session->userdata('login_error')!=''){
			echo "<div class='alert alert-danger text-center'><i class='fas fa-times-circle'></i> ".$this->session->userdata('login_error')."</div>";
			$this->session->unset_userdata('login_error');
		}

		if($this->session->userdata('app_settings_error')==1) {

			echo "<div class='alert alert-danger text-center'><i class='fas fa-times-circle'></i> ".$this->lang->line("Something went wrong..")."</div>";
			$this->session->set_userdata('app_settings_error', '0');
		}


		if($this->session->userdata('limit_cross') != '') {

			echo "<div class='alert alert-danger text-center'><i class='fas fa-times-circle'></i> ".$this->session->userdata('limit_cross')."</div>";
			$this->session->unset_userdata('limit_cross');
		}

	 ?>

	<div class="section-body">
		
		<div class="row">

				
			<div class="col-12">
				<div class="card">
					<div class="card-header">
					    <h4><i class="fab fa-youtube"></i> <?php echo $this->lang->line('YouTube Channels') ?></h4>
					    <div class="card-header-action">
					    	<a href='<?php echo base_url("social_accounts/login_with_google_panel"); ?>' <?php if($this->is_demo == '1' && $this->session->userdata("user_type")=="Admin") echo 'id="stop_import"'; ?> class='btn btn-outline-primary'><i class='fas fa-plus-circle'></i> <?php echo $this->lang->line('Import Channel'); ?></a>
					    </div>
					</div>
					<div class="card-body">

					    	<?php

					    	if(empty($youtube_channel_list))
					    	echo '<div class="empty-state">
						      <img class="img-fluid height_200px" src="'.base_url('assets/img/drawkit/drawkit-nature-man-colour.svg').'" alt="image">
						      <h2 class="mt-0">'.$this->lang->line("We could not find any data.").'</h2>
						      <a href="'.base_url('social_accounts/login_with_google_panel').'" class="btn btn-outline-primary mt-4"><i class="fas fa-cloud-download-alt"></i> '.$this->lang->line("Import Account").'</a>
						    </div>';

					    	$loop=0;
					    	foreach ($youtube_channel_list as $key => $single_channel): 
					    		$loop++;
					    		?>
			    				<div class="row">			    					
			    					<?php
			    					$cover_image = !empty($single_channel["cover_image"]) ? $single_channel["cover_image"] : base_url('assets/img/example-image.jpg');
			    					$col1 = '
			    					<div class="col-12 col-lg-5">
			    						<article class="article article-style-c">			    		                    
			    		                    <a href="'.base_url('social_accounts/youtube_channel/'.$single_channel['channel_id']).'"><img src="'.$cover_image.'" class="img-thumbnail height_275px width_100"/></a>
			    		                </article>
			    					</div>';

			    					$col2 = '
			    					<div class="col-12 col-lg-7">
			    						<article class="article article-style-c shadowed">
			    		                    <div class="article-details">
				    		                  	<div class="article-user">
				    		                  	  <a target="_BLANK" href="https://youtube.com/channel/'.$single_channel['channel_id'].'"><img alt="image" src="'.$single_channel["profile_image"].'"></a>
				    		                  	  
				    		                  	  <div class="article-user-details">
				    		                  	    <div class="user-detail-name">
				    		                  	      <a target="_BLANK" href="https://youtube.com/channel/'.$single_channel['channel_id'].'">'.$single_channel["title"].'</a>
				    		                  	    </div>
				    		                  	    <div class="text-job">
				    		                  	    <a href="'.base_url('social_accounts/youtube_channel/'.$single_channel['channel_id']).'">'.$this->lang->line('Video Manager').'</a>
				    		                  	    <span class="float-right pointer delete_channel"  social_media="youtube" table_id="'.$single_channel['id'].'"><i class="fas fa-times-circle text-danger"></i> '.$this->lang->line("unlink").'</span>
				    		                  	    </div>
				    		                  	  </div>
				    		                  	</div>			    		                    
			    		                    </div>

	      		    		                <div class="row p-3 d-none d-md-flex">
	          		                            <div class="col-md-4 col-12">
	          		                              <div class="card card-statistic-1 bordered no_shadow">
	          		                                <div class="card-icon">
	          		                                  <i class="far fa-eye text-primary"></i>
	          		                                </div>
	          		                                <div class="card-wrap">
	          		                                  <div class="card-header">
	          		                                    <h4>'.$this->lang->line('Views').'</h4>
	          		                                  </div>
	          		                                  <div class="card-body">	          		                                    
	          		                                    '.$single_channel['view_count'].'
	          		                                  </div>
	          		                                </div>
	          		                              </div>
	          		                            </div>
	          		                            <div class="col-md-4 col-12">
	          		                              <div class="card card-statistic-1 bordered no_shadow">
	          		                                <div class="card-icon">
	          		                                  <i class="fas fa-video text-primary"></i>
	          		                                </div>
	          		                                <div class="card-wrap">
	          		                                  <div class="card-header">
	          		                                    <h4>'.$this->lang->line('Videos').'</h4>
	          		                                  </div>
	          		                                  <div class="card-body">
	          		                                    '.$single_channel['video_count'].'
	          		                                  </div>
	          		                                </div>
	          		                              </div>
	          		                            </div>
	          		                            <div class="col-md-4 col-12">
	          		                              <div class="card card-statistic-1 bordered no_shadow">
	          		                                <div class="card-icon">
	          		                                  <i class="fas fa-user-circle text-primary"></i>
	          		                                </div>
	          		                                <div class="card-wrap">
	          		                                  <div class="card-header">
	          		                                    <h4>'.$this->lang->line('Subscribers').'</h4>
	          		                                  </div>
	          		                                  <div class="card-body">
	          		                                    '.$single_channel['subscriber_count'].'
	          		                                  </div>
	          		                                </div>
	          		                              </div>
	          		                            </div>
	          		                         </div>
			    		                </article>			    		                
			    					</div>';
			    					echo $col1.$col2;
				    				?>
				    			</div>							    
					    	<?php endforeach ?>
					</div>
				</div>
			</div>


			
			


		</div>

	</div>

</section>


<script src="<?php echo base_url('assets/js/system/account_list.js');?>"></script>