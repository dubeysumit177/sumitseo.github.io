<?php
require_once("Home.php");

class Cron_job extends Home
{
    public function __construct()
    {
        parent::__construct();
        $this->upload_path = realpath( APPPATH . '../upload');        
    }


    public function api_member_validity($user_id='')
    {
        if($user_id!='') {
            $where['where'] = array('id'=>$user_id);
            $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date'));
            $expire_date = strtotime($user_expire_date[0]['expired_date']);
            $current_date = strtotime(date("Y-m-d"));
            $package_data=$this->basic->get_data("users",$where=array("where"=>array("users.id"=>$user_id)),$select="package.price as price, users.user_type",$join=array('package'=>"users.package_id=package.id,left"));

            if(is_array($package_data) && array_key_exists(0, $package_data) && $package_data[0]['user_type'] == 'Admin' )
                return true;

            $price = '';
            if(is_array($package_data) && array_key_exists(0, $package_data))
            $price=$package_data[0]["price"];
            if($price=="Trial") $price=1;

            
            if ($expire_date < $current_date && ($price>0 && $price!=""))
            return false;
            else return true;           

        }
    }


    public function index()
    {
       $this->get_api();
    }

    public function _api_key_generator()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin')
        redirect('home/login_page', 'location');

        $this->member_validity();
        $val=$this->session->userdata("user_id")."-".substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 7 ).time()
        .substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789') , 0 , 7 );
        return $val;
    }

    public function get_api()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin')
        redirect('home/login_page', 'location');

        $this->member_validity();

        $data['body'] = "admin/cron_job/command";
        $data['page_title'] = $this->lang->line("Cron Job");
        $api_data=$this->basic->get_data("native_api",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $data["api_key"]="";
        if(count($api_data)>0) $data["api_key"]=$api_data[0]["api_key"];
        $this->_viewcontroller($data);
    }

    public function get_api_action()
    { 
        $this->is_demo_check();
        
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login', 'location');

        if($this->session->userdata('user_type') != 'Admin')
        redirect('home/login_page', 'location');

        $api_key=$this->_api_key_generator(); 
        if($this->basic->is_exist("native_api",array("api_key"=>$api_key)))
        $this->get_api_action();

        $user_id=$this->session->userdata("user_id");        
        if($this->basic->is_exist("native_api",array("user_id"=>$user_id)))
        $this->basic->update_data("native_api",array("user_id"=>$user_id),array("api_key"=>$api_key));
        else $this->basic->insert_data("native_api",array("api_key"=>$api_key,"user_id"=>$user_id));
            
        redirect('cron_job/get_api', 'location');
    }



    public function api_key_check($api_key="")
    {
        $user_id="";
        if($api_key!="")
        {
            $explde_api_key=explode('-',$api_key);
            $user_id="";
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];
        }

        if($api_key=="")
        {        
            echo "API Key is required.";    
            exit();
        }

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
           echo "API Key does not match with any user.";
           exit();
        }

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any authentic user.";
            exit();
        }              
       

    }

    protected function call_curl_internal_cronjob($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        echo $reply_response=curl_exec($ch); 
    }

    
    public function every_day($api_key="")
    {
        $link=base_url().'cron_job/membership_alert/'.$api_key;
        $this->call_curl_internal_cronjob($link);

        $link=base_url().'cron_job/delete_junk_data/'.$api_key;
        $this->call_curl_internal_cronjob($link);
    }

    
    public function every_2_minutes($api_key="")
    {
        $link=base_url().'cron_job/auto_reply_campaign_complete_cron_job/'.$api_key;
        $this->call_curl_internal_cronjob($link);

        $link=base_url().'cron_job/video_upload_to_youtube/'.$api_key;
        $this->call_curl_internal_cronjob($link);
    }

    
    public function every_5_minutes($api_key="")
    {  

        $link=base_url().'cron_job/auto_like_comment_campaign_prepare_cron_job/'.$api_key;
        $this->call_curl_internal_cronjob($link);

        $link=base_url().'cron_job/auto_like_comment_complete_cron_job/'.$api_key;
        $this->call_curl_internal_cronjob($link);

        $link=base_url().'cron_job/auto_channel_subscription_prepare_cron_job/'.$api_key;
        $this->call_curl_internal_cronjob($link);

        $link=base_url().'cron_job/auto_channel_subscription_complete_cron_job/'.$api_key;
        $this->call_curl_internal_cronjob($link);
    }

    
    public function every_10_minutes($api_key="")
    {
        $link=base_url().'cron_job/auto_reply_campaign_prepare_cron_job/'.$api_key;
        $this->call_curl_internal_cronjob($link);

        $link=base_url().'cron_job/get_keyword_position_data/'.$api_key;
        $this->call_curl_internal_cronjob($link);

        $link=base_url().'cron_job/video_link_wheel/'.$api_key;
        $this->call_curl_internal_cronjob($link);
    }


      
    public function membership_alert($api_key="") //membership_alert_delete_junk_data
    {
        $this->api_key_check($api_key);    

        $free_package_info = $this->basic->get_data('package',['where'=>['price'=>'0','validity'=>'0','is_default'=>'1']]);
        $free_package_id = isset($free_package_info[0]['id']) ? $free_package_info[0]['id'] : 0;

        $current_date = date("Y-m-d");
        $tenth_day_before_expire = date("Y-m-d", strtotime("$current_date + 10 days"));
        $one_day_before_expire = date("Y-m-d", strtotime("$current_date + 1 days"));
        $one_day_after_expire = date("Y-m-d", strtotime("$current_date - 1 days"));

        //send notification to members before 10 days of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $tenth_day_before_expire,
            'package_id !=' => $free_package_id
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');

        // getting email template info
        $email_template_info = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_10_days_before')),array('subject','message'));

        if(isset($email_template_info[0]) && $email_template_info[0]['subject'] !='' && $email_template_info[0]['message'] !='') {

            $subject = $email_template_info[0]['subject'];
            foreach ($info as $value) 
            {
                if(!$this->api_member_validity($value['id'])) continue;
                $url = base_url();

                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value['name'],$url,$mask),$email_template_info[0]['message']);

                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }


        } else {

            $subject = "Payment Notification";
            foreach ($info as $value) 
            {
                if(!$this->api_member_validity($value['id'])) continue;
                $message = "Dear {$value['name']},<br/> your account will expire after 10 days, Please pay your fees.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        }

        //send notificatio to members before 1 day of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $one_day_before_expire,
            'package_id !=' => $free_package_id
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');

        // getting email template info
        $email_template_info_01 = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_1_day_before')),array('subject','message'));

        if(isset($email_template_info_01[0]) && $email_template_info_01[0]['subject'] != '' && $email_template_info_01[0]['message'] != '') {

            $subject = $email_template_info_01[0]['subject'];
            foreach ($info as $value) {
                if(!$this->api_member_validity($value['id'])) continue;
                $url = base_url();
                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value['name'],$url,$mask),$email_template_info_01[0]['message']);

                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        }
        else {

            $subject = "Payment Notification";
            foreach ($info as $value) {
                if(!$this->api_member_validity($value['id'])) continue;
                $message = "Dear {$value['name']},<br/> your account will expire tomorrow, Please pay your fees.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        }
        

        //send notificatio to members after 1 day of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $one_day_after_expire,
            'package_id !=' => $free_package_id
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');

        $email_template_info_02 = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_1_day_after')),array('subject','message'));

        if(isset($email_template_info_02[0]) && $email_template_info_02[0]['subject'] != '' && $email_template_info_02[0]['message'] != '') {

            $subject = $email_template_info_02[0]['subject'];

            foreach ($info as $value) {
                if(!$this->api_member_validity($value['id'])) continue;
                $url = base_url();
                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value['name'],$url,$mask),$email_template_info_02[0]['message']);
                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        } else {

            $subject = "Payment Notification";
            foreach ($info as $value) {
                if(!$this->api_member_validity($value['id'])) continue;
                $message = "Dear {$value['name']},<br/> your account has been expired, Please pay your fees for continuity.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
                $to = $value['email'];
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }
        }        

    }


    //once a day
    public function delete_junk_data($api_key="")
    {
       $this->api_key_check($api_key);
       /****Clean Cache Directory , keep all files of last 24 hours******/
       $all_cache_file=$this->delete_cache('application/cache');
       $all_cache_file=$this->delete_cache('download/youtube');
    }


    private function delete_cache($myDir)
    {

        $cur_time=date('Y-m-d H:i:s');
        $yesterday=date("Y-m-d H:i:s",strtotime($cur_time." -1 day"));
        $yesterday=strtotime($yesterday);


        $dirTree = array();
        $di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);
        
        foreach (new RecursiveIteratorIterator($di) as $filename) {
        
        $dir = str_replace($myDir, '', dirname($filename));
        
        $org_dir=str_replace("\\", "/", $dir);
        
        
        if($org_dir)
        $file_path = $org_dir. "/". basename($filename);
        else
        $file_path = basename($filename);

        $path_explode = explode(".",$file_path);
        $extension= array_pop($path_explode);

        if($file_path!='.htaccess' && $file_path!='index.html'){

             $full_file_path=$myDir."/".$file_path;

             $file_creation_time=filemtime($full_file_path);
             $file_creation_time=date('Y-m-d H:i:s',$file_creation_time); //convert unix time to system time zone 
             $file_creation_time=strtotime($file_creation_time);


             if($file_creation_time<$yesterday){
                $dirTree[] = trim($file_path,"/");
                unlink($full_file_path);

             }
                
        }

        
        }
        
        return $dirTree;
            
    }


    private function mb_string_function($input="")
    {
        if(function_exists('iconv') && function_exists('mb_detect_encoding'))
        {
            $encoded_word =  mb_detect_encoding($input);
            if(isset($encoded_word)) $input = @iconv( $encoded_word, "UTF-8//TRANSLIT", $input );            
        }
        return $input;
    }



    public function auto_reply_campaign_prepare_cron_job($api_key = '') 
    {       
        $this->api_key_check($api_key);
        $no_of_campaign_per_cron_job_comment_auto_reply = !empty($this->config->item('no_of_campaign_per_cron_job_comment_auto_reply')) ? $this->config->item('no_of_campaign_per_cron_job_comment_auto_reply') : 5;
        $no_of_old_comment_to_reply_comment_auto_reply = !empty($this->config->item('no_of_old_comment_to_reply_comment_auto_reply')) ? $this->config->item('no_of_old_comment_to_reply_comment_auto_reply') : 20;

        $last_processed_date = date("Y-m-d H:i:s");
        $now_day_default = date("Y-m-d");

        $campaign_data = $this->basic->get_data("auto_reply_campaign",array("where"=>array("auto_reply_campaign.status"=>"0")),array("auto_reply_campaign.*","users.time_zone","users.status as user_status"),array('users'=>"users.id=auto_reply_campaign.user_id,left"),$no_of_campaign_per_cron_job_comment_auto_reply,"","last_processed_date ASC");
        $campaigns_data_proccess = array();
        $campaigns_id_proccess = array();
        $campaign_id_completed = array();

        foreach($campaign_data as $key => $value)
        {
            if($value['user_status']=="0")
            {
                $campaign_id_completed[] = $value['id'];
                continue;
            }

            $campaigns_data_proccess[] = $value;
            $campaigns_id_proccess[] = $value['id'];            
        }

        // this campaigns has been completed
        if(!empty($campaign_id_completed))
        {
            $this->db->where_in("id",$campaign_id_completed);
            $this->db->update("auto_reply_campaign",array("status"=>'2'));
        }

        if(empty($campaigns_id_proccess)) exit();
        
        // mark this campaigns as processing
        $this->db->where_in("id",$campaigns_id_proccess);
        $this->db->update("auto_reply_campaign",array("status"=>'1','last_processed_date'=>$last_processed_date));

        foreach ($campaigns_data_proccess as $key => $value)
        {


            $youtube_api_called_at = $value['youtube_api_called_at'];
            $called_at = date('d-m-Y H:i:s', strtotime($youtube_api_called_at));
            $called_at = strtotime($called_at) + (2 * 60 * 60);

            if ($called_at < time()){
                
                $this->basic->update_data("auto_reply_campaign",array("id"=>$value["id"]),array("youtube_api_called_at"=>$last_processed_date));
            }

            else{
                    continue;
            }

                 

            try 
            {
                $params['youtube_channel_info_table_id'] = $value['channel_auto_id'];
                $this->load->library('youtube_library',$params);
                $this->youtube_library->initializeGclient($params);
                
            } 
            catch (Exception $e) 
            {
                $error_message =  $e->getMessage();
                $this->basic->update_data("auto_reply_campaign",array("id"=>$value["id"]),array("status"=>'0',"error"=>$error_message,"youtube_api_called_at"=>$last_processed_date));
                continue;
            }

            $auto_reply_campaign_prepared_entry = array();
            
            if(!empty($value['video_id']))
            {
                          
                $video_comments = $this->youtube_library->get_video_comment($value['video_id']);
                if(isset($video_comments['error']))
                {
                    $error_message = $video_comments["message"];
                    $this->basic->update_data("auto_reply_campaign",array("id"=>$value["id"]),array("status"=>'0',"error"=>$error_message,"youtube_api_called_at"=>$last_processed_date));
                    continue;
                }
              
                $previous_campaign_comment_ids = array();
                $previous_campaign_comment_authors = array();
                $previous_campaign_data = $this->basic->get_data("auto_reply_campaign_report",array("where"=>array("auto_reply_campaign_table_id"=>$value["id"])),"comment_id,comment_author");
                if(!empty($previous_campaign_data)) 
                {
                    $previous_campaign_comment_ids = array_column($previous_campaign_data, "comment_id");
                    $previous_campaign_comment_authors = array_column($previous_campaign_data, "comment_author");
                }

                $reply_count=0;
                if (is_object($video_comments) && isset($video_comments->items))
                foreach ($video_comments->items as $video_comment)
                {                       
                    $comment_id = isset($video_comment['snippet']['topLevelComment']['id']) ? $video_comment['snippet']['topLevelComment']['id']  : "";
                    $comment_author = isset($video_comment['snippet']['topLevelComment']['snippet']['authorDisplayName']) ? $video_comment['snippet']['topLevelComment']['snippet']['authorDisplayName'] : "";
                    if(!empty($comment_id) && !empty($comment_author) && !in_array($comment_id, $previous_campaign_comment_ids))
                    {                       
                        if($value["multiple_reply"]=="0" && in_array($comment_author, $previous_campaign_comment_authors)) continue;

                        $temp = array();
                        $comment_text = isset($video_comment['snippet']['topLevelComment']['snippet']['textDisplay']) ? $video_comment['snippet']['topLevelComment']['snippet']['textDisplay'] : "";
                        $comment_author_avatar = isset($video_comment['snippet']['topLevelComment']['snippet']['authorProfileImageUrl']) ? $video_comment['snippet']['topLevelComment']['snippet']['authorProfileImageUrl'] : "";
                        $comment_author_channel_id = isset($video_comment['snippet']['topLevelComment']['snippet']['authorChannelId']['value']) ? $video_comment['snippet']['topLevelComment']['snippet']['authorChannelId']['value'] : "";

                        if($value["channel_id"] == $comment_author_channel_id) continue;

                        $temp['user_id'] =  $value['user_id'];
                        $temp['channel_auto_id'] =  $value['channel_auto_id'];
                        $temp['auto_reply_campaign_table_id'] = $value['id'];
                        $temp['comment_id'] = $comment_id;
                        $temp['comment_author'] = $comment_author;
                        $temp['comment_text'] = $comment_text;
                        $temp['comment_author_avatar'] = $comment_author_avatar;
                        $temp['comment_author_channel_id'] = $comment_author_channel_id;

                        $offensive_words = explode(',', $value['offensive_words']);                        
                        $is_offensive='0';


                        if($value['delete_offensive_comment'] == '1' && !empty($offensive_words))
                        {
                            foreach ($offensive_words as $offensive_word)
                            {
                                $temp_comment_text = $this->mb_string_function($comment_text);
                                $temp_offensive_word = $this->mb_string_function($offensive_word);
                                if (stripos($temp_comment_text, $temp_offensive_word) !== false) 
                                {
                                    $is_offensive='1';
                                    break;
                                }
                            }
                        }

                        $reply_to_be_given = "";
                        if($is_offensive=='0')
                        {
                            if ($value['reply_type'] == 'generic') $reply_to_be_given = $value['generic_reply_message'];                            
                            else if ($value['reply_type'] == 'filter')
                            {
                                $filter_messages = json_decode($value['filter_reply_message'], true);
                                $has_matched = false;

                                foreach ($filter_messages as $filter_message)
                                {                                    
                                    $temp_filter_message = $this->mb_string_function($filter_message['word']);
                                    $temp_comment_text = $this->mb_string_function( $comment_text);
                                    
                                    if (stripos($temp_comment_text, $temp_filter_message) !== false)
                                    {
                                        $reply_to_be_given = $filter_message['message'];
                                        $has_matched = true;
                                        break;
                                    }
                                }                                                                   

                                if ($has_matched == false) $reply_to_be_given = $value['filter_no_match_message'];
                            }
                                
                        }
                        
                        $temp['is_offensive'] = $is_offensive;
                        $temp['reply_to_be_given'] = !empty($reply_to_be_given) ? spintax_process($reply_to_be_given) : "";
                        $auto_reply_campaign_prepared_entry[] = $temp;
                        $reply_count++;
                        if($reply_count>=$no_of_old_comment_to_reply_comment_auto_reply) break;
                    }
                }
            }

            if(!empty($auto_reply_campaign_prepared_entry)) $this->db->insert_batch('auto_reply_campaign_report', $auto_reply_campaign_prepared_entry);
        }

        // mark this campaigns as pending again
        $this->db->where_in("id",$campaigns_id_proccess);
        $this->db->update("auto_reply_campaign",array("status"=>'0'));

        
    }


    public function auto_reply_campaign_complete_cron_job($api_key='')
    {
        $this->api_key_check($api_key);
        
        $no_of_reply_per_cron_job_comment_auto_reply = !empty($this->config->item('no_of_reply_per_cron_job_comment_auto_reply')) ? $this->config->item('no_of_reply_per_cron_job_comment_auto_reply') : 5;
        $delay_between_reply_comment_auto_reply = $this->config->item('delay_between_reply_comment_auto_reply');
        if($delay_between_reply_comment_auto_reply=='' || $delay_between_reply_comment_auto_reply=='0') $delay_between_reply_comment_auto_reply = rand ( 5 , 20 );
        
        
        $campaign_data = $this->basic->get_data('auto_reply_campaign_report', array('where' => array('status' => '0')), '', '', $no_of_reply_per_cron_job_comment_auto_reply,"",'id ASC');

        $campaigns_id_proccess = array();

        foreach ($campaign_data as $value)
        {
            array_push($campaigns_id_proccess, $value['id']);
        }

        if(count($campaigns_id_proccess) > 0)
        {
            $this->db->where_in('id', $campaigns_id_proccess);
            $this->basic->update_data('auto_reply_campaign_report', '', array('status' => '1'));
        }
        else exit;

        foreach ($campaign_data as $value)
        {  
            try 
            {
                $params['youtube_channel_info_table_id'] = $value['channel_auto_id'];
                $this->load->library('youtube_library',$params);
                $this->youtube_library->initializeGclient($params);
                
            } 
            catch (Exception $e) 
            {
                $error_message =  $e->getMessage();
                $this->basic->update_data('auto_reply_campaign_report',array("id"=>$value["id"]), array('status' => '2',"error"=>$error_message));
                continue;
            }

            $comment_id = $value['comment_id'];

            if($value["is_offensive"]=="0")
            {   
                if(isset($now_campaign_id) && $now_campaign_id==$value["auto_reply_campaign_table_id"])
                {
                    sleep($delay_between_reply_comment_auto_reply);
                }
                $now_campaign_id = $value["auto_reply_campaign_table_id"];

                $results = $this->youtube_library->create_comment_reply($comment_id, $value['reply_to_be_given']);
                
                if(isset($results['error']))
                {
                   $this->basic->update_data('auto_reply_campaign_report',array("id"=>$value["id"]), array('status' => '2',"error"=>$results["message"]));
                   continue;
                }
            }
            else
            {                

                $results = $this->youtube_library->delete_video_comment_other($comment_id);
                if(isset($results['error']))
                {
                    $error_message = $results["message"];
                    $this->basic->update_data('auto_reply_campaign_report',array("id"=>$value["id"]), array('status' => '2',"error"=>$error_message));
                    continue;             
                }
                
            }

        }
        
        $this->db->where_in('id', $campaigns_id_proccess);
        $this->basic->update_data('auto_reply_campaign_report', '', array('status' => '2',"replied_at"=>date("Y-m-d H:i:s")));        
        
    }





    public function auto_like_comment_campaign_prepare_cron_job($api_key = '') 
    {       
        $this->api_key_check($api_key);
        $campaign_check_per_cron= 20;
        $campaign_process_per_cron = 5;
        $last_processed_date = date("Y-m-d");
        $now_day_default = date("Y-m-d");

        $campaign_data = $this->basic->get_data("auto_like_comment",array("where"=>array("auto_like_comment.status"=>"0","last_processed_date !="=>$last_processed_date)),array("auto_like_comment.*","users.time_zone","users.status as user_status"),array('users'=>"users.id=auto_like_comment.user_id,left"),$campaign_check_per_cron,"","last_processed_date ASC");
        $campaigns_data_proccess = array();
        $campaigns_id_proccess = array();
        $campaign_id_completed = array();
        $count_campaign = 0;

        foreach($campaign_data as $key => $value)
        {
            $use_timezone = ($value['time_zone']!='') ? $value['time_zone'] : $this->config->item("time_zone");
            if($use_timezone=="") $use_timezone = "Europe/Dublin";
            if($use_timezone!="") date_default_timezone_set($use_timezone);

            $now_day = date("Y-m-d");
            $now_time = date("Y-m-d H:i:s");

            $expire_type = $value['expire_type'];

            $have_to_complete = false;
            if($expire_type=='no_of_activity' && $value['total_activity']>=$value['campaign_expire_max_activity']) $have_to_complete = true;
            else if($expire_type=='date' && strtotime($now_time) >= strtotime($value['expire_date'])) $have_to_complete = true;

            if($value['user_status']=="0" || $have_to_complete)
            {
                $campaign_id_completed[] = $value['id'];
                continue;
            }
            
            if(strtotime($value['last_processed_date']) < strtotime($now_day_default))
            {
                $campaigns_data_proccess[] = $value;
                $campaigns_id_proccess[] = $value['id'];
                $count_campaign++;
                if($count_campaign>=$campaign_process_per_cron) break;
            }
        }

        // this campaigns has been completed
        if(!empty($campaign_id_completed))
        {
            $this->db->where_in("id",$campaign_id_completed);
            $this->db->update("auto_like_comment",array("status"=>'2'));
        }


        if(empty($campaigns_id_proccess)) exit();
        
        // mark this campaigns as processing
        $this->db->where_in("id",$campaigns_id_proccess);
        $this->db->update("auto_like_comment",array("status"=>'1','last_processed_date'=>$last_processed_date));

        foreach ($campaigns_data_proccess as $key => $value)
        {
            $use_timezone = ($value['time_zone']!='') ? $value['time_zone'] : $this->config->item("time_zone");
            if($use_timezone=="") $use_timezone = "Europe/Dublin";
            if($use_timezone!="") date_default_timezone_set($use_timezone);

            $now_day = date("Y-m-d");
            $now_time = date("Y-m-d H:i:s");
            $scheduled_at = $now_time;

            $total_activity = $value['total_activity'];
            $expire_type = $value['expire_type'];
            $max_activity_per_day = $value['max_activity_per_day'];
            if(empty($max_activity_per_day) || $max_activity_per_day==0) $max_activity_per_day = 1;

            if($expire_type=='no_of_activity')
            {
                if($total_activity+$max_activity_per_day > $value['campaign_expire_max_activity'])
                $max_activity_per_day = $value['campaign_expire_max_activity'] - $total_activity; 
            }
            $minutes_interval = round(1400/$max_activity_per_day);

            try 
            {
                $params['youtube_channel_info_table_id'] = $value['channel_auto_id'];
                $this->load->library('youtube_library',$params);
                $this->youtube_library->initializeGclient($params);
                
            } 
            catch (Exception $e) 
            {
                $error_message =  $e->getMessage();
                $this->basic->update_data("auto_like_comment",array("id"=>$value["id"]),array("status"=>'0',"error"=>$error_message));
                continue;
            }

            $auto_like_comment_campaign_prepared_entry = array();
            $keyword_use = $channel_use = "";
            if($value["keyword_or_channel"] == "keyword")
            {
                $keywords = $value["keywords"];
                $exp = explode(',', $keywords);
                if(count($exp)>1)
                {
                    $pos = array_search($value["last_used_keyword"], $exp);
                    if($pos) unset($exp[$pos]);
                }
                if(!empty($exp))
                {
                    $rnd = array_rand($exp);
                    $keyword_use = $exp[$rnd];
                }       

            }
            else if($value["keyword_or_channel"] == "channel")
            {
                $channels = $value["channels"];
                $exp = explode(',', $channels);
                if(count($exp)>1)
                {
                    $pos = array_search($value["last_used_channel"], $exp);
                    if($pos) unset($exp[$pos]);
                }
                if(!empty($exp))
                {
                    $rnd = array_rand($exp);
                    $channel_use = $exp[$rnd];
                }
            }

            if(!empty($exp) && ($keyword_use!="" || $channel_use!=""))
            {
                $search_limit = ($max_activity_per_day<50) ? 50 : $max_activity_per_day;                

                $results = $this->youtube_library->get_youtube_video($keyword_use,$search_limit,$channel_use,"","","date");
                if(isset($results['error']))
                {
                    $error_message = $results["message"];
                    $this->basic->update_data("auto_like_comment",array("id"=>$value["id"]),array("status"=>'0',"error"=>$error_message));
                    continue;
                }

                $count_result=0;
                shuffle($results);
                $previous_campaign_videos = array();
                $previous_campaign_data = $this->basic->get_data("auto_like_comment_campaign_prepared",array("where"=>array("campaign_id"=>$value["id"])),"video_id");
                if(!empty($previous_campaign_data)) $previous_campaign_videos = array_column($previous_campaign_data, "video_id");
                foreach ($results as $result)
                {                       
                    if(isset($result['video_id']) && !in_array($result['video_id'], $previous_campaign_videos))
                    {
                        $minutes_interval_randomize = $minutes_interval + rand ( 2 , 10 );
                        $scheduled_at = date('Y-m-d H:i:s',strtotime('+'.$minutes_interval_randomize.' minutes',strtotime($scheduled_at)));
                        $temp = array();
                        $temp['user_id'] =  $value['user_id'];
                        $temp['channel_auto_id'] =  $value['channel_auto_id'];
                        $temp['campaign_id'] = $value['id'];
                        $temp['video_id'] = $result['video_id'];
                        $temp['auto_comment_template_id'] = $value['auto_comment_template_id'];
                        $temp['auto_like'] = $value['auto_like'];
                        $temp['status'] = '0';
                        $temp['scheduled_at'] = $scheduled_at;
                        $temp['time_zone'] = $use_timezone;
                        $auto_like_comment_campaign_prepared_entry[] = $temp;
                        $count_result++;
                    }
                    if($count_result>=$max_activity_per_day) break;
                }
                if($count_result==0)
                {
                    $error_message = $this->lang->line("No new video found to continue campaign.");
                    $this->basic->update_data("auto_like_comment",array("id"=>$value["id"]),array("status"=>'2',"error"=>$error_message));
                    continue;
                }

                // mark this campaigns as pending again
                $total_activity_new = $value["total_activity"]+$count_result;
                $this->basic->update_data("auto_like_comment",array("id"=>$value["id"]),array("status"=>'0',"last_used_keyword"=>$keyword_use,"last_used_channel"=>$channel_use,"total_activity"=>$total_activity_new));
            }

            if(!empty($auto_like_comment_campaign_prepared_entry)) $this->db->insert_batch('auto_like_comment_campaign_prepared', $auto_like_comment_campaign_prepared_entry);
        }      

        
    }


    public function auto_like_comment_complete_cron_job($api_key = '')
    {
        $this->api_key_check($api_key);
        $campaign_check_per_cron= 20;
        $campaign_process_per_cron = 5;
        
        $campaign_data = $this->basic->get_data('auto_like_comment_campaign_prepared', array('where' => array('status' => '0')), array("auto_like_comment_campaign_prepared.*","auto_comment_templete.comment_text"),array('auto_comment_templete'=>"auto_comment_templete.id=auto_like_comment_campaign_prepared.auto_comment_template_id,left"), $campaign_check_per_cron,"","scheduled_at ASC");

        $campaigns_data_proccess = array();
        $campaigns_id_proccess = array();
        $count_campaign = 0;

        foreach($campaign_data as $key => $value)
        {
            $use_timezone = ($value['time_zone']!='') ? $value['time_zone'] : $this->config->item("time_zone");
            if($use_timezone=="") $use_timezone = "Europe/Dublin";
            if($use_timezone!="") date_default_timezone_set($use_timezone);

            $now_day = date("Y-m-d");
            $now_time = date("Y-m-d H:i:s");

            if(strtotime($value['scheduled_at']) <= strtotime($now_time))
            {
                $campaigns_data_proccess[] = $value;
                $campaigns_id_proccess[] = $value['id'];
                $count_campaign++;
                if($count_campaign>=$campaign_process_per_cron) break;
            }
        }


        if(empty($campaigns_id_proccess)) exit();

        // mark this campaigns as processing
        $this->db->where_in("id",$campaigns_id_proccess);
        $this->db->update("auto_like_comment_campaign_prepared",array("status"=>'1'));

        foreach ($campaigns_data_proccess as $key => $value)
        {
            $use_timezone = ($value['time_zone']!='') ? $value['time_zone'] : $this->config->item("time_zone");
            if($use_timezone=="") $use_timezone = "Europe/Dublin";
            if($use_timezone!="") date_default_timezone_set($use_timezone);

            $comment_text = json_decode($value['comment_text'], true);            
            if(empty($comment_text)) 
            {
                $this->basic->update_data('auto_like_comment_campaign_prepared', array('id' => $value["id"]), array("error"=>$this->lang->line("Comment text not found.")));
                continue;
            }
            shuffle($comment_text);
            $comment_text_use = array_pop($comment_text);

            try 
            {
                $params['youtube_channel_info_table_id'] = $value['channel_auto_id'];
                $this->load->library('youtube_library',$params);
                $this->youtube_library->initializeGclient($params);
                
            } 
            catch (Exception $e) 
            {
                $error_message =  $e->getMessage();
                $this->basic->update_data('auto_like_comment_campaign_prepared', array('id' => $value['id']), array('status' => '2',"error"=>$error_message));
                continue;
            }

            $comment_text_use = spintax_process($comment_text_use);
            $results = $this->youtube_library->create_video_comment($value["video_id"],$comment_text_use);            
            if(!isset($results['error']))
            {                
                $temp_comment_result = array
                (
                    'status' => '2',
                    'comment_id' => $results->snippet['topLevelComment']['id'],
                    'author' => $results->snippet['topLevelComment']['snippet']['authorDisplayName'],
                    'comment_text' => $results->snippet['topLevelComment']['snippet']['textDisplay'],
                );
                $temp_comment_result['published_at'] = substr($results->snippet['topLevelComment']['snippet']['publishedAt'], 0, 19);
                $temp_comment_result['published_at'] = str_replace('T', ' ', $temp_comment_result['published_at']);              
                $this->basic->update_data("auto_like_comment_campaign_prepared",array("id"=>$value["id"]),$temp_comment_result);                            

                /* like a video */
                if($value['auto_like']=='1')
                {
                    $results2 = $this->youtube_library->video_like_dislike($value["video_id"], 'like');             
                    $error_message = $results2['message'];
                    if(isset($results2['error']))
                    {
                        $this->basic->update_data('auto_like_comment_campaign_prepared', array('id' => $value["id"]), array("error"=>$error_message));
                    }
                }
            }
            else
            {
                $error_message = $results['message'];
                $this->basic->update_data('auto_like_comment_campaign_prepared', array('id' => $value['id']), array('status' => '2',"error"=>$error_message));
            }

        }

        // mark this campaigns as complete
        $this->db->where_in("id",$campaigns_id_proccess);
        $this->db->update("auto_like_comment_campaign_prepared",array("status"=>'2'));
        
    }


    public function auto_channel_subscription_prepare_cron_job($api_key = '') 
    {       
        $this->api_key_check($api_key);
        $campaign_check_per_cron= 20;
        $campaign_process_per_cron = 5;
        $last_processed_date = date("Y-m-d");
        $now_day_default = date("Y-m-d");

        $campaign_data = $this->basic->get_data("auto_channel_subscription",array("where"=>array("auto_channel_subscription.status"=>"0","last_processed_date !="=>$last_processed_date)),array("auto_channel_subscription.*","users.time_zone","users.status as user_status"),array('users'=>"users.id=auto_channel_subscription.user_id,left"),$campaign_check_per_cron,"","last_processed_date ASC");
        $campaigns_data_proccess = array();
        $campaigns_id_proccess = array();
        $campaign_id_completed = array();
        $count_campaign = 0;

        foreach($campaign_data as $key => $value)
        {
            $use_timezone = ($value['time_zone']!='') ? $value['time_zone'] : $this->config->item("time_zone");
            if($use_timezone=="") $use_timezone = "Europe/Dublin";
            if($use_timezone!="") date_default_timezone_set($use_timezone);

            $now_day = date("Y-m-d");
            $now_time = date("Y-m-d H:i:s");

            $expire_type = $value['expire_type'];

            $have_to_complete = false;
            if($expire_type=='no_of_activity' && $value['total_activity']>=$value['campaign_expire_max_activity']) $have_to_complete = true;
            else if($expire_type=='date' && strtotime($now_time) >= strtotime($value['expire_date'])) $have_to_complete = true;

            if($value['user_status']=="0" || $have_to_complete)
            {
                $campaign_id_completed[] = $value['id'];
                continue;
            }
            
            if(strtotime($value['last_processed_date']) < strtotime($now_day_default))
            {
                $campaigns_data_proccess[] = $value;
                $campaigns_id_proccess[] = $value['id'];
                $count_campaign++;
                if($count_campaign>=$campaign_process_per_cron) break;
            }
        }

        // this campaigns has been completed
        if(!empty($campaign_id_completed))
        {
            $this->db->where_in("id",$campaign_id_completed);
            $this->db->update("auto_channel_subscription",array("status"=>'2'));
        }

        if(empty($campaigns_id_proccess)) exit();
        
        // mark this campaigns as processing
        $this->db->where_in("id",$campaigns_id_proccess);
        $this->db->update("auto_channel_subscription",array("status"=>'1','last_processed_date'=>$last_processed_date));

        foreach ($campaigns_data_proccess as $key => $value)
        {
            $use_timezone = ($value['time_zone']!='') ? $value['time_zone'] : $this->config->item("time_zone");
            if($use_timezone=="") $use_timezone = "Europe/Dublin";
            if($use_timezone!="") date_default_timezone_set($use_timezone);

            $now_day = date("Y-m-d");
            $now_time = date("Y-m-d H:i:s");
            $scheduled_at = $now_time;

            $total_activity = $value['total_activity'];
            $expire_type = $value['expire_type'];
            $max_activity_per_day = $value['max_activity_per_day'];
            if(empty($max_activity_per_day) || $max_activity_per_day==0) $max_activity_per_day = 1;

            if($expire_type=='no_of_activity')
            {
                if($total_activity+$max_activity_per_day > $value['campaign_expire_max_activity'])
                $max_activity_per_day = $value['campaign_expire_max_activity'] - $total_activity; 
            }
            $minutes_interval = round(1400/$max_activity_per_day);

            try 
            {
                $params['youtube_channel_info_table_id'] = $value['channel_auto_id'];
                $this->load->library('youtube_library',$params);
                $this->youtube_library->initializeGclient($params);
                
            } 
            catch (Exception $e) 
            {
                $error_message =  $e->getMessage();
                $this->basic->update_data("auto_channel_subscription",array("id"=>$value["id"]),array("status"=>'0',"error"=>$error_message));
                continue;
            }

            $auto_channel_subscription_prepared_entry = array();
            $keyword_use = "";
           
            $keywords = $value["keywords"];
            $exp = explode(',', $keywords);
            if(count($exp)>1)
            {
                $pos = array_search($value["last_used_keyword"], $exp);
                if($pos) unset($exp[$pos]);
            }
            if(!empty($exp))
            {
                $rnd = array_rand($exp);
                $keyword_use = $exp[$rnd];
            }           

            if(!empty($exp) && $keyword_use!="")
            {
                $search_limit = ($max_activity_per_day<50) ? 50 : $max_activity_per_day;

                $previous_date = '';
                $after_date = '';

                if ($value['date_range'] != '')
                {
                    $date_range = explode('|', $value['date_range']);
                    $previous_date = isset($date_range[0]) ? $date_range[0] : "";
                    $after_date = isset($date_range[1]) ? $date_range[1] : "";
                }
                $results = $this->youtube_library->get_youtube_channel($keyword_use,$search_limit,'', '', '', $previous_date, $after_date);
                if(isset($results['error']))
                {
                    $error_message = $results["message"];
                    $this->basic->update_data("auto_channel_subscription",array("id"=>$value["id"]),array("status"=>'0',"error"=>$error_message));
                    continue;
                }

                $count_result=0;
                shuffle($results);
                $previous_campaign_channels = array();
                $previous_campaign_data = $this->basic->get_data("auto_channel_subscription_prepared",array("where"=>array("campaign_id"=>$value["id"])),"targeted_channel_id");
                if(!empty($previous_campaign_data)) $previous_campaign_channels = array_column($previous_campaign_data, "targeted_channel_id");
                foreach ($results as $result)
                {                       
                    if(isset($result['channel_id']) && !in_array($result['channel_id'], $previous_campaign_channels))
                    {
                        $minutes_interval_randomize = $minutes_interval + rand ( 2 , 10 );
                        $scheduled_at = date('Y-m-d H:i:s',strtotime('+'.$minutes_interval_randomize.' minutes',strtotime($scheduled_at)));
                        $temp = array();
                        $temp['user_id'] =  $value['user_id'];
                        $temp['channel_auto_id'] =  $value['channel_auto_id'];
                        $temp['campaign_id'] = $value['id'];
                        $temp['targeted_channel_id'] = $result['channel_id'];
                        $temp['status'] = '0';
                        $temp['scheduled_at'] = $scheduled_at;
                        $temp['time_zone'] = $use_timezone;
                        $auto_channel_subscription_prepared_entry[] = $temp;
                        $count_result++;
                    }
                    if($count_result>=$max_activity_per_day) break;
                }
                if($count_result==0)
                {
                    $error_message = $this->lang->line("No new channel found to continue campaign.");
                    $this->basic->update_data("auto_channel_subscription",array("id"=>$value["id"]),array("status"=>'2',"error"=>$error_message));
                    continue;
                }

                // mark this campaigns as pending again
                $total_activity_new = $value["total_activity"]+$count_result;
                $this->basic->update_data("auto_channel_subscription",array("id"=>$value["id"]),array("status"=>'0',"last_used_keyword"=>$keyword_use,"total_activity"=>$total_activity_new));
            }

            if(!empty($auto_channel_subscription_prepared_entry)) $this->db->insert_batch('auto_channel_subscription_prepared', $auto_channel_subscription_prepared_entry);
        }      

        
    }


    public function auto_channel_subscription_complete_cron_job($api_key = '')
    {
        $this->api_key_check($api_key);
        $campaign_check_per_cron= 20;
        $campaign_process_per_cron = 5;
        
        $campaign_data = $this->basic->get_data('auto_channel_subscription_prepared', array('where' => array('status' => '0')),"","", $campaign_check_per_cron,"","scheduled_at ASC");

        $campaigns_data_proccess = array();
        $campaigns_id_proccess = array();
        $count_campaign = 0;

        foreach($campaign_data as $key => $value)
        {
            $use_timezone = ($value['time_zone']!='') ? $value['time_zone'] : $this->config->item("time_zone");
            if($use_timezone=="") $use_timezone = "Europe/Dublin";
            if($use_timezone!="") date_default_timezone_set($use_timezone);

            $now_day = date("Y-m-d");
            $now_time = date("Y-m-d H:i:s");

            if(strtotime($value['scheduled_at']) <= strtotime($now_time))
            {
                $campaigns_data_proccess[] = $value;
                $campaigns_id_proccess[] = $value['id'];
                $count_campaign++;
                if($count_campaign>=$campaign_process_per_cron) break;
            }
        }


        if(empty($campaigns_id_proccess)) exit();

        // mark this campaigns as processing
        $this->db->where_in("id",$campaigns_id_proccess);
        $this->db->update("auto_channel_subscription_prepared",array("status"=>'1'));

        foreach ($campaigns_data_proccess as $key => $value)
        {
            $use_timezone = ($value['time_zone']!='') ? $value['time_zone'] : $this->config->item("time_zone");
            if($use_timezone=="") $use_timezone = "Europe/Dublin";
            if($use_timezone!="") date_default_timezone_set($use_timezone);

            try 
            {
                $params['youtube_channel_info_table_id'] = $value['channel_auto_id'];
                $this->load->library('youtube_library',$params);
                $this->youtube_library->initializeGclient($params);
                
            } 
            catch (Exception $e) 
            {
                $error_message =  $e->getMessage();
                $this->basic->update_data('auto_channel_subscription_prepared', array('id' => $value['id']), array('status' => '2',"error"=>$error_message));
                continue;
            }

            $results = $this->youtube_library->subscribe_to_other_channel($value["targeted_channel_id"]);
            if(!isset($results['error']))
            {                
                $temp_comment_result = array
                (
                    'status' => '2',
                    "subscribed_id" => $results->id,
                    "subscription_status"=>"1",
                    'subscribed_at' => date("Y-m-d H:i:s")
                );             
                $this->basic->update_data("auto_channel_subscription_prepared",array("id"=>$value["id"]),$temp_comment_result);
            }
            else
            {
                $error_message = $results['message'];
                $this->basic->update_data('auto_channel_subscription_prepared', array('id' => $value['id']), array('status' => '2',"error"=>$error_message));
            }

        }

        // mark this campaigns as complete
        $this->db->where_in("id",$campaigns_id_proccess);
        $this->db->update("auto_like_comment_campaign_prepared",array("status"=>'2'));
        
    }
    


    public function video_upload_to_youtube($api_key="")
    {
        $this->api_key_check($api_key);  

        $where['where'] = array('upload_status'=>'0');
        $upload_video_list = $this->basic->get_data('youtube_video_upload',$where,"","",20,NULL,"upload_time ASC");
        
        foreach($upload_video_list as $value)
        {
            $channel_id = $value['channel_id'];
            $title = $value['title'];
            $description = $value['description'];
            $file_name = $value['link'];
            $tags = $value['tags'];
            $category = $value['category'];
            $privacy_type = $value['privacy_type'];
            $user_id = $value['user_id'];

            $time_zone = $value['time_zone'];
            date_default_timezone_set($time_zone);
            $upload_time = strtotime($value['upload_time']);

            $date_time = date("Y-m-d H:i:s",strtotime("+5 minutes"));
            $current_time = strtotime($date_time);            
          
            //upload video to youtube channel
            if($upload_time <= $current_time) {
                
                $this->basic->update_data('youtube_video_upload', array('id' => $value['id']), array('upload_status' => '1'));

                try 
                {
                    $params['youtube_channel_info_table_id'] = $value['channel_auto_id'];
                    $this->load->library('youtube_library',$params);
                    $this->youtube_library->initializeGclient($params);                
                } 
                catch (Exception $e) 
                {
                    // error loading libraray, complete the campaign (alamin)
                    $error_message =  $e->getMessage();
                    $this->basic->update_data('youtube_video_upload', array('id' => $value['id']), array('upload_status' => '2','error'=>$error_message));
                    continue;
                }

                $uploaded_video_status = $this->youtube_library->cronjob_upload_video_to_youtube($title,$description,$file_name,$tags,$category,$privacy_type);                 

                $error_found = isset($uploaded_video_status['error']) ? true : false;

                if(!$error_found)
                {                    
                    $uploaded_video_status['user_id'] = $value['user_id'];                
                    $uploaded_video_status['channel_auto_id'] = $value['channel_auto_id'];                
                    $data = array('upload_status'=>'2','video_id'=>$uploaded_video_status['video_id']);
                    $where = array('id'=>$value['id']);
                    $this->basic->update_data('youtube_video_upload',$where,$data);
                    $this->basic->insert_data('youtube_video_list',$uploaded_video_status);
                    $this->db->query("UPDATE youtube_channel_info SET video_count=video_count+1 WHERE id=".$value['channel_auto_id']);
                }
                else
                {                    
                    $error_message = isset($uploaded_video_status['message']) ? $uploaded_video_status['message'] : $this->lang->line("Something went wrong.");
                    $data = array('upload_status'=>'2','error'=>$error_message);
                    $where = array('id'=>$value['id']);
                    $this->basic->update_data('youtube_video_upload',$where,$data);
                }
                $file_location = $this->upload_path."/video/".$value['link'];
                unlink($file_location);

                if(!$error_found) break;
            }

            
        }
    }


    public function get_keyword_position_data($api_key="")
    {

        $this->api_key_check($api_key);
        
        /****** Video Tracking Code *******/
        $this->load->library('youtube_library');
        $keywords = $this->basic->get_data("video_position_set",$where=array("where"=>array("last_process_date !="=>date("Y-m-d"))),$select='',$join='',$limit=10,$start=NULL,$order_by='last_process_date ASC');
        
         foreach($keywords as $value){

            $keyword = $value['keyword'];
            $youtube_id=$value['youtube_video_id'];

        
            $keyword_position_youtube_data=$this->youtube_library->get_video_position($keyword,$youtube_id);
            $todate =  date("Y-m-d");
           
            $data = array
            (
                "keyword_id" => $value['id'],
                "youtube_position" => $keyword_position_youtube_data["position"],
                "date" => $todate
            );           
            $this->basic->insert_data("video_position_report",$data);
            $this->basic->update_data("video_position_set",array("id"=>$value['id']),array("last_process_date"=>$todate));

        }
                
    }


    public function video_link_wheel($api_key="")
    {               
        $this->api_key_check($api_key);
        $video_list=$this->basic->get_data("youtube_link_wheel",array("where"=>array("status"=>"0")),"","",$limit='1',$start=NULL,$order_by='id ASC');       

        foreach ($video_list as $key => $value)  // getting all pending wheels
        {
            $this->basic->update_data('youtube_link_wheel', array('id' => $value['id']), array('status' => '1'));
            try 
            {
                $params['youtube_channel_info_table_id'] = $value['channel_auto_id'];
                $this->load->library('youtube_library',$params);
                $this->youtube_library->initializeGclient($params);                  
            } 
            catch (Exception $e) 
            {
                // error loading libraray, complete the campaign (alamin)
                $error_message =  $e->getMessage();
                $this->basic->update_data('youtube_link_wheel', array('id' => $value['id']), array('status' => '2','error'=>$error_message));
                continue;
            }

            $user_id=$value["user_id"];     
            
            $wheel_id=$value["id"];
            $wheel_type=$value["wheel_type"];
            $money_video_ids=$value["money_video_id"];
            $video_ids=$value["video_ids"];
            $video_id_array=json_decode($video_ids,true);
            $money_video_id_array=json_decode($money_video_ids,true);
            $money_video_str="";
            foreach ($money_video_id_array as $key1 => $value1) 
            {
                if($value1!="")
                {
                   $money_video_str.="[lw:{$wheel_id}]https://www.youtube.com/watch?v={$value1}[/lw:{$wheel_id}]\n";
                }
            }
            $previous_video_str="";
            $count_video=count($video_id_array);           

            for($i=0;$i<$count_video;$i++)              
            {                           
                $main_str="";
                if($wheel_type=="close" && $i==0)
                {
                    $last_video=$video_id_array[$count_video-1];
                    $previous_video_str="[lw:{$wheel_id}]https://www.youtube.com/watch?v={$last_video}[/lw:{$wheel_id}]\n";
                }
                $main_str=$money_video_str.$previous_video_str;

                $previous_video_str="[lw:{$wheel_id}]https://www.youtube.com/watch?v={$video_id_array[$i]}[/lw:{$wheel_id}]\n";

                $response=0;
                
                $response=$this->youtube_library->update_video_info_wheel($video_id_array[$i],$main_str); 

                if($response=="1")
                {
                    $insert_data=array("video_id"=>$video_id_array[$i],"wheel_id"=>$wheel_id,"update_str"=>$main_str,"user_id"=>$user_id);
                    $this->basic->insert_data("youtube_link_wheel_log",$insert_data); // inserting log of generated wheel
                    $update_video_id=$video_id_array[$i];
                    $this->basic->execute_complex_query("UPDATE youtube_video_list SET description = CONCAT(description,' ','$main_str') WHERE video_id='$update_video_id'");
                }

            }

            $add_date=date("Y-m-d H:i:s");
            $this->basic->update_data("youtube_link_wheel",$where=array("id"=>$wheel_id),$update_data=array("status"=>"2","last_updated_at"=>$add_date));// marked wheel as completed              
        }

    }
    
}