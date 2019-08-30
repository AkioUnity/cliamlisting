<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BusinessDirectory admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		Show
 * @subpackage	ShowCore
 * @author		webhelios
 * @link		http://webhelios.com
 */



class Show_core extends CI_controller {

	var $PER_PAGE;
	var $active_theme = '';

	public function __construct()
	{
		parent::__construct();
		is_installed(); #defined in auth helper		
		
		expiration_cron();
		//$this->PER_PAGE = get_per_page_value();#defined in auth helper
		
		$this->PER_PAGE = get_settings('business_settings', 'posts_per_page', 6);


		$this->active_theme = get_active_theme();
		$this->load->model('show_model');

        $system_currency_type = get_settings('business_settings','system_currency_type',0);
        if($system_currency_type == 0)
        {
            $system_currency = get_currency_icon(get_settings('business_settings','system_currency','USD'));
        }
        else
        {
            $system_currency = get_settings('business_settings','system_currency','USD');
        }

        $this->session->set_userdata('system_currency',$system_currency);
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
	}

	public function index()
	{
		$this->home();	
	}

	public function home($banner_type='Layer Slider')
	{			
		$value = array();
		$data['content'] 	= load_view('home_view',$value,TRUE);
		$data['alias']	    = 'home';
		$data['sub_title']	= lang_key('home_page_title');
		$data['banner_type']	= urldecode($banner_type);
		load_template($data,$this->active_theme);
	}

	public function detail($unique_id='')
	{	
		$value = array();
		$this->load->model('user/post_model');
		$value['post'] = $this->post_model->get_post_by_unique_id($unique_id);
		$data['content'] 		= load_view('detail_view',$value,TRUE);

		$data['alias']	    = 'detail';
		$id = 0;
		$status = 1;
		if($value['post']->num_rows()>0)
		{			
			$row 	= $value['post']->row();
			$status = $row->status;	
			$id = $row->id;
			$seo['key_words'] 	= $row->tags;
			$title 				= get_post_data_by_lang($row,'title');
			$description 		= get_post_data_by_lang($row,'description');
			$this->post_model->inc_view_count_by_unique_id($unique_id);
		}

		if($status!=1)
		{
			$this->output->set_status_header('404');
			$data['content'] 	= load_view('404_view','',TRUE);
	        load_template($data,$this->active_theme,'template_view');			
		}
		else
		{

			$data['sub_title']			= $title;
			$description 	= strip_tags($description);
			$description 	= str_replace("'","",$description);
			$description 	= str_replace('"',"",$description);
			$seo['meta_description']	= $description;
			$seo['key_words'] = $row->tags;
			$data['seo']				= $seo;
			load_template($data,$this->active_theme);
		}
	}

	public function printview($unique_id='')
	{	
		$value['post']		= $this->show_model->get_post_by_unique_id($unique_id);
		$data['content'] 	= load_view('print_view',$value,TRUE);
		echo $data['content'];
	}

	public function embed($unique_id='')
	{	
		$value['post']		= $this->show_model->get_post_by_unique_id($unique_id);
		load_view('embed_view',$value);
	}


    public function terms()
    {
        $data['content'] 	= load_view('termscondition_view','',TRUE);
        $data['alias']	    = 'terms';
        load_template($data,$this->active_theme);
    }

    public function advfilter()
    {
    	$string = '';

    	foreach ($_POST as $key => $value) 
    	{
    		if(is_array($value))
    		{
    			$val = '';
    			foreach ($value as $row) {
    				$val .= $row.',';
    			}
    			$string .= $key.'='.$val.'+';	
    		}
    		else
			{
	    		$string .= $key.'='.$value.'+';			
			}    			
    	}
    	$string = rtrim($string,'+');

    	redirect(site_url('results/'.$string));
    }

