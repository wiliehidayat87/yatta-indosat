<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/open-flash-chart-2/php5-ofc-library/lib/OFC/OFC_Chart.php';

Class CI_Chart
{

    protected $libPath;
    protected $color = array(
        '#5f9ef3',
        '#2cb72c',
        '#db020c',
        '#cb2fe7',
        '#fbd501',
        '#e107a6',
        '#f93305',
        '#072f49',
        '#444907',
        '#fd05b4',
        '#2fe7e5',
        '#6b4212',
        '#2b0749',
        '#7878f8',
        '#78f88d',
        '#9ad009',
        '#490712',
        '#074929',
        '#f88a78',
        '#080808'
    );

    public function __construct(){
        $this->libPath = DOMAIN . 'libraries/open-flash-chart-2/';
    }

    public function getColour($index){
        return $this->color[$index];
    }

    public function getRandomColour(){
        $index = rand(0,19);
        return $this->color[$index];
    }

    public function chartEmbed($name, $width, $height, $source, $media='file'){
        switch($media){
            case 'file':
                $mediaParameter = 'data-file';
                break;

            default:
                $mediaParameter = 'get-data';
                break;
        }

        return sprintf(
            'swfobject.embedSWF(
                "%sopen-flash-chart.swf",
                "%s",
                "%d",
                "%d",
                "9.0.0",
                "expressInstall.swf",
                {"%s":"%s"},
                {"wmode":"transparent"}
            );
            %s
            %s',
            $this->libPath,
            $name,
            $width,
            $height,
            $mediaParameter,
            $name . '_func',
            $this->dataEmbed($name, $source),
            $this->functionEmbed($name)
        );
    }


    public function dataEmbed($name, $value){
        if($value=='') $value='{}';
        return sprintf(
            'var %s_data = %s;',
            $name,
            str_replace(array("\n", "\t", "  "),'',$value)
        );
    }


    public function functionEmbed($name){
        return sprintf(
            'function %s_func(){return JSON.stringify(%s_data);}',
            $name,
            $name
        );
    }


    public function canvas($name, $width, $height, $source, $media='javascript'){
        return sprintf(
            '<script type="text/javascript">%s</script>',
            $this->chartEmbed($name, $width, $height, $source, $media)
        );
    }


    public function barChart3D($data, $title='', $param=''){
        //check if data == null
        if(empty($data)){
            $data = null;
        }

        // set graph title
        $ofcTitle = new OFC_Elements_Title( $title );

        if(isset($param['nodata']) && $param['nodata'] == true){
            $ofcTitle = new OFC_Elements_Title("Sorry, we have no record!!");
            $ofcTitle->set_style("{font-size:12px;font-weight:bold;margin-top:20px;}");
        }

        $value = array();
        $label = array();
        $tooltip = array();

        if(is_array($data)){
            foreach($data as $item){
                if(is_array($item)){
                    array_push($value, $item['value']);
                    array_push($label, substr($item['label'],0,8).'..' );
                    array_push($tooltip, $item['label']);
                }
                else{
                    array_push($value, $item);
                    array_push($label, substr($item,0,8).'..');
                    array_push($tooltip, $item);
                }
            }
        }

        // setting bar
        $ofcBar = new OFC_Charts_Bar_Glass;
//      $ofcBar->set_values( $value );
        $ofcBar->colour = '#0066CC';

        foreach($value as $i => $v){
            $list = new bar_3d_value( $v, $this->getColour($i), $tooltip[$i].'<br>Total: #val#' );
            $ofcBar->append_value( $list );
        }

        // setting legend
        $ofcXAxis = new OFC_Elements_Axis_X;
        $ofcXAxis->colour = '#909090';
        $ofcXAxis->set_labels_from_array( $label );

        // find min value
        $minYAxis = 0;

        // find max value + 10%
        $maxYAxis = (!empty($value))? max($value) : 1;
        $maxYAxis += ceil( ($maxYAxis * 10) / 100);

        // step max 20; minimal value 1;
        $stepYAxis = ceil( ($maxYAxis - $minYAxis)/5 );
        if( $stepYAxis < 0 ){
            $stepYAxis = 1;
        }
//      if( $stepYAxis > 10 ){
//          $stepYAxis = 10;
//      }

//      if(isset($param['stepYAxis'])){
//          $stepYAxis = $param['stepYAxis'];
//      }

        $ofcYAxis = new OFC_Elements_Axis_Y;
        $ofcYAxis->set_range( $minYAxis, $maxYAxis, $stepYAxis );

        $ofcChart = new OFC_Chart;
        $ofcChart->set_title( $ofcTitle );
        $ofcChart->add_element( $ofcBar );
        $ofcChart->set_x_axis( $ofcXAxis );
        $ofcChart->set_y_axis( $ofcYAxis );
        $ofcChart->set_bg_colour( '#FFFFFF' );

        return $ofcChart->toPrettyString();
    }


    public function barMultiChart3D($data, $title='', $param=''){
        //check if data == null
        if(empty($data)){
            $data = null;
        }

        // set graph title
        $ofcTitle = new OFC_Elements_Title( $title );

        if(isset($param['nodata']) && $param['nodata'] == true){
            $ofcTitle = new OFC_Elements_Title("Sorry, we have no record!!");
            $ofcTitle->set_style("{font-size:12px;font-weight:bold;margin-top:20px;}");
        }

        $label  = array();
        $xAxis  = array();
        $value  = array();
        $bar    = array();

        if(is_array($data)){
            foreach($data as $key => $item){
                if(is_array($item)){
                    array_push($label, $item['label']);
                    array_push($value, max($item['value']));

                    if(count($xAxis) == 0) $xAxis = $item['xAxis'];

                    // setting bar
                    $ofcBar = new OFC_Charts_Bar_Glass;
                    $ofcBar->set_values( $item['value'] );
                    $ofcBar->set_key($item['label'], 10);
                    $ofcBar->colour = $this->getColour($key);
                    array_push($bar, $ofcBar);
                }
                else{
                    array_push($value, $item);
                    array_push($label, $item);

                    // setting bar
                    $ofcBar = new OFC_Charts_Bar_3d;
                    $ofcBar->set_values( $item );
                    $ofcBar->set_key($item, 10);
                    $ofcBar->colour = '#0066CC';
                    array_push($bar, $ofcBar);
                }
            }
        }

        // setting legend
        $ofcXAxis = new OFC_Elements_Axis_X;
        $ofcXAxis->colour = '#909090';

        // make string array
        foreach($xAxis as $key => $item){
            $xAxis[$key] = sprintf("%s", $item);
        }
        $ofcXAxis->set_labels_from_array( $xAxis );

        // find min value
        $minYAxis = 0;

        // find max value + 10%
        $maxYAxis = max($value);
        $maxYAxis += ceil( ($maxYAxis * 10) / 100);

        // step max 20; minimal value 1;
        $stepYAxis = ceil( ($maxYAxis - $minYAxis)/20 );
        if( $stepYAxis < 0 ){
            $stepYAxis = 1;
        }
        if( $stepYAxis > 20 ){
            $stepYAxis = 20;
        }

        if(isset($param['stepYAxis'])){
            $stepYAxis = $param['stepYAxis'];
        }

        $ofcYAxis = new OFC_Elements_Axis_Y;
        $ofcYAxis->set_range( $minYAxis, $maxYAxis, $stepYAxis );

        $ofcChart = new OFC_Chart;
        $ofcChart->set_title( $ofcTitle );
        foreach($bar as $el){
            $ofcChart->add_element( $el );
        }
        $ofcChart->set_x_axis( $ofcXAxis );
        $ofcChart->set_y_axis( $ofcYAxis );
        $ofcChart->set_bg_colour( '#FFFFFF' );

        return $ofcChart->toPrettyString();
    }

    public function pieChart($data, $title='', $param=''){
        // check if data == null
        if(empty($data)){
            $data = null;
        }

        // set graph title
        $ofcTitle = new OFC_Elements_Title( $title );
        if(isset($param['titleStyle'])){
        	$ofcTitle->set_style($param['titleStyle']);
        }

        if(isset($param['nodata']) && $param['nodata'] == true){
            $ofcTitle = new OFC_Elements_Title("Sorry, we have no record!!");
            $ofcTitle->set_style("{font-size:12px;font-weight:bold;margin-top:20px;}");
        }

        // setting pie
        $ofcPie = new OFC_Charts_Pie;
        $ofcPie->set_start_angle(35);
        $ofcPie->set_animate(true);
        $ofcPie->set_colours($this->color);
        $ofcPie->set_tooltip("#label#<br>#val#");
        $ofcPie->set_no_labels();
        $ofcPie->gradient_fill();

        if(is_array($data)){
            foreach($data as $item){
                if(is_array($item)){
                    if(!empty($item['url'])){
                        $ofcPie->set_values($item['value'], $item['label'], $item['url']);
                    }
                    else{
                        $ofcPie->set_values($item['value'], $item['label']);
                    }
                }
                else{
                    $ofcPie->set_values($item);
                }
            }
        }

        $ofcChart = new OFC_Chart;
        $ofcChart->set_title( $ofcTitle );
        $ofcChart->add_element( $ofcPie );
        $ofcChart->x_axis = null;
        $ofcChart->set_bg_colour( '#FFFFFF' );

        return $ofcChart->toPrettyString();
    }

    public function lineDotChart($data, $title="", $label=array(), $level=10, $haloSize=2, $width=2, $dotSize=2){
        $title = new OFC_Elements_Title( $title );

        $tmp = array();
        foreach($data as $index => $list){
            if(!is_array($list['value'])){
                $list['value'] = (array) $list['value'];
            }
            $line[$index] = new OFC_Charts_Line_Dot();
            $line[$index]->set_key($list['label'], 10);
            $line[$index]->set_values( $list['value']);
            $line[$index]->set_halo_size( $haloSize );
            $line[$index]->set_width( $width );
            $line[$index]->set_dot_size( $dotSize );
            $line[$index]->set_colour( $this->color[$index] );
            $tmp = array_merge($tmp, $list['value']);
        }

        if(empty($label)){
          for($i=0; $i < count($data[0]['value']); $i++){
            if(isset($data[0]['xAxis'][$i]) && !empty($data[0]['xAxis'][$i]))
                $label []= (string) $data[0]['xAxis'][$i];
            else
                $label []= "";
          }
        }

        rsort($tmp);
        $y = new OFC_Elements_Axis_Y();
        $max = ($tmp[0]<1)? 1:$tmp[0];
        $min = $tmp[ count($tmp)-1 ];
        $range = ($max-$min)/$level;
        $y->set_range( $min, $max, $range );


        $x = new OFC_Elements_Axis_X;
        $x->colour = '#909090';
        $x->set_labels_from_array( $label );
        $x->set_steps(1);
        //$x->set_range(1, count($label), count($label)/2);

        $chart = new OFC_Chart();
        $chart->set_title( $title );

        foreach($data as $index => $list){
            $chart->add_element( $line[$index] );
        }

        $chart->set_y_axis( $y );
        $chart->set_x_axis( $x );
        $chart->set_bg_colour( '#FFFFFF' );

        return $chart->toPrettyString();
    }


    public function lineChart($data, $title='', $param=''){
         $title = new OFC_Elements_Title( $title );

        // check if data == null
        if(empty($data)){
            $data = null;
        }

        if(isset($param['nodata']) && $param['nodata'] == true){
            $ofcTitle = new OFC_Elements_Title("Sorry, we have no record!!");
            $ofcTitle->set_style("{font-size:12px;font-weight:bold;margin-top:20px;}");
        }

        $minYAxis   = 0;
        $maxYAxis   = 1;
        $stepYAxis  = 0;
        $xAxis      = '';

        if(is_array($data)){
            $lineData = array();
            foreach($data as $index => $line){
                $lineDot = new OFC_Charts_Line_Dot;
                $lineDot->set_key(  $line['label'], 10);
                $lineDot->set_values( $line['value'] );
                $lineDot->set_halo_size( 2 );
                $lineDot->set_width( 2 );
                $lineDot->set_dot_size( 2 );
                $lineDot->set_colour( $this->getColour($index) );
                array_push($lineData, $lineDot);

                // find min y axis value
                $min = (is_array($line['value'])) ? min( $line['value'] ) : $line['value'];
                if( 0 > $min ){
                    $minYAxis = $min;
                }

                // find max y axis value
                $max = (is_array($line['value'])) ? max( $line['value'] ) : $line['value'];
                if($maxYAxis < $max){
                    $maxYAxis = $max;
                }

                if(isset($line['xAxis'])){
                    $xAxis = $line['xAxis'];
                }
            }


            $ofcYAxis = new OFC_Elements_Axis_Y;
            $ofcYAxis->labels = null;
            $ofcYAxis->set_offset( false );

            // add 10% for max y axis
            $maxYAxis += ceil( ($max * 10) / 100);

            // step max 20; minimal value 1;
            $stepYAxis = ceil( ($maxYAxis - $minYAxis)/6);
            if( $stepYAxis < 0 ){
                $stepYAxis = 1;
            }
//          if( $stepYAxis > 20 ){
//              $stepYAxis = 20;
//          }
//
//          if(isset($param['stepYAxis'])){
//              $stepYAxis = $param['stepYAxis'];
//          }

            $ofcYAxis->set_range( $minYAxis, $maxYAxis, $stepYAxis);
        }
        else{
            $lineData = null;
            $ofcYAxis = null;
        }


        $ofcXAxis = new OFC_Elements_Axis_X;
        $ofcXAxis->colour = '#909090';
        $ofcXAxis->set_steps(1);
        $ofcXAxis->set_offset( false );

        if($xAxis){
            if(is_array($xAxis)){
                $newXAxis = array();
                foreach($xAxis as $item){
                    array_push($newXAxis, sprintf('%s',$item));
                }
                $ofcXAxis->set_labels_from_array( $newXAxis );
            }
            else{
                $ofcXAxis->set_labels(sprintf('%s',$xAxis));
            }
        }

        $chart = new OFC_Chart();
        $chart->set_title( $title );

        if(is_array($lineData)){
            foreach($lineData as $item){
                $chart->add_element( $item );
            }
        }

        $chart->set_y_axis( $ofcYAxis );
        $chart->set_x_axis( $ofcXAxis );
        $chart->set_bg_colour( '#FFFFFF' );

        return $chart->toPrettyString();
    }


    /**
     * generate data for candle chart
     * @param (array) $data
     * @param (string) $title
     *
     * format:
     * data[]['high']
     * data[]['top']
     * data[]['bottom']
     * data[]['low']
     */
    public function candleChart($data, $title='', $param=''){
        // check if data == null
        if(empty($data)){
            $data = null;
        }

        // set graph title
        $ofcTitle = new OFC_Elements_Title( $title );

        if(isset($param['nodata']) && $param['nodata'] == true){
            $ofcTitle = new OFC_Elements_Title("Sorry, we have no record!!");
            $ofcTitle->set_style("{font-size:12px;font-weight:bold;margin-top:20px;}");
        }

        // candle graph
        $ofcCandle = new OFC_Charts_Candle;
        $ofcCandle->set_colour('#0066CC');

        foreach($data as $candle){
            $ofcCandle->set_values($candle['high'], $candle['top'], $candle['bottom'], $candle['low']);
        }

        $ofcYAxis = new OFC_Elements_Axis_Y;
        $ofcYAxis->set_range( 0, 40, 10 );

        // chart canvas
        $ofcChart = new OFC_Chart;
        $ofcChart->set_title( $ofcTitle );
        $ofcChart->add_element( $ofcCandle );
        $ofcChart->set_y_axis( $ofcYAxis );

        return $ofcChart->toPrettyString();
    }

    /**
     * generate data for area hollow chart
     * @param (array) $data
     * @param (string) $title
     *
     * format:
     * data[]['value']
     * data[]['label']
     */
    public function areaHolowChart($data, $title='', $param=''){
        // check if data == null
        if(empty($data)){
            $data = array();
            $max = 1;
        }

        // set graph title
        $ofcTitle = new OFC_Elements_Title( $title );

        if(isset($param['nodata']) && $param['nodata'] == true){
            $ofcTitle = new OFC_Elements_Title("Sorry, we have no record!!");
            $ofcTitle->set_style("{font-size:12px;font-weight:bold;margin-top:20px;}");
        }

        // set area hollow
        $minYAxis = 0;
        $maxYAxis = 0;
        $stepYAxis= 0;
        $xAxis = '';
        $listOfChart = array();

        $counter = 0;
        foreach($data as $item){
            $ofcArea = new OFC_Charts_Area_Hollow;
            $ofcArea->set_width( 1 );
            $ofcArea->set_values( $item['value'] );
            $ofcArea->set_colour($this->color[$counter]);
            $ofcArea->set_key($item['label'], 10);

            // find min y axis value
            $min = (is_array($item['value'])) ? min( $item['value'] ) : $item['value'];
            if( 0 > $min ){
                $minYAxis = $min;
            }

            // find max y axis value
            $max = (is_array($item['value'])) ? max( $item['value'] ) : $item['value'];
            if($maxYAxis < $max){
                $maxYAxis = $max;
            }

            if(isset($item['xAxis'])){
                $xAxis = $item['xAxis'];
            }

            array_push($listOfChart, $ofcArea);
            $counter++;
        }

        $ofcChart = new OFC_Chart;
        $ofcChart->set_title( $ofcTitle );

        foreach($listOfChart as $chart){
            $ofcChart->add_element( $chart );
        }

        $ofcYAxis = new OFC_Elements_Axis_Y;
        $ofcYAxis->labels = null;
        $ofcYAxis->set_offset( false );

        // add 10% for max y axis
        $maxYAxis += ceil( ($max * 10) / 100);

        // step max 20; minimal value 1;
        $stepYAxis = ceil( ($maxYAxis - $minYAxis)/10 );
//      if( $stepYAxis < 0 ){
//          $stepYAxis = 1;
//      }
//      if( $stepYAxis > 20 ){
//          $stepYAxis = 20;
//      }
//
//      if(isset($param['stepYAxis'])){
//          $stepYAxis = $param['stepYAxis'];
//      }

        $ofcYAxis->set_range( $minYAxis, $maxYAxis, $stepYAxis);

        $ofcXAxis = new OFC_Elements_Axis_X;
        $ofcXAxis->colour = '#909090';
        $ofcXAxis->set_steps(1);

        if($xAxis){
            if(is_array($xAxis)){
                $newXAxis = array();
                foreach($xAxis as $item){
                    array_push($newXAxis, sprintf('%s',$item));
                }
                $ofcXAxis->set_labels_from_array( $newXAxis );
            }
            else{
                $ofcXAxis->set_labels(sprintf('%s',$xAxis));
            }
        }

        $ofcChart->add_y_axis( $ofcYAxis );
        $ofcChart->x_axis = $ofcXAxis;
        $ofcChart->set_bg_colour( '#FFFFFF' );

        return $ofcChart->toPrettyString();
    }

    public function stackedBarChart($data, $title="", $param=array(), $label=array(), $level=5){
       // check if data == null
        if(empty($data)){
            $data = null;
        }

        // set graph title
        $ofc_title = new OFC_Elements_Title( $title );

        if(isset($param['nodata']) && $param['nodata'] == true){
            $ofc_title = new OFC_Elements_Title("Sorry, we have no record!!");
            $ofc_title->set_style("{font-size:12px;font-weight:bold;margin-top:20px;}");
        }

        $bar_stack = new OFC_Charts_Bar_Stack();
        if(!empty($data)){
            foreach($data as $index => $labes){
                $bar_stack->set_key($data[$index]['label'], 10);
            }

            $tmp = array();
            foreach($data[0]['xAxis'] as $key => $value){
                $list = array();
                $yTotal = 0;
                foreach($data as $index => $labels){
                    $list []= new OFC_Charts_Bar_Stack_Value($labels['value'][$key],
                                                             $this->getColour($index),
                                                             $labels['label'].':'.number_format($labels['value'][$key], 0, ',', '.').'<br>Total:#total#');
                    $yTotal += $labels['value'][$key];
                }
                $tmp []= $yTotal;
                $bar_stack->append_stack( $list );
            }

            if(empty($label)){
              for($i=0; $i < count($data[0]['value']); $i++){
                if(isset($data[0]['xAxis'][$i]) && !empty($data[0]['xAxis'][$i]))
                    $label []= (string) $data[0]['xAxis'][$i];
                else
                    $label []= "";
              }
            }
            rsort($tmp);
        }

        $y = new OFC_Elements_Axis_Y();
        $max = (isset($tmp[0]))? $tmp[0] : 0;
        $min = 0;
        $range = ($max-$min)/$level;
        $y->set_range( $min, $max, $range );


        $x = new OFC_Elements_Axis_X;
        $x->colour = '#909090';
//        $x->set_labels( $label );
        $x->set_labels_from_array( $label );
        $x->set_steps(1);
        //$x->set_range(1, count($label), count($label)/2);

        $chart = new OFC_Chart();
        $chart->set_title( $ofc_title );
        $chart->add_element( $bar_stack );
        $chart->set_y_axis( $y );
        $chart->set_x_axis( $x );
        $chart->set_bg_colour( '#FFFFFF' );

        return $chart->toPrettyString();
    }
}
