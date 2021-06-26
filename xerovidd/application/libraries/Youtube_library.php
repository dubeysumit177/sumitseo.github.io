<?php
require_once('Google_library/autoload.php');
require_once('Google_library/Client.php');
require_once('Google_library/Service/YouTube.php');
require_once('Google_library/Service/Oauth2.php');

class Youtube_library
{
    /**
     * {@code "offline"} to request offline access from the user.
     */
    const ACCESS_TYPE_OFFLINE = 'offline';

    /**
     * {@code "online"} to request online access from the user.
     */
    const ACCESS_TYPE_ONLINE = 'online';

    /**
     * Force approval. Can get refresh token
     */
    const APPROVAL_PROMPT_FORCE = 'force';

    /**
     * Auto login, Can't get refresh token after first login.
     */
    const APPROVAL_PROMPT_AUTO = 'auto';

    /**
     * OAuth2 endpoint
     */
    const GOOGLE_OAUTH2_ENDPOINT = 'https://accounts.google.com/o/oauth2/auth';

    /**
     * @var string
     */
    public $clientId;

    /**
     * @var string
     */
    public $clientSecret;

    /**
     * @var string
     */
    public $redirectUri;

    /**
     * @var string|json
     */
    public $accessToken;

    /**
     * @var string|not json
     */
    public $accessTokenOnly;


    /**
     * @var string|not json
     */
    public $googleApiKey;

    /**
     * @var array
     */
    public $scopes = [
        'https://www.googleapis.com/auth/userinfo.profile',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/youtube',
        'https://www.googleapis.com/auth/yt-analytics.readonly',
        'https://www.googleapis.com/auth/yt-analytics-monetary.readonly',
        'https://www.googleapis.com/auth/youtube.force-ssl'
    ];

    /**
     * Holds instance of Google Client
     *
     * @var \Google\Google_Client
     */
    public $client;

    /**
     * Holds instance of Google_Service_Oauth2
     *
     * @var \Google\Google_Service_Oauth2
     */
    public $oauth;

    /**
     * Holds instance of youtube and youtube analytics
     *
    */
    public $youtube;
    public $analytics;

    /**
     * Holds user ID
     *
     * @var string
     */
    public $userId;

    public $youtube_channel_info_table_id;


 
    public function __construct($params=[])
    {
        // Gets instance of Codeigniter
        $this->CI =& get_instance();

        // Loads database
        $this->CI->load->database();

        // Loads BASIC model
        $this->CI->load->model('basic');

        // Loads URL helper
        $this->CI->load->helper('url_helper');

        // Loads session
        $this->CI->load->library('session');
        if(!isset($params['is_refreshtoken'])) $params['is_refreshtoken'] = true;
        $this->initializeGclient($params);

       
    }


    public function initializeGclient($params=[])
    {
      // Sets user ID
      $this->youtube_channel_info_table_id = '';
      $this->userId = '';

      $this->youtube_channel_info_table_id = isset($params['youtube_channel_info_table_id']) ? $params['youtube_channel_info_table_id'] :  $this->CI->session->userdata('youtube_channel_info_table_id');

      if(!empty($this->youtube_channel_info_table_id))
      {
          $user_info = $this->CI->basic->get_data('youtube_channel_info',['where'=>['id'=>$this->youtube_channel_info_table_id]],['user_id']);
          $this->userId = $user_info[0]['user_id'];
      }

      // Gets OAuth2 parameters  
      $data = $this->getOAuth2Params($this->youtube_channel_info_table_id);

      if (! is_array($data)) {
          throw new \Exception('Client ID and Client secret are not found.');
      }

      $this->clientId = isset($data['google_client_id']) ? trim($data['google_client_id']) : null;
      $this->clientSecret = isset($data['google_client_secret']) ? trim($data['google_client_secret']) : null;
      $this->googleApiKey = isset($data['api_key']) ? trim($data['api_key']) : null;

      if (! empty($params['redirectUri'])) {
          $this->redirectUri = $params['redirectUri'];
      } else {
          $this->redirectUri = site_url("home/google_login_back");
      }

      $this->accessToken = isset($data['access_token']) ? trim($data['access_token']) : null;
      $this->accessTokenOnly = $this->getAccessTokenOnly();

      // Creates google my business object
      if(!isset($params['is_refreshtoken'])) $params['is_refreshtoken'] = true;
      $this->client = $this->bootGoogleClient($params['is_refreshtoken']);

      $this->youtube =  new Google_Service_YouTube($this->client);
      $this->analytics =  new Google_Service_YouTubeAnalytics($this->client);

      // Creates Google_Service_Oauth2 object
      $this->oauth = new Google_Service_Oauth2($this->client);
    }



    /**
     * Bootstraps Google_Client
     *
     * @return Google_Client
     */
    protected function bootGoogleClient($is_refreshtoken=true)
    {
        // Bootstraps Google_Client
        $client = new Google_Client();
        $client->setClientId($this->clientId);
        $client->setClientSecret($this->clientSecret);
        $client->addScope($this->scopes);
        $client->setAccessType(self::ACCESS_TYPE_OFFLINE);
        $client->setRedirectUri($this->redirectUri);

        if (null != $this->accessToken) {
            $client->setAccessToken($this->accessToken);
        }

        // Sets new access token if the previous one is expired
        if ($this->accessToken && $client->isAccessTokenExpired()) {
            $refreshToken = $client->getRefreshToken();
            if($is_refreshtoken)
            {
              $client->refreshToken($refreshToken);
              $accessToken = $client->getAccessToken();            
              // Updates access token
              try {
                  $this->updateAccessToken($accessToken);
              } catch (\Exception $e) {
                  log_message('error', 'Could not update access token.');
              }
            }
        }

        return $client;
    }    

