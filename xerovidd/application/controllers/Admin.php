<?php

require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/

class Admin extends Home
{
   
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');        
        if ($this->session->userdata('user_type') != 'Admin')
        redirect('home/login_page', 'location');
        $this->important_feature();
        $this->periodic_check();

    }


    public function settings()
    {
        $this->_viewcontroller(array("body"=>"admin/settings/settings","page_title"=>$this->lang->line("settings")));
    }

    public function general_settings()
    {               
        $data['body'] = "admin/settings/general";
        $data['time_zone'] = $this->_time_zone_list();        
        $data['language_info'] = $this->_language_list();
        $data['page_title'] = $this->lang->line('General Settings');

        $this->_viewcontroller($data);
    }

    public function general_settings_action()
    {       
        $this->is_demo_check();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        if ($_POST) 
        {
            // validation
            $this->form_validation->set_rules('institute_name',       '<b>'.$this->lang->line("company name").'</b>',             'trim');
            $this->form_validation->set_rules('institute_address',    '<b>'.$this->lang->line("company address").'</b>',          'trim');
            $this->form_validation->set_rules('institute_email',      '<b>'.$this->lang->line("company email").'</b>',            'trim|required');
            $this->form_validation->set_rules('institute_mobile',     '<b>'.$this->lang->line("company phone/ mobile").'</b>',    'trim');
            $this->form_validation->set_rules('time_zone',            '<b>'.$this->lang->line("time zone").'</b>',                'trim');
            $this->form_validation->set_rules('slogan',               '<b>'.$this->lang->line("slogan").'</b>',                 'trim');

            $this->form_validation->set_rules('language',             '<b>'.$this->lang->line("language").'</b>',                 'trim');

            $this->form_validation->set_rules('product_name',         '<b>'.$this->lang->line("product name").'</b>',                 'trim');
            $this->form_validation->set_rules('product_short_name',   '<b>'.$this->lang->line("product short name").'</b>','trim');
            $this->form_validation->set_rules('master_password',   '<b>'.$this->lang->line("Master Password").'</b>',                 'trim');
            $this->form_validation->set_rules('email_sending_option',  '<b>'.$this->lang->line("Email sending option").'</b>','trim');
            $this->form_validation->set_rules('force_https',  '<b>'.$this->lang->line("Force HTTPS").'</b>','trim');
            $this->form_validation->set_rules('enable_support',  '<b>'.$this->lang->line("Enable Suppordesk").'</b>','trim');
            $this->form_validation->set_rules('enable_signup_form',  '<b>'.$this->lang->line("Enable Signup Form").'</b>','trim');

             $this->form_validation->set_rules('image_upload_limit',  '<b>'.$this->lang->line("Messenger Bot section image upload limit").'</b>','trim');
            $this->form_validation->set_rules('video_upload_limit',  '<b>'.$this->lang->line("Messenger Bot section video upload limit").'</b>','trim');
            $this->form_validation->set_rules('audio_upload_limit',  '<b>'.$this->lang->line("Messenger Bot section audio upload limit").'</b>','trim');
            $this->form_validation->set_rules('file_upload_limit',  '<b>'.$this->lang->line("Messenger Bot section file upload limit").'</b>','trim');

            $this->form_validation->set_rules('no_of_campaign_per_cron_job_comment_auto_reply',  '<b>'.$this->lang->line("No. of campaign per cron job").'</b>','trim');
            $this->form_validation->set_rules('no_of_reply_per_cron_job_comment_auto_reply',  '<b>'.$this->lang->line("No. of replies per cron job").'</b>','trim');
            $this->form_validation->set_rules('no_of_old_comment_to_reply_comment_auto_reply',  '<b>'.$this->lang->line("No. of old comments to be replied").'</b>','trim');
            $this->form_validation->set_rules('delay_between_reply_comment_auto_reply',  '<b>'.$this->lang->line("Delay between replies").'</b>','trim');

            $this->form_validation->set_rules('show_rank_tracking_feature',  '<b>'.$this->lang->line("Show rank tracking feature").'</b>','trim');

            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) 
            {
                return $this->general_settings();
            } 
            else 
            {
                // assign
                $institute_name=addslashes(strip_tags($this->input->post('institute_name', true)));
                $institute_address=addslashes(strip_tags($this->input->post('institute_address', true)));
                $institute_email=addslashes(strip_tags($this->input->post('institute_email', true)));
                $institute_mobile=addslashes(strip_tags($this->input->post('institute_mobile', true)));
                $time_zone=addslashes(strip_tags($this->input->post('time_zone', true)));
                $language=addslashes(strip_tags($this->input->post('language', true)));
                $slogan=addslashes(strip_tags($this->input->post('slogan', true)));
                $product_name=addslashes(strip_tags($this->input->post('product_name', true)));
                $product_short_name=addslashes(strip_tags($this->input->post('product_short_name', true)));

                $master_password=addslashes(strip_tags($this->input->post('master_password', true)));

                $email_sending_option=addslashes(strip_tags($this->input->post('email_sending_option', true)));
                $force_https=addslashes(strip_tags($this->input->post('force_https', true)));
                $enable_support=addslashes(strip_tags($this->input->post('enable_support', true)));
                $enable_signup_form=addslashes(strip_tags($this->input->post('enable_signup_form', true)));

                $image_upload_limit=addslashes(strip_tags($this->input->post('image_upload_limit', true)));
                $video_upload_limit=addslashes(strip_tags($this->input->post('video_upload_limit', true)));
                $audio_upload_limit=addslashes(strip_tags($this->input->post('audio_upload_limit', true)));
                $file_upload_limit=addslashes(strip_tags($this->input->post('file_upload_limit', true)));

                $no_of_campaign_per_cron_job_comment_auto_reply=addslashes(strip_tags($this->input->post('no_of_campaign_per_cron_job_comment_auto_reply', true)));
                $no_of_reply_per_cron_job_comment_auto_reply=addslashes(strip_tags($this->input->post('no_of_reply_per_cron_job_comment_auto_reply', true)));
                $no_of_old_comment_to_reply_comment_auto_reply=addslashes(strip_tags($this->input->post('no_of_old_comment_to_reply_comment_auto_reply', true)));
                $delay_between_reply_comment_auto_reply=addslashes(strip_tags($this->input->post('delay_between_reply_comment_auto_reply', true)));
                $show_rank_tracking_feature=addslashes(strip_tags($this->input->post('show_rank_tracking_feature', true)));

                $base_path=realpath(APPPATH . '../assets/img');

                if($force_https=='') $force_https='0';
                if($enable_signup_form=='') $enable_signup_form='0';
                if($enable_support=='') $enable_support='0';
                if($show_rank_tracking_feature=='') $show_rank_tracking_feature='0';

                $this->load->library('upload');

                if ($_FILES['logo']['size'] != 0) {
                    $photo = "logo.png";
                    $config = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '500',
                        'max_width' => '700',
                        'max_height' => '200'
                        );
                    $this->upload->initialize($config);
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('logo')) {
                        $this->session->set_userdata('logo_error', $this->upload->display_errors());
                        return $this->general_settings();
                    }
                }

                if ($_FILES['favicon']['size'] != 0) {
                    $photo = "favicon.png";
                    $config2 = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '50',
                        'max_width' => '100',
                        'max_height' => '100'
                        );
                    $this->upload->initialize($config2);
                    $this->load->library('upload', $config2);

                    if (!$this->upload->do_upload('favicon')) {
                        $this->session->set_userdata('favicon_error', $this->upload->display_errors());
                        return $this->general_settings();  
                    }
                }

                // writing application/config/my_config
                $app_my_config_data = "<?php ";
                $app_my_config_data.= "\n\$config['default_page_url'] = '".$this->config->item('default_page_url')."';\n";
                $app_my_config_data.= "\$config['product_name'] = '$product_name';\n";
                $app_my_config_data.= "\$config['product_short_name'] = '$product_short_name';\n";               
                $app_my_config_data.= "\$config['product_version'] = '".$this->config->item('product_version')."';\n";
                $app_my_config_data.= "\$config['slogan'] = '$slogan';\n\n";
                $app_my_config_data.= "\$config['institute_address1'] = '$institute_name';\n";
                $app_my_config_data.= "\$config['institute_address2'] = '$institute_address';\n";
                $app_my_config_data.= "\$config['institute_email'] = '$institute_email';\n";
                $app_my_config_data.= "\$config['institute_mobile'] = '$institute_mobile';\n\n";                
                $app_my_config_data.= "\$config['time_zone'] = '$time_zone';\n";                
                $app_my_config_data.= "\$config['language'] = '$language';\n";
                
                $app_my_config_data.= "\$config['email_sending_option'] = '".$email_sending_option."';\n";

                $app_my_config_data.= "\$config['force_https'] = '".$force_https."';\n";
                $app_my_config_data.= "\$config['enable_signup_form'] = '".$enable_signup_form."';\n";
                $app_my_config_data.= "\$config['enable_support'] = '".$enable_support."';\n\n";

                if($master_password=='******')
                $app_my_config_data.= "\$config['master_password'] = '".$this->config->item("master_password")."';\n\n";
                else if($master_password=='')
                $app_my_config_data.= "\$config['master_password'] = '';\n\n";
                else $app_my_config_data.= "\$config['master_password'] = '".md5($master_password)."';\n\n";
           

                $app_my_config_data.= "\$config['image_upload_limit'] = ".$image_upload_limit.";\n";
                $app_my_config_data.= "\$config['video_upload_limit'] = ".$video_upload_limit.";\n";
                $app_my_config_data.= "\$config['audio_upload_limit'] = ".$audio_upload_limit.";\n";
                $app_my_config_data.= "\$config['file_upload_limit'] = ".$file_upload_limit.";\n\n"; 

                $app_my_config_data.= "\$config['no_of_campaign_per_cron_job_comment_auto_reply'] = ".$no_of_campaign_per_cron_job_comment_auto_reply.";\n"; 
                $app_my_config_data.= "\$config['no_of_reply_per_cron_job_comment_auto_reply'] = ".$no_of_reply_per_cron_job_comment_auto_reply.";\n"; 
                $app_my_config_data.= "\$config['no_of_old_comment_to_reply_comment_auto_reply'] = ".$no_of_old_comment_to_reply_comment_auto_reply.";\n"; 
                $app_my_config_data.= "\$config['delay_between_reply_comment_auto_reply'] = ".$delay_between_reply_comment_auto_reply.";\n\n"; 


                $app_my_config_data.= "\$config['sess_use_database'] = FALSE;\n";
                $app_my_config_data.= "\$config['sess_table_name'] = 'ci_sessions';\n";                 
                $app_my_config_data.= "\$config['show_rank_tracking_feature'] = '".$show_rank_tracking_feature."';";

                file_put_contents(APPPATH.'config/my_config.php', $app_my_config_data, LOCK_EX); //writting  application/config/my_config
                                         
                $this->session->set_flashdata('success_message', 1);
                redirect('admin/general_settings', 'location');
            }
        }
    }

    public function frontend_settings()
    {
        $data['body'] = "admin/settings/frontend";
        $data['time_zone'] = $this->_time_zone_list();        
        $data['language_info'] = $this->_language_list();
        $data['page_title'] = $this->lang->line('Front-end Settings');
        $this->_viewcontroller($data);
    }


    public function frontend_settings_action()
    {
        $this->is_demo_check();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') { redirect('home/access_forbidden', 'location'); }

        if ($_POST) 
        {
            $post=$_POST;
            foreach ($post as $key => $value) 
            {
                $$key = addslashes(strip_tags($this->input->post($key,TRUE)));
            }

            if(!isset($display_landing_page) || $display_landing_page=='') $display_landing_page='0';
            if(!isset($display_review_block) || $display_review_block=='') $display_review_block='0';
            if(!isset($display_video_block) || $display_video_block=='') $display_video_block='0';

            //review section
            $customer_review = array();
            $total_item      = $this->config->item('customer_review');

            $review_string = "array".'('."\n";

            for($i = 1; $i <= count($total_item); $i++) {
                $j = $i-1;
                $var1 = "reviewer".$i;
                $var2 = "designation".$i;
                $var3 = "pic".$i;
                $var4 = "description".$i;

                $customer_review[$j][$var1] = $$var1;
                $customer_review[$j][$var2] = $$var2;
                $customer_review[$j][$var3] = $$var3;
                $customer_review[$j][$var4] = $$var4;

                $review_string.= "   "."'{$j}'=> array(\n"."       "."'".$$var1."',\n"."       "."'".$$var2."',\n"."       "."'".$$var3."',\n"."       "."'".$$var4."',\n"."    "."),\n";
            }

            $review_string.=")";

            // video section
            $custom_video = array();
            $total_video  = $this->config->item('custom_video');

            $video_string = "array".'('."\n";

            for($i = 1; $i <= count($total_video); $i++) {

                $j = $i-1;
                $var1 = "thumbnail".$i;
                $var2 = "title".$i;
                $var3 = "video_url".$i;

                $custom_video[$j][$var1] = $$var1;
                $custom_video[$j][$var2] = $$var2;
                $custom_video[$j][$var3] = $$var3;

                $video_string.= "   "."'{$j}'=>array(\n"."     "."'".$$var1."',\n"."     "."'".$$var2."',\n"."     "."'".$$var3."',\n"."   "."),\n"; 
            }

            $video_string.= "\n)";

            
            // writing application/config/my_config
            $app_frontend_config_data = "<?php ";
            $app_frontend_config_data.= "\n\$config['theme_front'] = '".$theme_front."';\n";
            $app_frontend_config_data.= "\$config['display_landing_page'] = '".$display_landing_page."';\n";
            $app_frontend_config_data.= "\$config['facebook'] = '$facebook_link';\n";
            $app_frontend_config_data.= "\$config['twitter'] = '$twitter_link';\n";
            $app_frontend_config_data.= "\$config['linkedin'] = '$linkedin_link';\n";
            $app_frontend_config_data.= "\$config['youtube'] = '$youtube_link';\n";
            $app_frontend_config_data.= "\$config['display_review_block'] = '$display_review_block';\n";
            $app_frontend_config_data.= "\$config['display_video_block'] = '$display_video_block';\n";
            $app_frontend_config_data.= "\$config['promo_video'] = '$promo_video';\n";
            $app_frontend_config_data.= "\$config['customer_review_video'] = '$customer_review_video';\n";
            $app_frontend_config_data.= "\$config['customer_review'] = ".$review_string.";\n";
            $app_frontend_config_data.= "\n\$config['custom_video'] = ".$video_string.";\n";

            file_put_contents(APPPATH.'config/frontend_config.php', $app_frontend_config_data, LOCK_EX);
            $this->session->set_flashdata('success_message', 1);
            redirect('admin/frontend_settings', 'location');

        }
    }

    public function smtp_settings()
    {     
        $test_button = "";
        
        $data['body'] = "admin/settings/smtp";
        $data['page_title'] = $this->lang->line('SMTP Settings');
        $get_data = $this->basic->get_data("email_config");
        if (!empty($get_data)) {
            
            if($get_data[0]['email_address'] != "" && $get_data[0]['email_address'] != "" && $get_data[0]['smtp_port'] != "" && $get_data[0]['smtp_user'] != "" && $get_data[0]['smtp_password'] != "") {
                $test_button = 1;
            }
        }
        $data['test_btn'] = $test_button;

        $data['xvalue'] = isset($get_data[0])?$get_data[0]:array();
        $this->_viewcontroller($data);
    }

    public function smtp_settings_action()
    {
        $this->is_demo_check();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        if ($_POST) 
        {
            // validation
            $this->form_validation->set_rules('email_address',   '<b>'.$this->lang->line("Email Address").'</b>',    'trim');
            $this->form_validation->set_rules('smtp_host',       '<b>'.$this->lang->line("SMTP Host").'</b>',        'trim');
            $this->form_validation->set_rules('smtp_port',       '<b>'.$this->lang->line("SMTP Port").'</b>',        'trim');
            $this->form_validation->set_rules('smtp_user',       '<b>'.$this->lang->line("SMTP User").'</b>',        'trim');
            $this->form_validation->set_rules('smtp_password',   '<b>'.$this->lang->line("SMTP Password").'</b>',    'trim');
            $this->form_validation->set_rules('smtp_type',       '<b>'.$this->lang->line("Connection Type").'</b>',  'trim');
            

            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) 
            {
                return $this->smtp_settings();
            } 
            else 
            {
                // assign
                $email_address=$this->input->post('email_address', true);
                $smtp_host=$this->input->post('smtp_host', true);
                $smtp_port=$this->input->post('smtp_port', true);
                $smtp_user=$this->input->post('smtp_user', true);
                $smtp_password=$this->input->post('smtp_password', true);
                $smtp_type=$this->input->post('smtp_type', true);

                $update_data = 
                array
                (
                    'email_address'=>$email_address,
                    'smtp_host'=>$smtp_host,
                    'smtp_port'=>$smtp_port,
                    'smtp_user'=>$smtp_user,
                    'smtp_password'=>$smtp_password,
                    'smtp_type'=>$smtp_type,
                    'status'=>'1'
                );

                $get_data = $this->basic->get_data("email_config");
                if(isset($get_data[0]))
                $this->basic->update_data("email_config",array("id >"=>0),$update_data);
                else $this->basic->insert_data("email_config",$update_data);                 
                                         
                $this->session->set_flashdata('success_message', 1);
                redirect('admin/smtp_settings', 'location');
            }
        }
    }


    public function analytics_settings()
    {
        $data['body'] = "admin/settings/analytics";
        $data['page_title'] = $this->lang->line('Analytics Settings');
        $this->_viewcontroller($data);
    }


    public function analytics_settings_action()
    {
        $this->is_demo_check();

        $this->load->helper('file');        
        $pixel_code = $this->input->post('pixel_code');
        $google_code = $this->input->post('google_code');

        file_put_contents(APPPATH.'views/include/fb_px.php', $pixel_code, LOCK_EX);
        file_put_contents(APPPATH.'views/include/google_code.php', $google_code, LOCK_EX);

        $this->session->set_flashdata('success_message', 1);
        redirect('admin/analytics_settings','location');
    }


    public function advertisement_settings()
    {                
        $data['body'] = "admin/settings/advertisement";
        $data['config_data'] = $this->basic->get_data("ad_config");       
        $data['page_title'] = $this->lang->line('Advertisement Settings');  
        $this->_viewcontroller($data);
    }


    public function advertisement_settings_action()
    {
        $this->is_demo_check();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        if ($_POST) 
        {
            // validation
            $this->form_validation->set_rules('section1_html',          '<b>Section - 1 (970x90)</b>',              'trim');
            $this->form_validation->set_rules('section1_html_mobile',   '<b>Section - 1 : Mobile  (320x100)</b>',   'trim');
            $this->form_validation->set_rules('section2_html',          '<b>Section: 2 (300x250) </b>',             'trim');
            $this->form_validation->set_rules('section3_html',          '<b>Section: 3 (300x250) </b>',             'trim');
            $this->form_validation->set_rules('section4_html',          '<b>Section: 4 (300x600) </b>',             'trim');
            $this->form_validation->set_rules('status',                 '<b>'.$this->lang->line("status").'</b>',   'trim|required');
            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) 
            {
                return $this->ad_config();
            } 
            else 
            {
                // assign
                $section1_html=htmlspecialchars($this->input->post('section1_html', false),ENT_QUOTES);
                $section1_html_mobile=htmlspecialchars($this->input->post('section1_html_mobile', false),ENT_QUOTES);
                $section2_html=htmlspecialchars($this->input->post('section2_html', false),ENT_QUOTES);
                $section3_html=htmlspecialchars($this->input->post('section3_html', false),ENT_QUOTES);
                $section4_html=htmlspecialchars($this->input->post("section4_html", false),ENT_QUOTES);
                $status=$this->input->post("status");
               
                if($status=="1")
                $insert_update_data=array("section1_html"=>$section1_html,"section1_html_mobile"=>$section1_html_mobile,"section2_html"=>$section2_html,"section3_html"=>$section3_html,"section4_html"=>$section4_html,"status"=>$status);
                else
                $insert_update_data=array("status"=>$status);

                if($this->basic->is_exist("ad_config",$where='',$select='id')) 
                $this->basic->update_data("ad_config",$where='',$insert_update_data);
                else $this->basic->insert_data("ad_config",$insert_update_data);
                  
                $this->session->set_flashdata('success_message', 1);
                redirect('admin/advertisement_settings', 'location');
            }
        }
    }


    public function user_manager()
    {
        $data['body']='admin/user/user_list';
        $data['page_title']=$this->lang->line("User Manager");
        $this->_viewcontroller($data);  
    }

    public function user_manager_data()
    {           
        $this->ajax_check();
        $search_value = $_POST['search']['value'];
        $display_columns = array("#","CHECKBOX",'user_id','avatar','name', 'email','package_name', 'status', 'user_type','expired_date', 'actions', 'add_date','last_login_at','last_login_ip');
        $search_columns = array('name', 'email','mobile','add_date','expired_date','last_login_ip');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'user_id';
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
            
        $table="users";
        $join = array('package'=>"package.id=users.package_id,left");
        $select= array("users.*","users.id as user_id","package.package_name");
        $info=$this->basic->get_data($table,$where,$select,$join,$limit,$start,$order_by,$group_by='');
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        $i=0;
        $base_url=base_url();
        foreach ($info as $key => $value) 
        {
            $status = $info[$i]["status"];
            if($status=='1') $info[$i]["status"] = "<i title ='".$this->lang->line('Active')."'class='status-icon fas fa-toggle-on text-primary'></i>";
            else $info[$i]["status"] = "<i title ='".$this->lang->line('Inactive')."'class='status-icon fas fa-toggle-off gray'></i>";

            $last_login_at = $info[$i]["last_login_at"];
            if($last_login_at=='0000-00-00 00:00:00') $info[$i]["last_login_at"] = $this->lang->line("Never");
            else $info[$i]["last_login_at"] = date("jS M y H:i",strtotime($info[$i]["last_login_at"]));

            $expired_date = $info[$i]["expired_date"];
            if($expired_date=='0000-00-00 00:00:00' || $info[$i]["user_type"]=="Admin") $info[$i]["expired_date"] = "-";
            else $info[$i]["expired_date"] = date("jS M y",strtotime($info[$i]["expired_date"]));

            $info[$i]["add_date"] = date("jS M y",strtotime($info[$i]["add_date"]));

            if($info[$i]["package_name"]=="") $info[$i]["package_name"] = "-";
  
            $user_name = $info[$i]["name"];
            $user_id = $info[$i]["id"];
            $str="";   
            
            $str=$str."<a class='btn btn-circle btn-outline-warning' data-toggle='tooltip' title='".$this->lang->line('Edit')."' href='".$base_url.'admin/edit_user/'.$info[$i]["user_id"]."'>".'<i class="fas fa-edit"></i>'."</a>";
            $str=$str."&nbsp;<a class='btn btn-circle btn-outline-dark change_password' href='' data-toggle='tooltip' title='".$this->lang->line('Change Password')."' data-id='".$user_id."' data-user='".htmlspecialchars($user_name)."'>".'<i class="fas fa-key"></i>'."</a>";
            $str=$str."&nbsp;<a href='".$base_url.'home/user_delete_action/'.$info[$i]["user_id"]."' class='are_you_sure_datatable btn btn-circle btn-outline-danger' csrf_token='".$this->session->userdata('csrf_token_session')."' data-toggle='tooltip' title='".$this->lang->line('Delete')."'>".'<i class="fa fa-trash"></i>'."</a>";
             
            if($this->session->userdata('license_type') == 'double')
            $info[$i]["actions"] = "<div class='min_width_208px'>".$str."</div>";
            else $info[$i]["actions"] = "<div class='min_width_161px'>".$str."</div>";
            
            if($i==0) $info[$i]["actions"] .= "<script src='".base_url()."assets/js/system/tooltip_popover.js'></script>";

            $logo=$info[$i]["brand_logo"];

            if($logo=="") $logo=base_url("assets/img/avatar/avatar-1.png");
            else $logo=base_url().'member/'.$logo;

            $info[$i]["avatar"] = "<img src='".$logo."' width='40px' height='40px' class='rounded-circle'>";

            if($info[$i]['user_type']=='Admin') $tie="-circle orange";
            else $tie="-noicon blue";

            $info[$i]['name'] = "<span data-toggle='tooltip' title='".$this->lang->line($info[$i]['user_type'])."'><i class='fas fa-user".$tie." text-warning'></i> ".$info[$i]['name']." </span>";
                
            if($this->is_demo=='1')  $info[$i]["email"] ="******@*****.***";
            if($this->is_demo=='1')  $info[$i]["last_login_ip"] ="XXXXXXXXX";

            $i++;
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="user_id");

        echo json_encode($data);
    }


    public function add_user()
    {       
        $data['body']='admin/user/add_user';     
        $data['page_title']=$this->lang->line('Add User');     
        $packages=$this->basic->get_data('package',$where='',$select='',$join='',$limit='',$start='',$order_by='package_name asc');
        $data['packages'] = format_data_dropdown($packages,"id","package_name",false);
        $this->_viewcontroller($data);
    }


    public function add_user_action() 
    {
        $this->is_demo_check();

        if($_SERVER['REQUEST_METHOD'] === 'GET') 
        redirect('home/access_forbidden','location');

        if($_POST)
        {
            $this->form_validation->set_rules('name', '<b>'.$this->lang->line("Full Name").'</b>', 'trim');      
            $this->form_validation->set_rules('email', '<b>'.$this->lang->line("Email").'</b>', 'trim|required|valid_email|is_unique[users.email]');      
            $this->form_validation->set_rules('mobile', '<b>'.$this->lang->line("Mobile").'</b>', 'trim');      
            $this->form_validation->set_rules('password', '<b>'.$this->lang->line("Password").'</b>', 'trim|required');      
            $this->form_validation->set_rules('confirm_password', '<b>'.$this->lang->line("Confirm Password").'</b>', 'trim|required|matches[password]');      
            $this->form_validation->set_rules('address', '<b>'.$this->lang->line("Address").'</b>', 'trim');      
            $this->form_validation->set_rules('user_type', '<b>'.$this->lang->line("User Type").'</b>', 'trim|required');      
            $this->form_validation->set_rules('status', '<b>'.$this->lang->line("Status").'</b>', 'trim');

            if($this->input->post("user_type")=="Member")     
            {
                $this->form_validation->set_rules('package_id', '<b>'.$this->lang->line("Package").'</b>', 'trim|required');      
                $this->form_validation->set_rules('expired_date', '<b>'.$this->lang->line("Expiry Date").'</b>', 'trim|required');
            }
                
            if ($this->form_validation->run() == FALSE)
            {
                $this->add_user(); 
            }
            else
            {               
                $name=strip_tags($this->input->post('name',true));
                $email=$this->input->post('email');
                $mobile=strip_tags($this->input->post('mobile',true));
                $password=md5($this->input->post('password'));
                $confirm_password=$this->input->post('confirm_password');
                $address=strip_tags($this->input->post('address',true));
                $user_type=$this->input->post('user_type');
                $status=$this->input->post('status');
                $package_id=$this->input->post('package_id');
                $expired_date=$this->input->post('expired_date');
                if($status=='') $status='0';
                                                       
                $data=array
                (
                    'name'=>$name,
                    'email'=>$email,
                    'mobile'=>$mobile,
                    'password'=>$password,
                    'address'=>$address,
                    'user_type'=>$user_type,
                    'status'=>$status,
                    'add_date' => date("Y-m-d H:i:s")
                );

                if($user_type=='Member')
                {
                    $data["package_id"] = $package_id;
                    $data["expired_date"] = $expired_date;
                }
                else
                {
                    $data["package_id"] = 0;
                    $data["expired_date"] = '';
                }

                
                if($this->basic->insert_data('users',$data)) $this->session->set_flashdata('success_message',1);   
                else $this->session->set_flashdata('error_message',1);     
                
                redirect('admin/user_manager','location');                 
                
            }
        }   
    }


    public function edit_user($id=0)
    {       
        $this->is_demo_check();

        $data['body']='admin/user/edit_user';     
        $data['page_title']=$this->lang->line('Edit User');     
        $packages=$this->basic->get_data('package',$where='',$select='',$join='',$limit='',$start='',$order_by='package_name asc');
        $xdata=$this->basic->get_data('users',array("where"=>array("id"=>$id)));
        if(!isset($xdata[0])) exit();
        $data['packages'] = format_data_dropdown($packages,"id","package_name",false);
        $data['xdata'] = $xdata[0];
        $this->_viewcontroller($data);
    }


    public function edit_user_action() 
    {
        $this->is_demo_check();

        if($_SERVER['REQUEST_METHOD'] === 'GET') 
        redirect('home/access_forbidden','location');

        if($_POST)
        {
            $id = $this->input->post('id');
            $this->form_validation->set_rules('name', '<b>'.$this->lang->line("Full Name").'</b>', 'trim');
            $unique_email = "users.email.".$id; 
            $this->form_validation->set_rules('email', '<b>'.$this->lang->line("Email").'</b>', "trim|required|valid_email|is_unique[$unique_email]");      
            $this->form_validation->set_rules('mobile', '<b>'.$this->lang->line("Mobile").'</b>', 'trim');            
            $this->form_validation->set_rules('address', '<b>'.$this->lang->line("Address").'</b>', 'trim');      
            $this->form_validation->set_rules('user_type', '<b>'.$this->lang->line("User Type").'</b>', 'trim|required');      
            $this->form_validation->set_rules('status', '<b>'.$this->lang->line("Status").'</b>', 'trim');

            if($this->input->post("user_type")=="Member")     
            {
                $this->form_validation->set_rules('package_id', '<b>'.$this->lang->line("Package").'</b>', 'trim|required');      
                $this->form_validation->set_rules('expired_date', '<b>'.$this->lang->line("Expiry Date").'</b>', 'trim|required');
            }
                
            if ($this->form_validation->run() == FALSE)
            {
                $this->edit_user($id); 
            }
            else
            {               
                $name=strip_tags($this->input->post('name',true));
                $email=$this->input->post('email');
                $mobile=strip_tags($this->input->post('mobile',true));                
                $address=strip_tags($this->input->post('address',true));
                $user_type=$this->input->post('user_type');
                $status=$this->input->post('status');
                $package_id=$this->input->post('package_id');
                $expired_date=$this->input->post('expired_date');
                if($status=='') $status='0';
                                                       
                $data=array
                (
                    'name'=>$name,
                    'email'=>$email,
                    'mobile'=>$mobile,
                    'address'=>$address,
                    'user_type'=>$user_type,
                    'status'=>$status
                );
                if($user_type=='Member')
                {
                    $data["package_id"] = $package_id;
                    $data["expired_date"] = $expired_date;
                }
                else
                {
                    $data["package_id"] = 0;
                    $data["expired_date"] = '';
                }
                
                
                if($this->basic->update_data('users',array("id"=>$id),$data)) $this->session->set_flashdata('success_message',1);   
                else $this->session->set_flashdata('error_message',1);     
                
                redirect('admin/user_manager','location');                 
                
            }
        }   
    }
  

    public function login_log()
    {        
       $this->is_demo_check();

       $data['body'] = "admin/user/login_log";
       $data['page_title'] = $this->lang->line('Login Log');
       $today = date("Y-m-d");
       $prev_day = date('Y-m-d', strtotime($today. ' - 30 days'))." 00:00:00";
       $data['info'] = $this->basic->get_data('user_login_info',array('where'=>array('login_time >='=>$prev_day)),$select='',$join='',$limit='',$start=NULL,$order_by='login_time DESC');     
       $this->_viewcontroller($data);
    }

    public function delete_user_log()
    {       
        // $this->ajax_check();
        $this->is_demo_check_ajax();

        $table_name = "user_login_info";
        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));
        $from_date = $from_date." 23:59:59";
        $where = array('login_time <' => $from_date);
        if($this->basic->delete_data($table_name,$where))
        echo json_encode(array("status"=>"1","message"=>$this->lang->line("Log has been deleted successfully"))); 
        else echo json_encode(array("status"=>"0","message"=>$this->lang->line("Something went wrong, please try again")));
    }


    public function change_user_password_action()
    {
        $this->is_demo_check_ajax();

        $this->ajax_check();

        $id = $this->input->post('user_id');
        if ($_POST) 
        {
            $this->form_validation->set_rules('password', '<b>'. $this->lang->line("password").'</b>', 'trim|required');
            $this->form_validation->set_rules('confirm_password', '<b>'. $this->lang->line("confirm password").'</b>', 'trim|required|matches[password]');
        }
        if ($this->form_validation->run() == false) 
        {
           echo json_encode(array("status"=>"0","message"=>$this->lang->line("Something went wrong, please try again")));
           exit();
        } 
        else 
        {
            $this->csrf_token_check();

            $new_password = $this->input->post('password');
            $new_confirm_password = $this->input->post('confirm_password');

            $table_change_password = 'users';
            $where_change_passwor = array('id' => $id);
            $data = array('password' => md5($new_password));
            $this->basic->update_data($table_change_password, $where_change_passwor, $data);

            $where['where'] = array('id' => $id);
            $mail_info = $this->basic->get_data('users', $where);
            
            $name = $mail_info[0]['name'];
            $to = $mail_info[0]['email'];
            $password = $new_password;

            $mask = $this->config->item('product_name');
            $from = $this->config->item('institute_email');
            $url = site_url();


            $email_template_info = $this->basic->get_data('email_template_management',array('where'=>array('template_type'=>'change_password')),array('subject','message'));

            if(isset($email_template_info[0]) && $email_template_info[0]['subject'] != '' && $email_template_info[0]['message'] != '') 
            {
                $subject = $email_template_info[0]['subject'];
                $message = str_replace(array("#USERNAME#","#APP_URL#","#APP_NAME#","#NEW_PASSWORD#"),array($name,$url,$mask,$password),$email_template_info[0]['message']);
            } 
            else 
            {
                $subject = 'Change Password Notification';
                $message = "Dear {$name},<br/> Your <a href='".$url."'>{$mask}</a> password has been changed. Your new password is: {$password}.<br/><br/> Thank you.";
            }
           
            $this->_mail_sender($from, $to, $subject, $message, $mask);
            echo json_encode(array("status"=>"1","message"=>$this->lang->line("Password has been changed successfully")));
        }
    }

    public function send_test_email()
    {
        $this->ajax_check();

        $this->is_demo_check();

        if($_POST) {

            $email= strip_tags($this->input->post('email',true));
            $subject= strip_tags($this->input->post('subject',true));
            $message= $this->input->post('message');
            $user_ids=$this->input->post('user_ids');
            $from=$this->config->item('institute_email');
            $to = $email;
            $mask=$this->config->item("product_name");
            $html = 1;
            $test_mail = 1;
            $smtp = 1;
            
            $response = $this->_mail_sender($from, $to, $subject, $message, $mask, $html,$smtp,'',$test_mail);
            echo $response;
  
        }


        
    }


    public function send_email_member()
    {   
        $this->ajax_check();
        $this->is_demo_check();
        if($_POST)
        {
            $this->csrf_token_check();
            $subject= strip_tags($this->input->post('subject',true));
            $message= $this->input->post('message');
            $user_ids=$this->input->post('user_ids');
            $count=0;

            $info = $this->basic->get_data("users",array("where_in"=>array("id"=>$user_ids)));
            
            foreach($info as $member)
            {               
                $email=$member['email'];
                $member_id=$member['id'];
                $from=$this->config->item('institute_email');
                $to=$email;
                $mask=$this->config->item("product_name");
                
                if($message=="" || $from=="" || $to=="" || $subject=="") continue;

                if($this->_mail_sender($from,$to,$subject,$message,$mask))  $count++;
               
            }
            echo "<b> $count / ".count($info)." : ".$this->lang->line("Email Sent Successfully")."</b>";
           
        }   
    }



   public function email_template_settings()
   {
       $data['emailTemplatetabledata'] = $this->basic->get_data("email_template_management");

       $data['default_values'] = array(
           array(// account activation
               'subject' => "#APP_NAME# | Account Activation",
               'message' => '<p>To activate your account please perform the following steps :</p>
<ol>
<li>Go to this url : #ACTIVATION_URL#</li>
<li>Enter this code : #ACCOUNT_ACTIVATION_CODE#</li>
<li>Activate your account</li>
</ol>'
           ),
           array( // reset password
               'subject' => "#APP_NAME# | Password Recovery",
               'message' => '<p>To reset your password please perform the following steps :</p>
<ol>
<li>Go to this url : #PASSWORD_RESET_URL#</li>
<li>Enter this code : #PASSWORD_RESET_CODE#</li>
<li>reset your password.</li>
</ol>
<h4>Link and code will be expired after 24 hours.</h4>'
           ),
           array( // change password
               'subject' => 'Change Password Notification',
               'message' => 'Dear #USERNAME#,<br/> 
Your <a href="#APP_URL#">#APP_NAME#</a> password has been changed.<br>
Your new password is: #NEW_PASSWORD#.<br/><br/> 
Thank you,<br/>
<a href="#APP_URL#">#APP_NAME#</a> Team'
           ),
           array( // payment notification before 10 days
               'subject' => 'Payment Alert',
               'message' => 'Dear #USERNAME#,
<br/> Your account will expire after 10 days, Please pay your fees.<br/><br/>
Thank you,<br/>
<a href="#APP_URL#">#APP_NAME#</a> Team'
           ),
           array( // payment notification before 1 day
               'subject' => 'Payment Alert',
               'message' => 'Dear #USERNAME#,<br/>
Your account will expire tomorrow, Please pay your fees.<br/><br/>
Thank you,<br/>
<a href="#APP_URL#">#APP_NAME#</a> Team'
           ),
           array( //payment notification after 1 day
               'subject' => 'Subscription Expired',
               'message' => 'Dear #USERNAME#,<br/>
Your account has been expired, Please pay your fees for continuity.<br/><br/>
Thank you,<br/>
<a href="#APP_URL#">#APP_NAME#</a> Team'
           ),
           array( // paypal payment confirmation
               'subject' => 'Payment Confirmation',
               'message' => 'Congratulations,<br/> 
We have received your payment successfully.<br/>
Now you are able to use #PRODUCT_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>
Thank you,<br/>
<a href="#SITE_URL#">#APP_NAME#</a> Team'
           ),
           array( // new payment made email to admin
               'subject' => 'New Payment Made',
               'message' => 'New payment has been made by #PAID_USER_NAME#'
           ),
           array( // stripe payment confirmation
               'subject' => 'Payment Confirmation',
               'message' => 'Congratulations,<br/>
We have received your payment successfully.<br/>
Now you are able to use #PRODUCT_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>
Thank you,<br/>
<a href="#APP_URL#">#APP_NAME#</a> Team'
           ),
           array( // stripe new payment made email
               'subject' => 'New Payment Made',
               'message' => 'New payment has been made by #PAID_USER_NAME#'
           ),

       );

       $data['body'] = "admin/settings/email_template_setting";
       $data['page_title'] = $this->lang->line('Email Template Settings');
       $this->_viewcontroller($data);
   }

   public function email_template_settings_action()
   {
       $this->is_demo_check();
       
       if ($_SERVER['REQUEST_METHOD'] === 'GET') 
       { 
           redirect('home/access_forbidden', 'location'); 
       }

       if($_POST)
       {
           $post= $_POST;

           $i = 0;
           $subject = '';
           $message = '';

           foreach ($post as $key => $value) 
           {
               $modifiedKeys = explode('-',$key);

               if($modifiedKeys[1]=='subject')
                   $subject = $value;

               if($modifiedKeys[1] == 'message')
                   $message = $value;

               $i++;

               if($i%2 == 0)
               {
                   $this->basic->update_data('email_template_management',array('template_type'=>$modifiedKeys[0]), array('subject'=>$subject,'message'=>$message));
               }

           }
           $this->session->set_flashdata('success_message', 1);
           redirect('admin/email_template_settings', 'location');

       }
 
   }

   public function delete_email_template($template_type='')
   {
       $this->is_demo_check();

       if($this->basic->update_data("email_template_management",array('template_type'=>$template_type),array('subject'=>'','message'=>''))) 
       {
           
           $this->session->set_flashdata('success_message', 1);
           redirect('admin/email_template_settings','refresh');
       }

       
   }


   public function activity_log()
   {
        
        $this->is_demo_check();

        if($this->session->userdata('user_type')=='Member') exit();
        if($this->session->userdata('license_type') != 'double') exit();

        $users_info = $this->basic->get_data('users','',array('name','email','id'));
        $users = array();
        foreach($users_info as $value)
        {
            $users[$value['id']]['name'] = $value['name'];
            $users[$value['id']]['email'] = $value['email']; 
        }

        $select = array('user_id','post_type','campaign_name','last_updated_at','post_url');
        $auto_post_info = $this->basic->get_data("facebook_rx_auto_post",'',$select,'',10,$start=NULL,$order_by='last_updated_at desc');
        
        $slider_post_info = $this->basic->get_data("facebook_rx_slider_post",'',$select,'',10,$start=NULL,$order_by='last_updated_at desc');

        $select = array('user_id','cta_type','campaign_name','last_updated_at','post_url');
        $cta_post_info = $this->basic->get_data("facebook_rx_cta_post",'',$select,'',10,$start=NULL,$order_by='last_updated_at desc');

        $posting_array = array_merge($auto_post_info,$slider_post_info,$cta_post_info);

        usort($posting_array, function($a, $b) {
            return strtotime($a['last_updated_at']) < strtotime($b['last_updated_at']);
        });
        $posting_report = array();
        $posting_counter = 0;
        foreach($posting_array as $value)
        {
            if(isset($value['cta_type']))
            $posting_report[$posting_counter]['post_type'] = "CTA [".$value['cta_type']."]";
            else
            {
                $posting_report[$posting_counter]['post_type'] = $value['post_type'];
            }

            $posting_report[$posting_counter]['campaign_name'] = $value['campaign_name'];
            $posting_report[$posting_counter]['last_updated_at'] = $value['last_updated_at'];
            $posting_report[$posting_counter]['post_url'] = $value['post_url'];
            $posting_report[$posting_counter]['user_id'] = $value['user_id'];
            $posting_report[$posting_counter]['user_name'] = isset($users[$value['user_id']]['name']) ? $users[$value['user_id']]['name'] : "";
            $posting_report[$posting_counter]['user_email'] = isset($users[$value['user_id']]['email']) ? $users[$value['user_id']]['email'] : "";

            $posting_counter++;
            if($posting_counter == 10) break;

        } 

        $data['facebook_poster'] = $posting_report;

        $select = array('user_id','campaign_name','campaign_type','added_at','schedule_time','total_thread','successfully_sent','posting_status');
        $bulk_message_campaign = $this->basic->get_data("facebook_ex_conversation_campaign",'',$select,'',10,$start=NULL,$order_by='added_at desc');
        $bulk_message_campaign_report = array();
        $bulk_message_campaign_counter = 0;
        foreach($bulk_message_campaign as $value)
        {
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['campaign_name'] = $value['campaign_name'];
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['campaign_type'] = $value['campaign_type'];
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['added_at'] = $value['added_at'];
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['schedule_time'] = $value['schedule_time'];
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['total_thread'] = $value['total_thread'];
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['successfully_sent'] = $value['successfully_sent'];
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['posting_status'] = $value['posting_status'];
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['user_id'] = $value['user_id'];
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['user_name'] = isset($users[$value['user_id']]['name']) ? $users[$value['user_id']]['name'] : "";
            $bulk_message_campaign_report[$bulk_message_campaign_counter]['user_email'] = isset($users[$value['user_id']]['email']) ? $users[$value['user_id']]['email'] : "";
            $bulk_message_campaign_counter++;
            if($bulk_message_campaign_counter == 10) break;
        }
        $data['bulk_message_campaign'] = $bulk_message_campaign_report;


        $select = array('user_id','post_thumb','post_id','auto_reply_campaign_name','auto_private_reply_count','auto_comment_reply_count','last_reply_time');
        $autoreply_info = $this->basic->get_data("facebook_ex_autoreply",'',$select,'',10,$start=NULL,$order_by='last_reply_time desc');
        $autoreply_info_report = array();
        $autoreply_info_counter = 0;
        foreach($autoreply_info as $value)
        {
            $autoreply_info_report[$autoreply_info_counter]['campaign_name'] = $value['auto_reply_campaign_name'];
            $autoreply_info_report[$autoreply_info_counter]['post_thumb'] = $value['post_thumb'];
            $autoreply_info_report[$autoreply_info_counter]['post_id'] = $value['post_id'];
            $autoreply_info_report[$autoreply_info_counter]['auto_private_reply_count'] = $value['auto_private_reply_count'];
            $autoreply_info_report[$autoreply_info_counter]['auto_comment_reply_count'] = $value['auto_comment_reply_count'];
            $autoreply_info_report[$autoreply_info_counter]['last_reply_time'] = $value['last_reply_time'];
            $autoreply_info_report[$autoreply_info_counter]['user_id'] = $value['user_id'];
            $autoreply_info_report[$autoreply_info_counter]['user_name'] = isset($users[$value['user_id']]['name']) ? $users[$value['user_id']]['name'] : "";
            $autoreply_info_report[$autoreply_info_counter]['user_email'] = isset($users[$value['user_id']]['email']) ? $users[$value['user_id']]['email'] : "";
            $autoreply_info_counter++;
            if($autoreply_info_counter == 10) break;
        }
        $data['autoreply_campaign'] = $autoreply_info_report;


        $vidcaster_campaign_report = array();
        if($this->basic->is_exist("add_ons",array("project_id"=>21)))
        {
            $select = array('user_id','schedule_time','post_url','scheduler_name','posting_status','last_updated_at');
            $vidcaster_campaign_info = $this->basic->get_data("vidcaster_facebook_rx_live_scheduler",'',$select,'',10,$start=NULL,$order_by='last_updated_at desc');
            $vidcaster_campaign_counter = 0;
            foreach($vidcaster_campaign_info as $value)
            {
                $vidcaster_campaign_report[$vidcaster_campaign_counter]['campaign_name'] = $value['scheduler_name'];
                $vidcaster_campaign_report[$vidcaster_campaign_counter]['post_url'] = $value['post_url'];
                if($value['posting_status'] == 0) $posting_status = $this->lang->line('pending');
                if($value['posting_status'] == 1) $posting_status = $this->lang->line('processing');
                if($value['posting_status'] == 2) $posting_status = $this->lang->line('Completed');
                $vidcaster_campaign_report[$vidcaster_campaign_counter]['posting_status'] = $posting_status;
                $vidcaster_campaign_report[$vidcaster_campaign_counter]['last_updated_at'] = $value['last_updated_at'];
                $vidcaster_campaign_report[$vidcaster_campaign_counter]['user_id'] = $value['user_id'];
                $vidcaster_campaign_report[$vidcaster_campaign_counter]['user_name'] = isset($users[$value['user_id']]['name']) ? $users[$value['user_id']]['name'] : "";
                $vidcaster_campaign_report[$vidcaster_campaign_counter]['user_email'] = isset($users[$value['user_id']]['email']) ? $users[$value['user_id']]['email'] : "";
                $vidcaster_campaign_counter++;
                if($vidcaster_campaign_counter == 10) break;
            }

            $data['vidcaster_campaign'] = $vidcaster_campaign_report;
        }


        $combopost_campaign_report = array();
        if($this->basic->is_exist("add_ons",array("project_id"=>20)))
        {
            $select = array('user_id','schedule_time','posting_status','campaign_name','campaign_type');
            $comboposter_campaign_info = $this->basic->get_data("post_data_info",'',$select,'',10,$start=NULL,$order_by='schedule_time desc');
            $comboposter_campaign_counter = 0;
            foreach($comboposter_campaign_info as $value)
            {
                $combopost_campaign_report[$comboposter_campaign_counter]['campaign_name'] = $value['campaign_name'];
                $combopost_campaign_report[$comboposter_campaign_counter]['posting_status'] = $value['posting_status'];
                $combopost_campaign_report[$comboposter_campaign_counter]['post_type'] = $value['campaign_type'];
                $combopost_campaign_report[$comboposter_campaign_counter]['schedule_time'] = $value['schedule_time'];
                $combopost_campaign_report[$comboposter_campaign_counter]['user_id'] = $value['user_id'];
                $combopost_campaign_report[$comboposter_campaign_counter]['user_name'] = isset($users[$value['user_id']]['name']) ? $users[$value['user_id']]['name'] : "";
                $combopost_campaign_report[$comboposter_campaign_counter]['user_email'] = isset($users[$value['user_id']]['email']) ? $users[$value['user_id']]['email'] : "";
                $comboposter_campaign_counter++;
                if($comboposter_campaign_counter == 10) break;
            }

            $data['comboposter_campaign'] = $combopost_campaign_report;
        }


        $data['body'] = 'dashboard/activity_log';
        $data['page_title'] = $this->lang->line('Activity Log');
        $this->_viewcontroller($data);
   }



   

}
