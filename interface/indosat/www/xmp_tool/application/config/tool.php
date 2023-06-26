<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * All custom config goes here
 * 
 * 
 */

$config['themes'] = 'default'; /* which view will be used */

$config['module_mapping'] = array(
    'text' => array('service_module_text','service_module_registration','service_module_unregistration','service_module_waplink','service_module_textdelay'),
    'wappush' => array('service_module_wappush')
);
$config['request_type'] = array(
        'clubfun' => array('fun','sapi'),
        'ajuda' => array('ajuda'),
        'sim' => array('sim'),
        'sair' => array('sair', 'descadastrar', 'saida', 'canc', 'can', 'cancela', 'cancelar', 'off', 'stop', 'fim', 'end', 'sai', 'cld', 'n', 'descadastra', 'parar', 'para', 'chega')
    );