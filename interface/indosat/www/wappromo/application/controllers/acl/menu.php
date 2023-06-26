<?php
/**
 * acl/group controller
 *
 * @package		Waptool Creation
 * @since		September 29 2011
 * @author		LinkIT Dev Team
 */

class Menu extends CI_Controller {
    public $limit = 0;

	public function __construct() {
		parent::__construct();

        #user not login
        if (!$this->session->userdata('wap_username'))
            redirect(base_url() . 'login');
        $this->klogger->log("");
        $this->load->model(array ('menu_model', 'navigation_model'));
       
		$this->mysmarty->assign('navigation', $this->navigation_model->getMenuHtml());
        $this->mysmarty->assign('base_url',   base_url());
		$this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));

        $this->limit = $this->config->item('limit');
	}

    function index() {
        $this->klogger->log("");
        //$pagination['base_url'] = base_url(). 'service/index/page/';
        $this->mysmarty->assign('base_url', base_url());

        $this->mysmarty->view('acl/menu_view.html');
    }

    public function ajaxGetMenuList() {
        $this->klogger->log("");
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
                $menu  = $dt['menu'];
                $parent = (empty($dt['parent']))?'-':$dt['parent'];
                $link  = (empty($dt['link']))?'-':$dt['link'];
                $sort  = $dt['sort'];
                $status   = ($dt['status']==1)?"Aktif":"Tidak Aktif";

                $result .= "<tr>";
                $result .= "<td>$menu</td>";
                $result .= "<td>$parent</td>";
                $result .= "<td>$link</td>";
                $result .= "<td>$sort</td>";
                $result .= "<td>$status</td>";
                $result .= "<td><div class=\"menulink\"><a href=\"\">Edit</a> <a href=\"\">Delete</a></div></td>";
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
}
?>
