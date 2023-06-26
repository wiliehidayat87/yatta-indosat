<?php

srand((double)microtime()*1000000);
$data = array();

// add random height bars:
for( $i=0; $i<10; $i++ )
  $data[] = rand(2,9);

require_once dirname(__FILE__) . '/../lib/OFC/OFC_Chart.php';

$title = new OFC_Elements_Title( date("D M d Y") );

$bar = new OFC_Charts_Bar_3d();
$bar->set_values( $data );
$bar->colour = '#D54C78';

$x_axis = new OFC_Elements_Axis_X();
$x_axis->set_3d( 5 );
$x_axis->colour = '#909090';
$x_axis->set_labels_from_array( array('10','20','30','40','50','60','70','80','90','100') );

$chart = new OFC_Chart();
$chart->set_title( $title );
$chart->add_element( $bar );
$chart->set_x_axis( $x_axis );

echo $chart->toPrettyString();

