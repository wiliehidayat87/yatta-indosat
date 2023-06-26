<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * helper to load theme library in view
 * @author ardy satria | ardy.hasanuddin@indodeveloper.com
 * @param theme name (optional)
 * @return obj 
 */
function callTheme($theme_name=null) {
    $ci =& get_instance();
    if(!empty($theme_name)) {
        return  $ci->load->library('theme', $theme_name);
    }else {
        return  $ci->load->library('theme');
    }
}

function loadCSSTheme($theme_name=null) {
    $ci =& get_instance();
    $ci->load->library('theme');
    if(!empty($theme_name)) {
        $ci->load->library('theme', $theme_name);
        echo $ci->theme->load_css();
    }else {
        $ci->load->library('theme');
        echo $ci->theme->load_css();
    }
}

function getImageTheme($name) {
    $ci =& get_instance();
    $ci->load->library('theme');
    return $ci->theme->getImage($name);
}
?>
