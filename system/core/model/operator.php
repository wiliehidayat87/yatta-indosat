<?php

class model_operator extends model_base {

    public function getOperatorId($name) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $name));

        $memcache_profile = 'cache_server1';
        $config_cache = loader_config::getInstance()->getConfig('cache');
        $memcache = caching_memcache::getInstance($memcache_profile);
        //$memcache->load ( $memcache_profile );
        //var_dump($memcache);
        $caching_data = new caching_data();
        $caching_data->key = $config_cache->key_operatorId_prefix . "_" . $name;
        $caching_data->profile = $memcache_profile;

        $cache_data = $memcache->get($caching_data);

        //get from memcached first
        if (!empty($cache_data->value)) {
            return $cache_data->value;
        } else {
            $sql = sprintf("select * from xmp.operator where name = '%s' limit 1", mysql_real_escape_string($name));
            $data = $this->databaseObj->fetch($sql);
            if (count($data) > 0) {
                $operatorID = $data [0] ['id'];
            } else {
                $operatorID = '';
            }

            // set to cache value
            $caching_data->value = $operatorID;
            // save value to memcache
            $memcache->set($caching_data);

            return $operatorID;
        }
    }

}
