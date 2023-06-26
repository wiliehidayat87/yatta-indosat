<?php

class Getmodule extends MY_Controller {
    public $dir    		= '';
    public $scanException	= array('.','..','.svn','smartytest.php','welcome.php','index.html');
    
    public function __construct() {
        parent::__construct();
        
        $this->dir = $this->config->item('module_folder');
    }

    public function index(){
        $module = $this->input->post("module_name");
        $operator_id = $this->input->post("operator_id");
        $mechanism_id = $this->input->post("mechanism_id");
//        $counter_module = $this->input->post("counter_module");
        
        $getFormModule          = $this->getFileContent($module);
        $formModuleWithParam    = $this->replaceWithParam($getFormModule, $operator_id, $mechanism_id); 
        
        $response = array(  'formModule'    => $formModuleWithParam,
                            'status'        => TRUE
        );
        
        echo json_encode($response);
        exit;
    }
    
    public function getFileContent($module){
        $data = scandir($this->dir);
        $fileData = $this->filterFile($data);
        $getContent = "";
        foreach ($fileData as $file ){
            $cek = $module.".txt";
            if ($file == $cek){                
                $getContent = file_get_contents($this->dir.$cek);               
            }
        }
        
        return $getContent;
    }
    
    function replaceWithParam($file, $operator_id, $mechanism_id){
        $stringFile = preg_replace("/operator_id/",$operator_id,$file);
        $stringFile = preg_replace("/mechanism_id/",$mechanism_id,$stringFile);
//        $stringFile = preg_replace("/counter_module/",$counter,$stringFile);
        
        return $stringFile;
    }
    
    public function filterFile($fFile) {
        foreach($fFile as $filter){
                if (!in_array($filter, $this->scanException)) {
                        $filterData[]=$filter;
                }
        }
        return $filterData;
    }
}
