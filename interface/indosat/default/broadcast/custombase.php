<?php

class default_broadcast_custombase implements broadcast_interface {

    /**
     * @param $broadcast_data
     */
    public function push($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $broadcast_config = loader_config::getInstance()->getConfig('broadcast');
        $content_manager = content_manager::getInstance();
        $broadcast_content = $content_manager->getBroadcastContent($broadcast_data);
        
        if ($broadcast_content == false) {
            return false;
        }

        $main_config = loader_config::getInstance()->getConfig('main');
        $model_operator = loader_model::getInstance()->load('operator', 'connDatabase1');
        $operator_name = $main_config->operator;
        $adn = $main_config->adn;

        $file_path = $broadcast_config->bufferPath . "/data_user.tsv";
        $file = $this->populateUser($broadcast_data);

        if ($file !== false) {
            while (!feof($file)) {
                $users_data = explode("\t", fgets($file));

                if (!empty($users_data [0])) {
                    $slot = loader_config::getInstance()->getConfig('mt')->profile ['dailypush'] ['slot'];
                    $last_num = substr($users_data [3], - 1, 1);
                    $pid = ($last_num % $slot);

                    $pushbuffer_data = new model_data_pushbuffer ();
                    $pushbuffer_data->pid = $pid;
                    $pushbuffer_data->src = $adn;
                    $pushbuffer_data->dest = $users_data ['3'];
                    $pushbuffer_data->oprid = $model_operator->getOperatorId($operator_name);
                    $pushbuffer_data->service = $broadcast_data->service;
                    $pushbuffer_data->subject = strtoupper("MT;PUSH;" . $users_data ['7'] . ";DAILYPUSH");
                    $pushbuffer_data->message = $broadcast_content->content;
                    $pushbuffer_data->price = $broadcast_data->price;
                    $pushbuffer_data->stat = "ON_QUEUE";
                    $pushbuffer_data->tid = date("YmdHis") . str_replace('.', '', microtime(true));
                    $pushbuffer_data->type = "dailypush";

                    $mPushBuffer = loader_model::getInstance()->load('pushbuffer', 'connBroadcast');

                    $mPushBuffer->save($pushbuffer_data);
                }
            }
            fclose($file);
            if (unlink($file_path) === false) {
                $log->write(array('level' => 'error', 'message' => " Cannot delete a file : " . $file_path));
            }
        }

        return true;
    }

    /**
     * @param $broadcast_data
     */
    public function populateUser($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $broadcast_config = loader_config::getInstance()->getConfig('broadcast');
        $model_user = loader_model::getInstance()->load('user', 'connDatabase1');
        $model_user->execUser($broadcast_data);
        sleep(1); //waiting until file created


        $file = $broadcast_config->bufferPath . "/data_user.tsv";
        if (file_exists($file)) {
            return fopen($file, "r");
        } else {
            $log->write(array('level' => 'error', 'message' => "File not found : " . $file));
            return false;
        }
    }

    /**
     * @param $broadcast_data
     * @param $user_data
     */
    public function createMT($broadcast_pushdata, $users_data, $broadcast_data) {
        $log = manager_logging::getInstance();

        $log->write(array('level' => 'debug', 'message' => "Start"));

        return $mt_data;
    }

    public function sendtoQueue() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $slot = loader_config::getInstance()->getConfig('mt')->profile ['dailypush'] ['slot'];
        $model_service = loader_model::getInstance()->load('service', 'connDatabase1')->get();
        $main_config = loader_config::getInstance()->getConfig('main');
        $operator_name = $main_config->operator;
        for ($i = 0; $i < $slot; $i++) {
            foreach ($model_service as $service) {
                
                if ($main_config->use_forking) {
                    switch ($pid = pcntl_fork()) {
                        case - 1 :
                            $log->write(array('level' => 'error', 'message' => "Forking failed"));
                            die('Forking failed');
                            break;

                        case 0 :
                            $this->processSendToQueue($operator_name, $i, $service['name']);
                            exit();
                            break;

                        default :
                            //pcntl_waitpid ( $pid, $status );
                            break;
                    }                    
                } else {
                    $this->processSendToQueue($i, $service['name']);
                }

            }
        }
        return true;
    }
    
    public function processSendToQueue($operator_name, $i, $service_name) {
        $log = manager_logging::getInstance();

        $lockPath = '/tmp/lock_default_broadcast_queue_' . $operator_name .'_' . $i . $service_name;

        if (file_exists($lockPath)) {
            $log->write(array('level' => 'error', 'message' => "Lock File Exist on : " . $lockPath));
            echo "NOK - Lock File Exist on $lockPath \n";
            exit();
        } else {
            touch($lockPath);
        }

        $log->write(array('level' => 'debug', 'message' => " Slot : [" . $i . "][" . $service_name . "]"));
        $broadcast_config = loader_config::getInstance()->getConfig('broadcast');
        $model_user = loader_model::getInstance()->load('pushbuffer', 'connBroadcast');
        $model_user->execPushbuffer($i, $service_name);

        sleep(1); //waiting until file created


        $buffer = $broadcast_config->bufferPath . "/pushBuffer" . $i . $service_name . ".tsv";
        if (file_exists($buffer)) {
            $file = fopen($buffer, "r");
        } else {
            $log->write(array('level' => 'info', 'message' => " File not found : " . $buffer));
            return false;
        }

        if ($file !== false) {
            while (!feof($file)) {
                $pushBuffer = explode("\t", fgets($file));
                if (!empty($pushBuffer [0])) {

                    $log->write(array('level' => 'debug', 'message' => " Update stat : ON_PROCESS"));
                    $pushbuffer_data = new model_data_pushbuffer ();
                    $pushbuffer_data->stat = "ON_PROCESS";
                    $pushbuffer_data->id = $pushBuffer [0];
                    $model_user->update($pushbuffer_data);

                    $mt_data = loader_data::get('mt');
                    $mt_data->pid = $pushBuffer [1];
                    $mt_data->inReply = NULL;
                    $mt_data->msgId = date("YmdHis") . str_replace('.', '', microtime(true));
                    $mt_data->adn = $pushBuffer [2];
                    $mt_data->msgData = $pushBuffer [7];
                    $mt_data->price = $pushBuffer [8];
                    $mt_data->operatorId = $pushBuffer [4];
                    $mt_data->service = $pushBuffer [5];
                    $mt_data->subject = $pushBuffer [6];
                    $mt_data->operatorName = $operator_name;
                    $mt_data->msisdn = $pushBuffer [3];
                    $mt_data->type = trim($pushBuffer [12]);
                    //$mt_data->serviceId = charging_manager::getInstance()->getCharging($mt_data)->chargingId;

                    $mt_processor = new manager_mt_processor ();
                    $queue = $mt_processor->saveToQueue($mt_data);
                    if ($queue === false) {
                        $log->write(array('level' => 'debug', 'message' => " Update stat : PENDING"));
                        $pushbuffer_data->stat = "PENDING";
                        $model_user->update($pushbuffer_data);
                        fclose($file);
                        if (unlink($buffer) === false) {
                            $log->write(array('level' => 'error', 'message' => " Cannot delete a file : " . $buffer));
                        }
                        return false;
                    }

                    $log->write(array('level' => 'debug', 'message' => " Update stat : PUSHED"));
                    $pushbuffer_data->stat = "PUSHED";
                    $model_user->update($pushbuffer_data);
                }
            }
            fclose($file);
            if (unlink($buffer) === false) {
                $log->write(array('level' => 'error', 'message' => " Cannot delete a file : " . $buffer));
            }
        }        
        unlink($lockPath);
    }

}
