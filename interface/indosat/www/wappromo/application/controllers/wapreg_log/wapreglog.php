<?php
class Wapreglog extends CI_Controller
{
    //function Wapreglog()
   // {
       // parent::Controller();
 function __construct() {
        parent::__construct();
	$this->klogger->log("");
        $this->load->model("m_wapreglog");
       // $this->load->library('DX_Auth');
        $this->load->helper('url');
        //$this->dx_auth->check_uri_permissions();

        $varArray = $this->uri->uri_string();
        $temp     = explode('/', $varArray);
        $this->shortcode = $temp[1];

        $config = get_config();
        $this->mysmarty->assign('base_url', base_url());
        //$this->mysmarty->assign('shortcode', $config['shortcode']);
        $this->mysmarty->assign('activeShortcode', $this->shortcode);
    }

    function index2()
    {
	$this->klogger->log("");
        $this->load->library('pagination');
        // $this->load->model('m_wapreglog');

        $array_url = $this->uri->uri_to_assoc(2);
        $from      = (!isset ($array_url['page'])) ? 0 : $array_url['page'];
        $limit     = 2;
        $today     = date("Y-m-d");
        

            if ($this->input->post('dstart') == FALSE && !isset ($array_url['dstart']))
            {
                $dstart = $today;
                $dend   = $today;
                $result	 = $this->m_wapreglog->getWapregLog($from,$limit,$dstart,$dend);
                
                 $dataSend   = $result['result']['data'];
                 $count      = $result['result']['total'];

                $pagination['base_url']	   = sprintf('%s/wapreg_log/wapreglog/index/page/', base_url());
                $pagination['uri_segment'] = 5;
                
                $this->mysmarty->assign('dstart', $dstart);
                $this->mysmarty->assign('dend', $dend);
            }
            else
            {
                $dstart = ($this->input->post('dstart') == TRUE) ? $this->input->post('dstart') : $array_url['dstart'];
                $dend = ($this->input->post('dend') == TRUE) ? $this->input->post('dend') : $array_url['dend'];

                $result	 = $this->m_wapreglog->getWapregLog($from,$limit,$dstart,$dend);
                 $dataSend   = $result['result']['data'];
                 $count      = $result['result']['total'];

                $pagination['base_url']	   = sprintf('%s/wapreg_log/wapreglog/index/dstart/%/dend/%/page/', base_url(), $dstart,$dend);
                $pagination['uri_segment'] = 9;

                $this->mysmarty->assign('dstart', $dstart);
                $this->mysmarty->assign('dend', $dend);
            }
   

       // $pagination['total_rows'] =
       // echo 'jumlahnya: '.$count;
       // $pagination['per_page']   =
       // echo 'limitnya; '.$limit;

        $this->pagination->initialize($pagination);
        $html_pagination = $this->pagination->create_links();
       // echo "ini akfaifdsfa : ".$html_pagination;
        $this->mysmarty->assign('result', $dataSend);
        $this->mysmarty->assign('pagination', $html_pagination);
        $this->mysmarty->assign('today', $today);
        $this->mysmarty->assign('page_title', 'Banner Page');
        $this->mysmarty->view('wapreg_log/list.html');
    }

