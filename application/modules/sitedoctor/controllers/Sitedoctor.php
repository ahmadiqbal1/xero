<?php
/*
Addon Name: SiteDoctor
Unique Name: sitedoctor
Modules:
{
   "84":{
      "bulk_limit_enabled":"0",
      "limit_enabled":"1",
      "extra_text":"",
      "module_name":"SiteDoctor"
   }
}
Project ID: 13
Addon URI: https://xeroseo.com
Author: Xerone IT
Author URI: https://xeroneit.net
Version: 2.2.1
Description: Website health check
*/

require_once("application/controllers/Home.php"); // loading home controller

class Sitedoctor extends Home
{
  public $addon_data=array();
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
        // getting addon information in array and storing to public variable
        // addon_name,unique_name,module_id,addon_uri,author,author_uri,version,description,controller_name,installed
        //------------------------------------------------------------------------------------------
        $addon_path=APPPATH."modules/".strtolower($this->router->fetch_class())."/controllers/".ucfirst($this->router->fetch_class()).".php"; // path of addon controller
        $addondata=$this->get_addon_data($addon_path); 
        $this->addon_data=$addondata;

        $this->user_id=$this->session->userdata('user_id'); // user_id of logged in user, we may need it

        // all addon must be login protected
        //------------------------------------------------------------------------------------------
        if ($this->session->userdata('logged_in')!= 1) redirect('home/login', 'location');          