	public function terms_check($str)
	{
		if (isset($_POST['terms_conditon'])==FALSE)
		{
			$this->form_validation->set_message('terms_check', lang_key('must_accept_terms'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}


	public function sendemailtoagent($agent_id='0')
	{

		$this->form_validation->set_rules('sender_name', 'Name', 'required');
		$this->form_validation->set_rules('sender_email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('msg', 'Message', 'required');
		$this->form_validation->set_rules('ans', 'Code', 'required|callback_check_code');
		$this->form_validation->set_rules('terms_conditon','Terms and condition','xss_clean|callback_terms_check');

		$unique_id 	= $this->input->post('unique_id');		
		$title 		= $this->input->post('title');		

		if ($this->form_validation->run() == FALSE)
		{
			$this->load_contact_agent_view($unique_id);
		}
		else
		{
			$detail_link = $this->input->post('url');

			$data['sender_email'] = $this->input->post('sender_email');
			$data['sender_name']  = $this->input->post('sender_name');
			$data['subject']  	  = $this->input->post('subject');
			$data['msg']  		  = $this->input->post('msg');
			
			$data['msg']		 .= "<br /><br /> This email was sent from the following page:<br /><a href=\"".$detail_link."\" target=\"_blank\">".site_url('ads/'.$unique_id.'/'.$title)."</a>";
			add_user_meta($agent_id,'query_email#'.time(),json_encode($data));

			$this->load->library('email');
			$config['mailtype'] = "html";
			$config['charset'] 	= "utf-8";
			$config['newline'] = '\r\n';

			$this->email->initialize($config);
			$this->email->from($this->input->post('sender_email'),$this->input->post('sender_name'));
			$this->email->to($this->input->post('to_email'));

			$msg = $this->input->post('msg');

			if($this->input->post('phone')!='')				
			$msg .= "<br/><br/>".lang_key('senders_phone')." : ".$this->input->post('phone');
			$msg .= "<br/><br/>Email sent from : ".'<a href="'.$detail_link.'">'.$detail_link.'</a>';
			$this->email->subject($this->input->post('subject'));
			$this->email->message($msg);
			$this->email->send();

			$msg = '<div class="alert alert-success">'.lang_key('email_sent').'</div>';
			$this->load_contact_agent_view($unique_id,$msg); //updated on version 1.8
		
		}

	}

	public function load_contact_agent_view($unique_id='',$msg='')
	{
			//updated on version 1.8
			$value = array();
			$a = rand (1,10);
			$b = rand (1,10);
			$c = rand (1,10)%3;
			if($c==0)
			{
				$operator = '+';
				$ans = $a+$b;
			}
			else if($c==1)
			{
				$operator = 'X';
				$ans = $a*$b;
			}
			else if($c==2)
			{
				$operator = '-';
				$ans = $a-$b;
			}

			$this->session->set_userdata('security_ans',$ans);

			$value['question']  = $a." ".$operator." ".$b." = ?";

			$this->load->model('user/post_model');
			$post = $this->post_model->get_post_by_unique_id($unique_id);
			$value['post'] = $post->row();
			$value['msg']  = $msg;
			load_view('agent_email_view',$value);
	}

	public function categoryposts($id='')
	{
		$value = array();    	
		$value['category_id'] = $id;
		$data['content'] 	= load_view('category_posts_view',$value,TRUE);
		$data['alias']	    = 'categoryposts';
		$data['sub_title']	= lang_key('business_listed_under').' '.get_category_title_by_id($id);// added on version 1.5
		load_template($data,$this->active_theme);
	}

	
    #********* widget ajax functions ******************#
    function recentposts_ajax($limit=5,$view_type='grid')
    {
		$this->load->model('user/post_model');
		$value['posts'] = $this->post_model->get_recent_posts($limit);
    	load_view($view_type.'_view',$value);
    }

	function featuredposts_ajax($limit=5,$view_type='grid')
	{

		$this->load->model('user/post_model');
		$value['posts'] = $this->post_model->get_featured_posts($limit);
		load_view($view_type.'_view',$value);
	}

    function categoryposts_ajax($limit=5,$view_type='grid',$category_id='')
    {
		$this->load->model('user/post_model');
		$value['posts'] = $this->post_model->get_category_posts($limit,$category_id);
    	load_view($view_type.'_view',$value);
    }

    function memberposts_ajax($limit=5,$view_type='grid',$user_id='')
    {
		$this->load->model('user/post_model');
		$value['posts'] = $this->post_model->get_member_posts($limit,$user_id);
    	load_view($view_type.'_view',$value);
    }

    public function result($string='',$limit='0',$redirect=TRUE,$view_type='grid')
    {
    	$string = rawurldecode($string);
    	$data = array();
    	$values = explode("+",$string);

    	foreach ($values as $value) 
    	{
    		$get 	= explode("=",$value);
    		$s 		= (isset($get[1]))?$get[1]:'';
    		$val 	= explode(",",$s);

    		if(count($val)>1)
    		{
	    		$data[$get[0]] = $val;
    		}
    		else
	    		$data[$get[0]] = (isset($get[1]))?$get[1]:'';
    	}

    	$value 	= array();
    	$value['data'] = $data;

    	// echo "<pre>";
    	// print_r($data);
    	// die;
    	#get estates based on the advanced search criteria
    	if($limit=='all')
    	$value['query'] 		= $this->show_model->get_advanced_search_result($data,'all',$limit);
    	else
    	$value['query'] 		= $this->show_model->get_advanced_search_result($data,0,$limit);


        if($redirect==FALSE) {
        	$res = array();
        	$res['query'] = $value['query'];
        	$res['url']   = site_url('results/'.$string);
        	return $res;
        }

        $this->load->model('user/post_model');
        $value['categories'] = $this->post_model->get_all_categories();
    	$data 	= array();
    	$data['content'] 	= load_view('adsearch_view',$value,TRUE);
		$data['alias']	    = 'ad_search';
		load_template($data,$this->active_theme);
    }

    function getresult_ajax($view_type='grid',$limit='0',$return_type='view')
    {

    	/*************************************
    	If this function is modified make sure
    	to replicate the changes in function
    	getpoppedresult_ajax() located further
    	down
    	**************************************/

    	$string = '';

    	foreach ($_POST as $key => $value) 
    	{
    		if(is_array($value))
    		{
    			$val = '';
    			foreach ($value as $row) {
    				$val .= $row.',';
    			}
    			$string .= $key.'='.$val.'+';	
    		}
    		else
			{
	    		$string .= $key.'='.$value.'+';			
			}    			
    	}
    	$string = rtrim($string,'+');

		$this->load->model('user/post_model');
		$result = $this->result($string,$limit,FALSE,$view_type);

		$value = array();		
		$value['posts'] = $result['query'];

		$output = array();
		if($return_type=='view')
		{
	    	$output['content'] 	= load_view($view_type.'_view',$value,TRUE);
	    	$output['url']		= $result['url'];			
		}
		else
		{
	    	$output['data'] 	= prepare_map_json_from_query($result['query']);
	    	$output['url']		= $result['url'];
		}

    	echo json_encode($output);
    	die;
    }


    function getpoppedresult_ajax($string='',$view_type='grid')
    {

    	$this->load->model('user/post_model');
		$result = $this->result($string,0,FALSE);

		$value = array();		
		$value['posts'] = $result['query'];

		$output = array();
    	$output['content'] 	= load_view($view_type.'_view',$value,TRUE);
    	$output['url']		= $result['url'];
    	$output['pages'] 	= $result['pages'];
    

    	echo json_encode($output);
    	die;
    }


    function plain()
    {
    	$this->load->model('user/post_model');
    	$value['categories'] = $this->post_model->get_all_categories();
    	$data 	= array();
    	$data['content'] 	= load_view('adsearch_view',$value,TRUE);
		$data['alias']	    = 'map_search';
		load_template($data,$this->active_theme);
    }

    function map()
    {
    	$this->load->model('user/post_model');
    	$value['categories'] = $this->post_model->get_all_categories();
    	$data 	= array();
    	$data['content'] 	= load_view('map_search_view',$value,TRUE);
		$data['alias']	    = 'map_search';
		load_template($data,$this->active_theme);
    }

    function toggle($type='plain')
    {
    	$view_type = ($this->input->post('view_type')!='')?$this->input->post('view_type'):'grid';

    	$this->session->set_userdata('view_type',$view_type);
    	$this->session->set_userdata('search_view_type',$type);
    	redirect($this->input->post('url'));
    }


    #********** blog posts functions start**************#
	public function post($type='all',$start=0)
	{
		$this->config->load('business_directory');
		$options 				= $this->config->item('blog_post_types');
		$value['posts']			= $this->show_model->get_all_active_blog_posts_by_range($start,$this->PER_PAGE,'id','desc',$type);
		$total 					= $this->show_model->count_all_active_blog_posts($type);
		$value['pages']			= configPagination('show/post/'.$type,$total,5,$this->PER_PAGE);
		$value['page_title']	= (isset($options[$type]))?$options[$type]:$type;
		$data['sub_title']		= (isset($options[$type]))?$options[$type]:$type;
		$data['content'] 		= load_view('posts_view',$value,TRUE);
		load_template($data,$this->active_theme);

	}

	public function postdetail($id='')

	{			
		$this->load->model('admin/blog_model');
		$value['blogpost']			= $this->blog_model->get_post_by_id($id);
        $data['blog_meta']		=$value['blogpost'];
		$data['sub_title']			= get_blog_data_by_lang($value['blogpost'],'title');
		$data['content'] 		= load_view('post_detail_view',$value,TRUE);
		
		$data['alias']	    = 'detail';
		$id = 0;
		$status = 1;
		if(count($value['blogpost']))
		{			
			$row 	= $value['blogpost'];
			$status = $row->status;	
			$id = $row->id;
			$title 				= get_post_data_by_lang($row,'title');
			$description 		= get_post_data_by_lang($row,'description');
		}

		if($status!=1)
		{
			unset($data['blog_meta']);
			$data['sub_title']			= lang_key('404_not_found');
			$this->output->set_status_header('404');
			$data['content'] 	= load_view('404_view','',TRUE);
	        load_template($data,$this->active_theme,'template_view');			
		}
		else
		{

			$data['sub_title']			= $title;
			$description 	= strip_tags($description);
			$description 	= str_replace("'","",$description);
			$description 	= str_replace('"',"",$description);
			$seo['meta_description']	= $description;
			$data['seo']				= $seo;
			load_template($data,$this->active_theme);
		}

	}
    #********** blog posts functions end**************#


    #********** location functions start**************#
    public function get_locations_by_parent_ajax($parent='')
    {
    	if($parent=='')
    	{
	    	echo '<option value="">'.lang_key('select_one').'</option>';
    	}
    	else
    	{
	    	$this->load->model('admin/business_model');
	    	$query = $this->business_model->get_all_locations_by_parent($parent);
	    	echo '<option value="">'.lang_key('select_one').'</option>';
	    	foreach ($query->result() as $row) 
	    	{
	    		echo '<option data-name="'.trim($row->name).'" value="'.trim($row->id).'">'.lang_key(trim($row->name)).'</option>';
	    	}    		
    	}

    	die;
    }

    /*This function sets city name as the value also*/
    public function get_city_dropdown_by_parent_ajax($parent='')
    {
    	if($parent=='')
    	{
	    	echo '<option value="">'.lang_key('select_one').'</option>';
    	}
    	else
    	{
	    	$this->load->model('admin/business_model');
	    	$query = $this->business_model->get_all_locations_by_parent($parent);
	    	echo '<option value="">'.lang_key('select_one').'</option>';
	    	foreach ($query->result() as $row) 
	    	{
	    		echo '<option data-name="'.trim($row->name).'" city_id="'.$row->id.'" value="'.trim($row->name).'">'.lang_key(trim($row->name)).'</option>';
	    	}    		
    	}

    	die;
    }

    //added on version 1.8
    public function get_city_val_dropdown_by_parent_ajax($parent='')
    {
    	if($parent=='')
    	{
	    	echo '<option value="">'.lang_key('select_one').'</option>';
    	}
    	else
    	{
	    	$this->load->model('admin/business_model');
	    	$query = $this->business_model->get_all_locations_by_parent($parent);
	    	echo '<option value="">'.lang_key('select_one').'</option>';
	    	foreach ($query->result() as $row) 
	    	{
	    		echo '<option data-name="'.trim($row->name).'" city_id="'.$row->id.'" value="'.trim($row->id).'">'.lang_key(trim($row->name)).'</option>';
	    	}    		
    	}

    	die;
    }
    #********** location functions end**************#



    #********** Other page functions start**************#

    public function contact()
	{
		$a = rand (1,10);
		$b = rand (1,10);
		$c = rand (1,10)%3;
		if($c==0)
		{
			$operator = '+';
			$ans = $a+$b;
		}
		else if($c==1)
		{
			$operator = 'X';
			$ans = $a*$b;
		}
		else if($c==2)
		{
			$operator = '-';
			$ans = $a-$b;
		}

		$this->session->set_userdata('security_ans',$ans);
		$value['question']  = $a." ".$operator." ".$b." = ?";

		$data['content'] 	= load_view('contact_view',$value,TRUE);
		$data['alias']	    = 'contact';
		load_template($data,$this->active_theme);
	}

	public function page($alias='')
	{	
		$value['alias']  = $alias;
		$value['query']  = $this->show_model->get_page_by_alias($alias);
		$data['content'] = load_view('page_view',$value,TRUE,$this->active_theme);
		$data['alias']   = $alias;
		load_template($data,$this->active_theme);
	}

	function check_code($str)
	{
		if ($str != $this->session->userdata('security_ans'))
		{
			$this->form_validation->set_message('check_code', 'The %s is not correct');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function sendcontactemail()
	{
		$this->form_validation->set_rules('sender_name', 'Name', 'required');
		$this->form_validation->set_rules('sender_email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('msg', 'Message', 'required');
		$this->form_validation->set_rules('ans', 'Code', 'required|callback_check_code');

		if ($this->form_validation->run() == FALSE)
		{
			$this->contact();	
		}
		else
		{

			$this->load->library('email');
			$config['mailtype'] = "html";
			$config['charset'] 	= "utf-8";
			$this->email->initialize($config);
			$this->email->from($this->input->post('sender_email'),$this->input->post('sender_name'));
			$this->email->to(get_settings('webadmin_email','contact_email','support@example.com'));

			$this->email->subject(lang_key('contact_subject'));
			$this->email->message($this->input->post('msg').'<br/>'.lang_key('phone').': '.$this->input->post('phone'));
			$this->email->send();

			$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('mail_sent').'</div>');
			redirect(site_url('show/contact/'));			

		}

	}

	// updated on version 1.5
	public function rss()
	{
		$this->load->helper('xml');
		$this->load->helper('text');
		$curr_lang 	= get_current_lang();
		if($curr_lang=='')
		$curr_lang = default_lang();

		$value = array();	
		$value['curr_lang'] = $curr_lang;	
		$value['feed_name'] = translate(get_settings('site_settings','site_title','Whizbiz'));
        $value['encoding'] = 'utf-8';
        $value['feed_url'] = site_url('show/rss');
        $value['page_description'] = lang_key('your_web_description');
        $value['page_language'] = $curr_lang.'-'.$curr_lang;
        $value['creator_email'] = get_settings('webadmin_email','contact_email','');
        $value['posts']	=  $this->show_model->get_all_active_posts_by_range(0,$this->PER_PAGE,'id','desc');
        header("Content-Type: application/rss+xml");
		load_view('rss_view',$value,FALSE,$this->active_theme);
	}
	//end

    public function sitemap(){
        $this->load->helper('xml');
        $this->load->helper('file');
        $xml = read_file('./sitemap.xml');

        $value['title']='site map';

        $value['links'] = simplexml_load_string($xml);

        $data['content'] = load_view('sitemap_view',$value,TRUE,$this->active_theme);

        $data['alias']   = 'sitemap';

        load_template($data,$this->active_theme);
    }

    #********** Other page functions end**************#

    public function members($start=0)
    {
    	$value['query']			= $this->show_model->get_users_by_range($start,$this->PER_PAGE,'id');
        $total 					= $this->show_model->get_users_by_range('total');

        $value['pages']			= configPagination('show/members/',$total,4,$this->PER_PAGE);
		$data['content'] 		= load_view('members_view',$value,TRUE);
		$data['alias']	    	= 'members';

		load_template($data,$this->active_theme);	
    }

    public function memberposts($user_id='0',$start=0)
	{	

		$value['user']			= $this->show_model->get_user_by_userid($user_id);	
		$value['query']			= $this->show_model->get_all_estates_agent($user_id,$start,$this->PER_PAGE,'id');
        $total 					= $this->show_model->count_all_estates_agent($user_id);
		$value['pages']			= configPagination('profile/'.$user_id,$total,5,$this->PER_PAGE);

		$data['content'] 		= load_view('member_posts_view',$value,TRUE);
		$data['alias']	    	= 'member_posts';
		load_template($data,$this->active_theme);

	}

	// added on version 1.6
	public function featured_businesses()
	{
		$value = array();
		$data['content'] 	= load_view('featured_business_view',$value,TRUE);
		$data['alias']	    = 'featured_businesses';
		load_template($data,$this->active_theme);
	}

	public function categories()
	{
		$this->load->model('user/post_model');		
		$value = array();
		$value['categories'] = $this->post_model->get_all_parent_categories();

		$data['content'] 	= load_view('category_view',$value,TRUE);
		$data['alias']	    = 'categories';
		load_template($data,$this->active_theme);
	}

	public function pricing()
	{
		$this->load->model('admin/package_model');		
		$value = array();
		$value['post_packages'] = $this->package_model->get_all_packages_by_type('post_package');
		$value['featured_packages'] = $this->package_model->get_all_packages_by_type('featured_package');

		$data['content'] 	= load_view('pricing_view',$value,TRUE);
		$data['alias']	    = 'packages';
		load_template($data,$this->active_theme);
	}
	// end

	public function location()
	{

		$value = array();
		$value['countries'] = get_all_locations_by_type('country');

		$data['content'] 	= load_view('location_view',$value,TRUE);
		$data['alias']	    = 'location';
		load_template($data,$this->active_theme);
	}


	public function location_posts($id='', $type='country')
	{
		$value = array();
		$value['location_id'] = $id;
		$value['location_type'] = $type;
		$data['content'] 	= load_view('location_posts_view',$value,TRUE);
		$data['alias']	    = 'locationposts';
		$data['sub_title']	    = lang_key('business_listed_on').' '.get_location_name_by_id($id); // added on version 1.5
		load_template($data,$this->active_theme);
	}

	public function get_cities_ajax($term='')

	{

		if($term=='')

			$term = $this->input->post('term');


		$parent = $this->input->post('parent');

		$data = $this->show_model->get_locations_json($term,'city',$parent);

		echo json_encode($data);

	}

	function location_posts_ajax($limit=5,$view_type='grid',$location_id='', $location_type='country')
	{
		$this->load->model('user/post_model');
		$value['posts'] = $this->post_model->get_location_posts($limit,$location_id,$location_type);
		load_view($view_type.'_view',$value);
	}

	public function reviewdetail($id='')
	{
		$this->load->model('admin/business_model');
		$query = $this->business_model->get_review_by_id($id);
		if($query->num_rows()>0)
		{
			$value['review'] = $query->row();
			load_view('single_review_view',$value);
		}
	}


	public function load_claim_business_view($unique_id='',$msg='')
	{
			$value = array();
			$a = rand (1,10);
			$b = rand (1,10);
			$c = rand (1,10)%3;
			if($c==0)
			{
				$operator = '+';
				$ans = $a+$b;
			}
			else if($c==1)
			{
				$operator = 'X';
				$ans = $a*$b;
			}
			else if($c==2)
			{
				$operator = '-';
				$ans = $a-$b;
			}

			$this->session->set_userdata('security_ans',$ans);

			$value['question']  = $a." ".$operator." ".$b." = ?";

			$this->load->model('user/post_model');
			$post = $this->post_model->get_post_by_unique_id($unique_id);
			$value['post'] = $post->row();
			$value['msg'] = $msg;
			load_view('claim_business_view',$value);
	}

	public function sendclaimemail($agent_id='0')
	{

		$this->form_validation->set_rules('sender_name', 'Name', 'required');
		$this->form_validation->set_rules('sender_email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('msg', 'Message', 'required');
		$this->form_validation->set_rules('ans', 'Code', 'required|callback_check_code');
		$this->form_validation->set_rules('terms_conditon','Terms and condition','xss_clean|callback_terms_check');

		$unique_id 	= $this->input->post('unique_id');		
		$post_id 	= $this->input->post('post_id');		
		$title 		= $this->input->post('title');		

		if ($this->form_validation->run() == FALSE)
		{
			$this->load_claim_business_view($unique_id);
		}
		else
		{
			$detail_link = $this->input->post('url');

			$data['sender_email'] = $this->input->post('sender_email');
			$data['sender_name']  = $this->input->post('sender_name');
			$data['phone']  	  = $this->input->post('phone');
			$data['subject']  	  = $this->input->post('subject');
			$data['msg']  		  = $this->input->post('msg');
			
			$data['msg']		 .= "<br /><br /> This email was sent from the following page:<br /><a href=\"".$detail_link."\" target=\"_blank\">".site_url('ads/'.$unique_id.'/'.$title)."</a>";

			add_post_meta($post_id,'claim_email#'.time(),json_encode($data));

			$this->load->library('email');
			$config['mailtype'] = "html";
			$config['charset'] 	= "utf-8";
			$config['newline'] = '\r\n';

			$this->email->initialize($config);
			$this->email->from($this->input->post('sender_email'),$this->input->post('sender_name'));
			$this->email->to(get_settings('webadmin_email','contact_email','support@example.com'));

			$msg = $this->input->post('msg');
			$msg .= "<br/><br/>Email sent from : ".'<a href="'.$detail_link.'">'.$detail_link.'</a>';
			$this->email->subject($this->input->post('subject'));
			$this->email->message($msg);
			$this->email->send();

			$msg = '<div class="alert alert-success">'.lang_key('email_sent').'</div>';
			$this->load_claim_business_view($unique_id,$msg);
		
		}

	}

	//added on version 1.8
	public function getnewsletter()
	{
		$this->form_validation->set_rules('email', lang_key('your_email'), 'required|valid_email');

		if ($this->form_validation->run() == FALSE)
		{
			load_view('newsletter_form');
		}
		else
		{
			$user_email = $this->input->post('email');
			$emails = (array)get_option('email_subscribers');
			if(isset($emails['error']))
			{
				$data = array();				
				array_push($data, $user_email);
				add_option('email_subscribers',json_encode($data));
			}
			else
			{
				$data = (array)json_decode($emails['values']);
				if(!in_array($user_email, $data))
				{					
					array_push($data, $user_email);
					add_option('email_subscribers',json_encode($data));
				}
			}
			$value['msg'] = '<div class="alert alert-success">'.lang_key('email_saved').'</div>';
			load_view('newsletter_form',$value);		
		}
	}

	function unsubscribe()
	{
		$data['content'] 	= load_view('unsubscribe_view','',TRUE);
        $data['alias']	    = 'unsubscribe';
        load_template($data,$this->active_theme);
	}

	function unsubscribenewsletter()
	{
		$this->form_validation->set_rules('user_email', lang_key('user_email'), 'required|valid_email');

		if ($this->form_validation->run() == FALSE)
		{
			$this->unsubscribe();	
		}
		else
		{
			$user_email = $this->input->post('user_email');
			$option = (array)get_option('email_subscribers');

			if(!isset($option['error']))
			{
				$data = (array)json_decode($option['values']);

				if(($key = array_search($user_email, $data)) !== false) {
				    unset($data[$key]);
				}

				add_option('email_subscribers',json_encode($data));
			}
			$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('successfully_unsubscribed').'</div>');
			redirect(site_url('show/unsubscribe'));		
		}
	}
	//end

	public function register_domain($purchase_key='')
	{
		$domain = base_url();
		$item_id = '12931569';
		$data = md5(urlencode($purchase_key).'-'.urlencode($item_id).'-'.urlencode($domain));
		add_option('purchase_key',$purchase_key);
		add_option('item_id',$item_id);
	}

	// added on version 1.5
	function allsubcat($id='')
	{
		$this->load->model('user/post_model');
		$value['posts'] = $this->post_model->get_all_child_categories($id,'all');
		load_view('subcategory_view',$value,FALSE);
	}
	//end

	function test()
	{
		echo format_long_text("AryanClubloremipsumdollersitametAryanClubloremipsumdollersitamet");
	}

}





/* End of file install.php */

/* Location: ./application/modules/show/controllers/show_core.php */