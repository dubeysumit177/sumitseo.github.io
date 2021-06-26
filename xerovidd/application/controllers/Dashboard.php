<?php
require_once("Home.php"); 

class Dashboard extends Home
{
    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        $this->important_feature();
        $this->member_validity();     
    }


    public function index()
    {
        $this->dashboard();
    }

 
    public function dashboard()
    {

        /* comment reply */
        $query = $this->db->query("SELECT Date_Format(replied_at, '%d/%m/%Y') as ra, COUNT(*) as  totalresult FROM auto_reply_campaign_report WHERE user_id = ". $this->user_id ." GROUP BY ra");
        
        $comment_reply_stat = array();
        $total_comment_reply = 0;
        foreach ($query->result() as $single_stat) {
            
            $comment_reply_stat['date'][] = '"'.$single_stat->ra.'"';
            $comment_reply_stat['total'][] = '"'.$single_stat->totalresult.'"';
            $total_comment_reply += $single_stat->totalresult;
        }

        if (!isset($comment_reply_stat['date'])) {
            $comment_reply_stat['date'] = array();
        }
        if (!isset($comment_reply_stat['total'])) {
            $comment_reply_stat['total'] = array();
        }
        $comment_reply_stat['total_count'] = $total_comment_reply;
        $data['comment_reply_stat'] = $comment_reply_stat;



        /* subscription */
        $query = $this->db->query("SELECT Date_Format(`subscribed_at`, '%d/%m/%Y') as td, count(`id`) as  totalresult FROM `auto_channel_subscription_prepared` WHERE `user_id` = ". $this->user_id ." AND status='2' GROUP BY td");

        $subscription_stat = array();
        $total_subscription = 0;

        foreach ($query->result() as $single_stat) {
            
            $subscription_stat['date'][] = '"'.$single_stat->td.'"';
            $subscription_stat['total'][] = '"'.$single_stat->totalresult.'"';
            $total_subscription += $single_stat->totalresult;
        }

        if (!isset($subscription_stat['date'])) {
            $subscription_stat['date'] = array();
        }
        if (!isset($subscription_stat['total'])) {
            $subscription_stat['total'] = array();
        }
        $subscription_stat['total_count'] = $total_subscription;
        $data['subscription_stat'] = $subscription_stat;



        /* keyword tracking */
        $marked_keywords = $this->basic->get_data('video_position_set', array('where' => array('user_id' => $this->user_id, 'mark_for_dashboard' => '1')), array('id', 'keyword'));

        $keyword_ids = array();
        $keyword_id_name_maping = array();
        foreach ($marked_keywords as $single_keyword) {

            array_push($keyword_ids, $single_keyword['id']);
            $keyword_id_name_maping[$single_keyword['id']] = $single_keyword['keyword'];
        }

        $rank_stat = array();
        if (count($keyword_ids) > 0) {
            
            $today = date('Y-m-d');
            $previous_date = date('Y-m-d', strtotime($today. ' - 30 day'));

            $this->db->where('date > ', $previous_date);
            $this->db->where('date <= ', $today);
            $this->db->where_in('keyword_id', $keyword_ids);
            $rank_report = $this->basic->get_data('video_position_report', '', array('keyword_id' , 'date', 'youtube_position'));

            $thirty_days = array();
            $thirty_days_report_val = array();
            $max_number = 0;

            /* generate last 30 days date and result val */
            for ($i=1; $i < 31; $i++) { 
                
                array_push($thirty_days, '"'.date('Y-m-d', strtotime($previous_date. ' + '. $i .' day')).'"' );
                array_push($thirty_days_report_val, 0);
            }

            /* set 30 days time */
            $rank_stat['date'] = implode(',', $thirty_days);
            $rank_stat['colors'] = $this->getColorForRankReport();
            shuffle($rank_stat['colors']);

            /* set 30 days rank position to 0 */
            foreach ($keyword_id_name_maping as $key => $val) {
                
                $rank_stat[$key]['youtube_position'] = array($thirty_days_report_val);
            }

            /* update the rank position */
            foreach ($rank_report as $single_report) {
                
                $index = 29 - (strtotime($today) - strtotime($single_report['date']) ) / (24*60*60);
                $rank_stat[ $single_report['keyword_id'] ]['youtube_position'][0][$index] = $single_report['youtube_position'];

                $max_number = max($max_number, $single_report['youtube_position']);
            }

            /* prepare for js input */
            foreach ($rank_stat as $key => &$single_stat) {
                
                if ($key == 'date' || $key == 'colors') {
                    continue;
                }

                $single_stat['youtube_position'] = implode(',', $single_stat['youtube_position'][0]);
            }
            unset($single_stat);

            $rank_stat['step_size'] = ceil(($max_number + 1) / 5);
        }

        $data['rank_stat'] = $rank_stat;
        $data['keyword_id_name_maping'] = $keyword_id_name_maping;


        $data['channel_count'] = count($this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id))));

        $this->db->select_sum('view_count');
        $this->db->select_sum('video_count');
        $this->db->select_sum('subscriber_count');
        $this->db->select_sum('comment_count');
        $data['channel_infos'] = $this->basic->get_data('youtube_channel_info', array('where' => array('user_id' => $this->user_id)));


        $data['popular_playlist'] = $this->basic->get_data('youtube_channels_playlist', array('where' => array('user_id' => $this->user_id)), '', '', 5, 0, 'itemCount desc');


        $data['latest_uploaded_videos'] = $this->basic->get_data('youtube_video_upload', array('where' => array('youtube_video_upload.user_id' => $this->user_id, 'upload_status' => '1')), array('youtube_video_upload.*', 'youtube_channel_info.profile_image'), array('youtube_channel_info' => 'youtube_video_upload.channel_id=youtube_channel_info.channel_id,left'), 5, 0, 'upload_time desc');


        $data['popular_videos'] = $this->basic->get_data('youtube_video_list', array('where' => array('user_id' => $this->user_id)), '', '', 5, 0, 'viewCount desc');

      
        $data['last_comment_replies'] = $this->basic->get_data('auto_reply_campaign_report', array('where' => array('user_id' => $this->user_id)), '', '', 5, 0, 'replied_at desc');


        $data['upload_video_campaigns'] = $this->basic->get_data('youtube_video_upload', array('where' => array('user_id' => $this->user_id), '', '', 5, 0, 'upload_time desc'));


        $data['page_title'] = $this->lang->line("Dashboard");
        $data['body'] = 'dashboard';
        $this->_viewcontroller($data);
    }  


    protected function getColorForRankReport()
    {
        return array(
            'rgba(26, 188, 156,1.0)',
            'rgba(46, 204, 113,1.0)',
            'rgba(52, 152, 219,1.0)',
            'rgba(155, 89, 182,1.0)',
            'rgba(52, 73, 94,1.0)',
            'rgba(241, 196, 15,1.0)',
            'rgba(230, 126, 34,1.0)',
            'rgba(231, 76, 60,1.0)',
            'rgba(127, 140, 141,1.0)',
            'rgba(250, 211, 144,1.0)',
            'rgba(248, 194, 145,1.0)',
            'rgba(248, 194, 145,1.0)',
            'rgba(106, 137, 204,1.0)',
            'rgba(130, 204, 221,1.0)',
            'rgba(184, 233, 148,1.0)',
            'rgba(250, 152, 58,1.0)',
            'rgba(235, 47, 6,1.0)',
            'rgba(30, 55, 153,1.0)',
            'rgba(10, 61, 98,1.0)',
            'rgba(7, 153, 146,1.0)',
            'rgba(15, 188, 249,1.0)',
            'rgba(63,82,227,.8)',
            'rgba(254,86,83,.7)',
        );
    }

   

}
