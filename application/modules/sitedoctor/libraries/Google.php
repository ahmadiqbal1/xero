<?php 
class Google{

	public $google_api_key="";

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('my_helper');
		$this->CI->load->library('session');
		$this->CI->load->model('basic');

		$where=array("where"=>array("user_id"=>$this->CI->session->userdata("user_id")));
		$google_config=$this->CI->basic->get_data("config",$where);
		if(isset($google_config[0]))
		{			
			$this->google_api_key=$google_config[0]["google_safety_api"];
		}
		if($this->google_api_key=="")
		{
			$where=array("where"=>array("user_type"=>"Admin"));
			$join=array('users'=>"users.id=config.user_id,left");
			$google_config=$this->CI->basic->get_data("config",$where,"",$join);
			if(isset($google_config[0]))
			{			
				$this->google_api_key=$google_config[0]["google_safety_api"];
			}
		}

	}
	

	function google_page_speed_insight_desktop($domain="",$strategy="desktop")
	{

		$key=$this->google_api_key;
		if($domain=="" || $key=="") exit();

		//$url="https://www.googleapis.com/pagespeedonline/v3beta1/runPagespeed?key=".$key."&url=".$domain."&strategy=".$strategy;
		$url="https://www.googleapis.com/pagespeedonline/v5/runPagespeed?key=".$key."&url=".$domain."&strategy=".$strategy."&category=performance";

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
     
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch); // run the whole process

		$content= json_decode($content,TRUE);
		// echo "<pre>";
		// print_r($content);
		// echo "</pre>";
		curl_close($ch);

		return $content;

	}	
	function google_page_speed_insight_mobile($domain="",$strategy="mobile")
	{

		$key=$this->google_api_key;
		if($domain=="" || $key=="") exit();

		//$url="https://www.googleapis.com/pagespeedonline/v3beta1/runPagespeed?key=".$key."&url=".$domain."&strategy=".$strategy;
		$url="https://www.googleapis.com/pagespeedonline/v5/runPagespeed?key=".$key."&url=".$domain."&strategy=".$strategy."&category=performance";

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
     
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch); // run the whole process

		$content= json_decode($content,TRUE);
		// echo "<pre>";
		// print_r($content);
		// echo "</pre>";
		curl_close($ch);

		return $content;

	}


	function clean_domain_name($domain){

 		$domain=trim($domain);
		$domain=strtolower($domain);
		
		$domain=str_replace("www.","",$domain);
		$domain=str_replace("http://","",$domain);
		$domain=str_replace("https://","",$domain);
		$domain=str_replace("/","",$domain);
		
		return $domain; 
	}

	function get_general_content($url,$proxy="")
	{			
			
			$ch = curl_init(); // initialize curl handle
           /* curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);*/
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 120); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method
     
		 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
            
            $content = curl_exec($ch); // run the whole process
			
            curl_close($ch);
			
			return $content;
			
	}
	

	function google_place_scraper($keyword="",$latitude="",$longitude="",$place_type="",$radius="")
	{
		$key=$this->google_api_key;
		$response_array=array();
		
		if($keyword=="") return $response_array;

		$keyword=urlencode($keyword);
		
		$parameter_str="query={$keyword}&key={$key}";
		if($latitude!="" && $longitude!=""){
			$parameter_str.="&location={$latitude},{$longitude}";
		}
		
		if($radius!="")
			$parameter_str.="&radius={$radius}";
			
		if($place_type!="")
			$parameter_str.="&type={$place_type}";

		 $url="https://maps.googleapis.com/maps/api/place/textsearch/json?{$parameter_str}";
	
		
		$respose=$this->get_general_content($url);
		$response_array=json_decode($respose,true);
		return $response_array;
	}


	function google_map_scraper($place_id="")
	{
		$key=$this->google_api_key;
		$response_array=array();
		
		if($place_id=="") return $response_array;

		$url="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$place_id."&key=".$key;
		$respose=$this->get_general_content($url);
		$response_array=json_decode($respose,true);
		return $response_array;
	}
	



	function mobile_ready($domain="")
	{		
		$key=$this->google_api_key;
		
		if($domain=="" || $key=="") exit();
		$domain=$this->clean_domain_name($domain);
		$domain=addHttp($domain);
		$url="https://www.googleapis.com/pagespeedonline/v3beta1/mobileReady?key=".$key."&url=".$domain."&strategy=mobile";
		$respose=$this->get_general_content($url);
		$respose_array=json_decode($respose,true);
		if(!$respose_array || !isset($respose_array["ruleGroups"]["USABILITY"]["pass"]))
		$respose=$this->get_general_content($url);
		else return $respose;
	}


	// function mobile_ready_new($domain="")
	// {		
	// 	$key="AIzaSyCGwZ99I4ag9VNVRL44fxYB3Ir62m5l-YQ";
		
	// 	if($domain=="" || $key=="") exit();
	// 	$domain=$this->clean_domain_name($domain);
	// 	$domain=addHttp($domain);

	// 	$url="https://searchconsole.googleapis.com/v1/urlTestingTools/mobileFriendlyTest:run?key=".$key;	
	// 	$headers = array("Content-type: application/json");		
	// 	$post_data= array("url"=>$domain,"requestScreenshot"=>TRUE);
	// 	$post_data=json_encode($post_data);		
		 
	// 	$ch = curl_init(); 
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// 	curl_setopt($ch, CURLOPT_POST, 1);  
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
 //        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
 //        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
 //        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
 //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
 //        curl_setopt($ch, CURLOPT_TIMEOUT, 120); // times out after 50s
       
 //        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 //        $content = curl_exec($ch); // run the whole process		
	// 	$content= json_decode($content,TRUE);

	// 	return $content;
	// }



	function syncMailchimp($data='') 
 	{
        $apikey = $this->mailchimp_api_key; // They key is generated at mailchimps controlpanel under settings.
        $apikey_explode = explode('-',$apikey); // The API ID is the last part of your api key, after the hyphen (-), 
        if(is_array($apikey_explode) && isset($apikey_explode[1])) $api_id=$apikey_explode[1];
        else $api_id="";
        $listId = $this->mailchimp_list_id; //  example: us2 or us10 etc.

        if($apikey=="" || $api_id=="" || $listId=="" || $data=="") exit();
      
        $auth = base64_encode( 'user:'.$apikey );
		
        $insert_data=array
        (
			'email_address'  => $data['email'],
			'status'         => 'subscribed', // "subscribed","unsubscribed","cleaned","pending"
			'merge_fields'  => array('FNAME'=>$data['firstname'],'LNAME'=>'','CITY'=>'','MMERGE5'=>"Subscriber")	
	    );
			
		$insert_data=json_encode($insert_data);
 	
		$url="https://".$api_id.".api.mailchimp.com/3.0/lists/".$listId."/members/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $insert_data);                                                                                                           
        $result = curl_exec($ch);
    }




	/****Get Youtube video data******/
	
	
	function youtube_video_curl($url){
		$ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch); // run the whole process
		$results=json_decode($content,TRUE);
		return $results;
	}
	
	
	function get_youtube_video($keyword,$limit=200,$channel_id="",$location="", $location_radious="", $order="" , $published_after="", $published_before="",$video_duration="",$video_type="",$event_type="",$dimension="",$defination="",$license=""){
		
		$all_video_result=array();
		
		$keyword=urlencode($keyword);
		$api_key=$this->google_api_key;
		
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
		
		if($limit<50)		
			$url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults={$limit}{$param_str}";
		else
			$url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50{$param_str}";
		
		$results=$this->youtube_video_curl($url);
		
		if(!is_array($results))
			return array();
		
		$i=0;
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
					$results=$this->youtube_video_curl($url);
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
	
	
	
	public function get_video_position($keyword,$video_id){
		
		$all_video_info=$this->get_youtube_video($keyword);
		
		$position=0;
		
		
		foreach($all_video_info as $index=>$value){
			if($value['video_id']==$video_id){
				$position=$index+1;
				break;
			}
				
		}
		
		 $response['position']=$position;
		 $response['all_video']=$all_video_info;
		 
		 return$response;
		
	}


	public function get_video_link_keyword_position($keyword,$video_id){
		
		$all_video_info=$this->get_youtube_video($keyword,$limit=50);
		
		$position=0;
		
		
		foreach($all_video_info as $index=>$value){
			if($value['video_id']==$video_id){
				$position=$index+1;
				break;
			}
				
		}
		
		 $response['position']=$position;
		 $response['all_video']=$all_video_info;
		 
		 return$response;
		
	}


	public function get_live_video_position($keyword,$video_id){
		
		$all_video_info=$this->get_youtube_video($keyword,$limit=200,$channel_id="",$location="", $location_radious="", $order="" , $published_after="", $published_before="",$video_duration="",$video_type="",$event_type="upcoming");
		
		$position=0;
		
		
		foreach($all_video_info as $index=>$value){
			if($value['video_id']==$video_id){
				$position=$index+1;
				break;
			}
				
		}
		
		 $response['position']=$position;
		 $response['all_video']=$all_video_info;
		 
		 return $response;
		
	}
	
	
	
	public function get_video_by_id($ids){
		
		$api_key=$this->google_api_key;
		
		$id_array=explode(",",$ids);
		$chunk=array_chunk($id_array, 50);

		$i=0;
		$results=array();

		foreach ($chunk as $value) 
		{
			$chunk_ids=implode(",",$value);			
			$url="https://www.googleapis.com/youtube/v3/videos?key={$api_key}&part=id,snippet,contentDetails,statistics,status&id={$chunk_ids}";
			$results[$i]=$this->youtube_video_curl($url);
			$i++;
		}

		
		return $results;
		
	}
	
	
	
	public function get_channel_by_id($ids){
		
		$api_key=$this->google_api_key;
		$id_array=explode(",",$ids);
		$chunk=array_chunk($id_array, 50);

		$i=0;
		$results=array();

		foreach ($chunk as $value) 
		{
			$chunk_ids=implode(",",$value);			
			$url="https://www.googleapis.com/youtube/v3/channels?key={$api_key}&part=statistics&id={$chunk_ids}";
			$results[$i]=$this->youtube_video_curl($url);
			$i++;
		}
		return $results;
	}
	
	
	public function get_playlist_by_id($ids){
		
		$api_key=$this->google_api_key;
		$id_array=explode(",",$ids);
		$chunk=array_chunk($id_array, 50);

		$i=0;
		$results=array();

		foreach ($chunk as $value) 
		{
			$chunk_ids=implode(",",$value);			
			$url="https://www.googleapis.com/youtube/v3/playlists?key={$api_key}&part=contentDetails,status&id={$chunk_ids}";
			$results[$i]=$this->youtube_video_curl($url);
			$i++;
		}
		return $results;
	}
	
	
	
	
	
	public function get_vimeo_video_search($keyword,$access_token="",$limit="200"){
		$keyword=urlencode($keyword);
		
		$final_result=array();
		
		$url="https://api.vimeo.com/videos?query={$keyword}&access_token=f15c4e5b1862c1c00b259b7d8058f284&per_page=50&fields=uri,name,link,duration,pictures,created_time,modified_time,privacy,modified_time,release_time,stats,metadata,user";
		
		$results=$this->youtube_video_curl($url);
		
		$final_result[]=$results['data'];
		
		$no_times=2;
		if($limit>50){
			$extra=$limit-50;
			$no_times=$extra/50;
			$no_times=$no_times+2;
		}
		
		for($i=2;$i<$no_times;$i++){
			
			if($results['paging']['next']){
				 $url="https://api.vimeo.com/videos?query={$keyword}&access_token=f15c4e5b1862c1c00b259b7d8058f284&per_page=50&fields=uri,name,link,duration,pictures,created_time,modified_time,privacy,modified_time,release_time,stats,metadata,user&page={$i}";
				$results=$this->youtube_video_curl($url);
				$final_result[]=$results['data'];
			}
			else
				break;
			
		}
		
		
		return $final_result;
		
		
	}
	
	
	
	public function get_dailymotion_video_search($keyword,$limit="200"){
	
		$keyword=urlencode($keyword);
		
		$final_result=array();
		
		$url="https://api.dailymotion.com/videos?search={$keyword}&fields=id,title,description,thumbnail_180_url,duration,views_total,created_time,comments_total,url&sort=relevance&limit=50";
		
		$results=$this->youtube_video_curl($url);
		
		$final_result[]=$results['list'];
		
		
		$no_times=2;
		if($limit>50){
			$extra=$limit-50;
			$no_times=$extra/50;
			$no_times=$no_times+2;
		}
		
		for($i=2;$i<$no_times;$i++){
			
			if($results['has_more']){
				 $url="https://api.dailymotion.com/videos?search={$keyword}&fields=id,title,description,thumbnail_180_url,duration,views_total,created_time,likes_total,comments_total,url&sort=relevance&limit=50&page={$i}";
				$results=$this->youtube_video_curl($url);
				$final_result[]=$results['list'];
			}
			else
				break;			
		}	
		return $final_result;		
	}
	
	



	function get_youtube_channel($keyword,$limit=200,$location="",$location_radious="",$order="",$published_after="",$published_before=""){
		
		$all_channel_result=array();
		
		$keyword=urlencode($keyword);
		$api_key=$this->google_api_key;
		
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
			$url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults={$limit}{$param_str}";
		else
			$url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50{$param_str}";
		
		$results=$this->youtube_video_curl($url);
		
		if(!is_array($results))
			return array();
		
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
					$url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50&pageToken={$next_token_1}{$param_str}";		
					$results=$this->youtube_video_curl($url);
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
	
	
	function get_youtube_playlist($keyword,$limit=200,$channel_id="",$location="",$location_radious="",$order="",$published_after="",$published_before=""){
		
		$all_playlist_result=array();
		
		$keyword=urlencode($keyword);
		$api_key=$this->google_api_key;
		
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
		
		$results=$this->youtube_video_curl($url);
		
		if(!is_array($results))
			return array();
		
		$i=0;
		foreach($results['items'] as $r){
		
			if(isset($r['id']['playlistId'])){
				$all_playlist_result[$i]['published_at']=$r['snippet']['publishedAt'];
				$all_playlist_result[$i]['playlist_id']=$r['id']['playlistId'];
				$all_playlist_result[$i]['channel_id']=$r['snippet']['channelId'];
				$all_playlist_result[$i]['title']=$r['snippet']['title'];
				$all_playlist_result[$i]['description']=$r['snippet']['description'];
				$all_playlist_result[$i]['thumbnail']=$r['snippet']['thumbnails']['default']['url'];
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
					$results=$this->youtube_video_curl($url);
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
	
	
	// public function get_all_playlist_from_channel($channel_id){
	// 	$api_key=$this->google_api_key;
	// 	$url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId={$channel_id}&key={$api_key}";
	// 	return $this->youtube_video_curl($url);
	// } 


	
	function playlist_item($playlist_id,$next_page=''){	
		 $api_key=$this->google_api_key;
		 $url ="https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId={$playlist_id}&key={$api_key}&maxResults=50&pageToken={$next_page}";
		 return $this->youtube_video_curl($url);
	 
	 }
	 
	 
	
	
	
	
	
}
?>