    function index() {
	    $this->klogger->log("");
            // $this->load->model('wap_portal');
            $this->load->library('pagination');
            
            $array_url = $this->uri->uri_to_assoc(2);
            $from      = (!isset ($array_url['page'])) ? 0 : $array_url['page'];
            $limit     = 20;
            $today     = date("Y-m-d");

             if ($this->input->post('dstart') == FALSE && !isset ($array_url['dstart']))
            {
                $dstart = $today;
                $dend   = $today;
                $service = 'keyword';

                $from = ($from != 0)? $from +1:0;
                 $mData	   = $this->m_wapreglog->getWapregLog($from,$limit,$dstart,$dend);
                 $result   = $mData['result']['data'];
                 $count    = $mData['total'];

                $config['base_url']	   = sprintf('%s/wapreg_log/wapreglog/index/page/', base_url());
                $config['uri_segment'] = 5;

                $this->mysmarty->assign('dstart', $dstart);
                $this->mysmarty->assign('dend', $dend);
                $this->mysmarty->assign('service', $service);
            }
            else
            {
                $dstart  = ($this->input->post('dstart') == TRUE) ? $this->input->post('dstart') : $array_url['dstart'];
                $dend    = ($this->input->post('dend') == TRUE) ? $this->input->post('dend') : $array_url['dend'];
                $service = ($this->input->post('service') == TRUE) ? $this->input->post('service') : $array_url['service'];

             //   echo "dstart :".$array_url['dstart']." dend: ".$array_url['dend']." from : ".$from;
                if($service == "keyword")$service = '';

                 $from = ($from != 0)? $from +1:0;
                 $mData	   = $this->m_wapreglog->getWapregLog($from,$limit,$dstart,$dend,$service);
                 if($service == '') $service = "keyword";
                 $result   = $mData['result']['data'];
                 $count    = $mData['total'];

                $config['base_url']	   = sprintf('%s/wapreg_log/wapreglog/index/dstart/%s/dend/%s/service/%s/page/', base_url(), $dstart,$dend,$service);
                $config['uri_segment'] = 11;

                $this->mysmarty->assign('dstart', $dstart);
                $this->mysmarty->assign('dend', $dend);
                $this->mysmarty->assign('service', $service);
            }

            $config['total_rows'] = $count;
            $config['per_page']   = $limit;
          //$config['num_links']  = 1;

            $this->pagination->initialize($config);

      //    echo 'halo'. $this->pagination->create_links();
           
//var_dump($result);exit;
            $this->mysmarty->assign('result', $result);
            $this->mysmarty->assign('pagination', $this->pagination->create_links());
            $this->mysmarty->assign('hasPage', ceil($count/$limit)+1);
            $this->mysmarty->assign('page_title', 'Banner Page');
            $this->mysmarty->view('wapreg_log/list.html');
        }


    public function ajaxGetMsiDetails()
    {
	$this->klogger->log("");
        $result = "";
        $tbody  = "";

        $mData  = $this->m_wapreglog->ajaxGetJDDetails();

        $ttsel   = $mData['ttsel'];
        $tisat   = $mData['tisat'];
        $txl     = $mData['txl'];

        $tbody .= "<tr>";
        $tbody .= "<td>$ttsel</td>";
        $tbody .= "<td>$tisat</td>";
        $tbody .= "<td>$txl</td>";
        $tbody .= "</tr>";

        $response = array ('tbody' => $tbody);

        echo json_encode($response);
    }

    function add_edit($type, $id = 0)
    {
	$this->klogger->log("");
        $error       = FALSE;
        $banner_link = "";

        if ($type == "add")
        {
            if ($this->input->post('submit') == TRUE)
            {
                $banner_link = $this->input->post('banner_link', TRUE);

                if (empty ($banner_link))
                {
                    $error .= 'Required field is empty<br />';
                }
                else
                {
                    if (isset ($_FILES['banner_image']) && $_FILES['banner_image']['size'] > 0)
                    {
                        $do_upload = $this->upload('banner_image');

                        if ($do_upload['status'] == FALSE)
                        {
                            $error = $do_upload['error'];
                        }
                        else
                        {
                            $result = $this->m_wapreglog->addEdit($type, $do_upload['upload_data']['file_name'], $banner_link);

                            if ($result['status'] == true)
                                redirect(base_url().'m_wapreglog/banner');
                            else
                                $error .= $result['message'];
                        }
                    }
                }
            }
        }
        else if ($type == "edit")
        {
            $data        = $this->m_wapreglog->get_data_banner_by_id($id);
            $banner_id   = $id;
            $banner_link = $data['link'];
            $cek_edit    = "";

            if ($this->input->post('submit') == TRUE)
            {
                $banner_link = $this->input->post('banner_link', TRUE);
                $edit_banner = $this->input->post('edit_banner_image', TRUE);
                $cek_edit    = ($edit_banner == 1) ? "checked" : "";

                if (empty ($banner_link))
                {
                    $error .= 'Required field is empty<br />';
                }
                else
                {
                    if ($edit_banner == 1)
                    {
                        if (isset ($_FILES['banner_image']) && $_FILES['banner_image']['size'] > 0)
                        {
                            $do_upload = $this->upload('banner_image');

                            if ($do_upload['status'] == FALSE) {
                                $error = $do_upload['error'];
                            }
                            else {
                                $result = $this->m_wapreglog->addEdit($type, $do_upload['upload_data']['file_name'], $banner_link, $id);

                                if ($result['status'] == TRUE)
                                    redirect(base_url().'m_wapreglog/banner');
                                else
                                    $error .= $result['message'];
                            }
                        }
                    }
                    else
                    {
                        $result = $this->m_wapreglog->addEdit($type, "<NULL>", $banner_link, $id);

                        if ($result['status'] == TRUE)
                            redirect(base_url().'m_wapreglog/banner');
                        else
                            $error .= $result['message'];
                    }
                }

                $this->mysmarty->assign('cek_edit', $cek_edit);
            }
        }

        $page_title = ($type == "add") ? "Add" : "Edit";
        $this->mysmarty->assign('type', $type);
        $this->mysmarty->assign('id', $id);
        $this->mysmarty->assign('banner_link', $banner_link);
        $this->mysmarty->assign('error', $error);
        $this->mysmarty->assign('page_title', $page_title.' Banner');
        $this->mysmarty->view('m_wapreglog/addEdit.html');
    }

