<?php
/**
 *
 * wap/service controller
 *
 * @package		Waptool Creation
 * @since		September 29 2011
 * @author		Indra (LinkIT Dev Team)
 *
 */

class Service extends CI_Controller {
	public $limit = 0;

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('wap_username'))
        redirect(base_url() . 'login');
	$this->klogger->log("");
        $this->load->model(array ('navigation_model', 'service_model', 'adn_model'));

        $this->mysmarty->assign('navigation',   $this->navigation_model->getMenuHtml());
        $this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));
        $this->mysmarty->assign('base_url',     base_url());        

	$this->limit = $this->config->item('limit');
	       
    }

    function index() {
	$this->klogger->log("");
        $this->mysmarty->assign('base_url', base_url());
        $this->mysmarty->assign('wap_service', $this->getWapServiceList());
        $this->mysmarty->assign('adn',$this->getAdnList());
        $this->mysmarty->view('wap/service_view.html');
    }
    
    public function ajaxGetServiceList() {
	$this->klogger->log("");
	$search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit  = $this->limit;
        $paging = "";
        $result = "";
       
        
        $mData  = $this->service_model->getServiceList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];

        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id             = $dt['id'];
                $wap_service    = $dt['service'];
                $wap_name       = $dt['name'];
                $adn            = $dt['adn'];
                $mechanism      = $dt['mechanism'];               
                $datecreated   = $dt['datecreated'];
    
                $result .= "<tr>";
                $result .= "<td>$wap_service</td>";
                $result .= "<td>$wap_name</td>";
                $result .= "<td>$adn</td>";
                $result .= "<td>$mechanism</td>";
                $result .= "<td>$datecreated</td>";
                $result .= "<td><div class=\"menulink\"><a onclick=\"editService($id);\">Edit</a> <a onclick=\"deleteService($id);\">Delete</a></div></td>";
                $result .= "</tr>";
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "wap/service/ajaxGetServiceList/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows']  = $total;
                $pagination['per_page']    = $limit;

                $this->pagination->initialize($pagination);
                $paging = $this->pagination->create_links();
            }
            else {
                $paging = "<b>1</b>";
            }
        }
        else {
            $result .= "<tr><td colspan=\"6\">No data found</td></tr>";
        }

        $to = ($page + $limit) > $total ? $total : ($page + $limit);

        $response = array (
            'offset' => $offset,
            'query'  => $mData['query'],
            'result' => $result,
            'paging' => $paging,
            'from'   => ($page + 1),
            'to'     => $to,
            'total'  => $total
        );

        echo json_encode($response);
    }
    
    public function getWapServiceList(){
	$this->klogger->log("");
        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-wap-service\" id=\"txt-wap-service\">";
        foreach ($this->service_model->readWapService() as $_data)
        {
            $id             = $_data['id'];
            $wap_service    = $_data['name'];    
            $result .="<option value=\"$wap_service\">$wap_service</option>";
        }   
        $result .="</select>";
        $result .="</span>";
        return $result;
        
    }
    
    public function getAdnList(){
	$this->klogger->log("");
        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-adn\" id=\"txt-adn\">";
        foreach ($this->service_model->readAdn() as $__data)
        {
            $id         = $__data['id'];
            $adn        = $__data['name'];    
            $result    .="<option value=\"$adn\">$adn</option>";
        }   
        $result .="</select>";
        $result .="</span>";
        
        return $result;
    }  
    
    public function ajaxAddNewService() {
	$this->klogger->log("");
        $wap_service    = $this->input->post("txt-wap-service");
        $wap_name       = $this->input->post("txt-wap-name");
        $adn            = $this->input->post("txt-adn");
        $mechanism      = $this->input->post("txt-mechanism");

        $response = array ();
        
        //validate
        if (empty ($wap_service)) {
            $status_wap_service = FALSE;
            $msg_wap_service    = "require field";
        }
        else {
            $status_wap_service = TRUE;
            $msg_wap_service    = "";
        }
        
        if (empty ($wap_name)) {
            $status_wap_name = FALSE;
            $msg_wap_name    = "require field";
        }
        else {
            $status_wap_name = TRUE;
            $msg_wap_name    = "";
        }
        
        if (empty ($adn)) {
            $status_adn = FALSE;
            $msg_adn    = "require field";
        }
        else {
            $status_adn = TRUE;
            $msg_adn    = "";
        }
        
        if (empty ($mechanism)) {
            $status_mechanism = FALSE;
            $msg_mechanism    = "require field";
        }
        else {
            $status_mechanism = TRUE;
            $msg_mechanism    = "";
        }
        
        if (!empty($wap_service) && !empty($wap_name) && !empty($adn) && !empty($mechanism))
        {
            if ($this->service_model->check_wap_name($wap_name))
            {
                $response = array (
                        'status_wap_name' => FALSE, 
                        'msg_wap_name' => "Wap Name already exist, try another name",
                        'status' => FALSE, 
                        'message' => 'Wap Name already exist, try another name'
                         );
            }
            else
            {
                $response   = $this->service_model->addNewService($wap_service, $wap_name ,$adn, $mechanism);
            }
        }
        else
        {
            $response = array ( 'status_wap_service'    => $status_wap_service,
                                'msg_wap_service'       => $msg_wap_service,
                                'status_wap_name'       => $status_wap_name,
                                'msg_wap_name'          => $msg_wap_name,
                                'status_adn'            => $status_adn,
                                'msg_adn'               => $msg_adn,
                                'status_mechanism'      => $status_mechanism,
                                'msg_mechanism'         => $msg_mechanism,
                                'status'                => FALSE, 
                                'message'               => 'error'
                              );
        }
        
        echo json_encode($response);
        exit;
        
    }
    
    public function ajaxEditService() {
	$this->klogger->log("");
        $id     = $this->input->post("id");
        $result = $this->service_model->editService($id);

        $response = array (
            'wap_service'   => $result[0]['service'],
            'wap_name'      => $result[0]['name'],
            'adn'           => $result[0]['adn'],
            'mechanism'     => $result[0]['mechanism']
       );

        echo json_encode($response);
        exit;
    }
    
    public function ajaxUpdateService($id) {
        $this->klogger->log("");
        $wap_service        = $this->input->post("txt-wap-service");
        $wap_name           = $this->input->post("txt-wap-name");
        $adn                = $this->input->post("txt-adn");
        $mechanism          = $this->input->post("txt-mechanism");
        $wap_name_compare   = $this->input->post("wap-name-compare");
        
        $response = array();
        
        //validate
        if (empty ($wap_service)) {
            $status_wap_service = FALSE;
            $msg_wap_service    = "require field";
        }
        else {
            $status_wap_service = TRUE;
            $msg_wap_service    = "";
        }
        
        if (empty ($wap_name)) {
            $status_wap_name = FALSE;
            $msg_wap_name    = "require field";
        }
        else {
            $status_wap_name = TRUE;
            $msg_wap_name    = "";
        }
        
        if (empty ($adn)) {
            $status_adn = FALSE;
            $msg_adn    = "require field";
        }
        else {
            $status_adn = TRUE;
            $msg_adn    = "";
        }
        
        if (empty ($mechanism)) {
            $status_mechanism = FALSE;
            $msg_mechanism    = "require field";
        }
        else {
            $status_mechanism = TRUE;
            $msg_mechanism    = "";
        }
        
        if (!empty($wap_name) && !empty($mechanism))
        {
            if($wap_name==$wap_name_compare)
            {
                $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);
                $response = $this->service_model->updateService($id, $wap_service, $wap_name, $adn, $mechanism);
            }
            else {
                    if ($this->service_model->check_wap_name($wap_name))
                    {
                    $response = array (
                            'status_wap_name' => FALSE, 
                            'msg_wap_name' => "Wap Name already exist, try another name",
                            'status' => FALSE, 
                            'message' => 'Wap Name already exist, try another name'
                             );
                    }
                    else {
                    $response = $this->service_model->updateService($id, $wap_service, $wap_name, $adn, $mechanism);
                    }
            }
        }
        else {
           $response = array (  'status_wap_service'    => $status_wap_service,
                                'msg_wap_service'       => $msg_wap_service,
                                'status_wap_name'       => $status_wap_name,
                                'msg_wap_name'          => $msg_wap_name,
                                'status_adn'            => $status_adn,
                                'msg_adn'               => $msg_adn,
                                'status_mechanism'      => $status_mechanism,
                                'msg_mechanism'         => $msg_mechanism,
                                'status'                => FALSE, 
                                'message'               => 'error'
                              );
        }
        echo json_encode($response);
        exit;
        
    }
    
    public function ajaxDeleteService() {
	$this->klogger->log("");
        $id       = $this->input->post("id");
        $response = $this->service_model->deleteService($id);

        echo json_encode($response);
        exit;
    }  
}
