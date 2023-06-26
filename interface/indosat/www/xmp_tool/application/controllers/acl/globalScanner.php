<?php

class GlobalScanner extends MY_Controller {

    public $dir = '';
    public $scanException = array('.', '..', '.svn', 'smartytest.php', 'welcome.php', 'index.html');

    public function __construct() {
        parent::__construct();
        $this->load->model('acl/acl_model');
        $this->dir = $this->config->item('controller_folder');
    }

    function index() {
        $this->scanProcess();
        echo "global Scan is Done";
    }

    function scanProcess() {
        $fileData1 = $this->filterFile($this->fileScanner(''));
        foreach ($fileData1 as $fData) {
            if ($this->phpChecker($fData) == TRUE) {
                $fileLink = $fData;
                echo "<br>".$fileLink;
                $classId = $this->acl_model->checkLink(str_replace(".php", "", $fileLink, $result));
                if (!empty($classId)) {
                    $methodData = $this->methodExtractor($fileLink);
                    var_dump($methodData);
                    $this->insertDB($methodData, $classId);
                }
            } else {
                $fileData2 = $this->filterFile($this->fileScanner($fData));
                foreach ($fileData2 as $fData2) {
                    if ($this->phpChecker($fData2) == TRUE) {
                        $fileLink = $fData . "/" . $fData2;
                        echo "<br>".$fileLink;
                        $classId = $this->acl_model->checkLink(str_replace(".php", "", $fileLink, $result));
                        if (!empty($classId)) {
                            $methodData = $this->methodExtractor($fileLink);
                            var_dump($methodData);
                            $this->insertDB($methodData, $classId);
                        }
                    }
                }
            }
        }
    }

    function fileScanner($subPath) {
        if (empty($subPath))
            $dataScan = scandir($this->dir);
        else
            $dataScan = scandir($this->dir . $subPath);

        return $dataScan;
    }

    function pathFinder() {
        
    }

    function phpChecker($phpCheck) {
        if (strpos($phpCheck, '.php') === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    function methodExtractor($link) {
        $arr_methods = array();
        $linesData = file($this->dir . $link);
        foreach ($linesData as $data) {
            if (preg_match('/(function|Function) ([_A-Za-z0-9]+)/', $data, $regs)) {
                if ($regs[2] != '__construct')
                    $arr_methods[] = $regs[2];
            }
        }
        return $arr_methods;
    }

    function filterFile($fFile) {
        foreach ($fFile as $filter) {
            if (!in_array($filter, $this->scanException)) {
                $filterData[] = $filter;
            }
        }
        return $filterData;
    }

    function insertDB($methodData, $idCtrlLink) {
        $groupList = $this->acl_model->getGroupList();
        foreach ($groupList as $group) {
            foreach ($methodData as $method) {
                $response = $this->acl_model->getClassMethod($group['id'], $idCtrlLink, $method);
            }
        }
    }

}
