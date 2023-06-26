<?php

class default_charging_manager {

    private static $instance;

    private function __construct() {

    }

    public static function getInstance() {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!self::$instance)
            self::$instance = new self ();

        return self::$instance;
    }

    public function getCharging($data) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $model_charging = loader_model::getInstance ()->load('charging', 'connDatabase1');
        $charging_data = $model_charging->get($data);

        if ($charging_data !== false) {
            $data = loader_data::get('charging');
            $data->id = $charging_data ['id'];
            $data->operator = $charging_data ['operator'];
            $data->adn = $charging_data ['adn'];
            $data->chargingId = $charging_data ['charging_id'];
            $data->gross = $charging_data ['gross'];
            $data->netto = $charging_data ['netto'];
            $data->username = $charging_data ['username'];
            $data->password = $charging_data ['password'];
            $data->senderType = $charging_data ['sender_type'];
            $data->messageType = $charging_data ['message_type'];

            return $data;
        } else {
            return false;
        }
    }

}