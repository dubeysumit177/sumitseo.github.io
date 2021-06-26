<?php

require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/

class Social_apps extends Home
{
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');      

        $function_name=$this->uri->segment(2);
        $youtube_app_action_array = array();

        if(!in_array($function_name, $youtube_app_action_array)) 
        {
            if ($this->session->userdata('user_type')== "Member" && $this->config->item("backup_mode")==0)
            redirect('home/login', 'location');        
        }     

        if ($this->session->userdata('user_type') != 'Admin')
        redirect('home/login_page', 'location');
        
        $this->load->helper('form');
        $this->load->library('upload');
        
        $this->upload_path = realpath(APPPATH . '../upload');
        set_time_limit(0);

        $this->important_feature();
        $this->periodic_check();

    }


    public function index()
    {
        $this->settings();
    }


    public function settings()
    {

        $data['page_title'] = $this->lang->line('Social Apps');

        $data['body'] = 'admin/social_apps/settings';
        $data['title'] = $this->lang->line('Social Apps');

        $this->_viewcontroller($data);
    }


    public function google_settings()
    {


        $google_settings = $this->basic->get_data('social_app_google_config');

        if (!isset($google_settings[0])) $google_settings = array();
        else $google_settings = $google_settings[0];

        $data['google_settings'] = $google_settings;
        $data['page_title'] = $this->lang->line('Google App Settings');
        $data['title'] = $this->lang->line('Google App Settings');
        $data['body'] = 'admin/social_apps/google_settings';

        $this->_viewcontroller($data);
    }


    public function google_settings_data()
    {
        $this->ajax_check();

        $search_value = $_POST['search']['value'];
        $display_columns = array("#",'id','app_name','status','api_key','google_client_id', 'google_client_secret');
        $search_columns = array( 'app_name','api_key','google_client_id', 'google_client_secret');

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

        $this->db->where('user_id', $this->user_id);

        $table="social_app_google_config";
        $info=$this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');

        $this->db->where('user_id', $this->user_id);
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        $i=0;
        if($this->is_demo=='1')
        foreach ($info as $key => $value) 
        {                
            $info[$i]["api_key"] = "**************";
            $info[$i]["google_client_id"] = "****************************";
            $info[$i]["google_client_secret"] = "****************************";
            $i++;
        }



        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start);
        
        echo json_encode($data);
    }



    public function google_settings_action()
    {

        $this->ajax_check();
        if($this->is_demo == '1')
        {
            echo json_encode(array('type'=>'error','message'=>'You do not have access to perform this action in this demo.'));
            exit();
        }

        $submit_type = $this->input->post('submit_type', true);

        $app_name = strip_tags($this->input->post('app_name', true));
        $api_key = strip_tags(trim($this->input->post('api_key', true)));
        $google_client_id = strip_tags(trim($this->input->post('google_client_id', true)));
        $google_client_secret = strip_tags(trim($this->input->post('google_client_secret', true)));
        $status = $this->input->post('status', true);

        $response = array();

        if ($app_name == '') {

            $response['type'] = 'error';

            if ($app_name == '') $response['message'] = $this->lang->line('App Name can not be empty');

            echo json_encode($response);
            exit;
        }

        $data['app_name'] = $app_name;
        $data['user_id'] = $this->user_id;

        if ($this->session->userdata("user_type") == "Admin") {
            $data['is_admin_app'] = '1';
        }

        if ($submit_type == 'add') {

            if ($api_key != '') $data['api_key'] = $api_key;
            if ($google_client_id != '') $data['google_client_id'] = $google_client_id;
            if ($google_client_secret != '') $data['google_client_secret'] = $google_client_secret;
        }
        else {

            $data['api_key'] = $api_key;
            $data['google_client_id'] = $google_client_id;
            $data['google_client_secret'] = $google_client_secret;
        }

        if ($status == '') $data['status'] = '0';
        else $data['status'] = '1';

        if ($submit_type == 'add') {

            $this->basic->insert_data('social_app_google_config', $data);

            if ($this->db->affected_rows() > 0) {

                $inserted_id = $this->db->insert_id();

                // $this->basic->update_data('users', array('social_app_google_config_id' => '0'), array("social_app_google_config_id" => $inserted_id));

                $response['type'] = 'success';
                $response['message'] = $this->lang->line('App has been added successfully.');
            }
            else {

                $response['type'] = 'error';
                $response['message'] = $this->lang->line('Something went wrong.');
            }

            echo json_encode($response);
        }
        else if ($submit_type == 'edit') {

            $table_id = $this->input->post('table_id', true);
            $this->basic->update_data('social_app_google_config', array('id' => $table_id), $data);
            echo json_encode(array('type' => 'success', 'message' => $this->lang->line('App has been updated successfully.')));
        }

    }



    public function change_google_app_state()
    {
        $this->ajax_check();
        if($this->is_demo == '1')
        {
            echo json_encode(array('type'=>'0','message'=>'You do not have access to perform this action in this demo.'));
            exit();
        }

        $table_id = $this->input->post('table_id', true);

        $current_state = $this->basic->get_data('social_app_google_config', array('where' => array('id' => $table_id)), array('status'));
        $current_state = $current_state[0]['status'];

        if ($current_state == '0') $next_state = '1';
        if ($current_state == '1') $next_state = '0';


        $this->basic->update_data('social_app_google_config', array('id' => $table_id), array('status' => $next_state));

        echo json_encode(array('type' => '1', 'message' => $this->lang->line("App status has been changed successfully.")));
    }


    public function delete_google_app()
    {
        $this->ajax_check();
        if($this->is_demo == '1')
        {
            echo json_encode(array('type'=>'0','message'=>'You do not have access to perform this action in this demo.'));
            exit();
        }

        $table_id = $this->input->post('table_id', true);

       
        /* get user's channel lists & table ids */
        $channel_table_ids = array();
       
        $this->db->where_in('social_app_google_config_table_id', $table_id);
        $user_channel_list = $this->basic->get_data('youtube_channel_info', '', array('id','user_id'));
           
        foreach ($user_channel_list as $list){
            $this->delete_single_social_account('youtube', $list['id'],$list['user_id']);
        }        

        $this->basic->delete_data('social_app_google_config', array('id' => $table_id));

        if ($this->db->affected_rows() > 0)
            echo json_encode(array('type' => '1', 'message' => $this->lang->line("App has been deleted successfully.")));
        else
            echo json_encode(array('type' => '0', 'message' => $this->lang->line("Something went wrong.")));
    }




    public function facebook_settings()
    {
        
        $facebook_settings = $this->basic->get_data('social_app_facebook_config');

        if (!isset($facebook_settings[0])) $facebook_settings = array();
        else $facebook_settings = $facebook_settings[0];

        $data['facebook_settings'] = $facebook_settings;
        $data['token_validity'] = "";
        $data['page_title'] = $this->lang->line('Facebook App Settings');
        $data['title'] = $this->lang->line('Facebook App Settings');
        $data['body'] = 'admin/social_apps/facebook_settings';

        $this->_viewcontroller($data);
    }



    public function facebook_settings_data()
    {
        $this->ajax_check();

        $search_value = $_POST['search']['value'];
        $display_columns = array("#",'id','app_name','app_id','app_secret','status');
        $search_columns = array( 'app_name','app_id','app_secret');

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

        $table="social_app_facebook_config";
        $info=$this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        $i=0;
        if($this->is_demo=='1')
        foreach ($info as $key => $value) 
        {                
            $info[$i]["app_id"] = "**************";
            $info[$i]["app_secret"] = "****************************";
            $i++;
        }


        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start);
        
        echo json_encode($data);
    }




    public function facebook_settings_action()
    {
   
        $this->ajax_check();

        if($this->is_demo == '1')
        {
            echo json_encode(array('type'=>'error','message'=>'You do not have access to perform this action in this demo.'));
            exit();
        }

        $submit_type = $this->input->post('submit_type', true);

        $app_name = strip_tags($this->input->post('app_name', true));
        $app_id = strip_tags(trim($this->input->post('app_id', true)));
        $app_secret = strip_tags(trim($this->input->post('app_secret', true)));
        $status = $this->input->post('status', true);

        $response = array();

        if ($app_name == '' || $app_id == '' || $app_secret == '') {

            $response['type'] = 'error';

            if ($app_name == '') $response['message'] = $this->lang->line('App Name can not be empty');
            if ($app_id == '') $response['message'] = $this->lang->line('App ID can not be empty');
            if ($app_secret == '') $response['message'] = $this->lang->line('App Secret can not be empty');

            echo json_encode($response);
            exit;
        }

        $data['app_name'] = $app_name;
        $data['app_id'] = $app_id;
        $data['app_secret'] = $app_secret;

        if ($submit_type == 'add') {

            if ($status != '') {

                $this->basic->update_data('social_app_facebook_config', '', array('status' => '0'));
                $data['status'] = $status;
            }

            $this->basic->insert_data('social_app_facebook_config', $data);

            if ($this->db->affected_rows() > 0) {

                $response['type'] = 'success';
                $response['message'] = $this->lang->line('App has been added successfully.');
            }
            else {

                $response['type'] = 'error';
                $response['message'] = $this->lang->line('Something went wrong.');
            }

            echo json_encode($response);
        }
        else if ($submit_type == 'edit') {

            if ($status != '') {

                $this->basic->update_data('social_app_facebook_config', '', array('status' => '0'));
                $data['status'] = $status;
            }

            $table_id = $this->input->post('table_id', true);
            $this->basic->update_data('social_app_facebook_config', array('id' => $table_id), $data);
            echo json_encode(array('type' => 'success', 'message' => $this->lang->line('App has been updated successfully.')));
        }

    }


    public function change_facebook_app_state()
    {
        $this->ajax_check();
        if($this->is_demo == '1')
        {
            echo json_encode(array('type'=>'0','message'=>'You do not have access to perform this action in this demo.'));
            exit();
        }

        $table_id = $this->input->post('table_id', true);

        $current_state = $this->basic->get_data('social_app_facebook_config', array('where' => array('id' => $table_id)), array('status'));
        $current_state = $current_state[0]['status'];

        if ($current_state == '0') $next_state = '1';
        if ($current_state == '1') $next_state = '0';


        $this->basic->update_data('social_app_facebook_config', '', array('status' => '0'));

        $this->basic->update_data('social_app_facebook_config', array('id' => $table_id), array('status' => $next_state));

        echo json_encode(array('type' => '1', 'message' => $this->lang->line("App status has been changed successfully.")));

    }


    public function delete_facebook_app()
    {
        $this->ajax_check();
        if($this->is_demo == '1')
        {
            echo json_encode(array('type'=>'0','message'=>'You do not have access to perform this action in this demo.'));
            exit();
        }


        $table_id = $this->input->post('table_id', true);

        $this->basic->delete_data('social_app_facebook_config', array('id' => $table_id));

        if ($this->db->affected_rows() > 0)    echo json_encode(array('type' => '1', 'message' => $this->lang->line("App has been deleted successfully.")));
        else echo json_encode(array('type' => '0', 'message' => $this->lang->line("Something went wrong.")));
    }


}