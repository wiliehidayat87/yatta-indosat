<?php

class Group extends CI_Controller {
    public $limit = 0;

	public function __construct() {
		parent::__construct();

        #user not login
       if (!$this->session->userdata('username'))
            redirect(base_url() . 'login');
                         
        $this->load->model(array('navigation_model','group_model'));
        $this->ci_smarty->assign('base_url', base_url());
        $this->ci_smarty->assign('navigation',   $this->navigation_model->getMenuHtml());

        $this->limit = $this->config->item('limit');
	}

    public function index() {
        $jsFile = array('feature.js','acl/group.js','json2.js','swfobject.js', 'internal_account.js', 'internal_account_dashboard.js');
        
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('check_menu', $this->getCheckMenu());
        $this->ci_smarty->assign('title', 'XMP Internal :: Group');
        $this->ci_smarty->assign('template', 'tpl_group_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function ajaxGetGroupList() {
        $search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit  = $this->limit;
        $paging = "";
        $result = "";

        $mData  = $this->group_model->getGroupList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];

        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id         = $dt['id'];
                $group_name = $dt['group_name'];
                $group_desc = $dt['group_desc'];
                $group_menu = $dt['group_menu'];
                $status     = ($dt['status'] == '1') ? "Active" : "Inactive";

                $result .= "<tr>";
                $result .= "<td>$group_name</td>";
                $result .= "<td>$group_desc</td>";
                $result .= "<td>$status</td>";
                $result .= "<td><div class=\"menulink\"><a onclick=\"editGroup($id);\">Edit</a> <a onclick=\"deleteGroup($id);\">Delete</a></div></td>";
                $result .= "</tr>";
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "acl/group/ajaxGetGroupList/";
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
            $result .= "<tr><td colspan=\"4\">No data found</td></tr>";
            $paging  = "<b>0</b>";
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
        exit;
    }

    public function getCheckMenu() {
        $i      = 1;
        $result = "";
        
        $data=$this->navigation_model->getCheckBoxMenuList();
        $result .="<ul id=\"group-list\">";
        foreach ($data as $dt) {
            $id   = $dt['id'];
            $menu = $dt['menu'];
            $parent=$dt['parent'];
            
            if($parent==="0")
            {
                $result .= "<li><input type=\"checkbox\" name=\"parent$id\" id=\"menu-$i\" class=\"check-menu\" value=\"$id\" /> $menu";
                $count=false;        
                foreach ($data as $dt2) 
                {
                     $id2   = $dt2['id'];
                     $menu2 = $dt2['menu'];
                     $parent2=$dt2['parent'];
                     
                     if($parent2==$id)
                     {
                         if($count==false)
                         {
                            $result .="<ul>";
                            $count=true;
                         }
                         $i++;
                         $result .= "<li><input type=\"checkbox\" name=\"child$id\" id=\"menu-$i\" class=\"check-menu\" value=\"$id2\" /> $menu2</li>";
                                                 
                     }    
               }
              if($count==true)
                $result .="</ul></li>";
              else
                $result .="</li>";
                
            }    
            $i++;
        }
        $result .="</ul>";
        return $result;
    }

    public function ajaxAddNewGroup() {
		$group_name = $this->input->post("txt-name");
        $group_desc = $this->input->post("txt-desc");
        $group_menu = $this->input->post("txt-menu");
               
        $response = array ();
        
        #validate
        if (empty ($group_name)) {
            $status_group_name 	= FALSE;
            $msg_group_name    	= "required field";
        }
        else {
            $status_group_name 	= TRUE;
            $msg_group_name    	= "";
        }
    
    	if(!empty ($group_name)){
			if ($this->group_model->checkGroupList($group_name,'')){
				$response = array (
								'status_group_name' 	=> FALSE, 
								'msg_group_name' 		=> "Group already exist", 
								'status' 				=> FALSE, 
								'message' 				=> 'group already exist'
							);
			}
            else{
				$response = $this->group_model->addNewGroup($group_name, $group_desc, $group_menu);
			}    
                
		}
        else{    
                $response = array ( 
								'status_group_name'	=> $status_group_name,
								'msg_group_name'    => $msg_group_name,
								'status'            => FALSE, 
								'message'           => 'error'
                                  );
                            
		}
            echo json_encode($response);
            exit;
	}

    public function ajaxEditGroup() {
        $id     = $this->input->post("id");
        $result = $this->group_model->editGroup($id);

        $response = array (
            'name' => $result[0]['group_name'],
            'desc' => $result[0]['group_desc'],
            'menu' => explode(",", $result[0]['group_menu'])
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateGroup($id) {
		$group_name 		= $this->input->post("txt-name");
        $group_desc 		= $this->input->post("txt-desc");
        $group_menu 		= $this->input->post("txt-menu");
        $group_name_compare = $this->input->post("txt-name_compare");
        $group_desc_compare = $this->input->post("txt-desc_compare");
        $group_menu_compare = $this->input->post("txt-menu_compare");
		
		if(	$group_name 	== $group_name_compare && 
			$group_desc 	== $group_desc_compare && 
			$group_menu 	== $group_menu_compare )
		{
            $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);
            echo json_encode($response);
            exit;
        }
		
		
        $response = array ();
        
        #validate
        if (empty ($group_name)) {
            $status_group_name 	= FALSE;
            $msg_group_name    	= "required field";
        }
        else {
            $status_group_name 	= TRUE;
            $msg_group_name    	= "";
        }
        
		if(!empty ($group_name)){
			if ($this->group_model->checkGroupList($group_name,$id)){
				$response = array (
								'status_group_name' 	=> FALSE, 
								'msg_group_name' 		=> "Group already exist", 
								'status' 				=> FALSE, 
								'message' 				=> 'group already exist'
							);
			}
            else{
				$response = $this->group_model->updateGroup($id, $group_name, $group_desc, $group_menu);
			}    
                
		}
        else{    
                $response = array ( 
								'status_group_name'	=> $status_group_name,
								'msg_group_name'    => $msg_group_name,
								'status'            => FALSE, 
								'message'           => 'error'
							 );
                            
		}
            echo json_encode($response);
            exit;
        
    }

    public function ajaxDeleteGroup() {
        $id       = $this->input->post("id");
        $response = $this->group_model->deleteGroup($id);

        echo json_encode($response);
        exit;
    }
}
?>
