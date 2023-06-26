<?php

class Download_content extends MY_Controller{
    public $limit = 0;
    
    public function __construct() {
        parent::__construct();

        $this->load->model('service/download_content_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->limit = $this->config->item('limit');
    }
    
    public function index($id = NULL) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
        $jsFile         = 'service/download_content.js';
        
        if(empty($id)){
            $idDownload     = $this->input->post('id');       
        }else{
            $idDownload     = $id;
        }
        
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('idDownload', $idDownload);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Download Content');        
        $this->smarty->assign('pageLimit', $this->limit);
        $this->smarty->display('service/download_content_view.tpl');
    }
    
    public function ajaxGetDownloadContentList(){
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }        
        $idDownload = $this->input->post('id');
        $search     = strtoupper($this->input->post("search"));
        $page       = $this->uri->segment(4);
        $offset     = (isset ($page)) ? (int) $page : 0;
        $limit      = $this->input->post("limit");
        $paging     = "";
        $result     = "";
        
        $mData  = $this->download_content_model->getDownloadContentList($idDownload, $offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i      = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id             = $dt['id'];
                $sort           = $dt['sort'];
                $content_code   = $dt['code'];
                $title          = $dt['title'];
                $image          = $dt['image'];
                $price          = $dt['price'];
                $c_limit        = $dt['limit'];
    
                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                    
                $result .= "<td>$sort</td>";
                $result .= "<td>$content_code</td>";
                $result .= "<td>$title</td>";
                $result .= "<td>$image</td>";
                $result .= "<td>$price</td>";
                $result .= "<td>$c_limit</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editDownloadContent($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteDownloadContent($id);\">Delete</a></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "service/download_content/ajaxGetDownloadContentList/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows']  = $total;
                $pagination['per_page']    = $limit;

                $this->pagination->initialize($pagination);
                $paging_data = $this->pagination->create_links();
                $paging_data = preg_replace('/\<strong\>(\d+)\<\/strong\>/i', '<a class="current" href="">$1</a>', $paging_data);
                $paging_data = explode("&nbsp;", $paging_data);
                foreach ($paging_data as $page) {
                    if (!empty($page))
                        $paging.="<li>$page</li>";
                }
            } else {
                $paging = '<li><a class="current" href="">1</a></li>';
            }
        } else {
            $result .= "<tr><td colspan=\"7\">No data found</td></tr>";
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
    
    public function ajaxDeleteDownloadContent() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array ('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id         = $this->input->post("id");
        $response   = $this->download_content_model->deleteDownloadContent($id);

        echo json_encode($response);
        exit;
    }
}
