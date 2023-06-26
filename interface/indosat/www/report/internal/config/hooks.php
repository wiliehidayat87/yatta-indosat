<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_controller'] = array(
                                'class'    => 'AppHook',
                                'function' => 'pre_controller',
                                'filename' => 'apphook.php',
                                'filepath' => 'hooks',
                                'params'   => ''
                                );
$hook['post_controller'] = array(
                                'class'    => 'AppHook',
                                'function' => 'post_controller',
                                'filename' => 'apphook.php',
                                'filepath' => 'hooks',
                                'params'   => ''
                                );

/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */
