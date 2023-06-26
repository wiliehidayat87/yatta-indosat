<?php

class user_inclusion {

    public function includes($user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($user_data)));

        $load_config = loader_config::getInstance();
        $model_partner = loader_model::getInstance()->load('partner', 'connDatabase1');

        $partner_data = new partner_data ();
        $partner_data->service = $user_data->service;
        $partner_data->type = 'inclusion';

        $partner = $model_partner->get($partner_data);

        if ($partner == true) {
            if ($user_data->channel_subscribe == "java") {
                $channel = "sms";
            } else {
                $channel = $user_data->channel_subscribe;
            }
            $config_spring = $load_config->getConfig('spring');

            $get = 'operation=include_list';
            $get .= '&partner_id=' . $partner ['username'];
            $get .= '&partner_key=' . $partner ['password'];
            $get .= '&channel=' . $partner ['service'];
            $get .= '&event_source=' . $channel;
            $get .= '&target_list=' . $user_data->msisdn;

            $hit = http_request::get($config_spring->includeSubscriberURL, $get);

            return $hit;
        } else {
            return false;
        }
    }

    public function excludes($user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($user_data)));

        $load_config = loader_config::getInstance();
        $model_partner = loader_model::getInstance()->load('partner', 'connDatabase1');

        $partner_data = new partner_data ();
        $partner_data->service = $user_data->service;
        $partner_data->type = 'exclusion';

        $partner = $model_partner->get($partner_data);

        if ($partner == true) {
            if ($user_data->channel_unsubscribe == "java") {
                $channel = "sms";
            } else {
                $channel = $user_data->channel_unsubscribe;
            }
            $config_spring = $load_config->getConfig('spring');

            $get = 'operation=exclude_list';
            $get .= '&partner_id=' . $partner ['username'];
            $get .= '&partner_key=' . $partner ['password'];
            $get .= '&channel=' . $partner ['service'];
            $get .= '&event_source=' . $channel;
            $get .= '&target_list=' . $user_data->msisdn;

            $hit = http_request::get($config_spring->excludeSubscriberURL, $get);

            return $hit;
        } else {
            return false;
        }
    }

    public function listing(user_data $user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($user_data)));

        $load_config = loader_config::getInstance();
        $model_partner = loader_model::getInstance()->load('partner', 'connDatabase1');

        $partner_data = new partner_data ();
        $partner_data->service = $user_data->service;
        $partner_data->type = 'list';

        $partner = $model_partner->get($partner_data);

        if ($partner == true) {
            if ($user_data->channel_subscribe == "java") {
                $channel = "sms";
            } else {
                $channel = $user_data->channel_subscribe;
            }
            $config_spring = $load_config->getConfig('spring');

            $get = 'operation=list';
            $get .= '&partner_id=' . $partner ['username'];
            $get .= '&partner_key=' . $partner ['password'];
            $get .= '&channel=' . $partner ['service'];
            $get .= '&event_source=' . $channel;
            $get .= '&target_list=' . $user_data->msisdn;

            $hit = http_request::get($config_spring->listSubscriberURL, $get);

            return $hit;
        } else {
            return false;
        }
    }

}