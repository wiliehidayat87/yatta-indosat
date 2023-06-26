<?php

class Add_Service extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('service/creator_model');
        $this->load->model(array('masterdata/adn_model', 'masterdata/service_model', 'masterdata/operator_model'));
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->limit = $this->config->item('limit');
    }

    public function index() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        /*
          if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
          $message = $this->link_auth->errorMessage();

          if ($message['Message'] == "Class not Found")
          redirect(base_url() . 'errorpage/errorpage/classNotFound');
          if ($message['Message'] == "Feature Disabled")
          redirect(base_url() . 'errorpage/errorpage/featureDisabled');
          exit;
          }
         */
        $sess_data = array('service_name' => '', 'adn' => '', 'operator' => '', 'pattern' => '');
        $this->session->unset_userdata($sess_data);
        $error = $this->session->flashdata('error'); //var_dump($error);
        $jsFile = 'service/creator.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Creator');
        $this->smarty->assign('operator', $this->getOperatorList());
        //$this->smarty->assign('service', $this->getServiceList());
        $this->smarty->assign('adn', $this->getAdnList());
        //$this->smarty->assign('pageLimit', $this->limit);
        $this->smarty->assign('error', $error);
        $this->smarty->display('service/service_add.tpl');
    }

    public function edit_addkeyword() {
        $adn = $this->input->post("adn");
        $operator = $this->input->post('operator');
        $service_id = $this->input->post('service_id');
        $service_name = $this->input->post('service_name');
        $pattern = $this->input->post('pattern');

        $sess_data = array('operator' => $operator, 'pattern' => $pattern, 'service_id' => $service_id, 'service_name' => $service_name, 'adn' => $adn );
        #quick hack : restore database to default
        $this->load->database('default', TRUE);
        $this->session->set_userdata($sess_data);

        redirect('/service/add_service/keyword', 'refresh');
    }

    public function edit_keyword() {
        $service_id = (int)$this->uri->segment(4);
        $operator = $this->uri->segment(5);
        $pattern_id = (int)$this->uri->segment(6);

        #get service_name and adn by id
        $service = $this->service_model->editService($service_id);
        //var_dump($service);
        $jsFile = 'service/creator.js';
        $this->smarty->assign('jsFile', $jsFile);

        $result = $this->creator_model->getCreatorById($pattern_id);
        $this->smarty->assign('mecha', $result);

        $reply = $this->creator_model->getReplyByMechaId($pattern_id);
        $n = 1;
        $modules = array();
        foreach ($reply as $reply_value) {

            $attribute = $this->creator_model->getAttributeByReplyId($reply_value['id']);

            $param['module_name'] = $reply_value['module_name'];
            $param['adn'] = $result['adn'];
            $param['operator'] = $result['operator'];
            $param['message'] = $reply_value['message'];
            $param['charging_id'] = $reply_value['charging_id'];
            foreach ($attribute as $attribute_value) {
                $param[$attribute_value['attribute_name']] = $attribute_value['value'];
            }
            $selectModule = json_decode($this->getSelectModuleForEdit($param), true);

            if ($n == 1) {
                $module['select'] = "";
                $modulePull = json_decode($this->getModulePull($param), true);
            } else {
                $module['select'] = $selectModule['data'];
                $modulePull = json_decode($this->getModule($param), true);
            }
            $module['module'] = $modulePull['data'];
            $modules[] = $module;
            $n++;
        }

        $this->smarty->assign('module_name', $reply[0]['module_name']);
        $this->smarty->assign('module', $modules);
        $this->smarty->assign('reply', $reply);
        $this->smarty->assign('mechanism_id', $pattern_id);
        $this->smarty->assign('service_name',$service[0]['name']);
        $this->smarty->assign('adn',$service[0]['adn']);

        $this->smarty->assign('operator', array($operator));
        $this->smarty->assign('service_id', $service_id);
        $this->smarty->display('service/keyword_edit.tpl');
    }

    public function edit() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        $service_id = (int) $this->uri->segment(4);

        $jsFile = 'service/add_service.js';
        $this->smarty->assign('jsFile', $jsFile);

        #get keyword, adn, serviceid, servicename, operator
        $keywords = $this->creator_model->getListKeywordsByServiceId($service_id);
        $service = $this->creator_model->getServiceById($service_id);
        $adn = $service[0]->adn;
        $service_name = $service[0]->name;

        $operators = $this->creator_model->getOperatorByServiceId($service_id);
        foreach ($keywords as $val) {
            $operator_pattern[$val['pattern']] = $this->creator_model->getListKeywordsByPattern($val['pattern'], $service_id);
        }

        //$operator = $operator[0]->name;
        if (count($operators) > 0) {
            foreach ($operators as $operat) {
                $operator[] = $operat->name;
            }
        }
        //var_dump($operator, $service_name, $adn);

        $op = $this->getOperatorList();
        foreach ($op as $v) {
            $operator_list[] = $v['name'];
        }

        $available_operator = array_diff($operator_list, $operator);
        //var_dump($operator, $operator_pattern);

        $jsFile = 'service/creator.js';
        $this->smarty->assign('keywords', $keywords);
        $this->smarty->assign('operator', $operator); // operator di dalam servicenya
        $this->smarty->assign('operator_pattern', $operator_pattern);
        $this->smarty->assign('available_operator', $available_operator); // list operator
        $this->smarty->assign('adn', $adn);
        $this->smarty->assign('all_adn', $this->getAdnList());
        $this->smarty->assign('service_name', $service_name);
        $this->smarty->assign('service_id', $service_id);
        $this->smarty->display('service/service_edit.tpl');
    }

    public function submit() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        /*
          if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
          $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
          echo json_encode($response);
          exit;
          }
         */

        $service_name = $this->input->post("service_name");
        $adn = $this->input->post("adn");
        $operator = $this->input->post('operator');

        $response = array();

        # validate required field
        if (empty($service_name)) {
            $error[] = "Service Name Field is Required";
        }
        if (empty($adn)) {
            $error[] = "ADN Field is Required";
        }
        if (count($operator) < 1 or $operator === false)
            $error[] = "Operator Field is Required";



        # if error exist then redirect to prev page
        if (count($error) > 0) {
            $this->session->set_flashdata('error', $error);
            redirect('service/add_service');
        }

        #check if service with adn is already exist
        if ($this->service_model->check_service_name($service_name, $adn)) {
            $error [] = "Service Name [$service_name] and Adn [$adn] already exist, please try another combination";
            $this->load->database('default', TRUE);
            $this->session->set_flashdata('error', $error);
            redirect('service/add_service');
        } else { # add new service
            $result = $this->service_model->addNewService($service_name, $adn);

            if ($result['status'] === true) {
                $sess_data = array('service_name' => $service_name, 'adn' => $adn, 'operator' => $operator, 'service_id' => $result['message']);
                #quick hack : restore database to default
                //var_dump($sess_data);
                $this->load->database('default', TRUE);
                $this->session->set_userdata($sess_data);
                redirect('/service/add_service/keyword', 'refresh');
            } else {
                $error [] = "Failed To Add New Service Name [$service_name] and Adn [$adn] , please try again ";
                $this->session->set_flashdata('error', $error);
                redirect('service/add_service');
            }
        }


        /*
          $submit = strtolower($this->input->post('submit'));

          switch($submit) {
          case 'add keyword':
          $this->add_keyword();
          break;
          case 'reset':
          $this->reset();
          break;
          case 'input':
          $this->input();
          break;
          }
         */
    }

    public function submit_keyword() {
    //echo "<pre>"; //var_dump($_POST);exit;
        $active = $this->input->post('active');
        $keyword = $this->input->post('keyword');
        $mechanism = $this->input->post('mechanism');
        $service_id = $this->input->post('service_id');
        $custom_handler = $this->input->post('custom');

        if (count($custom_handler) > 0) {
            foreach ($custom_handler as $op_name => $cu_handler) {
                $operator = $this->operator_model->getOperatorId($op_name);
                $operator_id = (int) $operator[0]->id;

                $handler = $cu_handler['handler'];
                $status = (int) $active[$operator_name];

                $mechanism_id = $this->creator_model->insertMechanism($keyword, $operator_id, $service_id, $handler, $status);
            }
        }

        if (count($mechanism) > 0) {
            $handler = 'service_creator_handler';
            //var_dump($mechanism);
            foreach ($mechanism as $operator_name => $val) {
            #get operator id
                $operator = $this->operator_model->getOperatorId($operator_name);
                $operator_id = (int) $operator[0]->id;
                //var_dump($operator_name, $operator_id, $service_id, $keyword);

                $status = (int) $active[$operator_name];
                #insert mechanism
                $mechanism_id = $this->creator_model->insertMechanism($keyword, $operator_id, $service_id, $handler, $status);
                foreach ($val as $module) {
                #Get module ID based module name
                    $module_name = $module['module_name'];
                    $module_id = $this->creator_model->getModuleIdByName($module_name);
                    $module_id = (int) $module_id[0]->id;

                    #insert into reply table
                    $charging_id = $module['charging'];
                    $message = $module['message'];
                    $subject = " ";
                    //var_dump($mechanism_id, $module_id, $subject, $message, $charging_id, $module_name, $val);
                    $reply_id = $this->creator_model->insertReply($mechanism_id, $module_id, $subject, $message, $charging_id);

                    #insert into reply_attribute
                    $mandatory_attributes = $this->getAtrributeByModuleName($module_name);

                    foreach ($mandatory_attributes as $attribute_name => $attribute_id) {
                    #IF NOT Empty THEN insert to reply attributes
                        $value = $module[$attribute_name];
                        if (!empty($value))
                            $this->creator_model->insertReplyAttribute($attribute_id, $value, $reply_id);
                    }
                }
            }

            $sess_data = array('service_name' => '', 'adn' => '', 'operator' => '', 'service_id' => '');
            #quick hack : restore database to default
            $this->load->database('default', TRUE);
            $this->session->unset_userdata($sess_data);

            redirect('service/add_service/edit/' . $service_id);
        }else {
            redirect('service/add_service/edit/' . $service_id);
        }

        redirect('service/add_service/');
    }

    protected function getAtrributeByModuleName($module_name) {
        $aid = $this->config->item('attributes');
        //var_dump($attribute);
        $attributes = array();
        switch ($module_name) {
            case 'registration':
                $attributes = array('rereg_welcome' => $aid['rereg_welcome'], 'msg_isregistered' => $aid['msg_isregistered']);
                break;
            case 'unregistration':
                $attributes = array('msg_unreg_notregistered' => $aid['msg_unreg_notregistered']);
                break;
            case 'text':
                $attributes = array('pull_member' => $aid['pull_member'], 'msg_pull_notregistered' => $aid['msg_pull_notregistered'], 'rereg_content' => $aid['rereg_content']);
                break;
            case 'textdelay':
                $attributes = array('pull_member' => $aid['pull_member'], 'msg_pull_notregistered' => $aid['msg_pull_notregistered'], 'rereg_content' => $aid['rereg_content']);
                break;
            case 'waplink':
                $attributes = array('pull_member' => $aid['pull_member'], 'msg_pull_notregistered' => $aid['msg_pull_notregistered'], 'rereg_content' => $aid['rereg_content'], 'wapdownload_name' => $aid['wapdownload_name'], 'wapdownload_limit' => $aid['wapdownload_limit']);
                break;
            case 'wappush':
                $attributes = array('msg_pull_notregistered' => $aid['msg_pull_notregistered'], 'rereg_content' => $aid['rereg_content'], 'wapdownload_name' => $aid['wapdownload_name'], 'wapdownload_limit' => $aid['wapdownload_limit']);
                break;
        }

        return $attributes;
    }

    protected function add_keyword() {
        $service_name = $this->input->post('service_name');
        $adn = $this->input->post('adn');
        $operator = $this->input->post('operator');

        $sess_data = array('service_name' => $service_name, 'adn' => $adn, 'operator' => $operator);
        $this->session->set_userdata($sess_data);
        //var_dump($this->session->all_userdata()); exit;
        redirect('/service/add_service/keyword', 'refresh');
    }

    public function keyword() {

        $session = $this->session->all_userdata();
//var_dump($session);
        $jsFile = 'service/creator.js';
        $this->smarty->assign('jsFile', $jsFile);

        if ($session['pattern']) {
            $this->smarty->assign('pattern', $session['pattern']);
        }

        $this->smarty->assign('service_name', $session['service_name']);
        $this->smarty->assign('adn', $session['adn']);
        $this->smarty->assign('operator', $session['operator']);
        $this->smarty->assign('service_id', $session['service_id']);
        $this->smarty->display('service/keyword.tpl');
    }

    public function getModule($data = array()) {
        $counter = $this->input->post('counter');

        if (empty($data['operator']))
            $operator = $this->input->post('operator');
        else
            $operator = $data['operator'];

        if (empty($data['module_name']))
            $module = $this->input->post('module');
        else
            $module = $data['module_name'];

        if (empty($data['adn']))
            $adn = $this->input->post('adn');
        else
            $adn = $data['adn'];

        switch ($module) {
            case 'registration' :
                $module = $this->module_registration($operator, $adn, $counter, $data);
                break;
            case 'unregistration' :
                $module = $this->module_unregistration($operator, $adn, $counter, $data);
                break;
            case 'text' :
                $module = $this->module_text($operator, $adn, $counter, $data);
                break;
            case 'textdelay' :
                $module = $this->module_text_delay($operator, $adn, $counter, $data);
                break;
            case 'waplink' :
                $module = $this->module_waplink($operator, $adn, $counter, $data);
                break;
            case 'wappush' :
                $module = $this->module_wappush($operator, $adn, $counter, $data);
                break;
            default:
                break;
        }
        $return = array('status' => TRUE, 'data' => $module);
        if (count($data) == 0)
            echo json_encode($return);
        else
            return json_encode($return);
    }

    public function getModulePull($data = array()) {
        if (empty($data['operator']))
            $operator = $this->input->post('operator');
        else
            $operator = $data['operator'];

        if (empty($data['module_name']))
            $module = $this->input->post('module');
        else
            $module = $data['module_name'];

        if (empty($data['adn']))
            $adn = $this->input->post('adn');
        else
            $adn = $data['adn'];

        switch ($module) {
            case 'registration' :
                $module = $this->module_registration_pull($operator, $adn, $data);
                break;
            case 'unregistration' :
                $module = $this->module_unregistration_pull($operator, $adn, $data);
                break;
            case 'text' :
                $module = $this->module_text_pull($operator, $adn, $data);
                break;
            case 'textdelay' :
                $module = $this->module_text_delay_pull($operator, $adn, $data);
                break;
            case 'waplink' :
                $module = $this->module_waplink_pull($operator, $adn, $data);
                break;
            case 'wappush' :
                $module = $this->module_wappush_pull($operator, $adn, $data);
                break;
            default:
                break;
        }
        $return = array('status' => TRUE, 'data' => $module);
        if (count($data) == 0)
            echo json_encode($return);
        else
            return json_encode($return);
    }

    private function getCharging($module, $operator, $adn, $select = '') {
        $val_charging = $this->creator_model->getCharging($operator, $adn, $module);
        //var_dump($operator,$adn,$module,$val_charging); exit;
        $charging = '<option value="">Select Charging</option>';
        foreach ($val_charging as $v) {
            $charging .= '<option value="' . $v['id'] . '" ' . ($select == $v['id'] ? 'selected="selected"' : '') . '>' . $v['charging_id'] . ' | ' . $v['gross'] . '</option>';
        }
        //var_dump($select,$val_charging ,$module, $operator, $adn);
        return $charging;
    }

    public function getSelectModule($data = array()) {
        if (empty($data['operator']))
            $operator = $this->input->post('operator');
        else
            $operator = $data['operator'];

        if (empty($data['adn']))
            $adn = $this->input->post('adn');
        else
            $adn = $data['adn'];

        $id = uniqid();
        $counter = $this->input->post('counter');
        $content = '
<div id="module-area-' . $operator . '-' . $id . '" style="margin-top:20px">
Select Module : &nbsp;&nbsp;<select name="module[' . $operator . '][]" id="select-module-' . $operator . '-' . $id . '" onChange="javascript:getModule2(\'' . $operator . '\',\'' . $adn . '\',\'' . $id . '\',\'' . $counter . '\')">
<option value="">Select Module</option>
<option ' . ($data['module_name'] == 'registration' ? 'selected="selected" ' : '') . 'value="registration">Registration</option>
<option ' . ($data['module_name'] == 'unregistration' ? 'selected="selected" ' : '') . 'value="unregistration">UnRegistration</option>
<option ' . ($data['module_name'] == 'text' ? 'selected="selected" ' : '') . 'value="text">Text</option>
<option ' . ($data['module_name'] == 'textdelay' ? 'selected="selected" ' : '') . 'value="textdelay">Text Delay</option>
<option ' . ($data['module_name'] == 'waplink' ? 'selected="selected" ' : '') . 'value="waplink">Wap Link</option>
<option ' . ($data['module_name'] == 'wappush' ? 'selected="selected" ' : '') . 'value="wappush">Wap Push</option>
</select>
<input type="button" name="DeleteModule" value="Remove Module" onClick="javascript:removeModule(\'' . $operator . '\',\'' . $id . '\')"/>
<br /><br />
<div id="content-operator-' . $operator . '-' . $id . '"></div>
</div>
        ';
        $return = array('status' => TRUE, 'data' => $content);
        if (count($data) == 0)
            echo json_encode($return);
        else
            return json_encode($return);
        exit;
    }

    public function getSelectModuleForEdit($data = array()) {
        if (empty($data['operator']))
            $operator = $this->input->post('operator');
        else
            $operator = $data['operator'];

        if (empty($data['adn']))
            $adn = $this->input->post('adn');
        else
            $adn = $data['adn'];

        $id = uniqid();
        $counter = $this->input->post('counter');
        $content = '
<div id="module-area-' . $operator . '-' . $id . '" style="margin-top:20px">
Select Module : &nbsp;&nbsp;<select name="module[' . $operator . '][]" id="select-module-' . $operator . '-' . $id . '" onChange="javascript:getModule2(\'' . $operator . '\',\'' . $adn . '\',\'' . $id . '\',\'' . $counter . '\')">
<option value="">Select Module</option>
<option ' . ($data['module_name'] == 'registration' ? 'selected="selected" ' : '') . 'value="registration">Registration</option>
<option ' . ($data['module_name'] == 'unregistration' ? 'selected="selected" ' : '') . 'value="unregistration">UnRegistration</option>
<option ' . ($data['module_name'] == 'text' ? 'selected="selected" ' : '') . 'value="text">Text</option>
<option ' . ($data['module_name'] == 'textdelay' ? 'selected="selected" ' : '') . 'value="textdelay">Text Delay</option>
<option ' . ($data['module_name'] == 'waplink' ? 'selected="selected" ' : '') . 'value="waplink">Wap Link</option>
<option ' . ($data['module_name'] == 'wappush' ? 'selected="selected" ' : '') . 'value="wappush">Wap Push</option>
</select>
<input type="button" name="DeleteModule" value="Remove Module" onClick="javascript:removeModule(\'' . $operator . '\',\'' . $id . '\')"/>
<br /><br />
<div id="content-operator-' . $operator . '-' . $id . '"></div>

        ';
        $return = array('status' => TRUE, 'data' => $content);
        if (count($data) == 0)
            echo json_encode($return);
        else
            return json_encode($return);
        exit;
    }

    private function module_registration($operator, $adn, $counter, $data = array()) {
        $charging = $this->getCharging('registration', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Registration</legend>
        <input type="hidden" value="registration" name="mechanism[' . $operator . '][' . $counter . '][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][' . $counter . '][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Register Message</td>
        <td>
        <textarea name="mechanism[' . $operator . '][' . $counter . '][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][' . $counter . '][rereg_welcome]" value="1"' . ($data['rereg_welcome'] == '1' ? ' checked="true"' : '') . ' />Rereg Welcome
        </td>
        </tr>
        <tr>
        <td>Message Is Register</td>
        <td><textarea name="mechanism[' . $operator . '][' . $counter . '][msg_isregistered]" cols="50" rows="3">' . $data['msg_isregistered'] . '</textarea></td>
        </tr>
        </fieldset>
        </table>
        ';
        return $form;
    }

    private function module_registration_pull($operator, $adn, $data=array()) {
        $charging = $this->getCharging('registration', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Registration</legend>
        <input type="hidden" value="registration" name="mechanism[' . $operator . '][0][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][0][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Register Message</td>
        <td>
        <textarea name="mechanism[' . $operator . '][0][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][0][rereg_welcome]" value="1" ' . ($data['rereg_welcome'] == '1' ? 'checked="true"' : '') . ' />Rereg Welcome
        </td>
        </tr>
        <tr>
        <td>Message Is Register</td>
        <td><textarea name="mechanism[' . $operator . '][0][msg_isregistered]" cols="50" rows="3">' . $data['msg_isregistered'] . '</textarea></td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_unregistration_pull($operator, $adn, $data=array()) {
        $charging = $this->getCharging('unregistration', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>UnRegistration</legend>
        <input type="hidden" value="unregistration" name="mechanism[' . $operator . '][0][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][0][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Text Message</td>
        <td>
        <textarea name="mechanism[' . $operator . '][0][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td>Message Not Register</td>
        <td><textarea name="mechanism[' . $operator . '][0][msg_unreg_notregistered]" cols="50" rows="3">' . $data['msg_unreg_notregistered'] . '</textarea></td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_unregistration($operator, $adn, $counter = 1, $data = array()) {
        $charging = $this->getCharging('unregistration', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>UnRegistration</legend>
        <input type="hidden" value="unregistration" name="mechanism[' . $operator . '][' . $counter . '][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][' . $counter . '][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Text Message</td>
        <td>
        <textarea name="mechanism[' . $operator . '][' . $counter . '][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][' . $counter . '][pull_member]" value="1"' . ($data['pull_member'] == '1' ? 'checked="true"' : '') . ' />Pull Member
        </td>
        </tr>
        <tr>
        <td>Message Not Register</td>
        <td><textarea name="mechanism[' . $operator . '][' . $counter . '][msg_unreg_notregistered]" cols="50" rows="3">' . $data['msg_unreg_notregistered'] . '</textarea></td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_text($operator, $adn, $counter = 1, $data = array()) {
        $charging = $this->getCharging('text', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Text</legend>
        <input type="hidden" value="text" name="mechanism[' . $operator . '][' . $counter . '][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][' . $counter . '][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Message</td>
        <td>
        <textarea name="mechanism[' . $operator . '][' . $counter . '][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][' . $counter . '][rereg_content]" value="1"' . ($data['rereg_content'] == '1' ? 'checked="true"' : '') . ' />Rereg Content
        </td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_text_pull($operator, $adn, $data=array()) {
        $charging = $this->getCharging('text', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Text</legend>
        <input type="hidden" value="text" name="mechanism[' . $operator . '][0][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][0][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Message</td>
        <td>
        <textarea name="mechanism[' . $operator . '][0][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][0][pull_member]" value="1"' . ($data['pull_member'] == '1' ? 'checked="true"' : '') . ' />Pull Member
        </td>
        </tr>
        <tr>
        <td>Message Pull Not Register</td>
        <td><textarea name="mechanism[' . $operator . '][0][msg_pull_notregistered]" cols="50" rows="3">' . $data['msg_pull_notregistered'] . '</textarea></td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_text_delay($operator, $adn, $counter = 1, $data = array()) {
        $charging = $this->getCharging('textdelay', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Text</legend>
        <input type="hidden" value="textdelay" name="mechanism[' . $operator . '][' . $counter . '][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][' . $counter . '][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Message</td>
        <td>
        <textarea name="mechanism[' . $operator . '][' . $counter . '][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][' . $counter . '][rereg_content]" value="1"' . ($data['rereg_content'] == '1' ? 'checked="true"' : '') . ' />Rereg Content
        </td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_text_delay_pull($operator, $adn, $data = array()) {
        $charging = $this->getCharging('textdelay', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Text Delay</legend>
        <input type="hidden" value="textdelay" name="mechanism[' . $operator . '][0][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][0][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Message</td>
        <td>
        <textarea name="mechanism[' . $operator . '][0][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][0][pull_member]" value="1"' . ($data['pull_member'] == '1' ? 'checked="true"' : '') . ' />Pull Member
        </td>
        </tr>
        <tr>
        <td>Message Pull Not Register</td>
        <td><textarea name="mechanism[' . $operator . '][0][msg_pull_notregistered]" cols="50" rows="3">' . $data['msg_pull_notregistered'] . '</textarea></td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_waplink($operator, $adn, $counter = 1, $data = array()) {
        $charging = $this->getCharging('waplink', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Waplink</legend>
        <input type="hidden" value="waplink" name="mechanism[' . $operator . '][' . $counter . '][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][' . $counter . '][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Message (@URL@)</td>
        <td>
        <textarea name="mechanism[' . $operator . '][' . $counter . '][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][' . $counter . '][rereg_content]" value="1"' . ($data['rereg_content'] == '1' ? 'checked="true"' : '') . ' />Rereg Content
        </td>
        </tr>
        <tr>
        <td>Wap Name</td>
        <td>
            <input type="text" name="mechanism[' . $operator . '][' . $counter . '][wapdownload_name]" value="' . $data['wapdownload_name'] . '" />
        </td>
        </tr>
        <tr>
        <td>Download Limit</td>
        <td>
            <input type="text" name="mechanism[' . $operator . '][' . $counter . '][wapdownload_limit]" value="' . $data['wapdownload_limit'] . '" size="5" />
        </td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_waplink_pull($operator, $adn, $data = array()) {
        $charging = $this->getCharging('waplink', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Waplink</legend>
        <input type="hidden" value="waplink" name="mechanism[' . $operator . '][0][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][0][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Message (@URL@)</td>
        <td>
        <textarea name="mechanism[' . $operator . '][0][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][0][pull_member]" value="1"' . ($data['pull_member'] == '1' ? 'checked="true"' : '') . ' />Pull Member
        </td>
        </tr>
        <tr>
        <td>Message Pull Not Register</td>
        <td><textarea name="mechanism[' . $operator . '][0][msg_pull_notregistered]" cols="50" rows="3">' . $data['msg_pull_notregistered'] . '</textarea></td>
        </tr>
        <tr>
        <td>Wap Name</td>
        <td>
            <input type="text" name="mechanism[' . $operator . '][' . $counter . '][wapdownload_name]" value="' . $data['wapdownload_name'] . '" />
        </td>
        </tr>
        <tr>
        <td>Download Limit</td>
        <td>
            <input type="text" name="mechanism[' . $operator . '][' . $counter . '][wapdownload_limit]" value="' . $data['wapdownload_limit'] . '" size="5" />
        </td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_wappush($operator, $adn, $counter = 1, $data = array()) {
        $charging = $this->getCharging('wappush', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Wappush</legend>
        <input type="hidden" value="waplink" name="mechanism[' . $operator . '][' . $counter . '][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][' . $counter . '][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Message (@URL@)</td>
        <td>
        <textarea name="mechanism[' . $operator . '][' . $counter . '][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][' . $counter . '][rereg_content]" value="1"' . ($data['rereg_content'] == '1' ? 'checked="true"' : '') . ' />Rereg Content
        </td>
        </tr>
        <tr>
        <td>Wap Name</td>
        <td>
            <input type="text" name="mechanism[' . $operator . '][' . $counter . '][wapdownload_name]" value="' . $data['wapdownload_name'] . '" />
        </td>
        </tr>
        <tr>
        <td>Download Limit</td>
        <td>
            <input type="text" name="mechanism[' . $operator . '][' . $counter . '][wapdownload_limit]" value="' . $data['wapdownload_limit'] . '" size="5" />
        </td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    private function module_wappush_pull($operator, $adn, $data = array()) {
        $charging = $this->getCharging('wappush', $operator, $adn, $data['charging_id']);
        $form = '
        <fieldset>
        <legend>Wappush</legend>
        <input type="hidden" value="wappush" name="mechanism[' . $operator . '][0][module_name]" />
        <table width="500px" border="0" cellpadding="3" cellspacing="1">
        <tr>
        <td>Charging</td>
        <td>
            <select name="mechanism[' . $operator . '][0][charging]">' . $charging . '</select>
        </td>
        </tr>
        <tr>
        <td>Message (@URL@)</td>
        <td>
        <textarea name="mechanism[' . $operator . '][0][message]">' . $data['message'] . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="checkbox" name="mechanism[' . $operator . '][0][pull_member]" value="1"' . ($data['pull_member'] == '1' ? 'checked="true"' : '') . ' />Pull Member
        </td>
        </tr>
        <tr>
        <td>Message Pull Not Register</td>
        <td><textarea name="mechanism[' . $operator . '][0][msg_pull_notregistered]" cols="50" rows="3">' . $data['msg_pull_notregistered'] . '</textarea></td>
        </tr>
        <tr>
        <td>Wap Name</td>
        <td>
            <input type="text" name="mechanism[' . $operator . '][' . $counter . '][wapdownload_name]" value="' . $data['wapdownload_name'] . '" />
        </td>
        </tr>
        <tr>
        <td>Download Limit</td>
        <td>
            <input type="text" name="mechanism[' . $operator . '][' . $counter . '][wapdownload_limit]" value="' . $data['wapdownload_limit'] . '" size="5" />
        </td>
        </tr>
        </table>
        </fieldset>
        ';
        return $form;
    }

    public function ajaxGetCreatorList() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $search = strtoupper($this->input->post("search"));
        $serviceid = (int) $this->input->post('serviceid');
        $operatorid = (int) $this->input->post('operatorid');
        $page = $this->uri->segment(4);
        $offset = (isset($page)) ? (int) $page : 0;
        $limit = (int) $this->input->post("limit");
        $paging = "";
        $result = "";

        $mData = $this->creator_model->getCreatorList($offset, $limit, $search, $serviceid, $operatorid);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $pattern = $dt['pattern'];
                $handler = $dt['handler'];
                $operator = $dt['operator_name'];
                $service = $dt['service_name'];
                $date_created = $dt['date_created'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";

                $result .= "<td>$pattern</td>";
                $result .= "<td>$handler</td>";
                $result .= "<td>$operator</td>";
                $result .= "<td>$service</td>";
                $result .= "<td>$date_created</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editCreator($id);\">Edit</a> </td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "service/creator/ajaxGetCreatorList/";
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
            $result .= "<tr><td colspan=\"5\">No data found</td></tr>";
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

    public function ajaxAddNewCreator() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $pattern = $this->input->post("txt-pattern");
        $operator_id = $this->input->post("txt-operatorId");
        $service_id = $this->input->post("txt-serviceId");

        $response = array();

        //validate
        if (empty($pattern)) {
            $status_pattern = FALSE;
            $msg_pattern = "Name Field is Required";
        } else {
            $status_pattern = TRUE;
            $msg_pattern = "";
        }

        $data['pattern'] = $pattern;
        $data['operator_id'] = $operator_id;
        $data['service_id'] = $service_id;

        if (!empty($pattern) && !empty($operator_id) && !empty($service_id)) {
            if ($this->creator_model->check_creator_name($data)) {
                $response = array(
                    'status_pattern' => FALSE,
                    'msg_pattern' => "Service Name and Adn already exist, please try another combination",
                    'status' => FALSE,
                    'message' => 'Service Name and Adn already exist, please try another combination'
                );
            } else {
                $response = $this->creator_model->addNewCreator($data);
                $mData = $this->creator_model->selectIdCreator();
                $data = $mData['result']['data'];

                foreach ($data as $key => $dt) {
                    $id = $dt['id'];
                }

                $response['id'] = $id;
            }
        } else {
            $response = array('status_pattern' => $status_pattern,
                'msg_pattern' => $msg_pattern,
                'status_adn' => $status_adn,
                'msg_adn' => $msg_adn,
                'status' => FALSE,
                'message' => 'error'
            );
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateCreator($id) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $pattern = $this->input->post("txt-pattern");
        $operator_id = $this->input->post("txt-operatorId");
        $service_id = $this->input->post("txt-serviceId");
        $pattern_compare = $this->input->post("pattern_compare");
        $operator_id_compare = $this->input->post("operatorId_compare");
        $service_id_compare = $this->input->post("serviceId_compare");

        $response = array();

        if ($pattern == $pattern_compare && $operator_id == $operator_id_compare && $service_id == $service_id_compare) {
            $response = array('status' => TRUE, 'message' => '', 'id' => $id);
            echo json_encode($response);
            exit;
        }

        //validate
        if (empty($pattern)) {
            $status_pattern = FALSE;
            $msg_pattern = "Name Field is Required";
        } else {
            $status_pattern = TRUE;
            $msg_pattern = "";
        }

        $data['id'] = $id;
        $data['pattern'] = $pattern;
        $data['operator_id'] = $operator_id;
        $data['service_id'] = $service_id;

        if (!empty($pattern) && !empty($operator_id) && !empty($service_id)) {
            if ($this->creator_model->check_creator_name($data)) {
                $response = array(
                    'status_pattern' => FALSE,
                    'msg_pattern' => "Service Name and Adn already exist, please try another combination",
                    'status' => FALSE,
                    'message' => 'Service Name and Adn already exist, please try another combination'
                );
            } else {
                $response = $this->creator_model->updateCreator($data);
            }
        } else {
            $response = array('status_pattern' => $status_pattern,
                'msg_pattern' => $msg_pattern,
                'status_adn' => $status_adn,
                'msg_adn' => $msg_adn,
                'status' => FALSE,
                'message' => 'error'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function ajaxEditCreator() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $result = $this->creator_model->editCreator($id);

        $response = array(
            'pattern' => $result[0]['pattern'],
            'operator_id' => $result[0]['operator_id'],
            'service_id' => $result[0]['service_id']
        );

        echo json_encode($response);
        exit;
    }

    public function getOperatorList() {
        $query = $this->creator_model->getOperatorList();

        $data = array();
        if ($query > 0) {
            foreach ($query as $dt) {
                $data[$dt['id']]['id'] = $dt['id'];
                $data[$dt['id']]['name'] = strtolower($dt['name']);
            //$data[$dt['id']]['name'] = ucwords(strtolower($dt['name']));
            }
        }

        return $data;
    }

    public function getServiceList() {
        $query = $this->creator_model->getServiceList();

        $data = array();
        if ($query > 0) {
            foreach ($query as $dt) {
                $data[$dt['id']]['id'] = $dt['id'];
                $data[$dt['id']]['name'] = ucwords(strtolower($dt['name']));
            }
        }

        return $data;
    }

    public function getAdnList() {
        $query = $this->creator_model->getAdnList();

        $data = array();
        if ($query > 0) {
            foreach ($query as $dt) {
                $data[$dt['id']]['id'] = $dt['id'];
                $data[$dt['id']]['name'] = ucwords(strtolower($dt['name']));
            }
        }

        return $data;
    }

    public function ajaxGetOperatorAvailable() {
        $pattern = urldecode($this->uri->segment(4));
        $service_id = (int)$this->uri->segment(5);
        $service_name = $this->uri->segment(6);
        $adn = $this->uri->segment(7);

        $query = $this->creator_model->getListKeywordsByPattern($pattern, $service_id);

        if (count($query) > 0) {
            foreach ($query as $operat) {
                $operator[] = $operat['operator_name'];
            }
        }
        $op = $this->getOperatorList();
        foreach ($op as $v) {
            $operator_list[] = $v['name'];
        }
        $available_operator = array_diff($operator_list, $operator);

        $data = "";
        if (count($available_operator) > 0) {
            $data .= '<input type="hidden" name="pattern" value="' . $pattern . '" />';
            $data .= '<input type="hidden" name="service_id" value="' . $service_id . '" />';
            $data .= '<input type="hidden" name="service_name" value="' . $service_name . '" />';
            $data .= '<input type="hidden" name="adn" value="' . $adn . '" />';

            foreach ($available_operator as $dt) {
                $data .= '<input type="checkbox" name="operator[]" value="' . $dt . '" class="text ui-widget-content ui-corner-all" />';
                $data .= '<label for="email">' . $dt . '</label><br />';
            }
        } else {
            $data .= "Operator not available";
        }

        echo $data;
    }

    public function ajaxGetOperator() {
        $service_id = urldecode($this->uri->segment(4));
        $service_name = urldecode($this->uri->segment(5));
        $adn = $this->uri->segment(6);
        $query = $this->getOperatorList();

        $data = "";
        if (count($query) > 0) {
            $data .= '<input type="hidden" value="'.$service_name.'" name="service_name" />';
            $data .= '<input type="hidden" value="'.$service_id.'" name="service_id" />';
            $data .= '<input type="hidden" value="'.$adn.'" name="adn" />';

            foreach ($query as $dt) {
                $data .= '<input type="checkbox" name="operator[]" value="' . $dt['name'] . '" class="text ui-widget-content ui-corner-all" />';
                $data .= '<label for="email">' . $dt['name'] . '</label><br />';
            }
        } else {
            $data .= "Operator not available";
        }

        echo $data;
    }

    public function deleteKeyword() {
        $service_id = $this->uri->segment(4);
        $id = $this->uri->segment(5);
        $query = $this->creator_model->delKeywordById($id);
        redirect('/service/add_service/edit/' . $service_id, 'refresh');
    }

    public function delKeywordByPattern() {
        $service_id = $this->uri->segment(4);
        $pattern = urldecode($this->uri->segment(5));
        $query = $this->creator_model->delKeywordByPattern($pattern,$service_id );
        redirect('/service/add_service/edit/' . $service_id, 'refresh');
    }

    public function getCustomHandler() {
        $operator = strtolower($this->input->post('operator'));
        $service_id = $this->input->post('service_id');
        $service_name = $this->input->post('service_name');

        $handler = $this->creator_model->getCustomHandler();
        $html = 'Pilih Handler : <select name="custom[' . $operator . '][handler]" id="select-custom-handler-' . $operator . '">';
        foreach ($handler as $v) {
            $html .= '<option value="' . $operator . '_' . $v['handler'] . '">' . $v['name'] . '</option>';
        }
        $html .= '</select> <input type="button" name="pilih" value="pilih" onClick="javascript:selectIniFile(\'' . $operator . '\',\'' . $service_name . '\')" />';
        $html .= '<br /><div id="custom-' . $operator . '-inifile"></div>';
        $response = array('status' => TRUE, 'data' => $html);
        echo json_encode($response);
    }

    public function readIniFile() {
        $service_name = $this->input->post('service_name');
        $operator = $this->input->post('operator');
        $handler_name = $this->input->post('handler_name');

        $xmp_path = $this->config->item('xmp_path');
        $operator_path = $this->config->item('operator_path');
        $op_xmp_version = $this->config->item('operator_xmp_version');

        $default_inifile = $xmp_path . "/default/service/reply/" . $service_name . ".ini";
        $operator_inifile = $operator_path . "/" . $operator . "/" . $op_xmp_version . "/" . $operator . "/service/reply/" . $service_name . ".ini";

        if (is_readable($operator_inifile)) {
            $ini_path = $operator_inifile;
        } elseif (is_readable($default_inifile)) {
            $ini_path = $default_inifile;
        } else {
            echo json_encode(array('status' => FALSE, 'data' => "File $operator_inifile Not Found"));
            exit;
        }


        if (file_exists($ini_path)) {
            $read_reply = @parse_ini_file($ini_path);
            $read_percat = @parse_ini_file($ini_path, true);
            $rows_reply = array_keys($read_reply);

            if (count($read_percat) > 0) {
                /*
                  $response .='
                  <div id="dialog-form" title="Create new user">
                  <p class="validateTips">All form fields are required.</p>

                  <form>
                  <fieldset>
                  <label for="name">Name</label>
                  <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
                  <label for="email">Email</label>
                  <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
                  <label for="password">Password</label>
                  <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
                  </fieldset>
                  </form>
                  </div>
                  ';
                 */
                $response .= '<table border="0" width="100%" cellpadding="3" cellspacing="1">';
                $response .= '<tr style="background-color:grey;color:white;font-size:bold"><td>Key</td><td>Value</td><td>Price</td><td>Action</td></tr>';

                foreach ($rows_reply as $item) {
                    $message = $read_percat['REPLY'][$item];
                    $len_reply = strlen($message);

                    if (strpos($item, $handler_name) !== FALSE) {
                        $response .= '<tr>';
                        $response .= '<td>' . $item . '</td>';
                        $response .= '<td>' . $message . '</td>';
                        $response .= '<td>' . $read_reply[$item] . '</td>';
                        $response .= '<td><a href="javascript:void(0)" id="' . $item . '" onClick="showEditDialog(\'' . $item . '\',\'' . urlencode($message) . '\',\'' . $read_reply[$item] . '\',\'' . $service_name . '\',\'' . $operator . '\')">edit</a></td>';
                        $response .= '</tr>';
                    }
                //$len_reply = strlen($key);
                //$reply_display[] = array('function' => $item, 'value' => $key, 'length' => $len_reply, 'function_encode' => base64_encode($item));
                //    break;
                }
                $response .= '</table>';
                echo json_encode(array('status' => TRUE, 'data' => $response));
            } else {
                echo json_encode(array('status' => FALSE, 'data' => "Not Valid INI Format"));
                exit;
            }
        } else {
            echo "Sempak $ini_path";
        }
    }

    public function getIniFormEdit() {
        $key = $this->input->post('key');
        $price = $this->input->post('price');
        $value = urldecode($this->input->post('value'));
        $service = $this->input->post('service');
        $operator = $this->input->post('operator');

        $html = 'Edit ' . $key . '<br />';
        $html .= '<input type="hidden" value="' . $key . '" name="inikey" id="inikey" />';
        $html .= '<input type="hidden" value="' . $service . '" name="service_name" id="ini_service_name" />';
        $html .= '<input type="hidden" value="' . $operator . '" name="operator" id="ini_operator" />';
        $html .= '<table border="0" width="100" cellpadding="3" cellspacing="1">';
        $html .= '<tr><td>Message</td><td> <textarea name="inivalue" cols="40" rows="3">' . $value . '</textarea></td></tr>';
        $html .= '<tr><td>Price</td><td><input type="text" name="iniprice" value="' . $price . '" size="8" /></td></tr>';
        $html .= '</table>';
        $response = array('status' => TRUE, 'data' => $html);
        echo json_encode($response);
    }

    public function editIniFile() {

        $service_name = $this->input->post('service_name');
        $operator = $this->input->post('operator');
        $post_message = $this->input->post('inivalue');
        $post_price = $this->input->post('iniprice');
        $function = $this->input->post('inikey');

        $xmp_path = $this->config->item('xmp_path');
        $operator_path = $this->config->item('operator_path');
        $op_xmp_version = $this->config->item('operator_xmp_version');

        $default_inifile = $xmp_path . "/default/service/reply/" . $service_name . ".ini";
        $operator_inifile = $operator_path . "/" . $operator . "/" . $op_xmp_version . "/" . $operator . "/service/reply/" . $service_name . ".ini";

        if (is_readable($operator_inifile)) {
            $ini_path = $operator_inifile;
        } elseif (is_readable($default_inifile)) {
            $ini_path = $default_inifile;
        } else {
            echo json_encode(array('status' => FALSE, 'data' => "File $operator_inifile Not Found"));
            exit;
        }


        $iniValue = parse_ini_file($ini_path, true);
        $iniValue['REPLY'][$function] = $post_message;
        $iniValue['CHARGING'][$function] = $post_price;

        $stringSave = '';

        foreach ($iniValue as $firstTree => $secondValue) {
            $stringSave .= '[' . $firstTree . ']' . "\n";

            foreach ($secondValue as $secondTree => $thirdValue) {
                $stringSave .= $secondTree . ' = "' . $thirdValue . '"' . "\n";
            }
        }

        @file_put_contents($ini_path, $stringSave);
        echo "OK";
    }

    public function change_name() {
        $adn = $this->input->post('adn');
        $service_name = $this->input->post('service_name');
        $service_id = $this->input->post('service_id');

        $this->service_model->updateService($service_name, $adn, $service_id);
        redirect(base_url() . 'service/add_service/edit/'.$service_id);
    }

    public function submit_keyword_edit() {

        $active = $this->input->post('active');
        $keyword = $this->input->post('keyword');
        $mechanism = $this->input->post('mechanism');
        $service_id = $this->input->post('service_id');
        $custom_handler = $this->input->post('custom');
        $mechanism_id = $this->input->post('mechanism_id');

        #remove all reply and atributes by mechanism id
        //
        $success_delete = $this->creator_model->removeReplyAndAttributes($mechanism_id);

        if($success_delete) {
            if (count($custom_handler) > 0) {
                foreach ($custom_handler as $op_name => $cu_handler) {
                    $operator = $this->operator_model->getOperatorId($op_name);
                    $operator_id = (int) $operator[0]->id;

                    $handler = $cu_handler['handler'];
                    $status = (int) $active[$operator_name];

                    $this->creator_model->updateMechanism($keyword, $operator_id, $service_id, $handler, $status, $mechanism_id);
                }
            }

            if (count($mechanism) > 0) {
                $handler = 'service_creator_handler';
                //var_dump($mechanism);
                foreach ($mechanism as $operator_name => $val) {
                #get operator id
                    $operator = $this->operator_model->getOperatorId($operator_name);
                    $operator_id = (int) $operator[0]->id;
                    //var_dump($operator_name, $operator_id, $service_id, $keyword);

                    $status = (int) $active[$operator_name];
                    #insert mechanism
                    $this->creator_model->updateMechanism($keyword, $operator_id, $service_id, $handler, $status, $mechanism_id);
                    foreach ($val as $module) {
                    #Get module ID based module name
                        $module_name = $module['module_name'];
                        $module_id = $this->creator_model->getModuleIdByName($module_name);
                        $module_id = (int) $module_id[0]->id;

                        #insert into reply table
                        $charging_id = $module['charging'];
                        $message = $module['message'];
                        $subject = " ";
                        //var_dump($mechanism_id, $module_id, $subject, $message, $charging_id, $module_name, $val);
                        $reply_id = $this->creator_model->insertReply($mechanism_id, $module_id, $subject, $message, $charging_id);

                        #insert into reply_attribute
                        $mandatory_attributes = $this->getAtrributeByModuleName($module_name);

                        foreach ($mandatory_attributes as $attribute_name => $attribute_id) {
                        #IF NOT Empty THEN insert to reply attributes
                            $value = $module[$attribute_name];
                            if (!empty($value))
                                $this->creator_model->insertReplyAttribute($attribute_id, $value, $reply_id);
                        }
                    }
                }

                $sess_data = array('service_name' => '', 'adn' => '', 'operator' => '', 'service_id' => '');
                #quick hack : restore database to default
                $this->load->database('default', TRUE);
                $this->session->unset_userdata($sess_data);

            //redirect('service/add_service/edit_keyword/15/claro/352';);
            }
            redirect('service/add_service/edit_keyword/'.$service_id.'/claro/'.$mechanism_id.'/success');

        }else {
        // FAILED DELETE
            redirect('service/add_service/edit_keyword/'.$service_id.'/claro/'.$mechanism_id.'/delete');
        }
    }

}