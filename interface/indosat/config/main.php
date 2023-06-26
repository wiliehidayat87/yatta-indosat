<?php

class config_main {

    public $operator = 'indosat';
    public $adn = '99879';
    public $channel = array('sms', 'web', 'wap', 'umb');
    public $partner = array('l7');
    public $prefix = 'indosat';
    public $request_type = array(
        'reg' => array('reg'),
        'unreg' => array('unreg')
    );
    public $use_forking = true;

}
