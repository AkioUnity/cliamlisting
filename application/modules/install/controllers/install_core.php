<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Justmy admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		Install
 * @subpackage	Install
 * @author		Cai Xian
 * @link		http://justmy.com
 */

class Install_core extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->is_installed();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error input-xxlarge">', '</div>');
	}
	
	/* 
	 * Read config.xml file to see 
	 * if bookit is already installed
	 * 
	 */
	function is_installed()
	{
		$file 	= './dbc_config/config.xml';
	   	$xmlstr = file_get_contents($file);
		$xml 	= simplexml_load_string($xmlstr);
		$config	= $xml->xpath('//config');	
		if($config[0]->is_installed=='yes' && $this->uri->segment(2)!='complete')
			redirect(site_url('admin/auth'));
	}
	
	public function index()
	{
		$data['content'] = $this->load->view('check_config_view','',TRUE);	
		$this->load->view('template_view',$data);
	}
	
	public function dbsetup()
	{
		$data['content'] = $this->load->view('dbsetup_view','',TRUE);	
		$this->load->view('template_view',$data);
	}
	

	/*
	 * function for checking if provided db
	 * settings are ok or not
	 * updated on version 1.7
	 */
	public function check_db_connection()
	{
		$link_type = $this->get_link_type();
		if($link_type=='')
		{
			return 'EXTFAILED';
		}
		else if($link_type=='mysqli')
		{
			$link = @mysqli_connect($this->input->post('db_host'),$this->input->post('db_user'),$this->input->post('db_pass'),$this->input->post('db_name'));
			if (!$link) {
			  @mysqli_close($link);
			  return "DBCONNFAIL";
			}
			$db_selected = mysqli_select_db($link,$this->input->post('db_name'));
			if (!$db_selected) {
			  @mysqli_close($link);
			  return "DBNOTEXIST";
			}
			
			@mysqli_close($link);
			return "SUCCESS";			
		}
		else
		{
			$link = @mysql_connect($this->input->post('db_host'),$this->input->post('db_user'),$this->input->post('db_pass'),$this->input->post('db_name'));
			if (!$link) {
			  @mysql_close($link);
			  return "DBCONNFAIL";
			}
			$db_selected = mysql_select_db($this->input->post('db_name'),$link);
			if (!$db_selected) {
			  @mysql_close($link);
			  return "DBNOTEXIST";
			}
			
			@mysql_close($link);
			return "SUCCESS";			

		}
	}

	public function get_link_type()
	{
		$link_type = '';

		if (function_exists('mysql_connect')) {
		  $link_type = 'mysql';
		}

		if (function_exists('mysqli_connect')) {
		  $link_type = 'mysqli';
		}

		return $link_type;
	}
	
	public function accountsetup()
	{
		$data['content'] = $this->load->view('accountsetup_view','',TRUE);	
		$this->load->view('template_view',$data);
	}

	public function saveaccountsettings()
	{
		$this->form_validation->set_rules('user_name', 	'Username', 		'required|min_length[5]|xss_clean');
		$this->form_validation->set_rules('user_email',	'User email', 		'required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 	'Password', 		'required|matches[re_password]|min_length[5]|xss_clean');
		$this->form_validation->set_rules('re_password','Retype Password', 	'required|xss_clean');
		$this->form_validation->set_rules('enc_key','Encription Key', 		'required|min_length[3]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->accountsetup();	
		}
		else
		{
			$this->load->helper('file');
			$data = read_file('./application/config/config-setup.php');
			$data = str_replace('enc_key',	$this->input->post('enc_key'),	$data);

			if ( ! write_file('./application/config/config.php', $data))
			{
					$this->session->set_flashdata('msg', '<div class="alert alert-error">Unable to write the file.Please check your directory permission</div>');
					redirect(site_url('install/saveaccountsettings'));
			}
			

			$this->load->database();
			$this->load->library('encrypt');
			
			$userdata['user_name'] 	= $this->input->post('user_name');
			$userdata['first_name'] 	= $this->input->post('first_name');
			$userdata['last_name'] 	= $this->input->post('last_name');
			$userdata['gender'] 	= $this->input->post('gender');
			$userdata['user_email'] = $this->input->post('user_email');
			$userdata['confirmed']  = 1;
			$userdata['user_type']  = 1;
			$userdata['status']  	= 1;
			$userdata['password'] 	= $this->encrypt->sha1($this->input->post('password'));
			$this->db->insert('users',$userdata);

			$file = './dbc_config/config.xml';
    	
    		$xmlstr = file_get_contents($file);
			$xml = simplexml_load_string($xmlstr);
			$xml->is_installed = 'yes';
			file_put_contents($file, $xml->asXML());
			
			redirect(site_url('install/complete'));
		}
	}
	
	public function complete()
	{
		$data['content'] = $this->load->view('complete_view','',TRUE);	
		$this->load->view('template_view',$data);	
	}
}

/* End of file install.php */
/* Location: ./application/modules/install/controllers/install_core.php */