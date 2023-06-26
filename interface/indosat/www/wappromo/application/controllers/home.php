<?php
/**
 *
 * Service controller
 *
 * @package		Waptool Creation
 * @since		September 29 2011
 * @author		Indra (LinkIT Dev Team)
 *
 */

class Home extends CI_Controller {
	public $limit = 0;

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('wap_username'))
        redirect(base_url() . 'login');
        $this->klogger->log("");
        $this->load->model(array ('navigation_model', 'service_model'));

        $this->mysmarty->assign('navigation',   $this->navigation_model->getMenuHtml());
        $this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));
        $this->mysmarty->assign('base_url',     base_url());        

	$this->limit = $this->config->item('limit');
	       
    }

    function index() {
        $this->klogger->log("");
        $welcometext = "Welcome in Waptool Creation"; //Create dynamic Text in here
        $this->mysmarty->assign('text', $welcometext);
        $this->mysmarty->assign('base_url', base_url());
//        $this->mysmarty->assign('adn',$this->getAdn());
        $this->mysmarty->view('home_view.html');
    }
}
