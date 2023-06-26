<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

/**
 * Get Menu Data
 *
 * Maps to the following URL
 * 		http://example.com/index.php/welcome
 *	- or -
 * 		http://example.com/index.php/welcome/index
 *	- or -
 * Since this controller is set as the default controller in
 * config/routes.php, it's displayed at http://example.com/
 *
 * So any other public methods not prefixed with an underscore will
 * map to /index.php/welcome/<method_name>
 * @see http://codeigniter.com/user_guide/general/urls.html
 */
    public function index() {
        $this->load->model('acl/navigation_model');
        $group_id = $this->session->userdata('groupMenu');
        $nav = $this->navigation_model->getMenuList('0', $group_id);
        $skipthis = array('','#','logout');
        if (!$nav[0][link] OR in_array($nav[0][link],$skipthis)) $nav = $this->navigation_model->getMenu($group_id);
        //var_dump($nav);
        $this->load->helper('url');
        redirect($nav[0][link]);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
