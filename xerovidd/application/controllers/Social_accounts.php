<?php
require_once("Home.php");

class Social_accounts extends Home
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

   public function index()
   {
       if($this->session->userdata('user_type') == 'Admin' || in_array(1,$this->module_access)) {

         $youtube_channel_list = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id)));

         $data['youtube_channel_list'] = $youtube_channel_list;
       }

       $data['body'] = "social_accounts/account_lists";
       $data['page_title'] = $this->lang->line("Social Accounts");
       $this->_viewcontroller($data);
   }

   public function channel_manager()
   {
      $data['body'] = 'social_accounts/menu_block';
      $data['page_title'] = $this->lang->line('Channel Manager');
      $this->_viewcontroller($data);
   }


   public function login_with_google_panel()
   {
      try
      {
        $params['redirectUri'] = base_url("home/youtube_login_redirect");
        $params['is_refreshtoken'] = false;
        $this->load->library('youtube_library',$params);
        $data['youtube_login_button'] = $this->youtube_library->set_login_button();
        $no_app = '0';
      } catch (Exception $e) {

        $no_app = '1';
        $extra_message = "";
        $extra_message2 = "";
        if($this->session->userdata("user_type")=="Admin") 
        $extra_message = '<a href="'.base_url('social_apps/google_settings').'" class="btn btn-outline-primary mt-4"><i class="fa fa-key"></i> '.$this->lang->line("Set Google API Keys").'</a>';
        else $extra_message2 = $this->lang->line("Please contact system admin.");

        $data['youtube_login_button'] = '
        <div class="card" id="nodata">
          <div class="card-body">
            <div class="empty-state">
              <img class="img-fluid height_250px" src="'.base_url('assets/img/drawkit/drawkit-nature-man-colour.svg').'" alt="image">
              <h2 class="mt-0">'.$this->lang->line("Google app settings not found.")." ".$extra_message2.'</h2>
              '.$extra_message.'
            </div>
          </div>
        </div>';
          
      }


      $data['body'] = "social_accounts/google_login_panel";
      $data['no_app'] = $no_app;
      $data['page_title'] = $this->lang->line('YouTube Channel Import');
      $this->_viewcontroller($data);
   }


   public function delete_social_accounts()
   {
      $this->ajax_check();

      if($this->is_demo == '1' && $this->session->userdata("user_type")=="Admin")
      {
          echo json_encode(array('type'=>'error','message'=>'You do not have access to perform this action in this demo.'));
          exit();
      }

      $social_media = $this->input->post('social_media', true);
      $table_id = $this->input->post('table_id', true);

      $this->db->trans_start();
      $response = $this->delete_single_social_account($social_media, $table_id);
      $this->db->trans_complete();

      if($this->db->trans_status() === FALSE) echo json_encode(array('type' => 'error', 'message' => $this->lang->line("Something went wrong.")));
      else echo json_encode(array('type' => 'success', 'message' => $this->lang->line("Channel and all related data has been deleted successfully."))); 
   }

   public function youtube_channel($channel_id = '', $iframe = '0',$load_src="")
   {
      if ($channel_id == '') redirect(base_url('social_accounts'),'location');
      $data['body'] = "social_accounts/youtube";   
      $data['page_title'] = $this->lang->line("Channel Videos");  
      $data['iframe'] = $iframe;
      $data['load_src'] = $load_src;

      $selected_playlist_id="";
      if($load_src=="playlist_manager")
      {
        $selected_playlist_id = $this->session->userdata("playlist_manager_playlist_list_clicked_id");
      }
      $data["selected_playlist_id"] = $selected_playlist_id;

      $where = array('where' => array('channel_id' => $channel_id, 'user_id' => $this->user_id));        
      $channel_id_info = $this->basic->get_data('youtube_channel_info', $where, array('id','channel_id','title'));
      if (count($channel_id_info) == 0)  redirect(base_url('social_accounts/404'),'location');
      $table_id = $channel_id_info[0]['id'];
      $channel_id = $channel_id_info[0]['channel_id'];
      $data["channel_title"] = $channel_id_info[0]["title"];

      /* saved templates */
      $template_info = $this->basic->get_data('auto_reply_template', array('where' => array('user_id' => $this->user_id)), array('id', 'name'));
      $saved_templates = array('0' => $this->lang->line("Please Select a template"));
      if (count($template_info) > 0) {           
          foreach ($template_info as $template) {
              $saved_templates[$template['id']] = $template['name'];
          }   
      } 

      $data["saved_templates"] = $saved_templates;
      $data["table_id"] = $table_id;
      $data["channel_id"] = $channel_id;
      $this->_viewcontroller($data);    
   }

   public function youtube($channel_id = '', $iframe = '0',$load_src="")
   {
       $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
       $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
       $search = $this->input->post('search');
       $order_by = "";       

       $where = array('where' => array('channel_id' => $channel_id, 'user_id' => $this->user_id));       
       $channel_id_info = $this->basic->get_data('youtube_channel_info', $where, array('id','channel_id','title'));
       if (count($channel_id_info) == 0)  redirect(base_url('social_accounts/404'),'location');
       $table_id = $channel_id_info[0]['id'];
       $channel_id = $channel_id_info[0]['channel_id'];
       $data["channel_title"] = $channel_id_info[0]["title"];

       $this->session->set_userdata('youtube_channel_info_table_id',$table_id);

       $where_simple = array('channel_id' => $channel_id,"user_id"=>$this->user_id);

       if($search!='')  $this->db->like('title', $search);
                         
       $where = array('where' => $where_simple);

       $table = "youtube_video_list";
       $video_lists = $this->basic->get_data($table, $where, $select = '', $join='', $limit, $start, $order_by);

       $auto_reply_enabled_video_ids = $this->basic->get_data('auto_reply_campaign', array('where' => array('channel_id' => $channel_id,"user_id"=>$this->user_id)), array('video_id'));
       $auto_reply_enabled_video_ids = json_encode($auto_reply_enabled_video_ids);
       // transforming [{"word":"xxx"},{"word":"yyy"}] to 'xxx,yyy'
       $auto_reply_enabled_video_ids = str_replace('{"video_id":"', '', $auto_reply_enabled_video_ids);
       $auto_reply_enabled_video_ids = str_replace('"}', '', $auto_reply_enabled_video_ids);
       $auto_reply_enabled_video_ids = str_replace('[', '', $auto_reply_enabled_video_ids);
       $auto_reply_enabled_video_ids = str_replace(']', '', $auto_reply_enabled_video_ids);
       $auto_reply_enabled_video_ids = explode(',', $auto_reply_enabled_video_ids);      

       $html='';
       $p_class = ($iframe=='1') ? 'p-2' : "";
       $m_class = ($iframe=='1') ? 'mb-0' : "";
       $i = 0;
       foreach ($video_lists as $key => $single_video)
       {  
          $single_data = "";

          $single_data .= '
          <div class="col-12 col-sm-6 col-md-6 col-lg-3 '.$p_class.'">
            <article class="article profile-widget '.$m_class.'">
              <div class="article-header">';

                $style ="style = 'background: url(\"". $single_video['image_link'] ."\");'";

                $single_data .= '
                <div class="article-image youtube" data-background="'.$single_video['image_link'].'" video_url="https://www.youtube.com/embed/'.$single_video['video_id'].'" '.$style.'>
                </div>';

                $single_data .= '
                <div class="check_box_background">
                    <div class="check_box">
                      <input id="box_'.$i.'" type="checkbox" name="selected_videos" value="'.$single_video['video_id'].'" class="regular-checkbox">
                      <label for="box_'.$i.'" class="cursor_pointer"></label>
                    </div>
                </div>';
              
                if ($iframe!='1' || $load_src=='video_manager')
                {                
                  $single_data .= '
                  <div class="video_option_background">
                      <div class="float-right dropright option_dropdown">
                          <a href="#" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                          <div class="dropdown-menu youtube_video_search_action_dropdown_menu" x-placement="bottom-start">';

                            if($this->session->userdata('user_type') == 'Admin' || in_array(6,$this->module_access))
                            {                       
                                $submit_type = (in_array($single_video['video_id'], $auto_reply_enabled_video_ids)) ? 'edit' : 'add';
                                $auto_reply_button = (in_array($single_video['video_id'], $auto_reply_enabled_video_ids)) ? $this->lang->line('Edit Auto Reply') : $this->lang->line('Set Auto Reply');
                                $single_data .= '
                                <a href="#" class="dropdown-item has-icon" id="set_auto_reply_button" data-toggle="modal" data-target="#set_auto_reply_modal" 
                                  video_id="'.$single_video['video_id'].'" 
                                  channel_id="'.$single_video['channel_id'].'" 
                                  submit_type="'.$submit_type.'">
                                  <i class="fas fa-reply-all"></i> 
                                  '.$auto_reply_button.'
                                </a>
                                <a href="'.base_url('responder/auto_reply_campaign/'.$single_video['video_id']).'" class="dropdown-item has-icon" target="_BLANK"><i class="fas fa-list"></i> '.$this->lang->line('Auto Reply Report').'</a>';
                            }


                            if($this->session->userdata('user_type') == 'Admin' || in_array(9,$this->module_access))
                            {
                                $single_data .= '
                                <a href="#" class="dropdown-item has-icon" id="edit_yt_video" data-toggle="modal" data-target="#edit_yt_video_modal" 
                                video_id="'.$single_video['video_id'].'" 
                                channel_id="'.$single_video['channel_id'].'" 
                                ><i class="fas fa-video"></i> '.$this->lang->line('Edit Video').'</a>';
                            }

                            $show_rank_tracking_feature = $this->config->item('show_rank_tracking_feature');
                            if($show_rank_tracking_feature == '') $show_rank_tracking_feature='1';

                            if(($this->session->userdata('user_type') == 'Admin' || in_array(11,$this->module_access)) && $show_rank_tracking_feature=='1')
                            {
                                $single_data .= '
                                <a href="#" class="dropdown-item has-icon rank_track_set"  data-toggle="modal" data-target="#rank_track_set_modal" video_id="'.$single_video['video_id'].'"><i class="fas fa-trophy"></i> '.$this->lang->line('Rank Tracking').'</a>

                                <a href="#" class="dropdown-item has-icon rank_track_report" data-toggle="modal" data-target="#rank_track_report_modal" video_id="'.$single_video['video_id'].'"><i class="far fa-flag"></i> '.$this->lang->line('Rank Tracking Report').'</a>';
                            }

                            $single_data .= '
                            <a href="'.base_url('social_accounts/video_analytics/' . $table_id . '/' . $single_video['id']).'" target="_BLANK" class="dropdown-item has-icon"><i class="far fa-chart-bar"></i> '.$this->lang->line('Analytics').'</a>

                          </div>
                      </div>
                  </div>';                
                }

                $formated_title = (strlen($single_video['title']) > 40) ? substr($single_video['title'], 0, 40).'...' : $single_video['title'];
                $single_data .= '
                <div class="article-title">
                  <h2 title="'.$single_video['title'].'" class="white_color">'.$formated_title.'</h2>
                </div>
              </div>';

              $single_data .= '
              <div class="article-details padding_0">                  
                <div class="profile-widget-items">
                  <div class="profile-widget-item">
                      <div class="profile-widget-item-label">'.$this->lang->line('Views').'</div>
                      <div class="profile-widget-item-value">'.$single_video['viewCount'].'</div>
                  </div>
                  <div class="profile-widget-item">
                      <div class="profile-widget-item-label">'.$this->lang->line('Duration').'</div>
                      <div class="profile-widget-item-value">'.youtube_time_to_time_duration($single_video['duration']).'</div>
                  </div>
                  <div class="profile-widget-item">
                      <div class="profile-widget-item-label">'.$this->lang->line('Like').'</div>
                      <div class="profile-widget-item-value">'.$single_video['likeCount'].'</div>
                  </div>
                </div>

              </div>
            </article>
          </div>';          
          $html.=$single_data;
          $i++;
       }
       $html.='<script src="'.base_url().'assets/js/system/youtube_action.js"></script>';
       if(function_exists('mb_convert_encoding')) $html = mb_convert_encoding($html, "UTF-8", "UTF-8");
       echo json_encode(array("html"=>$html,"found"=>count($video_lists)));


   }


   public function edit_yt_video()
   {

      $this->ajax_check();

      $get_video_category_list = $this->get_video_category();
      $get_lan                 = $this->get_language_names();
      
      array_unshift($get_lan,$this->lang->line("Please select a language"));
      $get_video_category_list[""] = $this->lang->line("Select category");


      $video_privacy_status_array    = $this->video_privacy_status();

      array_unshift($video_privacy_status_array, $this->lang->line("Please select a privacy"));

      $video_id   = $this->input->post('video_id',true);
      $channel_id = $this->input->post('channel_id',true);



      $video_info = $this->basic->get_data('youtube_video_list', array('where' => array('video_id' => $video_id,'channel_id'=>$channel_id,'user_id'=>$this->user_id)));
       
      if (count($video_info)>0) 
      {
          $video_info = $video_info[0];

          if ($this->user_id == $video_info['user_id']) 
          {
            
            if(!empty($video_info['defaultLanguage'])) 
              $select_lan=$video_info['defaultLanguage'];
            else
              $select_lan ="";



            if(!empty($video_info['privacyStatus']))
              $select_privacy = $video_info['privacyStatus'];
            else
              $select_privacy = "";

            
            $select_category = $video_info['categoryId'];

            $tags_left  = str_replace('[', '', $video_info['tags']);
            $tags_right = str_replace(']', '', $tags_left);
            $final_tags = str_replace('"', '', $tags_right);
             
            $select_lang = "en";

            if(!empty($video_info['localizations']))
              $checked = "checked";
            else
              $checked ="";

            $final_string = '';

            $final_string .= '         
              <div class="form-group">
                <label for="title"> '.$this->lang->line("Video Title").' </label>
                <input id="title" name="title" class="form-control" type="text" value="'.$video_info['title'].'">
              </div>';

            $final_string .= '          
              <div class="form-group">
                <label for="description"> '.$this->lang->line("Video Description").'</label>
                <textarea id="description" name="description" class="form-control inputtags">'.$video_info['description'].'</textarea>
              </div> ';

            $final_string .= '          
              <div class="form-group">
                <label for="description"> '.$this->lang->line("Video Tags (Comma Separated)").'</label>
                <textarea id="tags" name="tags" class="form-control inputtags">'.$final_tags.'</textarea>
              </div> ';

            $final_string .='
           <div class="row">
             <div class="col-12 col-md-6">
               <div class="form-group">
                 <label for="" > '.$this->lang->line('Language').'</label>
                 '.form_dropdown('defaultLanguage',$get_lan,$select_lan,'class="form-control select2" id="defaultLanguage"').'
               </div>
             </div>
             <div class="col-12 col-md-6">
               <div class="form-group">
                 <label for=""> '.$this->lang->line('Privacy Status').'</label>
                 '.form_dropdown('privacyStatus',$video_privacy_status_array,$select_privacy,'class="form-control select2" id="privacyStatus"').'
               </div>
             </div>
           </div>';

            $final_string .='
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="" > '.$this->lang->line('Video Category').'</label>
                  '.form_dropdown('categoryId',$get_video_category_list,$select_category,'class="form-control select2" id="categoryId"').'
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for=""> '.$this->lang->line('Localizations').'</label>
                  <br>
                  <label class="custom-switch mt-2">
                    <input type="checkbox" name="localization_has" value="localization_has" class="custom-switch-input" '.$checked.' id="myCheck">
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">'.$this->lang->line('Yes').'</span>

                  </label>

                </div>
              </div>
            </div>';

            $show_localizations = (!empty($video_info['localizations'])) ? 'd_block' : 'd_none';

            $final_string .='
            
            <div class="row '.$show_localizations.'" id="localizations_id">
              <div class="col-12 col-md-12">
                <div class="video_edit_filter_message_block">';


                $localizations_data = json_decode($video_info['localizations'],true);
                $i = 0;

                if(!empty($localizations_data))
                {

                  
                  foreach ($localizations_data as $key => $value) {

                    if($i%2 == 0)
                      $card_type = 'card-primary';
                    else
                      $card_type = 'card-info';

                    $i++;
                    

                    
                    $final_string .= '<div class="card '.$card_type.' single_card">

                      <div class="card-header">
                        <h4>'.$this->lang->line("Localizations").'</h4>
                        <div class="card-header-action">
                          <button class="btn btn-outline-secondary video_edit_remove_div"><i class="fas fa-times"></i> '.$this->lang->line('Remove').'</button>
                        </div>
                      </div>
                      <div class="card-body">

                        <div class="form-group">
                          <label for="" > '.$this->lang->line('Language').'</label>
                          '.form_dropdown('localization_lang[]',$get_lan,$key,'class="form-control select2" id="language1"').'
                        </div>
                        <div class="form-group">
                          <label for="title"> '.$this->lang->line("Title").' </label>
                          <input name="localization_title[]"  class="form-control" type="textbox" value="'.$value['title'].'" id="textbox1">
                        </div>  

                        <div class="form-group">
                          <label for="description"> '.$this->lang->line("Description").'</label>
                          <textarea name="localization_description[]" class="form-control" id="textarea1">'.$value['description'].'</textarea>
                        </div> 
                      </div>
                    </div>';

                  }
                }
                else
                {

                    $final_string .= '<div class="card card-primary single_card">

                      <div class="card-header">
                        <h4>'.$this->lang->line("Localizations").'</h4>
                        <div class="card-header-action">
                          <button class="btn btn-outline-secondary video_edit_remove_div"><i class="fas fa-times"></i> '.$this->lang->line('Remove').'</button>
                        </div>
                      </div>
                      <div class="card-body">

                        <div class="form-group">
                          <label for="" > '.$this->lang->line('Language').'</label>
                          '.form_dropdown('localization_lang[]',$get_lan,$select_lang,'class="form-control select2" id="language1"').'
                        </div>
                        <div class="form-group">
                          <label for="title"> '.$this->lang->line("Title").' </label>
                          <input name="localization_title[]"  class="form-control" type="textbox" value="" id="textbox1">
                        </div>  

                        <div class="form-group">
                          <label for="description"> '.$this->lang->line("Description").'</label>
                          <textarea name="localization_description[]" class="form-control" id="textarea1"></textarea>
                        </div> 
                      </div>
                    </div>';

                    $i++;
                }
         


                if($i%2 == 0)
                  $even_or_odd = 'even';
                else
                  $even_or_odd = 'odd';
                
                 $final_string .= '<div class="clearfix video_edit_add_more_button_block">
                    <input type="hidden" id="video_edit_content_block" value="'.$i.'">
                    <input type="hidden" id="video_edit_odd_or_even" value="'.$even_or_odd.'">
                    <button class="btn btn-outline-primary float-right" id="video_edit_add_more"><i class="fa fa-plus-circle"></i> '.$this->lang->line('Add More Localizations').'</button>
                    </div>

                  </div>

                  </div>
               </div>
               <script src="'.base_url().'assets/js/system/select2.js"></script>
               <link rel="stylesheet" href="'.base_url('assets/css/system/select2_100.css').'">';

              echo $final_string;
          }
      }

   }


   public function update_video_data()
   {

     $this->ajax_check();
     $this->load->library('youtube_library');

     $video_id                = $this->input->post('video_id',true);
     $channel_id              = $this->input->post('channel_id',true);
     $video_title             = strip_tags($this->input->post('title',true));
     $video_description       = strip_tags($this->input->post('description',true));
     $video_tags              = strip_tags($this->input->post('tags',true));
     $video_language          = $this->input->post('defaultLanguage',true);
     $video_privacy_status    = $this->input->post('privacyStatus',true);
     $video_category_id       = $this->input->post('categoryId',true);

     $localization_has = $this->input->post('localization_has', true);
     $localizations_lang        = $this->input->post('localization_lang',true);
     $localizations_titles    = $this->input->post('localization_title',true);
     $localizations_descriptions  = $this->input->post('localization_description',true);
   
     $response = array();

     if ($video_title == "") 
     {

       $response['status'] = 'empty';
       $response['field'] = 'video_title';
       echo json_encode($response);
       exit;

     }

     if ($video_description == "")
     {
      
        $response['status'] = 'empty';
        $response['field'] = 'video_description';
        echo json_encode($response);
        exit;

     }

     if ($video_tags != '') $video_tags = explode(',', $video_tags);
     else $video_tags = array();

     if ($video_language == '0') $video_language = '';
     if ($video_privacy_status == '0') $video_privacy_status = '';


     $localizations = array();
     if (isset($localization_has) && $localization_has == 'localization_has') {

        if( count( array_unique( $localizations_lang ) ) < count($localizations_lang) )
        {
           $response['status'] = 'empty';
           $response['field'] = 'localizations_lan';
           echo json_encode($response);
           exit;
        }

        $i = 0;
        foreach ($localizations_titles as $key => $value) 
        {
          if(empty($value))
          {
              $response['status'] = 'empty';
              $response['field'] = 'localizations_titles';
              echo json_encode($response);
              exit;
          }

          $localizations[ $localizations_lang[ $i++ ] ]['title'] = $value;
        }

        $i = 0;
        foreach ($localizations_descriptions as $key => $value) 
        {
          if(empty($value))
          {
            $response['status'] = 'empty';
            $response['field'] = 'localizations_descriptions';
            echo json_encode($response);
            exit;
          }

          $localizations[ $localizations_lang[ $i++ ] ]['description'] = $value;
        }
     }
     
         
     // ************************************************//
     $status = $this->_check_usage($module_id = 9, $request = 1);
     if ($status == "2") {

         echo json_encode(array("status" => "failed", "message" => $this->lang->line("Sorry, your own video editing monthly limit has been exceeded.")));
         exit();
     } else if ($status == "3") {
         echo json_encode(array("status" => "failed", "message" => $this->lang->line("Sorry, your own video editing bulk limit has been exceeded.")));
         exit();
     }
     // ************************************************//
     $result = $this->youtube_library->update_video_info($video_id, $video_category_id, $video_language, $video_description, $video_tags, $video_title, $video_privacy_status, $localizations);


     if (is_object($result)) {
        
        $data = array(
            'title' => $result->snippet['title'],
            'description' => $result->snippet['description'],
            'tags' => json_encode($result->snippet['tags']),
            'categoryId' => $result->snippet['categoryId'],
            'defaultLanguage' => $result->snippet['defaultLanguage'],
            'localizations' => json_encode($result->localizations),
            'privacyStatus' => $video_privacy_status,
        );


        $this->basic->update_data('youtube_video_list', array('user_id' => $this->user_id, 'video_id' => $video_id), $data);
        $this->_insert_usage_log($module_id = 9, $request = 1);
     } else {
        $data = array();
     }

     if (count($data) > 0)
        $response['status'] = 'success';
     else {

        $response['status'] = 'failed';
        $response['message'] = $result;
     }

      echo json_encode($response);

   }


   
  public function video_manager()
  {

     $data['no_app_error'] = $this->check_app_settings();
     $channel_info = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id)));

     /* if any channel is selected already */
     if ($this->session->userdata('youtube_channel_info_table_id')) {

         if (!is_numeric($this->session->userdata('youtube_channel_info_table_id')))
             $this->session->set_userdata('youtube_channel_info_table_id', '');

         $channel_table_id = $this->session->userdata('youtube_channel_info_table_id');
         $channel_result = array();

         $i = 0;
         foreach ($channel_info as $info) {
             
             if ($info['id'] == $channel_table_id) {

                 $channel_result = $info;
                 unset($channel_info[$i]);
             }
             $i++;
         }

         array_unshift($channel_info, $channel_result);
     }

     $data['channel_info'] = $channel_info;
     $data['page_title'] = $this->lang->line("Video Manager");
     $data['body'] = "social_accounts/video_manager";
     $this->_viewcontroller($data);
  }


   public function playlist_manager()
   {

      if($this->session->userdata('user_type') != 'Admin' && !in_array(10,$this->module_access)) {
          redirect('404','refresh');
      }
      $data['no_app_error'] = $this->check_app_settings();

      $channel_info = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id)));

      /* if any channel is selected already */
      if ($this->session->userdata('youtube_channel_info_table_id')) {

          if (!is_numeric($this->session->userdata('youtube_channel_info_table_id')))
              $this->session->set_userdata('youtube_channel_info_table_id', '');

          $channel_table_id = $this->session->userdata('youtube_channel_info_table_id');
          $channel_result = array();

          $i = 0;
          foreach ($channel_info as $info) {
              
              if ($info['id'] == $channel_table_id) {

                  $channel_result = $info;
                  unset($channel_info[$i]);
              }
              $i++;
          }

          array_unshift($channel_info, $channel_result);
      }

      $data['channel_info'] = $channel_info;
      $data['page_title'] = $this->lang->line("Playlist Manager");
      $data['page_title'] = "Playlist Management";
      $data['body'] = "social_accounts/playlist_manager";
      $this->_viewcontroller($data);
   }


   public function channels_playlist_info()
   {
      $this->ajax_check();

      $channel_id = $this->input->post('channel_id', true);
      $channel_table_id = $this->input->post('channel_table_id', true);

      $this->session->set_userdata('youtube_channel_info_table_id', $channel_table_id);
      $this->load->library("youtube_library");

      $playlist_result = $this->youtube_library->get_channel_playlist($channel_id);
      $has_playlist = false;

      $final_string = '
            <script src="'.base_url().'assets/js/system/make_scroll.js"></script>
            <div id="middle_column_contents">
              <div class="card main_card">
                  <div class="card-header padding-left-10 padding-right-10">
                    <div class="col-9 padding-0 d_inherit">
                      <h4 class="put_page_name_url"><i class="fas fa-list"></i> '. $this->lang->line("Playlist"). '</h4>
                      <input type="text" class="form-control width_135px" id="search_playlist"  onkeyup="search_in_div(this,\'playlist_search_div\', \'playlist_search_text\')"  autofocus placeholder="'. $this->lang->line('Search...'). '">
                    </div>
                    <div class="col-3 padding-0">
                      <button class="btn btn-sm btn-outline-primary playlist_action float-right" id="add_playlist_button" channel_id="'. $channel_id .'">
                          <i class="fas fa-plus-circle"></i>
                          <span class="d-none d-sm-inline">'.$this->lang->line('Add Playlist'). '</span>
                      </button>
                    </div>
                  </div>
                  <div class="card-body p-0">
                  <div class="makeScroll">';

                  if (!isset($playlist_result["error"])) {         
                     

                      if (isset($playlist_result['items']) && count($playlist_result['items']) > 0) {

                          $has_playlist = true;

                          foreach ($playlist_result['items'] as $item) {
                              

                              $id = $item['id'];
                              $name = $item['snippet']['title'];
                              $thumbnail = $item['snippet']['thumbnails']['medium']['url'];
                              $video_count = $item['contentDetails']['itemCount'];

                              if (isset($item['snippet']['description'])) $description = htmlspecialchars($item['snippet']['description']);
                              else $description = '';

                              if (isset($item['snippet']['tags'])) $tags = $item['snippet']['tags'];
                              else $tags = array();

                              $tags = implode(',', $tags);


                              if (strlen($name) > 40)
                                  $name = substr($name, 0, 40). '...';


                              $final_string .= '
                                  <div class="card card-large-icons card-condensed playlist_item playlist_search_div m-2 padding_10_10_0_25px" playlist_id="'.$id.'"  playlist_id="'. $id .'">
                                    <div class="row full_width">
                                      <div class="col-3 col-md-2 padding-0">
                                          <div class="avatar-item">
                                            <img src="'. $thumbnail .'" class="rounded-circle playlistImg" width="45" height="45" alt="" >
                                          </div>
                                      </div>

                                      <div class="col-9 col-md-10 padding-0">
                                        <div class="card-body padding-10">
                                          <div class="row">
                                            <div class="col-9 col-md-10">
                                              <h4 class="playlist_search_text">'. $name .'</h4>                    
                                              <span class="" id="">'. $this->lang->line('Videos') .' : <b class="number_of_videos">'. $video_count .'</b></span>
                                            </div>
                                            <div class="col-3 col-md-2 pr-0">
                                              <div class="dropdown float-right margin_top_10px"> 
                                                <a href="#" data-toggle="dropdown"><i class="fas fa-edit font_size_18px"></i></a>
                                                <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                  <li>
                                                    <a href="https://youtube.com/playlist?list='.$id.'" class="dropdown-item has-icon visit_playlist"><i class="fas fa-eye"></i> '.$this->lang->line('Visit Playlist').'
                                                    </a>
                                                  </li>
                                                  <li>
                                                    <a href="" class="dropdown-item has-icon edit_playlist"><i class="fas fa-edit"></i> '.$this->lang->line('Edit Playlist').'
                                                      <input type="hidden" class="playlist_id" value="'. $id .'">
                                                      <input type="hidden" class="title" value="'. $name .'">
                                                      <input type="hidden" class="description" value="'. $description .'">
                                                      <input type="hidden" class="tags" value="'. $tags .'">
                                                    </a>
                                                  </li>
                                                  <li>
                                                    <a href="" class="dropdown-item has-icon delete_playlist text-danger"><i class="fas fa-trash-alt"></i> '.$this->lang->line('Delete Playlist').'
                                                      <input type="hidden" class="playlist_id" value="'. $id .'">
                                                    </a>
                                                  </li>
                                                </ul>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                          }        
                      }
                      
                  } else {
                    $error_mesage = $playlist_result["message"];
                    $final_string = '
                    <div class="card no_shadow" id="nodata">
                      <div class="card-body">
                        <div class="empty-state">
                          <img class="img-fluid height_300px" src="'.base_url('assets/img/drawkit/drawkit-full-stack-man-colour.svg').'" alt="image">
                          <h2 class="mt-0">'.$error_mesage.'</h2>
                        </div>
                      </div>
                    </div>';
                  }

                  $final_string .='
                        </div>
                  </div>
                </div>
              </div>';



      $response = array(
        'has_playlist' => $has_playlist,
        'content' => $final_string,
      );

      echo json_encode($response);

   }



   public function playlists_video_info()
   {
      $this->ajax_check();

      /* get inputs */
      $playlist_id = $this->input->post('playlist_id', true);
      $channel_id = $this->input->post('channel_id', true);

      $this->session->set_userdata("playlist_manager_playlist_list_clicked_id",$playlist_id);

      $this->load->library('youtube_library');


      /* get this playlist all videos */
      $video_count = 0;
      $error_message = "";
      $next_page = '';
      do
      {
          $playlist_info = $this->youtube_library->playlist_item($playlist_id, $next_page);

          if(isset($playlist_info['nextPageToken'])) $next_page = $playlist_info['nextPageToken'];
          else $next_page = '';

          if (!isset($playlist_info['error'])) {
              
              $video_id_str = '';
              foreach($playlist_info['items'] as $info)
              {
                  $video_id = $info['snippet']['resourceId']['videoId'];
                  $video_id_str.= $video_id . ",";
                  $video_information[$video_id]['publishedAt'] = $info['snippet']['publishedAt'];
                  $video_information[$video_id]['title'] = $info['snippet']['title'];
                  $video_information[$video_id]['playlist_item_id'] = $info['id'];
                  if (isset($info['snippet']['thumbnails']['medium']['url']))
                      $video_information[$video_id]['thumbnails'] = $info['snippet']['thumbnails']['medium']['url'];
              }
              $video_info = $this->youtube_library->get_video_details_list($video_id_str);
              if (isset($video_info['items'])) {

                  foreach($video_info['items'] as $v_info)
                  {
                      $video_count++;
                      $single_video_id = $v_info['id'];
                      $video_information[$single_video_id]['duration'] = isset($v_info['contentDetails']['duration']) ? $v_info['contentDetails']['duration'] : "";
                      $video_information[$single_video_id]['caption'] = isset($v_info['contentDetails']['caption']) ? $v_info['contentDetails']['caption'] : "";
                      $video_information[$single_video_id]['viewCount'] = isset($v_info['statistics']['viewCount']) ? $v_info['statistics']['viewCount'] : "";
                      $video_information[$single_video_id]['likeCount'] = isset($v_info['statistics']['likeCount']) ? $v_info['statistics']['likeCount'] : "";
                      $video_information[$single_video_id]['dislikeCount'] = isset($v_info['statistics']['dislikeCount']) ? $v_info['statistics']['dislikeCount'] : "";
                      $video_information[$single_video_id]['commentCount'] = isset($v_info['statistics']['commentCount']) ? $v_info['statistics']['commentCount'] : "";
                  }
              }
          }
          else
          {
            $error_message = $playlist_info['message'];
            break;
          }

      } while ($next_page != '');

      $card_header ='<div class="card-header padding-left-10 padding-right-10">
                        <div class="col-9 padding-0 d_inherit">
                          <h4><i class="fab fa-youtube"></i> '. $this->lang->line("Videos") .'</h4>
                          <input type="text" class="form-control list_search width_150px" id="" placeholder="'. $this->lang->line('Search...') .'"  id="search_video"  onkeyup="search_in_div(this,\'video_search_div\', \'video_search_text\')" >
                        </div>
                        <div class="col-3 padding-0">
                          <div class="dropdown d-inline float-right">
                            <a href="#" data-toggle="dropdown" class="btn btn-outline-primary dropdown-toggle"><i class="fas fa-plus-circle"></i> <span class="d-none d-sm-inline">'.$this->lang->line("Add Video").'</span></a>
                            <input type="hidden" id="parent_info" channel_id="'. $channel_id .'" playlist_id="'. $playlist_id .'">
                            <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <li>
                                <a href="" class="dropdown-item has-icon search_video"><i class="fas fa-search"></i> '.$this->lang->line('Search Video').'</a>
                              </li>
                              <li>
                                <a href="" class="dropdown-item has-icon own_video" channel_id="'. $channel_id .'"><i class="fas fa-paw"></i> '.$this->lang->line('Own video').'</a>
                              </li>
                            </ul>
                          </div> 
                        </div>
                        
                    </div>';



      $final_string = '
            <script src="'.base_url().'assets/js/system/playlist_video_info.js"></script>
            <div id="right_column_content">
                <div class="card main_card">
                    '.$card_header.'

                    <div class="card-body p-2">
                        <div class="makeScroll">
                           <ul class="list-unstyled">';


      if (isset($video_information)) {

          foreach ($video_information as $video_id => $info) {

              if (!isset($info['thumbnails']))
                  $info['thumbnails'] = '../assets/img/youtube.png';

              if (!isset($info['duration']))
                  $info['duration'] = '0S';
              else {

                  $info['duration'] = str_replace('PT', '', $info['duration']);
                  $info['duration'] = str_replace('H', ':', $info['duration']);
                  $info['duration'] = str_replace('M', ':', $info['duration']);
              }


              if (!isset($info['viewCount']))
                  $info['viewCount'] = '0';

              if (!isset($info['likeCount']))
                  $info['likeCount'] = '0';
              
              $final_string .= '

              <li class="media h-100 video_search_div mb-3">
                <div class="avatar-item m-0">
                  <img alt="image" src="'. $info['thumbnails'] .'" class="video_thumbnail pointer" video_id="'. $video_id .'" width="70" height="70" style="border:1px solid #eee;">
                  <div class="avatar-badge">
                      <span class="delete_video pointer pointer blue"  playlist_item_id="'. $info['playlist_item_id'] .'">
                          <i class="fas fa-trash-alt red"></i>
                      </span>
                  </div>
                </div>
                <div class="media-body my-auto pl-3">
                  <div class="media-title video_thumbnail video_search_text pointer" video_id="'. $video_id .'"> '.$info['title'].'</div> 
                  <div class="text-muted"><i class="far fa-clock" data-toggle="tooltip" title="'.$this->lang->line('Duration').'"></i> '.$info['duration'].' <i class="fas fa-eye" data-toggle="tooltip" title="'.$this->lang->line('Views').'"></i> '.custom_number_format($info['viewCount']). ' <i class="fas fa-heart" data-toggle="tooltip" title="'.$this->lang->line('Likes').'"></i> '.custom_number_format($info['likeCount']).'</div>
                </div>
              </li>';

          }



      }


      $final_string .='       </ul>                          
                          </div>
                    </div>
                </div>
            </div>
            <script src="'.base_url().'assets/js/system/tooltip_popover.js"></script>';

      if($error_message!="")
      $final_string = '
      <div class="card no_shadow" id="nodata">
        '.$card_header.'
        <div class="card-body">
          <div class="empty-state">
            <img class="img-fluid height_300px" src="'.base_url('assets/img/drawkit/drawkit-full-stack-man-colour.svg').'" alt="image">
            <h2 class="mt-0">'.$error_message.'</h2>
          </div>
        </div>
      </div>';
      else if(!isset($video_information) || count($video_information)==0)
      $final_string = '
      <div class="card no_shadow" id="nodata">
        '.$card_header.'
        <div class="card-body">
          <div class="empty-state">
            <img class="img-fluid height_300px" src="'.base_url('assets/img/drawkit/drawkit-full-stack-man-colour.svg').'" alt="image">
            <h2 class="mt-0">'.$this->lang->line("No video found.").'</h2>
          </div>
        </div>
      </div>';

      
      echo json_encode(array("video_info"=>$final_string,"video_count"=>$video_count));

   }



   public function create_user_playlist()
   {
      $this->ajax_check();

      $channel_id = $this->input->post('channel_id', true);
      $playlist_id = $this->input->post('playlist_id', true);
      $title = strip_tags($this->input->post('title', true));
      $description = strip_tags($this->input->post('description', true));
      $privacy_type = $this->input->post('privacy_type', true);
      $tags = $this->input->post('tags', true);

      $response = array();

      if ($title == '') {

          $response['type'] = 'error';
          $response['message'] = $this->lang->line("Title can not be empty.");

          echo json_encode($response);
          exit;
      }

      if ($tags != '') $tags = explode(',', $tags);
      else $tags = array();
      if ($privacy_type == '0')
          $privacy_type = '';


      /* create / edit  playlist */
      $this->load->library('youtube_library');

      if ($playlist_id != '') $result = $this->youtube_library->update_playlist($playlist_id, $title, $description, $privacy_type, $tags);
      else $result = $this->youtube_library->create_playlist($title, $description, $privacy_type, $tags);

      if (isset($result->snippet['title'])) {

          $response['type'] = 'success';

          if ($playlist_id != '') $response['message'] = $this->lang->line("Playlist has been updated successfully");
          else $response['message'] = $this->lang->line("Playlist has been created successfully");
      }
      else 
      {
          $response['type'] = 'error';
          $response['message'] = isset($result['message']) ? $result['message'] : $this->lang->line("Something went wrong.");
      }

      echo json_encode($response);
   }


   public function delete_playlist()
   {
      $this->ajax_check();

      $channel_id = $this->input->post('channel_id', true);
      $playlist_id = $this->input->post('playlist_id', true);

      $response = array();
      /* delete playlist */
      $this->load->library('youtube_library');
      $result = $this->youtube_library->delete_playlist($playlist_id);

      if (isset($result['error']))
      {
          
          $response['type'] = 'error';
          $response['message'] = isset($result['message']) ? $result['message'] : $this->lang->line("Something went wrong.");
      }
      else
      {
          $response['type'] = 'success';
          $response['message'] = $this->lang->line("Playlist has been deleted successfully.");
      }

      echo json_encode($response);

   }



   public function add_playlist_video()
   {
      $this->ajax_check();

      $channel_id = $this->input->post('channel_id', true);
      $playlist_id = $this->input->post('playlist_id', true);
      $videos = $this->input->post('videos', true);

      $response = array();

      /* add video on playlist */
      $this->load->library('youtube_library');

      foreach ($videos as $video) {

          $result = $this->youtube_library->add_video_on_playlist($playlist_id, $video);
      }

      if (is_object($result)) {
          
          $response['type'] = 'success';
          $response['message'] = $this->lang->line("Videos have added to playlist successfully. It may take few minutes to affect changes.");
      } else {

          $response['type'] = 'error';
          $response['message'] = $result;
      }

      echo json_encode($response);

   }


   public function delete_playlist_video()
   {
      $this->ajax_check();

      $playlist_item_id = $this->input->post('playlist_item_id', true);
      $playlist_id = $this->input->post('playlist_id', true);
      $channel_id = $this->input->post('channel_id', true);

      $response = array();

      /* delete playlist itme */
      $this->load->library('youtube_library');
      $api_response = $this->youtube_library->delete_video_from_playlist($playlist_item_id);
      
      if(isset($api_response['error']))
      {
        $response['type'] = 'error';
        $response['message'] = isset($api_response['message']) ? $api_response['message'] : $this->lang->line("Something went wrong.");
        $response['playlist_id'] = $playlist_id;
        echo json_encode($response); exit();
      }

      $response['type'] = 'success';
      $response['message'] = $this->lang->line("Video has been removed from playlist successfully. It may take few minutes to affect changes.");
      $response['playlist_id'] = $playlist_id;

      echo json_encode($response);

   }


   
   public function upload_video_list()
   {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(8,$this->module_access)) {
            redirect('404','refresh');
        }

        $data['page_title'] = $this->lang->line("Scheduled Video Uploader");
        $data['body'] = "social_accounts/upload_video_list";
        $data['get_video_category_list'] = $this->get_video_category();
        $data['time_zone_list'] = $this->_time_zone_list();  
        $channels = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id)), array('title', 'channel_id'));

        /* channel dropdown */
        $channel_dropdown = array();
        foreach ($channels as $key => $channel) 
            $channel_dropdown[$channel['channel_id']] = $channel['title'];
        array_unshift($channel_dropdown, $this->lang->line("Please select a channel"));
        $data['channel_dropdown'] = $channel_dropdown;
        $this->_viewcontroller($data);


   }


   public function upload_video_list_data()
   {
        $this->ajax_check();

        $search_value = $_POST['search']['value'];
        $display_columns = array("#",'id','channel_id','video_id','title','time_zone','upload_time','upload_status');
        $search_columns = array( 'title','channel_id','upload_time');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where = '';
        $sql = "SELECT * FROM `youtube_video_upload` ";
        $where .= "WHERE `user_id` = $this->user_id";

        if ($search_value != '') 
        {
            $where .= " AND (";
            foreach ($search_columns as $key => $value) {
              $where .= "`$value` LIKE '%$search_value%' OR ";
            }
            $where = rtrim($where, " OR ");
            $where .= ")";
        }


        $sql .= $where;
        $sql_without_limit = $sql;

        if ($start) {
          $sql .= " ORDER BY $order_by LIMIT $limit, $start";
        } else {
          $sql .= " ORDER BY $order_by LIMIT $limit";
        }


        /* filtered result */
        $query = $this->db->query($sql);
        $i = 0; $info = array();
        foreach ($query->result_array() as $row) {
            $info[$i++] = $row;
        }

        /* total results */
        $total_rows_array= $this->db->query($sql_without_limit);
        $total_result= count($total_rows_array->result_array());


        foreach ($info as $key => $value) {
            $info[$key]['upload_time'] = '<div class="min_width_120px">'.date('jS M y H:i', strtotime($value['upload_time'])).'</div>';
        }


        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start);
        
        echo json_encode($data);
   }

   public function scheduled_video_edit()
   {
       $this->ajax_check();

       $campaign_id = $this->input->post('campaign_id', true);

       $campaign_info = $this->basic->get_data('youtube_video_upload', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));


       if (count($campaign_info) > 0) {

           $campaign_info['result_status'] = 'success';
           echo json_encode($campaign_info);
       }
       else {
           $response = array('result_status' => 'failed');
           echo json_encode($response);
       }
   }

   public function scheduled_video_edit_action()
   {

       $this->ajax_check();

       $response = array();

       /* get inputs from form */
       $submit_type  = $this->input->post('submit_type',true);
       $campaign_id = $this->input->post('campaign_id', true);
       $title = trim(strip_tags($this->input->post('title', true)));
       $description = trim(strip_tags($this->input->post('description', true)));
       $category = $this->input->post('category', true);
       $time_zone = $this->input->post('time_zone', true);
       $tags = trim(strip_tags($this->input->post('tags', true)));
       $video_type = $this->input->post('video_type', true);
       $schedule_time = $this->input->post('schedule_time', true);
       if($submit_type == 'edit')
       {
            $response['type'] = 'empty_field';
            $error_flag = true;
            if ($title == '')
                $response['field'] = $this->lang->line("Please provide video title");
            
            else if($category == '')
              $response['field'] = $this->lang->line("Please select a category");
            else if($time_zone =='')
              $response['field'] = $this->lang->line("Please select time zone");
            else if($video_type == '')
              $response['field'] = $this->lang->line("Please select privacy");
            else if($schedule_time=='')
              $response['field'] = $this->lang->line("Please set schedule date time");
            else
                $error_flag = false;

            if ($error_flag) {

                echo json_encode($response);
                exit;
            }

            $data = array(
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'time_zone' => $time_zone,
                'tags' => $tags,
                'privacy_type' => $video_type,
                'upload_time' => $schedule_time,
                'upload_status' => '0'
            );
            if($this->basic->update_data('youtube_video_upload',array('id' => $campaign_id), $data))
              $response['type'] ='success';
            else
              $response['type'] ='failed';


       }

       else
       {
        $response['type'] = 'fail';
       }
       echo json_encode($response);

   }

   public function delete_schedule_video_campaign()
   {
       $this->ajax_check();

       $campaign_id = $this->input->post('campaign_id', true);
       
       $response = array();

       $result = $this->basic->get_data('youtube_video_upload', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));

       if (count($result) > 0)
       {
         $this->basic->delete_data('youtube_video_upload', array('id' => $campaign_id));
         if ($result[0]['upload_status'] != '2') $this->_delete_usage_log(8, 1);     
         $response['status'] = 1;
         $response['message'] = $this->lang->line('Campaign has been deleted successfully.');
       }
       else
       {
          $response['status'] = 0;
          $response['message'] = $this->lang->line('Something went wrong.');
       }
       echo json_encode($response);
   }


   public function upload_video()
   {
        $data['no_app_error'] = $this->check_app_settings();
        
        $data['page_title'] = $this->lang->line("Upload Video");
        $data['body'] = "social_accounts/upload_video";
        $data['get_video_category_list'] = $this->get_video_category();
        $data['time_zone_list'] = $this->_time_zone_list();

        $channel_info = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id)));

        /* if any channel is selected already */
        if ($this->session->userdata('youtube_channel_info_table_id')) {

            if (!is_numeric($this->session->userdata('youtube_channel_info_table_id')))
                $this->session->set_userdata('youtube_channel_info_table_id', '');

            $channel_table_id = $this->session->userdata('youtube_channel_info_table_id');
            $channel_result = array();

            $i = 0;
            foreach ($channel_info as $info) {
                
                if ($info['id'] == $channel_table_id) {

                    $channel_result = $info;
                    unset($channel_info[$i]);
                }
                $i++;
            }

            array_unshift($channel_info, $channel_result);
        }

        $data['channel_info'] = $channel_info;
        $this->_viewcontroller($data);

   }

   public function youtube_video_upload_action()
   {
      
      $this->ajax_check();

      $channel_id = $this->input->post('channel_id',true);
      $title = trim(strip_tags($this->input->post('title',true)));
      $description = trim(strip_tags($this->input->post('description',true)));
      $tags = trim(strip_tags($this->input->post('tags',true)));
      $category = $this->input->post('category',true);
      $privacy_type = $this->input->post('privacy_type',true);
      $time_zone = $this->input->post('time_zone',true);
      $schedule_time = $this->input->post('schedule_time',true);
      $file_name = $this->input->post('video_url',true);

      $response = array();

      if($title =="")
      {
        $response['status'] = 'title';
        echo json_encode($response);
        exit;
      }
      if ($channel_id == "") 
      {
        $response['status'] = 'channel_id';
        echo json_encode($response);
        exit;

      }
      if($category == "")
      {
        $response['status'] = 'category';
        echo json_encode($response);
        exit;
      }

      if($privacy_type == "")
      {
        $response['status'] = 'privacy_type';
        echo json_encode($response);
        exit;
      }
      if($time_zone == "")
      {
        $response['status'] = 'time_zone';
        echo json_encode($response);
        exit;
      }      
      if($schedule_time == "")
      {
        $response['status'] = 'schedule_time';
        echo json_encode($response);
        exit;
      }
      if($file_name == "")
      {
        $response['status'] = 'file_name';
        echo json_encode($response);
        exit;
      }

      if ($file_name !="") 
      {
          $data = array
          (
              'user_id' => $this->user_id,
              'channel_id' => $channel_id,
              'channel_auto_id' => $this->session->userdata("youtube_channel_info_table_id"),
              'title' => $title,
              'description' => $description,
              'tags' => $tags,
              'category' => $category,
              'privacy_type' => $privacy_type,
              'time_zone' => $time_zone,
              'link' => $file_name,
              'upload_time' => $schedule_time,
              'upload_status' => '0',
          );

          // ************************************************//
          $status = $this->_check_usage($module_id = 8, $request = 1);
          if ($status == "2") {

              echo json_encode(array("status" => "error", "message" => $this->lang->line("Sorry, your video upload monthly limit has been exceeded.")));
              exit();
          } else if ($status == "3") {

              echo json_encode(array("status" => "error", "message" => $this->lang->line("Sorry, your video upload bulk limit has been exceeded.")));
              exit();
          }
          // ************************************************//



          if($this->basic->insert_data('youtube_video_upload',$data))
          {
            $this->_insert_usage_log($module_id = 8, $request = 1);
            $response['status'] = 'success';
            echo json_encode($response);
          }
          else
          {
            $response['status'] = 'error';
            $response['message'] = $this->lang->line("Your data has been failed to stored into the database");
            echo json_encode($response);
          }
      }
      else
      {
        $response['status'] = 'file_name';
        echo json_encode($response);
        exit;
      }




   }

   public function video_upload_files()
   {

     if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();

     $ret=array();
     $output_dir = FCPATH."upload/video";
     if (isset($_FILES["file"])) {
      
         $error =$_FILES["file"]["error"];
     
         $post_fileName =$_FILES["file"]["name"];
         $post_fileName_array=explode(".", $post_fileName);
         $ext=array_pop($post_fileName_array);
         $filename=implode('.', $post_fileName_array);
         $filename="files_".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;
        
         $allow=".mov,.mpeg4,.mp4,.avi,.wmv,.mpegps,.flv,.3gpp,.webm";
         $allow=str_replace('.', '', $allow);
         $allow=explode(',', $allow);
         if(!in_array(strtolower($ext), $allow)) 
         {
             echo json_encode("Bad request.");
             exit;
         }

         
         move_uploaded_file($_FILES["file"]["tmp_name"], $output_dir.'/'.$filename);
         $ret[]= $filename;
         echo json_encode($filename);
     }
   }

   public function video_delete_files()
   {

     if(!$_POST) exit();

     $output_dir = FCPATH."upload/video/";
     if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
     {
          $fileName =$_POST['name'];
          $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
          $filePath = $output_dir. $fileName;
          if (file_exists($filePath))
          {
             unlink($filePath);

          }
     }
   }


   public function keyword_tracking_settings_action()
   {

      $this->ajax_check();
      $user_id = $this->user_id;
      $response = array();
      $keyword = strip_tags($this->input->post('keyword', true));
      $name = strip_tags($this->input->post('name', true));
      $video_id = strip_tags($this->input->post('video_id', true));
      $mark_for_dashboard = $this->input->post('mark_for_dashboard', true);
      
      if($keyword == '')
      {
        $response['status'] = 'keyword';
        $response['message'] = $this->lang->line('Please Enter Keyword');
        echo json_encode($response); exit;
        
      }


      if ($video_id == '') 
      {
         $response['status'] = 'video';
         $response['message'] = $this->lang->line('Please Enter video id');
         echo json_encode($response); exit;
      }

      $data = array(
          'keyword' => $keyword,
          'name' => $name,
          'youtube_video_id' => $video_id,
          'user_id' => $user_id,
          'add_date' => date("Y-m-d H:i:s"),
          'deleted' => '0'
      );

      // ************************************************//
      $status = $this->_check_usage($module_id = 11, $request = 1);
      if ($status == "2") {

          echo json_encode(array("status" => "0", "message" => $this->lang->line("Sorry, your rank tracking campaign monthly limit has been exceeded.")));
          exit();
      } else if ($status == "3") {

          echo json_encode(array("status" => "0", "message" => $this->lang->line("Sorry, your rank tracking campaign bulk limit has been exceeded.")));
          exit();
      }
      // ************************************************//
      
      if($this->basic->insert_data('video_position_set', $data))
      {
        
          $this->_insert_usage_log($module_id = 11, $request = 1);
          $response['status'] = '1';
          $response['message'] = $this->lang->line('Video rank settings has been saved successfully.');

          if ($mark_for_dashboard == 'on') {
            
              $this->basic->update_data('video_position_set', array('user_id' => $user_id), array('mark_for_dashboard' => '0'));
              $this->basic->update_data('video_position_set', array('user_id' => $user_id, 'youtube_video_id' => $video_id), array('mark_for_dashboard' => '1'));
          }
      }
      else
      {
        $response['status'] = '0';
        $response['message'] = $this->lang->line('Something Went Wrong');
      }

      echo json_encode($response);
   }

   public function get_rank_tracker_data()
   {
      
      $this->ajax_check();
      $video_id =  $this->input->post('video_id', true);
      $user_id = $this->user_id;
      $info = $this->basic->get_data('video_position_set',array("where" => array('youtube_video_id'=>$video_id,'user_id'=>$user_id)));
      
      $len = 0;
      if (count($info) > 0) {
        $len = count($info) - 1;
      }

      $data_make = isset($info[$len]) ? $info[$len] : '';

      echo json_encode($data_make);

   }

   public function search_keyword_id()
   {

        $this->ajax_check();
        $video_id = $this->input->post('video_id',true);

        $user_id = $this->user_id;
        $info = $this->basic->get_data('video_position_set',array("where"=>array('youtube_video_id'=>$video_id, 'user_id'=>$user_id)));
        
        $len = 0;
        if (count($info) > 0) {
          $len = count($info) - 1;
        }
        $data_make = isset($info[$len]) ? $info[$len] : '';

        echo json_encode($data_make);


   }

   public function keyword_position_report_data()
   {
     $this->ajax_check();

     $keyword = $this->input->post("keyword");
     $from_date = $this->input->post("from_date");
     $to_date = $this->input->post("to_date");
     if($keyword == '')
     {
      echo '';
      exit;
     }
     $where['where'] = array(
         "keyword_id" => $keyword,
         "date >=" => date("Y-m-d",strtotime($from_date)),
         "date <=" => date("Y-m-d",strtotime($to_date))
         );
     $join = array(
         "video_position_set" => "video_position_report.keyword_id=video_position_set.id,left"
         );

     $keyword_position = $this->basic->get_data("video_position_report",$where,$select="",$join);

     $str = '
            <script src="'.base_url().'assets/js/system/youtube_action.js"></script>
            <div class="card">
              <div class="card-header">
                <h4> <i class="fa fa-youtube"></i> '.$this->lang->line('Search Results').'</h4>
                <div class="card-header-action">
                  <div class="badges">
                    <span class="badge badge-primary">'.count($keyword_position).'</span>
                  </div>                    
                </div>
              </div>
            </div>
          ';

        if(!empty($keyword_position))
        $str.='
            <div class="table-responsive">
            
            <table class="table table-borderless table-dark text-white">
              <thead>
                <tr>
                  <th class="text-warning">'.$this->lang->line("Date").'</th>
                  <th class="text-warning">'.$this->lang->line("Tracking Name").'</th>
                  <th class="text-warning">'.$this->lang->line("Keyword").'</th>
                  <th class="text-warning">'.$this->lang->line("Rank").'</th>
                  <th class="text-warning">'.$this->lang->line("Play").'</th>

                </tr>
              </thead>';
          
        foreach($keyword_position as $value){   
          
                $str .= '<tbody>
                            <tr>
                            <td>'.date('jS F y', strtotime($value['date'])).'</td>
                            <td>'.$value['name'].'</td>
                            <td>'.$value['keyword'].'</td>
                            <td class="text-center">'.$value['youtube_position'].'</td>
                            <td><a class="youtube" data-toggle="tooltip" title="'.$this->lang->line("Video ID").': '.$value['youtube_video_id'].'" href="https://www.youtube.com/embed/'.$value['youtube_video_id'].'"> '.$this->lang->line("play").' </a></td>
                            </tr>
                        </tbody>';
            }

            if(!empty($keyword_position))
            $str.='</table></div>';

            echo $str.'<script src="'.base_url().'assets/js/system/tooltip_popover.js"></script>';




   }




   public function rank_tracking_settings()
   {
      if($this->session->userdata('user_type') != 'Admin' && !in_array(11,$this->module_access)) {
          redirect('404','refresh');
      }

     $data['body'] = 'social_accounts/keyword_list';
     $data['page_title'] = $this->lang->line("Video Rank Tracking");
     $this->_viewcontroller($data);


   }


   public function rank_keyword_list_data()
   {
        $this->ajax_check();

        $search_value = $_POST['search']['value'];
        $display_columns = array("#",'id','name','keyword','youtube_video_id','add_date','youtube_video_id','youtube_video_id');
        $search_columns = array( 'name','keyword','add_date');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where = array();
        if ($search_value != '') 
        {
            $or_where = array();
            foreach ($search_columns as $key => $value) 
            $or_where[$value.' LIKE '] = "%$search_value%";
            $where = array('or_where' => $or_where);
        }
        $where['where'] = array('user_id' => $this->user_id);

        $table="video_position_set";
        $info=$this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        foreach ($info as $key => $value) {
            $info[$key]['add_date'] = date('jS F y', strtotime($value['add_date']));
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start);
        echo json_encode($data);
   }

   public function delete_rank_tracker_campaign()
   {
       $this->ajax_check();

       $campaign_id = $this->input->post('campaign_id', true);
       
       $response = array();

       $result = $this->basic->get_data('video_position_set', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));

       if (count($result) > 0)
       {
          $this->basic->delete_data('video_position_set', array('id' => $campaign_id));
          $this->basic->delete_data('video_position_report', array('keyword_id' => $campaign_id));
          $this->_delete_usage_log(11, 1);
          $response['status'] = 1;
          $response['message'] = $this->lang->line('Campaign has been deleted successfully.');
       }
       else
       {
           $response['status'] = 0;
           $response['message'] = $this->lang->line('Something went wrong.');
       }

       echo json_encode($response);
   }

   public function keyword_position_report()
   {
      if($this->session->userdata('user_type') != 'Admin' && !in_array(11,$this->module_access)) {
          redirect('404','refresh');
      }

      $data['body'] = 'social_accounts/keyword_report';
      $data['page_title'] = $this->lang->line("Keyword Tracking Report");
      $where['where'] = array('user_id' => $this->user_id);
      $keywords = $this->basic->get_data("video_position_set",$where);
      $keywords_array = array();
      foreach($keywords as $value){
          $keywords_array[$value['id']] = $value['keyword']." | ".$value['name']." | ".$value['youtube_video_id'];
        }


      $data['keywords'] = $keywords_array;
      $this->_viewcontroller($data);

   }

   /* analytics start */

   public function channel_analytics($channel_list_table_id = '', $start_date = '', $end_date = '')
   {

       if ($channel_list_table_id == '') {

           redirect('404','location');
       }

       $channel_list_info = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id, 'id' => $channel_list_table_id)));
       if (count($channel_list_info) == 0) {

           redirect('404','location');
       }



       $channel_info = $this->basic->get_data('youtube_channel_info', array('where' => array('youtube_channel_info.user_id' => $this->user_id, 'youtube_channel_info.channel_id' => $channel_list_info[0]['channel_id'])),array("youtube_channel_info.*"));
       if (count($channel_info) == 0) {

           redirect('404','location');
       }

       $this->session->set_userdata('individual_channel_access_token',$channel_info[0]['access_token']);
       $this->session->set_userdata('individual_channel_refresh_token',$channel_info[0]['refresh_token']);
       $channel_id = $channel_info[0]['channel_id'];

       $analytics_data = $this->get_individual_channel_analytics($channel_id, $start_date, $end_date);

       $this->session->unset_userdata('individual_channel_access_token');
       $this->session->unset_userdata('individual_channel_refresh_token');


       $data['body'] = 'social_accounts/channel_analytics';
       $data['page_title'] = $this->lang->line("Channel Analytics");
       $data['channel_list_table_id'] = $channel_list_table_id;
       $data['tables'] = isset($analytics_data['tables']) ? $analytics_data['tables'] : array();
       $data['report_data'] = isset($analytics_data['report_data']) ? $analytics_data['report_data'] : array();
       $data['start_date'] = isset($analytics_data['start_date']) ? $analytics_data['start_date'] : '';
       $data['end_date'] = isset($analytics_data['end_date']) ? $analytics_data['end_date'] : '';
       $data['channel_info'] = $channel_info;
       $this->_viewcontroller($data);

   }


   protected function get_individual_channel_analytics($channel_id = '', $start_date = '', $end_date = '')
   {
      $this->load->library('youtube_library');


      if($start_date == '' && $end_date == '')
      {            
          $end_date = date("Y-m-d"); 
          $start_date = date('Y-m-d', strtotime("-28 days"));
      }
      else
      {
          $end_date = str_replace('-', '/', $end_date);
          $start_date = str_replace('-', '/', $start_date);
          $end_date = date("Y-m-d",strtotime($end_date));
          $start_date = date("Y-m-d",strtotime($start_date));
      }


      $dDiff = strtotime($end_date) - strtotime($start_date);
      $no_of_days = floor($dDiff/(60*60*24));


      // ***************************** views ********************
      $metrics = 'views';
      $dimension = 'day';
      $sort = 'day';
      $views_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $views_info = (array)$views_info;

      $views = array();
      $views['max_val'] = 0;
      if(!empty($views_info['rows']))
      {
          foreach($views_info['rows'] as $value)
          {
              $views_raw[$value[0]] = $value[1];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($views_raw[$day_count])){

                  $views['date'][] = $day_count;
                  $views['views'][] = $views_raw[$day_count];
                  $views['max_val'] = max($views['max_val'], $views_raw[$day_count]);
              }
              else
              {
                  $views['date'][] = $day_count;
                  $views['views'][] = 0;
              }
          }
      }
      $response['views'] = json_encode($views);
      // ***************************** end of views ********************
      


      // ***************************** minute_watch ********************
      $metrics = 'estimatedMinutesWatched';
      $dimension = 'day';
      $sort = 'day';
      $minute_watch_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $minute_watch_info = (array)$minute_watch_info;

      $minute_watch = array();
      $minute_watch['max_val'] = 0;
      if(!empty($minute_watch_info['rows']))
      {
          foreach($minute_watch_info['rows'] as $value)
          {
              $minute_watch_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($minute_watch_raw[$day_count])){
                  $minute_watch['date'][] = $day_count;
                  $minute_watch['minute_watch'][] = $minute_watch_raw[$day_count];
                  $minute_watch['max_val'] = max($minute_watch['max_val'], $minute_watch_raw[$day_count]);
              }
              else
              {
                  $minute_watch['date'][] = $day_count;
                  $minute_watch['minute_watch'][] = 0;
              }
          }
      }
      $response['minute_watch'] = json_encode($minute_watch);
      // ************************* end of minute_watch ***********************




      // ***************************** minute_watch ********************
      $metrics = 'averageViewDuration';
      $dimension = 'day';
      $sort = 'day';
      $second_watch_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $second_watch_info = (array)$second_watch_info;

      $second_watch = array();
      $second_watch['max_val'] = 0;
      if(!empty($second_watch_info['rows']))
      {
          foreach($second_watch_info['rows'] as $value)
          {
              $second_watch_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($second_watch_raw[$day_count])){
                  $second_watch['date'][] = $day_count;
                  $second_watch['second_watch'][] = $second_watch_raw[$day_count];
                  $second_watch['max_val'] = max($second_watch['max_val'], $second_watch_raw[$day_count]);
              }
              else
              {
                  $second_watch['date'][] = $day_count;
                  $second_watch['second_watch'][] = 0;
              }
          }
      }
      $response['second_watch'] = json_encode($second_watch);
      // ************************* end of minute_watch ***********************



      // ***************************** subscriber_vs_unsubscriber ********************
      $metrics = 'subscribersGained';
      $dimension = 'day';
      $sort = 'day';
      $subscriber_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $subscriber_info = (array)$subscriber_info;


      $metrics = 'subscribersLost';
      $unsubscriber_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $unsubscriber_info = (array)$unsubscriber_info;


      $subscriber_vs_unsubscriber = array();

      $subscriber_vs_unsubscriber['max_val'] = 0;
      $subscriber_vs_unsubscriber['max_val_subs'] = 0;
      $subscriber_vs_unsubscriber['max_val_unsubs'] = 0;
      if(!empty($subscriber_info['rows']) && !empty($unsubscriber_info['rows']))
      {
          for($i=0;$i<count($subscriber_info['rows']);$i++)
          {
              $subscriber_vs_unsubscriber_raw[$i]['date'] = $subscriber_info['rows'][$i][0];
              $subscriber_vs_unsubscriber_raw[$i]['subscriber'] = $subscriber_info['rows'][$i][1];
              $subscriber_vs_unsubscriber_raw[$i]['unsubscriber'] = $unsubscriber_info['rows'][$i][1];
          }

          foreach($subscriber_vs_unsubscriber_raw as $value)
          {
              $subscriber_vs_unsubscriber_raw_2[$value['date']]['subscriber'] = $value['subscriber'];
              $subscriber_vs_unsubscriber_raw_2[$value['date']]['unsubscriber'] = $value['unsubscriber'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($subscriber_vs_unsubscriber_raw_2[$day_count])){
                  $subscriber_vs_unsubscriber['date'][] = $day_count;
                  $subscriber_vs_unsubscriber['subscriber'][] = $subscriber_vs_unsubscriber_raw_2[$day_count]['subscriber'];
                  $subscriber_vs_unsubscriber['unsubscriber'][] = $subscriber_vs_unsubscriber_raw_2[$day_count]['unsubscriber'];

                  $subscriber_vs_unsubscriber['max_val_subs'] = max($subscriber_vs_unsubscriber['max_val_subs'], $subscriber_vs_unsubscriber_raw_2[$day_count]['subscriber']);
                  $subscriber_vs_unsubscriber['max_val_unsubs'] = max($subscriber_vs_unsubscriber['max_val_unsubs'], $subscriber_vs_unsubscriber_raw_2[$day_count]['unsubscriber']);
              }
              else
              {
                  $subscriber_vs_unsubscriber['date'][] = $day_count;
                  $subscriber_vs_unsubscriber['subscriber'][] = 0;
                  $subscriber_vs_unsubscriber['unsubscriber'][] = 0;
              }
          }
      }

      $subscriber_vs_unsubscriber['max_val'] = max($subscriber_vs_unsubscriber['max_val_unsubs'], $subscriber_vs_unsubscriber['max_val_subs']);
      unset($subscriber_vs_unsubscriber['max_val_unsubs']);
      unset($subscriber_vs_unsubscriber['max_val_subs']);
      $response['subscriber_vs_unsubscriber'] = json_encode($subscriber_vs_unsubscriber);

      
      // ************************* end of subscriber_vs_unsubscriber ***********************


      // ***************************** likes_vs_dislikes ********************
      $metrics = 'likes';
      $dimension = 'day';
      $sort = 'day';
      $likes_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $likes_info = (array)$likes_info;

      $metrics = 'dislikes';
      $dislikes_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $dislikes_info = (array)$dislikes_info;


      $likes_vs_dislikes = array();

      $likes_vs_dislikes['max_val'] = 0;
      $likes_vs_dislikes['max_val_likes'] = 0;
      $likes_vs_dislikes['max_val_dislikes'] = 0;

      if(!empty($likes_info['rows']) && !empty($dislikes_info['rows']))
      {
          for($i=0;$i<count($likes_info['rows']);$i++)
          {
              $likes_vs_dislikes_raw[$i]['date'] = $likes_info['rows'][$i][0];
              $likes_vs_dislikes_raw[$i]['likes'] = $likes_info['rows'][$i][1];
              $likes_vs_dislikes_raw[$i]['dislikes'] = $dislikes_info['rows'][$i][1];
          }

          foreach($likes_vs_dislikes_raw as $value)
          {
              $likes_vs_dislikes_raw_2[$value['date']]['likes'] = $value['likes'];
              $likes_vs_dislikes_raw_2[$value['date']]['dislikes'] = $value['dislikes'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($likes_vs_dislikes_raw_2[$day_count])){
                  $likes_vs_dislikes['date'][] = $day_count;
                  $likes_vs_dislikes['likes'][] = $likes_vs_dislikes_raw_2[$day_count]['likes'];
                  $likes_vs_dislikes['dislikes'][] = $likes_vs_dislikes_raw_2[$day_count]['dislikes'];

                  $likes_vs_dislikes['max_val_likes'] = max($likes_vs_dislikes['max_val_likes'], $likes_vs_dislikes_raw_2[$day_count]['likes']);
                  $likes_vs_dislikes['max_val_dislikes'] = max($likes_vs_dislikes['max_val_dislikes'], $likes_vs_dislikes_raw_2[$day_count]['dislikes']);
              }
              else
              {
                  $likes_vs_dislikes['date'][] = $day_count;
                  $likes_vs_dislikes['likes'][] = 0;
                  $likes_vs_dislikes['dislikes'][] = 0;
              }
          }
      }

      $likes_vs_dislikes['max_val'] = max($likes_vs_dislikes['max_val_likes'], $likes_vs_dislikes['max_val_dislikes']);
      unset($likes_vs_dislikes['max_val_likes']);
      unset($likes_vs_dislikes['max_val_dislikes']);

      $response['likes_vs_dislikes'] = json_encode($likes_vs_dislikes);

      
      // ************************* end of likes_vs_dislikes ***********************



      // ***************************** video_added_vs_removed ********************
      $metrics = 'videosAddedToPlaylists';
      $dimension = 'day';
      $sort = 'day';
      $video_added_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $video_added_info = (array)$video_added_info;


      $metrics = 'videosRemovedFromPlaylists';
      $video_removed_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $video_removed_info = (array)$video_removed_info;

      $video_added_vs_removed = array();
      $video_added_vs_removed['max_val'] = 0;
      $video_added_vs_removed['max_val_added'] = 0;
      $video_added_vs_removed['max_val_removed'] = 0;

      if(!empty($video_added_info['rows']) && !empty($video_removed_info['rows']))
      {
          for($i=0;$i<count($video_added_info['rows']);$i++)
          {
              $video_added_vs_removed_raw[$i]['date'] = $video_added_info['rows'][$i][0];
              $video_added_vs_removed_raw[$i]['added'] = $video_added_info['rows'][$i][1];
              $video_added_vs_removed_raw[$i]['removed'] = $video_removed_info['rows'][$i][1];
          }

          foreach($video_added_vs_removed_raw as $value)
          {
              $video_added_vs_removed_raw_2[$value['date']]['added'] = $value['added'];
              $video_added_vs_removed_raw_2[$value['date']]['removed'] = $value['removed'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($video_added_vs_removed_raw_2[$day_count])){
                  $video_added_vs_removed['date'][] = $day_count;
                  $video_added_vs_removed['added'][] = $video_added_vs_removed_raw_2[$day_count]['added'];
                  $video_added_vs_removed['removed'][] = $video_added_vs_removed_raw_2[$day_count]['removed'];

                  $video_added_vs_removed['max_val_added'] = max($video_added_vs_removed['max_val_added'], $video_added_vs_removed_raw_2[$day_count]['added']);
                  $video_added_vs_removed['max_val_removed'] = max($video_added_vs_removed['max_val_removed'], $video_added_vs_removed_raw_2[$day_count]['removed']);
              }
              else
              {
                  $video_added_vs_removed['date'][] = $day_count;
                  $video_added_vs_removed['added'][] = 0;
                  $video_added_vs_removed['removed'][] = 0;
              }
          }

      }

      $video_added_vs_removed['max_val'] = max($video_added_vs_removed['max_val_added'], $video_added_vs_removed['max_val_removed']);
      unset($video_added_vs_removed['max_val_added']);
      unset($video_added_vs_removed['max_val_removed']);

      $response['video_added_vs_removed'] = json_encode($video_added_vs_removed);

      
      // ************************* end of video_added_vs_removed ***********************


      // ***************************** comments ********************
      $metrics = 'comments';
      $dimension = 'day';
      $sort = 'day';
      $comments_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $comments_info = (array)$comments_info;

      $comments = array();
      $comments['max_val'] = 0;
      if(!empty($comments_info['rows']))
      {
          foreach($comments_info['rows'] as $value)
          {
              $comments_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($comments_raw[$day_count])){
                  $comments['date'][] = $day_count;
                  $comments['comments'][] = $comments_raw[$day_count];

                  $comments['max_val'] = max($comments['max_val'], $comments_raw[$day_count]);
              }
              else
              {
                  $comments['date'][] = $day_count;
                  $comments['comments'][] = 0;
              }
          }
      }
      $response['comments'] = json_encode($comments);
      // ************************* end of comments ***********************


      // ***************************** shares ********************
      $metrics = 'shares';
      $dimension = 'day';
      $sort = 'day';
      $shares_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $shares_info = (array)$shares_info;

      $shares = array();
      $shares['max_val'] = 0;
      if(!empty($shares_info['rows']))
      {
          foreach($shares_info['rows'] as $value)
          {
              $shares_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($shares_raw[$day_count])){
                  $shares['date'][] = $day_count;
                  $shares['shares'][] = $shares_raw[$day_count];

                  $shares['max_val'] = max($shares['max_val'], $shares_raw[$day_count]);
              }
              else
              {
                  $shares['date'][] = $day_count;
                  $shares['shares'][] = 0;
              }
          }
      }
      $response['shares'] = json_encode($shares);
      // ************************* end of shares ***********************


      // ***************************** country map ********************
      $metrics = 'views';
      $dimension = 'country';
      $sort = '-views';
      $max_result = 1000;
      $country_map_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
      $country_map_info = (array)$country_map_info;

      $country_map = array();
      $country_names_array = $this->get_country_names();
      if(!empty($country_map_info['rows']))
      {
          $i=0;
          $a = array('Country','Views');
          $country_map[$i] = $a;
          foreach($country_map_info['rows'] as $value)
          {
              $i++;
              $temp = array();
              $temp[] = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
              $temp[] = $value[1];
              $country_map[$i] = $temp;
          }
      }
      
      $response['country_map'] = htmlspecialchars(json_encode($country_map), ENT_QUOTES, 'UTF-8');
      // ************************* end of country map ***********************

      

      // ***************************** top 10 country ********************
      $metrics = 'views';
      $dimension = 'country';
      $sort = '-views';
      $max_result = 10;
      $top_ten_country_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
      $top_ten_country_info = (array)$top_ten_country_info;
      $top_ten_country_chart_data = array();

      $top_ten_country_table = "<table class='table table-bordered table-hover table-striped'><tr><th>Sl</th><th>Country</th><th>Views</th></tr>";
      if(!empty($top_ten_country_info['rows']))
      {
          $i = 0;
          $total_views = 0;
          foreach($top_ten_country_info['rows'] as $value)
          {
              $i++;
              $country = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
              $top_ten_country_table .= "<tr><td>".$i."</td><td>".$country."</td><td>".$value[1]."</td></tr>";
              $total_views = $total_views+$value[1];
          }
          $top_ten_country_table .= "</table>";


          $color_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
          $i=0;
          $color_count=0;
          foreach($top_ten_country_info['rows'] as $value)
          {
              $top_ten_country_chart_data[$i]['value'] = $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;              
              $top_ten_country_chart_data[$i]['color'] = $color_array[$color_count];
              $top_ten_country_chart_data[$i]['highlight'] = $color_array[$color_count];
              $top_ten_country_chart_data[$i]['label'] = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
              $i++;
              $color_count++;
              if($color_count>=count($color_array)) $color_count=0;
          }
      }
      $data['top_ten_country_table'] = $top_ten_country_table;
      $response['top_ten_country_chart_data'] = json_encode($top_ten_country_chart_data);
      // ************************* end of top 10 country ***********************



      // ***************************** gender percentage ********************
      $metrics = 'viewerPercentage';
      $dimension = 'gender';
      $sort = '';
      $max_result = 10;
      $gender_percentage_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
      $gender_percentage_info = (array)$gender_percentage_info;
      $gender_percentage = array();

      $gender_percentage_list = "";
      if(!empty($gender_percentage_info['rows']))
      {
          $color_array = array("#E27263","#E5C77B");
          $i=0;
          foreach($gender_percentage_info['rows'] as $value)
          {
              $gender_percentage[$i]['value'] = $value[1];
              $gender_percentage[$i]['color'] = $color_array[$i];
              $gender_percentage[$i]['highlight'] = $color_array[$i];
              $gender_percentage[$i]['label'] = $value[0];

              $gender_percentage_list .= '<li><i class="fa fa-circle-o" style="color: '.$color_array[$i].';"></i> '.$value[0].' : '.$value[1].' %</li>';
              $i++;
          }
      }
      $response['gender_percentage_list'] = $gender_percentage_list;
      $response['gender_percentage'] = json_encode($gender_percentage);
      // ************************* gender percentage ***********************



      // ***************************** age group ********************
      $metrics = 'viewerPercentage';
      $dimension = 'ageGroup';
      $sort = '';
      $max_result = 10;
      $age_group_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
      $age_group_info = (array)$age_group_info;
      $age_group = array();

      $age_group_list = "";
      if(!empty($age_group_info['rows']))
      {
          $color_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
          $color_array = array_reverse($color_array);
          $i=0;
          $color_count=0;
          foreach($age_group_info['rows'] as $value)
          {
              $age_group[$i]['value'] = $value[1];
              $age_group[$i]['color'] = $color_array[$color_count];
              $age_group[$i]['highlight'] = $color_array[$color_count];
              $age_group[$i]['label'] = $value[0];

              $age_group_list .= '<li><i class="fa fa-circle-o" style="color: '.$color_array[$color_count].';"></i> '.$value[0].' : '.$value[1].' %</li>';
              $i++;
              $color_count++;
              if($color_count>=count($color_array)) $color_count=0;
          }
      }
      $response['age_group_list'] = $age_group_list;
      $response['age_group'] = json_encode($age_group);
      // ************************* age group ***********************



      // ***************************** views ********************
      $metrics = 'annotationImpressions';
      $dimension = 'day';
      $sort = 'day';
      $annotation_impression_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $annotation_impression_info = (array)$annotation_impression_info;

      $annotation_impression = array();
      $annotation_impression['max_val'] = 0;
      if(!empty($annotation_impression_info['rows']))
      {
          foreach($annotation_impression_info['rows'] as $value)
          {
              $annotation_impression_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($annotation_impression_raw[$day_count])){
                  $annotation_impression['date'][] = $day_count;
                  $annotation_impression['annotation_impressions'][] = $annotation_impression_raw[$day_count];

                  $annotation_impression['max_val'] = max($annotation_impression['max_val'], $annotation_impression_raw[$day_count]);
              }
              else
              {
                  $annotation_impression['date'][] = $day_count;
                  $annotation_impression['annotation_impressions'][] = 0;
              }
          }
      }
      $response['annotation_impressions'] = json_encode($annotation_impression);
      // ************************* end of views ***********************



      // ***************************** annotation close and click impressions ********************
      $metrics = 'annotationClosableImpressions';
      $dimension = 'day';
      $sort = 'day';
      $close_impression_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $close_impression_info = (array)$close_impression_info;


      $metrics = 'annotationClickableImpressions';
      $click_impression_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $click_impression_info = (array)$click_impression_info;

      $annotation_close_click_impressions = array();

      $annotation_close_click_impressions['max_val'] = 0;
      $annotation_close_click_impressions['max_click_val'] = 0;
      $annotation_close_click_impressions['max_close_val'] = 0;

      if(!empty($close_impression_info['rows']) && !empty($click_impression_info['rows']))
      {
          for($i=0;$i<count($close_impression_info['rows']);$i++)
          {
              $annotation_close_click_impressions_raw[$i]['date'] = $close_impression_info['rows'][$i][0];
              $annotation_close_click_impressions_raw[$i]['click_impression'] = $click_impression_info['rows'][$i][1];
              $annotation_close_click_impressions_raw[$i]['close_impression'] = $close_impression_info['rows'][$i][1];
          }

          foreach($annotation_close_click_impressions_raw as $value)
          {
              $annotation_close_click_impressions_raw_2[$value['date']]['click_impression'] = $value['click_impression'];
              $annotation_close_click_impressions_raw_2[$value['date']]['close_impression'] = $value['close_impression'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($annotation_close_click_impressions_raw_2[$day_count])){
                  $annotation_close_click_impressions['date'][] = $day_count;
                  $annotation_close_click_impressions['click_impression'][] = $annotation_close_click_impressions_raw_2[$day_count]['click_impression'];
                  $annotation_close_click_impressions['close_impression'][] = $annotation_close_click_impressions_raw_2[$day_count]['close_impression'];

                  $annotation_close_click_impressions['max_click_val'] = max($annotation_close_click_impressions['max_click_val'], $annotation_close_click_impressions_raw_2[$day_count]['click_impression']);
                  $annotation_close_click_impressions['max_close_val'] = max($annotation_close_click_impressions['max_close_val'], $annotation_close_click_impressions_raw_2[$day_count]['close_impression']);
              }
              else
              {
                  $annotation_close_click_impressions['date'][] = $day_count;
                  $annotation_close_click_impressions['click_impression'][] = 0;
                  $annotation_close_click_impressions['close_impression'][] = 0;
              }
          }
      }

      $annotation_close_click_impressions['max_val'] = max($annotation_close_click_impressions['max_click_val'], $annotation_close_click_impressions['max_close_val']);
      unset($annotation_close_click_impressions['max_click_val']);
      unset($annotation_close_click_impressions['max_close_val']);

      $response['annotation_close_click_impressions'] = json_encode($annotation_close_click_impressions);

      
      // ************************* end of annotation close and click impressions ***********************


      
      // ***************************** annotation close and click impressions ********************
      $metrics = 'annotationCloses';
      $dimension = 'day';
      $sort = 'day';
      $annotation_close_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $annotation_close_info = (array)$annotation_close_info;


      $metrics = 'annotationClicks';
      $annotation_click_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
      $annotation_click_info = (array)$annotation_click_info;

      $annotation_clicks_closes = array();

      $annotation_clicks_closes['max_val'] = 0;
      $annotation_clicks_closes['max_click_val'] = 0;
      $annotation_clicks_closes['max_close_val'] = 0;

      if(!empty($annotation_close_info['rows']) && !empty($annotation_click_info['rows']))
      {
          for($i=0;$i<count($annotation_close_info['rows']);$i++)
          {
              $annotation_clicks_closes_raw[$i]['date'] = $annotation_close_info['rows'][$i][0];
              $annotation_clicks_closes_raw[$i]['annotation_click'] = $annotation_click_info['rows'][$i][1];
              $annotation_clicks_closes_raw[$i]['annotation_close'] = $annotation_close_info['rows'][$i][1];
          }

          foreach($annotation_clicks_closes_raw as $value)
          {
              $annotation_clicks_closes_raw_2[$value['date']]['annotation_click'] = $value['annotation_click'];
              $annotation_clicks_closes_raw_2[$value['date']]['annotation_close'] = $value['annotation_close'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($annotation_clicks_closes_raw_2[$day_count])){
                  $annotation_clicks_closes['date'][] = $day_count;
                  $annotation_clicks_closes['annotation_click'][] = $annotation_clicks_closes_raw_2[$day_count]['annotation_click'];
                  $annotation_clicks_closes['annotation_close'][] = $annotation_clicks_closes_raw_2[$day_count]['annotation_close'];

                  $annotation_clicks_closes['max_click_val'] = max($annotation_clicks_closes['max_click_val'], $annotation_clicks_closes_raw_2[$day_count]['annotation_click']);
                  $annotation_clicks_closes['max_close_val'] = max($annotation_clicks_closes['max_close_val'], $annotation_clicks_closes_raw_2[$day_count]['annotation_close']);

              }
              else
              {
                  $annotation_clicks_closes['date'][] = $day_count;
                  $annotation_clicks_closes['annotation_click'][] = 0;
                  $annotation_clicks_closes['annotation_close'][] = 0;
              }
          }
      }

      $annotation_clicks_closes['max_val'] = max($annotation_clicks_closes['max_click_val'], $annotation_clicks_closes['max_close_val']);
      unset($annotation_clicks_closes['max_click_val']);
      unset($annotation_clicks_closes['max_close_val']);

      $response['annotation_clicks_closes'] = json_encode($annotation_clicks_closes);

      
      // ************************* end of annotation close and click impressions ***********************



      // ***************************** top 10 country ********************
      $metrics = 'views';
      $dimension = 'deviceType';
      $sort = '';
      $max_result = '';
      $device_type_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
      $device_type_info = (array)$device_type_info;
      $device_type_chart_data = array();

      
      $device_type_table = array();
      if(!empty($device_type_info['rows']))
      {
          $i = 0;
          $total_views = 0;
          foreach($device_type_info['rows'] as $value)
          {
              $i++;
              $device = ucfirst($value[0]);
              $device_type_table[] = array('device' => $device, 'value' => $value[1]);
              $total_views = $total_views+$value[1];
          }


          $color_array = array("#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
          $i=0;
          $color_count=0;
          foreach($device_type_info['rows'] as $value)
          {
              $device_type_chart_data['value'][] = $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;
              $device_type_chart_data['color'][] = $color_array[$color_count];
              $device_type_chart_data['highlight'][] = $color_array[$color_count];
              $device_type_chart_data['label'][] = ucfirst($value[0]);

              $device_type_table[$i]['percentage'] =  $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;

              $i++;
              $color_count++;
              if($color_count>=count($color_array)) $color_count=0;
          }
      }
      $data['device_type_table'] = $device_type_table;
      $response['device_type_chart_data'] = json_encode($device_type_chart_data);
      // ************************* end of top 10 country ***********************




      // ***************************** top 10 country ********************
      $metrics = 'views';
      $dimension = 'operatingSystem';
      $sort = '';
      $max_result = '';
      $operating_system_info = $this->youtube_library->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
      $operating_system_info = (array)$operating_system_info;
      $operating_system_chart_data = array();

     
      $operating_system_table = array();
      if(!empty($operating_system_info['rows']))
      {
          $i = 0;
          $total_views = 0;
          foreach($operating_system_info['rows'] as $value)
          {
              $i++;
              $device = ucfirst($value[0]);
              $operating_system_table[] = array('operatingSystem' => $device, 'value' => $value[1]);
              $total_views = $total_views+$value[1];
          }

          $color_array = array("#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
          $color_array = array_reverse($color_array);
          $i=0;
          $color_count=0;
          foreach($operating_system_info['rows'] as $value)
          {
              $operating_system_chart_data['value'][] =  $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;
              $operating_system_chart_data['color'][] = $color_array[$color_count];
              $operating_system_chart_data['highlight'][] = $color_array[$color_count];
              $operating_system_chart_data['label'][] = ucfirst($value[0]);

              $operating_system_table[$i]['percentage'] = $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;

              $i++;
              $color_count++;
              if($color_count>=count($color_array)) $color_count=0;
          }
      }
      $data['operating_system_table'] = $operating_system_table;
      $response['operating_system_chart_data'] = json_encode($operating_system_chart_data);
      // ************************* end of top 10 country ***********************


      return array('report_data' => $response, 'tables' => $data, 'start_date' => $start_date, 'end_date' => $end_date);

   }


   public function video_analytics($channel_table_id = '', $video_table_id = '', $start_date = '', $end_date = '')
   {
      
        if ($video_table_id == '' || $channel_table_id == '') {

            redirect('404','location');
        }


        $video_info = $this->basic->get_data('youtube_video_list', array('where' => array('user_id' => $this->user_id, 'id' => $video_table_id)));


        if (count($video_info) == 0) {

            redirect('404','location');
        }


        $analytics_data = $this->get_individual_video_analytics($video_info[0]['channel_id'], $video_info[0]['video_id'], $this->user_id, $start_date, $end_date);

        $data['body'] = 'social_accounts/video_analytics';
        $data['page_title'] = $this->lang->line("Video Analytics");
        $data['channel_table_id'] = $channel_table_id;
        $data['video_info'] = $video_info;
        $data['video_table_id'] = $video_table_id;
        $data['tables'] = isset($analytics_data['tables']) ? $analytics_data['tables'] : array();
        $data['report_data'] = isset($analytics_data['report_data']) ? $analytics_data['report_data'] : array();
        $data['start_date'] = isset($analytics_data['start_date']) ? $analytics_data['start_date'] : '';
        $data['end_date'] = isset($analytics_data['end_date']) ? $analytics_data['end_date'] : '';
        $this->_viewcontroller($data);

   }



   protected function get_individual_video_analytics($channel_id, $video_id, $user_id, $start_date = '', $end_date = '')
   {

      $this->load->library('youtube_library');


      $channel_info = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $user_id, 'channel_id' => $channel_id)));

      if (count($channel_info) == 0) return;

      $this->session->set_userdata('individual_video_access_token',$channel_info[0]['access_token']);
      $this->session->set_userdata('individual_video_refresh_token',$channel_info[0]['refresh_token']);
      $channel_id = $channel_info[0]['channel_id'];


      if($start_date == '' && $end_date == '')
      {            
          $end_date = date("Y-m-d");
          $start_date = date('Y-m-d', strtotime("-28 days"));
      }
      else
      {
          $end_date = str_replace('-', '/', $end_date);
          $start_date = str_replace('-', '/', $start_date);
          $end_date = date("Y-m-d",strtotime($end_date));
          $start_date = date("Y-m-d",strtotime($start_date));
      }


      $dDiff = strtotime($end_date) - strtotime($start_date);
      $no_of_days = floor($dDiff/(60*60*24));

      
      // ***************************** views ********************
      $metrics = 'views';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $views_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $views_info = (array)$views_info;

      $views = array();
      $views['max_val'] = 0;
      if(!empty($views_info['rows']))
      {
          foreach($views_info['rows'] as $value)
          {
              $views_raw[$value[0]] = $value[1];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($views_raw[$day_count])){
                  $views['date'][] = $day_count;
                  $views['views'][] = $views_raw[$day_count];

                  $views['max_val'] = max($views['max_val'], $views_raw[$day_count]);
              }
              else
              {
                  $views['date'][] = $day_count;
                  $views['views'][] = 0;
              }
          }
          
          
      }
      $response['views'] = json_encode($views);
      // ************************* end of views ***********************


      // ***************************** minute_watch ********************
      $metrics = 'estimatedMinutesWatched';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $minute_watch_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $minute_watch_info = (array)$minute_watch_info;

      $minute_watch = array();
      $minute_watch['max_val'] = 0;
      if(!empty($minute_watch_info['rows']))
      {
          foreach($minute_watch_info['rows'] as $value)
          {
              $minute_watch_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($minute_watch_raw[$day_count])){
                  $minute_watch['date'][] = $day_count;
                  $minute_watch['minute_watch'][] = $minute_watch_raw[$day_count];

                  $minute_watch['max_val'] = max($minute_watch['max_val'], $minute_watch_raw[$day_count]);
              }
              else
              {
                  $minute_watch['date'][] = $day_count;
                  $minute_watch['minute_watch'][] = 0;
              }
          }
      }
      $response['minute_watch'] = json_encode($minute_watch);
      // ************************* end of minute_watch ***********************



      // ***************************** minute_watch ********************
      $metrics = 'averageViewDuration';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $second_watch_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $second_watch_info = (array)$second_watch_info;

      $second_watch = array();
      $second_watch['max_val'] = 0;
      if(!empty($second_watch_info['rows']))
      {
          foreach($second_watch_info['rows'] as $value)
          {
              $second_watch_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($second_watch_raw[$day_count])){
                  $second_watch['date'][] = $day_count;
                  $second_watch['second_watch'][] = $second_watch_raw[$day_count];

                  $second_watch['max_val'] = max($second_watch['max_val'], $second_watch_raw[$day_count]);
              }
              else
              {
                  $second_watch['date'][] = $day_count;
                  $second_watch['second_watch'][] = 0;
              }
          }

      }
      $response['second_watch'] = json_encode($second_watch);
      // ************************* end of minute_watch ***********************




      // ***************************** subscriber_vs_unsubscriber ********************
      $metrics = 'subscribersGained';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $subscriber_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $subscriber_info = (array)$subscriber_info;


      $metrics = 'subscribersLost';
      $unsubscriber_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $unsubscriber_info = (array)$unsubscriber_info;

      $subscriber_vs_unsubscriber = array();

      $subscriber_vs_unsubscriber['max_val'] = 0;
      $subscriber_vs_unsubscriber['max_val_subs'] = 0;
      $subscriber_vs_unsubscriber['max_val_unsubs'] = 0;

      if(!empty($subscriber_info['rows']) && !empty($unsubscriber_info['rows']))
      {            
          for($i=0;$i<count($subscriber_info['rows']);$i++)
          {
              $subscriber_vs_unsubscriber_raw[$i]['date'] = $subscriber_info['rows'][$i][0];
              $subscriber_vs_unsubscriber_raw[$i]['subscriber'] = $subscriber_info['rows'][$i][1];
              $subscriber_vs_unsubscriber_raw[$i]['unsubscriber'] = $unsubscriber_info['rows'][$i][1];
          }

          foreach($subscriber_vs_unsubscriber_raw as $value)
          {
              $subscriber_vs_unsubscriber_raw_2[$value['date']]['subscriber'] = $value['subscriber'];
              $subscriber_vs_unsubscriber_raw_2[$value['date']]['unsubscriber'] = $value['unsubscriber'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($subscriber_vs_unsubscriber_raw_2[$day_count])){
                  $subscriber_vs_unsubscriber['date'][] = $day_count;
                  $subscriber_vs_unsubscriber['subscriber'][] = $subscriber_vs_unsubscriber_raw_2[$day_count]['subscriber'];
                  $subscriber_vs_unsubscriber['unsubscriber'][] = $subscriber_vs_unsubscriber_raw_2[$day_count]['unsubscriber'];

                  $subscriber_vs_unsubscriber['max_val_subs'] = max($subscriber_vs_unsubscriber['max_val_subs'], $subscriber_vs_unsubscriber_raw_2[$day_count]['subscriber']);
                  $subscriber_vs_unsubscriber['max_val_unsubs'] = max($subscriber_vs_unsubscriber['max_val_unsubs'], $subscriber_vs_unsubscriber_raw_2[$day_count]['unsubscriber']);
              }
              else
              {
                  $subscriber_vs_unsubscriber['date'][] = $day_count;
                  $subscriber_vs_unsubscriber['subscriber'][] = 0;
                  $subscriber_vs_unsubscriber['unsubscriber'][] = 0;
              }
          }



      }

      $subscriber_vs_unsubscriber['max_val'] = max($subscriber_vs_unsubscriber['max_val_subs'], $subscriber_vs_unsubscriber['max_val_unsubs']);
      unset($subscriber_vs_unsubscriber['max_val_subs']);
      unset($subscriber_vs_unsubscriber['max_val_unsubs']);

      $response['subscriber_vs_unsubscriber'] = json_encode($subscriber_vs_unsubscriber);
      
      // ************************* end of subscriber_vs_unsubscriber ***********************




      // ***************************** likes_vs_dislikes ********************
      $metrics = 'likes';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $likes_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $likes_info = (array)$likes_info;

      $metrics = 'dislikes';
      $dislikes_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $dislikes_info = (array)$dislikes_info;

      $likes_vs_dislikes = array();

      $likes_vs_dislikes['max_val'] = 0;
      $likes_vs_dislikes['max_val_likes'] = 0;
      $likes_vs_dislikes['max_val_dislikes'] = 0;

      if(!empty($likes_info['rows']) && !empty($dislikes_info['rows']))
      {
          for($i=0;$i<count($likes_info['rows']);$i++)
          {
              $likes_vs_dislikes_raw[$i]['date'] = $likes_info['rows'][$i][0];
              $likes_vs_dislikes_raw[$i]['likes'] = $likes_info['rows'][$i][1];
              $likes_vs_dislikes_raw[$i]['dislikes'] = $dislikes_info['rows'][$i][1];
          }

          foreach($likes_vs_dislikes_raw as $value)
          {
              $likes_vs_dislikes_raw_2[$value['date']]['likes'] = $value['likes'];
              $likes_vs_dislikes_raw_2[$value['date']]['dislikes'] = $value['dislikes'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($likes_vs_dislikes_raw_2[$day_count])){
                  $likes_vs_dislikes['date'][] = $day_count;
                  $likes_vs_dislikes['likes'][] = $likes_vs_dislikes_raw_2[$day_count]['likes'];
                  $likes_vs_dislikes['dislikes'][] = $likes_vs_dislikes_raw_2[$day_count]['dislikes'];


                  $likes_vs_dislikes['max_val_likes'] = max($likes_vs_dislikes['max_val_likes'], $likes_vs_dislikes_raw_2[$day_count]['likes']);
                  $likes_vs_dislikes['max_val_dislikes'] = max($likes_vs_dislikes['max_val_dislikes'], $likes_vs_dislikes_raw_2[$day_count]['dislikes']);
              }
              else
              {
                  $likes_vs_dislikes['date'][] = $day_count;
                  $likes_vs_dislikes['likes'][] = 0;
                  $likes_vs_dislikes['dislikes'][] = 0;
              }
          }
      }

      $likes_vs_dislikes['max_val'] = max($likes_vs_dislikes['max_val_likes'], $likes_vs_dislikes['max_val_dislikes']);
      unset($likes_vs_dislikes['max_val_likes']);
      unset($likes_vs_dislikes['max_val_dislikes']);

      $response['likes_vs_dislikes'] = json_encode($likes_vs_dislikes);
      
      // ************************* end of likes_vs_dislikes ***********************




      // ***************************** comments ********************
      $metrics = 'comments';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $comments_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $comments_info = (array)$comments_info;

      $comments = array();
      $comments['max_val'] = 0;
      if(!empty($comments_info['rows']))
      {
          foreach($comments_info['rows'] as $value)
          {
              $comments_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($comments_raw[$day_count])){
                  $comments['date'][] = $day_count;
                  $comments['comments'][] = $comments_raw[$day_count];

                  $comments['max_val'] = max($comments['max_val'], $comments_raw[$day_count]);
              }
              else
              {
                  $comments['date'][] = $day_count;
                  $comments['comments'][] = 0;
              }
          }
      }
      $response['comments'] = json_encode($comments);
      // ************************* end of comments ***********************



      // ***************************** shares ********************
      $metrics = 'shares';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $shares_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $shares_info = (array)$shares_info;

      $shares = array();
      $shares['max_val'] = 0;
      if(!empty($shares_info['rows']))
      {
          foreach($shares_info['rows'] as $value)
          {
              $shares_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($shares_raw[$day_count])){
                  $shares['date'][] = $day_count;
                  $shares['shares'][] = $shares_raw[$day_count];

                  $shares['max_val'] = max($shares['max_val'], $shares_raw[$day_count]);
              }
              else
              {
                  $shares['date'][] = $day_count;
                  $shares['shares'][] = 0;
              }
          }
      }
      $response['shares'] = json_encode($shares);
      // ************************* end of shares ***********************




      // ***************************** country map ********************
      $metrics = 'views';
      $dimension = 'country';
      $sort = '-views';
      $filter = "video=={$video_id}";
      $max_result = 1000;
      $country_map_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $country_map_info = (array)$country_map_info;

      $country_map = array();
      $country_names_array = $this->get_country_names();
      if(!empty($country_map_info['rows']))
      {
          $i=0;
          $a = array('Country','Views');
          $country_map[$i] = $a;
          foreach($country_map_info['rows'] as $value)
          {
              $i++;
              $temp = array();
              $temp[] = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
              $temp[] = $value[1];
              $country_map[$i] = $temp;
          }
      }
      
      $response['country_map'] = htmlspecialchars(json_encode($country_map), ENT_QUOTES, 'UTF-8');
      // ************************* end of country map ***********************



      // ***************************** top 10 country ********************
      $metrics = 'views';
      $dimension = 'country';
      $sort = '-views';
      $filter = "video=={$video_id}";
      $max_result = 10;
      $top_ten_country_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $top_ten_country_info = (array)$top_ten_country_info;
      $top_ten_country_chart_data = array();

      $top_ten_country_table = "<table class='table table-bordered table-hover table-striped'><tr><th>Sl</th><th>Country</th><th>Views</th></tr>";
      if(!empty($top_ten_country_info['rows']))
      {
          $i = 0;
          $total_views = 0;
          foreach($top_ten_country_info['rows'] as $value)
          {
              $i++;
              $country = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
              $top_ten_country_table .= "<tr><td>".$i."</td><td>".$country."</td><td>".$value[1]."</td></tr>";
              $total_views = $total_views+$value[1];
          }
          $top_ten_country_table .= "</table>";


          $color_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
          $i=0;
          $color_count=0;
          foreach($top_ten_country_info['rows'] as $value)
          {
              $top_ten_country_chart_data[$i]['value'] = $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;
              $top_ten_country_chart_data[$i]['color'] = $color_array[$color_count];
              $top_ten_country_chart_data[$i]['highlight'] = $color_array[$color_count];
              $top_ten_country_chart_data[$i]['label'] = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
              $i++;
              $color_count++;
              if($color_count>=count($color_array)) $color_count=0;
          }
      }
      $data['top_ten_country_table'] = $top_ten_country_table;
      $response['top_ten_country_chart_data'] = json_encode($top_ten_country_chart_data);
      // ************************* end of top 10 country ***********************



      // ***************************** gender percentage ********************
      $metrics = 'viewerPercentage';
      $dimension = 'gender';
      $sort = '';
      $filter = "video=={$video_id}";
      $max_result = 10;
      $gender_percentage_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $gender_percentage_info = (array)$gender_percentage_info;
      $gender_percentage = array();

      $gender_percentage_list = "";
      if(!empty($gender_percentage_info['rows']))
      {
          $color_array = array("#E27263","#E5C77B");
          $i=0;
          foreach($gender_percentage_info['rows'] as $value)
          {
              $gender_percentage[$i]['value'] = $value[1];
              $gender_percentage[$i]['color'] = $color_array[$i];
              $gender_percentage[$i]['highlight'] = $color_array[$i];
              $gender_percentage[$i]['label'] = $value[0];

              $gender_percentage_list .= '<li><i class="fa fa-circle-o" style="color: '.$color_array[$i].';"></i> '.$value[0].' : '.$value[1].' %</li>';
              $i++;
          }
      }
      $response['gender_percentage_list'] = $gender_percentage_list;
      $response['gender_percentage'] = json_encode($gender_percentage);
      // ************************* gender percentage ***********************




      // ***************************** age group ********************
      $metrics = 'viewerPercentage';
      $dimension = 'ageGroup';
      $sort = '';
      $filter = "video=={$video_id}";
      $max_result = 10;
      $age_group_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $age_group_info = (array)$age_group_info;
      $age_group = array();

      $age_group_list = "";
      if(!empty($age_group_info['rows']))
      {
          $color_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
          $color_array = array_reverse($color_array);
          $i=0;
          $color_count=0;
          foreach($age_group_info['rows'] as $value)
          {
              $age_group[$i]['value'] = $value[1];
              $age_group[$i]['color'] = $color_array[$color_count];
              $age_group[$i]['highlight'] = $color_array[$color_count];
              $age_group[$i]['label'] = $value[0];

              $age_group_list .= '<li><i class="fa fa-circle-o" style="color: '.$color_array[$color_count].';"></i> '.$value[0].' : '.$value[1].' %</li>';
              $i++;
              $color_count++;
              if($color_count>=count($color_array)) $color_count=0;
          }
      }
      $response['age_group_list'] = $age_group_list;
      $response['age_group'] = json_encode($age_group);
      // ************************* age group ***********************



      // ***************************** views ********************
      $metrics = 'annotationImpressions';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $annotation_impression_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $annotation_impression_info = (array)$annotation_impression_info;

      $annotation_impression = array();
      $annotation_impression['max_val'] = 0;
      if(!empty($annotation_impression_info['rows']))
      {
          foreach($annotation_impression_info['rows'] as $value)
          {
              $annotation_impression_raw[$value[0]] = $value[1];
          }

          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($annotation_impression_raw[$day_count])){
                  $annotation_impression['date'][] = $day_count;
                  $annotation_impression['annotation_impressions'][] = $annotation_impression_raw[$day_count];

                  $annotation_impression['max_val'] = max($annotation_impression['max_val'], $annotation_impression_raw[$day_count]);
              }
              else
              {
                  $annotation_impression['date'][] = $day_count;
                  $annotation_impression['annotation_impressions'][] = 0;
              }
          }
      }
      $response['annotation_impressions'] = json_encode($annotation_impression);
      // ************************* end of views ***********************




      // ***************************** annotation close and click impressions ********************
      $metrics = 'annotationClosableImpressions';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $close_impression_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $close_impression_info = (array)$close_impression_info;


      $metrics = 'annotationClickableImpressions';
      $click_impression_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $click_impression_info = (array)$click_impression_info;

      $annotation_close_click_impressions = array();

      $annotation_close_click_impressions['max_val'] = 0;
      $annotation_close_click_impressions['max_val_close'] = 0;
      $annotation_close_click_impressions['max_val_click'] = 0;

      if(!empty($close_impression_info['rows']) && !empty($click_impression_info['rows']))
      {
          for($i=0;$i<count($close_impression_info['rows']);$i++)
          {
              $annotation_close_click_impressions_raw[$i]['date'] = $close_impression_info['rows'][$i][0];
              $annotation_close_click_impressions_raw[$i]['click_impression'] = $click_impression_info['rows'][$i][1];
              $annotation_close_click_impressions_raw[$i]['close_impression'] = $close_impression_info['rows'][$i][1];
          }

          foreach($annotation_close_click_impressions_raw as $value)
          {
              $annotation_close_click_impressions_raw_2[$value['date']]['click_impression'] = $value['click_impression'];
              $annotation_close_click_impressions_raw_2[$value['date']]['close_impression'] = $value['close_impression'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($annotation_close_click_impressions_raw_2[$day_count])){
                  $annotation_close_click_impressions['date'][] = $day_count;
                  $annotation_close_click_impressions['click_impression'][] = $annotation_close_click_impressions_raw_2[$day_count]['click_impression'];
                  $annotation_close_click_impressions['close_impression'][] = $annotation_close_click_impressions_raw_2[$day_count]['close_impression'];


                  $annotation_close_click_impressions['max_val_click'] = max($annotation_close_click_impressions['max_val_click'], $annotation_close_click_impressions_raw_2[$day_count]['click_impression']);
                  $annotation_close_click_impressions['max_val_close'] = max($annotation_close_click_impressions['max_val_close'], $annotation_close_click_impressions_raw_2[$day_count]['close_impression']);
              }
              else
              {
                  $annotation_close_click_impressions['date'][] = $day_count;
                  $annotation_close_click_impressions['click_impression'][] = 0;
                  $annotation_close_click_impressions['close_impression'][] = 0;
              }
          }
      }

      $annotation_close_click_impressions['max_val'] = max($annotation_close_click_impressions['max_val_click'], $annotation_close_click_impressions['max_val_close']);
      unset($annotation_close_click_impressions['max_val_click']);
      unset($annotation_close_click_impressions['max_val_close']);

      $response['annotation_close_click_impressions'] = json_encode($annotation_close_click_impressions);

      
      // ************************* end of annotation close and click impressions ***********************




      // ***************************** annotation close and click impressions ********************
      $metrics = 'annotationCloses';
      $dimension = 'day';
      $sort = 'day';
      $filter = "video=={$video_id}";
      $max_result = '';
      $annotation_close_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $annotation_close_info = (array)$annotation_close_info;


      $metrics = 'annotationClicks';
      $annotation_click_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $annotation_click_info = (array)$annotation_click_info;

      $annotation_clicks_closes = array();

      $annotation_clicks_closes['max_val'] = 0;
      $annotation_clicks_closes['max_val_click'] = 0;
      $annotation_clicks_closes['max_val_close'] = 0;

      if(!empty($annotation_close_info['rows']) && !empty($annotation_click_info['rows']))
      {
          for($i=0;$i<count($annotation_close_info['rows']);$i++)
          {
              $annotation_clicks_closes_raw[$i]['date'] = $annotation_close_info['rows'][$i][0];
              $annotation_clicks_closes_raw[$i]['annotation_click'] = $annotation_click_info['rows'][$i][1];
              $annotation_clicks_closes_raw[$i]['annotation_close'] = $annotation_close_info['rows'][$i][1];
          }

          foreach($annotation_clicks_closes_raw as $value)
          {
              $annotation_clicks_closes_raw_2[$value['date']]['annotation_click'] = $value['annotation_click'];
              $annotation_clicks_closes_raw_2[$value['date']]['annotation_close'] = $value['annotation_close'];
          }


          for($i=0;$i<=$no_of_days;$i++)
          {
              $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

              if(isset($annotation_clicks_closes_raw_2[$day_count])){
                  $annotation_clicks_closes['date'][] = $day_count;
                  $annotation_clicks_closes['annotation_click'][] = $annotation_clicks_closes_raw_2[$day_count]['annotation_click'];
                  $annotation_clicks_closes['annotation_close'][] = $annotation_clicks_closes_raw_2[$day_count]['annotation_close'];

                  $annotation_clicks_closes['max_val_click'] = max($annotation_clicks_closes['max_val_click'], $annotation_clicks_closes_raw_2[$day_count]['annotation_click']);
                  $annotation_clicks_closes['max_val_close'] = max($annotation_clicks_closes['max_val_close'], $annotation_clicks_closes_raw_2[$day_count]['annotation_close']);
              }
              else
              {
                  $annotation_clicks_closes['date'][] = $day_count;
                  $annotation_clicks_closes['annotation_click'][] = 0;
                  $annotation_clicks_closes['annotation_close'][] = 0;
              }
          }
      }

      $annotation_clicks_closes['max_val'] = max($annotation_clicks_closes['max_val_click'], $annotation_clicks_closes['max_val_close']);
      unset($annotation_clicks_closes['max_val_click']);
      unset($annotation_clicks_closes['max_val_close']);

      $response['annotation_clicks_closes'] = json_encode($annotation_clicks_closes);

      
      // ************************* end of annotation close and click impressions ***********************




      // ***************************** top 10 country ********************
      $metrics = 'views';
      $dimension = 'deviceType';
      $sort = '';
      $filter = "video=={$video_id}";
      $max_result = '';
      $device_type_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $device_type_info = (array)$device_type_info;
      $device_type_chart_data = array();

      
      $device_type_table = array();
      if(!empty($device_type_info['rows']))
      {
          $i = 0;
          $total_views = 0;
          foreach($device_type_info['rows'] as $value)
          {
              $i++;
              $device = ucfirst($value[0]);
              $device_type_table[] = array('device' => $device, 'value' => $value[1]);
              $total_views = $total_views+$value[1];
          }


          $color_array = array("#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
          $i=0;
          $color_count=0;
          foreach($device_type_info['rows'] as $value)
          {
              $device_type_chart_data['value'][] = $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;
              $device_type_chart_data['color'][] = $color_array[$color_count];
              $device_type_chart_data['highlight'][] = $color_array[$color_count];
              $device_type_chart_data['label'][] = ucfirst($value[0]);

              $device_type_table[$i]['percentage'] = $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;
              $i++;
              $color_count++;
              if($color_count>=count($color_array)) $color_count=0;
          }
      }
      $data['device_type_table'] = $device_type_table;
      $response['device_type_chart_data'] = json_encode($device_type_chart_data);
      // ************************* end of top 10 country ***********************




      // ***************************** top 10 country ********************
      $metrics = 'views';
      $dimension = 'operatingSystem';
      $sort = '';
      $filter = "video=={$video_id}";
      $max_result = '';
      $operating_system_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $operating_system_info = (array)$operating_system_info;
      $operating_system_chart_data = array();

      $operating_system_table = array();
      if(!empty($operating_system_info['rows']))
      {
          $i = 0;
          $total_views = 0;
          foreach($operating_system_info['rows'] as $value)
          {
              $i++;
              $device = ucfirst($value[0]);
              $operating_system_table[] = array('operatingSystem' => $device, 'value' => $value[1]);
              $total_views = $total_views+$value[1];
          }


          $color_array = array("#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
          $color_array = array_reverse($color_array);
          $color_count=0;
          $i=0;
          foreach($operating_system_info['rows'] as $value)
          {
              $operating_system_chart_data['value'][] = $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;
              $operating_system_chart_data['color'][] = $color_array[$color_count];
              $operating_system_chart_data['highlight'][] = $color_array[$color_count];
              $operating_system_chart_data['label'][] = ucfirst($value[0]);
              $operating_system_table[$i]['percentage'] = $total_views>0 ? number_format($value[1]*100/$total_views,2) : 0;
              $i++;
              $color_count++;
              if($color_count>=count($color_array)) $color_count=0;
          }
      }
      $data['operating_system_table'] = $operating_system_table;
      $response['operating_system_chart_data'] = json_encode($operating_system_chart_data);
      // ************************* end of top 10 country ***********************


      // ***************************** shares ********************
      $metrics = 'audienceWatchRatio';
      $dimension = 'elapsedVideoTimeRatio';
      $sort = '';
      $filter = "video=={$video_id}";
      $max_result = '';
      $retention_info = $this->youtube_library->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
      $retention_info = (array)$retention_info;

      $retention = array();
      $retention['max_val'] = 0;
      if(!empty($retention_info['rows']))
      {
          $i = 0;
          $j = 0;
          foreach($retention_info['rows'] as $value)
          {
              $j=$i+1;
              $retention['video_length'][] = $j." % ";
              $retention['audience'][] = $value[1];
              $retention['max_val'] = max($retention['max_val'], $value[1]);
              $i++;
          }

      }
      $response['retention'] = json_encode($retention);
      // ************************* end of shares ***********************
      
      $this->session->unset_userdata('individual_video_access_token');
      $this->session->unset_userdata('individual_video_refresh_token');


      return array('report_data' => $response, 'tables' => $data, 'start_date' => $start_date, 'end_date' => $end_date);
   }

   /* analytics ends */
}