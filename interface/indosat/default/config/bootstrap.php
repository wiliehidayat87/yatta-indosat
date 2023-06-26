<?php

/**
 * 
 * Operator supported are listed here, along with their bootstrap file
 * 
 * Only used if there's 1 API/class used for many operator
 * 
 * NOTE : 
 * put this config on core folder only
 *
 */
class config_bootstrap {

    public $operator = array(
        '1' => array('vivo' => '/app/operator/vivo/0.1/vivo/xmp.php'),
        '2' => array('claro' => '/app/operator/claro/0.1/claro/xmp.php'),
        '3' => array('tim' => '/app/operator/tim/0.1/tim/xmp.php'),
        '4' => array('oi' => '/app/operator/oi/0.1/oi/xmp.php')
    );

}