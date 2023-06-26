<?php

class http_request {

    public static function get($url, $getfields = '', $timeout = 10) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . (!empty($getfields) ? '?' . $getfields : ""));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $output = curl_exec($ch);
        $header = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $log->write(array('level' => 'info', 'message' => "Hit url : " . $url . (!empty($getfields) ? "?" . $getfields : ""), 'response' => "Header: " . $header . ". Body: " . $output));
        return $output;
    }

    public static function post($url, $postfields, $timeout = 10, $arrCustom = array()) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

        foreach ($arrCustom as $key => $val) {
            curl_setopt($ch, $key, $val);
        }

        $output = curl_exec($ch);
        $header = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $log->write(array('level' => 'info', 'message' => "Hit url : " . $url . "?" . $postfields, 'response' => "Header: " . $header . ". Body: " . $output));
        return $output;
    }

    public static function postBody($url, $body, $timeout = 10) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        return self::post($url, $body, $timeout);
    }

    public static function getRealIpAddr() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}
