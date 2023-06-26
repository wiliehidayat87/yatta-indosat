<?php

class manager_callcenter {

    public $spring_mapping = array('6' => 'vivo', '5' => 'claro', '7' => 'tim', '4' => 'oi');

    public function process($GET) {
        if (empty($GET['phone']))
            $idCarier = $this->getCarrier($GET['phone']);
        else
            $idCarier = 5;
        require_once $this->getOperatorName($idCarier);

        $log_profile = 'call_center';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);

        $xmp_controller = $GET ['xmp_controller'];
        $class_name = 'api_callcenter_' . $xmp_controller;

        if (class_exists($class_name)) {
            $api_callcenter = new $class_name ( );
            return $api_callcenter->process($GET);
        } else {
            return 'INVALID PARAMETERS';
        }
    }

    private function getCarrier($phone) {

        $url = "http://webapps.okto.com.br:50811/carrier/lookup";
        $param = 'phone=' . $phone;
        $param .= '&user=download';
        $param .= '&pwd=0kt0';
        $param .= '&opc=carrier_id';

        $hit_url = $url . '?' . $param;
        #HIT URL
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_URL, $hit_url);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = @curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function getOperatorName($id) {
        $id = (int) $id;
        $cfg = $config = $this->spring_mapping;

        $config_bootstrap = new config_bootstrap();
        $operatorName = $config_bootstrap->operator;
        foreach ($operatorName as $xmp_path) {
            if (array_key_exists($cfg[$id], $xmp_path)) {
                return $xmp_path[$cfg[$id]];
            }
        }
    }

}