<?php

require_once("Home.php"); // including home controller

/**
* class admin_config
* @category controller
*/
class Search_engine extends Home
{
    /**
    * load constructor method
    * @access public
    * @return void
    */
  public function __construct()
  {
        parent::__construct();

        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login_page', 'location');
        }
        $this->important_feature();
        $this->member_validity();
  }

   
  /**
   * video($channel_id="0", $is_iframe = "")
   * function was here. It has moved to Home.php
   */
  


  public function youtube_video_search_action()
  {
     
      $this->ajax_check();

      $keyword=$this->input->post("keyword",true);
      $limit=$this->input->post("limit",true);
      $event_type=$this->input->post("event_type",true);
      $radius=$this->input->post("radius",true);
      $latitude=$this->input->post("location_lat",true);
      $longitude=$this->input->post("location_long",true);
      $channel_id=$this->input->post("channel_id",true);
      $publish_before_after=$this->input->post("date_range_val",true);
      $order=$this->input->post("order",true);
      $duration=$this->input->post("duration",true);
      $video_type=$this->input->post("video_type",true);
      $dimension=$this->input->post("dimension",true);
      $defination=$this->input->post("defination",true);
      $license=$this->input->post("license",true);


      $publish_before_after = explode('|', $publish_before_after);
      $publish_after = isset($publish_before_after[0])  ? $publish_before_after[0]:"";
      $publish_before = isset($publish_before_after[1]) ? $publish_before_after[1]:"";

      $is_iframe = $this->input->post('is_iframe', true);

   
      if($keyword=="" && $channel_id=="")
      {
          echo $this->lang->line("Please Enter Keyword or Channel ID");
          exit();
       
      }

      $location="";
      if($latitude!="" && $longitude!="") 
      {          
        $location=$latitude.",".$longitude;
        if($radius=="") $radius="1000km";
      }

      $this->load->library('youtube_library');

      $youtube_data=$this->youtube_library->get_youtube_video($keyword,$limit,$channel_id,$location,$radius,$order,$publish_after,$publish_before,$duration,$video_type,$event_type,$dimension,$defination,$license);

      $this->_insert_usage_log($module_id = 14, $request = 1);
      $final_data = array();
      $total_video = 0;

      if(!isset($youtube_data['error']))
      {
        $video_ids=is_array($youtube_data) ? array_column($youtube_data, 'video_id') : array();
        $total_video=count($video_ids);

        $video_ids=implode(",",$video_ids);
        $final_data=$this->youtube_library->get_video_by_id($video_ids);
      }
      $output='<script src="'.base_url().'assets/js/system/video_search_action.js"></script>';

      if($is_iframe=='1')
      {
          $temp = $this->basic->get_data("youtube_channel_info",array("where"=>array("id"=>$this->session->userdata('youtube_channel_info_table_id'),"user_id"=>$this->user_id)),"channel_id");
          $selected_channel_id = isset($temp[0]["channel_id"]) ? $temp[0]["channel_id"] : "";
          $header_action = "<button id='add_selected_videos' type='button' channel_id='".$selected_channel_id."' playlist_id='".$this->session->userdata("playlist_manager_playlist_list_clicked_id")."' class='btn btn-lg btn-outline-primary float-right'><i class='fas fa-plus-circle'></i> ".$this->lang->line('Add to Playlist')."</button>";
      }
      else
      {
        $header_action = "<div class='badges'>
                            <span class='badge badge-primary'>".$total_video."</span>
                          </div>";
      }
      
      $output .= "<div class='card no_shadow'>
                  <div class='card-header'>
                    <h4> <i class='fas fa-video'></i> ".$this->lang->line("Search Results")."</h4>
                    <div class='card-header-action'>
                      ".$header_action."                   
                    </div>
                  </div>
                </div>
                <div class='row video_lists_css' id='video_lists'>";

      if(isset($youtube_data['error'])) 
        $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                    <div class='empty-state'>
                         <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                         <h2 class='mt-0'> ".$youtube_data['message']."</h2>
                         <a href='' class='btn btn-outline-primary mt-4'><i class='fas fa-search'></i> ".$this->lang->line('try once again')."</a>
                       </div>
                    
                   </div>";
      else if($total_video==0) 
        $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                    <div class='empty-state'>
                         <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                         <h2 class='mt-0'> ".$this->lang->line('We could not find any data.')."</h2>
                         <a href='' class='btn btn-outline-primary mt-4'><i class='fas fa-search'></i> ".$this->lang->line('try once again')."</a>
                       </div>
                    
                   </div>";
      
      foreach ($final_data as $value) 
      {
         if(isset($value["items"]))
         {  
            $i = 0;
           foreach ($value["items"] as $row) 
           {
               $title=isset($row["snippet"]["title"]) ? $row["snippet"]["title"] : "";
               $description=isset($row["snippet"]["description"]) ? $row["snippet"]["description"] : "";
               $published_at=isset($row["snippet"]["publishedAt"]) ? $row["snippet"]["publishedAt"] : "";
               $published_at=date("Y-m-d",strtotime($published_at));
               $tags=isset($row["snippet"]["tags"]) ? implode(',', $row["snippet"]["tags"]) : "";
               $duration=isset($row["contentDetails"]["duration"]) ? $row["contentDetails"]["duration"] : "";
               $duration=str_replace(array("PT","H","M","S"),array("","h ","m ","s "), $duration);
               $views=isset($row["statistics"]["viewCount"]) ?  $row["statistics"]["viewCount"] : 0;
               $likes=isset($row["statistics"]["likeCount"]) ?  $row["statistics"]["likeCount"] : 0;
               $dislikes=isset($row["statistics"]["dislikeCount"]) ?  $row["statistics"]["dislikeCount"] : 0;
               $favourite=isset($row["statistics"]["favoriteCount"]) ?  $row["statistics"]["favoriteCount"] : 0;
               $comments=isset($row["statistics"]["commentCount"]) ?  $row["statistics"]["commentCount"] : 0;
               $thumb=isset($row["snippet"]["thumbnails"]["medium"]["url"]) ?  $row["snippet"]["thumbnails"]["medium"]["url"] : "";
               $id=isset($row["id"]) ?  $row["id"] : "";
               $real_url="https://www.youtube.com/watch?v={$id}";
       
               $url="https://www.youtube.com/embed/{$id}?rel=0&wmode=transparent&autoplay=1";
               $download_video=base_url("social_accounts/download_video/").$id."/".$title;
               $tag_blink=base_url("search_engine/tag_keyword_scraper/{$id}");
               $download_sub_link=base_url("search_engine/subtitle_downloader/{$id}");
               $download_video="<a href='{$download_video}' target='_BLANK' class='dropdown-item has-icon download_video''><i class='fas fa-cloud-download-alt'></i> ".$this->lang->line("Download Video")."</a>";

               $download_tag_blink="<a target='_BLANK' href='{$tag_blink}' class='dropdown-item has-icon' submit_type='edit'>
                <i class='fas fa-tags'></i> ".$this->lang->line("Tag / Keyword")." </a>";
           
               $copy_url="<a href='#' data-clipboard-text='{$real_url}' class='dropdown-item has-icon copy_class' submit_type='edit'><i class='fas fa-cut'></i> ".$this->lang->line("Copy url")." </a>";

               if(strlen($title)>20) 
                $dot= '...'; 
               else 
                $dot= "";

               if(strlen($description)>25)
                $des = '...';
               else
                $des = "";

    
               $output .= "<div class='col-12 col-sm-6 col-md-6 col-lg-4 samsu'>
                            <article class='article profile-widget'>
                              <div class='article-header'>

                                <div class='article-image youtube cboxElement' data-background='{$thumb}' video_url='{$url}' style='background-image: url({$thumb});'>
                                </div>
                                <div class='check_box_background text-center'>
                                  <div class='profile-widget-item white_color'>
                                    <div class='profile-widget-item-label'><i class='fa fa-eye white_color'></i></div>
                                    <div class='profile-widget-item-value'>".thousandsCurrencyFormat($views)."</div>
                                  </div>

                                </div>";

              if ($is_iframe == '1') 
                  $output .= '
                        <div class="video_option_background">
                              <div class="check_box right_unset">
                                <input id="box_'. $i .'" type="checkbox" name="selected_videos" value="'. $id .'" class="regular-checkbox">
                                <label for="box_'. $i++ .'" class="cursor_pointer"></label>
                              </div>
                        </div>
                  ';
              else 
                  $output .="
                                <div class='video_option_background'>
                                  <div class='float-right dropdown option_dropdown'>
                                    <a href='#' data-toggle='dropdown' aria-expanded='false'><i class='fas fa-ellipsis-h'></i></a>

                                    <div class='dropdown-menu youtube_video_search_action_dropdown_menu' x-placement='bottom-start'>                                      
                                      {$download_tag_blink}
                                      {$copy_url}
                                      </div>
                                    </div>
                                  </div>

                                  <div class='article-title'>
                                    <h2 title='".$title."' class='white_color'>".substr($title, 0, 20).$dot."</h2>
                                    <div class='check_box_background text-center top_8px'>
                                      <div class='profile-widget-item white_color'>
                                        <div class='profile-widget-item-label'><i class='fa fa-clock DD4B39_color'></i></div>
                                        <div class='profile-widget-item-value'>".$duration."</div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                            ";

              $output .="
                                <div class='article-details padding_0'>



                                  <p title='".$description."' class='description_info'>".substr($description, 0, 25).$des."</p>

                                  <div class='profile-widget-items'>

                                    <div class='profile-widget-item'>
                                      <div class='profile-widget-item-label'> <i title=".$this->lang->line('Likes')." class='fa fa-thumbs-up'></i> </div>
                                      <div class='profile-widget-item-value'>".thousandsCurrencyFormat($likes)."</div>
                                    </div>
                                    <div class='profile-widget-item'>
                                      <div class='profile-widget-item-label'> <i title=".$this->lang->line('Dislikes')." class='fa fa-thumbs-down'></i> </div>
                                      <div class='profile-widget-item-value'>".thousandsCurrencyFormat($dislikes)."</div>
                                    </div>
                                    <div class='profile-widget-item'>
                                      <div class='profile-widget-item-label'><i title=".$this->lang->line('Commnets')." class='fa fa-comments'></i></div>
                                      <div class='profile-widget-item-value'>".thousandsCurrencyFormat($comments)."</div>
                                    </div>
                                  </div>

                                </div>
                              </article>
                            </div>";
            $i++;
           }
       
         
         }
      }
      $output.="</div>";


      if ($is_iframe == '1') {

        $output .="<script src='".base_url()."assets/js/system/video_search_action_iframe.js'></script>";

      }
      


      $page_encoding =  mb_detect_encoding($output);
      if(isset($page_encoding)){
        $output = @iconv( $page_encoding, "utf-8//IGNORE", $output );
      } 
       echo $output;

  }

  public function tag_keyword_scraper($id="")
  {
    
    $data['page_title'] = $this->lang->line("YouTube Keyword Finder");
    $data['body'] = "search_engine/tag_keyword_scraper";
    $data["video_id"]=$id;
    $data['no_app_error'] = $this->check_app_settings();
    $this->_viewcontroller($data);


  }

  public function tag_keyword_scraper_action()
  {
    $this->ajax_check();
    $video_id=$this->input->post("video_id");    
    $this->load->library("youtube_library");
    $download_id = $this->session->userdata('download_id');
    $final_data=$this->youtube_library->get_video_by_id($video_id);

    if(!is_array($final_data)) $final_data = array();

    $total_video = isset($final_data[0]['pageInfo']['totalResults']) ? $final_data[0]['pageInfo']['totalResults'] : 0;
    
    $output='<script src="'.base_url().'assets/js/system/video_search_action.js"></script>';

        
    $tags=array();
    $title="";

    foreach ($final_data as $value) 
    {
       if(isset($value["items"]))
       {
         foreach ($value["items"] as $row) 
         {
             $title=isset($row["snippet"]["title"]) ? $row["snippet"]["title"] : "";
             $description=isset($row["snippet"]["description"]) ? $row["snippet"]["description"] : "";
             $tags=isset($row["snippet"]["tags"]) ? $row["snippet"]["tags"] : array();
             $duration=isset($row["contentDetails"]["duration"]) ? $row["contentDetails"]["duration"] : "";
             $duration=str_replace(array("PT","H","M","S"),array("","h ","m ","s "), $duration);
             $views=isset($row["statistics"]["viewCount"]) ?  $row["statistics"]["viewCount"] : 0;
             $likes=isset($row["statistics"]["likeCount"]) ?  $row["statistics"]["likeCount"] : 0;
             $dislikes=isset($row["statistics"]["dislikeCount"]) ?  $row["statistics"]["dislikeCount"] : 0;
             $favourite=isset($row["statistics"]["favoriteCount"]) ?  $row["statistics"]["favoriteCount"] : 0;
             $comments=isset($row["statistics"]["commentCount"]) ?  $row["statistics"]["commentCount"] : 0;
             $thumb=isset($row["snippet"]["thumbnails"]["medium"]["url"]) ?  $row["snippet"]["thumbnails"]["medium"]["url"] : "";
             $id=isset($row["id"]) ?  $row["id"] : "";
             $real_url="https://www.youtube.com/watch?v={$id}";
             $url="https://www.youtube.com/embed/{$id}?rel=0&wmode=transparent&autoplay=1";

             $download_video=base_url("search_engine/video_downloader/{$id}");
             $download_video="<a href='{$download_video}' target='_BLANK' class='dropdown-item has-icon download_video'><i class='fas fa-cloud-download-alt'></i> ".$this->lang->line("Download Video")."</a>";
             $copy_url="<a href='#' data-clipboard-text='{$real_url}' class='dropdown-item has-icon copy_class'><i class='fas fa-cut'></i> ".$this->lang->line("Copy url")." </a>"; 
             $output .= "<div class='card no_shadow'>
                         <div class='card-header'>
                           <h4> <i class='fas fa-video'></i> ".$this->lang->line("Search Results")." : ".count($tags)." ".$this->lang->line("Tags")."</h4>
                           <div class='card-header-action'>
                            <a title=".$this->lang->line("Download Tag \ Keyword")." class='btn btn-primary float-right' href=".base_url("download/youtube/tag_scraper_{$this->user_id}_{$download_id}.csv")."> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download Tag / Keyword")."</a> </div>
                         </div>
                       </div>
                       <div class='row video_lists_css' id='video_lists'>";

             $tag_details="";
             for($i=0;$i<count($tags);$i++) 
             {
                 $tag_details.="<li class='list-group-item'><i class='fa fa-tags'></i> ".$tags[$i]."</li>";
             }
            
             if(strlen($title)>20) 
              $dot= '...'; 
             else 
              $dot= "";

             if(strlen($description)>50)
              $des = '...';
             else
              $des = "";

             $tag_count = count($tags);
             if($tag_count == 0)
             {
              $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-6'>
                                        <div class='empty-state'>
                                             <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                                             <h2 class='mt-0'> ".$this->lang->line('We could not find any Tag.')."</h2>
                                           </div>
                                        
                                       </div>";
             }
             else
             {
              $output .="<div class='col-12 col-sm-6 col-md-6 col-lg-6'>                     
                          <ul class='margin_left_47px_neg'>".$tag_details."</ul>
                        </div>";
             }
          

             $output .= "<div class='col-12 col-sm-6 col-md-6 col-lg-6'>
                          <article class='article profile-widget'>
                            <div class='article-header'>

                              <div class='article-image youtube cboxElement' data-background='{$thumb}' video_url='{$url}' style='background-image: url({$thumb});'>
                              </div>
                              <div class='check_box_background text-center'>
                                <div class='profile-widget-item white_color'>
                                  <div class='profile-widget-item-label'><i class='fa fa-eye white_color'></i></div>
                                  <div class='profile-widget-item-value'>".thousandsCurrencyFormat($views)."</div>
                                </div>

                              </div>

                              <div class='video_option_background'>
                                <div class='float-right dropdown option_dropdown'>
                                  <a href='#' data-toggle='dropdown' aria-expanded='false'><i class='fas fa-ellipsis-h'></i></a>

                                  <div class='dropdown-menu youtube_video_search_action_dropdown_menu' x-placement='bottom-start'>
                                      
                                      
                                      {$copy_url}
                                   
                                    </div>
                                  </div>
                                </div>

                                <div class='article-title'>
                                  <h2 title='".$title."' class='white_color'>".substr($title, 0, 20).$dot."</h2>
                                  <div class='check_box_background text-center top_8px'>
                                    <div class='profile-widget-item white_color'>
                                      <div class='profile-widget-item-label'><i class='fa fa-clock DD4B39_color'></i></div>
                                      <div class='profile-widget-item-value'>".$duration."</div>
                                    </div>

                                  </div>
                                </div>
                              </div>
                              <div class='article-details padding_0'>



                                <p title='".$description."' class='description_info'>".substr($description, 0, 50).$des."</p>

                                <div class='profile-widget-items'>

                                  <div class='profile-widget-item'>
                                    <div class='profile-widget-item-label'> <i title=".$this->lang->line('Likes')." class='fa fa-thumbs-up'></i> </div>
                                    <div class='profile-widget-item-value'>".thousandsCurrencyFormat($likes)."</div>
                                  </div>
                                  <div class='profile-widget-item'>
                                    <div class='profile-widget-item-label'> <i title=".$this->lang->line('Dislikes')." class='fa fa-thumbs-down'></i> </div>
                                    <div class='profile-widget-item-value'>".thousandsCurrencyFormat($dislikes)."</div>
                                  </div>
                                  <div class='profile-widget-item'>
                                    <div class='profile-widget-item-label'><i title=".$this->lang->line('Commnets')." class='fa fa-comments'></i></div>
                                    <div class='profile-widget-item-value'>".thousandsCurrencyFormat($comments)."</div>
                                  </div>
                                </div>

                              </div>
                            </article>
                          </div>"; 
         }
         
       }
       if(isset($value['error'])) $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                     <div class='empty-state'>
                          <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                          <h2 class='mt-0'> ".$value['message']."</h2>
                        </div>
                     </div>";
       if($title=="") $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                     <div class='empty-state'>
                          <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                          <h2 class='mt-0'> ".$this->lang->line('We could not find any data.')."</h2>
                        </div>
                     </div>";

    }
    $output.="</div>";

    echo $output;

    $tag_scraper_writer=fopen("download/youtube/tag_scraper_{$this->user_id}_{$download_id}.csv", "w");
    fprintf($tag_scraper_writer, chr(0xEF).chr(0xBB).chr(0xBF));           
    $write_validation[]="Tags/Keyword";
    fputcsv($tag_scraper_writer, $write_validation);
    for($i=0;$i<count($tags);$i++) 
    {
        $write_validation=array();
        $write_validation[]=$tags[$i];
        fputcsv($tag_scraper_writer, $write_validation);
    }              
    fclose($tag_scraper_writer);   



  }


  public function playlist($channel_id="")
  {
    if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access)) {
        redirect('404','refresh');
    }
     
    $data['page_title'] = $this->lang->line("YouTube Playlist Search");
    $data['body'] = "search_engine/playlist_search";
    $data["channel_id"]=$channel_id;
    $data['no_app_error'] = $this->check_app_settings();
    $this->_viewcontroller($data);

  }


  public function youtube_playlist_search_action()
  {
     
     $this->ajax_check();

     $keyword=$this->input->post("keyword",true);
     $limit=$this->input->post("limit",true);       
     $channel_id=$this->input->post("channel_id",true);
     $publish_before_after=$this->input->post("date_range_val",true);
    
     $publish_before_after = explode('|', $publish_before_after);
     $publish_after = isset($publish_before_after[0])  ? $publish_before_after[0]:"";
     $publish_before = isset($publish_before_after[1]) ? $publish_before_after[1]:"";

     
     if($keyword=="" && $channel_id=="")
     {
         echo $this->lang->line("Please Enter Keyword or Channel ID");
         exit();
      
     }

     $this->load->library('youtube_library');

     $youtube_data=$this->youtube_library->get_youtube_playlist($keyword,$limit,$channel_id,$location="",$radius="",$order="",$publish_after,$publish_before);

     $this->_insert_usage_log($module_id = 15, $request = 1);

     $final_data = array();
     $total_playlist = 0;
    
     if(!isset($youtube_data['error']))
     {
        $playlist_ids=is_array($youtube_data)?array_column($youtube_data, 'playlist_id'):array();      
        
        $total_playlist=count($playlist_ids);
        $playlist_ids=implode(",",$playlist_ids);
  
        $final_data=$this->youtube_library->get_playlist_by_id($playlist_ids);
     }
     $final_data = array();
     $output='<script src="'.base_url().'assets/js/system/video_search_action.js"></script>';



        $output .= "<div class='card no_shadow'>
                      <div class='card-header'>
                       <h4> <i class='fas fa-video'></i> ".$this->lang->line("Search Results")."</h4>
                       <div class='card-header-action'>
                         <div class='badges'>
                           <span class='badge badge-primary'>".$total_playlist."</span>
                         </div>                    
                       </div>
                     </div>
                   </div>
                   <div class='row video_lists_css' id='video_lists'>";

     if(isset($youtube_data['error']))
        $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                     <div class='empty-state'>
                          <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                          <h2 class='mt-0'> ".$youtube_data['message']."</h2>
                          <a href='' class='btn btn-outline-primary mt-4'><i class='fas fa-search'></i> ".$this->lang->line('try once again')."</a>
                        </div>
                     </div>";
     else if($total_playlist==0) 
        $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                     <div class='empty-state'>
                          <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                          <h2 class='mt-0'> ".$this->lang->line('We could not find any data.')."</h2>
                          <a href='' class='btn btn-outline-primary mt-4'><i class='fas fa-search'></i> ".$this->lang->line('try once again')."</a>
                        </div>
                     </div>";

     $formatted_data=array();
     foreach ($final_data as $value) 
     {
        if(isset($value["items"]))
        {             
            foreach ($value["items"] as $row) 
            {
               if(isset($row["id"]))
               {
                  $formatted_data[$row["id"]]=array();
                  $formatted_data[$row["id"]]["playlist_id"]=$row["id"];
                  $formatted_data[$row["id"]]["status"] =  isset($row["status"]["privacyStatus"]) ? $row["status"]["privacyStatus"] : "";
                  $formatted_data[$row["id"]]["videoCount"] = isset($row["contentDetails"]["itemCount"]) ? $row["contentDetails"]["itemCount"]: "0";

               }
            }
         }
     } 
     $cli=0;
     if(!isset($youtube_data['error']))
     foreach ($youtube_data as $row) 
     {    
        $published_at=isset($row["published_at"]) ? $row["published_at"] : "";
        $published_at=date("Y-m-d",strtotime($published_at));
        $channel_id=isset($row["channel_id"]) ? $row["channel_id"] : "";
        $playlist_id=isset($row["playlist_id"]) ? $row["playlist_id"] : "";
        $title=isset($row["title"]) ? $row["title"] : "";
        $description=isset($row["description"]) ? $row["description"] : "";
        $thumb=isset($row["thumbnail"]) ? $row["thumbnail"] : "";

        $channel_link="<a href='https://www.youtube.com/channel/{$channel_id}' target='_BLANK' class='dropdown-item has-icon'><i class='fa fa-tv'></i> ".$this->lang->line("Visit Channel")."</a>";         

        $video_count= isset($formatted_data[$playlist_id]["videoCount"]) ? $formatted_data[$playlist_id]["videoCount"] : "0";
        $status= isset($formatted_data[$playlist_id]["status"]) ? $formatted_data[$playlist_id]["status"]: "0";

        $real_url="https://www.youtube.com/playlist?list={$playlist_id}";
        $url="http://www.youtube.com/embed/videoseries?list={$playlist_id}&amp;hl=en_US&showinfo=1";

        $get_video_sub_btn=base_url("search_engine/youtube_playlist_video/{$playlist_id}");
        $get_video_btn="<a href='{$get_video_sub_btn}' target='_BLANK' class='dropdown-item has-icon'><i class='fa fa-video'></i> ".$this->lang->line("Videos")."</a>";

        
        $copy_url="<a href='#' data-clipboard-text='{$real_url}' class='dropdown-item has-icon copy_class'><i class='fa fa-cut'></i> ".$this->lang->line("Copy Playlist URL")." </a>";
        $playlist_url="<a href='{$real_url}' target='_BLANK' class='dropdown-item has-icon copy_class'><i class='fa fa-list'></i> ".$this->lang->line("Vist Playlist")." </a>";

        if(strlen($title)>20) 
         $dot= '...'; 
        else 
         $dot= "";

        if(strlen($description)>25)
          $des = '...';
        else
          $des = "";
    
        $output .= "<div class='col-12 col-sm-6 col-md-6 col-lg-4 samsu'>
                              <article class='article profile-widget'>
                                <div class='article-header'>

                                  <div class='article-image youtube cboxElement' data-background='{$thumb}' video_url='{$url}' style='background-image: url({$thumb});'>
                                  </div>
                                  

                                  <div class='video_option_background'>
                                    <div class='float-right dropdown option_dropdown'>
                                      <a href='#' data-toggle='dropdown' aria-expanded='false'><i class='fas fa-ellipsis-h'></i></a>

                                      <div class='dropdown-menu youtube_video_search_action_dropdown_menu' x-placement='bottom-start'>
                                          {$get_video_btn}
                                          {$playlist_url}
                                          {$channel_link}
                                          {$copy_url}
                                           

                                          </div>
                                      </div>
                                    </div>

                                    <div class='article-title'>
                                      <h2 title='".$title."' class='white_color>".substr($title, 0, 20).$dot."</h2>
                                    
                                    </div>
                                  </div>
                                  <div class='article-details padding_0'>
                                    <p title='".$this->lang->line('Published')."' class='description_info'><span class='float-left'>".$this->lang->line("Published")."</span> &nbsp; &nbsp; <span class='float-right'>".$published_at."</span></p>
                                    <div class='profile-widget-items'>
                                    <div class='profile-widget-item'>
                                      <div class='profile-widget-item-label'> <i title=".$this->lang->line("Playlist video")." class='fa fa-video'></i> </div>
                                      <div class='profile-widget-item-value'>".$video_count."</div>
                                    </div>

                                    <div class='profile-widget-item'>
                                      <div class='profile-widget-item-label'> <i title=".$this->lang->line('Status')." class='fa fa-user'></i> </div>
                                      <div class='profile-widget-item-value'>".ucfirst($status)."</div>
                                    </div>

                                  </div>
                                </article>
                              </div>"; 

       $cli++;
     }
     
     $output.="</div>";

     $page_encoding =  mb_detect_encoding($output);
     if(isset($page_encoding)){
       $output = @iconv( $page_encoding, "utf-8//IGNORE", $output );
     } 
     echo $output;



  }

  public function youtube_playlist_video($playlist_id="",$monetized="")
  {
     $this->load->library('youtube_library'); 
     $final_data=$this->youtube_library->playlist_item_paginated($playlist_id);
     $total_video=isset($final_data["items"]) ? count($final_data["items"]) : 0;
     $playlist_count= isset($final_data["pageInfo"]["totalResults"]) ? $final_data["pageInfo"]["totalResults"] : 0 ;
     $playlist_url=$url="http://www.youtube.com/embed/videoseries?list={$playlist_id}&amp;hl=en_US&showinfo=1";    
     $output='<script src="'.base_url().'assets/js/system/video_search_action.js"></script>';
     $visit_playlist = "<a target='_BLANK' href='https://www.youtube.com/playlist?list=".$playlist_id."'>{$playlist_id}</a>";
     $output .= "<div class='card'>
                 <div class='card-header'>
                   <h4><i class='fab fa-youtube'></i> ".$this->lang->line("Playlist Videos")." : ".$visit_playlist."</h4>
                   <div class='card-header-action'>
                     <div class='badges'>
                       <span class='badge badge-primary'>".$this->lang->line('Showing')." ".$total_video." / ".$playlist_count."</span>
                     </div>                    
                   </div>
                 </div>
               </div>
               <div class='row video_lists_css' id='video_lists'>";

      if(isset($final_data['error'])) 
         $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                    <div class='empty-state'>
                         <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                         <h2 class='mt-0'> ".$final_data['message']."</h2>
                    </div>";
      if($total_video==0) 
         $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                    <div class='empty-state'>
                         <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                         <h2 class='mt-0'> ".$this->lang->line('We could not find any data.')."</h2>
                    </div>";

        if(isset($final_data["items"]))
        {
          foreach ($final_data["items"] as $row) 
          {
              $title=isset($row["snippet"]["title"]) ? $row["snippet"]["title"] : "";
              $description=isset($row["snippet"]["description"]) ? $row["snippet"]["description"] : "";
              $published_at=isset($row["snippet"]["publishedAt"]) ? $row["snippet"]["publishedAt"] : "";
              $published_at=date("Y-m-d",strtotime($published_at));
              $thumb=isset($row["snippet"]["thumbnails"]["medium"]["url"]) ?  $row["snippet"]["thumbnails"]["medium"]["url"] : "";
              $id=isset($row["snippet"]["resourceId"]["videoId"]) ?  $row["snippet"]["resourceId"]["videoId"] : "";



              $real_url="https://www.youtube.com/watch?v={$id}";         
              $url="https://www.youtube.com/embed/{$id}?rel=0&wmode=transparent&autoplay=1";
              $copy_url="<a href='#' data-clipboard-text='{$real_url}' class='dropdown-item has-icon copy_class' submit_type='edit'><i class='fa fa-cut'></i> ".$this->lang->line("Copy url")." </a>";

              $tag_blink=base_url("search_engine/tag_keyword_scraper/{$id}");
              $download_tag_blink="<a target='_BLANK' href='{$tag_blink}' class='dropdown-item has-icon' submit_type='edit'>
                              <i class='fas fa-tags'></i> ".$this->lang->line("Tag / Keyword")." </a>";
       
               if(strlen($title)>20) 
                $dot= '...'; 
               else 
                $dot= "";

             
               $output .= "<div class='col-12 col-sm-6 col-md-6 col-lg-3'>
                                     <article class='article profile-widget samsu'>
                                       <div class='article-header'>

                                         <div class='article-image youtube cboxElement' data-background='{$thumb}' video_url='{$url}' style='background-image: url({$thumb});'>
                                         </div>
                                         

                                         <div class='video_option_background'>
                                           <div class='float-right dropdown option_dropdown'>
                                             <a href='#' data-toggle='dropdown' aria-expanded='false'><i class='fas fa-ellipsis-h'></i></a>

                                             <div class='dropdown-menu youtube_video_search_action_dropdown_menu' x-placement='bottom-start'>
                                               
                                                 {$copy_url}
                                                 {$download_tag_blink}
                                                  

                                                 </div>
                                             </div>
                                           </div>

                                           <div class='article-title'>
                                             <h2 title='".$title."' class='white_color'>".substr($title, 0, 20).$dot."</h2>
                                           
                                           </div>
                                         </div>
                                         <div class='article-details padding_0'>
                                           <p title='".$this->lang->line('Published')."' class='description_info'><span class='float-left'>".$this->lang->line("Published")."</span> &nbsp; &nbsp; <span class='float-right'>".$published_at."</span></p>
                                          
                                       </article>
                                     </div>";


          }
          
        }
       
       $output.="</div>";


       $page_encoding =  mb_detect_encoding($output);
       if(isset($page_encoding)){
         $output = @iconv( $page_encoding, "utf-8//IGNORE", $output );
       } 

       $data["output"]  = $output;
       $data['body'] = 'search_engine/playlist_videos';
       $data['page_title'] = $this->lang->line("Playlist Videos");
      
       $this->_viewcontroller($data);

  }


  public function channel()
  {
    if($this->session->userdata('user_type') != 'Admin' && !in_array(16,$this->module_access)) {
        redirect('404','refresh');
    }
    $data['page_title'] = $this->lang->line("YouTube Channel Search");
    $data['body'] = "search_engine/channel_search";
    $data['no_app_error'] = $this->check_app_settings();
    $this->_viewcontroller($data);

  }

  public function youtube_channel_search_action()
  {

    $keyword=$this->input->post("keyword",true);
    $limit=$this->input->post("limit",true);       
    $publish_before_after=$this->input->post("date_range_val",true);

    $publish_before_after = explode('|', $publish_before_after);
    $publish_after = isset($publish_before_after[0])  ? $publish_before_after[0]:"";
    $publish_before = isset($publish_before_after[1]) ? $publish_before_after[1]:"";

    if($keyword=="")
    {
      echo $this->lang->line("Please Enter Keyword");
      exit();

    }

    $this->load->library('youtube_library');

    $youtube_data=$this->youtube_library->get_youtube_channel($keyword,$limit,$location="",$radius="",$order="",$publish_after,$publish_before);

    $this->_insert_usage_log($module_id = 16, $request = 1);
    $total_channel = 0;
    $final_data = array();

    if(!isset($youtube_data['error']))
    {
      $channel_ids=is_array($youtube_data)?array_column($youtube_data, 'channel_id'):array();      
      $total_channel=count($channel_ids);
      $channel_ids=implode(",",$channel_ids);
      $final_data=$this->youtube_library->get_channel_by_id($channel_ids);
    }

    $output='';
    $output .= "<div class='card no_shadow'>
                  <div class='card-header'>
                    <h4> <i class='fas fa-tv'></i> ".$this->lang->line("Search Results")."</h4>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <span class='badge badge-primary'>".$total_channel."</span>
                      </div>                    
                    </div>
                  </div>
                </div>
                <div class='row video_lists_css' id='video_lists'>";
    if(isset($youtube_data['error']))
      $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                    <div class='empty-state'>
                      <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                      <h2 class='mt-0'> ".$youtube_data['message']."</h2>
                      <a href=".base_url('search_engine/channel')." class='btn btn-outline-primary mt-4'><i class='fas fa-search'></i> ".$this->lang->line('try once again')."</a>
                    </div>
                  </div>";

    if($total_channel==0) 
      $output.= "<div class='col-12 col-sm-6 col-md-6 col-lg-12' id='nodata'>
                    <div class='empty-state'>
                      <img class='img-fluid height_250px' src='".base_url('assets/img/drawkit/drawkit-nature-man-colour.svg')."' alt='image'>
                      <h2 class='mt-0'> ".$this->lang->line('We could not find any data.')."</h2>
                      <a href=".base_url('search_engine/channel')." class='btn btn-outline-primary mt-4'><i class='fas fa-search'></i> ".$this->lang->line('try once again')."</a>
                    </div>
                  </div>";

    $formatted_data=array();
    foreach ($final_data as $value) 
    {
      if(isset($value["items"]))
      {             
        foreach ($value["items"] as $row) 
        {
          if(isset($row["id"]))
          {
            $formatted_data[$row["id"]]=array();
            $formatted_data[$row["id"]]["channel_id"]=$row["id"];
            $formatted_data[$row["id"]]["viewCount"]            =  isset($row["statistics"]["viewCount"])              ? $row["statistics"]["viewCount"] : "0";
            $formatted_data[$row["id"]]["commentCount"]         =  isset($row["statistics"]["commentCount"])           ? $row["statistics"]["commentCount"]: "0";
            $formatted_data[$row["id"]]["subscriberCount"]      =  isset($row["statistics"]["subscriberCount"])        ? $row["statistics"]["subscriberCount"]: "0";
            $formatted_data[$row["id"]]["hiddenSubscriberCount"]=  isset($row["statistics"]["hiddenSubscriberCount"])  ? $row["statistics"]["hiddenSubscriberCount"]: "0";
            $formatted_data[$row["id"]]["videoCount"]           =  isset($row["statistics"]["videoCount"])             ? $row["statistics"]["videoCount"]: "0";

          }
        }
      }
    } 
    if(!isset($youtube_data['error']))
    foreach ($youtube_data as $row) 
    {    
      $published_at=isset($row["published_at"]) ? $row["published_at"] : "";
      $published_at=date("Y-m-d",strtotime($published_at));
      $channel_id=isset($row["channel_id"]) ? $row["channel_id"] : "";
      $title=isset($row["title"]) ? $row["title"] : "";
      $description=isset($row["description"]) ? $row["description"] : "";
      $thumb=isset($row["thumbnail"]) ? $row["thumbnail"] : "";

      $views= isset($formatted_data[$channel_id]["viewCount"]) ? $formatted_data[$channel_id]["viewCount"] : "0";
      $subscriber= isset($formatted_data[$channel_id]["subscriberCount"]) ? $formatted_data[$channel_id]["subscriberCount"]: "0";
      $hidden_subscriber= isset($formatted_data[$channel_id]["hiddenSubscriberCount"]) ? $formatted_data[$channel_id]["hiddenSubscriberCount"]: "0";
      $video_count= isset($formatted_data[$channel_id]["videoCount"]) ? $formatted_data[$channel_id]["videoCount"] : "0";
      $comments= isset($formatted_data[$channel_id]["commentCount"]) ? $formatted_data[$channel_id]["commentCount"]: "0";

      $real_url="https://www.youtube.com/channel/{$channel_id}";

      $get_play_list_sub_btn=base_url("search_engine/playlist/{$channel_id}");
      $get_play_list_btn="<a  class='dropdown-item has-icon' href='{$get_play_list_sub_btn}' target='_BLANK'><i class='fa fa-list'></i> ".$this->lang->line("Playlists")."</a>";

      $channel_link="<a href='https://www.youtube.com/channel/{$channel_id}' target='_BLANK' class='dropdown-item has-icon'><i class='fa fa-tv'></i> ".$this->lang->line("Visit Channel")."</a>";    

      $get_video_sub_btn=base_url("search_engine/video/{$channel_id}");
      $get_video_btn="<a  class='dropdown-item has-icon' href='{$get_video_sub_btn}' target='_BLANK'><i class='fa fa-video'></i> ".$this->lang->line('Videos')."</a>";

      


      if(strlen($title)>20) 
        $dot= '...'; 
      else 
        $dot= "";

      if(strlen($description)>25)
        $des = '...';
      else
        $des = "";


      $output .= "<div class='col-12 col-sm-6 col-md-6 col-lg-4'>
                    <article class='article profile-widget'>
                      <div class='article-header'>

                        <div class='article-image youtube cboxElement' data-background='{$thumb}' video_url='{$real_url}' style='background-image: url({$thumb});'>
                        </div>


                        <div class='video_option_background'>
                          <div class='float-right dropdown option_dropdown'>
                            <a href='#' data-toggle='dropdown' aria-expanded='false'><i class='fas fa-ellipsis-h'></i></a>

                            <div class='dropdown-menu youtube_video_search_action_dropdown_menu' x-placement='bottom-start'>
                              {$get_play_list_btn}
                              {$get_video_btn}
                              {$channel_link}
                            </div>
                          </div>
                        </div>

                        <div class='article-title'>
                          <h2 title='".$title."' class='white_color'>".substr($title, 0, 20).$dot."</h2>

                        </div>
                      </div>
                      <div class='article-details padding_0'>
                        <p title='".$this->lang->line('Published')."' class='description_info'><span class='float-left'>".$this->lang->line("Published")."</span> &nbsp; &nbsp; <span class='float-right'>".$published_at."</span></p>
                        <div class='profile-widget-items'>
                          <div class='profile-widget-item'>
                            <div class='profile-widget-item-label'> <i title=".$this->lang->line("Total Views")." class='fa fa-eye'></i> </div>
                            <div class='profile-widget-item-value'>".thousandsCurrencyFormat($views)."</div>
                          </div>   
                          <div class='profile-widget-item'>
                            <div class='profile-widget-item-label'> <i title=".$this->lang->line("Subscriber")." class='fa fa-user'></i> </div>
                            <div class='profile-widget-item-value'>".thousandsCurrencyFormat($subscriber)."</div>
                          </div>

                          <div class='profile-widget-item'>
                            <div class='profile-widget-item-label'> <i title=".$this->lang->line('Videos')." class='fa fa-video'></i> </div>
                            <div class='profile-widget-item-value'>".thousandsCurrencyFormat($video_count)."</div>
                          </div>

                        </div>
                      </article>
                    </div>";



    }

    $output.="</div>";

    $page_encoding =  mb_detect_encoding($output);
    if(isset($page_encoding)){
      $output = @iconv( $page_encoding, "utf-8//IGNORE", $output );
    } 
    echo $output;

  }







}