<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BusinessDirectory Purchase Controller
 *
 * This class handles Purchase management related functionality
 *
 * @package        Admin
 * @subpackage    Purchase
 * @author        webhelios
 * @link        http://webhelios.com
 */
class Purchase_core extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->regdomain();
    }

    function regdomain()
    {
        $this->session->set_userdata('form_key', rand(1, 500));
        $data = array('error' => '<div class="alert alert-danger" style="margin-top:10px;">' . lang_key('login_failed') . '</div>');
        load_admin_view('regdomain_view', $data);
    }

    public function addkey()
    {
        if ($this->input->post('form_key') == $this->session->userdata('form_key')) {
            //set POST variables

            $fields = array(
                'purchase_key' => urlencode($this->input->post('purchase_key')),
                'item_id' => urlencode($this->input->post('item_id')),
                'domain' => urlencode($this->input->post('domain')),
                'item' => 'whizbiz'
            );

            $fields_string = '';
            //url-ify the data for the POST
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

            $this->load->helper('file');
            add_option('purchase_key', $this->input->post('purchase_key'));
            add_option('item_id', $this->input->post('item_id'));
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Purchase code verified. Please login now</div>');
            redirect(site_url('admin/auth/'));

        }
    }
}

/* End of file purchase.php */
/* Location: ./application/modules/admin/controllers/purchase.php */