<?php

class default_broadcast_wappush implements broadcast_interface {

    /**
     * @param $broadcast_data
     */
    public function push(broadcast_data $broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if ($broadcast_data->contentSelect == "custom") {
            $custombase = new default_broadcast_custombase($broadcast_data);
            return $custombase->push($broadcast_data);
        }

        $content_manager = content_manager::getInstance();
        $broadcast_content = $content_manager->getBroadcastContent($broadcast_data);

        if ($broadcast_content == false) {
            return false;
        }

        $model_operator = loader_model::getInstance()->load('operator', 'connDatabase1');
        $operator_name = $broadcast_data->operator;
        $operator_id = $model_operator->getOperatorId($operator_name);

        $users = $this->populateUser($broadcast_data);

        if ($users !== false) {
            $pushproject_data = new model_data_pushproject();
            $pushproject_data->sid = $broadcast_data->id;
            $pushproject_data->src = $broadcast_data->adn;
            $pushproject_data->oprid = $operator_id;
            $pushproject_data->service = $broadcast_data->service;
            $pushproject_data->subject = strtoupper("MT;PUSH;SMS;DAILYPUSH");
            $pushproject_data->message = $broadcast_content->content;
            $pushproject_data->price = $broadcast_data->price;

            $mPushProject = loader_model::getInstance()->load('pushproject', 'connBroadcast');
            $pid = $mPushProject->save($pushproject_data);

            $amount = 0;
            foreach ($users as $users_data) {
                $pushbuffer_data = new model_data_pushbuffer ();
                $pushbuffer_data->pid = $pid;
                $pushbuffer_data->src = $broadcast_data->adn;
                $pushbuffer_data->dest = $users_data ['msisdn'];
                $pushbuffer_data->oprid = $operator_id;
                $pushbuffer_data->service = $broadcast_data->service;
                $pushbuffer_data->subject = strtoupper("MT;PUSH;SMS;DAILYPUSH");
                $pushbuffer_data->message = $broadcast_content->content;
                $pushbuffer_data->price = $broadcast_data->price;
                $pushbuffer_data->stat = "ON_QUEUE";
                $pushbuffer_data->tid = date("YmdHis") . str_replace('.', '', microtime(true));

                $mt_data = $this->createMT($pushbuffer_data, $broadcast_data);
                $pushbuffer_data->obj = serialize($mt_data);

                $mPushBuffer = loader_model::getInstance()->load('pushbuffer', 'connBroadcast');

                if ($mPushBuffer->save($pushbuffer_data)) {
                    $amount++;
                }
            }

            $pushproject_data = new model_data_pushproject();
            $pushproject_data->pid = $pid;
            $pushproject_data->amount = $amount;
            $mPushProject->update($pushproject_data);
        }

        return true;
    }

    /**
     * @param $broadcast_data
     */
    public function populateUser(broadcast_data $broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $model_user = loader_model::getInstance()->load('user', 'connDatabase1');
        return $model_user->execUser($broadcast_data);
    }

    public function createMT(model_data_pushbuffer $pushbuffer_data, broadcast_data $broadcast_data) {
        $log = manager_logging::getInstance();

        $log->write(array('level' => 'debug', 'message' => "Start"));

        $mt_data = loader_data::get('mt');
        $mt_data->inReply = NULL;
        $mt_data->msgId = date("YmdHis") . str_replace('.', '', microtime(true));
        $mt_data->adn = $pushbuffer_data->src;
        $mt_data->msgData = $pushbuffer_data->message;
        $mt_data->price = $pushbuffer_data->price;
        $mt_data->operatorId = $pushbuffer_data->oprid;
        $mt_data->channel = "sms";
        $mt_data->service = $pushbuffer_data->service;
        $mt_data->subject = $pushbuffer_data->subject;
        $mt_data->operatorName = $broadcast_data->operator;
        $mt_data->msisdn = $pushbuffer_data->dest;
        $mt_data->type = "dailywappush";

        $user_data = loader_data::get('user');
        $user_data->service = $pushbuffer_data->service;
        $user_data->msisdn = $pushbuffer_data->dest;
        $user_data->adn = $pushbuffer_data->src;
        $user_data->operator = $broadcast_data->operator;

        $content = loader_data::get('content');

        $content->handler = 'indirect';
        $content->price = 0;
        $content->limit = 1;
        $content->userObj = $user_data;
        $content->initialChargeType = 'not charged';

        $content->wapName = "default";

        $contentNew = content_manager::getInstance()->getContent($content);

        $mt_data->content_data = $contentNew;

        return $mt_data;
    }

}