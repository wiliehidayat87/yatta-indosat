<?php
/**
 * acl/group controller
 *
 * @package		Waptool Creation
 * @since		September 29 2011
 * @author		Indra (LinkIT Dev Team)
 */

class Group extends CI_Controller {
    public $limit = 0;

	public function __construct() {
		parent::__construct();

        #user not login
        if (!$this->session->userdata('wap_username'))
            redirect(base_url() . 'login');
        $this->klogger->log("");
        $this->load->model(array ('group_model', 'navigation_model'));

        $this->mysmarty->assign('navigation',   $this->navigation_model->getMenuHtml());
        $this->mysmarty->assign('base_url',     base_url());
        //$this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));

        $this->limit = $this->config->item('limit');
	}

    public function index() {
        $this->klogger->log("");
        $this->mysmarty->assign('base_url', base_url());
        $this->mysmarty->assign('check_menu', $this->getCheckMenu());

        $this->mysmarty->view('acl/group_view.html');
    }

    public function ajaxGetGroupList() {
        $this->klogger->log("");
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
        $this->klogger->log("");
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

    public function ajaxSaveGroup() {
        $this->klogger->log("");
        $group_name = $this->input->post("group-name");
        $group_desc = $this->input->post("group-desc");
        $group_menu = $this->input->post("group-menu");

        $response = $this->group_model->saveGroup($group_name, $group_desc, $group_menu);

        echo json_encode($response);
        exit;
    }

    public function ajaxEditGroup() {
        $this->klogger->log("");
        $id     = $this->input->post("id");
        $result = $this->group_model->editGroup($id);

        $response = array (
            'group_name' => $result[0]['group_name'],
            'group_desc' => $result[0]['group_desc'],
            'group_menu' => explode(",", $result[0]['group_menu'])
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateGroup($id) {
        $this->klogger->log("");
        $group_name = $this->input->post("group-name");
        $group_desc = $this->input->post("group-desc");
        $group_menu = $this->input->post("group-menu");

        $response = $this->group_model->updateGroup($id, $group_name, $group_desc, $group_menu);

        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteGroup() {
        $this->klogger->log("");
        $id       = $this->input->post("id");
        $response = $this->group_model->deleteGroup($id);

        echo json_encode($response);
        exit;
    }
}
