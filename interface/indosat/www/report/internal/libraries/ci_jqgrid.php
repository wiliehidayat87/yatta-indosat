<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ci_jqgrid
{

    private $page 	 = 1;
    private $total 	 = 0;
    private $records = 0;
    private $paging  = 10;
    private $autoPaging = false;
    private $output;
    private $field   = array();

    public function setField($field){
        if(is_array($field)){
            $this->field = $field;
        }
        else{
            array_push($this->field, $field);
        }
    }

    public function setTotalRecord($total){
        $this->total = $total;
    }

    public function setPaging($limit){
        $this->paging = $limit;
    }

    public function setData($field, $data, $page=''){
        $this->setField($field);

        if(is_array($data)){
            $this->records = count($data);

            if($this->autoPaging == true){
                if(!empty($page)){
                    $this->page = $page;
                    $startIndex = ($this->paging * ($this->page - 1));
                    $endIndex	= ($this->paging * $this->page) - 1;
                    $data = array_slice($data, $startIndex, $endIndex);
                }
            }
            else{
                $this->page = $page;
                $this->records = $this->total;
            }

            $this->total = ceil($this->records/$this->paging);
            $this->output = new jqContainer($this->page, $this->total, $this->records);

            foreach($data as $key => $item){
                $row = new rows($key);
    //				foreach($item as $field => $value){
                    foreach($this->field as $index => $field){
                        if(isset($item[$field])){
                            $row->setCell(sprintf('%s',$item[$field]), $index);
                        }
                    }
    //				}
                $this->output->setRows($row);
            }
        }
        else{
            $this->output = new jqContainer($this->page, $this->total, $this->records);
        }
    }

    public function setDataTree($field, $data, $level, $parentId, $isLeaf=false, $page=''){
        $this->setField($field);

        if(is_array($data)){
            $this->records = count($data);

            if(!empty($page)){
                $this->page = $page;
                $startIndex = ($this->paging * ($this->page - 1));
                $endIndex	= ($this->paging * $this->page) - 1;
                $data = array_slice($data, $startIndex, $endIndex);
            }

            $this->total = ceil($this->records/$this->paging);

            $this->output = new jqContainer($this->page, $this->total, $this->records);

            foreach($data as $key => $item){
                $row = new rowsTree($key);
    //				foreach($item as $field => $value){
                    foreach($this->field as $index => $field){
                        if(isset($item->$field)){
                            $row->setCell(sprintf('%s',$item->$field), $index);
                        }
                    }
    //				}
                $row->setOption($level, $parentId, $isLeaf);
                $this->output->setRows($row);
            }
        }
        else{
            $this->output = new jqContainer($this->page, $this->total, $this->records);
        }
    }

    public function setDataTreeCPList($field, $data, $level, $parentId, $isLeaf=false, $page=''){
        $this->setField($field);

        if(is_array($data)){
            $this->records = count($data);

            if(!empty($page)){
                $this->page = $page;
                $startIndex = ($this->paging * ($this->page - 1));
                $endIndex	= ($this->paging * $this->page) - 1;
                $data = array_slice($data, $startIndex, $endIndex);
            }

            $this->total = ceil($this->records/$this->paging);

            $this->output = new jqContainer($this->page, $this->total, $this->records);

            foreach($data as $key => $item){
                $row = new rowsTree($key);
    //				foreach($item as $field => $value){
                    foreach($this->field as $index => $field){
                        if(isset($item->$field)){
                            $row->setCell(sprintf('%s',$item->$field), $index);
                        }
                    }
    //				}

                switch(strtolower($item->type)){
                    case 'cp':
                        $level = 0;
                        $isLeaf= false;
                        break;
                    case 'shortcode':
                        $level = 1;
                        $isLeaf= false;
                        break;
                    case 'service':
                        $level = 2;
                        $isLeaf= true;
                        break;
                }

                if(isset($item->parentId)) $parentId = $item->parentId;
                $row->setOption($level, $parentId, $isLeaf);
                $this->output->setRows($row);
            }
        }
        else{
            $this->output = new jqContainer($this->page, $this->total, $this->records);
        }
    }

    public function getJsonData(){
        return json_encode($this->output);
    }
    }

    class jqContainer{
    public $page;
    public $total;
    public $records;
    public $rows = array();

    public function __construct($page, $total, $records){
        $this->page 	= sprintf('%s', $page); //current page
        $this->total	= $total; //total page
        $this->records	= sprintf('%s', $records); //total data
    }

    public function setRows($row){
        array_push($this->rows, $row);
    }
    }

    class rows{
    public $id;
    public $cell = array();

    public function __construct($id){
        $this->id = $id;
    }

    public function setCell($cell, $index=false){
        if($index !== false){
            $this->cell[$index] = $cell;
        }
        else{
            array_push($this->cell, $cell);
        }
    }
    }

    class rowsTree{
    public $id;
    public $cell = array();

    public function __construct($id){
        $this->id = $id;
    }

    public function setCell($cell, $index=false){
        if($index !== false){
            $this->cell[$index] = $cell;
        }
        else{
            array_push($this->cell, $cell);
        }
    }

    public function setOption($level, $parentId, $isLeaf=false, $expanded=false){
        array_push($this->cell, $level);
        array_push($this->cell, $parentId);
        array_push($this->cell, $isLeaf);
        array_push($this->cell, $expanded);
    }
}
