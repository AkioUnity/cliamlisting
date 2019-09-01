<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BusinessDirectory admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		Show
 * @subpackage	ReviewModelCore
 * @author		Cai Xian
 * @link		http://justmy.com
 */



class Review_model_core extends CI_Model

{



    function __construct()

    {

        parent::__construct();

        $this->load->database();

    }

    function already_given_review($data)
    {
        if($data['created_by'])
        {
            $query = $this->db->get_where('review', array('created_by' => $data['created_by'],'post_id'=>$data['post_id']));

            if($query->num_rows() > 0)
            {
                return 'FALSE';
            }
            else{
                return 'TRUE';
            }

            //echo $this->db->last_query();die;


        }


    }


    function insert_review($data)
    {
        $this->db->insert('review',$data);
        return $this->db->insert_id();
    }


    function update_post_average_rating($post_id, $average_rating)
    {
        $this->db->update('posts',array('rating'=>$average_rating), array('id'=>$post_id));
    }

    function get_review_by_id($id)
    {
        $query = $this->db->get_where('review',array('id'=>$id));
        if($query->num_rows()<=0)
        {
            echo 'Invalid review id';
            die;
        }
        else
        {
            return $query;
        }
    }

}



/* End of file review_model_core.php.php */

/* Location: ./application/modules/show/models/review_model_core.php */