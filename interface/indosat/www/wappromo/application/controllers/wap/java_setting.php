<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Java_setting extends CI_Controller
{
    protected $selected;

    public function  __construct() {
        parent::__construct();
        
        if (!$this->session->userdata('wap_username'))
        redirect(base_url() . 'login');
	$this->klogger->log("");
        $this->load->model(array ('navigation_model', 'subscription_model', 'service_model', 'adn_model','ak_model'));
        $this->mysmarty->assign('navigation',   $this->navigation_model->getMenuHtml());
        $this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));
        $this->mysmarty->assign('base_url',     base_url());
        $read_config = get_config();
        $this->java_type = $read_config['dl_java_file'];
	$this->limit = $this->config->item('limit');
        //$this->load->helper(array('url','form'));
        //$this->load->library('session');

    }

    public function index(){
        //$this->mysmarty->assign('base_url',base_url());
//        $this->load->model('Java_setting_model');
//		$service1 = $this->Java_setting_model->getServiceNull("not");
//		$this->mysmarty->assign('service1',$service1);
//		$service2 = $this->Java_setting_model->getServiceNull("");
//		$this->mysmarty->assign('service2',$service2);
//		$this->mysmarty->assign('show',"");
//		$this->mysmarty->assign('showTitle',"Default");
//
//		$this->mysmarty->view('wap/java_setting.html');

                $firstShow = 1;
	        $this->load->model('Java_setting_model');
			$service1 = $this->Java_setting_model->getService($firstShow, '!=');
			$this->mysmarty->assign('service1',$service1);
			$service2 = $this->Java_setting_model->getService($firstShow);
			$this->mysmarty->assign('service2',$service2);
			$this->mysmarty->assign('show',$firstShow);
			$this->mysmarty->assign('showTitle',$firstShow);
			
                        $this->mysmarty->assign('titlever', $this->java_type[$firstShow]);
            $this->mysmarty->assign('type_array', $this->java_type);
            $this->mysmarty->view('wap/java_setting.html');
    }

    public function select()
    {
        $this->load->model('Java_setting_model');
        $query=$this->Java_setting_model->getSelect($this->input->post('versi'));
        redirect('bookmark');
    }

    private function _version()
    {
        $this->load->model('Java_setting_model');
        $version=$this->Java_setting_model->getVersion("-");
        $selected_id=$this->Java_setting_model->getActive();
        $this->mysmarty->assign('selected',$selected_id);
        $this->mysmarty->assign('version',$version);

    }

	public function submit_ver(){
		$this->load->model('Java_setting_model');
		$version=$_POST['set'];
		if(isset($_POST['box2'])){
			$box2=$_POST['box2'];
                        $box1=$_POST['box1'];
			foreach($box2 as $val){
				$this->Java_setting_model->changeVersion($val,$version);
			}

                        
			?><script language="javascript">alert("Data has been changed to version <?php echo $version; ?>");</script><?php
			redirect(base_url()."wap/java_setting/index", 'refresh');
		}else{
			?><script language="javascript">alert("Select at least one service");</script><?php
			redirect(base_url()."wap/java_setting/index", 'refresh');
		}
	}

	public function show_ver(){
		if($_POST['show']==''){
			redirect(base_url()."java_setting/index", 'refresh');
		}else{
			$this->mysmarty->assign('base_url',base_url());
	        $this->load->model('Java_setting_model');
			$service1 = $this->Java_setting_model->getService($_POST['show'], '!=');
			$this->mysmarty->assign('service1',$service1);
			$service2 = $this->Java_setting_model->getService($_POST['show']);
			$this->mysmarty->assign('service2',$service2);
			$this->mysmarty->assign('show',$_POST['show']);
			$this->mysmarty->assign('showTitle',$_POST['show']);
                         $this->mysmarty->assign('titlever', $this->java_type[$_POST['show']]);
            $this->mysmarty->assign('type_array', $this->java_type);
			$this->mysmarty->view('wap/java_setting.html');

		}
	}
}
?>
