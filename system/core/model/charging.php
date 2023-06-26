<?php

class model_charging extends model_base {

    public function get($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $memcache_profile = 'cache_server1';
        $config_cache = loader_config::getInstance()->getConfig('cache');
        $memcache = caching_memcache::getInstance($memcache_profile);
        //$memcache->load ( $memcache_profile );

        $caching_data = new caching_data ( );
        $caching_data->key = $config_cache->key_charging_prefix . "_" . $data->service . "_" . $data->adn . "_" . $data->operatorId . "_" . $data->type . "_" . $data->serviceId . "_" . $data->price;
        $caching_data->profile = $memcache_profile;
        $caching_data->expire = $config_cache->expire;
        // $data->service, $data->adn, $data->operatorId, $data->type, $data->serviceId, $data->price


        $cache_data = $memcache->get($caching_data);

        //get from memcached first
        if (!empty($cache_data->value)) {
            $log->write(array('level' => 'info', 'message' => "Get from memcached first"));
            return $cache_data->value;
        } else {

            $sql = "
                select c.*
                from xmp.charging as c
                inner join
                xmp.service_charging_mapping sc on c.id = sc.charging_id
                inner join
                xmp.service s on s.id = sc.service_id
                where
                s.name = '%s' and
                s.adn = '%s' and
                c.operator = '%s' and
                c.message_type = '%s' ";

            if (!empty($data->serviceId))
                $sql .= " and c.charging_id = '" . mysql_real_escape_string($data->serviceId) . "' ";

            if ($data->price !== false and isset($data->price))
                $sql .= " and c.gross = '" . mysql_real_escape_string($data->price) . "' ";

            $sql .= " limit 1";

            $sql = sprintf($sql, mysql_real_escape_string($data->service), mysql_real_escape_string($data->adn), mysql_real_escape_string($data->operatorId), mysql_real_escape_string($data->type));

//		print_r($sql);

            $data = $this->databaseObj->fetch($sql);

            if (count($data) > 0) {
                // set to cache value
                $caching_data->value = $data [0];
                // save value to memcache
                $memcache->set($caching_data);
                return $data [0];
            } else {
                return false;
            }
        }
    }

    public function getTariff($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("SELECT * FROM spring_content_delivery WHERE price = '%s' LIMIT 1", mysql_real_escape_string($data->price));

        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

}
