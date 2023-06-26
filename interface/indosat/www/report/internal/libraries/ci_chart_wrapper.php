<?php
require_once dirname(__FILE__) . '/ci_chart.php';

define('ROW_DATA_LABEL', 'value');
define('ROW_TITLE_LABEL', 'label');

class ci_chart_wrapper{
    private $type;
    private $data;
    private $stepYAxis;
    protected $ci_chart;

    public function __construct(){
        $this->ci_chart = new Ci_Chart();
    }

    public function set($type, $data, $stepYAxis=false, $multiData=false){
        $this->type = $type;
        $this->data = $this->normalizeChart($data, $multiData);
        $this->stepYAxis = $stepYAxis;
    }

    /*
     * Modify function normalizeChart for necesssary data modification
     */
    private function normalizeChart($data, $multiData, $rowTitleLabel= ROW_TITLE_LABEL, $rowDataLabel = ROW_DATA_LABEL){
        $reformatedData = array();
        if($data == false || empty($data)){
            return false;
        }

        foreach($data as $row){
            $row = (array)$row;
            if(empty($row)){
                return false;
            }
            else{
                $row['value']   = array();
                $row['xAxis']   = array();

                foreach($row[$rowDataLabel] as $index => $value){
                    array_push($row['value'], (int) $value);
                    array_push($row['xAxis'], $index);
                }

                if( ($multiData != true)){
                    if (isset($row['type']) && ($row['type'] == 'total')){
                        array_push($reformatedData, array(
                            'label' => $row[$rowTitleLabel],
                            'value' => $row['value'],
                            'xAxis' => $row['xAxis']
                        ));
                    }
                }
                else{
                    array_push($reformatedData, array(
                        'label' => $row[$rowTitleLabel],
                        'value' => $row['value'],
                        'xAxis' => $row['xAxis']
                    ));
                }
            }
        }

        return $reformatedData;
    }

    public function get(){
        switch($this->type){
            case 'line':
                return $this->getLineData($this->data, false);
                break;

            case 'multibar';
                return $this->getMultiBarData($this->data, false);
                break;

            case 'area':
                return $this->getAreaData($this->data, false);
                break;

            case 'bar':
                return $this->getBarData($this->data, false);
                break;

            case 'pie':
                return $this->getPieData($this->data, false);
                break;
        }
    }

    public function scratchCanvas($canvasName, $width, $height, $chartData){
        return $this->ci_chart->canvas($canvasName, $width, $height, $chartData);
    }

    public function getLineData($data, $ajaxCall=true){
        if($data == false){
            if($ajaxCall == true){
                $result['status']   = 'OK';
                $result['message']  = 'Success';
                $result['data']     = '{}';

                echo json_encode($result);
                exit;
            }
            else{
                $param['nodata'] = true;
                $data = null;
            }
        }

        if($this->stepYAxis != false)
            $param['stepYAxis'] = $this->stepYAxis;

        $line = $this->ci_chart->lineChart($data, '', $param);

        if($ajaxCall == true){
            $result['status']   = 'OK';
            $result['message']  = 'Success';
            $result['data']     = json_decode($line);

            echo json_encode($result);
        }
        else{

            return $line;
        }
    }

    public function getMultiBarData($data, $ajaxCall=true){
        $param = array();
        if($data == false){
            if($ajaxCall == true){
                $result['status']   = 'OK';
                $result['message']  = 'Success';
                $result['data']     = '{}';
                echo json_encode($result);
                exit;
            }
            else{
                $param['nodata'] = true;
                $data = null;
            }
        }

        if($this->stepYAxis != false)
            $param['stepYAxis'] = $this->stepYAxis;

        $multibar = $this->ci_chart->barMultiChart3D($data, '', $param);

        if($ajaxCall == true){
            $result['status']   = 'OK';
            $result['message']  = 'Success';
            $result['data']     = json_decode($multibar);

            echo json_encode($result);
        }
        else{

            return $multibar;
        }
    }

    public function getAreaData($data, $ajaxCall=true){
        $param = array();
        if($data == false){
            if($ajaxCall == true){
                $result['status']   = 'OK';
                $result['message']  = 'Success';
                $result['data']     = '{}';

                echo json_encode($result);
                exit;
            }
            else{
                $param['nodata'] = true;
                $data = null;
            }
        }

        if($this->stepYAxis != false)
            $param['stepYAxis'] = $this->stepYAxis;

        $area = $this->ci_chart->areaHolowChart($data, '', $param);

        if($ajaxCall == true){
            $result['status']   = 'OK';
            $result['message']  = 'Success';
            $result['data']     = json_decode($area);

            echo json_encode($result);
        }
        else{

            return $area;
        }
    }

    public function getPieData($data, $ajaxCall=true){
        $param = array();
        if($data == false){
            if($ajaxCall == true){
                $result['status']   = 'OK';
                $result['message']  = 'Success';
                $result['data']     = '{}';

                echo json_encode($result);
                exit;
            }
            else{
                $param['nodata'] = true;
                $data = null;
            }
        }

        if(is_array($data)){
            foreach($data as $key => $item){
                if(is_array($item['value'])){
                    $data[$key]['value'] = array_sum($item['value']);
                }
                else{
                    $data[$key]['value'] = $item['value'];
                }
            }
        }

        $pie = $this->ci_chart->pieChart($data, '', $param);

        if($ajaxCall == true){
            $result['status']   = 'OK';
            $result['message']  = 'Success';
            $result['data']     = json_decode($pie);

            echo json_encode($result);
        }
        else{

            return $pie;
        }
    }


    public function getBarData($data, $ajaxCall=true){
        $param = array();
        if($data == false){
            if($ajaxCall == true){
                $result['status']   = 'OK';
                $result['message']  = 'Success';
                $result['data']     = '{}';

                echo json_encode($result);
                exit;
            }
            else{
                $param['nodata'] = true;
                $data = null;
            }
        }

        if(is_array($data)){
            foreach($data as $key => $item){
                if(is_array($item['value'])){
                    $data[$key]['value'] = array_sum($item['value']);
                }
                else{
                    $data[$key]['value'] = $item['value'];
                }
            }
        }

        if($this->stepYAxis != false)
            $param['stepYAxis'] = $this->stepYAxis;

        $bar = $this->ci_chart->barChart3D($data,'', $param);

        if($ajaxCall == true){
            $result['status']   = 'OK';
            $result['message']  = 'Success';
            $result['data']     = json_decode($bar);

            echo json_encode($result);
        }
        else{

            return $bar;
        }
    }
}
?>
