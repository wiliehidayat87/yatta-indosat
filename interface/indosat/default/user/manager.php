<?php

class default_user_manager {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!self::$instance)
            self::$instance = new self ( );

        return self::$instance;
    }

    public function getUserData($user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($user_data)));

        $model_user = loader_model::getInstance()->load('user', 'connDatabase1');
        $data = $model_user->get($user_data);

        if ($data != false) {
            $user_data->id = $data ['id'];
            $user_data->msisdn = $data ['msisdn'];
            $user_data->service = $data ['service'];
            $user_data->transaction_id_subscribe = $data ['transaction_id_subscribe'];
            $user_data->transaction_id_unsubscribe = $data ['transaction_id_unsubscribe'];
            $user_data->adn = $data ['adn'];
            $user_data->operator_id = $data ['operator'];
            $user_data->channel_subscribe = $data ['channel_subscribe'];
            $user_data->channel_unsubscribe = $data ['channel_unsubscribe'];
            $user_data->subscribed_from = $data ['subscribed_from'];
            $user_data->subscribed_to = $data ['subscribed_until'];
            $user_data->partner = $data ['partner'];
            $user_data->active = $data ['active'];
            $user_data->time_created = $data ['time_created'];
            $user_data->time_modified = $data ['time_updated'];

            return $user_data;
        }
        return false;
    }

    public function getUserException($user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($user_data)));

        $model_user = loader_model::getInstance()->load('user', 'connDatabase1');
        $data = $model_user->getException($user_data);
        if ($data != false) {
            $user_data->id = $data ['id'];
            $user_data->msisdn = $data ['msisdn'];
            $user_data->service = $data ['service'];
            $user_data->transaction_id_subscribe = $data ['transaction_id_subscribe'];
            $user_data->transaction_id_unsubscribe = $data ['transaction_id_unsubscribe'];
            $user_data->adn = $data ['adn'];
            $user_data->operator_id = $data ['operator'];
            $user_data->channel_subscribe = $data ['channel_subscribe'];
            $user_data->channel_unsubscribe = $data ['channel_unsubscribe'];
            $user_data->subscribed_from = $data ['subscribed_from'];
            $user_data->subscribed_to = $data ['subscribed_until'];
            $user_data->partner = $data ['partner'];
            $user_data->active = $data ['active'];
            $user_data->time_created = $data ['time_created'];
            $user_data->time_modified = $data ['time_updated'];

            return $user_data;
        }
        return false;
    }

    public function addUserData($user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($user_data)));

        $model_user = loader_model::getInstance()->load('user', 'connDatabase1');
        return $model_user->add($user_data);
    }

    public function updateUserData($user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($user_data)));

        $model_user = loader_model::getInstance()->load('user', 'connDatabase1');
        $model_user->update($user_data);
        return true;
    }

}