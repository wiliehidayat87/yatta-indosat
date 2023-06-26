<?php

class manager_user_inclusion {

    public function includes(user_data $user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($user_data)));

        $config_main = loader_config::getInstance()->getConfig('main');
        $class_name = $config_main->operator . '_user_inclusion';

        if (class_exists($class_name)) {
            $userInclusion = new $class_name ();
        } else if (class_exists('user_inclusion')) {
            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
            $userInclusion = new user_inclusion ();
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "user_inclusion"));
            return false;
        }

        return $userInclusion->includes($user_data);
    }

    public function excludes(user_data $user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($user_data)));

        $config_main = loader_config::getInstance()->getConfig('main');
        $class_name = $config_main->operator . '_user_inclusion';

        if (class_exists($class_name)) {
            $userInclusion = new $class_name ();
        } else if (class_exists('user_inclusion')) {
            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
            $userInclusion = new user_inclusion ();
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "user_inclusion"));
            return false;
        }

        return $userInclusion->excludes($user_data);
    }

    public function listing(user_data $user_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($user_data)));

        $config_main = loader_config::getInstance()->getConfig('main');
        $class_name = $config_main->operator . '_user_inclusion';

        if (class_exists($class_name)) {
            $userInclusion = new $class_name ();
        } else if (class_exists('user_inclusion')) {
            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
            $userInclusion = new user_inclusion ();
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "user_inclusion"));
            return false;
        }

        return $userInclusion->listing($user_data);
    }

}
