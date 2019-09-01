<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BusinessDirectory Purchase Controller
 *
 * This class handles Purchase management related functionality
 *
 * @package        Admin
 * @subpackage    Purchase *
 * @link        http://justmy.com
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
            redirect(site_url('admin/auth/'));
    }
}

/* End of file purchase.php */
/* Location: ./application/modules/admin/controllers/purchase.php */