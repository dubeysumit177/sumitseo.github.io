<section class="section">
	
	<div class="section-header">
		<h1>
			<i class="far fa-chart-bar"></i> 
			<?php echo $this->lang->line('Channel Analytics'); ?>
		</h1>&nbsp;&nbsp;
		<div class="badges d_inline" title="<?php echo $this->lang->line('Analytics result duration'); ?>">
		    <a href="#" class="badge badge-primary mb-0"><?php echo date('M d, Y', strtotime($start_date)) ?> - <?php echo date('M d, Y', strtotime($end_date)) ?></a>                   
		</div>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><a href="<?php echo base_url("social_accounts"); ?>"><?php echo $this->lang->line('Social Accounts'); ?></a></div>
		  <div class="breadcrumb-item"><?php echo $this->lang->line('Channel Analytics'); ?></div>
		</div>
	</div>

	<div class="section-body">

		    <div class="row">
				<div class="col-12 col-md-6">
                    <h2 class="section-title mt-0" ><a target="_BLANK" href="https://youtube.com/channel/<?php echo isset($channel_info[0]['channel_id']) ?  $channel_info[0]['channel_id'] : ""; ?>"><?php echo isset($channel_info[0]['title']) ? $channel_info[0]['title'] : ""; ?></a></h2>
                    <p class="section-lead">
                      <?php echo $this->lang->line("Channel ID"); ?> : 
                      <?php echo isset($channel_info[0]['channel_id']) ? $channel_info[0]['channel_id'] : ""; ?>
                    </p>
                </div>
				<div class="col-12 col-md-6">
				    <div class="input-group mb-3" id="searchbox">
						<input name="search_value" type="text" class="form-control daterange-cus_1" id="form_search_value" placeholder="<?php echo $this->lang->line('Select Date Range'); ?>" aria-label="" aria-describedby="basic-addon2" value="">
						<div class="input-group-append">
							<button class="btn btn-primary" id="search_submit" type="submit"><i class="fas fa-search"></i> <?php echo $this->lang->line('Search'); ?></button>
						</div>
				    </div>
				</div>		

		    	<div class="col-12">
		    		
			        <div class="card">
			          <div class="card-header">
			            <h4><?php echo $this->lang->line("Views (Day statistics)"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of times that a video was viewed. In a playlist report, the metric indicates the number of times that a video was viewed in the context of a playlist. The YouTube Help Center provides additional information about how views are reported. This is a core metric and is subject to the Deprecation Policy.'); ?>"></i></h4>
			          </div>
			          <div class="card-body">

			          	<div class="text-center">
			          		
			          		
			          	</div>
			          	<input type="hidden" id="views_info" value='<?php echo isset($report_data["views"]) ? $report_data["views"] : ""; ?>' />

			            <canvas id="viewChart" height="100"></canvas>
			          </div>
			        </div>
		    	</div>
		    </div>


		    <div class="row">
		    	<div class="col-md-6 col-12">
			        <div class="card">
			          <div class="card-header">
			            <h4><?php echo $this->lang->line("Estimated minutes watched"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of minutes that users watched videos for the specified channel, content owner, video, or playlist. This is a core metric and is subject to the Deprecation Policy.'); ?>"></i></h4>
			          </div>
			          <div class="card-body">

			          	<div class="text-center">
			          		
			          		
			          	</div>
			          	<input type="hidden" id="estimated_minutes_watched" value='<?php echo isset($report_data["minute_watch"]) ? $report_data["minute_watch"] : ""; ?>' />

			            <canvas id="estimated_minutes_watchedChart" height="200"></canvas>
			          </div>
			        </div>
		    	</div>	

		    	<div class="col-md-6 col-12">
		    		
			        <div class="card">
			          <div class="card-header">
			            <h4><?php echo $this->lang->line("Average view duration (seconds)"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The average length, in seconds, of video playbacks. In a playlist report, the metric indicates the average length, in seconds, of video playbacks that occurred in the context of a playlist. This is a core metric and is subject to the Deprecation Policy.'); ?>"></i></h4>
			          </div>
			          <div class="card-body">

			          	<div class="text-center">
			          		
			          		
			          	</div>
			          	<input type="hidden" id="average_view_duration" value='<?php echo isset($report_data["second_watch"]) ? $report_data["second_watch"] : ""; ?>' />

			            <canvas id="average_view_durationChart" height="200"></canvas>
			          </div>
			        </div>
		    	</div>
		    </div>


	        <div class="row">
	        	
	        	<div class="col-12">
	        		
	    	        <div class="card">
	    	          <div class="card-header">
	    	            <h4><?php echo $this->lang->line("Subscriber Vs Unsubscriber"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of times that users subscribed or unsubscribed to a channel. Channels can gain or lose subscribers in several places, including the video watch page, the channel page, and the guide that appears on the YouTube home page. In channel reports, this metric includes subscribers gained from any of these places. However, in reports that use either the video dimension or the filter parameter to restrict the response to only include data for a particular video.'); ?>"></i></h4>
	    	          </div>
	    	          <div class="card-body">

	    	          	<div class="text-center">
	    	          		
	    	          		
	    	          	</div>
	    	          	<input type="hidden" id="subscriber_vs_unsubscriber_info" value='<?php echo isset($report_data["subscriber_vs_unsubscriber"]) ? $report_data["subscriber_vs_unsubscriber"] : ""; ?>' />

	    	            <canvas id="subscriberUnsubscriberChart" height="100"></canvas>
	    	          </div>
	    	        </div>
	        	</div>
	        </div>

	        <div class="row">
	        	
	        	<div class="col-12">
	        		
	    	        <div class="card">
	    	          <div class="card-header">
	    	            <h4><?php echo $this->lang->line("Likes Vs Dislikes"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of times that users indicated that they liked disliked videos. The number of times that users indicated that they liked a video by giving it a positive rating. This is a core metric and is subject to the Deprecation Policy. The number of times that users indicated that they disliked a video by giving it a negative rating.'); ?>"></i></h4>
	    	          </div>
	    	          <div class="card-body">

	    	          	<div class="text-center">
	    	          		
	    	          		
	    	          	</div>
	    	          	<input type="hidden" id="likes_vs_dislikes_info" value='<?php echo isset($report_data["likes_vs_dislikes"]) ? $report_data["likes_vs_dislikes"] : ""; ?>' />

	    	            <canvas id="likesDislikesChart" height="100"></canvas>
	    	          </div>
	    	        </div>
	        	</div>
	        </div>

	        <div class="row">
	        	
	        	<div class="col-12">
	        		
	    	        <div class="card">
	    	          <div class="card-header">
	    	            <h4><?php echo $this->lang->line("Video Added Vs Removed"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of times that videos were added to any and removed from any YouTube playlists'); ?>"></i></h4>
	    	          </div>
	    	          <div class="card-body">

	    	          	<div class="text-center">
	    	          		
	    	          		
	    	          	</div>
	    	          	<input type="hidden" id="video_added_vs_removed_info" value='<?php echo isset($report_data["video_added_vs_removed"]) ? $report_data["video_added_vs_removed"] : ""; ?>' />

	    	            <canvas id="videoAddedRemovedChart" height="100"></canvas>
	    	          </div>
	    	        </div>
	        	</div>
	        </div>




            <div class="row">
            	<div class="col-md-6 col-12">
        	        <div class="card">
        	          <div class="card-header">
        	            <h4><?php echo $this->lang->line("Comments"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of times that users commented on a video. See the YouTube Help Center for more information. This is a core metric and is subject to the Deprecation Policy.'); ?>"></i></h4>
        	          </div>
        	          <div class="card-body">

        	          	<div class="text-center">
        	          		
        	          		
        	          	</div>
        	          	<input type="hidden" id="comments_info" value='<?php echo isset($report_data["comments"]) ? $report_data["comments"] : ""; ?>' />

        	            <canvas id="commentsChart" height="200"></canvas>
        	          </div>
        	        </div>
            	</div>	

            	<div class="col-md-6 col-12">
            		
        	        <div class="card">
        	          <div class="card-header">
        	            <h4><?php echo $this->lang->line("Shares"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of times that users shared a video through the Share button. See the YouTube Help Center for more information. This is a core metric and is subject to the Deprecation Policy.'); ?>"></i></h4>
        	          </div>
        	          <div class="card-body">

        	          	<div class="text-center">
        	          		
        	          		
        	          	</div>
        	          	<input type="hidden" id="shares_info" value='<?php echo isset($report_data["shares"]) ? $report_data["shares"] : ""; ?>' />

        	            <canvas id="sharesChart" height="200"></canvas>
        	          </div>
        	        </div>
            	</div>
            </div>



            <div class="row">
            	<div class="col-md-6 col-12">
        	        <div class="card">
        	          <div class="card-header">
        	            <h4><?php echo $this->lang->line("Device Type Report With Percentage(%)"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('This statistics aggregates viewing statistics based on the manner in which viewers reached your playlist content. This dimension identifies the physical form factor of the device on which the view occurred. The following list identifies the device types for which the API returns data. You can also use the deviceType dimension as a filter to restrict an operating system report to only contain data for a specific type of device.'); ?>"></i></h4>
        	          </div>
        	          <div class="card-body">

        	          	<div class="text-center">
        	          		
        	          		
        	          	</div>
        	          	<input type="hidden" id="device_type_info" value='<?php echo isset($report_data["device_type_chart_data"]) ? $report_data["device_type_chart_data"] : ""; ?>' />

        	            <canvas id="deviceTypeChart" height="170"></canvas>


						<?php foreach ($tables['device_type_table'] as $key => $value): ?>
							
        	            	

        	            	<div class="mb-4 mt-4">
        	            	  <div class="text-small float-right font-weight-bold text-muted"><?php echo $value['value']; ?></div>
        	            	  <div class="font-weight-bold mb-1"><?php echo $value['device']; ?></div>
        	            	  <div class="progress" data-height="3">
        	            	    <div class="progress-bar" role="progressbar" data-width="<?php echo $value['percentage'] ?>%" aria-valuenow="<?php echo $value['percentage'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
        	            	  </div>
        	            	</div>
    	              
						<?php endforeach ?>

        	            
        	          </div>
        	        </div>
            	</div>	

            	<div class="col-md-6 col-12">
            		
        	        <div class="card">
        	          <div class="card-header">
        	            <h4><?php echo $this->lang->line("Operating System Report With Percentage(%)"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The statistics aggregates viewing statistics based on viewers\' operating systems. This dimension identifies the software system of the device on which the view occurred. The following list identifies the operating systems for which the API returns data. You can also use the operatingSystem as a filter to restrict a device type report to only contain data for a specific operating system.'); ?>"></i></h4>
        	          </div>
        	          <div class="card-body">

        	          	<div class="text-center">
        	          		
        	          		
        	          	</div>
        	          	<input type="hidden" id="operating_system_info" value='<?php echo isset($report_data["operating_system_chart_data"]) ? $report_data["operating_system_chart_data"] : ""; ?>' />

        	            <canvas id="operatingSystemChart" height="170"></canvas>

						<?php foreach ($tables['operating_system_table'] as $key => $value): ?>
							
        	            	

        	            	<div class="mb-4 mt-4">
        	            	  <div class="text-small float-right font-weight-bold text-muted"><?php echo $value['value']; ?></div>
        	            	  <div class="font-weight-bold mb-1"><?php echo $value['operatingSystem']; ?></div>
        	            	  <div class="progress" data-height="3">
        	            	    <div class="progress-bar" role="progressbar" data-width="<?php echo $value['percentage'] ?>%" aria-valuenow="<?php echo $value['percentage'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
        	            	  </div>
        	            	</div>
    	              
						<?php endforeach ?>
        	          </div>
        	        </div>
            	</div>
            </div>





            <div class="row">
            	
            	<div class="col-12">
            		
        	        <div class="card">
        	          <div class="card-header">
        	            <h4><?php echo $this->lang->line("Annotation Impressions"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of annotation impressions'); ?>"></i></h4>
        	          </div>
        	          <div class="card-body">

        	          	<div class="text-center">
        	          		
        	          		
        	          	</div>
        	          	<input type="hidden" id="annotation_impressions_info" value='<?php echo isset($report_data["annotation_impressions"]) ? $report_data["annotation_impressions"] : ""; ?>' />

        	            <canvas id="annotationImpressionsChart" height="100"></canvas>
        	          </div>
        	        </div>
            	</div>
            </div>



            <div class="row">
            	
            	<div class="col-12">
            		
        	        <div class="card">
        	          <div class="card-header">
        	            <h4><?php echo $this->lang->line("Annotation Clicks and Closes Impressions"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of annotations that appeared and could be clicked or closed'); ?>"></i></h4>
        	          </div>
        	          <div class="card-body">

        	          	<div class="text-center">
        	          		
        	          		
        	          	</div>
        	          	<input type="hidden" id="annotation_close_click_impressions_info" value='<?php echo isset($report_data["annotation_close_click_impressions"]) ? $report_data["annotation_close_click_impressions"] : ""; ?>' />

        	            <canvas id="annotationCloseClickImpressionsChart" height="100"></canvas>
        	          </div>
        	        </div>
            	</div>
            </div>

            <div class="row">
            	
            	<div class="col-12">
            		
        	        <div class="card">
        	          <div class="card-header">
        	            <h4><?php echo $this->lang->line("Annotation Clicks and Closes"); ?> <i class="fas fa-info-circle"  data-container="body" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('The number of clicked annotations and the number of closed annotations.'); ?>"></i></h4>
        	          </div>
        	          <div class="card-body">

        	          	<div class="text-center">
        	          		
        	          		
        	          	</div>
        	          	<input type="hidden" id="annotation_clicks_closes_info" value='<?php echo isset($report_data["annotation_clicks_closes"]) ? $report_data["annotation_clicks_closes"] : ""; ?>' />

        	            <canvas id="annotationClicksClosesChart" height="100"></canvas>
        	          </div>
        	        </div>
            	</div>
            </div>

	</div>

</section>


<?php include("application/views/social_accounts/channel_analytics_js.php"); ?>


