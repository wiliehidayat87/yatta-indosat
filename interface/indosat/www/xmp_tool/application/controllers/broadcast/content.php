<?php

/*
 * 
 *  Broadcast tool for XMP
 *  Broadcast content management
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate: 2012-11-19 17:49:59 +0700 (Mon, 19 Nov 2012) $
 *  Last updated by   $Author: erad $
 *  Last revision     $LastChangedRevision: 2990 $
 * 
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Content extends MY_Controller {
    

    public function __construct() {
        parent::__construct();

        $this->load->model('broadcast/broadcast_model');
        $this->load->library('Link_auth');
        $this->load->helper('broadcast_helper');
        
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
    }

    public function index() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        
        /*if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }*/
        
        //var_dump($_GET, $_POST, $_REQUEST); exit;
        
        $this->smarty->assign('page',   $_GET['id']);
        $this->smarty->assign('pageTitle', "Broadcast Content Management");
        
        if ($_GET) $param = $_GET;
        if ($_POST) $param = $_POST;
        $param['rec'] = ($param['rec']) ? $param['rec'] : 50;
                
        //$this->smarty->assign('addons_available', $addons["available"]);
        $serviceList = getServiceList();
        $this->smarty->assign('svc_ids',   $serviceList['id']);
        $this->smarty->assign('svc_names', $serviceList['name']);
        $this->smarty->assign('svc_id',    $param['service']);
        $this->smarty->assign('content',   $param['content']);

        $content = $this->getContentList($param);
        $contentList = $content["result"]["data"];
        $content['pg'] = $param['pg'];
        $content['rec'] = $param['rec'];
        $this->showNavigation($content);
        
        $this->smarty->assign('contents', $contentList);
        $this->smarty->assign('rec',  $param['rec']);

        $this->smarty->display('broadcast/content.tpl');
        
    }
    
    public function showNavigation($param) {
        $total_page = ceil($param['total'] / $param['rec']);
        if ($total_page<=1) {
            $pageit .= '<b>[1] </b>';
        } else {
            $pageit = "";
            for ($i=1; $i<=$total_page; $i++) {
                if ($param['pg']!=$i) $pageit .= '<a href="'.base_url().'broadcast/content?pg='.$i.'&rec='.$param['rec'].'"><b>'.$i.'</b></a> ';
                else $pageit .= '<b>['.$i.'] </b>';
            }
        }
    
        $nav = '<table cellpadding="0" cellspacing="0">
				<tbody><tr>
				<td valign="bottom"><table cellpadding="0" cellspacing="0"><tbody><tr><td>Page :  '.$pageit.' </td></tr></tbody></table></td>
				<td width="10"></td>
				</tr>
			</tbody></table>';
		$this->smarty->assign('page_navigation', $nav);

    }

    public function delete() {
        if ($_POST['dopost']=="md") {
            if ($_POST['cSel']) {
                $result = $this->broadcast_model->deleteContent($_POST['cSel']);
            }
        }
        redirect('broadcast/content');
    }
    
    public function edit() {
        
        if ($_GET) $param = $_GET;
        if ($_POST) $param = $_POST;
        
        $serviceList  = getServiceList();
        
        $contentList = $this->getContentList($param);
        list ($datepublish_date, $datepublish_hour) = explode (" ", $contentList['datepublish'], 2);
        
        $this->smarty->assign('svc_ids',         $serviceList['id']);
        $this->smarty->assign('svc_names',       $serviceList['name']);
        $this->smarty->assign('svc_id',          $contentList['service']);
        $this->smarty->assign('content_label',   $contentList['content_label']);
        $this->smarty->assign('content',         $contentList['content']);
        $this->smarty->assign('author',          $contentList['author']);
        $this->smarty->assign('notes',           $contentList['notes']);
        $this->smarty->assign('datepublish_date',$datepublish_date);
        $this->smarty->assign('datepublish_hour',$datepublish_hour);
        $this->smarty->assign('lastused',        $contentList['lastused']);
        $this->smarty->assign('modified',        $contentList['modified']);
        $this->smarty->assign('created',         $contentList['created']);
        $this->smarty->assign('content_id',      $contentList['id']);
        
        $this->smarty->assign('action',          'editSave');
        $this->smarty->assign('pageTitle',       "Edit Push Content");
        $this->smarty->display('broadcast/content_edit.tpl');
    }
    
    public function editSave() {
        if ($_GET) $param = $_GET;
        if ($_POST) $param = $_POST;
        
        $param["datepublish"] = sprintf ("%s %s", $param['datepublish_date'], $param['datepublish_hour']);
        
        $result = $this->broadcast_model->editContent($param);

        //$this->index();
        redirect('broadcast/content');
        
    }
    
    public function insert() {
        $serviceList  = getServiceList();

        $this->smarty->assign('svc_ids',         $serviceList['id']);
        $this->smarty->assign('svc_names',       $serviceList['name']);
        $this->smarty->assign('action',          'insertSave');
        $this->smarty->assign('datepublish_date',date("Y-m-d"));
        $this->smarty->assign('datepublish_hour',date("H:i:s"));
        $this->smarty->assign('pageTitle',       "New Push Content");
       
        $this->smarty->display('broadcast/content_edit.tpl');
        
    }
    
    public function insertSave() {
        if ($_GET) $param = $_GET;
        if ($_POST) $param = $_POST;
        
        $param["datepublish"] = sprintf ("%s %s", $param['datepublish_date'], $param['datepublish_hour']);
        
        $result = $this->broadcast_model->addNewContent($param);

        //$this->index();
        redirect('broadcast/content');
        
    }
    
    public function getContentList($param) {

        $dataArr = $this->broadcast_model->getContent($param);
        
        if (!$param["id"]) {
            $contentList = $dataArr;
        } else {
            $contentList = $dataArr["result"]["data"][0];
        }
        
        return $contentList;
    }

    public function import() {
        $this->smarty->assign('pageTitle',       "Import Push Content");
        
        $this->smarty->display('broadcast/content_import.tpl');
    }
    
    public function import_progress() {
print <<<HTML
<html>
    <head>
        <title>Push Messages Result</title>
        <link rel="STYLESHEET" type="text/css" href="../lib/others/style.css">
    </head>
<body>
In Progress ....<br><br><br>
<span class=textInfo>Be patient please, import content message is in progress<br>If you close this window, then the process will be aborted.</span>
</body>
</html>                
HTML;
exit;
    }
    
    public function import_process() {
        set_time_limit (0);
        
        $fileUploaded = array ();
        $numUploaded  = 0;
        $numValid	  = 0;
        $_listfile	  = '';

        foreach ($_FILES['contentfile']['tmp_name'] as $k=>$tmpFile) {
            if (is_uploaded_file($tmpFile)) {
                $_ctype    = getMimeType($tmpFile);
                if ($_ctype === FALSE) $_ctype = $_FILES['contentfile']['type'][$k];

                $validCSV  = strpos($_ctype, 'text/') !== FALSE;
                $name = $_FILES['contentfile']['name'][$k];
                if ($validCSV) {
                    $numValid++;
                }
                $_listfile .=  "$name - ".(($validCSV)?'<b>OK</b>':'FAILED')." <br>";
                $fileUploaded[$name] = array (
                    'status' 	=> $validCSV,
                    'tmpname'	=> $tmpFile,
                    'type'	=> $_FILES['contentfile']['type'][$k],
                );
            }
        }

        $numUploaded = count($fileUploaded);
        $urlref  = $_POST['urlref'];
        $listfile = "Found : <b>$numUploaded</b> files, Valid File : <b>$numValid</b><br>$_listfile";
        $content = "
        <html>
            <head>
                <title>Import Content Result</title>
                <link rel=\"STYLESHEET\" type=\"text/css\" href=\"../lib/others/style.css\">
            </head>
        <body>
        <table width=100% border=0 height=100%>
        <tr><td align=center valign=middle>Contents is now processed. You can safely close this window.</td></tr>
        <tr><td align=center valign=middle>$listfile</td></tr>
        <tr><td align=center valign=bottom class=textErr>Caution !! Do not reload<br>
        <a href='javascript:window.close()'><b>CLOSE</b></a></td></tr>
        </table>
        <script>
        var win = window.opener;
        //alert(win)
        win.document.location.href='$urlref';
        </script>
        </body>
        </html>";                

        if (strlen($content) < 256) {
            $content = str_pad($content, 256); // IE hack
        }
        header("HTTP/1.1 200 OK");
        header("Content-Length: ".strlen($content));
        echo $content;
        flush();	

        if ($_POST['dopost']=='IMPORTCONTENTFILE') {
            foreach ($fileUploaded as $nmFile => $aInfo) {
                if (is_uploaded_file($aInfo['tmpname']) && $aInfo['status'] === TRUE) {
                    $this->export2db ($aInfo['tmpname']);
                }
            }
        }
    }

    
    public function export2db ($file) {
        $handle = fopen($file, "r");
        $SERVICE        = 0;
        $LABEL          = 1;
        $CONTENT        = 2;
        $PUBLISHDATE    = 3;
        $AUTHOR         = 4;
        $idx = 1;
        $noFound		= FALSE;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($idx==1) {
                if (count($data) != 5) {
                    $noFound	= TRUE;
                    break;
                }			
                foreach ($data as $_k=>$_v) { ${strtoupper($_v)} = $_k; }
                $idx++;
                continue;
            }
            if (empty($data[0])) continue;
            $content = $data[$CONTENT];
            if (strpos($content,"'")!==FALSE) $content = addslashes($content); 

                $result = $this->broadcast_model->isContentExist ($data[$SERVICE], $data[$LABEL], $data[$PUBLISHDATE]);
                $ExistContent = $result["result"]["data"]["0"]["id"];
                if ($ExistsContent !== FALSE && is_numeric($ExistContent)) {
                    $sql_query = sprintf("UPDATE push_content SET  content='%s', author='%s' WHERE id=%d",
                     asAscii($content),  $data[$AUTHOR], $ExistContent
                    );
                } else {
                    $sql_query = sprintf("INSERT INTO push_content SET  service='%s', content_label='%s', content='%s', datepublish='%s', author='%s'",
                    $data[$SERVICE], $data[$LABEL], asAscii($content), $data[$PUBLISHDATE], $data[$AUTHOR]
                    );
                }
                $this->broadcast_model->runQuery($sql_query);
            }

            if ($noFound) {
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    if ($idx==1) {
                        foreach ($data as $_k=>$_v) { ${strtoupper($_v)} = $_k; }
                        $idx++;
                        continue;
                    }
                    if (empty($data[0])) continue;
                    $content = $data[$CONTENT];
                    if (strpos($content,"'")!==FALSE) $content = addslashes($content);

                    $result = $this->broadcast_model->isContentExist ($data[$SERVICE], $data[$LABEL], $data[$PUBLISHDATE]);
                    $ExistContent = $result["result"]["data"]["0"]["id"];
                    if ($ExistsContent !== FALSE && is_numeric($ExistContent)) {
                        $sql_query = sprintf("UPDATE push_content SET  content='%s', author='%s' WHERE id=%d",
                        $content,  $data[$AUTHOR], $ExistContent
                        );
                    } else {
                        $sql_query = sprintf("INSERT INTO push_content SET  service='%s', content_label='%s', content='%s', datepublish='%s', author='%s'",
                        $data[$SERVICE], $data[$LABEL], $content, $data[$PUBLISHDATE], $data[$AUTHOR]
                        );
                    }

                    $this->broadcast_model->runQuery($sql_query);
                }

            }

        fclose($handle);
    }    
    
}
?>