    /**
     * Updates access token
     *
     * @param string $accessToken
     * @throws Exception
     */
    protected function updateAccessToken($accessToken)
    {
        $accessTokenValidity = json_decode($accessToken, true);
        if (! is_array($accessTokenValidity) || null == $accessTokenValidity) {
            throw new Exception('Invalid access token provided to be saved in database.');
        }

        $data = [
            'access_token' => $accessToken
        ];

        $where = [
            'id' =>  $this->youtube_channel_info_table_id,
            'user_id' => $this->userId
        ];

        $this->CI->basic->update_data('youtube_channel_info', $where, $data);
    }

    /**
     * Responsible for getting OAuth2 params
     *
     * @return array|null
     */
    protected function getOAuth2Params($youtube_channel_info_table_id)
    {
        if($youtube_channel_info_table_id)
        {
            $table_name = 'social_app_google_config';
            $select = [
                'social_app_google_config.id',
                'social_app_google_config.google_client_id',
                'social_app_google_config.google_client_secret',
                'social_app_google_config.api_key',
                'youtube_channel_info.access_token'
            ];

            $where = [
                'where' => [
                    'youtube_channel_info.id' => $youtube_channel_info_table_id
                ]
            ];

            $join = [
                'youtube_channel_info' => 'youtube_channel_info.social_app_google_config_table_id=social_app_google_config.id,left'
            ];
            $order_by= "";

        }
        else
        {
            $table_name = 'social_app_google_config';
            $select = [
                'social_app_google_config.id',
                'social_app_google_config.google_client_id',
                'social_app_google_config.google_client_secret',
                'social_app_google_config.api_key',
            ];

            $where = [
                'where' => [
                    'social_app_google_config.status' => '1',
                ]
            ];

            $join = [];
            $order_by = 'rand()';
        }


        $result = $this->CI->basic->get_data($table_name, $where, $select, $join,1,NULL,$order_by);

        $social_app_google_config_table_id=isset($result[0]['id'])?$result[0]['id']:0;
        $this->CI->session->set_userdata('social_app_google_config_table_id', $social_app_google_config_table_id);

        return isset($result[0]) ? $result[0] : null;
    }

    protected function getAccessTokenOnly() // reyurns only the access token index form json access token
    {
        if(empty($this->accessToken)) return "";
        $accessTokenArray = json_decode($this->accessToken, true);
        if(is_array($accessTokenArray) && isset($accessTokenArray["access_token"]))
        return $accessTokenArray["access_token"];
        else return "";
    }