        // if you want the addon to be accessed by admin and member who has permission to this addon
        //-------------------------------------------------------------------------------------------
        if(isset($addondata['module_id']) && is_numeric($addondata['module_id']) && $addondata['module_id']>0)
        {
            if($this->session->userdata('user_type') != 'Admin' && !in_array($addondata['module_id'],$this->module_access))
            {
              redirect('home/login_page', 'location');
              exit();
            }
        }
    }

    public function index()
    {
        $this->checked_website_lists();      
    } 

    public function checked_website_lists()
    {
        $data['body'] = "checked_website_lists";
        $data['page_title'] = $this->lang->line("Website Health Checker");

        $this->_viewcontroller($data);
    }

    public function checked_website_lists_data()
    {
        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'id','domain_name','warning_count','mobile_perfomence_score','actions','desktop_perfomence_score','perfomence_category','overall_score','searched_at');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_simple=array();

        if($post_date_range!="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

            if($from_date!="Invalid date" && $to_date!="Invalid date")
            {
                $from_date = date('Y-m-d', strtotime($from_date));
                $to_date   = date('Y-m-d', strtotime($to_date));
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") {
            $where_simple['domain_name like'] = "%".$searching."%";
        }

        $where_simple['user_id'] = $this->user_id;
        $where  = array('where'=>$where_simple);

        $table = "site_check_report";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');

        for($i=0;$i<count($info);$i++)
        {  
            $action_count = 4;
            $info[$i]['searched_at'] = "<div style='min-width:140px;'>".date("M j, Y h:i A",strtotime($info[$i]["searched_at"]))."</div>";
            $report_url = base_url('sitedoctor/report/').$info[$i]['id'];
            $download_url = base_url('sitedoctor/report_pdf/').$info[$i]['id'];
            $compare_url = base_url('sitedoctor/add_domain/').$info[$i]['id'];

            $report_btn = "<a href='".$report_url."' target='_BLANK' class='btn btn-circle btn-outline-primary' data-toggle='tooltip' title='".$this->lang->line("View Report")."'><i class='fas fa-eye'></i></a>";

            $compare_btn = "<a href='".$compare_url."' target='_BLANK' class='btn btn-circle btn-outline-info' data-toggle='tooltip' title='".$this->lang->line("Compare with competitor")."'><i class='fas fa-adjust'></i></a>";

            $download_bottupm = "<a target='_blank' href='".$download_url."' class='btn btn-circle btn-outline-success download_report' table_id='".$info[$i]['id']."' data-toggle='tooltip' title='".$this->lang->line("Downaload Report")."'><i class='fas fa-cloud-download-alt'></i></a>";

            $delete_btn = "<a href='#' class='btn btn-circle btn-outline-danger delete_website' table_id='".$info[$i]['id']."' data-toggle='tooltip' title='".$this->lang->line("Delete")."'><i class='fas fa-trash-alt'></i></a>";

            $action_width = ($action_count*47)+20;
            $info[$i]['actions'] ='
            <div class="dropdown d-inline dropright text-center">
              <button class="btn btn-outline-primary dropdown-toggle no_caret" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-briefcase"></i>
              </button>
              <div class="dropdown-menu mini_dropdown text-center" style="width:'.$action_width.'px !important">';
                $info[$i]['actions'] .= $report_btn;
                $info[$i]['actions'] .= $compare_btn;
                $info[$i]['actions'] .= $download_bottupm;
                $info[$i]['actions'] .= $delete_btn;
                $info[$i]['actions'] .="
              </div>
            </div>
            <script>
            $('[data-toggle=\"tooltip\"]').tooltip();</script>";
        
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }

    public function add_domain($base_site="")
    {
        $data['body'] = 'add_domain';
        $data["base_site"]=$base_site;
        if($base_site=="") {
            $data["compare"]=0;
            $data['page_title'] = $this->lang->line('website health check');
        }
        else { 
            $data["compare"]=1;
            $data['page_title'] = $this->lang->line('Comaparative health check');
        }
        $this->_viewcontroller($data);
    }

    public function add_domain_direct($direct_site_check_url="")
    {
        if(!isset($_GET["site"])) redirect('sitedoctor/add_domain', 'location'); 

        $data['body'] = 'add_domain';
        $data['page_title'] = $this->lang->line('website health check');
        $data["base_site"]="";
        $data["direct_site_check_url"]=urldecode($_GET["site"]); // if this url exist when call it means form has to be submitted automatically
        $this->_viewcontroller($data);
    }

    function convert_to_ascii($url)
    {
        $parts = parse_url($url);
        if (!isset($parts['host']))
        return $url; // missing http? makes parse_url fails

        if (mb_detect_encoding($parts['host']) != 'ASCII'  && function_exists("idn_to_ascii") ){
            $parts['host'] = idn_to_ascii($parts['host']);
            return $parts['scheme']."://".$parts['host'];
        }
        return $url;
    }

    public function add_domain_action()
    {
        $this->ajax_check();
        $status=$this->_check_usage($module_id=84,$request=1);
        if($status=="2") 
        {
            $response=array("status"=>"0","message"=>$this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>");
            echo json_encode($response);
            exit();
        }
        else if($status=="3") 
        {            
            $response=array("status"=>"0","message"=>$this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>");
            echo json_encode($response);
            exit();
        }

        $this->session->unset_userdata('insert_table_id_sitedoctor');
        $this->session->unset_userdata('is_compare');
        $this->session->unset_userdata('compare_table_id');
        $this->session->unset_userdata('sitedoc_report_completed_for_domain');


        if ($_SERVER['REQUEST_METHOD'] === 'GET') redirect('home/access_forbidden', 'location');

        $this->load->library("google");
        $this->load->library("sitedoctor_library");

        $domain=trim($this->input->post('website', true));
        if($domain=="")
        {
            $response=array("status"=>"0","message"=>$this->lang->line('you have not enter any domain name'));
            echo json_encode($response);
            exit();
        }

        $domain=addHttp($domain);       
        $domain_encoded=$this->convert_to_ascii($domain);
        $base_site=trim($this->input->post('base_site', true));
        $compare=trim($this->input->post('compare', true));


        $download_id=$this->session->userdata('download_id_front');

        $insert=array();

        $insert["domain_name"]=$domain;
        $insert["searched_at"]=date("Y-m-d H:i:s");
        $insert["user_id"]=$this->session->userdata("user_id");
        $insert["completed_step_count"]=0;

        $search_existing_info = $this->basic->get_data('site_check_report',['where'=>['user_id'=>$this->session->userdata("user_id"),'domain_name'=>$domain]],['id']);
        if(empty($search_existing_info))
        {
            $this->basic->insert_data("site_check_report",$insert);
            $last_id=$this->db->insert_id();    
        }
        else
        {
            $this->basic->update_data('site_check_report',['id'=>$search_existing_info[0]['id']],$insert);
            $last_id = $search_existing_info[0]['id'];            
        }
        // unset($insert['completed_step_count']);
        //***************************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=84,$request=1);
        //***************************************//        
        $this->session->set_userdata('insert_table_id_sitedoctor', $last_id);
        $this->session->set_userdata('health_check_total',100);
        $this->session->set_userdata('health_check_count',0);

        $this->session->set_userdata('sitedoc_report_completed_for_domain',$domain);
        if($compare==1)
        {
            $insert_compare=array();
            $insert_compare["searched_at"]=date("Y-m-d H:i:s");
            $insert_compare["user_id"]=$this->session->userdata("user_id");
            $insert_compare["base_site"]=$base_site;
            $insert_compare["competutor_site"]=$last_id;
            $this->basic->insert_data("comparision",$insert_compare);
            $comparision_id=$this->db->insert_id();
            $this->session->set_userdata('compare_table_id',$comparision_id);
            $this->session->set_userdata('is_compare','1');
        }
        else
            $this->session->set_userdata('is_compare','0');

        session_write_close();
        

        // site check starts
        $site_stat=$this->sitedoctor_library->site_statistic_check($domain_encoded);
        foreach ($site_stat as $key => $value) 
        {
            $insert[$key]= is_array($value) ? json_encode($value) : $value;
        }
        // end of site check



        //desktop starts
        $desktop_result=$this->google->google_page_speed_insight_desktop($domain,"desktop");

            
        if (isset($desktop_result['error'])) {
            $insert['desktop_google_api_error'] = $desktop_result['error']['message'];
        }
        else{

            if (isset($desktop_result['loadingExperience'])) {
                $insert["desktop_loadingexperience_metrics"] =  isset($desktop_result['loadingExperience']) ? json_encode($desktop_result['loadingExperience']) : "";
            }
            if (isset($desktop_result['originLoadingExperience'])) {
                $insert["desktop_originloadingexperience_metrics"] =  isset($desktop_result['originLoadingExperience']) ? json_encode($desktop_result['originLoadingExperience']) : "";
            }
            if (isset($desktop_result['lighthouseResult']['configSettings'])) {
               $insert["desktop_lighthouseresult_configsettings"] =  isset($desktop_result['lighthouseResult']['configSettings']) ? json_encode($desktop_result['lighthouseResult']['configSettings']) : "";
            }
            if (isset($desktop_result['lighthouseResult']['audits'])) {

                $first_meaningful_paint = isset($desktop_result['lighthouseResult']['audits']['first-meaningful-paint']['score']) ? $desktop_result['lighthouseResult']['audits']['first-meaningful-paint']['score'] : 0;

                $speed_index = isset($desktop_result['lighthouseResult']['audits']['speed-index']['score']) ? 
                $desktop_result['lighthouseResult']['audits']['speed-index']['score'] : 0;

                $first_cpu_idle = isset($desktop_result['lighthouseResult']['audits']['first-cpu-idle']['score']) ? $desktop_result['lighthouseResult']['audits']['first-cpu-idle']['score'] : 0;

                $first_contentful_paint = isset($desktop_result['lighthouseResult']['audits']['first-contentful-paint']['score']) ? $desktop_result['lighthouseResult']['audits']['first-contentful-paint']['score'] : 0;
                $interactive = isset($desktop_result['lighthouseResult']['audits']['interactive']['score']) ? 
                $desktop_result['lighthouseResult']['audits']['interactive']['score'] : 0;

                $desktop_score = ($first_meaningful_paint*7)+($speed_index*27)+($first_cpu_idle*13)+($first_contentful_paint*20)+($interactive*33);

                $insert["desktop_perfomence_score"] = $desktop_score;

                if(isset($desktop_result['lighthouseResult']['audits']['resource-summary']))
                    unset($desktop_result['lighthouseResult']['audits']['resource-summary']['details']);

                if (isset($desktop_result['lighthouseResult']['audits']['efficient-animated-content']))
                    unset($desktop_result['lighthouseResult']['audits']['efficient-animated-content']['details']);

                if (isset($desktop_result['lighthouseResult']['audits']['metrics']))
                    unset($desktop_result['lighthouseResult']['audits']['metrics']);   

                if (isset($desktop_result['lighthouseResult']['audits']['network-server-latency']))
                    unset($desktop_result['lighthouseResult']['audits']['network-server-latency']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['offscreen-images']))
                    unset($desktop_result['lighthouseResult']['audits']['offscreen-images']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['uses-responsive-images']))
                    unset($desktop_result['lighthouseResult']['audits']['uses-responsive-images']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['unused-css-rules']))
                    unset($desktop_result['lighthouseResult']['audits']['unused-css-rules']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['total-byte-weight']))
                    unset($desktop_result['lighthouseResult']['audits']['total-byte-weight']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['mainthread-work-breakdown']))
                    unset($desktop_result['lighthouseResult']['audits']['mainthread-work-breakdown']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['uses-webp-images']))
                    unset($desktop_result['lighthouseResult']['audits']['uses-webp-images']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['critical-request-chains']))
                    unset($desktop_result['lighthouseResult']['audits']['critical-request-chains']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['dom-size']))
                    unset($desktop_result['lighthouseResult']['audits']['dom-size']['details']);                

                if (isset($desktop_result['lighthouseResult']['audits']['unminified-javascript']))
                    unset($desktop_result['lighthouseResult']['audits']['unminified-javascript']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['redirects']))
                    unset($desktop_result['lighthouseResult']['audits']['redirects']['details']);   

                if (isset($desktop_result['lighthouseResult']['audits']['time-to-first-byte']))
                    unset($desktop_result['lighthouseResult']['audits']['time-to-first-byte']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['render-blocking-resources']))
                    unset($desktop_result['lighthouseResult']['audits']['render-blocking-resources']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['font-display']))
                    unset($desktop_result['lighthouseResult']['audits']['font-display']['details']);

                if (isset($desktop_result['lighthouseResult']['audits']['estimated-input-latency']))
                    unset($desktop_result['lighthouseResult']['audits']['estimated-input-latency']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['uses-rel-preconnect']))
                    unset($desktop_result['lighthouseResult']['audits']['uses-rel-preconnect']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['unminified-css']))
                    unset($desktop_result['lighthouseResult']['audits']['unminified-css']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['bootup-time']))
                    unset($desktop_result['lighthouseResult']['audits']['bootup-time']['details']);                

                if (isset($desktop_result['lighthouseResult']['audits']['uses-rel-preload']))
                    unset($desktop_result['lighthouseResult']['audits']['uses-rel-preload']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['user-timings']))
                    unset($desktop_result['lighthouseResult']['audits']['user-timings']['details']);                

                if (isset($desktop_result['lighthouseResult']['audits']['uses-text-compression']))
                    unset($desktop_result['lighthouseResult']['audits']['uses-text-compression']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['uses-optimized-images']))
                    unset($desktop_result['lighthouseResult']['audits']['uses-optimized-images']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['uses-long-cache-ttl']))
                    unset($desktop_result['lighthouseResult']['audits']['uses-long-cache-ttl']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['third-party-summary']))
                    unset($desktop_result['lighthouseResult']['audits']['third-party-summary']['details']);                
                if (isset($desktop_result['lighthouseResult']['audits']['network-rtt']))
                    unset($desktop_result['lighthouseResult']['audits']['network-rtt']['details']);                

                if (isset($desktop_result['lighthouseResult']['audits']['diagnostics']))
                    unset($desktop_result['lighthouseResult']['audits']['diagnostics']);                

                if (isset($desktop_result['lighthouseResult']['audits']['network-requests']))
                    unset($desktop_result['lighthouseResult']['audits']['network-requests']['details']);                

                if (isset($desktop_result['lighthouseResult']['audits']['screenshot-thumbnails']))
                    unset($desktop_result['lighthouseResult']['audits']['screenshot-thumbnails']);                

                if (isset($desktop_result['lighthouseResult']['audits']['main-thread-tasks']))
                    unset($desktop_result['lighthouseResult']['audits']['main-thread-tasks']);

                if (isset($desktop_result['lighthouseResult']['categories']['performance']))
                    unset($desktop_result['lighthouseResult']['categories']['performance']['auditRefs']);                
                
                $insert['desktop_lighthouseresult_audits'] = isset($desktop_result['lighthouseResult']['audits']) ? json_encode($desktop_result['lighthouseResult']['audits']) : "";                   

            }
            if (isset($desktop_result['lighthouseResult']['categories'])) {
                $insert['desktop_lighthouseresult_categories'] = isset($desktop_result['lighthouseResult']['categories']) ? json_encode($desktop_result['lighthouseResult']['categories']) : "";
            }
        }
            
        // $insert["response_code"] = isset($desktop_result["responseCode"]) ? $desktop_result["responseCode"] : "";

        // $insert["speed_score"]  = isset($desktop_result["ruleGroups"]["SPEED"]["score"]) ? $desktop_result["ruleGroups"]["SPEED"]["score"] : "";

        // $pagestat = isset($desktop_result["pageStats"]) ? $desktop_result["pageStats"] : array();
        // $insert["pagestat"] = json_encode($pagestat);

        // $avoid_landing_page_redirects = array();
        // $avoid_landing_page_redirects["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["ruleImpact"] : 0;
        // $avoid_landing_page_redirects["redirect_count"] = isset($desktop_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["summary"]["args"]["0"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["summary"]["args"]["0"]["value"] : 0;
        // $avoid_landing_page_redirects["urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["urlBlocks"]["0"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["avoid_landing_page_redirects"]=json_encode($avoid_landing_page_redirects);

        // $gzip_compression = array();
        // $gzip_compression["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["ruleImpact"] : 0;
        // $gzip_compression["total_size_compressable"] = isset($desktop_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $gzip_compression["total_percentage_compressable"] = isset($desktop_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $gzip_compression["urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["gzip_compression"]=json_encode($gzip_compression);

        // $leverage_browser_caching = array();
        // $leverage_browser_caching["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["LeverageBrowserCaching"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["LeverageBrowserCaching"]["ruleImpact"] : 0;
        // $leverage_browser_caching["urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["LeverageBrowserCaching"]["urlBlocks"]["0"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["LeverageBrowserCaching"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["leverage_browser_caching"]=json_encode($leverage_browser_caching);

        // $main_resource_server_response_time = array();
        // $main_resource_server_response_time["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["MainResourceServerResponseTime"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["MainResourceServerResponseTime"]["ruleImpact"] : 0;
        // $main_resource_server_response_time["response_time"] = isset($desktop_result["formattedResults"]["ruleResults"]["MainResourceServerResponseTime"]["urlBlocks"]["0"]["header"]["args"]["0"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["MainResourceServerResponseTime"]["urlBlocks"]["0"]["header"]["args"]["0"]["value"] : "";
        // $insert["main_resource_server_response_time"]=json_encode($main_resource_server_response_time);

        // $minify_css = array();
        // $minify_css["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyCss"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyCss"]["ruleImpact"] : 0;
        // $minify_css["total_size_minifiable"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $minify_css["total_percentage_minifiable"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $minify_css["urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["minify_css"]=json_encode($minify_css);

        // $minify_html = array();
        // $minify_html["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyHTML"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyHTML"]["ruleImpact"] : 0;
        // $minify_html["total_size_minifiable"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $minify_html["total_percentage_minifiable"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $minify_html["urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["minify_html"]=json_encode($minify_html);

        // $minify_javaScript = array();
        // $minify_javaScript["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["ruleImpact"] : 0;
        // $minify_javaScript["total_size_minifiable"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $minify_javaScript["total_percentage_minifiable"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $minify_javaScript["urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["minify_javaScript"]=json_encode($minify_javaScript);

        // $minimize_render_blocking_resources = array();
        // $minimize_render_blocking_resources["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["ruleImpact"] : 0;
        // $minimize_render_blocking_resources["js_urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["urlBlocks"]["1"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["urlBlocks"]["1"]["urls"] : array();
        // $minimize_render_blocking_resources["css_urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["urlBlocks"]["2"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["urlBlocks"]["2"]["urls"] : array();
        // $insert["minimize_render_blocking_resources"]=json_encode($minimize_render_blocking_resources);

        // $optimize_images = array();
        // $optimize_images["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["OptimizeImages"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["OptimizeImages"]["ruleImpact"] : 0;
        // $optimize_images["total_size_optimizable"] = isset($desktop_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $optimize_images["total_percentage_optimizable"] = isset($desktop_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $desktop_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $optimize_images["urls"] = isset($desktop_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["urls"]) ? $desktop_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["optimize_images"]=json_encode($optimize_images);

        // $prioritize_visible_content = array();
        // $prioritize_visible_content["rule_impact"] = isset($desktop_result["formattedResults"]["ruleResults"]["PrioritizeVisibleContent"]["ruleImpact"]) ? $desktop_result["formattedResults"]["ruleResults"]["PrioritizeVisibleContent"]["ruleImpact"] : 0;
        // $insert["prioritize_visible_content"]=json_encode($prioritize_visible_content);

        $step_count=$this->session->userdata('health_check_count');
        if($step_count=="") $step_count=0;
        $step_count+=16;
        $insert['completed_step_count'] = $step_count;
        $this->basic->update_data('site_check_report',['id'=>$last_id],$insert);
        // end of desktop


        // mobile starts
        $mobile_result=$this->google->google_page_speed_insight_mobile($domain,"mobile");

        if (isset($mobile_result['error'])) {
            $insert['mobile_google_api_error'] = $mobile_result['error']['message'];
        }
        else{
            if (isset($mobile_result['loadingExperience'])) {
                $insert["mobile_loadingexperience_metrics"] =  isset($mobile_result['loadingExperience']) ? json_encode($mobile_result['loadingExperience']) : "";
            }
            if (isset($mobile_result['originLoadingExperience'])) {
                $insert["mobile_originloadingexperience_metrics"] =  isset($mobile_result['originLoadingExperience']) ? json_encode($mobile_result['originLoadingExperience']) : "";
            }
            if (isset($mobile_result['lighthouseResult']['configSettings'])) {
               $insert["mobile_lighthouseresult_configsettings"] =  isset($mobile_result['lighthouseResult']['configSettings']) ? json_encode($mobile_result['lighthouseResult']['configSettings']) : "";
            }
            if (isset($mobile_result['lighthouseResult']['audits'])) {

                $first_meaningful_paint1 = isset($mobile_result['lighthouseResult']['audits']['first-meaningful-paint']['score']) ? $mobile_result['lighthouseResult']['audits']['first-meaningful-paint']['score'] : 0;

                $speed_index1 = isset($mobile_result['lighthouseResult']['audits']['speed-index']['score']) ? 
                $mobile_result['lighthouseResult']['audits']['speed-index']['score'] : 0;

                $first_cpu_idle1 = isset($mobile_result['lighthouseResult']['audits']['first-cpu-idle']['score']) ? $mobile_result['lighthouseResult']['audits']['first-cpu-idle']['score'] : 0;

                $first_contentful_paint1 = isset($mobile_result['lighthouseResult']['audits']['first-contentful-paint']['score']) ? $mobile_result['lighthouseResult']['audits']['first-contentful-paint']['score'] : 0;
                $interactive1 = isset($mobile_result['lighthouseResult']['audits']['interactive']['score']) ? 
                $mobile_result['lighthouseResult']['audits']['interactive']['score'] : 0;

                $mobile_score = ($first_meaningful_paint1*7)+($speed_index1*27)+($first_cpu_idle1*13)+($first_contentful_paint1*20)+($interactive1*33);

                $insert["mobile_perfomence_score"] = $mobile_score;

                if(isset($mobile_result['lighthouseResult']['audits']['resource-summary']))
                    unset($mobile_result['lighthouseResult']['audits']['resource-summary']['details']);

                if (isset($mobile_result['lighthouseResult']['audits']['efficient-animated-content']))
                    unset($mobile_result['lighthouseResult']['audits']['efficient-animated-content']['details']);

                if (isset($mobile_result['lighthouseResult']['audits']['metrics']))
                    unset($mobile_result['lighthouseResult']['audits']['metrics']);   

                if (isset($mobile_result['lighthouseResult']['audits']['network-server-latency']))
                    unset($mobile_result['lighthouseResult']['audits']['network-server-latency']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['offscreen-images']))
                    unset($mobile_result['lighthouseResult']['audits']['offscreen-images']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['uses-responsive-images']))
                    unset($mobile_result['lighthouseResult']['audits']['uses-responsive-images']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['unused-css-rules']))
                    unset($mobile_result['lighthouseResult']['audits']['unused-css-rules']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['total-byte-weight']))
                    unset($mobile_result['lighthouseResult']['audits']['total-byte-weight']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['mainthread-work-breakdown']))
                    unset($mobile_result['lighthouseResult']['audits']['mainthread-work-breakdown']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['uses-webp-images']))
                    unset($mobile_result['lighthouseResult']['audits']['uses-webp-images']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['critical-request-chains']))
                    unset($mobile_result['lighthouseResult']['audits']['critical-request-chains']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['dom-size']))
                    unset($mobile_result['lighthouseResult']['audits']['dom-size']['details']);                

                if (isset($mobile_result['lighthouseResult']['audits']['unminified-javascript']))
                    unset($mobile_result['lighthouseResult']['audits']['unminified-javascript']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['redirects']))
                    unset($mobile_result['lighthouseResult']['audits']['redirects']['details']);   

                if (isset($mobile_result['lighthouseResult']['audits']['time-to-first-byte']))
                    unset($mobile_result['lighthouseResult']['audits']['time-to-first-byte']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['render-blocking-resources']))
                    unset($mobile_result['lighthouseResult']['audits']['render-blocking-resources']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['font-display']))
                    unset($mobile_result['lighthouseResult']['audits']['font-display']['details']);

                if (isset($mobile_result['lighthouseResult']['audits']['estimated-input-latency']))
                    unset($mobile_result['lighthouseResult']['audits']['estimated-input-latency']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['uses-rel-preconnect']))
                    unset($mobile_result['lighthouseResult']['audits']['uses-rel-preconnect']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['unminified-css']))
                    unset($mobile_result['lighthouseResult']['audits']['unminified-css']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['bootup-time']))
                    unset($mobile_result['lighthouseResult']['audits']['bootup-time']['details']);                

                if (isset($mobile_result['lighthouseResult']['audits']['uses-rel-preload']))
                    unset($mobile_result['lighthouseResult']['audits']['uses-rel-preload']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['user-timings']))
                    unset($mobile_result['lighthouseResult']['audits']['user-timings']['details']);                

                if (isset($mobile_result['lighthouseResult']['audits']['uses-text-compression']))
                    unset($mobile_result['lighthouseResult']['audits']['uses-text-compression']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['uses-optimized-images']))
                    unset($mobile_result['lighthouseResult']['audits']['uses-optimized-images']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['uses-long-cache-ttl']))
                    unset($mobile_result['lighthouseResult']['audits']['uses-long-cache-ttl']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['third-party-summary']))
                    unset($mobile_result['lighthouseResult']['audits']['third-party-summary']['details']);                
                if (isset($mobile_result['lighthouseResult']['audits']['network-rtt']))
                    unset($mobile_result['lighthouseResult']['audits']['network-rtt']['details']);                

                if (isset($mobile_result['lighthouseResult']['audits']['diagnostics']))
                    unset($mobile_result['lighthouseResult']['audits']['diagnostics']);                

                if (isset($mobile_result['lighthouseResult']['audits']['network-requests']))
                    unset($mobile_result['lighthouseResult']['audits']['network-requests']['details']);                

                if (isset($mobile_result['lighthouseResult']['audits']['screenshot-thumbnails']))
                    unset($mobile_result['lighthouseResult']['audits']['screenshot-thumbnails']);                

                if (isset($mobile_result['lighthouseResult']['audits']['main-thread-tasks']))
                    unset($mobile_result['lighthouseResult']['audits']['main-thread-tasks']);

                if (isset($mobile_result['lighthouseResult']['categories']['performance']))
                    unset($mobile_result['lighthouseResult']['categories']['performance']['auditRefs']);                
                
                $insert['mobile_lighthouseresult_audits'] = isset($mobile_result['lighthouseResult']['audits']) ? json_encode($mobile_result['lighthouseResult']['audits']) : "";                   

            }
            if (isset($mobile_result['lighthouseResult']['categories'])) {
                $insert['mobile_lighthouseresult_categories'] = isset($mobile_result['lighthouseResult']['categories']) ? json_encode($mobile_result['lighthouseResult']['categories']) : "";
            }
        }
                    
        // $insert["response_code_mobile"] = isset($mobile_result["responseCode"]) ? $mobile_result["responseCode"] : "";

        // $insert["speed_score_mobile"]   = isset($mobile_result["ruleGroups"]["SPEED"]["score"]) ? $mobile_result["ruleGroups"]["SPEED"]["score"] : "";

        // $insert["speed_usability_mobile"]   = isset($mobile_result["ruleGroups"]["USABILITY"]["score"]) ? $mobile_result["ruleGroups"]["USABILITY"]["score"] : "";

        // $pagestat_mobile     = isset($mobile_result["pageStats"]) ? $mobile_result["pageStats"] : array();
        // $insert["pagestat_mobile"]     = json_encode($pagestat_mobile);

        // $avoid_interstitials_mobile = array();
        // $avoid_interstitials_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidInterstitials"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidInterstitials"]["ruleImpact"] : 0;
        // $avoid_interstitials_mobile["app_count"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidInterstitials"]["summary"]["args"]["0"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidInterstitials"]["summary"]["args"]["0"]["value"] : 0;
        // $avoid_interstitials_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidInterstitials"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidInterstitials"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["avoid_interstitials_mobile"]=json_encode($avoid_interstitials_mobile);

        // $avoid_plugins_mobile = array();
        // $avoid_plugins_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidPlugins"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidPlugins"]["ruleImpact"] : 0;
        // $avoid_plugins_mobile["plugin_count"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidPlugins"]["summary"]["args"]["0"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidPlugins"]["summary"]["args"]["0"]["value"] : 0;
        // $avoid_plugins_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidPlugins"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidPlugins"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["avoid_plugins_mobile"]=json_encode($avoid_plugins_mobile);

        // $configure_viewport_mobile = array();
        // $configure_viewport_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["ConfigureViewport"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["ConfigureViewport"]["ruleImpact"] : 0;
        // $insert["configure_viewport_mobile"]=json_encode($configure_viewport_mobile);

        // $size_content_to_viewport_mobile = array();
        // $size_content_to_viewport_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["SizeContentToViewport"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["SizeContentToViewport"]["ruleImpact"] : 0;
        // $size_content_to_viewport_mobile["content_width"] = isset($mobile_result["formattedResults"]["ruleResults"]["SizeContentToViewport"]["urlBlocks"]["0"]["header"]["args"]["0"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["SizeContentToViewport"]["urlBlocks"]["0"]["header"]["args"]["0"]["value"] : "";
        // $size_content_to_viewport_mobile["viewport_width"] = isset($mobile_result["formattedResults"]["ruleResults"]["SizeContentToViewport"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["SizeContentToViewport"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $size_content_to_viewport_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["SizeContentToViewport"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["SizeContentToViewport"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["size_content_to_viewport_mobile"]=json_encode($size_content_to_viewport_mobile);

        // $size_tap_targets_appropriately_mobile = array();
        // $size_tap_targets_appropriately_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["SizeTapTargetsAppropriately"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["SizeTapTargetsAppropriately"]["ruleImpact"] : 0;
        // $size_tap_targets_appropriately_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["SizeTapTargetsAppropriately"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["SizeTapTargetsAppropriately"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["size_tap_targets_appropriately_mobile"]=json_encode($size_tap_targets_appropriately_mobile);

        // $use_legible_font_sizes_mobile = array();
        // $use_legible_font_sizes_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["UseLegibleFontSizes"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["UseLegibleFontSizes"]["ruleImpact"] : 0;
        // $use_legible_font_sizes_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["UseLegibleFontSizes"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["UseLegibleFontSizes"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["use_legible_font_sizes_mobile"]=json_encode($use_legible_font_sizes_mobile);

        // $avoid_landing_page_redirects_mobile = array();
        // $avoid_landing_page_redirects_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["ruleImpact"] : 0;
        // $avoid_landing_page_redirects_mobile["redirect_count"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["summary"]["args"]["0"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["summary"]["args"]["0"]["value"] : 0;
        // $avoid_landing_page_redirects_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["AvoidLandingPageRedirects"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["avoid_landing_page_redirects_mobile"]=json_encode($avoid_landing_page_redirects_mobile);

        // $gzip_compression_mobile = array();
        // $gzip_compression_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["ruleImpact"] : 0;
        // $gzip_compression_mobile["total_size_compressable"] = isset($mobile_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $gzip_compression_mobile["total_percentage_compressable"] = isset($mobile_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $gzip_compression_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["EnableGzipCompression"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["gzip_compression_mobile"]=json_encode($gzip_compression_mobile);

        // $leverage_browser_caching_mobile = array();
        // $leverage_browser_caching_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["LeverageBrowserCaching"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["LeverageBrowserCaching"]["ruleImpact"] : 0;
        // $leverage_browser_caching_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["LeverageBrowserCaching"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["LeverageBrowserCaching"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["leverage_browser_caching_mobile"]=json_encode($leverage_browser_caching_mobile);

        // $main_resource_server_response_time_mobile = array();
        // $main_resource_server_response_time_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["MainResourceServerResponseTime"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["MainResourceServerResponseTime"]["ruleImpact"] : 0;
        // $main_resource_server_response_time_mobile["response_time"] = isset($mobile_result["formattedResults"]["ruleResults"]["MainResourceServerResponseTime"]["urlBlocks"]["0"]["header"]["args"]["0"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["MainResourceServerResponseTime"]["urlBlocks"]["0"]["header"]["args"]["0"]["value"] : "";
        // $insert["main_resource_server_response_time_mobile"]=json_encode($main_resource_server_response_time_mobile);

        // $minify_css_mobile = array();
        // $minify_css_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyCss"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyCss"]["ruleImpact"] : 0;
        // $minify_css_mobile["total_size_minifiable"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $minify_css_mobile["total_percentage_minifiable"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $minify_css_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyCss"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["minify_css_mobile"]=json_encode($minify_css_mobile);

        // $minify_html_mobile = array();
        // $minify_html_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyHTML"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyHTML"]["ruleImpact"] : 0;
        // $minify_html_mobile["total_size_minifiable"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $minify_html_mobile["total_percentage_minifiable"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $minify_html_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyHTML"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["minify_html_mobile"]=json_encode($minify_html_mobile);

        // $minify_javaScript_mobile = array();
        // $minify_javaScript_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["ruleImpact"] : 0;
        // $minify_javaScript_mobile["total_size_minifiable"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $minify_javaScript_mobile["total_percentage_minifiable"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $minify_javaScript_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinifyJavaScript"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["minify_javaScript_mobile"]=json_encode($minify_javaScript_mobile);

        // $minimize_render_blocking_resources_mobile = array();
        // $minimize_render_blocking_resources_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["ruleImpact"] : 0;
        // $minimize_render_blocking_resources_mobile["js_urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["urlBlocks"]["1"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["urlBlocks"]["1"]["urls"] : array();
        // $minimize_render_blocking_resources_mobile["css_urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["urlBlocks"]["2"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["MinimizeRenderBlockingResources"]["urlBlocks"]["2"]["urls"] : array();
        // $insert["minimize_render_blocking_resources_mobile"]=json_encode($minimize_render_blocking_resources_mobile);

        // $optimize_images_mobile = array();
        // $optimize_images_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["OptimizeImages"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["OptimizeImages"]["ruleImpact"] : 0;
        // $optimize_images_mobile["total_size_optimizable"] = isset($mobile_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["header"]["args"]["1"]["value"] : "";
        // $optimize_images_mobile["total_percentage_optimizable"] = isset($mobile_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"]) ? $mobile_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["header"]["args"]["2"]["value"] : "";
        // $optimize_images_mobile["urls"] = isset($mobile_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["urls"]) ? $mobile_result["formattedResults"]["ruleResults"]["OptimizeImages"]["urlBlocks"]["0"]["urls"] : array();
        // $insert["optimize_images_mobile"]=json_encode($optimize_images_mobile);

        // $prioritize_visible_content_mobile = array();
        // $prioritize_visible_content_mobile["rule_impact"] = isset($mobile_result["formattedResults"]["ruleResults"]["PrioritizeVisibleContent"]["ruleImpact"]) ? $mobile_result["formattedResults"]["ruleResults"]["PrioritizeVisibleContent"]["ruleImpact"] : 0;
        // $insert["prioritize_visible_content_mobile"]=json_encode($prioritize_visible_content_mobile);

        $step_count+=16;
        $insert['completed_step_count'] = $step_count;
        $this->basic->update_data('site_check_report',['id'=>$last_id],$insert);
        // end of mobile

        //$insert["mobile_ready_data"] = $this->google->mobile_ready($domain);
        $insert["perfomence_category"] = "Performance";
        $step_count+=30;
        $insert['completed_step_count'] = $step_count;
        $this->basic->update_data('site_check_report',['id'=>$last_id],$insert);


        $insert["alexa_rank"] = json_encode($this->sitedoctor_library->get_alexa_rank($domain));
        $insert["domain_ip_info"] = json_encode($this->sitedoctor_library->get_ip_country($domain,$proxy=''));


        $all_scores = array();

        $mobile_ready_data = json_decode($insert["mobile_ready_data"], true);
        //$all_scores['mobile_friendly_check'] = $mobile_ready_data["ruleGroups"]["USABILITY"]["score"];

        //$all_scores['page_speed_mobile'] = $insert['speed_score_mobile'];

        //$all_scores['usability_score_mobile'] = $insert['speed_usability_mobile'];

        //$all_scores['page_speed_desktop'] = $insert['speed_score'];

        $all_scores['page_title'] = $insert['title'];

        $all_scores['meta_description'] = $insert['description'];

        $all_scores['text_html_ratio'] = $insert['text_to_html_ratio'];

        $all_scores['robot_txt'] = $insert['robot_txt_exist'];

        $all_scores['sitemap_exist'] = $insert['sitemap_exist'];

        $all_scores['is_favicon_found'] = $insert['is_favicon_found'];

        $all_scores['image_without_alt_count'] = $insert['image_without_alt_count'];

        $all_scores['doctype_is_exist'] = $insert['doctype_is_exist'];

        $depreciated_html_tag=json_decode($insert["depreciated_html_tag"],true);
        $depreciated_html_tag=array_sum($depreciated_html_tag);       
        $all_scores['depreciated_html_tag'] = $depreciated_html_tag;

        $all_scores['total_page_size_general']=round($insert["total_page_size_general"]);

        $all_scores['page_size_gzip'] = round($insert["page_size_gzip"]);

        $inline_css=json_decode($insert["inline_css"],true);
        $inline_css=count($inline_css);
        $all_scores['inline_css'] = $inline_css;

        $internal_css=json_decode($insert["internal_css"],true);
        $internal_css=count($internal_css);
        $all_scores['internal_css'] = $internal_css;

        $micro_data_schema_list=json_decode($insert["micro_data_schema_list"],true);
        $micro_data_schema_list=count($micro_data_schema_list);       
        $all_scores['micro_data_schema_list'] = $micro_data_schema_list;

        $all_scores['is_ip_canonical'] = $insert["is_ip_canonical"];

        $all_scores['is_url_canonicalized'] = $insert["is_url_canonicalized"];

        $email_list=json_decode($insert["email_list"],true);
        $email_list=count($email_list);
        $all_scores['email_list'] = $email_list;

        $meta_keyword=$insert["meta_keyword"];
        $meta_keyword_check=empty($meta_keyword) ? 1 : 0;
        $all_scores['meta_keyword'] = $meta_keyword_check;

        $one_phrase=json_decode($insert["keyword_one_phrase"],true); 
        $two_phrase=json_decode($insert["keyword_two_phrase"],true); 
        $three_phrase=json_decode($insert["keyword_three_phrase"],true); 
        $four_phrase=json_decode($insert["keyword_four_phrase"],true); 

        $keyword_usage=$this->sitedoctor_library->keyword_usage_check($insert["meta_keyword"],array_keys($one_phrase),array_keys($two_phrase),array_keys($three_phrase),array_keys($four_phrase));
        $all_scores['keyword_usage'] = $keyword_usage;

        $not_seo_friendly_link=json_decode($insert["not_seo_friendly_link"],true);
        $not_seo_friendly_link=count($not_seo_friendly_link);
        $all_scores['not_seo_friendly_link'] = $not_seo_friendly_link;

        $all_scores['html_headings']=0;
        $h1=json_decode($insert["h1"],true); 
        if(count($h1) > 0) {
            $all_scores['html_headings']+=0.5;
        }
        $h2=json_decode($insert["h2"],true); 
        if(count($h2) > 0) {
            $all_scores['html_headings']+=0.2;
        }
        $h3=json_decode($insert["h3"],true); 
        if(count($h3) > 0) {
            $all_scores['html_headings']+=0.2;
        }
        $h4=json_decode($insert["h4"],true); 
        if(count($h4) > 0) {
            $all_scores['html_headings']+=0.2;
        }
        $h5=json_decode($insert["h5"],true); 
        if(count($h5) > 0) {
            $all_scores['html_headings']+=0.2;
        }
        $h6=json_decode($insert["h6"],true);  
        if(count($h6) > 0) {
            $all_scores['html_headings']+=0.2;
        }        

        $insert["overall_score"] = $this->sitedoctor_library->get_overall_score($all_scores);

        
        $step_count++;
        $insert['completed_step_count'] = $step_count;
        $this->basic->update_data('site_check_report',['id'=>$last_id],$insert);
        // $this->basic->update_data('site_check_report',['id'=>$last_id],['completed_step_count'=>$step_count]);

        if($compare==1)
            $details_url=site_url('sitedoctor/comparison_report'."/".$comparision_id);
        else 
            $details_url=site_url('sitedoctor/report'."/".$last_id.'/'.$this->sitedoctor_library->clean_domain_name($domain));

        $response=array("status"=>"1","details_url"=>$details_url);

        echo json_encode($response);
    }

    public function progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('health_check_total'); 
        $comparision_id = $this->session->userdata('compare_table_id');
        $domain=$this->session->userdata('sitedoc_report_completed_for_domain');
        $insert_table_id = $this->session->userdata('insert_table_id_sitedoctor');
        $is_compare = $this->session->userdata('is_compare');

        $bulk_complete_search = 0;
        $info = $this->basic->get_data('site_check_report',['where'=>['id'=>$insert_table_id,'user_id'=>$this->session->userdata('user_id')]],['completed_step_count']);
        $bulk_complete_search=isset($info[0]['completed_step_count']) ? (int)$info[0]['completed_step_count'] : 0;

        $response['details_url'] = 'not_set';
        $link = '';
        if($is_compare == '1')
        {
            if($comparision_id != '')
            {              
                $link = site_url('sitedoctor/comparison_report'."/".$comparision_id);
            }
        }
        if($is_compare == '0')
        {
            if($insert_table_id != '')
            {              
                $this->load->library("sitedoctor_library");
                $link = site_url('sitedoctor/report'."/".$insert_table_id.'/'.$this->sitedoctor_library->clean_domain_name($domain));
            }
        }
        if($link != '') $response['details_url'] = $link;  
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;   

        echo json_encode($response);        
    }

    public function report($id=0,$domain="")
    {

        if($id==0) exit();
        $where['where'] = array('id'=>$id);
        $data["site_info"] = $this->basic->get_data("site_check_report",$where);


        if(isset($data["site_info"][0])) $page_title= strtolower($data["site_info"][0]["domain_name"]);
        else exit();

        $data["page_title"]=str_replace(array("www.","http://","https://"), "", $page_title);

        $data['seo_meta_description']="web site healthy check report of ".$page_title." by ".$this->config->item("product_short_name");

        $data["load_css_js"]=0;
        $data["is_pdf"]=0;
        $data["compare_report"]=0; // this is for generating general and comaparative health report with one view file

        $this->load->library("sitedoctor_library");
        // $this->config->load('sitedoctor_config');
        $this->load->view("report",$data);
    } 

    public function report_pdf($id=0,$domain="")
    {

        if($id==0) exit();
        $where['where'] = array('id'=>$id);
        $data["site_info"] = $this->basic->get_data("site_check_report",$where);
        $data["user_data"] = $this->basic->get_data("users",array("where"=>array("id"=>$this->session->userdata("user_id"))));

        if(isset($data["site_info"][0])) $page_title= strtolower($data["site_info"][0]["domain_name"]);
        else exit();

        $data["page_title"]=str_replace(array("www.","http://","https://"), "", $page_title);       
        $this->load->library("sitedoctor_library");
        $data["load_css_js"]=1;
        $data["compare_report"]=0;
        $data["is_pdf"]=1;
        // $this->config->load('sitedoctor_config');

        ob_start();
        include(APPPATH ."vendor/autoload.php");
        $this->load->view("report",$data); 
        ob_get_contents();
        $html=ob_get_clean();   
        include(APPPATH ."vendor/mpdf/mpdf/src/Mpdf.php");
        $mpdf2=new \Mpdf\Mpdf();
        $mpdf2->addPage();
        $mpdf2->SetDisplayMode('fullpage');
        $mpdf2->writeHTML($html);      
        $domain=str_replace("/","", $data["page_title"]);
        $domain=trim($domain);
        $download_id=$this->session->userdata('download_id_front').$this->_random_number_generator(10);
        $file_name=$domain."_health_check_report_".$download_id.".pdf";
        $mpdf2->output($file_name,"I");

    }

    public function direct_download()
    {
        if($_POST)
        {
            $id=$this->input->post("hidden_id");            
            $file_name=$this->report_pdf($id);
            $download_link=base_url().$file_name;
            echo '<div class="box-body chart-responsive minus"><div class="col-xs-12"><div class="alert text-center" style="font-size:18px">'.$this->lang->line("pdf report has been generated"). '<br/> <br/><a href="'.$download_link.'" target="_BLANK" style="font-size:20px"> <i class="fa fa-cloud-download"></i> '.$this->lang->line("click here to download").'</a></div></div></div>';
        }
    }


    public function comparison_report($id=0)
    {
        if($id==0) exit();

        $where['where'] = array('comparision.id'=>$id);
        $select=array("comparision.base_site","comparision.competutor_site","comparision.searched_at","comparision.id as id");
        $data["comparision_info"] = $this->basic->get_data("comparision",$where,$select);
        if(!isset($data["comparision_info"][0])) exit();    

        $where['where'] = array('id'=>$data["comparision_info"][0]["base_site"]);
        $data["site_info"] = $this->basic->get_data("site_check_report",$where);
        if(!isset($data["site_info"][0])) exit();

        $where['where'] = array('id'=>$data["comparision_info"][0]["competutor_site"]);
        $data["site_info2"] = $this->basic->get_data("site_check_report",$where);
        if(!isset($data["site_info2"][0])) exit();


        $data["comparision_info"][0]["base_domain"]=$data["site_info"][0]["domain_name"];
        $data["comparision_info"][0]["competutor_domain"]=$data["site_info2"][0]["domain_name"];
        $page_title= strtolower($data["comparision_info"][0]["base_domain"])." Vs ".strtolower($data["comparision_info"][0]["competutor_domain"]);

        $page_title=str_replace(array("www.","http://","https://"), "", $page_title);
        $data["page_title"]=$page_title;       
        $data['seo_meta_description']="web site healthy check report of ".$page_title." by ".$this->config->item("product_short_name");

        $this->load->library("sitedoctor_library");
        // $this->config->load('sitedoctor_config');
        $data["load_css_js"]=0;
        $data["is_pdf"]=0;
        $data["compare_report"]=1; // this is for generating general and comaparative health report with one view file
        $this->load->view("comparison_report",$data);
    }


    public function comparision_report_pdf($id=0)
    {

        if($id==0) exit();

        $where['where'] = array('comparision.id'=>$id);
        $select=array("comparision.base_site","comparision.competutor_site","comparision.searched_at","comparision.id as id");
        $data["comparision_info"] = $this->basic->get_data("comparision",$where,$select);
        if(!isset($data["comparision_info"][0])) exit();

        $where['where'] = array('id'=>$data["comparision_info"][0]["base_site"]);
        $data["site_info"] = $this->basic->get_data("site_check_report",$where);
        if(!isset($data["site_info"][0])) exit();

        $where['where'] = array('id'=>$data["comparision_info"][0]["competutor_site"]);
        $data["site_info2"] = $this->basic->get_data("site_check_report",$where);
        if(!isset($data["site_info2"][0])) exit();

        $data["comparision_info"][0]["base_domain"]=$data["site_info"][0]["domain_name"];
        $data["comparision_info"][0]["competutor_domain"]=$data["site_info2"][0]["domain_name"];
        $page_title= strtolower($data["comparision_info"][0]["base_domain"])." Vs ".strtolower($data["comparision_info"][0]["competutor_domain"]);

        $page_title=str_replace(array("www.","http://","https://"), "", $page_title);
        $data["page_title"]=$page_title;     

        $data["user_data"] = $this->basic->get_data("users",array("where"=>array("id"=>$this->session->userdata("user_id"))));  

        $this->load->library("sitedoctor_library");
        $data["load_css_js"]=1;
        $data["compare_report"]=1;
        $data["is_pdf"]=1;
        // $this->config->load('sitedoctor_config');

        ob_start();
        include(APPPATH ."vendor/autoload.php");
        $this->load->view("comparison_report",$data); 
        ob_get_contents();
        $html=ob_get_clean();   
        include(APPPATH ."vendor/mpdf/mpdf/src/Mpdf.php");
        $mpdf2=new \Mpdf\Mpdf();
        $mpdf2->addPage();
        $mpdf2->SetDisplayMode('fullpage');
        $mpdf2->writeHTML($html);          
        $domain=str_replace(array("/"," "),"", $data["page_title"]);
        $domain=trim($domain);
        $download_id=$this->session->userdata('download_id_front').$this->_random_number_generator(10);
        $file_name="Website_health_comparision_report_".$domain."_".$download_id.".pdf";
        $mpdf2->output($file_name, 'I');

    }

    public function direct_download_comparision()
    {
        if($_POST)
        {            
            $id=$this->input->post("hidden_id");
            $file_name=$this->comparision_report_pdf($id);
            $download_link=base_url().$file_name;
            echo '<div class="box-body chart-responsive minus"><div class="col-xs-12"><div class="alert text-center" style="font-size:18px">'.$this->lang->line("pdf report has been generated"). '<br/> <br/><a href="'.$download_link.'" target="_BLANK" style="font-size:20px"> <i class="fa fa-cloud-download"></i> '.$this->lang->line("click here to download").'</a></div></div></div>';

        }
    }

    public function delete_website_health_report()
    {
        $this->ajax_check();

        $table_id = $this->input->post("table_id",true);

        if($table_id == "" || $table_id == "0") exit;

        $sql = "";

        if($this->basic->delete_data("site_check_report",array("id"=>$table_id,"user_id"=>$this->user_id))) {

            $sql = "DELETE FROM comparision WHERE user_id=".$this->user_id." AND base_site=".$table_id." OR competutor_site=".$table_id;

            if($sql != "") {

                $this->db->query($sql);
            }

            echo "1";

        } else {

            echo "0";
        }
    }

    public function comparative_check_report()
    {
        $data['body'] = 'comaparative_check_lists';
        $data['page_title'] = $this->lang->line("comparitive health Checker");
        $this->_viewcontroller($data);
    }

    public function comparative_check_report_data()
    {
        $this->ajax_check();
        $this->load->library("sitedoctor_library");

        $base_website       = trim($this->input->post("base_website",true));
        $competitor_website = trim($this->input->post("competitor_website",true));
        $post_date_range    = $this->input->post("post_date_range",true);
        $display_columns    = array("#",'id','domain_name','warning_count','mobile_perfomence_score','actions','desktop_perfomence_score','overall_score','searched_at');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_simple=array();

        if($post_date_range!="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

            if($from_date!="Invalid date" && $to_date!="Invalid date")
            {
                $from_date = date('Y-m-d', strtotime($from_date));
                $to_date   = date('Y-m-d', strtotime($to_date));
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($base_website !="") {
            $where_simple['base_site_table.domain_name like'] = "%".$base_website."%";
        }

        if($competitor_website !="") {
            $where_simple['competutor_site_table.domain_name like'] = "%".$competitor_website."%";
        }

        $where_simple['comparision.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);
        $join=array('site_check_report as base_site_table'=>"base_site_table.id=comparision.base_site,left",'site_check_report as competitor_site_table'=>"competitor_site_table.id=comparision.competutor_site,left");
        $select= array(
            "base_site_table.domain_name as base_domain",
            "competitor_site_table.domain_name as competitor_domain",
            "comparision.base_site",
            "comparision.competutor_site",
            "comparision.searched_at",
            "comparision.email",
            "comparision.id as id",

            "base_site_table.warning_count as base_warning_count",
            "competitor_site_table.warning_count as competitor_warning_count",

            "base_site_table.mobile_perfomence_score as base_mobile_perfomence_score",
            "competitor_site_table.mobile_perfomence_score as competitor_mobile_perfomence_score",

            "base_site_table.desktop_perfomence_score as base_desktop_perfomence_score",
            "competitor_site_table.desktop_perfomence_score as competitor_desktop_perfomence_score",

            // "base_site_table.perfomence_category as base_speed_usability_mobile",
            // "competitor_site_table.perfomence_category as competitor_speed_usability_mobile",

            "base_site_table.overall_score as base_overall_score",
            "competitor_site_table.overall_score as competitor_overall_score"
        );

        $table = "comparision";

        $info = $this->basic->get_data($table,$where,$select,$join,$limit,$start,$order_by,$group_by='');
        
        for($i=0;$i<count($info);$i++)
        {  
            $info[$i]['id'] = $info[$i]['id'];
            $info[$i]['domain_name'] = "<a target='_BLANK' href='".$info[$i]['base_domain']."'>".$this->sitedoctor_library->clean_domain_name($info[$i]['base_domain'])."</a> - <a target='_BLANK' href='".$info[$i]['competitor_domain']."'>".$this->sitedoctor_library->clean_domain_name($info[$i]['competitor_domain'])."</a>";
            $info[$i]['searched_at'] = date("M j, Y h:i A",strtotime($info[$i]['searched_at']));
            $info[$i]['details'] = "<a class='label label-warning' href='".base_url()."sitedoctor/comparison_report/".$info[$i]['id']."'><i class='fa fa-file'></i> ".$this->lang->line('report')."</a>";

            $info[$i]['warning_count'] = $info[$i]['base_warning_count']." - ".$info[$i]['competitor_warning_count'];            
            $info[$i]['mobile_perfomence_score'] = $info[$i]['base_mobile_perfomence_score']." - ".$info[$i]['competitor_mobile_perfomence_score'];            
            $info[$i]['desktop_perfomence_score'] = $info[$i]['base_desktop_perfomence_score']." - ".$info[$i]['competitor_desktop_perfomence_score'];            
            // $info[$i]['perfomence_category'] = $info[$i]['base_speed_usability_mobile']." - ".$info[$i]['competitor_speed_usability_mobile'];            
            $info[$i]['overall_score'] = $info[$i]['base_overall_score']." - ".$info[$i]['competitor_overall_score']; 


            $action_count = 3;
            $report_url = base_url()."sitedoctor/comparison_report/".$info[$i]['id'];
            $download_url = base_url()."sitedoctor/comparision_report_pdf/".$info[$i]['id'];

            $report_btn = "<a href='".$report_url."' target='_BLANK' class='btn btn-circle btn-outline-primary' data-toggle='tooltip' title='".$this->lang->line("View Report")."'><i class='fas fa-eye'></i></a>";
            $download_bottupm = "<a target='_blank' href='".$download_url."' class='btn btn-circle btn-outline-success download_report' table_id='".$info[$i]['id']."' data-toggle='tooltip' title='".$this->lang->line("Downaload Report")."'><i class='fas fa-cloud-download-alt'></i></a>";
            $delete_btn = "<a href='#' class='btn btn-circle btn-outline-danger delete_website' table_id='".$info[$i]['id']."' data-toggle='tooltip' title='".$this->lang->line("Delete")."'><i class='fas fa-trash-alt'></i></a>";

            $action_width = ($action_count*47)+20;
            $info[$i]['actions'] ='
            <div class="dropdown d-inline dropright text-center">
              <button class="btn btn-outline-primary dropdown-toggle no_caret" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-briefcase"></i>
              </button>
              <div class="dropdown-menu mini_dropdown text-center" style="width:'.$action_width.'px !important">';
                $info[$i]['actions'] .= $report_btn;
                $info[$i]['actions'] .= $download_bottupm;
                $info[$i]['actions'] .= $delete_btn;
                $info[$i]['actions'] .="
              </div>
            </div>
            <script>
            $('[data-toggle=\"tooltip\"]').tooltip();</script>";
        
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }

    public function comparative_check_report_delete()
    {
        $all=$this->input->post("all");

        if($all==0)
        {
            $selected_grid_data = $this->input->post('info', true);
            $json_decode = json_decode($selected_grid_data, true);
            $id_array = array();
            foreach ($json_decode as  $value) 
            {
                $id_array[] = $value['id'];
            }

            $where['where_in'] = array('id'=>$id_array);
            $site_check_table_ids = $this->basic->get_data('comparision',$where);
            $site_check_delete_ids = array();
            foreach($site_check_table_ids as $ids_to_delete){
                array_push($site_check_delete_ids, $ids_to_delete['base_site']);
                array_push($site_check_delete_ids, $ids_to_delete['competutor_site']);
            } 
            $this->db->where_in('id', $id_array);
        } 
        else $this->db->where("user_id",$this->session->userdata("user_id"));

        $this->db->delete('comparision');

        if($all==0)
        {
            $this->db->where_in('id',$site_check_delete_ids);
        } 
        else $this->db->where("user_id",$this->session->userdata("user_id"));

        $this->db->delete('site_check_report');
    }

    public function activate()
    {
        if(!$_POST) exit();

        $is_free_addon=false; 
        $addon_controller_name=ucfirst($this->router->fetch_class()); // here addon_controller_name name is Comment [origianl file is Comment.php, put except .php]
        $purchase_code=$this->input->post('purchase_code');
        if(!$is_free_addon)
        {
            $this->addon_credential_check($purchase_code,strtolower($addon_controller_name)); // retuns json status,message if error
        }

        //this addon system support 2-level sidebar entry, to make sidebar entry you must provide 2D array like below
        $sidebar=[];

        // mysql raw query needed to run, it's an array, put each query in a seperate index, create table query must should IF NOT EXISTS
        $sql=array
        (
            0 =>"CREATE TABLE IF NOT EXISTS `comparision` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `base_site` int(11) NOT NULL DEFAULT '0',
            `competutor_site` int(11) NOT NULL DEFAULT '0',
            `email` longtext NOT NULL,
            `searched_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            `user_id` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `base_site` (`base_site`,`competutor_site`,`user_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
            1=>"CREATE TABLE IF NOT EXISTS `site_check_report` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `domain_name` varchar(200) DEFAULT NULL,
            `searched_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            `response_code` varchar(50) DEFAULT NULL,
            `speed_score` varchar(50) DEFAULT NULL,
            `pagestat` text,
            `avoid_landing_page_redirects` text,
            `gzip_compression` text,
            `leverage_browser_caching` text,
            `main_resource_server_response_time` text,
            `minify_css` text,
            `minify_html` text,
            `minify_javaScript` text,
            `minimize_render_blocking_resources` text,
            `optimize_images` text,
            `prioritize_visible_content` text,
            `response_code_mobile` varchar(50) DEFAULT NULL,
            `speed_score_mobile` varchar(50) DEFAULT NULL,
            `speed_usability_mobile` varchar(50) DEFAULT NULL,
            `pagestat_mobile` text,
            `avoid_interstitials_mobile` text,
            `avoid_plugins_mobile` text,
            `configure_viewport_mobile` text,
            `size_content_to_viewport_mobile` text,
            `size_tap_targets_appropriately_mobile` text,
            `use_legible_font_sizes_mobile` text,
            `avoid_landing_page_redirects_mobile` text,
            `gzip_compression_mobile` text,
            `leverage_browser_caching_mobile` text,
            `main_resource_server_response_time_mobile` text,
            `minify_css_mobile` text,
            `minify_html_mobile` text,
            `minify_javaScript_mobile` text,
            `minimize_render_blocking_resources_mobile` text,
            `optimize_images_mobile` text,
            `prioritize_visible_content_mobile` text,
            `title` text,
            `description` text,
            `meta_keyword` text,
            `viewport` varchar(50) DEFAULT NULL,
            `h1` text,
            `h2` text,
            `h3` text,
            `h4` text,
            `h5` text,
            `h6` text,
            `noindex_by_meta_robot` varchar(50) DEFAULT NULL,
            `nofollowed_by_meta_robot` varchar(50) DEFAULT NULL,
            `keyword_one_phrase` text,
            `keyword_two_phrase` text,
            `keyword_three_phrase` text,
            `keyword_four_phrase` text,
            `total_words` varchar(50) DEFAULT NULL,
            `robot_txt_exist` varchar(50) DEFAULT NULL,
            `robot_txt_content` text,
            `sitemap_exist` varchar(50) DEFAULT NULL,
            `sitemap_location` text,
            `external_link_count` varchar(50) DEFAULT NULL,
            `internal_link_count` varchar(50) DEFAULT NULL,
            `nofollow_link_count` varchar(50) DEFAULT NULL,
            `dofollow_link_count` varchar(50) DEFAULT NULL,
            `external_link` text,
            `internal_link` text,
            `nofollow_internal_link` text,
            `not_seo_friendly_link` text,
            `image_without_alt_count` varchar(50) DEFAULT NULL,
            `image_not_alt_list` text,
            `inline_css` text,
            `internal_css` text,
            `depreciated_html_tag` text,
            `is_favicon_found` varchar(50) DEFAULT NULL,
            `favicon_link` varchar(50) DEFAULT NULL,
            `total_page_size_general` varchar(50) DEFAULT NULL,
            `page_size_gzip` varchar(50) DEFAULT NULL,
            `is_gzip_enable` varchar(50) DEFAULT NULL,
            `doctype` varchar(50) DEFAULT NULL,
            `doctype_is_exist` varchar(50) DEFAULT NULL,
            `nofollow_link_list` text,
            `canonical_link_list` text,
            `noindex_list` text,
            `micro_data_schema_list` text,
            `is_ipv6_compatiable` varchar(50) DEFAULT NULL,
            `ipv6` varchar(50) DEFAULT NULL,
            `ip` varchar(50) DEFAULT NULL,
            `dns_report` text,
            `is_ip_canonical` varchar(50) DEFAULT NULL,
            `email_list` text,
            `is_url_canonicalized` varchar(50) DEFAULT NULL,
            `text_to_html_ratio` varchar(50) DEFAULT NULL,
            `general_curl_response` text,
            `mobile_ready_data` text,
            `warning_count` varchar(50) DEFAULT NULL,
            `email` longtext NOT NULL,
            `domain_ip_info` text,
            `alexa_rank` text,
            `overall_score` double NOT NULL,
            `user_id` int(11) NOT NULL,
            `completed_step_count` int(11) NOT NULL,
            `desktop_loadingexperience_metrics` text,
            `desktop_lighthouseresult_configsettings` text,
            `desktop_perfomence_score` text,
            `desktop_lighthouseresult_audits` text,
            `desktop_lighthouseresult_categories` text,
            `mobile_loadingexperience_metrics` text,
            `mobile_lighthouseresult_configsettings` text,
            `mobile_perfomence_score` text,
            `mobile_lighthouseresult_audits` text,
            `mobile_lighthouseresult_categories` text,
            `perfomence_category` text,
            `mobile_originloadingexperience_metrics` text,
            `desktop_originloadingexperience_metrics` text,
            PRIMARY KEY (`id`),
            KEY `domain_name` (`domain_name`,`searched_at`,`user_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
        ); 

        //send blank array if you does not need sidebar entry,send a blank array if your addon does not need any sql to run
        $this->register_addon($addon_controller_name,$sidebar,$sql,$purchase_code); 
    }


    public function deactivate()
    {        
        $addon_controller_name=ucfirst($this->router->fetch_class()); // here addon_controller_name name is Comment [origianl file is Comment.php, put except .php]
        $this->unregister_addon($addon_controller_name);         
        }

        public function delete()
        {        
        $addon_controller_name=ucfirst($this->router->fetch_class()); // here addon_controller_name name is Comment [origianl file is Comment.php, put except .php]

        // mysql raw query needed to run, it's an array, put each query in a seperate index, drop table/column query should have IF EXISTS
        $sql=array
        (
        0=>"DROP TABLE IF EXISTS `comparision`;",
        1=>"DROP TABLE IF EXISTS `site_check_report`;"
        );  

        // deletes add_ons,modules and menu, menu_child1 table ,custom sql as well as module folder, no need to send sql or send blank array if you does not need any sql to run on delete
        $this->delete_addon($addon_controller_name,$sql);         
    }


}