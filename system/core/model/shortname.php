<?php

class model_shortname extends model_base {

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
        // $data->service, $data->adn, $data->operatorId, $data->type, $data->serviceId, $data->price

        $cache_data = $memcache->get($caching_data);

        //get from memcached first
        if (!empty($cache_data->value)) {
            return $cache_data->value;
        } else {

            $shortname_data = new shortname_data();
            $shortname_data->adn = $data->adn;
            $shortname_data->service = $data->service;
            //$shortname_data->channel = $data->channel;
	    $shortname_data->channel = 'sms';
            $getMtType = explode(";", $data->subject);
            $shortname_data->mt_type = $getMtType[0];

            $query = "SELECT xls . *
                FROM xmp.xl_shortname AS xls
                INNER JOIN xmp.service s ON xls.service_id = s.id
                INNER JOIN xmp.adn ON xmp.adn.name = s.adn
                WHERE
                
                xmp.adn.name = '%s' AND
                s.name = '%s' AND
                xls.channel = '%s' AND
                xls.mt_type = '%s'
                LIMIT 1
                ";

            $sql = sprintf($query, mysql_real_escape_string($shortname_data->adn), mysql_real_escape_string($shortname_data->service), mysql_real_escape_string($shortname_data->channel), 'pull'/* $shortname_data->mt_type */);

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

}
