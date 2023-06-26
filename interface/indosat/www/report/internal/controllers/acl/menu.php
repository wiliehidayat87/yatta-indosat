<?php

class Menu extends CI_Controller {
    public $limit = 0;

	public function __construct() {
		parent::__construct();

        #user not login
       if (!$this->session->userdata('username'))
            redirect(base_url() . 'login');

		$this->load->model(array('navigation_model','menu_model'));
        $this->ci_smarty->assign('base_url', base_url());
        $this->ci_smarty->assign('navigation',   $this->navigation_model->getMenuHtml());
    
        $this->limit = $this->config->item('limit');
	}

    function index() {
        $jsFile = array('feature.js','acl/menu.js','json2.js', 'chart.js', 'swfobject.js', 'internal_account.js', 'internal_account_dashboard.js');
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('parent_list', $this->getParentList());
		$this->ci_smarty->assign('status', $this->getStatus());
        $this->ci_smarty->assign('title', 'XMP Internal :: Menu');
        $this->ci_smarty->assign('template', 'tpl_menu_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function ajaxGetMenuList() {
        $search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit  = $this->limit;
        $paging = "";
        $result = "";

        $mData  = $this->menu_model->getMenuList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];

        if ($total > 0) {
            foreach ($data as $key => $dt) {
				$id		= $dt['id'];
                $menu  	= $dt['menu'];
                $parent = (empty($dt['parent']))?'-':$dt['parent'];
                $link  	= (empty($dt['link']))?'-':$dt['link'];
                $sort  	= $dt['sort'];
                $status   = ($dt['status']==1)?"Active":"Inactive";

                $result .= "<tr>";
                $result .= "<td>$menu</td>";
                $result .= "<td>$parent</td>";
                $result .= "<td>$link</td>";
                $result .= "<td>$sort</td>";
                $result .= "<td>$status</td>";
                $result .= "<td><div class=\"menulink\"><a onclick=\"editMenu($id);\">Edit</a> <a onclick=\"deleteMenu($id);\">Delete</a></div></td>";
                $result .= "</tr>";
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "acl/menu/ajaxGetMenuList/";
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
            $result .= "<tr><td colspan=\"5\">No data found</td></tr>";
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
    
    public function getParentList(){
        
        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-parent\" id=\"txt-parent\">";
        $result .="<option value=\"0\">-</option>";
        foreach ($this->menu_model->readParentList() as $_data)
        {
            $id             = $_data['id'];
            $parent         = $_data['parent'];
            $parent_name    = $_data['menu'];            
            $result .="<option value=\"$id\">$parent_name</option>";
        }   
        $result .="</select>";
        $result .="</span>";
        return $result;
   }
   
       public function getStatus(){
        return array('0'=>'Inactive','1'=>'Active');
    }
    
    public function ajaxSaveMenu() {
        $menu_name          = ucwords($this->input->post("txt-menu-name"));
        $parent             = $this->input->post("txt-parent");
        $link    			= strtolower($this->input->post("txt-link"));
        $status             = $this->input->post("txt-status");
                
        $response = array ();
        
        //validate    
        if (empty ($menu_name)) {
            $status_menu_name   = FALSE;
            $msg_menu_name      = "require field";
        }
        else {
            $status_menu_name = TRUE;
            $msg_menu_name    = "";
        }
        
        if (empty ($link)) {
            $status_link   = FALSE;
            $msg_link      = "require field";
        }
        else {
            $status_link = TRUE;
            $msg_link    = "";
        }
        
        if (!empty($menu_name) && !empty($link))
        {
            if ($this->menu_model->check_menu_name($menu_name))
            {
                $response = array (
                        'status_menu_name'  => FALSE, 
                        'msg_menu_name'     => "Menu Name already exist, try another name",
                        'status'            => FALSE, 
                        'message'           => 'Menu Name already exist, try another name'
                         );
            }
            else
            {
                $response   = $this->menu_model->addMenu($menu_name, $parent ,$link, $status);
            }            
        }
        else
        {
            $response = array ( 'status_menu_name'          => $status_menu_name,
                                'msg_menu_name'             => $msg_menu_name,
                                'status_link'    			=> $status_link,
                                'msg_link'       			=> $msg_link,
                                'status'                    => FALSE, 
                                'message'                   => 'error'
                              );
        }
        
        echo json_encode($response);
        exit;
        
    }
    
    public function ajaxEditMenu() {
        $id     = $this->input->post("id");
        $result = $this->menu_model->editMenu($id);

        $response = array (
            'menu_name'         => $result[0]['menu'],
            'parent'            => $result[0]['parent'],
            'link'   			=> $result[0]['link'],
            'sort'              => $result[0]['sort'],
            'status'            => $result[0]['status'],
       );

        echo json_encode($response);
        exit;
    }
    
    public function ajaxUpdateMenu($id) {
        $menu_name          = ucwords($this->input->post("txt-menu-name"));
        $parent             = $this->input->post("txt-parent");
        $link    			= strtolower($this->input->post("txt-link"));
        $sort               = $this->input->post("txt-sort");
        $status             = $this->input->post("txt-status");
        $menu_name_compare  = ucwords($this->input->post("txt-menu-name-compare"));
        $sort_old           = $this->input->post("txt-sort-old");
                
        $response = array ();
        
        //validate    
        if (empty ($menu_name)) {
            $status_menu_name   = FALSE;
            $msg_menu_name      = "require field";
        }
        else {
            $status_menu_name = TRUE;
            $msg_menu_name    = "";
        }
        
        if (empty ($link)) {
            $status_controlller_link   = FALSE;
            $msg_controlller_link      = "require field";
        }
        else {
            $status_controlller_link = TRUE;
            $msg_controlller_link    = "";
        }
        
        if (!empty($menu_name) && !empty($link))
        {
            if($menu_name==$menu_name_compare)
            {
                $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);
                if (is_numeric($sort)) {
                    $response   = $this->menu_model->updateMenu($id, $menu_name, $parent, $link, $sort, $sort_old, $status);
                }
                else{
                    $response = array ('status_sort' => FALSE, 'msg_sort' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                }
            }
            else{
                if ($this->menu_model->check_menu_name($menu_name))
                {
                    $response = array (
                            'status_menu_name'  => FALSE, 
                            'msg_menu_name'     => "Menu Name already exist, try another name",
                            'status'            => FALSE, 
                            'message'           => 'Menu Name already exist, try another name'
                            );
                }
                else{
                    if (is_numeric($sort)) {
                        $response   = $this->menu_model->updateMenu($id, $menu_name, $parent, $link, $sort, $sort_old, $status);
                    }
                    else {
                        $response = array ('status_sort' => FALSE, 'msg_sort' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                    }
                }            
            }
        }
        else
        {
            $response = array ( 'status_menu_name'          => $status_menu_name,
                                'msg_menu_name'             => $msg_menu_name,
                                'status_link'    			=> $status_link,
                                'msg_link'       			=> $msg_link,
                                'status'                    => FALSE, 
                                'message'                   => 'error'
                              );
        }
        
        echo json_encode($response);
        exit;
        
    }
    
    public function ajaxDeleteMenu() {
        $id       = $this->input->post("id");
        $response = $this->menu_model->deleteMenu($id);

        echo json_encode($response);
        exit;
    }
}
?>
