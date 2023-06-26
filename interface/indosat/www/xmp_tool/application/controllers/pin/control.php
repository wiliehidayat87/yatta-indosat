<?php

class Control extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('pin/control_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());

        $this->limit = $this->config->item('limit');
    }

    public function index() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
        $jsFile = 'pin/control.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Master Data Control');
        $this->smarty->assign('operator', $this->getOperatorList());
        $this->smarty->assign('hourStart', $this->getHour("00"));
        $this->smarty->assign('minuteStart', $this->getMinute("00"));
        $this->smarty->assign('hourEnd', $this->getHour("23"));
        $this->smarty->assign('minuteEnd', $this->getMinute("59"));
        $this->smarty->assign('pageLimit', $this->limit);
        $this->smarty->display('pin/control.tpl');
    }

    public function ajaxGetControlList() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $search = strtoupper($this->input->post("search"));
        $page = $this->uri->segment(4);
        $offset = (isset($page)) ? (int) $page : 0;
        $limit = $this->input->post("limit");
        $paging = "";
        $result = "";

        $mData = $this->control_model->getControlList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $operator = $dt['operator'];
                $scName = $dt['name'];
                $desc = $dt['desc'];
                $active = $dt['active'];
                $mon = $dt['mon'];
                $tue = $dt['tue'];
                $wed = $dt['wed'];
                $thu = $dt['thu'];
                $fri = $dt['fri'];
                $sat = $dt['sat'];
                $sun = $dt['sun'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";

                $result .= "<td>$operator</td>";
                $result .= "<td>$scName</td>";
                $result .= "<td>$desc</td>";
                $result .= "<td>" . ($active == 1 ? "Active" : "InActive") . "</td>";
                $result .= "<td>$mon</td>";
                $result .= "<td>$tue</td>";
                $result .= "<td>$wed</td>";
                $result .= "<td>$thu</td>";
                $result .= "<td>$fri</td>";
                $result .= "<td>$sat</td>";
                $result .= "<td>$sun</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editControl($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteControl($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "pin/control/ajaxGetControlList/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows'] = $total;
                $pagination['per_page'] = $limit;

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
            $result .= "<tr><td colspan=\"12\">No data found</td></tr>";
        }

        $to = ($page + $limit) > $total ? $total : ($page + $limit);


        $response = array(
            'offset' => $offset,
            'query' => $mData['query'],
            'result' => $result,
            'paging' => $paging,
            'from' => ($page + 1),
            'to' => $to,
            'total' => $total
        );

        echo json_encode($response);
    }

    public function getOperatorList() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-operator\" id=\"txt-operator\">";
        $result .="<option value=\"\">-- operator --</option>";
        foreach ($this->control_model->readOperator() as $_data) {
            $operator = $_data['name'];
            $result .="<option value=\"$operator\">$operator</option>";
        }
        $result .="</select>";
        $result .="</span>";
        return $result;
    }

    public function ajaxSaveControl() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $operator = $this->input->post("txt-operator");
        $name = $this->input->post("txt-name");
        $desc = $this->input->post("txt-desc");
        $active = $this->input->post("txt-active");
        $mon = $this->input->post("txt-mon");
        $tue = $this->input->post("txt-tue");
        $wed = $this->input->post("txt-wed");
        $thu = $this->input->post("txt-thu");
        $fri = $this->input->post("txt-fri");
        $sat = $this->input->post("txt-sat");
        $sun = $this->input->post("txt-sun");
        $preg = true;
        $response = array();

        #validate
        if (empty($operator)) {
            $status_operator = FALSE;
            $msg_operator = "Operator Field is Required";
        } else {
            $status_operator = TRUE;
            $msg_operator = "";
        }
        if (empty($name)) {
            $status_name = FALSE;
            $msg_name = "Name Field is Required";
        } else {
            $status_name = TRUE;
            $msg_name = "";
        }
        if (empty($desc)) {
            $status_desc = FALSE;
            $msg_desc = "Description Field is Required";
        } else {
            $status_desc = TRUE;
            $msg_desc = "";
        }
        if (empty($mon)) {
            $status_mon = FALSE;
            $msg_mon = "Mon Field is Required";
        } else {
            $status_mon = TRUE;
            $msg_mon = "";
        }
        if (empty($tue)) {
            $status_tue = FALSE;
            $msg_tue = "Tue Field is Required";
        } else {
            $status_tue = TRUE;
            $msg_tue = "";
        }
        if (empty($wed)) {
            $status_wed = FALSE;
            $msg_wed = "Wed Field is Required";
        } else {
            $status_wed = TRUE;
            $msg_wed = "";
        }
        if (empty($thu)) {
            $status_thu = FALSE;
            $msg_thu = "Thu Field is Required";
        } else {
            $status_thu = TRUE;
            $msg_thu = "";
        }
        if (empty($fri)) {
            $status_fri = FALSE;
            $msg_fri = "Fri Field is Required";
        } else {
            $status_fri = TRUE;
            $msg_fri = "";
        }
        if (empty($sat)) {
            $status_sat = FALSE;
            $msg_sat = "Sat Field is Required";
        } else {
            $status_sat = TRUE;
            $msg_sat = "";
        }
        if (empty($sun)) {
            $status_sun = FALSE;
            $msg_sun = "Sun Field is Required";
        } else {
            $status_sun = TRUE;
            $msg_sun = "";
        }

        if (!empty($operator) && !empty($name) && !empty($desc) && !empty($mon) && !empty($tue) && !empty($wed) && !empty($thu) && !empty($fri) && !empty($sat) && !empty($sun) && $preg == true) {
            $data = array();
            $data['name'] = $name;
            $data['operator'] = $operator;
            $data['desc'] = $desc;
            $data['active'] = $active;
            $data['mon'] = $mon;
            $data['tue'] = $tue;
            $data['wed'] = $wed;
            $data['thu'] = $thu;
            $data['fri'] = $fri;
            $data['sat'] = $sat;
            $data['sun'] = $sun;
            if ($this->control_model->checkControl($data)) {
                $response = array(
                    'status_mon' => FALSE,
                    'msg_mon' => "name,operator is not available, please try another combination",
                    'status' => FALSE,
                    'message' => 'name,operator is not available, please try another combination'
                );
            } else {
                if ($active == '1')
                    $this->control_model->updateStatusByOperator($data);
                $response = $this->control_model->saveControl($data);
            }
        } else {
            $response = array(
                'status_operator' => $status_operator,
                'msg_operator' => $msg_operator,
                'status_name' => $status_name,
                'msg_name' => $msg_name,
                'status_desc' => $status_desc,
                'msg_desc' => $msg_desc,
                'status_mon' => $status_mon,
                'msg_mon' => $msg_mon,
                'status_tue' => $status_tue,
                'msg_tue' => $msg_tue,
                'status_wed' => $status_wed,
                'msg_wed' => $msg_wed,
                'status_thu' => $status_thu,
                'msg_thu' => $msg_thu,
                'status_fri' => $status_fri,
                'msg_fri' => $msg_fri,
                'status_sat' => $status_sat,
                'msg_sat' => $msg_sat,
                'status_sun' => $status_sun,
                'msg_sun' => $msg_sun,
                'status' => FALSE,
                'message' => 'required field'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function ajaxEditControl() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $result = $this->control_model->editControl($id);

        $mon = $this->extractTime($result[0]['mon']);
        $tue = $this->extractTime($result[0]['tue']);
        $wed = $this->extractTime($result[0]['wed']);
        $thu = $this->extractTime($result[0]['thu']);
        $fri = $this->extractTime($result[0]['fri']);
        $sat = $this->extractTime($result[0]['sat']);
        $sun = $this->extractTime($result[0]['sun']);

        $response = array(
            'operator' => $result[0]['operator'],
            'name' => $result[0]['name'],
            'desc' => $result[0]['desc'],
            'active' => $result[0]['active'],
            'monHStart' => $mon['hStart'],
            'monMStart' => $mon['mStart'],
            'monHEnd' => $mon['hEnd'],
            'monMEnd' => $mon['mEnd'],
            'tueHStart' => $tue['hStart'],
            'tueMStart' => $tue['mStart'],
            'tueHEnd' => $tue['hEnd'],
            'tueMEnd' => $tue['mEnd'],
            'wedHStart' => $wed['hStart'],
            'wedMStart' => $wed['mStart'],
            'wedHEnd' => $wed['hEnd'],
            'wedMEnd' => $wed['mEnd'],
            'thuHStart' => $thu['hStart'],
            'thuMStart' => $thu['mStart'],
            'thuHEnd' => $thu['hEnd'],
            'thuMEnd' => $thu['mEnd'],
            'friHStart' => $fri['hStart'],
            'friMStart' => $fri['mStart'],
            'friHEnd' => $fri['hEnd'],
            'friMEnd' => $fri['mEnd'],
            'satHStart' => $sat['hStart'],
            'satMStart' => $sat['mStart'],
            'satHEnd' => $sat['hEnd'],
            'satMEnd' => $sat['mEnd'],
            'sunHStart' => $sun['hStart'],
            'sunMStart' => $sun['mStart'],
            'sunHEnd' => $sun['hEnd'],
            'sunMEnd' => $sun['mEnd']
        );

        echo json_encode($response);
        exit;
    }

    private function extractTime($sting) {
        $data = explode("-", $sting);
        foreach ($data as $val) {
            $data2 = explode(":", $val);
            $hour[] = $data2[0];
            $minute[] = $data2[1];
        }
        return array('hStart' => $hour[0], 'mStart' => $minute[0], 'hEnd' => $hour[1], 'mEnd' => $minute[1]);
    }

    public function ajaxUpdateControl($id) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $operator = $this->input->post("txt-operator");
        $name = $this->input->post("txt-name");
        $desc = $this->input->post("txt-desc");
        $active = $this->input->post("txt-active");
        $mon = $this->input->post("txt-mon");
        $tue = $this->input->post("txt-tue");
        $wed = $this->input->post("txt-wed");
        $thu = $this->input->post("txt-thu");
        $fri = $this->input->post("txt-fri");
        $sat = $this->input->post("txt-sat");
        $sun = $this->input->post("txt-sun");
        $preg = true;
        #validate
        if (empty($operator)) {
            $status_operator = FALSE;
            $msg_operator = "Operator Field is Required";
        } else {
            $status_operator = TRUE;
            $msg_operator = "";
        }
        if (empty($name)) {
            $status_name = FALSE;
            $msg_name = "Name Field is Required";
        } else {
            $status_name = TRUE;
            $msg_name = "";
        }
        if (empty($desc)) {
            $status_desc = FALSE;
            $msg_desc = "Description Field is Required";
        } else {
            $status_desc = TRUE;
            $msg_desc = "";
        }
        if (empty($mon)) {
            $status_mon = FALSE;
            $msg_mon = "Mon Field is Required";
        } else {
            $status_mon = TRUE;
            $msg_mon = "";
        }
        if (empty($tue)) {
            $status_tue = FALSE;
            $msg_tue = "Tue Field is Required";
        } else {
            $status_tue = TRUE;
            $msg_tue = "";
        }
        if (empty($wed)) {
            $status_wed = FALSE;
            $msg_wed = "Wed Field is Required";
        } else {
            $status_wed = TRUE;
            $msg_wed = "";
        }
        if (empty($thu)) {
            $status_thu = FALSE;
            $msg_thu = "Thu Field is Required";
        } else {
            $status_thu = TRUE;
            $msg_thu = "";
        }
        if (empty($fri)) {
            $status_fri = FALSE;
            $msg_fri = "Fri Field is Required";
        } else {
            $status_fri = TRUE;
            $msg_fri = "";
        }
        if (empty($sat)) {
            $status_sat = FALSE;
            $msg_sat = "Sat Field is Required";
        } else {
            $status_sat = TRUE;
            $msg_sat = "";
        }
        if (empty($sun)) {
            $status_sun = FALSE;
            $msg_sun = "Sun Field is Required";
        } else {
            $status_sun = TRUE;
            $msg_sun = "";
        }

        if (!empty($operator) && !empty($name) && !empty($desc) && !empty($mon) && !empty($tue) && !empty($wed) && !empty($thu) && !empty($fri)) {
            $data = array();
            $data['id'] = $id;
            $data['name'] = $name;
            $data['operator'] = $operator;
            $data['desc'] = $desc;
            $data['active'] = $active;
            $data['mon'] = $mon;
            $data['tue'] = $tue;
            $data['wed'] = $wed;
            $data['thu'] = $thu;
            $data['fri'] = $fri;
            $data['sat'] = $sat;
            $data['sun'] = $sun;
            if ($active == '1')
                $this->control_model->updateStatusByOperator($data);
            $response = $this->control_model->updateControl($data);
        } else {
            $response = array(
                'status_operator' => $status_operator,
                'msg_operator' => $msg_operator,
                'status_name' => $status_name,
                'msg_name' => $msg_name,
                'status_desc' => $status_desc,
                'msg_desc' => $msg_desc,
                'status_mon' => $status_mon,
                'msg_mon' => $msg_mon,
                'status_tue' => $status_tue,
                'msg_tue' => $msg_tue,
                'status_wed' => $status_wed,
                'msg_wed' => $msg_wed,
                'status_thu' => $status_thu,
                'msg_thu' => $msg_thu,
                'status_fri' => $status_fri,
                'msg_fri' => $msg_fri,
                'status_sat' => $status_sat,
                'msg_sat' => $msg_sat,
                'status_sun' => $status_sun,
                'msg_sun' => $msg_sun,
                'status' => FALSE,
                'message' => 'required field'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteControl() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $response = $this->control_model->deleteControl($id);

        echo json_encode($response);
        exit;
    }

    protected function getHour($selected = false) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
        $countHour = 24;
        $result = "";
        for ($i = 0; $i < $countHour; $i++) {
            $hour = str_pad($i, 2, 0, STR_PAD_LEFT);
            if ($hour == $selected)
                $select = "selected=\"selected\"";
            else
                $select = "";
            $result .="<option value=\"$hour\" $select>$hour</option>";
        }
        return $result;
    }

    protected function getMinute($selected = false) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
        $countMinutes = 60;
        $result = "";
        for ($j = 0; $j < $countMinutes; $j++) {
            $minute = str_pad($j, 2, 0, STR_PAD_LEFT);
            if ($minute == $selected)
                $select = "selected=\"selected\"";
            else
                $select = "";
            $result .="<option value=\"$minute\" $select>$minute</option>";
        }
        return $result;
    }

}