    public function set_login_button()
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => implode(' ', $this->scopes),
            'access_type' => self::ACCESS_TYPE_OFFLINE,
            'approval_prompt' => self::APPROVAL_PROMPT_FORCE,
        ];

        $login_url = self::GOOGLE_OAUTH2_ENDPOINT . '?' . http_build_query($params);
        return 
        '
          <a id="gSignInWrapper" href="'.$login_url.'">
            <div id="customBtn" class="customGPlusSignIn">
              <span class="icon"></span>
              <span class="buttonText">'.$this->CI->lang->line("Sign in with").' Google</span>
            </div>
          </a>
        ';
    }


    public function user_details()
    {
    
        $userProfile=array();        
        
        if(isset($_GET['code'])){
            $this->client->authenticate($_GET['code']);
            $access_token= $this->client->getAccessToken();
            if(isset($access_token)){
                $access_token_array =json_decode($access_token,true);
                $this->accessTokenOnly = isset($access_token_array['access_token'])?$access_token_array['access_token']:"";
                $this->client->setAccessToken($access_token);
                $userProfile = $this->oauth->userinfo->get();
            }       
        }            
        return $userProfile;
    }

    public function get_channel_list()
    {        
        $url ="https://www.googleapis.com/youtube/v3/channels?part=brandingSettings,contentDetails,snippet,statistics&mine=true&access_token={$this->accessTokenOnly}";
        return $this->get_curl($url,true);
    }

    public function get_playlist_info($channel_id="")
    {
        $url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet,contentDetails&channelId={$channel_id}&maxResults=50";
        $headers = array("Content-type: application/json", "Authorization: Bearer {$this->accessTokenOnly}");
        return $this->get_curl($url,false,$headers);       
    }

    public function playlist_item($playlist_id="", $next_page = '', $max_result = 50)
    {
       $url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId={$playlist_id}&mine=true&access_token={$this->accessTokenOnly}&maxResults={$max_result}&pageToken={$next_page}";
        return $this->get_curl($url,true);
    }

    public function playlist_item_paginated($playlist_id,$next_page='')
    { 
         $api_key=$this->googleApiKey;

         if (isset($this->accessTokenOnly)) {
            $access_token = $this->accessTokenOnly;
            $url ="https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId={$playlist_id}&access_token={$access_token}&maxResults=50&pageToken={$next_page}";
         }
         else $url ="https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId={$playlist_id}&key={$api_key}&maxResults=50&pageToken={$next_page}";
         $result = $this->get_curl($url,true);
         return $result;
     
    }

    function get_video_details_list($video_ids="")
    {
        $part=urlencode("contentDetails,statistics,snippet");
        $url ="https://www.googleapis.com/youtube/v3/videos?part={$part}&id={$video_ids}&mine=true&access_token={$this->accessTokenOnly}&maxResults=50";
        return $this->get_curl($url);
    }

    public function update_video_info($video_id, $categoryId = '', $defaultLanguage = '', $description = '', $tags = array(), $title = '', $privacyStatus = '', $localizes = array())
    {
        // Define the $video object, which will be uploaded as the request body.
        $video = new Google_Service_YouTube_Video();

        // Add 'id' string to the $video object.
        $video->setId($video_id);

        $part = array();

        // Add 'localizations' object to the $video object.
        if (count($localizes) > 0) {

            $localizations = new Google_Service_YouTube_VideoLocalization();

            foreach ($localizes as $key => $localization) {
                
                $localizations[$key] = new Google_Service_YouTube_VideoLocalization();
                $localizations[$key]->setDescription($localization['description']);
                $localizations[$key]->setTitle($localization['title']);
            }

            $video->setLocalizations($localizations);

            array_push($part, 'localizations');
        }


        // Add 'snippet' object to the $video object.
        if ($categoryId != '' || $defaultLanguage != '' || $description != '' || count($tags) > 0 || $title != '') {

            $videoSnippet = new Google_Service_YouTube_VideoSnippet();

            if ($categoryId != '') $videoSnippet->setCategoryId($categoryId);
            if ($defaultLanguage != '') $videoSnippet->setDefaultLanguage($defaultLanguage);
            if ($description != '') $videoSnippet->setDescription($description);
            if (count($tags) > 0) $videoSnippet->setTags($tags);
            if ($title != '') $videoSnippet->setTitle($title);

            $video->setSnippet($videoSnippet);

            array_push($part, 'snippet');
        }


        // Add 'status' object to the $video object.
        if ($privacyStatus != '') {

            $videoStatus = new Google_Service_YouTube_VideoStatus();
            $videoStatus->setPrivacyStatus($privacyStatus);
            $video->setStatus($videoStatus);

            array_push($part, 'status');
        }

        $part = implode(',', $part);

        try 
        {        
          $response = $this->youtube->videos->update($part, $video);
          if(isset($response['error']))
          {
            $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return array("error"=>"1","message"=>$error_message);
          }
          else return $response;
        }
        catch (Google_Exception $e){
           $response = $e->getMessage();  
        }
        catch (Google_Service_Exception $e){
           $response = $e->getMessage();
        }
        catch (\Exception $e){  
           $response = $e->getMessage();
        }

        return $response;
    }

    public function update_video_info_wheel($video_id="",$description_add="",$description_remove="")
    {   
        $response = array();
        try
        {
            // REPLACE this value with the video ID of the video being updated.
            $videoId = $video_id;

            // Call the API's videos.list method to retrieve the video resource.
            $listResponse = $this->youtube->videos->listVideos("snippet",array('id' => $videoId));

            if(isset($listResponse['error']))
            {
              $error_message = isset($listResponse['error']['message']) ? $listResponse['error']['message'] : $this->CI->lang->line("Something went wrong.");
              return $error_message;
            }

            // If $listResponse is empty, the specified video was not found.
            if (empty($listResponse)) {
                $response = "Can't find a video with video id: ".$videoId;
            }
            else 
            {
              // Since the request specified a video ID, the response only
              // contains one video resource.
              $video = $listResponse[0];
              $videoSnippet = $video['snippet'];
              $tags = $videoSnippet['tags'];
              $video_description= $videoSnippet['description'];   

              if($description_add!="")
              {
                $videoSnippet['description'] = $video_description."\n\n ".$description_add;
              }
              if($description_remove!="")
              {
                $description_remove= trim($description_remove);
                $videoSnippet['description'] = str_replace($description_remove,"", $video_description);
              }
                  
              // Update the video resource by calling the videos.update() method.
              $updateResponse = $this->youtube->videos->update("snippet", $video);
              if(isset($updateResponse['error']))
              {
                $error_message = isset($updateResponse['error']['message']) ? $updateResponse['error']['message'] : $this->CI->lang->line("Something went wrong.");
                return $error_message;
              }
              $response = "1";
            }
        }         
        catch (Google_Exception $e){
          $response = $e->getMessage();
        }
        catch (Google_Service_Exception $e){
          $response = $e->getMessage();
        }
        catch (\Exception $e){ 
          $response = $e->getMessage();
        }

        return $response;
    }

    public function get_video_analytics($channel_id='',$metrics='',$dimension='',$sort='',$filter='',$max_result='',$start_date='',$end_date='')
    {
        $id = "channel=={$channel_id}";
        if($dimension!='')
            $optparams['dimensions'] = $dimension;
        if($sort!='')
            $optparams['sort'] = $sort;
        if($filter!='')
            $optparams['filters'] = $filter;
        if($max_result!='')
            $optparams['maxResults'] = $max_result;
        $analytics_info = $this->analytics->reports->query($id, $start_date, $end_date, $metrics, $optparams);
        return $analytics_info;
    }

    public function get_channel_analytics($channel_id='',$metrics='',$dimension='',$sort='',$max_result='',$start_date='',$end_date='')
    { 
        $id = "channel=={$channel_id}";
        if($dimension!='')
            $optparams['dimensions'] = $dimension;
        if($sort!='')
            $optparams['sort'] = $sort;
        if($max_result!='')
            $optparams['maxResults'] = $max_result;
        $analytics_info =  $this->analytics->reports->query($id, $start_date, $end_date, $metrics, $optparams);
        return $analytics_info;
    }

    public function get_channel_playlist($channel_id="")
    {
        $url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet,contentDetails&channelId={$channel_id}&maxResults=50";
        $headers = array("Content-type: application/json", "Authorization: Bearer {$this->accessTokenOnly}");
        return $this->get_curl($url,true,$headers);
    }

    public function create_playlist($title, $description = '', $privacy_type = '', $tags = '')
    {
        
        // 1. Create the snippet for the playlist. Set its title and description.
        $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
        $playlistSnippet->setTitle($title);

        if ($description != '') $playlistSnippet->setDescription($description);
        if (count($tags) > 0) $playlistSnippet->setTags($tags);

        // 3. Define a playlist resource and associate the snippet and status
        // defined above with that resource.
        $youTubePlaylist = new Google_Service_YouTube_Playlist();
        $youTubePlaylist->setSnippet($playlistSnippet);
        
        // 2. Define the playlist's status.
        if ($privacy_type != '') {

            $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
            $playlistStatus->setPrivacyStatus($privacy_type);   
            $youTubePlaylist->setStatus($playlistStatus);
        }

        // 4. Call the playlists.insert method to create the playlist. The API
        // response will contain information about the new playlist.
        // $playlistResponse = $youtube->playlists->insert('snippet,status', $youTubePlaylist, array());        
        try 
        {        
          $playlistResponse = $this->youtube->playlists->insert('snippet,status', $youTubePlaylist, array());
          if(isset($playlistResponse['error']))
          {
            $error_message = isset($playlistResponse['error']['message']) ? $playlistResponse['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return array("error"=>"1","message"=>$error_message);
          }
          else return $playlistResponse;
        }
        catch (Google_Exception $e){
          $playlistResponse['error'] = '1';
          $playlistResponse['message'] = $e->getMessage();

        }
        catch (Google_Service_Exception $e){
          $playlistResponse['error'] = '1';
          $playlistResponse['message'] = $e->getMessage();

        }
        catch (\Exception $e){
          $playlistResponse['error'] = '1';
          $playlistResponse['message'] = $e->getMessage();
        }

        
        return $playlistResponse;
    }


    public function update_playlist($playlist_id, $title, $description = '', $privacy_type = '', $tags = '')
    {   
        // Define the $playlist object, which will be uploaded as the request body.
        $playlist = new Google_Service_YouTube_Playlist();

        // Add 'id' string to the $playlist object.
        $playlist->setId($playlist_id);

        // Add 'snippet' object to the $playlist object.
        $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();

        if ($description != '') $playlistSnippet->setDescription($description);

        if (count($tags) > 0) $playlistSnippet->setTags($tags);

        $playlistSnippet->setTitle($title);
        $playlist->setSnippet($playlistSnippet);

        // Add 'status' object to the $playlist object.
        if ($privacy_type != '') {
            
            $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
            $playlistStatus->setPrivacyStatus($privacy_type);
            $playlist->setStatus($playlistStatus);
        }

        try 
        {        
          $response = $this->youtube->playlists->update('snippet,status', $playlist);
          if(isset($response['error']))
          {
            $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return array("error"=>"1","message"=>$error_message);
          }
          else return $response;
        }
        catch (Google_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (Google_Service_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (\Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }

        return $response;
    }


    public function delete_playlist($playlist_id)
    {        
        try 
        {        
          $response = $this->youtube->playlists->delete($playlist_id);
          if(isset($response['error']))
          {
            $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return array("error"=>"1","message"=>$error_message);
          }
          else return $response;
        }
        catch (Google_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (Google_Service_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (\Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function add_video_on_playlist($playlist_id, $video_id)
    {   
        // 5. Add a video to the playlist. First, define the resource being added
        // to the playlist by setting its video ID and kind.
        $resourceId = new Google_Service_YouTube_ResourceId();
        $resourceId->setVideoId($video_id);
        $resourceId->setKind('youtube#video');

        // Then define a snippet for the playlist item. Set the playlist item's
        // title if you want to display a different value than the title of the
        // video being added. Add the resource ID and the playlist ID retrieved
        // in step 4 to the snippet as well.
        $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
        $playlistItemSnippet->setTitle('First video in the test playlist');
        $playlistItemSnippet->setPlaylistId($playlist_id);
        $playlistItemSnippet->setResourceId($resourceId);

        // Finally, create a playlistItem resource and add the snippet to the
        // resource, then call the playlistItems.insert method to add the playlist
        // item.
        $playlistItem = new Google_Service_YouTube_PlaylistItem();
        $playlistItem->setSnippet($playlistItemSnippet);
        
        try 
        {        
          $playlistItemResponse = $this->youtube->playlistItems->insert('snippet,contentDetails', $playlistItem, array());
          if(isset($playlistItemResponse['error']))
          {
            $error_message = isset($playlistItemResponse['error']['message']) ? $playlistItemResponse['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return $error_message;
          }
          else return $playlistItemResponse;
        }
        catch (Google_Exception $e){
          $playlistItemResponse = $e->getMessage();
        }
        catch (Google_Service_Exception $e){
          $playlistItemResponse = $e->getMessage();
        }
        catch (\Exception $e){
          $playlistItemResponse = $e->getMessage();
        }
        return $playlistItemResponse;
    }


    public function delete_video_from_playlist($playlist_item_id)
    {        
        try 
        {        
          $response = $this->youtube->playlistItems->delete($playlist_item_id);
          if(isset($response['error']))
          {
            $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return array("error"=>"1","message"=>$error_message);
          }
          else return $response;
        }
        catch (Google_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (Google_Service_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (\Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        return $response;

    }

    public function get_youtube_video($keyword,$limit=200,$channel_id="",$location="", $location_radious="", $order="" , $published_after="", $published_before="",$video_duration="",$video_type="",$event_type="",$dimension="",$defination="",$license=""){
        
        $all_video_result=array();
        
        $keyword=urlencode($keyword);
        $api_key=$this->googleApiKey;
        
        $results=array();        
        
        $param_str="";
        if($channel_id)
            $param_str.="&channelId={$channel_id}";
            
        if($location){
            $param_str.="&location={$location}";
            if($location_radious)
                $param_str.="&locationRadius={$location_radious}";
        }
        
            
        if($order)
            $param_str.="&order={$order}";
            
        if($published_after){
            $published_after= date("Y-m-d\TH:i:s\Z", strtotime($published_after));
            $param_str.="&publishedAfter={$published_after}";
        }
        
        if($published_before){
            $published_before= date("Y-m-d\TH:i:s\Z", strtotime($published_before));
            $param_str.="&publishedBefore={$published_before}";
        }
        
        if($video_type)
            $param_str.="&videoType={$video_type}";
        
        if($video_duration)
            $param_str.="&videoDuration={$video_duration}";

        if($dimension)
            $param_str.="&videoDimension={$dimension}";

        if($defination)
            $param_str.="&videoDefinition={$defination}";

        if($license)
            $param_str.="&videoLicense={$license}";

        if($event_type)
            $param_str.="&eventType={$event_type}";
        
        if($param_str)
            $param_str.="&type=video";
        
        if($limit<50) $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults={$limit}{$param_str}";
        else $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50{$param_str}";
        
        $results=$this->get_curl($url,true);

        if(isset($results['error'])) return $results;

        
        $i=0;
        if(isset($results['items']))
        foreach($results['items'] as $r){
        
            if(isset($r['id']['videoId'])){
            
                $all_video_result[$i]['video_id']=$r['id']['videoId'];
                $all_video_result[$i]['published_at']=$r['snippet']['publishedAt'];
                $all_video_result[$i]['channel_id']=$r['snippet']['channelId'];
                $all_video_result[$i]['title']=$r['snippet']['title'];
                $all_video_result[$i]['description']=$r['snippet']['description'];
                $i++;       
            }   
        }
        
        $no_times=0;
        if($limit>50){
            $extra=$limit-50;
            $no_times=$extra/50;
        }
        
        for($page=0;$page<$no_times;$page++){
        
                $next_token_1=isset($results['nextPageToken'])? $results['nextPageToken']: "";
                
                if($next_token_1){
                    $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50&pageToken={$next_token_1}{$param_str}";        
                    $results=$this->get_curl($url);
                    foreach($results['items'] as $r){
                
                    if(isset($r['id']['videoId'])){
                        $all_video_result[$i]['video_id']=$r['id']['videoId'];
                        $all_video_result[$i]['published_at']=$r['snippet']['publishedAt'];
                        $all_video_result[$i]['channel_id']=$r['snippet']['channelId'];
                        $all_video_result[$i]['title']=$r['snippet']['title'];
                        $all_video_result[$i]['description']=$r['snippet']['description'];
                        $i++;       
                    }   
                 }
                    
            }
            
            else
                return $all_video_result;
        }
        
        return $all_video_result;
        
    }

    public function get_video_by_id($ids) // video search engine
    {        
        $api_key=$this->googleApiKey;
        
        $id_array=explode(",",$ids);
        $chunk=array_chunk($id_array, 50);

        $i=0;
        $results=array();

        foreach ($chunk as $value) 
        {
            $chunk_ids=implode(",",$value);         
            $url="https://www.googleapis.com/youtube/v3/videos?key={$api_key}&part=id,snippet,contentDetails,statistics,status&id={$chunk_ids}";
            $results[$i]=$this->get_curl($url,true);
            $i++;
        }        
        return $results;
        
    }

    public function get_youtube_channel($keyword,$limit=200,$location="",$location_radious="",$order="",$published_after="",$published_before="")
    {
        
        $all_channel_result=array();
        
        $keyword=urlencode($keyword);
        $api_key=$this->googleApiKey;

        /* prepare access token */
        $access_token = $this->accessTokenOnly;
        
        $results=array();
        
        $param_str="&type=channel";
            
        if($location){
            $param_str.="&location={$location}";
            if($location_radious)
                $param_str.="&locationRadius={$location_radious}";
        }
        
        if($order)
            $param_str.="&order={$order}";
            
        if($published_after){
            $published_after= date("Y-m-d\TH:i:s\Z", strtotime($published_after));
            $param_str.="&publishedAfter={$published_after}";
        }
        
        if($published_before){
            $published_before= date("Y-m-d\TH:i:s\Z", strtotime($published_before));
            $param_str.="&publishedBefore={$published_before}";
        }
        
        if($limit<50)
        {

            if ($access_token == "")
                $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults={$limit}{$param_str}";
            else
                $url="https://www.googleapis.com/youtube/v3/search?access_token={$access_token}&part=snippet,id&q={$keyword}&maxResults={$limit}{$param_str}";

        }       
        else
        {

            if ($access_token == "")
                $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50{$param_str}";
            else
                $url="https://www.googleapis.com/youtube/v3/search?access_token={$access_token}&part=snippet,id&q={$keyword}&maxResults=50{$param_str}";

        }
        
        $results=$this->get_curl($url,true);
        
        if(isset($results['error'])) return $results;
        
        $i=0;
        foreach($results['items'] as $r){
        
            if(isset($r['id']['channelId'])){
                $all_channel_result[$i]['published_at']=$r['snippet']['publishedAt'];
                $all_channel_result[$i]['channel_id']=$r['snippet']['channelId'];
                $all_channel_result[$i]['title']=$r['snippet']['title'];
                $all_channel_result[$i]['description']=$r['snippet']['description'];
                $all_channel_result[$i]['thumbnail']=$r['snippet']['thumbnails']['default']['url'];
                $i++;       
            }   
        }
        
        $no_times=0;
        if($limit>50){
            $extra=$limit-50;
            $no_times=$extra/50;
        }
        
        for($page=0;$page<$no_times;$page++){
        
                $next_token_1=isset($results['nextPageToken'])? $results['nextPageToken']: "";
                
                if($next_token_1){

                    if ($access_token == "")
                        $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50&pageToken={$next_token_1}{$param_str}";    
                    else
                        $url="https://www.googleapis.com/youtube/v3/search?access_token={$access_token}&part=snippet,id&q={$keyword}&maxResults=50&pageToken={$next_token_1}{$param_str}";  

                    $results=$this->get_curl($url);
                    foreach($results['items'] as $r){
                
                    if(isset($r['id']['channelId'])){
                        $all_channel_result[$i]['published_at']=$r['snippet']['publishedAt'];
                        $all_channel_result[$i]['channel_id']=$r['snippet']['channelId'];
                        $all_channel_result[$i]['title']=$r['snippet']['title'];
                        $all_channel_result[$i]['description']=$r['snippet']['description'];
                        $all_channel_result[$i]['thumbnail']=$r['snippet']['thumbnails']['medium']['url'];
                        $i++;       
                    }   
                 }
                    
            }
            
            else
                return $all_channel_result;
        }
        
        return $all_channel_result;
        
    }

    public function get_channel_by_id($ids){
        
        $api_key=$this->googleApiKey;
        $id_array=explode(",",$ids);
        $chunk=array_chunk($id_array, 50);

        $i=0;
        $results=array();

        foreach ($chunk as $value) 
        {
            $chunk_ids=implode(",",$value);         
            $url="https://www.googleapis.com/youtube/v3/channels?key={$api_key}&part=statistics&id={$chunk_ids}";
            $results[$i]=$this->get_curl($url);
            $i++;
        }
        return $results;
    }


    public function get_youtube_playlist($keyword,$limit=200,$channel_id="",$location="",$location_radious="",$order="",$published_after="",$published_before=""){
        
        $all_playlist_result=array();
        
        $keyword=urlencode($keyword);
        $api_key=$this->googleApiKey;
        
        $results=array();
        
        $param_str="&type=playlist";

        if($channel_id)
            $param_str.="&channelId={$channel_id}";
            
            
        if($location){
            $param_str.="&location={$location}";
            if($location_radious)
                $param_str.="&locationRadius={$location_radious}";
        }
        
        if($order)
            $param_str.="&order={$order}";
            
        if($published_after){
            $published_after= date("Y-m-d\TH:i:s\Z", strtotime($published_after));
            $param_str.="&publishedAfter={$published_after}";
        }
        
        if($published_before){
            $published_before= date("Y-m-d\TH:i:s\Z", strtotime($published_before));
            $param_str.="&publishedBefore={$published_before}";
        }
        
        if($limit<50)       
            $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults={$limit}{$param_str}";
        else
            $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50{$param_str}";
        
        $results=$this->get_curl($url,true);
        
        if(isset($results['error'])) return $results;
        
        $i=0;
        foreach($results['items'] as $r){
        
            if(isset($r['id']['playlistId'])){
                $all_playlist_result[$i]['published_at']=$r['snippet']['publishedAt'];
                $all_playlist_result[$i]['playlist_id']=$r['id']['playlistId'];
                $all_playlist_result[$i]['channel_id']=$r['snippet']['channelId'];
                $all_playlist_result[$i]['title']=$r['snippet']['title'];
                $all_playlist_result[$i]['description']=$r['snippet']['description'];
                $all_playlist_result[$i]['thumbnail']=isset($r['snippet']['thumbnails']['default']['url']) ? $r['snippet']['thumbnails']['default']['url'] : "";
                $i++;       
            }   
        }
        
        $no_times=0;
        if($limit>50){
            $extra=$limit-50;
            $no_times=$extra/50;
        }
        
        for($page=0;$page<$no_times;$page++){
        
                $next_token_1=isset($results['nextPageToken'])? $results['nextPageToken']: "";
                
                if($next_token_1){
                    $url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50&pageToken={$next_token_1}{$param_str}";        
                    $results=$this->get_curl($url);
                    foreach($results['items'] as $r){
                
                    if(isset($r['id']['playlistId'])){
                        $all_playlist_result[$i]['published_at']=$r['snippet']['publishedAt'];
                        $all_playlist_result[$i]['playlist_id']=$r['id']['playlistId'];
                        $all_playlist_result[$i]['channel_id']=$r['snippet']['channelId'];
                        $all_playlist_result[$i]['title']=$r['snippet']['title'];
                        $all_playlist_result[$i]['description']=$r['snippet']['description'];
                        $all_playlist_result[$i]['thumbnail']=$r['snippet']['thumbnails']['medium']['url'];
                        $i++;       
                    }   
                 }
                    
            }
            
            else
                return $all_playlist_result;
        }
        
        return $all_playlist_result;
        
    }


    public function get_playlist_by_id($ids){
        
        $api_key=$this->googleApiKey;
        $id_array=explode(",",$ids);
        $chunk=array_chunk($id_array, 50);

        $i=0;
        $results=array();

        foreach ($chunk as $value) 
        {
            $chunk_ids=implode(",",$value);         
            $url="https://www.googleapis.com/youtube/v3/playlists?key={$api_key}&part=contentDetails,status&id={$chunk_ids}";
            $results[$i]=$this->get_curl($url);
            $i++;
        }
        return $results;
    }


    public function delete_channel_subscription($id_after_subscription)
    {
      try 
      {        
        $response = $this->youtube->subscriptions->delete($id_after_subscription,array());
        if(isset($response['error']))
        {
          $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
          return array("error"=>"1","message"=>$error_message);
        }
        else return $response;
      }
      catch (Google_Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      catch (Google_Service_Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      catch (\Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      return $response;
    }

    public function subscribe_to_other_channel($channel_id)
    { 
        // Identify the resource being subscribed to by specifying its channel ID
        // and kind.
        $resourceId = new Google_Service_YouTube_ResourceId();
        $resourceId->setChannelId($channel_id);
        $resourceId->setKind('youtube#channel');

        // Create a snippet object and set its resource ID.
        $subscriptionSnippet = new Google_Service_YouTube_SubscriptionSnippet();
        $subscriptionSnippet->setResourceId($resourceId);

        // Create a subscription request that contains the snippet object.
        $subscription = new Google_Service_YouTube_Subscription();
        $subscription->setSnippet($subscriptionSnippet);

        // Execute the request and return an object containing information
        // about the new subscription.
        try 
        {        
          $response = $this->youtube->subscriptions->insert('id,snippet',$subscription, array());
          if(isset($response['error']))
          {
            $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return array("error"=>"1","message"=>$error_message);
          }
          else return $response;
        }
        catch (Google_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (Google_Service_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (\Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        return $response;
    }


    // possibly unused function
    public function get_channel_latest_video_info($channel_id, $max_result = 50)
    {      
      $url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&channelId='. $channel_id .'&maxResults='. $max_result .'&order=date&type=video&access_token='.$this->accessTokenOnly;      
      $result = $this->get_curl($url);

      /* return latest video id */
      if (isset($result['items'][1]['id']['videoId']))
      {

        $temp = array();
        
        $temp['video_id'] = $result['items'][1]['id']['videoId'];
        $temp['published_at'] = date('Y-m-d H:i:s', strtotime( str_replace('T', ' ', substr($result['items'][1]['snippet']['publishedAt'], 0, 16) )));

        $temp['videos'] = $result['items'];
        $temp['total_video'] = min($result['pageInfo']['resultsPerPage'], $result['pageInfo']['totalResults']);

        return $temp;
      }
      else 
      {
        if(isset($result["error"])) return $result = $result["error"]["message"];
        else return $this->CI->lang->line("Something went wrong.");        
      }

    }


    public function get_video_comment($video_id, $pageToken = '')
    { 
      $queryParams = [
          'textFormat' => 'plainText',
          'videoId' => $video_id,
          'maxResults' => 100,
          'pageToken' => $pageToken,
      ];

      if ($pageToken == '') unset($queryParams['pageToken']);      

      try 
      {        
        $response = $this->youtube->commentThreads->listCommentThreads('snippet', $queryParams);
        if(isset($response['error']))
        {
          $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
          return array("error"=>"1","message"=>$error_message);
        }
        else return $response;
      }
      catch (Google_Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      catch (Google_Service_Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      catch (\Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      return $response;
    }

    public function delete_video_comment_own($comment_id)
    {
      try 
      {        
        $response =  $this->youtube->comments->delete($comment_id);
        if(isset($response['error']))
        {
          $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
          return array("error"=>"1","message"=>$error_message);
        }
        else return $response;
      }
      catch (Google_Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      catch (Google_Service_Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      catch (\Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      return $response;  
    }

    public function delete_video_comment_other($comment_id)
    {
      try 
      {        
        $response = $this->youtube->comments->setModerationStatus($comment_id, 'rejected');
        if(isset($response['error']))
        {
          $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
          return array("error"=>"1","message"=>$error_message);
        }
        else return $response;
      }
      catch (Google_Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      catch (Google_Service_Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      catch (\Exception $e){
        $response['error'] = '1';
        $response['message'] = $e->getMessage();
      }
      return $response;  
    }

    public function create_comment_reply($comment_id, $reply)
    { 
        $commentSnippet = new Google_Service_YouTube_CommentSnippet();
        $commentSnippet->setTextOriginal($reply);
        $commentSnippet->setParentId($comment_id);

        # Create a comment with snippet.
        $comment = new Google_Service_YouTube_Comment();
        $comment->setSnippet($commentSnippet);

        # Call the YouTube Data API's comments.insert method to reply to a comment.
        # (If the intention is to create a new top-level comment, commentThreads.insert
        # method should be used instead.)

        try 
        {        
          $response = $this->youtube->comments->insert('snippet', $comment);
          if(isset($response['error']))
          {
            $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return array("error"=>"1","message"=>$error_message);
          }
          else return $response;
        }
        catch (Google_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (Google_Service_Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (\Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function create_video_comment($video_id, $comment)
    { 
        $commentSnippet = new Google_Service_YouTube_CommentSnippet();
        $commentSnippet->setTextOriginal($comment);

        # Create a top-level comment with snippet.
        $topLevelComment = new Google_Service_YouTube_Comment();
        $topLevelComment->setSnippet($commentSnippet);

        # Create a comment thread snippet with channelId and top-level comment.
        $commentThreadSnippet = new Google_Service_YouTube_CommentThreadSnippet();
        $commentThreadSnippet->setVideoId($video_id);
        $commentThreadSnippet->setTopLevelComment($topLevelComment);

        # Create a comment thread with snippet.
        $commentThread = new Google_Service_YouTube_CommentThread();
        $commentThread->setSnippet($commentThreadSnippet);

        // Call the YouTube Data API's commentThreads.insert method to create a comment.


        # Insert video comment
        // $commentThreadSnippet->setVideoId($VIDEO_ID);
        // Call the YouTube Data API's commentThreads.insert method to create a comment.
        try
        {
          $videoCommentInsertResponse = $this->youtube->commentThreads->insert('snippet', $commentThread);
          if(isset($videoCommentInsertResponse['error']))
          {
            $error_message = isset($videoCommentInsertResponse['error']['message']) ? $videoCommentInsertResponse['error']['message'] : $this->CI->lang->line("Something went wrong.");
            return array("error"=>"1","message"=>$error_message);
          }
          else return $videoCommentInsertResponse;
        }
        catch (Google_Exception $e){
          $error_message = $e->getMessage();
          return array("error"=>"1","message"=>$error_message);
        }
        catch (Google_Service_Exception $e){
          $error_message = $e->getMessage();
          return array("error"=>"1","message"=>$error_message);
        }
        catch (\Exception $e){
          $error_message = $e->getMessage();
          return array("error"=>"1","message"=>$error_message);
        }
    }

    public function video_like_dislike($video_id, $action)
    {
      try
      {
        $response= $this->youtube->videos->rate($video_id, $action,$params=array());
        if(isset($response['error']))
        {
          $error_message = isset($response['error']['message']) ? $response['error']['message'] : $this->CI->lang->line("Something went wrong.");
          return array("error"=>"1","message"=>$error_message);
        }
        else return $response;
      }
      catch (Google_Exception $e){
        $error_message = $e->getMessage();
        return array("error"=>"1","message"=>$error_message);
      }
      catch (Google_Service_Exception $e){
        $error_message = $e->getMessage();
        return array("error"=>"1","message"=>$error_message);
      }
      catch (\Exception $e){
        $error_message = $e->getMessage();
        return array("error"=>"1","message"=>$error_message);
      }
    }

    public function cronjob_upload_video_to_youtube($title='',$description='',$video_link='',$tags='',$category_id='',$privacy_type='')
    { 
        $snippet = new Google_Service_YouTube_VideoSnippet();
        try
        {
          $tags = explode(',', $tags);
          $videoPath = realpath(FCPATH."upload/video/".$video_link);
          $snippet = new Google_Service_YouTube_VideoSnippet();
          $snippet->setTitle($title);
          $snippet->setDescription($description);
          $snippet->setTags($tags);
          
          // https://developers.google.com/youtube/v3/docs/videoCategories/list
          $snippet->setCategoryId($category_id);
          $status = new Google_Service_YouTube_VideoStatus();
          $status->privacyStatus = $privacy_type;

          $video = new Google_Service_YouTube_Video();
          $video->setSnippet($snippet);
          $video->setStatus($status);

          $chunkSizeBytes = 1 * 1024 * 1024;

          // Setting the defer flag to true tells the client to return a request which can be called
          // with ->execute(); instead of making the API call immediately.
          $this->client->setDefer(true);

          $insertRequest = $this->youtube->videos->insert("status,snippet", $video);

          $media = new Google_Http_MediaFileUpload(
            $this->client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
            );
          $media->setFileSize(filesize($videoPath));

          $status = false;
          $handle = fopen($videoPath, "rb");
          while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
          }

          fclose($handle);

          // If you want to make other calls after the file upload, set setDefer back to false
          $this->client->setDefer(false);
          $response = array();
          $response['video_id'] = $status['id'];
          $response['publish_time'] = $status->snippet['publishedAt'];
          $response['channel_id'] = $status->snippet['channelId'];
          $response['title'] = $status->snippet['title'];
          $response['description'] = $status->snippet['description'];
          $response['image_link'] = $status->snippet['thumbnails']['medium']['url'];
          $response['tags'] = json_encode($status->snippet['tags']);
          $response['categoryId'] = $status->snippet['categoryId'];
          $response['liveBroadcastContent'] = $status->snippet['liveBroadcastContent'];
          $response['privacyStatus'] = $status->status['privacyStatus'];
          $response['viewCount'] = '0';
          $response['likeCount'] = '0';       


        }
        catch (Google_Exception $e) {          
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (Google_Service_Exception $e) {
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        catch (\Exception $e){
          $response['error'] = '1';
          $response['message'] = $e->getMessage();
        }
        return $response;

    }


    public function get_video_position($keyword,$video_id)
    {      
      $all_video_info=$this->get_youtube_video($keyword);
      $position=0;      
      
      foreach($all_video_info as $index=>$value){
        if($value['video_id']==$video_id)
        {
          $position=$index+1;
          break;
        }
          
      }      
      $response['position']=$position;
      $response['all_video']=$all_video_info;       
      return $response;     
    }



    public function get_curl($url="",$return_error=false,$headers=array("Content-type: application/json"))
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
        $st=curl_exec($ch);
        $result=json_decode($st,TRUE);
        
        $curl_info = curl_getinfo($ch);

        if($return_error) 
        {
          if(curl_error($ch)!="")
          {
            $http_code = isset($curl_info["http_code"]) ? $curl_info["http_code"] : "#";
            $error_message  = "HTTP Code - ".$http_code."  : ".curl_error($ch);
            return array("error"=>"1","message"=>$error_message);
          }

          if(isset($result["error"])) 
          {
            $error_reason = isset($result["error"]["errors"][0]["reason"])  ? "(".$result["error"]["errors"][0]["reason"].")":"";
            $error_message = isset($result["error"]["message"]) ? $result["error"]["message"] : $this->CI->lang->line("Unknown API error occured.");
            $error_message.=" ".$error_reason;
            return array("error"=>"1","message"=>$error_message);
          }
        }

        return $result;
    }
    

}