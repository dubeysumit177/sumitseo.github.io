<?php

require_once("Home.php"); // including home controller

/**
* class admin_config
* @category controller
*/
class Responder extends Home
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


  public function template_manager()
  {
      $data['body'] = 'automation/menu_block';
      $data['page_title'] = $this->lang->line('Template Manager');
      $this->_viewcontroller($data);
  }


  public function auto_reply_create_campaign()
  {

      if (!isset($_POST)) exit;

      $response = array();
      $filter_word_message = array();

    
      $submit_type = $this->input->post('submit_type', true);
      $video_id = $this->input->post('video_id', true);
      $channel_id = $this->input->post('channel_id', true);
      $campaign_name = strip_tags($this->input->post('campaign_name', true));
      $save_as_template = $this->input->post('save_as_template', true);

      $use_saved_template = $this->input->post('use_saved_template', true);
      if ($use_saved_template == '1') {

          $saved_template_id = $this->input->post('saved_template_id', true);

          $template_info = $this->basic->get_data('auto_reply_template', array('where' => array('id' => $saved_template_id, 'user_id' => $this->user_id)));

          if (count($template_info) > 0) {
              
              $delete_offensive_comment = $template_info[0]['delete_offensive_comment'];
              $offensive_keywords = $template_info[0]['offensive_words'];
              $multiple_reply = $template_info[0]['multiple_reply'];
              $reply_type = $template_info[0]['reply_type'];
              $generic_message = $template_info[0]['generic_reply_message'];
              $filter_word_message = json_decode($template_info[0]['filter_reply_message'], true);
              $not_found_filter_message = $template_info[0]['filter_no_match_message'];
          } else {

              echo json_encode(array('status' => 'insufficient_data', 'field' => 'template_not_found'));
              exit;
          }
      } else {

          $delete_offensive_comment = $this->input->post('delete_offensive_comment', true);
          $offensive_keywords = $this->input->post('offensive_keywords', true);
          $multiple_reply = $this->input->post('multiple_reply', true);
          $reply_type = $this->input->post('reply_type', true);
          $generic_message = $this->input->post('generic_message', true);
          $filter_words = $this->input->post('filter_words', true);
          $filter_message = $this->input->post('filter_message', true);
          $not_found_filter_message = $this->input->post('not_found_filter_message', true);

          if ($campaign_name == "") {

              $response['status'] = 'insufficient_data';
              $response['field'] = 'campaign_name';
              echo json_encode($response);
              exit;
          }

          if ($reply_type == 'generic' && $generic_message == '') {

              $response['status'] = 'insufficient_data';
              $response['field'] = 'empty_generic_message';
              echo json_encode($response);
              exit;
          }

          $has_filter_word_empty = false;
          $has_filter_message_empty = false;

          if ($use_saved_template != '1') {
            
              if ($reply_type == 'filter') {

                  $i = 0;
                  foreach ($filter_words as $key => $value) {

                      if ($value == '') $has_filter_word_empty = true;
                      $filter_word_message[$i++]['word'] = $value;
                  }

                  $i = 0;
                  foreach ($filter_message as $key => $value) {

                      if ($value == '') $has_filter_message_empty = true;
                      $filter_word_message[$i++]['message'] = $value;
                  }

              }
          }

          if ($reply_type == 'filter' && ($has_filter_word_empty == true || $has_filter_message_empty == true)) {

              $response['status'] = 'insufficient_data';
              $response['field'] = 'filter_message_combination';
              echo json_encode($response);
              exit;
          }

          if ($reply_type == 'filter' && $not_found_filter_message == '') {

              $response['status'] = 'insufficient_data';
              $response['field'] = 'empty_not_found_filter_message';
              echo json_encode($response);
              exit;
          }
      }



      if (!isset($_POST['delete_offensive_comment']))
          $delete_offensive_comment = '0';

      if (!isset($_POST['multiple_reply']))
          $multiple_reply = '0';



      $data = array(
          'campaign_name' => $campaign_name,
          'user_id' => $this->user_id,
          'channel_id' => $channel_id,
          'channel_auto_id' =>$this->session->userdata('youtube_channel_info_table_id'),
          'video_id' => $video_id,
          'delete_offensive_comment' => $delete_offensive_comment,
          'offensive_words' => $offensive_keywords,
          'multiple_reply' => $multiple_reply,
          'reply_type' => $reply_type,
          'generic_reply_message' => $generic_message,
          'filter_reply_message' => json_encode($filter_word_message),
          'filter_no_match_message' => $not_found_filter_message,
          'created_time' => date('Y-m-d H:i:s'),
      );

      if ($submit_type == 'add') {

          // ************************************************//
          $status = $this->_check_usage($module_id = 1, $request = 1);
          if ($status == "2") {

              echo json_encode(array("status" => "failed", "message" => $this->lang->line("Sorry, your auto reply campaign creation monthly limit has been exceeded.")));
              exit();
          } else if ($status == "3") {

              echo json_encode(array("status" => "failed", "message" => $this->lang->line("Sorry, your auto reply campaign creation bulk limit has been exceeded.")));
              exit();
          }
          // ************************************************//
          
          $this->basic->insert_data('auto_reply_campaign', $data);
          $this->_insert_usage_log($module_id = 6, $request = 1);

          if ($use_saved_template != '1' && $save_as_template == '1') {

              $reply_template_data = array(

                  'user_id' => $this->user_id,
                  'name' => $campaign_name,
                  'delete_offensive_comment' => $delete_offensive_comment,
                  'offensive_words' => $offensive_keywords,
                  'multiple_reply' => $multiple_reply,
                  'reply_type' => $reply_type,
                  'generic_reply_message' => $generic_message,
                  'filter_reply_message' => json_encode($filter_word_message),
                  'filter_no_match_message' => $not_found_filter_message,
                  'created_at' => date('Y-m-d H:i:s'),
              );

              $this->basic->insert_data('auto_reply_template', $reply_template_data);
          }
      }
      else if ($submit_type == 'edit') {

          $update_id = $this->basic->get_data('auto_reply_campaign', array('where' => array('video_id' => $video_id)));
          if (count($update_id) > 0)
              $this->basic->update_data('auto_reply_campaign', array('id' => $update_id[0]['id']), $data);
      }

      if ($this->db->affected_rows() > 0) {

          $response['status'] = 'success';
          $response['type'] = $submit_type;
      }
      else {

          $response['status'] = 'failed';
          $response['message'] = $this->lang->line('Something went wrong.');
      }

      echo json_encode($response);
  }


  public function auto_reply_info_for_edit()
  {
      if (!isset($_POST)) exit;

      $video_id = $this->input->post('video_id', true);

      $reply_info = $this->basic->get_data('auto_reply_campaign', array('where' => array('video_id' => $video_id)));

      if (count($reply_info) > 0) {

          $reply_info = $reply_info[0];


          if ($this->user_id == $reply_info['user_id']) {

              $final_string = '';

              $final_string .= '         
                <div class="form-group">
                  <label for="campaign_name"> '.$this->lang->line("Campaign Name").' </label>
                  <input id="campaign_name" name="campaign_name" class="form-control" type="text" value="'.$reply_info['campaign_name'].'">
                </div>';

              $offensive_keywords_checked = ($reply_info['delete_offensive_comment'] == 0) ? '' : 'checked';
              $final_string .= '         
                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="delete_offensive_comment" > '.$this->lang->line('Do you want to delete offensive comments ?').'</label><br>
                      <label class="custom-switch mt-2">
                        <input id="delete_offensive_comment" type="checkbox" name="delete_offensive_comment" value="1" class="custom-switch-input"'.$offensive_keywords_checked.'>
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">'.$this->lang->line('Yes').'</span>
                      </label>
                    </div>
                  </div>
                </div>  ';

              $final_string .= '          
                <div class="form-group offensive_keywords_block">
                  <label for="offensive_keywords"> '.$this->lang->line("Offensive keywords (press enter to separate words)").'</label>
                  <textarea id="offensive_keywords" name="offensive_keywords" class="form-control inputtags">'.$reply_info['offensive_words'].'</textarea>
                </div> ';

              $generic_reply_type_checked = ($reply_info['reply_type'] == 'generic') ? 'checked' : '';
              $filter_reply_type_checked = ($reply_info['reply_type'] == 'filter') ? 'checked' : '';
              $final_string .= '          
                <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="" > '.$this->lang->line('Reply Type').'</label>
                      <div class="custom-switches-stacked mt-2">
                        <div class="row">   
                          <div class="col-6 col-md-4">
                            <label class="custom-switch">
                              <input type="radio" name="reply_type" value="generic" class="custom-switch-input" '.$generic_reply_type_checked.'>
                              <span class="custom-switch-indicator"></span>
                              <span class="custom-switch-description">'.$this->lang->line('Generic').'</span>
                            </label>
                          </div>                        
                          <div class="col-6 col-md-4">
                            <label class="custom-switch">
                              <input type="radio" name="reply_type" value="filter" class="custom-switch-input" '.$filter_reply_type_checked.'>
                              <span class="custom-switch-indicator"></span>
                              <span class="custom-switch-description">'.$this->lang->line('By Filter').'</span>
                            </label>
                          </div>
                        </div>                                  
                      </div>
                  </div> 
                </div>';

              $enable_multiple_reply_checked = ($reply_info['multiple_reply'] == '1') ? 'checked' : '';
              $final_string .='
                <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="multiple_reply" > '.$this->lang->line('Enable multiple reply').'</label><br>
                      <label class="custom-switch mt-2">
                        <input id="multiple_reply" type="checkbox" name="multiple_reply" value="1" class="custom-switch-input" '.$enable_multiple_reply_checked.'>
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">'.$this->lang->line('Enable').'</span>
                      </label>
                    </div>
                  </div>             
                </div>';

              $show_generic_block = ($reply_info['reply_type'] == 'generic') ? '' : 'd_none';
              $final_string .= '          
                <div class="form-group generic_message_block '.$show_generic_block.'">
                  <label for="generic_message"> '.$this->lang->line("Message for generic reply.").'</label>
                  <textarea id="generic_message" name="generic_message" class="form-control">'.$reply_info['generic_reply_message'].'</textarea>
                </div> ';

              
              $show_filter_block = ($reply_info['reply_type'] == 'filter') ? '' : 'd_none';

              $final_string .= '<div class="filter_message_block '.$show_filter_block.'">';

              $filter_message = json_decode($reply_info['filter_reply_message'], true);

              $i = 0;
              foreach ($filter_message as $key => $value) {
                  
                  if ($i % 2 == 0) $card = 'info';
                  else $card = 'primary';

                  $final_string .= '            
                    <div class="card card-'.$card.' single_card">
                      <div class="card-header">
                          <h4>'.$this->lang->line("Filter Reply").'</h4>
                          <div class="card-header-action">
                                <button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> '.$this->lang->line('Remove').'</button>
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="form-group">
                            <label for="filter_words"> '.$this->lang->line("Filter Word").' </label>
                            <input name="filter_words[]"  class="form-control filter_word_input" type="text" value="'.$value['word'].'">
                          </div>  

                          <div class="form-group">
                            <label for="filter_message"> '.$this->lang->line("Message for filter").'</label>
                            <textarea name="filter_message[]" class="form-control">'.$value['message'].'</textarea>
                          </div> 
                      </div>
                    </div>';
                  $i++;
              }

              if (count($filter_message) == 0) {

                  $final_string .= '
                          <div class="card card-info single_card">
                              <div class="card-header">
                                  <h4>'. $this->lang->line("Filter Reply") .'</h4>
                                  <div class="card-header-action">
                                        <button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> '. $this->lang->line("Remove") .'</button>
                                  </div>
                              </div>
                              <div class="card-body">
                                  <div class="form-group">
                                    <label for="filter_words"> '. $this->lang->line("Filter Word") .' </label>
                                    <input name="filter_words[]"  class="form-control filter_word_input" type="text">
                                  </div>  

                                  <div class="form-group">
                                    <label for="filter_message"> '. $this->lang->line("Message for filter") .'</label>
                                    <textarea name="filter_message[]" class="form-control"></textarea>
                                  </div> 
                              </div>
                          </div>
                  ';


              }


              $latest_block_count = (count($filter_message) == 0) ? '1' : count($filter_message);
              $latest_block_odd_even = (count($filter_message) % 2 == 0) ? 'even' : 'odd';

              $final_string .= '            
                <div class="clearfix add_more_button_block">
                  <input type="hidden" id="content_block" value="'.count($filter_message).'">
                  <input type="hidden" id="odd_or_even" value="'.$latest_block_odd_even.'">
                  <button class="btn btn-outline-primary float-right" id="add_more_filter_button"><i class="fa fa-plus-circle"></i> '.$this->lang->line('Add more filter').'</button>
                </div>';


              $offensive_keyword = explode(',', $reply_info['offensive_words']);


              $final_string .= '            
                <div class="form-group">
                  <label for="not_found_filter_message"> '.$this->lang->line("Message for no match").'</label>
                  <textarea id="not_found_filter_message" name="not_found_filter_message" class="form-control">'.$reply_info['filter_no_match_message'].'</textarea>
                </div> </div>

                <link rel="stylesheet" href="'.base_url('assets/css/system/auto_reply_edit.css').'">


                <script>

                  $(function() {
                    "use strict";
                    $(document).ready(function () {
                        $(".inputtags").tagsinput("add", "");
                        ';
                        foreach ($offensive_keyword as $key => $keyword) {
                            $final_string .= '$(".inputtags").tagsinput("add", "'. $keyword .'");';
                        }

                    $final_string .= '  
                    });
                  });
                </script>
                ';

              
              


              echo $final_string;
          }
          else {
            echo '<div class="alert alert-danger text-center"><i class="far fa-times-circle"></i> '.$this->lang->line('Something went wrong.').'</div>';
          }
      }

  }


  public function auto_reply_campaign($video_id = '')
  {

      if($this->session->userdata('user_type') != 'Admin' && !in_array(6,$this->module_access)) {
          redirect('404','refresh');
      }
      
      $data['video_id'] = $video_id;
      $data['page_title'] = $this->lang->line('Auto Reply Campaign');
      $data['body'] = 'automation/auto_reply_campaign';

      $this->_viewcontroller($data);
  }


  public function auto_reply_campaign_data()
  {
      $this->ajax_check();

      $search_value = $_POST['search']['value'];
      $display_columns = array("#",'id', 'campaign_name','video_id', 'title','status','last_processed_date','actions');
      $search_columns = array( 'campaign_name','video_id','auto_reply_campaign.channel_id');

      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
      $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
      $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
      $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'auto_reply_campaign.id';
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
      $where['where'] = array('auto_reply_campaign.user_id' => $this->user_id);

      $table="auto_reply_campaign";
      $join = array('youtube_channel_info'=>"youtube_channel_info.id=auto_reply_campaign.channel_auto_id,left");
      $info=$this->basic->get_data($table,$where,$select='auto_reply_campaign.*,youtube_channel_info.title',$join,$limit,$start,$order_by,$group_by='');
      $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
      $total_result=$total_rows_array[0]['total_rows'];

      $i = 0;
      foreach ($info as $key => $value) 
      {
        $info[$i]['last_processed_date'] = $value['last_processed_date']!="0000-00-00 00:00:00" ? date('jS F y, H:i', strtotime($value['last_processed_date'])) : "" ;
        $info[$i]['title'] = "<a target='_BLANK' href='https://youtube.com/channel/".$info[$i]['channel_id']."'>".$info[$i]['title']."</a>";

        $campain_status_icon = "";
        $campaign_status_text = "";

        if ($value['status'] == '2')
        {
          $campain_status_icon = '<i class="fas fa-pause margin_bottom_12px"></i>';
          $campaign_status_text = $this->lang->line("Restart");
        }
        else if ($value['status'] == '1')
        {
          $campain_status_icon = '<i class="fas fa-play margin_bottom_12px"></i>';
          $campaign_status_text = $this->lang->line("Force Re-active");
        }

        $string = '';

        if ($value['status'] != '0') $string .= '<button data-toggle="tooltip" title="'. $campaign_status_text .'" class="btn btn-circle btn-outline-primary campaign_state" campaign_id="'. $value["id"] .'"> '. $campain_status_icon .'</button>&nbsp;';

        $string .= '<button data-toggle="tooltip" title="'.$this->lang->line("Report").'" class="btn btn-circle btn-outline-primary report_details" campaign_id="'. $value["id"] .'"><i class="fas fa-eye margin_bottom_12px"></i></button>&nbsp;'; 

        $string .= '<button class="btn btn-circle btn-outline-warning edit_campaign" data-toggle="tooltip" title="'.$this->lang->line("Edit").'" campaign_id="'. $value["id"] .'" video_id="'. $value["video_id"] .'" channel_id="'. $value["channel_id"] .'"><i class="fas fa-edit margin_bottom_12px"></i></button>&nbsp;';

        $string .= '<button class="btn btn-circle btn-outline-danger delete_campaign" data-toggle="tooltip" title="'.$this->lang->line("Delete").'" campaign_id="'. $value["id"] .'"><i class="fa fa-trash margin_bottom_13px"></i></button>&nbsp;';

        $string .= '<a class="btn btn-circle btn-danger show_error" data-toggle="tooltip" href="#" title="'.$this->lang->line("Error").'" campaign_id="' . $value["id"] .'"><i class="fas fa-bug"></i></a>';

        $info[$i]['actions'] = ($i==0) ? $string.'<script src="'.base_url().'assets/js/system/tooltip_popover.js"></script>' : $string; 

        if($info[$i]['status'] == '2') $info[$i]['status'] =  "<span class='text-success'>".$this->lang->line("Completed")."</span>";
        else if($info[$i]['status'] == '0') $info[$i]['status'] =  "<span class='text-success'>".$this->lang->line("Active")."</span>";
        else $info[$i]['status'] =  "<span class='text-warning'>".$this->lang->line("Processing")."</span>";
        $i++;
      }

      $data['draw'] = (int)$_POST['draw'] + 1;
      $data['recordsTotal'] = $total_result;
      $data['recordsFiltered'] = $total_result;
      $data['data'] = convertDataTableResult($info, $display_columns ,$start);

      echo json_encode($data);
  }


  public function change_auto_reply_campaign_staus()
  {
      $this->ajax_check();

      $campaign_id = $this->input->post('campaign_id', true);

      $campaign_info = $this->basic->get_data('auto_reply_campaign', array('where' => array('id' => $campaign_id, 'user_id' => $this->user_id)), array('status'));

      if (count($campaign_info) > 0) {
          
          if ($campaign_info[0]['status'] != '0') $changed_status = '0';
          else 
          {
            echo json_encode(array("status" => "success", "message" => $this->lang->line("Campaign already in processing state.")));
            exit();
          }
          
          $this->basic->update_data('auto_reply_campaign', array('id' => $campaign_id, 'user_id' => $this->user_id), array('status' => $changed_status));

          echo json_encode(array("status" => "success", "message" => $this->lang->line("State has been changed successfully.")));
      }
      else echo json_encode(array("status" => "error", "message" => $this->lang->line("Something went wrong.")));  
      
  }


  public function view_campaign_report()
  {
      $this->ajax_check();
      $report_campagin_id = $this->input->post("report_campagin_id",true);



      $search_value = $_POST['search']['value'];
      $display_columns = array("#",'id','comment_author','is_offensive','status','replied_at','actions');
      $search_columns = array( 'comment_author');

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
      $where['where'] = array('auto_reply_campaign_table_id' => $report_campagin_id);

      $table="auto_reply_campaign_report";
      $info=$this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
      $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
      $total_result=$total_rows_array[0]['total_rows'];


      $i = 0;
      foreach ($info as $key => $value) 
      {
        $info[$i]['replied_at'] = $value['replied_at']!="0000-00-00 00:00:00" ? date('jS F y, H:i', strtotime($value['replied_at'])) : "";

        $completed = $value['is_offensive']=='1' ? $this->lang->line("Deleted") : $this->lang->line("Completed");

        if($info[$i]['status'] == '2') $info[$i]['status'] =  "<span class='text-success'>".$completed."</span>";
        else if($info[$i]['status'] == '0') $info[$i]['status'] =  "<span class='text-danger'>".$this->lang->line("Pending")."</span>";
        else $info[$i]['status'] =  "<span class='text-warning'>".$this->lang->line("Processing")."</span>";

        if($info[$i]['is_offensive'] == '1') $info[$i]['is_offensive'] =  "<span class='text-danger'>".$this->lang->line("Yes")."</span>";
        else $info[$i]['is_offensive'] =  "<span class='text-info'>".$this->lang->line("No")."</span>";

        $info[$i]['actions'] = 
        '
          <a class="btn btn-circle btn-outline-primary show_comment_text" data-toggle="tooltip" href="#" title="'.$this->lang->line("Comment Text").'" campaign_id="'.$value["id"].'"><i class="fas fa-comment"></i></a>
          <a class="btn btn-circle btn-outline-primary show_reply_text" data-toggle="tooltip" href="#" title="'.$this->lang->line("Reply Text").'" campaign_id="'.$value["id"].'"><i class="fas fa-reply"></i></a>
          <a class="btn btn-circle btn-danger show_error_report" data-toggle="tooltip" href="#" title="'.$this->lang->line("Error").'" campaign_id="'.$value["id"].'"><i class="fas fa-bug"></i></a>
        ';
        if($i==0) $info[$i]["actions"] .= '<script src="'.base_url().'assets/js/system/tooltip_popover.js"></script>';
        $i++;
      }   

      
      $data['draw'] = (int)$_POST['draw'] + 1;
      $data['recordsTotal'] = $total_result;
      $data['recordsFiltered'] = $total_result;
      $data['data'] = convertDataTableResult($info, $display_columns ,$start);

      echo json_encode($data);      
  }


  public function auto_like_comment_campaign_report()
  {
    $this->ajax_check();
    $auto_like_comment_campagin_id = $this->input->post("auto_like_comment_campagin_id",true);

    $is_exist = $this->basic->is_exist("auto_like_comment",array("id"=>$auto_like_comment_campagin_id,"user_id"=>$this->user_id));
    if(!$is_exist) exit();

    $search_value = $_POST['search']['value'];
    $display_columns = array("#", 'video', 'author','auto_like','status','actions','scheduled_at','published_at','comment_id');
    $search_columns = array('video', 'author','scheduled_at');

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 6;
    $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'scheduled_at';
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
    $where['where'] = array('campaign_id' => $auto_like_comment_campagin_id);

    $table="auto_like_comment_campaign_prepared";
    $info=$this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
    $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
    $total_result=$total_rows_array[0]['total_rows'];

    $i = 0;
    foreach ($info as $value)
    {        
      $info[$i]['video'] = '<a href="https://www.youtube.com/watch?v='. $value['video_id'] .'" target="_BLANK">'. $value['video_id'] .'</a>';
      
      if ($info[$i]['published_at'] == '0000-00-00 00:00:00') $info[$i]['published_at'] = '-';
      else $info[$i]['published_at'] = date('jS F y, H:i',strtotime($info[$i]['published_at']));
      $info[$i]['scheduled_at'] = date('jS F y, H:i',strtotime($info[$i]['scheduled_at']));
      
      $info[$i]['auto_like'] = ($info[$i]['auto_like'] == '1') ? $this->lang->line("Yes") : $this->lang->line("No");

      if($info[$i]['status'] == '2') $info[$i]['status'] =  "<span class='text-success'>".$this->lang->line("Completed")."</span>";
      else if($info[$i]['status'] == '0') $info[$i]['status'] =  "<span class='text-danger'>".$this->lang->line("Pending")."</span>";
      else $info[$i]['status'] =  "<span class='text-warning'>".$this->lang->line("Processing")."</span>";

      $info[$i]["actions"] = '
      <div class="width_90px">
        <a class="btn btn-circle btn-outline-primary view_comment" data-toggle="tooltip" href="#" title="'.$this->lang->line("Comment Text").'" campaign_id="'.$value["id"].'"><i class="fas fa-comment"></i></a>&nbsp;
        <a class="btn btn-circle btn-danger show_error_report" data-toggle="tooltip" href="#" title="'.$this->lang->line("Error").'" campaign_id="'.$value["id"].'"><i class="fas fa-bug"></i></a>
      </div>';

      if($i==0) $info[$i]["actions"] .= '<script src="'.base_url().'assets/js/system/tooltip_popover.js"></script>';

      $i++;
    }

    
    $data['draw'] = (int)$_POST['draw'] + 1;
    $data['recordsTotal'] = $total_result;
    $data['recordsFiltered'] = $total_result;
    $data['data'] = convertDataTableResult($info, $display_columns ,$start);


    echo json_encode($data);
  }


  public function auto_subscribe_unsubcribe_channel_report()
  {
    $this->ajax_check();
    $auto_subscribe_unsubscribe_campagin_id = $this->input->post("auto_subscribe_unsubscribe_campagin_id",true);

    $is_exist = $this->basic->is_exist("auto_channel_subscription",array("id"=>$auto_subscribe_unsubscribe_campagin_id,"user_id"=>$this->user_id));
    if(!$is_exist) exit();

    $search_value = $_POST['search']['value'];
    $display_columns = array("#", 'targeted_channel_id','status','actions','scheduled_at','subscribed_unsubscribed_at');
    $search_columns = array('targeted_channel_id','scheduled_at');

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
    $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'status';
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
    $where['where'] = array('campaign_id' => $auto_subscribe_unsubscribe_campagin_id);

    $table="auto_channel_subscription_prepared";
    $info=$this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
    $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
    $total_result=$total_rows_array[0]['total_rows'];

    $i = 0;
    foreach ($info as $value)
    {        
      $info[$i]['targeted_channel_id'] = '<a href="https://www.youtube.com/channel/'. $value['targeted_channel_id'] .'" target="_BLANK">'. $value['targeted_channel_id'] .'</a>';
      
      $info[$i]['scheduled_at'] = date('jS F y, H:i',strtotime($info[$i]['scheduled_at']));
      $subscribed_unsubscribed_action = '';
      if($value['subscription_status']=='1') 
      {
        $subscribed_unsubscribed_at = $value['subscribed_at'];
        if($info[$i]['status'] == '2') $subscribed_unsubscribed_action = '<a data-toggle="tooltip" class="btn btn-outline-danger btn-circle subscribe_unsubscribe" href="#" title="'.$this->lang->line("Unsubscribe").'" action="unsubscribe" campaign_id="'.$value['id'].'"><i class="fas fa-user-slash"></i></a>&nbsp;';
      }
      else 
      {
        $subscribed_unsubscribed_at = $value['unsubscribed_at'];
        if($info[$i]['status'] == '2') $subscribed_unsubscribed_action = '<a data-toggle="tooltip" class="btn btn-outline-success btn-circle  subscribe_unsubscribe" href="#" title="'.$this->lang->line("Subscribe").'" action="subscribe" campaign_id="'.$value['id'].'"><i class="fas fa-user-check"></i></a>&nbsp;';
      }

      if($subscribed_unsubscribed_at!='0000-00-00 00:00:00') $info[$i]['subscribed_unsubscribed_at'] = date('jS F y, H:i',strtotime($subscribed_unsubscribed_at));
      else $info[$i]['subscribed_unsubscribed_at'] = "-";

      if($info[$i]['status'] == '2') $info[$i]['status'] =  "<span class='text-success'>".$this->lang->line("Completed")."</span>";
      else if($info[$i]['status'] == '0') $info[$i]['status'] =  "<span class='text-danger'>".$this->lang->line("Pending")."</span>";
      else $info[$i]['status'] =  "<span class='text-warning'>".$this->lang->line("Processing")."</span>";


      $info[$i]["actions"] =
        $subscribed_unsubscribed_action.'
        <a class="btn btn-circle btn-danger show_error_report" data-toggle="tooltip" href="#" title="'.$this->lang->line("Error").'" campaign_id="'.$value["id"].'"><i class="fas fa-bug"></i></a>';
        if($i==0) $info[$i]["actions"] .= '<script src="'.base_url().'assets/js/system/tooltip_popover.js"></script>';

      $i++;
    }

    
    $data['draw'] = (int)$_POST['draw'] + 1;
    $data['recordsTotal'] = $total_result;
    $data['recordsFiltered'] = $total_result;
    $data['data'] = convertDataTableResult($info, $display_columns ,$start);


    echo json_encode($data);
  }
  

  public function delete_auto_reply_campaign()
  {
      $this->ajax_check();

      $campaign_id = $this->input->post('campaign_id', true);
      $campaign_info = $this->basic->get_data('auto_reply_campaign', array('where' => array('id' => $campaign_id)));

      if (count($campaign_info) > 0) 
      {
          $campaign_info = $campaign_info[0];
          $response = array();

          if ($this->user_id == $campaign_info['user_id'])
          {
              $this->basic->delete_data('auto_reply_campaign', array('id' => $campaign_id));             
              $this->basic->delete_data('auto_reply_campaign_report', array('auto_reply_campaign_table_id' => $campaign_id));             
              if ($campaign_info['status'] != '2')  $this->_delete_usage_log(6, 1);
                  
              $response['status'] = 'success';
              $response['message'] = $this->lang->line('Campaign has been deleted successfully.'); 
              echo json_encode($response);exit;
          }      
          else 
          {
                $response['status'] = 'failed';
                $response['message'] = $this->lang->line("You don't have access on this campaign."); 
                echo json_encode($response);exit;
          }
      }
      else 
      {
          $response['status'] = 'failed';
          $response['message'] = $this->lang->line("No campaign found."); 
          echo json_encode($response);exit;
      }
  }



  public function auto_comment_template()
  {
      if($this->session->userdata('user_type') != 'Admin' && !in_array(12,$this->module_access)) {
          redirect('404','refresh');
      }

      $per_page = 10;
      $search_value = "";


      // set per_page and search_value from user_submission
      if (isset($_POST['rows_number']) || isset($_POST['search_value'])) {

          $per_page = $this->input->post('rows_number', true);
          $search_value = $this->input->post('search_value', true);

          $this->session->set_userdata('auto_comment_templete_per_page', $per_page);
          $this->session->set_userdata('auto_comment_templete_search_value', $search_value);
      }


      // set session so that pagination can get proper per_page & search_value
      if ($this->session->userdata('auto_comment_templete_per_page')) 
          $per_page = $this->session->userdata('auto_comment_templete_per_page');

      if ($this->session->userdata('auto_comment_templete_search_value')) 
          $search_value = $this->session->userdata('auto_comment_templete_search_value');


      $total_templetes = $this->basic->get_data('auto_comment_templete', array('where' => array('user_id' => $this->user_id, 'campaign_name LIKE' => '%'.$search_value.'%')));


      if ($per_page == 'all')
          $per_page = count($total_templetes);

      /* set cinfiguration for pagination */
      $config = array(

          'uri_segment' => 3,
          'base_url' => base_url('responder/auto_comment_template/'),
          'total_rows' => count($total_templetes),
          'per_page' => $per_page,

          'full_tag_open' => '<ul class="pagination">',
          'full_tag_close' => '</ul>',

          'first_link' => $this->lang->line('First Page'),
          'first_tag_open' => '<li class="page-item">',
          'first_tag_close' => '</li>',

          'last_link' => $this->lang->line('Last Page'),
          'last_tag_open' => '<li class="page-item">',
          'last_tag_close' => '</li>',

          'next_link' => $this->lang->line('Next'),
          'next_tag_open' => '<li class="page-item">',
          'next_tag_close' => '</li>',

          'prev_link' => $this->lang->line('Previous'),
          'prev_tag_open' => '<li class="page-item">',
          'prev_tag_close' => '</li>',

          'cur_tag_open' => '<li class="page-item active"><a class="page-link">',
          'cur_tag_close' => '</a></li>',

          'num_tag_open' => '<li class="page-item">',
          'num_tag_close' => '</li>',
          'attributes' => array('class' => 'page-link')
      );
      $this->pagination->initialize($config);
      $page_links = $this->pagination->create_links();


      $start = $this->uri->segment(3);
      $limit = $config['per_page'];

      $templetes = $this->basic->get_data('auto_comment_templete', array('where' => array('user_id' => $this->user_id, 'campaign_name LIKE' => '%'.$search_value.'%')), '', '', $limit, $start);

      $data['templetes'] = $templetes;
      $data['page_links'] = $page_links;
      $data['per_page'] = ($per_page == count($total_templetes)) ? 'all' : $per_page;
      $data['search_value'] = $search_value;
      $data['body'] = "automation/auto_comment_templete";


      $this->_viewcontroller($data);


  }


  public function create_auto_comment_template()
  {

      $submit_type = $this->input->post('submit_type', true);
      $campaign_name = strip_tags($this->input->post('campaign_name', true));
      $comment_message = $this->input->post('comment_message', true);

      if (empty($comment_message[0])) {
          echo "message not found";
          exit;
      }


      if ($submit_type == 'add') {

          $data = array(
              'campaign_name' => $campaign_name,
              'user_id' => $this->user_id,
              'comment_text' => json_encode($comment_message),
              'created_at' => date('Y-m-d H:i:s')
          );

          $this->basic->insert_data('auto_comment_templete', $data);

          echo $this->db->affected_rows();
      }
      else if ($submit_type == 'edit') {

          $campaign_id = $this->input->post('campaign_id', true);

          $data = array(
              'campaign_name' => $campaign_name,
              'comment_text' => json_encode($comment_message)
          );

          $this->basic->update_data('auto_comment_templete', array('user_id' => $this->user_id, 'id' => $campaign_id), $data);
          echo $this->db->affected_rows();
      }
  }


  public function edit_auto_comment_template_data()
  {
      $this->ajax_check();

      $campaign_id = $this->input->post('campaign_id', true);

      $result = $this->basic->get_data('auto_comment_templete', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));

      if (isset($result[0])) {

          $final_string = '';
          $result = $result[0];
          $messages = json_decode($result['comment_text']);

          $final_string .= '         
          <form action="" method="post" id="comment_templete_form">   
            <input type="hidden" name="submit_type" value="edit" id="submit_type">
            <input type="hidden" name="campaign_id" value="'. $result['id'] .'" id="campaign_id_on_modal">

              <div class="form-group">
                <label for="campaign_name"> '. $this->lang->line("Campaign Name") .' </label>
                <input id="campaign_name" name="campaign_name" class="form-control" type="text" value="'. $result['campaign_name'] .'">
              </div><br>

              <div id="comments_section">';
              
            $i = 0;
            foreach ($messages as $key => $message) {
              
              if ($i % 2 == 0) $card_type = 'card-info';
              else $card_type = 'card-primary';
              $i++;
              
              $final_string .= '  
                  <div class="card '. $card_type .' single_card">
                    <div class="card-header">
                      <h4>'. $this->lang->line("Comment Message") .'</h4>
                        <div class="card-header-action">
                              <button class="btn btn-outline-secondary remove_div"><i class="fas fa-times"></i> '. $this->lang->line('Remove') .'</button>
                        </div>
                    </div>
                    <div class="card-body">
                              
                      <div class="form-group">
                        <label for="comment_message"> '. $this->lang->line("Message for comment") .'</label>
                        <textarea name="comment_message[]" class="form-control">'. $message .'</textarea>
                      </div> 

                    </div>
                  </div>';


            }


            if ($i % 2 == 0) $odd_or_even = 'even';
            else $odd_or_even = 'odd';


            $final_string .= '
                <div class="clearfix add_more_button_block">
                  <input type="hidden" id="content_block" value="'. $i .'">
                  <input type="hidden" id="odd_or_even" value="'. $odd_or_even .'">
                  <button class="btn btn-outline-primary float-right" id="add_more_message_button"><i class="fa fa-plus-circle"></i> '. $this->lang->line('Add more message') .'</button>
                </div>
              </div>
            </form>';

          echo $final_string;
      }
      else {
          echo "error";
      }
  }


  public function delete_auto_comment_template()
  {
      $this->ajax_check();

      $campaign_id = $this->input->post('campaign_id', true);

      $this->basic->delete_data('auto_comment_templete', array('user_id' => $this->user_id, 'id' => $campaign_id));

      if ($this->db->affected_rows() > 0)
          echo $this->db->affected_rows();
      else echo '0';
  }


  public function auto_reply_template()
  {
      $data['page_title'] = $this->lang->line("Auto Reply Template");
      $data['body'] = "automation/auto_reply_template";


      $this->_viewcontroller($data);
  }


  public function auto_reply_template_data()
  {
      $this->ajax_check();

      $search_value = $_POST['search']['value'];
      $display_columns = array("#",'id','name','delete_offensive_comment','reply_type','multiple_reply','created_at','action');
      $search_columns = array( 'name','created_at');

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


      $table="auto_reply_template";
      $info=$this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
      $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
      $total_result=$total_rows_array[0]['total_rows'];

      $i = 0;
      foreach ($info as $value) {

          $info[$i]['created_at'] = date('jS M y H:i', strtotime($value['created_at']));

          /* offecsive comments action */
          if ($info[$i]['delete_offensive_comment'] == '1') {
              $info[$i]['delete_offensive_comment'] = $this->lang->line("Delete");  
          } else {
              $info[$i]['delete_offensive_comment'] = $this->lang->line("Do nothing");
          } 

          /* reply type */
          if ($info[$i]['reply_type'] == 'generic') {
              $info[$i]['reply_type'] = $this->lang->line("Generic Reply");  
          } else {
              $info[$i]['reply_type'] = $this->lang->line("Filtered Reply");
          } 

          /* multiple reply action */
          if ($info[$i]['multiple_reply'] == '0') {
              $info[$i]['multiple_reply'] = $this->lang->line("Reply Once");  
          } else {
              $info[$i]['multiple_reply'] = $this->lang->line("Reply Multiple Times");
          } 

          $info[$i]['action'] = '
          <div class=" text-center">
            <a href="'. base_url('responder/edit_auto_reply_template/'. $value['id']) .'"data-toggle="tooltip"  class="btn btn-circle btn-outline-warning edit_campaign" title="Edit" ><i class="fas fa-edit margin_bottom_12px"></i>
            </a>
            &nbsp;
            <button class="btn btn-circle btn-outline-danger delete_campaign" data-toggle="tooltip" title="Delete" template_id="'. $value['id'] .'"><i class="fa fa-trash margin_bottom_13px"></i>
            </button>
            </div>';
            if($i==0) $info[$i]["action"] .= '<script src="'.base_url().'assets/js/system/tooltip_popover.js"></script>';
          
          $i++;
      }

      $data['draw'] = (int)$_POST['draw'] + 1;
      $data['recordsTotal'] = $total_result;
      $data['recordsFiltered'] = $total_result;
      $data['data'] = convertDataTableResult($info, $display_columns ,$start);
      
      echo json_encode($data);
  }


  public function create_auto_reply_template()
  {
      $data['page_title'] = $this->lang->line("Create Auto Reply Template");
      $data['body'] = "automation/create_auto_reply_template";


      $this->_viewcontroller($data);
  }


  public function edit_auto_reply_template($id)
  {

      /* get template data */
      $template_data = $this->basic->get_data('auto_reply_template', array('where' => array('id' => $id, 'user_id' => $this->user_id)));
      if (count($template_data) == 0) {
          redirect(base_url('responder/auto_reply_template'),'location');
      } else {
          $template_data = $template_data[0];
      }

      $data['template_data'] = $template_data;


      $data['page_title'] = $this->lang->line("Create Auto Reply Template");
      $data['body'] = "automation/edit_auto_reply_template";

      $this->_viewcontroller($data);
  }

  /* create & update */
  public function auto_reply_template_action()
  {
      $this->ajax_check();

      /* get data from the form */
      $name = strip_tags($this->input->post('name', true));
      $delete_offensive_comment = $this->input->post('delete_offensive_comment', true);
      $offensive_words = $this->input->post('offensive_keywords', true);
      $multiple_reply = $this->input->post('multiple_reply', true);
      $reply_type = $this->input->post('reply_type', true);
      $generic_reply_message = $this->input->post('generic_message', true);
      $filter_words = $this->input->post('filter_words', true);
      $filter_message = $this->input->post('filter_message', true);
      $filter_no_match_message = $this->input->post('not_found_filter_message', true);

      $action_type = $this->input->post('action_type', true);
      if ($action_type == 'edit') {
          $template_id = $this->input->post('template_id', true);
      }


      /* check and validate form error */
      $has_error = false;

      if ($name == '' || 
        ($delete_offensive_comment == '1' && $offensive_words == '') || 
        ($reply_type == 'generic' && $generic_reply_message == '') || 
        ($reply_type == 'filter' && $filter_no_match_message == '') || 
        ($reply_type == 'filter' && count($filter_words) == 0 && count($filter_message) == 0)) {

          $has_error = true;
          $error_message = $this->lang->line("Either name is empty or corresponding values are missing.");
      }

      if ($reply_type == 'filter') {
          
          $filter_words_count = 0;
          $filter_message_count = 0;

          /* check if there has any empty row */
          foreach ($filter_words as $words) {

              if ($words != '') {
                  $filter_words_count++;
              }
          }

          foreach ($filter_message as $message) {

              if ($message != '') {
                  $filter_message_count++;
              }
          }

          /* finally match them that if they are eqyal */
          if ($filter_words_count != $filter_message_count) {

              $has_error = true;
              $error_message = $this->lang->line("Filter word & message are not matched.");
          }
      }


      /* if has any error then send responce accordingly */
      if ($has_error) {
          echo json_encode(array('status' => 'error', 'message' => $error_message));
          exit;
      }


      /* if has passed error check insert on database and send response accordingly */
      $filter_db_store_message = array();
      if ($reply_type == 'filter') {
          
          /* prepare filter message for inserting on database */
          $filter_count = count($filter_words);

          for ($i=0; $i < $filter_count; $i++) { 
              array_push($filter_db_store_message, array('word' => $filter_words[$i], 'message' => $filter_message[$i]));
          }
      }

      if ($delete_offensive_comment == '') {
          $delete_offensive_comment = '0';
      }

      if ($multiple_reply == '') {
          $multiple_reply = '0';
      }

      $insert_data = array(
        'user_id' => $this->user_id,
        'name' => $name,
        'delete_offensive_comment' => $delete_offensive_comment,
        'offensive_words' => $offensive_words,
        'multiple_reply' => $multiple_reply,
        'reply_type' => $reply_type,
        'generic_reply_message' => $generic_reply_message,
        'filter_reply_message' => json_encode($filter_db_store_message),
        'filter_no_match_message' => $filter_no_match_message,
        'created_at' => date('Y-m-d H:i:s'),
      );

      if ($action_type == 'create') {
          $this->basic->insert_data('auto_reply_template', $insert_data);
          echo json_encode(array('status' => 'success', 'message' => $this->lang->line("Template created successfully")));
      } else {
          $this->basic->update_data('auto_reply_template', array('id' => $template_id), $insert_data);
          echo json_encode(array('status' => 'success', 'message' => $this->lang->line("Template updated successfully")));
      }
  }


  public function delete_auto_reply_template()
  {
      $this->ajax_check();

      $template_id = $this->input->post('template_id', true);

      /* check template info */
      $template_info = $this->basic->get_data('auto_reply_template', array('where' => array('id' => $template_id, 'user_id' => $this->user_id)));

      if (count($template_info) == 0) {
          echo json_encode(array('status' => 'error'));
      } else {

          $this->basic->delete_data('auto_reply_template', array('id' => $template_id, 'user_id' => $this->user_id));
          echo json_encode(array('status' => 'success'));
      }
  }



  public function auto_like_comment()
  {
      if($this->session->userdata('user_type') != 'Admin' && !in_array(12,$this->module_access)) {
          redirect('404','refresh');
      }

      $auto_comment_templetes = $this->basic->get_data('auto_comment_templete', array('where' => array('user_id' => $this->user_id)), array('id', 'campaign_name'));


      /* auto comment templete for dropdown */
      $auto_comment_templete_dropdown = array();
      $auto_comment_templete_dropdown[0] = $this->lang->line("Please select a template");

      foreach ($auto_comment_templetes as $key => $auto_comment_templete) 
          $auto_comment_templete_dropdown[$auto_comment_templete['id']] = $auto_comment_templete['campaign_name'];
 


      $channels = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id)), array('title', 'channel_id'));

      /* channel dropdown */
      $channel_dropdown = array();
      foreach ($channels as $key => $channel) 
          $channel_dropdown[$channel['channel_id']] = $channel['title'];
      array_unshift($channel_dropdown, $this->lang->line("Please select a channel"));

      $data['channel_dropdown'] = $channel_dropdown;
      $data['auto_comment_templete_dropdown'] = $auto_comment_templete_dropdown;
      $data['page_title'] = $this->lang->line("Auto Like Comment");
      $data['body'] = "automation/auto_like_comment";

      $this->_viewcontroller($data);
  }



  public function auto_like_comment_campaigns()
  {
      $this->ajax_check();

      $search_value = $_POST['search']['value'];
      $display_columns = array("#",'id','campaign_name','title','created_at', 'status', 'total_activity');
      $search_columns = array( 'campaign_name','auto_like_comment.channel_id');

      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
      $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
      $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
      $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'created_at';
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
      $where['where'] = array('auto_like_comment.user_id' => $this->user_id);

      $table="auto_like_comment";
      $join = array('youtube_channel_info'=>"youtube_channel_info.id=auto_like_comment.channel_auto_id,left");
      $info=$this->basic->get_data($table,$where,$select='auto_like_comment.*,title',$join,$limit,$start,$order_by,$group_by='');
      $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
      $total_result=$total_rows_array[0]['total_rows'];

      foreach ($info as $key => $value) {
          $info[$key]['created_at'] = date('jS M y H:i', strtotime($value['created_at']));
          $info[$key]['title'] = "<a href='https://youtube.com/channel/".$value['channel_id']."' target='_BLANK'>".$value['title']."</a>";
      }

      $data['draw'] = (int)$_POST['draw'] + 1;
      $data['recordsTotal'] = $total_result;
      $data['recordsFiltered'] = $total_result;
      $data['data'] = convertDataTableResult($info, $display_columns ,$start);

      echo json_encode($data);
  }


  public function create_auto_like_comment_campaign()
  {
      $this->ajax_check();

      $response = array();

      /* get inputs from form */
      $submit_type = $this->input->post('submit_type', true);
      $campaign_name = strip_tags($this->input->post('campaign_name', true));
      $user_channel_id = $this->input->post('user_channel_id', true);
      $auto_comment_template = $this->input->post('auto_comment_template', true);
      $search_type = $this->input->post('search_type', true);
      $keywords = '';
      $channels = '';
      $enable_auto_like = $this->input->post('enable_auto_like', true);
      $max_activity = $this->input->post('max_activity', true);
      $expire_type = $this->input->post('expire_type', true);
      $expire_date = '';
      $expire_activity = '';

      /* validate input fields */
      if (!isset($enable_auto_like))
          $enable_auto_like = '0';

      if ($search_type == 'keyword') 
          $keywords = $this->input->post('keywords', true);
      else if ($search_type == 'channel') 
          $channels = $this->input->post('channels', true);

      if ($expire_type == 'date')
          $expire_date = $this->input->post('expire_date', true);
      else if ($expire_type == 'no_of_activity')
          $expire_activity = $this->input->post('expire_activity', true);
      

      /* protest empty value and not acceptable values */
      if ($campaign_name == '' || $auto_comment_template == '0' || $user_channel_id == '0' || $max_activity == '' || !is_numeric($max_activity) || $search_type != '' || $expire_type != '') {

          $response['type'] = 'empty_field';
          $error_flag = true;

          if ($campaign_name == '')
              $response['field'] = $this->lang->line("Campaign Name can not be empty.");
          else if ($auto_comment_template == '0')
              $response['field'] = $this->lang->line("Please select an auto comment template.");
          else if ($user_channel_id == '0')
              $response['field'] = $this->lang->line("Please select a channel.");
          else if ($search_type == 'keyword' && $keywords == '')
              $response['field'] = $this->lang->line("Please write atleast one keyword.");
          else if ($search_type == 'channel' && $channels == '')
              $response['field'] = $this->lang->line("Please write atleast one channel.");
          else if ($max_activity == '')
              $response['field'] = $this->lang->line("Max activity can not be empty.");
          else if (!is_numeric($max_activity))
              $response['field'] = $this->lang->line("Max activity must have to be a number.");
          else if ($max_activity>50)
              $response['field'] = $this->lang->line("Max activity can be maximum 50.");  
          else if ($expire_type == 'date' && $expire_date == '')
              $response['field'] = $this->lang->line("Please select a date.");
          else if ($expire_type == 'no_of_activity' && $expire_activity == '')
              $response['field'] = $this->lang->line("Please set number of expire activity.");
          else if ($expire_type == 'no_of_activity' && !is_numeric($expire_activity))
              $response['field'] = $this->lang->line("Expire activity must have to be a number.");
          else
              $error_flag = false;

          if ($error_flag) {

              echo json_encode($response);
              exit;
          }
      }

      /* create the update or insert data field */
      $data = array(

          'user_id' => $this->user_id,
          'campaign_name' => $campaign_name,
          'auto_comment_template_id' => $auto_comment_template,
          'channel_id' => $user_channel_id,
          'channel_auto_id' => $this->get_channel_auto_id($user_channel_id),
          'keyword_or_channel' => $search_type,
          'keywords' => $keywords,
          'channels' => $channels,
          'auto_like' => $enable_auto_like,
          'max_activity_per_day' => $max_activity,
          'expire_type' => $expire_type,
          'expire_date' => $expire_date,
          'campaign_expire_max_activity' => $expire_activity,
          'created_at' => date('Y-m-d H:i:s'),
          'status' => '0'
      );

      /* insert new campaign / edit a campaign */
      if ($submit_type == 'add') {

          // ************************************************//
          $status = $this->_check_usage($module_id = 12, $request = 1);
          if ($status == "2") {

              echo json_encode(array("type" => "failed", "message" => $this->lang->line("Sorry, your auto like & comment campaign monthly limit has been exceeded.")));
              exit();
          } else if ($status == "3") {

              echo json_encode(array("type" => "failed", "message" => $this->lang->line("Sorry, your auto like & comment campaign bulk limit has been exceeded.")));
              exit();
          }
          // ************************************************//
          
          $this->basic->insert_data('auto_like_comment', $data);
          $this->_insert_usage_log($module_id = 12, $request = 1);

          if ($this->db->affected_rows() > 0) {

              $response['type'] = 'success';
              $response['requested'] = 'add';
          }
          else {
              $response['type'] = 'failed';
              $response['message'] = $this->lang->line("Something went wrong.");
          }
      }
      else if ($submit_type == 'edit') {

          $campaign_id = $this->input->post('campaign_id', true); 

          $check_campain_info = $this->basic->get_data('auto_like_comment', array('where' => array('id' => $campaign_id, 'user_id' => $this->user_id)));

          if (count($check_campain_info) > 0 ) {
              if ($expire_type == 'no_of_activity' && $check_campain_info[0]['total_comment'] >= $expire_activity)
                  unset($data['status']);
              else if ($expire_type =='date' && strtotime($check_campain_info[0]['expire_date']) > strtotime($expire_date))
                unset($data['status']);

              $this->basic->update_data('auto_like_comment', array('id' => $campaign_id), $data);
              $response['type'] = 'success';
              $response['requested'] = 'edit';
          }
          else
              $response['type'] = 'failed';
      }


      echo json_encode($response);
  }

  

  public function edit_auto_like_comment_campaign_data()
  {
      $this->ajax_check();

      $campaign_id = $this->input->post('campaign_id', true);

      $campaign_info = $this->basic->get_data('auto_like_comment', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));

      if (count($campaign_info) > 0) {

          $campaign_info['result_status'] = 'success';
          echo json_encode($campaign_info);
      }
      else {
          $response = array('result_status' => 'failed');
          echo json_encode($response);
      }
  }


  public function delete_auto_like_comment_campaign()
  {
      $this->ajax_check();

      $campaign_id = $this->input->post('campaign_id', true);

      $response = array();

      $result = $this->basic->get_data('auto_like_comment', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));

      if (count($result) > 0)
      {
        $this->basic->delete_data('auto_like_comment', array('id' => $campaign_id,'user_id' => $this->user_id));
        $this->basic->delete_data('auto_like_comment_campaign_prepared', array('campaign_id' => $campaign_id,'user_id' => $this->user_id));
        if ($result[0]['status'] != '2')$this->_delete_usage_log(12, 1);
      }

      
      $response['status'] = 1;
      $response['message'] = $this->lang->line('Campaign has been deleted successfully.');
    

      echo json_encode($response);
  }




  public function auto_channel_subscription()
  {
      if($this->session->userdata('user_type') != 'Admin' && !in_array(13,$this->module_access)) {
          redirect('404','refresh');
      }

      $channels = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id)), array('title', 'channel_id'));

      /* channel dropdown */
      $channel_dropdown = array();
      foreach ($channels as $key => $channel) 
          $channel_dropdown[$channel['channel_id']] = $channel['title'];
      array_unshift($channel_dropdown, $this->lang->line("Please select a channel"));
      
      $data['channel_dropdown'] = $channel_dropdown;
      $data['page_title'] = $this->lang->line('Auto Subscribe & Unsubscribe');
      $data['body'] = "automation/auto_subscription";

      $this->_viewcontroller($data);
  }


  public function auto_channel_subscription_campaigns()
  {
      $this->ajax_check();

      $search_value = $_POST['search']['value'];

      $display_columns = array("#",'id','campaign_name','title','created_at', 'status', 'total_activity');
      $search_columns = array( 'campaign_name','auto_channel_subscription.channel_id');

      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
      $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
      $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 4;
      $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'created_at';
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
      $where['where'] = array('auto_channel_subscription.user_id' => $this->user_id);
      
      $table="auto_channel_subscription";
      $join = array('youtube_channel_info'=>"youtube_channel_info.id=auto_channel_subscription.channel_auto_id,left");
      $info=$this->basic->get_data($table,$where,$select='auto_channel_subscription.*,title',$join,$limit,$start,$order_by,$group_by='');
      $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
      $total_result=$total_rows_array[0]['total_rows'];

      foreach ($info as $key => $value) {
         $info[$key]['created_at'] = date('jS M y H:i', strtotime($value['created_at']));
         $info[$key]['title'] = "<a href='https://youtube.com/channel/".$value['channel_id']."' target='_BLANK'>".$value['title']."</a>";
      }

      $data['draw'] = (int)$_POST['draw'] + 1;
      $data['recordsTotal'] = $total_result;
      $data['recordsFiltered'] = $total_result;
      $data['data'] = convertDataTableResult($info, $display_columns ,$start);

      echo json_encode($data);
  }



  public function create_auto_channel_subscription_campaign()
  {
      $this->ajax_check();

      $response = array();

     /* get inputs from form */
     $submit_type = $this->input->post('submit_type', true);
     $campaign_name = strip_tags($this->input->post('campaign_name', true));
     $user_channel_id = $this->input->post('user_channel_id', true);
     $search_type = $this->input->post('search_type', true);
     $keywords = $this->input->post('keywords', true);
     $date_range = $this->input->post('date_range', true);
     $max_activity = $this->input->post('max_activity', true);
     $expire_type = $this->input->post('expire_type', true);
     $expire_date = '';
     $expire_activity = '';


     /* validate input fields */
     if (!isset($enable_auto_unsubscribe))
         $enable_auto_unsubscribe = '0';

     if ($expire_type == 'date')
         $expire_date = $this->input->post('expire_date', true);
     else if ($expire_type == 'no_of_activity')
         $expire_activity = $this->input->post('expire_activity', true);
     

     /* protest empty value and not acceptable values */
     if ($campaign_name == '' || $user_channel_id == '0' || $max_activity == '' || !is_numeric($max_activity) || $expire_type != '') {

         $response['type'] = 'empty_field';
         $error_flag = true;

         if ($campaign_name == '')
             $response['field'] = $this->lang->line("Campaign Name can not be empty.");
         else if ($user_channel_id == '0')
             $response['field'] = $this->lang->line("Please select a channel.");
         else if ($keywords == '')
             $response['field'] = $this->lang->line("Please write atleast one keyword.");
         else if ($max_activity == '')
             $response['field'] = $this->lang->line("Max activity can not be empty.");
         else if (!is_numeric($max_activity))
             $response['field'] = $this->lang->line("Max activity must have to be a number.");   
         else if ($max_activity>50)
              $response['field'] = $this->lang->line("Max activity can be maximum 50.");        
         else if ($expire_type == 'date' && $expire_date == '')
             $response['field'] = $this->lang->line("Please select a date.");
         else if ($expire_type == 'no_of_activity' && $expire_activity == '')
             $response['field'] = $this->lang->line("Please set number of expire activity.");
         else if ($expire_type == 'no_of_activity' && !is_numeric($expire_activity))
             $response['field'] = $this->lang->line("Expire activity must have to be a number.");
         else
             $error_flag = false;

         if ($error_flag) {

             echo json_encode($response);
             exit;
         }
     }



     /* create the update or insert data field */
     $data = array(

         'user_id' => $this->user_id,
         'campaign_name' => $campaign_name,
         'channel_id' => $user_channel_id,
         'channel_auto_id' => $this->get_channel_auto_id($user_channel_id),
         'keywords' => $keywords,
         'date_range' => $date_range,
         'max_activity_per_day' => $max_activity,         
         'expire_type' => $expire_type,
         'expire_date' => $expire_date,
         'campaign_expire_max_activity' => $expire_activity,
         'created_at' => date('Y-m-d H:i:s'),
         'status' => '0'
     );


     /* insert new campaign / edit a campaign */
     if ($submit_type == 'add') {

         // ************************************************//
         $status = $this->_check_usage($module_id = 13, $request = 1);
         if ($status == "2") {

             echo json_encode(array("type" => "failed", "message" => $this->lang->line("Sorry, your auto subscription campaign monthly limit has been exceeded.")));
             exit();
         } else if ($status == "3") {

             echo json_encode(array("type" => "failed", "message" => $this->lang->line("Sorry, your auto subscription campaign bulk limit has been exceeded.")));
             exit();
         }
         // ************************************************//

         $this->basic->insert_data('auto_channel_subscription', $data);
         $this->_insert_usage_log($module_id = 13, $request = 1);

         if ($this->db->affected_rows() > 0) {

             $response['type'] = 'success';
             $response['requested'] = 'add';
         }
         else {
             $response['type'] = 'failed';
             $response['message'] = $this->lang->line("Something went wrong.");
         }
     }
     else if ($submit_type == 'edit') {

        $campaign_id = $this->input->post('campaign_id', true); 

        $check_campain_info = $this->basic->get_data('auto_channel_subscription', array('where' => array('id' => $campaign_id, 'user_id' => $this->user_id)));

        if (count($check_campain_info) > 0 ) {

            unset($data['todays_date']);
            unset($data['this_week']);
            unset($data['this_month']);

            if ($expire_type == 'no_of_activity' && $check_campain_info[0]['total_subscribed'] >= $expire_activity)
                unset($data['status']);
            else if ($expire_type =='date' && strtotime($check_campain_info[0]['expire_date']) > strtotime($expire_date))
              unset($data['status']);

            $this->basic->update_data('auto_channel_subscription', array('id' => $campaign_id), $data);
            $response['type'] = 'success';
            $response['requested'] = 'edit';
        }
        else
            $response['type'] = 'failed';
     }


     echo json_encode($response);

  }


  public function edit_auto_channel_subscription_campaign_data()
  {
      $this->ajax_check();

      $campaign_id = $this->input->post('campaign_id', true);

      $campaign_info = $this->basic->get_data('auto_channel_subscription', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));

      if (count($campaign_info) > 0) {

          $campaign_info['result_status'] = 'success';
          echo json_encode($campaign_info);
      }
      else {
          $response = array('result_status' => 'failed');
          echo json_encode($response);
      }
  }



  public function delete_auto_channel_subscription_campaign()
  {
      $this->ajax_check();

      $campaign_id = $this->input->post('campaign_id', true);

      $response = array();

      $result = $this->basic->get_data('auto_channel_subscription', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));

      if (count($result) > 0)
      {
        $this->basic->delete_data('auto_channel_subscription', array('id' => $campaign_id,'user_id' => $this->user_id));
        $this->basic->delete_data('auto_channel_subscription_prepared', array('campaign_id' => $campaign_id,'user_id' => $this->user_id));
        if($result[0]['status'] != '2') $this->_delete_usage_log(13, 1);
      }

          
      $response['status'] = 1;
      $response['message'] = $this->lang->line('Campaign has been deleted successfully.');     

      echo json_encode($response);
  }

  public function subscribe_unsubscribe_channel()
  {
      $this->ajax_check();

      /* get data from fron_end */
      $campaign_id = $this->input->post('campaign_id', true);
      $action = $this->input->post('action', true);
      
      $campaign_data = $this->basic->get_data('auto_channel_subscription_prepared', array('where' => array('user_id' => $this->user_id, 'id' => $campaign_id)));


      if (count($campaign_data) == 0) {
        
          echo json_encode(array('status' => 'error', 'message' => $this->lang->line("Something went wrong")));
          exit;
      }

      $targeted_channel_id = $campaign_data[0]["targeted_channel_id"];
      $subscribed_id = $campaign_data[0]["subscribed_id"];
      $params['youtube_channel_info_table_id'] = $campaign_data[0]['channel_auto_id'];
      $this->load->library('youtube_library',$params);

      $data_update = array();

      if($action=="subscribe")
      {
        $result = $this->youtube_library->subscribe_to_other_channel($targeted_channel_id);
        $data_update["subscribed_at"] = date("Y-m-d H:i:s");
        $data_update["subscribed_id"] = isset($result->id) ? $result->id : "";
        $data_update["subscription_status"] = "1";
        $success = $this->lang->line("Channel has been subscribed successfully.");
      }
      else
      {
        $result = $this->youtube_library->delete_channel_subscription($subscribed_id);
        $data_update["unsubscribed_at"] = date("Y-m-d H:i:s");
        $data_update["subscribed_id"] = "";
        $data_update["subscription_status"] = "0";
        $success = $this->lang->line("Channel has been unsubscribed successfully.");
      }

      if(isset($result['error']))
      {
        $error = isset($result['message']) ? $result['message'] : $this->lang->line("Something went wrong");
        $this->basic->update_data('auto_channel_subscription_prepared', array('id' => $campaign_id), array('error' => $error));
        echo json_encode(array('status' => 'error', 'message' => $error));        
      }
      else
      {
        $this->basic->update_data('auto_channel_subscription_prepared', array('id' => $campaign_id), $data_update);
        echo json_encode(array('status' => 'success', 'message' => $success));
      }
  }



}