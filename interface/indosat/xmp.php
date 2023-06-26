<?php
define ( 'CORE_PATH', '/app/xmp2012/system/core' );
define ( 'APP_PATH', '/app/xmp2012/system' );
define ( 'OPERATOR_PATH', '/app/xmp2012/interface');
define ( 'CONFIG_PATH', '/app/xmp2012/interface/indosat/config' );

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
require_once CORE_PATH . "/autoload.php";
autoload::getInstance();
