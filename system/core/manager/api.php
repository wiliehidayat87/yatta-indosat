<?php

/**
 * 
 * must require config_boostrap first, do it manually
 * 
 *
 */
class manager_api {

    public function process($GET) {
        $operator_name = $GET ['operator'];

        $xmp_path = '/app/operator/' . $operator_name . '/0.1/' . $operator_name . '/xmp.php';
        if (file_exists($xmp_path)) {
            require_once ($xmp_path);
        } else {
            return array('status' => 'NOK', 'description' => 'no operator found');
        }

        $log_profile = 'mo_subscriber';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $classApi = $operator_name . "_api_" . $GET ['controller'];
        $classDefault = "default_api_" . $GET ['controller'];

        if (class_exists($classApi)) {
            $api = new $classApi ();
        } elseif (class_exists($classDefault)) {
            $log->write(array('level' => 'info', 'message' => " Class Doesn't Exist : " . $classApi));
            $api = new $classDefault ();
        } else {
            $log->write(array('level' => 'error', 'message' => " Class Doesn't Exist : " . $classApi . " & " . $classDefault));
            return array('status' => 'NOK', 'description' => 'no action found');
        }

        return $api->process($GET);
    }

}