    function insert_update($type, $id = 0) {
	$this->klogger->log("");
        $error       = "";
        $banner_id   = 0;
        $banner_link = "";

        if ($this->input->post('submit') == TRUE) {
            $banner_link = $this->input->post('banner_link', TRUE);
            $error       = FALSE;

            if (empty ($banner_link)) {
                $error .= 'Required field is empty<br />';
            }
            else {
                if ($type == 'edit') {
                    $edit_banner_image = $this->input->post('edit_banner_image', TRUE);

                    if (isset ($edit_banner_image) || $edit_banner_image == 1) {
                        if (isset ($_FILES['banner_image']) && $_FILES['banner_image']['size'] > 0) {
                            $do_upload = $this->upload('banner_image');

                            if ($do_upload['status'] == FALSE) {
                                $error = $do_upload['error'];
                            }
                            else {
                                $result = $this->m_wapreglog->addEdit($type, $do_upload['upload_data']['file_name'], $banner_link, $id);

                                if ($result['status'] == true)
                                    redirect(base_url().'m_wapreglog/banner');
                                else
                                    $error .= $result['message'];
                            }
                        }
                    }
                    else {
                        $result = $this->m_wapreglog->addEdit($type, "", $banner_link, $id);

                        if ($result['status'] == true)
                            redirect(base_url().'m_wapreglog/banner');
                        else
                            $error .= $result['message'];
                    }
                }
                else {
                    if (isset ($_FILES['banner_image']) && $_FILES['banner_image']['size'] > 0) {
                        $do_upload = $this->upload('banner_image');

                        if ($do_upload['status'] == FALSE) {
                            $error = $do_upload['error'];
                        }
                        else {
                            $result = $this->m_wapreglog->addEdit($type, $do_upload['upload_data']['file_name'], $banner_link);

                            if ($result['status'] == true)
                                redirect(base_url().'m_wapreglog/banner');
                            else
                                $error .= $result['message'];
                        }
                    }
                }
            }
        }
        else {
            if ($type == "edit") {
                $data = $this->m_wapreglog->get_data_banner_by_id($id);
                $banner_id = $id;
                $banner_link = $data['link'];
            }
        }

        $page_title = ($type == "add") ? "Add" : "Edit";

        $this->mysmarty->assign('type', $type);
        $this->mysmarty->assign('id', $id);
        $this->mysmarty->assign('banner_id', $banner_id);
        $this->mysmarty->assign('banner_link', $banner_link);
        $this->mysmarty->assign('error', $error);
        $this->mysmarty->assign('page_title', $page_title.' Banner');
        $this->mysmarty->view('m_wapreglog/addEdit.html');
    }

    function delete($id) {
	$this->klogger->log("");
        try {
            if ($this->m_wapreglog->delete($id) == TRUE)
                redirect(base_url().'m_wapreglog/banner');
            else
                echo "delete process error..!!";
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function upload($file) {
	$this->klogger->log("");
        $config['upload_path']   = './temp/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '1024';
        $config['max_width']     = '200';

        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file) == FALSE)
            $result = array ('status' => FALSE, 'result' => 'failed', 'error' => $this->upload->display_errors());
        else
            $result = array ('status' => TRUE, 'result' => 'success', 'upload_data' => $this->upload->data());

        return $result;
    }
}
?>
