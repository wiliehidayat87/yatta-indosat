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

class Content extends CI_Controller {
	public $limit = 0;

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('wap_username'))
        redirect(base_url() . 'login');
	$this->klogger->log("");
        $this->load->model(array ('navigation_model', 'content_model'));

        $this->mysmarty->assign('navigation',   $this->navigation_model->getMenuHtml());
        $this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));
        $this->mysmarty->assign('base_url',     base_url());        

	$this->limit = $this->config->item('limit');
	       
    }

    function index() {
	$this->klogger->log("");
        $this->mysmarty->assign('base_url', base_url());
        $this->mysmarty->assign('site', $this->getWapSiteList());
//        $this->mysmarty->assign('content', $this->getWapContentList());
        $this->mysmarty->assign('code', $this->getWapContentCode());
        $this->mysmarty->view('wap/content_view.html');
    }
    
    public function ajaxGetContentList() {
	$this->klogger->log("");
	$search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit  = $this->limit;
        $paging = "";
        $result = "";
       
        
        $mData  = $this->content_model->getContentList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];

        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id             = $dt['id'];
                $site           = $dt['site_id'];
                $content        = $dt['code_title'];
                $code           = $dt['code'];
                $price          = $dt['price'];               
                $sort           = $dt['sort'];
    
                $result .= "<tr>";
                $result .= "<td>$site</td>";
                $result .= "<td>$content</td>";
                $result .= "<td>$code</td>";
                $result .= "<td>$price</td>";
                $result .= "<td>$sort</td>";
                $result .= "<td><div class=\"menulink\"><a onclick=\"editContent($id);\">Edit</a> <a onclick=\"deleteContent($id);\">Delete</a></div></td>";
                $result .= "</tr>";
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "wap/content/ajaxGetContentList/";
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
    
    public function getWapSiteList(){
	$this->klogger->log("");
        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-site\" id=\"txt-site\">";
        foreach ($this->content_model->readWapSite() as $_data)
        {
            $id             = $_data['ID'];
            $site           = $_data['Name'];    
            $result .="<option value=\"$id\">$site</option>";
        }   
        $result .="</select>";
        $result .="</span>";
        return $result;
        
    }
    
//    public function getWapContentList(){
//        $result = "";
//        $result .="<span>";
//        $result .="<select name=\"txt-content\" id=\"txt-content\">";
//        foreach ($this->content_model->readWapContent() as $__data)
//        {
//            $id         = $__data['ID'];
//            $content    = $__data['Title'];    
//            $result    .="<option value=\"$id\">$content</option>";
//        }   
//        $result .="</select>";
//        $result .="</span>";
//        
//        return $result;
//    }
    
    public function getWapContentCode(){
	$this->klogger->log("");
        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-code\" id=\"txt-code\">";
        foreach ($this->content_model->readWapContent() as $___data)
        {
            $id         = $___data['ID'];
            $code       = $___data['Code'];    
            $result    .="<option value=\"$code\">$code</option>";
        }   
        $result .="</select>";
        $result .="</span>";
        
        return $result;
    }
    
    public function ajaxAddNewContent() {
	$this->klogger->log("");
        $site       = $this->input->post("txt-site");
//        $content    = $this->input->post("txt-content");
        $code       = $this->input->post("txt-code");
        $price      = $this->input->post("txt-price");

        $response = array ();
        
        //validate    
        if (empty ($price)) {
            $status_price = FALSE;
            $msg_price    = "require field";
        }
        else {
            $status_price = TRUE;
            $msg_price    = "";
        }
        
        if (!empty($price))
        {
            if ($this->content_model->check_content_code($code, $site))
            {
                $response = array (
                            'status_code' => FALSE, 
                            'msg_code' => "Content Code for this Site already exist, try another code",
                            'status' => FALSE, 
                            'message' => 'Content Code for this Site already exist, try another code'
                );
            }
            else {
                if (is_numeric($price)) {
                    $response   = $this->content_model->addNewContent($site, $code, $price);
                }
                else {
                    $response = array ('status_price' => FALSE, 'msg_price' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                }
            }
        }
        else
        {
            $response = array ( 'status_price'      => $status_price,
                                'msg_price'         => $msg_price,
                                'status'            => FALSE, 
                                'message'           => 'error'
                              );
        }
        
        echo json_encode($response);
        exit;
        
    }
    
    public function ajaxEditContent() {
	$this->klogger->log("");
        $id     = $this->input->post("id");
        $result = $this->content_model->editContent($id);

        $response = array (
            'site'   => $result[0]['site_id'],
            'code'   => $result[0]['code'],
            'price'  => $result[0]['price'],
            'sort'   => $result[0]['sort'],
       );

        echo json_encode($response);
        exit;
    }
    
    public function ajaxUpdateContent($id) {
        $this->klogger->log("");
        $site         = $this->input->post("txt-site");
        $code         = $this->input->post("txt-code");
        $price        = $this->input->post("txt-price");
        $sort         = $this->input->post("txt-sort");
        $code_compare = $this->input->post("txt-code-compare");
        $sort_old     = $this->input->post("txt-sort-old");

        $response = array();
        
        //validate
        if (empty ($price)) {
            $status_price = FALSE;
            $msg_price    = "require field";
        }
        else {
            $status_price = TRUE;
            $msg_price    = "";
        }
        
        if (!empty($price))
        {
            if($code==$code_compare)
            {
                $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);
                if (is_numeric($price)) {
                    if (is_numeric($sort)) {
                        $response   = $this->content_model->updateContent($id, $site, $code, $price, $sort, $sort_old);
                    }
                    else {
                        $response = array ('status_sort' => FALSE, 'msg_sort' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                    }
                }
                else {
                    $response = array ('status_price' => FALSE, 'msg_price' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                }
            }
            else {
                if ($this->content_model->check_content_code($code, $site))
                {
                    $response = array (
                                'status_code' => FALSE, 
                                'msg_code' => "Content Code for this Site already exist, try another code",
                                'status' => FALSE, 
                                'message' => 'Content Code for this Site already exist, try another code'
                    );
                }
                else {
                    if (is_numeric($price)) {
                        if (is_numeric($sort)) {
                            $response   = $this->content_model->updateContent($id, $site, $code, $price, $sort, $sort_old);
                        }
                        else {
                            $response = array ('status_sort' => FALSE, 'msg_sort' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                        }
                    }
                    else {
                        $response = array ('status_price' => FALSE, 'msg_price' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                    }
                }
            }
        }
        else
        {
            $response = array ( 'status_price'      => $status_price,
                                'msg_price'         => $msg_price,
                                'status'            => FALSE, 
                                'message'           => 'error'
                              );
        }
        
        echo json_encode($response);
        exit;
        
    }
    
    public function ajaxDeleteContent() {
	$this->klogger->log("");
        $id       = $this->input->post("id");
        $response = $this->content_model->deleteContent($id);

        echo json_encode($response);
        exit;
    }  
}
