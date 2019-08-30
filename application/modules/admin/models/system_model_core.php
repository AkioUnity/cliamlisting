<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BusinessDirectory System_model_core model
 *
 * This class handles System_model_core management related functionality
 *
 * @package		Admin
 * @subpackage	System_model_core
 * @author		webhelios
 * @link		http://webhelios.com
 */

class System_model_core extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
    function create_db_backup()
    {
        # Load the DB utility class
        $this->load->dbutil();

        $prefs = array(
                'tables'      => array(),  			// Array of tables to backup.
                'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                'filename'    => '',    			// File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );

        # Backup your entire database and assign it to a variable
        $backup =& $this->dbutil->backup($prefs);
		
        $value = getdate();
        $today = $value['year']."-".$value['mon']."-".$value['mday'];
        # Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file('assets/backups/mybackup('.$today.').sql', $backup);
    }

    function restore_db_backup($file)
    {
    	$this->load->helper('file');
        $schema = read_file('./assets/backups/'.$file);

        $query = rtrim(trim($schema), "\n;");
        $query_list = explode(";", $query);

        foreach($query_list as $query)
            $this->db->query($query);
    }
    
    #************* lang editor file ******************#
	function get_all_langs()
	{
		$this->load->config('business_directory');
		return $this->config->item('active_languages');	
	}

	function get_language_data($file_name)
	{
		$this->load->library('yaml');
		$lang =  $this->yaml->parse_file('./dbc_config/locals/'.$file_name);
		return $lang;
	}
	
	#************ email functions *************#
	function get_all_emails()
	{
		$query = $this->db->get_where('emailtmpl',array('status'=>1));
		return $query;
	}
	
	function get_email_by_id($id)
	{
		$query = $this->db->get_where('emailtmpl',array('id'=>$id));
		return $query;
	}
	
	#updated on version 1.8
	function get_email_tmpl_by_email_name($name)
	{
		$lang = get_current_lang();
		
		$file 		= './dbc_config/locals-email/'.$lang.'_'.$name.'.xml';
	   	$xmlstr 	= file_get_contents($file);
	   	if($xmlstr!='')
	   	{
			$xml 		= simplexml_load_string($xmlstr);
			$content	= $xml->xpath('//content');	   		
	   	}
	   	
		if(isset($content[0]))
		{
			$values = (array)$content[0];
			$values['body'] = nl2br($values['body']);
			return $values;
		}
		else
		{
			$query = $this->db->get_where('emailtmpl',array('email_name'=>$name));
			if($query->num_rows()>0)
			{
				$row = $query->row();
				$values = json_decode($row->values);
				return $values;
			}
			else
			{
				$values = array('subject'=>'Subject Not found','body'=>'body not found');
			}
			return $values;			
		}
	}
	#end
	
	function update_email_tmpl($data,$id)
	{
		$this->db->update('emailtmpl',$data,array('id'=>$id));
	}

	// added on version 1.5
	function get_unused_images($action='get')
	{
		$this->load->helper('directory');
		$images = directory_map('./uploads/gallery', 1);
		$unused_gallery = array();
		foreach ($images as $img) {
			if($img!='index.html')
			{
				$this->db->like('gallery',$img);
				$query = $this->db->get_where('posts');
				if($query->num_rows()<=0)
					array_push($unused_gallery, array('name'=>$img,'path'=>'./uploads/gallery'));				
			}
		}
	

		$images = directory_map('./uploads/profile_photos', 1);
		
		$filter = array('index.html','thumb','nophoto-.jpg','nophoto-male.jpg','nophoto-female.jpg','');
		foreach ($images as $img) {
			if(!in_array($img,$filter))
			{
				$this->db->like('profile_photo',$img);
				$query = $this->db->get_where('users');
				if($query->num_rows()<=0)
					array_push($unused_gallery, array('name'=>$img,'path'=>'./uploads/profile_photos'));				
			}
		}
	


		$images = directory_map('./uploads/images', 1);
		
		foreach ($images as $img) {
			if($img!='index.html' && $img!='no-image.png' && $img!='preview.jpg')
			{
				$this->db->like('featured_img',$img);
				$this->db->or_like('description',$img);
				$query1 = $this->db->get_where('posts');

				$this->db->like('featured_img',$img);
				$query2 = $this->db->get_where('categories');

				$this->db->like('content',$img);
				$this->db->or_like('sidebar',$img);
				$query3= $this->db->get_where('pages');

				$this->db->like('featured_img',$img);
				$this->db->or_like('description',$img);
				$query4= $this->db->get_where('blog');

				if($query1->num_rows()<=0 && $query2->num_rows()<=0 && $query3->num_rows()<=0 && $query4->num_rows()<=0)
					array_push($unused_gallery, array('name'=>$img,'path'=>'./uploads/images'));				
			}
		}
	

		$images = directory_map('./uploads/thumbs', 1);
	
		foreach ($images as $img) {
			if($img!='index.html' && $img!='no-image.png' && $img!='preview.jpg')
			{
				$this->db->like('featured_img',$img);
				$this->db->or_like('description',$img);
				$query1 = $this->db->get_where('posts');

				$this->db->like('featured_img',$img);
				$query2 = $this->db->get_where('categories');

				$this->db->like('content',$img);
				$this->db->or_like('sidebar',$img);
				$query3= $this->db->get_where('pages');

				$this->db->like('featured_img',$img);
				$this->db->or_like('description',$img);
				$query4= $this->db->get_where('blog');

				if($query1->num_rows()<=0 && $query2->num_rows()<=0 && $query3->num_rows()<=0 && $query4->num_rows()<=0)
					array_push($unused_gallery, array('name'=>$img,'path'=>'./uploads/thumbs'));				
			}
		}
		// echo '<pre>';
		// print_r($unused_gallery);


		// die;
		if($action=='get')
		return $unused_gallery;
		elseif($action=='clear')
		{
			foreach ($unused_gallery as $file) {
				unlink($file['path'].'/'.$file['name']);
			}
		}
	}
	//end

}

/* End of file system_model_core.php */
/* Location: ./system/application/models/system_model_core.php */