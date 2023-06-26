<?php

class model_module {

    public function readMechanism($id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $id));

        return 'char';
    }

}