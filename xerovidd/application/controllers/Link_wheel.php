<?php 
require_once("Home.php"); // loading home controller

class Link_wheel extends Home
{

    public $user_id;    
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        $this->important_feature();
        $this->member_validity();
        if($this->session->userdata('user_type') != 'Admin' && !in_array(18,$this->module_access))
        redirect('home/login_page', 'location'); 

        $this->upload_path = realpath( APPPATH . '../upload');
    }

    public function index()
    {
        $data['page_title'] = $this->lang->line("Video Link Wheel");
        $data['body'] = 'link_wheel/link_wheel_campaign_list';
        $this->_viewcontroller($data);
    }


    public function link_wheel_campaign_data()
    {
        $this->ajax_check();

        $search_value = $_POST['search']['value'];
        $display_columns = array("#",'id', 'wheel_name','wheel_type', 'status', 'last_updated_at', 'actions');
        $search_columns = array( 'wheel_name','video_ids','money_video_id');

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

        $table="youtube_link_wheel";
        $info=$this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        $i=0;
        foreach ($info as $key => $value) {
            $info[$key]['last_updated_at'] = date('jS M y H:i', strtotime($value['last_updated_at']));

            $error = '<a class="btn btn-circle btn-danger show_error" data-toggle="tooltip" href="#" title="'.$this->lang->line("Error").'" campaign_id="'.$value['id'].'"><i class="fas fa-bug"></i></a></div>';

            $info[$key]['actions'] = '<div class="min_width_150px"><a data-toggle="tooltip" class="btn btn-circle btn-outline-info" target="_BLANK" href="'.base_url("link_wheel/details/".$value['id']).'" title="'.$this->lang->line("Report").'"><i class="fas fa-eye"></i></a>&nbsp;<a data-toggle="tooltip" class="btn btn-circle btn-outline-danger remove_wheel" href="'.base_url("link_wheel/delete/".$value['id'].'/'.$value['status']).'" title="'.$this->lang->line("Remove").'"><i class="fas fa-trash-alt"></i></a>&nbsp;';
            $info[$key]['actions'] .= $error; 
            if($i==0) $info[$i]["actions"] .= '<script src="'.base_url().'assets/js/system/tooltip_popover.js"></script>';

            $posting_status = $value["status"];

            if( $posting_status == '2') $info[$key]['status'] = '<span class="text-success"><i class="fa fa-check-circle green"></i> '.$this->lang->line("Completed").'</span>';
            else if( $posting_status == '1') $info[$key]['status'] = '<span class="text-muted"><i class="fa fa-spinner green"></i> '.$this->lang->line("Processing").' </span>';
            else $info[$key]['status'] = '<span class="text-warning"><i class="fas fa-times-circle"></i> '.$this->lang->line("Pending").'</span>';
            $i++;
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start);

        echo json_encode($data);
    }



    public function create_wheel()
    {
        if($_POST)
        {

            $wheel_type=$this->input->post("wheel_type");
            
            $wheel_name=strip_tags($this->input->post("wheel_name",true));
            $money_video_id=$this->input->post("money_video_id");
            $video_ids= $this->input->post("videos");
            $count=count($video_ids);
      
            //************************************************//
            $status=$this->_check_usage($module_id=18,$count);
            if($status=="2") 
            {
                echo json_encode(array("status"=>"0","message"=>$this->lang->line("Sorry, bulk limit is exceeded for this module.")));
                exit();
            }
            $status=$this->_check_usage($module_id=18,$request=1);
            if($status=="3") 
            {
                echo json_encode(array("status"=>"0","message"=>$this->lang->line("Sorry, your monthly limit is exceeded for this module.")));
                exit();
            }
            //************************************************//

            if(!isset($money_video_id) || $money_video_id=='null') $money_video_id=array();
            $money_video_ids=json_encode($money_video_id);

            $insert_data=array
            (
                "video_ids"=>json_encode($video_ids),
                "status"=>"0",
                "wheel_name"=>$wheel_name,
                "wheel_type"=>$wheel_type,
                "last_updated_at"=>date("Y-m-d H:i:s"),
                "money_video_id"=>$money_video_ids,
                "user_id"=>$this->user_id,
                "channel_auto_id" => $this->session->userdata('youtube_channel_info_table_id')
            );     

            if($this->basic->insert_data("youtube_link_wheel",$insert_data)) 
            {
                $this->_insert_usage_log($module_id=18,$request=1);   
                echo json_encode(array("status"=>"1","message"=>"Video link wheel has been submitted successfully and will take time to complete based on server load."));
            }
            else echo json_encode(array("status"=>"0","message"=>"Video link wheel was failed to submit."));            
        }
    }


    public function details($id=0)
    {
        if($id==0) exit();
        $data= $this->basic->get_data("youtube_link_wheel",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)));
        if(isset($data[0]))
        $this->_viewcontroller(array("data"=>$data[0],"page_title"=>$this->lang->line("Wheel Details"),"body"=>"link_wheel/view_details"));
    }

    public function delete($wheel_id=0,$wheel_status=0)
    {
        $this->ajax_check();
        
        $data= $this->basic->get_data("youtube_link_wheel",array("where"=>array("id"=>$wheel_id,"user_id"=>$this->user_id)));
        if(!isset($data[0])) exit();

        if($wheel_status!=2) // if not completed wheel delete only from wheel list and exit
        {
            $this->basic->delete_data("youtube_link_wheel",$where=array("id"=>$wheel_id));
            $this->_delete_usage_log($module_id=18,$request=1); 
            echo json_encode(array("status"=>"1","message"=>$this->lang->line("Wheel has been removed successfully.")));
            exit();
        }

        $channel_auto_id = $data[0]['channel_auto_id'];
        $params['youtube_channel_info_table_id'] = $channel_auto_id;
        $this->load->library('youtube_library',$params);

        $data=array();
        $data= $this->basic->get_data("youtube_link_wheel_log",array("where"=>array("wheel_id"=>$wheel_id,"user_id"=>$this->user_id)));
        $status=1; //no error
        $response_array = array();
        foreach ($data as $key => $value) 
        {             
            $video_id=$value["video_id"];          
            $update_str=$value["update_str"];
                   
            $response=$this->youtube_library->update_video_info_wheel($video_id,$description_add="",$description_remove=$update_str);

            if($response=="1")
            {
                $this->basic->execute_complex_query("UPDATE youtube_video_list SET description = REPLACE(description,'$update_str','') WHERE video_id='$video_id'");
            }
            else 
            {
                $response_array[] = $response;
                $status=2; // error
            }
        }

        if($status=="1")
        {
            $this->basic->delete_data("youtube_link_wheel_log",array("wheel_id"=>$wheel_id));
            $this->basic->delete_data("youtube_link_wheel",$where=array("id"=>$wheel_id)); 
            echo json_encode(array("status"=>"1","message"=>$this->lang->line("Wheel has been removed successfully.")));
        }
        else echo json_encode(array("status"=>"0","message"=>implode(', ', $response_array)));
    }
   


}