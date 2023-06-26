<?php
class Partner extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('partner_model');
    }

    public function index(){
        die('where are you going?');
    }

    public function signIn() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'signInUsername', 'signInPassword');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->signIn($this->getParam('signInUsername'),$this->getParam('signInPassword'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function partnerAdd() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerUsername', 'partnerPassword', 'sharing', 'hasAccess', 'privilegeList');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->partnerAdd($this->getParam('partnerUsername'),$this->getParam('partnerPassword'), $this->getParam('sharing'), $this->getParam('hasAccess'), $this->getParam('privilegeList'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function partnerGetList() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'startFrom', 'limit');
            $optionalParams = array ('searchPattern');
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->partnerGetList($this->getParam('searchPattern'), $this->getParam('startFrom'), $this->getParam('limit'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPremiumDownloadReport() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','partnerId','period');
            $optionalParams = array ('contentType'=>NULL, 'searchPattern'=>NULL, 'startFrom'=>NULL, 'limit'=>NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');
            $this->splitPeriod('period');

            //call model to get data
            $data = $this->partner_model->getPremiumDownloadReport(
                $this->getParam('partnerId'),
                $this->getParam('year'),
                $this->getParam('month'),
                $this->getParam('contentType'),
                $this->getParam('searchPattern'),
                $this->getParam('startFrom'),
                $this->getParam('limit')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function addServicePermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $partnerId = $this->getParam('partnerId');
            $data = json_decode($this->getParam('data'), 1);

            $shortCode = $serviceId = $startdate = $endDate = $ratio = $filterSubject = $filterOperator = $filterSid = $filterPrice = array();
            $insertedId = array();

            for ($i = 0; $i < count($data); $i++) {
                $shortCode[$i] = $data[$i]['shortCode'];
                $serviceId[$i] = $data[$i]['service'];

                for ($j = 0; $j < count($data[$i]['ratio']); $j++) {
                    $startDate[$i][] = $data[$i]['ratio'][$j]['startDate'];
                    $endDate[$i][] = $data[$i]['ratio'][$j]['endDate'];
                    $ratio[$i][] = $data[$i]['ratio'][$j]['ratio'];
                }

                for ($j = 0; $j < count($data[$i]['filter']); $j++) {
                    if (true === isset($data[$i]['filter'][$j])) {
                        switch ($data[$i]['filter'][$j]['param']) {
                        case 'subject':
                            $filterSubject[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'operator':
                            $filterOperator[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'sid':
                            $filterSid[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'price':
                            $filterPrice[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        }
                    }
                }
            }

            for ($i = 0; $i < count($shortCode); $i++) {
                $dataResult = $this->partner_model->addServicePermission($partnerId, $shortCode[$i], $serviceId[$i]);

                if(false !== $data && null !== $data){
                    $insertedId[] = $partnerServiceId = $dataResult;

                    for ($j = 0; $j < count($startDate[$i]); $j++) {
                        $ratioResult = $this->partner_model->addServiceRatio($partnerServiceId, $startDate[$i][$j], $endDate[$i][$j], $ratio[$i][$j]);
                        if (false == $ratioResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deleteService($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }

                    if (true === isset($filterSubject[$i])) {
                        for ($j = 0; $j < count($filterSubject[$i]); $j++) {
                            $subjectResult = $this->partner_model->addServiceFilter($partnerServiceId, $filterSubject[$i][$j], 'subject');
                            if (false == $subjectResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteService($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterOperator[$i])) {
                        for ($j = 0; $j < count($filterOperator[$i]); $j++) {
                            $operatorResult = $this->partner_model->addServiceFilter($partnerServiceId, $filterOperator[$i][$j], 'operator');
                            if (false == $operatorResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteService($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterSid[$i])) {
                        for ($j = 0; $j < count($filterSid[$i]); $j++) {
                            $sidResult = $this->partner_model->addServiceFilter($partnerServiceId, $filterSid[$i][$j], 'sid');
                            if (false == $sidResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteService($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterPrice[$i])) {
                        for ($j = 0; $j < count($filterPrice[$i]); $j++) {
                            $priceResult = $this->partner_model->addServiceFilter($partnerServiceId, $filterPrice[$i][$j], 'price');
                            if (false == $priceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteService($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }
                }
                else{
                    throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                }
            }
            echo respOK($dataResult);
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getServicePermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array ('searchPattern'=>NULL, 'startFrom'=>NULL, 'limit'=>NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getServicePermission($this->getParam('partnerId'), $this->getParam('searchPattern'), $this->getParam('startFrom'), $this->getParam('limit'));

            if($data != false){
                $records = $data[1];

                if (false !== $records && null != $records) {
                    $serviceList = $ratioIdList = array();

                    for ($i = 0; $i < count($records); $i++) {
                        $records[$i]['key'] = ('operator' == $records[$i]['type'] && 'NULL' != $records[$i]['operator']) ? $records[$i]['operator'] : $records[$i]['key'];

                        if (true !== array_key_exists($records[$i]['id'], $serviceList)) {
                            $serviceList[$records[$i]['id']] = array(
                                'id' => $records[$i]['id'],
                                'partnerId' => $records[$i]['partner_id'],
                                'shortCode' => $records[$i]['service_shortcode'],
                                'serviceName' => $records[$i]['service_name'],
                                'ratio' => array(
                                    0 => array(
                                        'startDate' => substr($records[$i]['start_time'], 0, 10),
                                        'endDate' => substr($records[$i]['end_time'], 0, 10),
                                        'ratio' => $records[$i]['ratio']
                                    )
                                ),
                                'filter' => array(
                                    0 => array(
                                        'filterId' => $records[$i]['filter_id'],
                                        'partnerServiceId' => $records[$i]['id'],
                                        'key' => $records[$i]['key'],
                                        'type' => $records[$i]['type']
                                    )
                                )
                            );

                            $ratioIdList[] = $records[$i]['ratio_id'];
                            $filterIdList[] = $records[$i]['filter_id'];
                        }
                        else {
                            if (true !== in_array($records[$i]['ratio_id'], $ratioIdList)) {
                                $serviceList[$records[$i]['id']]['ratio'][] = array(
                                    'startDate' => substr($records[$i]['start_time'], 0, 10),
                                    'endDate' => substr($records[$i]['end_time'], 0, 10),
                                    'ratio' => $records[$i]['ratio']
                                );
                                $ratioIdList[] = $records[$i]['ratio_id'];
                            }

                            if (true !== in_array($records[$i]['filter_id'], $filterIdList)) {
                                $serviceList[$records[$i]['id']]['filter'][] = array(
                                    'filterId' => $records[$i]['filter_id'],
                                    'partnerServiceId' => $records[$i]['id'],
                                    'key' => $records[$i]['key'],
                                    'type' => $records[$i]['type']
                                );
                                $filterIdList[] = $records[$i]['filter_id'];
                            }
                        }
                    }
                }
                else {
                    $serviceList = null;
                }

                $data[1] = $serviceList;
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getServicePermissionById() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'partnerServiceId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getServicePermissionById($this->getParam('partnerId'), $this->getParam('partnerServiceId'));

            write_log('debug','call view to return API response');
            if($data != false){
                $temp = $data[1];
                $buffer = $ratioIdList = $filterList = array();

                for ($i = 0; $i < count($temp); $i++) {
                    $buffer['partnerServiceId'] = $temp[$i]['id'];
                    $buffer['shortCode'] = $temp[$i]['service_shortcode'];
                    $buffer['service'] = $temp[$i]['service_name'];

                    if (true !== in_array($temp[$i]['ratio_id'], $ratioIdList)) {
                        $buffer['ratio'][] = array(
                            'startDate' => $temp[$i]['start_time'],
                            'endDate' => $temp[$i]['end_time'],
                            'ratio' => $temp[$i]['ratio']
                        );

                        $ratioIdList[] = $temp[$i]['ratio_id'];
                    }

                    if (true !== in_array($temp[$i]['type'] . $temp[$i]['key'], $filterList)) {
                        $buffer['filter'][] = array(
                            'param' => $temp[$i]['type'],
                            'value' => $temp[$i]['key']
                        );

                        $filterList[] = $temp[$i]['type'] . $temp[$i]['key'];
                    }
                }

                $data[1] = $buffer;
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function editServicePermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'partnerServiceId', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $partnerId = $this->getParam('partnerId');
            $partnerServiceId = $this->getParam('partnerServiceId');
            $data = json_decode($this->getParam('data'), 1);

            $shortCode = $serviceId = $startdate = $endDate = $ratio = $filterSubject = $filterOperator = $filterSid = $filterPrice = array();
            $insertedId = array();

            for ($i = 0; $i < count($data); $i++) {
                $shortCode[$i] = $data[$i]['shortCode'];
                $serviceId[$i] = $data[$i]['service'];

                for ($j = 0; $j < count($data[$i]['ratio']); $j++) {
                    $startDate[$i][] = $data[$i]['ratio'][$j]['startDate'];
                    $endDate[$i][] = $data[$i]['ratio'][$j]['endDate'];
                    $ratio[$i][] = $data[$i]['ratio'][$j]['ratio'];
                }

                for ($j = 0; $j < count($data[$i]['filter']); $j++) {
                    if (true === isset($data[$i]['filter'][$j])) {
                        switch ($data[$i]['filter'][$j]['param']) {
                        case 'subject':
                            $filterSubject[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'operator':
                            $filterOperator[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'sid':
                            $filterSid[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'price':
                            $filterPrice[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        }
                    }
                }
            }

            $this->partner_model->removeAllServiceRatio($partnerServiceId);
            $this->partner_model->removeAllServiceFilter($partnerServiceId);

            for ($i = 0; $i < count($shortCode); $i++) {
                for ($j = 0; $j < count($startDate[$i]); $j++) {
                    $ratioResult = $this->partner_model->addServiceRatio($partnerServiceId, $startDate[$i][$j], $endDate[$i][$j], $ratio[$i][$j]);
                    if (false == $ratioResult) {
                        foreach ($insertedId AS $key => $value) {
                            $this->partner_model->deleteService($value);
                        }

                        throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                    }
                }

                if (true === isset($filterSubject[$i])) {
                    for ($j = 0; $j < count($filterSubject[$i]); $j++) {
                        $subjectResult = $this->partner_model->addServiceFilter($partnerServiceId, $filterSubject[$i][$j], 'subject');
                        if (false == $subjectResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deleteService($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }
                }

                if (true === isset($filterOperator[$i])) {
                    for ($j = 0; $j < count($filterOperator[$i]); $j++) {
                        $operatorResult = $this->partner_model->addServiceFilter($partnerServiceId, $filterOperator[$i][$j], 'operator');
                        if (false == $operatorResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deleteService($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }
                }

                if (true === isset($filterSid[$i])) {
                    for ($j = 0; $j < count($filterSid[$i]); $j++) {
                        $sidResult = $this->partner_model->addServiceFilter($partnerServiceId, $filterSid[$i][$j], 'sid');
                        if (false == $sidResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deleteService($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }
                }

                if (true === isset($filterPrice[$i])) {
                    for ($j = 0; $j < count($filterPrice[$i]); $j++) {
                        $priceResult = $this->partner_model->addServiceFilter($partnerServiceId, $filterPrice[$i][$j], 'price');
                        if (false == $priceResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deleteService($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }
                }
            }
            echo respOK(array(0, true));
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function contentCodeExists() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'contentCode');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->contentCodeExists($this->getParam('contentCode'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function addContentPermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $partnerId = $this->getParam('partnerId');
            $data = json_decode($this->getParam('data'), 1);

            $pricingType = $mappingType = $contentId = $startdate = $endDate = $ratio = $filterService = $filterSid = $filterPrice = array();
            $insertedId = array();

            for ($i = 0; $i < count($data); $i++) {
                $pricingType[$i] = $data[$i]['pricingType'];
                $mappingType[$i] = $data[$i]['mappingType'];
                $contentId[$i] = $data[$i]['content'];

                for ($j = 0; $j < count($data[$i]['ratio']); $j++) {
                    $startDate[$i][] = $data[$i]['ratio'][$j]['startDate'];
                    $endDate[$i][] = $data[$i]['ratio'][$j]['endDate'];
                    $ratio[$i][] = $data[$i]['ratio'][$j]['ratio'];
                }

                for ($j = 0; $j < count($data[$i]['filter']); $j++) {
                    if (true === isset($data[$i]['filter'][$j])) {
                        switch ($data[$i]['filter'][$j]['param']) {
                        case 'service':
                            $filterService[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'sid':
                            $filterSid[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'price':
                            $filterPrice[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        }
                    }
                }
            }

            for ($i = 0; $i < count($mappingType); $i++) {
                $data = $this->partner_model->addContentPermission($partnerId, $mappingType[$i], $pricingType[$i], $contentId[$i]);

                if(false !== $data && null !== $data){
                    $insertedId[] = $partnerContentId = $data;

                    for ($j = 0; $j < count($startDate[$i]); $j++) {
                        $ratioResult = $this->partner_model->addContentRatio($partnerContentId, $startDate[$i][$j], $endDate[$i][$j], $ratio[$i][$j]);
                        if (false == $ratioResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deleteContent($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }

                    if (true === isset($filterService[$i])) {
                        for ($j = 0; $j < count($filterService[$i]); $j++) {
                            $serviceResult = $this->partner_model->addContentFilter($partnerContentId, $filterService[$i][$j], 'service');
                            if (false == $serviceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteContent($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterSid[$i])) {
                        for ($j = 0; $j < count($filterSid[$i]); $j++) {
                            $sidResult = $this->partner_model->addContentFilter($partnerContentId, $filterSid[$i][$j], 'sid');
                            if (false == $sidResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteContent($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterPrice[$i])) {
                        for ($j = 0; $j < count($filterPrice[$i]); $j++) {
                            $priceResult = $this->partner_model->addContentFilter($partnerContentId, $filterPrice[$i][$j], 'price');
                            if (false == $priceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteContent($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }
                }
                else{
                    throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                }
            }
            echo respOK($data);
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function editContentPermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'partnerContentId', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $partnerId = $this->getParam('partnerId');
            $partnerContentId = $this->getParam('partnerContentId');
            $data = json_decode($this->getParam('data'), 1);

            $pricingType = $mappingType = $contentId = $startdate = $endDate = $ratio = $filterService = $filterSid = $filterPrice = array();
            $insertedId = array();

            for ($i = 0; $i < count($data); $i++) {
                $pricingType[$i] = $data[$i]['pricingType'];
                $mappingType[$i] = $data[$i]['mappingType'];
                $contentId[$i] = $data[$i]['content'];

                for ($j = 0; $j < count($data[$i]['ratio']); $j++) {
                    $startDate[$i][] = $data[$i]['ratio'][$j]['startDate'];
                    $endDate[$i][] = $data[$i]['ratio'][$j]['endDate'];
                    $ratio[$i][] = $data[$i]['ratio'][$j]['ratio'];
                }

                for ($j = 0; $j < count($data[$i]['filter']); $j++) {
                    if (true === isset($data[$i]['filter'][$j])) {
                        switch ($data[$i]['filter'][$j]['param']) {
                        case 'service':
                            $filterService[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'sid':
                            $filterSid[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'price':
                            $filterPrice[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        }
                    }
                }
            }

            for ($i = 0; $i < count($mappingType); $i++) {
                $data = $this->partner_model->editContentPermission($partnerId, $partnerContentId, $mappingType[$i], $pricingType[$i], $contentId[$i]);

                if(false !== $data && null !== $data){
                    $insertedId[] = $partnerContentId;

                    $this->partner_model->removeAllContentRatio($partnerContentId);
                    $this->partner_model->removeAllContentFilter($partnerContentId);

                    for ($j = 0; $j < count($startDate[$i]); $j++) {
                        $ratioResult = $this->partner_model->addContentRatio($partnerContentId, $startDate[$i][$j], $endDate[$i][$j], $ratio[$i][$j]);
                        if (false == $ratioResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deleteContent($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }

                    if (true === isset($filterService[$i])) {
                        for ($j = 0; $j < count($filterService[$i]); $j++) {
                            $serviceResult = $this->partner_model->addContentFilter($partnerContentId, $filterService[$i][$j], 'service');
                            if (false == $serviceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteContent($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterSid[$i])) {
                        for ($j = 0; $j < count($filterSid[$i]); $j++) {
                            $sidResult = $this->partner_model->addContentFilter($partnerContentId, $filterSid[$i][$j], 'sid');
                            if (false == $sidResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteContent($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterPrice[$i])) {
                        for ($j = 0; $j < count($filterPrice[$i]); $j++) {
                            $priceResult = $this->partner_model->addContentFilter($partnerContentId, $filterPrice[$i][$j], 'price');
                            if (false == $priceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deleteContent($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }
                }
                else{
                    throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                }
            }
            echo respOK($data);
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getContentPermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array (
                'searchPattern' => $this->input->post('searchPattern', 1),
                'startFrom' => $this->input->post('startFrom', 1),
                'limit' => $this->input->post('limit', 1)
            );
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getContentPermission($this->getParam('partnerId'), $this->getParam('searchPattern'), $this->getParam('startFrom'), $this->getParam('limit'));

            write_log('debug','call view to return API response');
            if($data != false){
                $records = $data[1];

                if (false !== $records && null != $records) {
                    $contentList = $ratioIdList = $filterIdList = array();

                    for ($i = 0; $i < count($records); $i++) {
                        if (true !== array_key_exists($records[$i]['id'], $contentList)) {
                            $contentList[$records[$i]['id']] = array(
                                'id' => $records[$i]['id'],
                                'mappingType' => $records[$i]['mapping_type'],
                                'pricingType' => $records[$i]['pricing_type'],
                                'content' => $records[$i]['content_id'],
                                'owner' => $records[$i]['owner'],
                                'ratio' => array(
                                    0 => array(
                                        'startDate' => substr($records[$i]['start_time'], 0, 10),
                                        'endDate' => substr($records[$i]['end_time'], 0, 10),
                                        'ratio' => $records[$i]['ratio']
                                    )
                                ),
                                'filter' => array(
                                    0 => array(
                                        'filterId' => $records[$i]['filter_id'],
                                        'partnerContentId' => $records[$i]['partner_content_id'],
                                        'key' => $records[$i]['key'],
                                        'type' => $records[$i]['type']
                                    )
                                )
                            );

                            $ratioIdList[] = $records[$i]['ratio_id'];
                            $filterIdList[] = $records[$i]['filter_id'];
                        }
                        else {
                            if (true !== in_array($records[$i]['ratio_id'], $ratioIdList)) {
                                $contentList[$records[$i]['id']]['ratio'][] = array(
                                    'startDate' => substr($records[$i]['start_time'], 0, 10),
                                    'endDate' => substr($records[$i]['end_time'], 0, 10),
                                    'ratio' => $records[$i]['ratio']
                                );
                                $ratioIdList[] = $records[$i]['ratio_id'];
                            }

                            if (true !== in_array($records[$i]['filter_id'], $filterIdList)) {
                                $contentList[$records[$i]['id']]['filter'][] = array(
                                    'filterId' => $records[$i]['filter_id'],
                                    'partnerContentId' => $records[$i]['partner_content_id'],
                                    'key' => $records[$i]['key'],
                                    'type' => $records[$i]['type']
                                );
                                $filterIdList[] = $records[$i]['filter_id'];
                            }
                        }
                    }
                }
                else {
                    $contentList = null;
                }

                $data[1] = $contentList;
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getContentPermissionById() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'partnerContentId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getContentPermissionById($this->getParam('partnerId'), $this->getParam('partnerContentId'));

            write_log('debug','call view to return API response');
            if($data != false){
                $temp = $data[1];
                $buffer = $ratioIdList = $filterList = array();

                for ($i = 0; $i < count($temp); $i++) {
                    $buffer['partnerContentId'] = $temp[$i]['id'];
                    $buffer['pricingType'] = $temp[$i]['pricing_type'];
                    $buffer['mappingType'] = $temp[$i]['mapping_type'];
                    $buffer['content'] = $temp[$i]['content_id'];

                    if (true !== in_array($temp[$i]['ratio_id'], $ratioIdList)) {
                        $buffer['ratio'][] = array(
                            'startDate' => substr($temp[$i]['start_time'], 0, 10),
                            'endDate' => substr($temp[$i]['end_time'], 0, 10),
                            'ratio' => $temp[$i]['ratio']
                        );

                        $ratioIdList[] = $temp[$i]['ratio_id'];
                    }

                    if (true !== in_array($temp[$i]['type'] . $temp[$i]['key'], $filterList)) {
                        $buffer['filter'][] = array(
                            'param' => $temp[$i]['type'],
                            'value' => $temp[$i]['key']
                        );

                        $filterList[] = $temp[$i]['type'] . $temp[$i]['key'];
                    }
                }

                $data[1] = $buffer;
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getFreeDownloadReport() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','partnerId','period');
            $optionalParams = array ('contentType'=>NULL, 'searchPattern'=>NULL, 'startFrom'=>NULL, 'limit'=>NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');
            $this->splitPeriod('period');

            //call model to get data
            $data = $this->partner_model->getFreeDownloadReport(
                $this->getParam('partnerId'),
                $this->getParam('year'),
                $this->getParam('month'),
                $this->getParam('contentType'),
                $this->getParam('searchPattern'),
                $this->getParam('startFrom'),
                $this->getParam('limit')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getTextDownloadReport() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','partnerId','period');
            $optionalParams = array ('service'=>NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');

            //call model to get data
            $data = $this->partner_model->getTextDownloadReport(
                $this->getParam('partnerId'),
                $this->getParam('year'),
                $this->getParam('month'),
                $this->getParam('service')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function removeContentFilter() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'contentFilterId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->removeContentFilter($this->getParam('contentFilterId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function deleteContent() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'contentId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->deleteContent($this->getParam('contentId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function changePassword() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'newPassword');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->changePassword($this->getParam('partnerId'),$this->getParam('newPassword'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function currentPasswordMatches() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'oldPassword');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->currentPasswordMatches($this->getParam('partnerId'),$this->getParam('oldPassword'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPartnerService() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getPartnerService($this->getParam('partnerId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getContentOwner() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getContentOwner();

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function countPartnerContent() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->countPartnerContent($this->getParam('partnerId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function countPartnerService() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->countPartnerService($this->getParam('partnerId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function addPublisherPermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $partnerId = $this->getParam('partnerId');
            $data = json_decode($this->getParam('data'), 1);

            $mappingType = $contentId = $startdate = $endDate = $ratio = $filterService = $filterSid = $filterPrice = array();
            $insertedId = array();

            for ($i = 0; $i < count($data); $i++) {
                $mappingType[$i] = $data[$i]['mappingType'];
                $contentId[$i] = $data[$i]['content'];

                for ($j = 0; $j < count($data[$i]['ratio']); $j++) {
                    $startDate[$i][] = $data[$i]['ratio'][$j]['startDate'];
                    $endDate[$i][] = $data[$i]['ratio'][$j]['endDate'];
                    $ratio[$i][] = $data[$i]['ratio'][$j]['ratio'];
                }

                for ($j = 0; $j < count($data[$i]['filter']); $j++) {
                    if (true === isset($data[$i]['filter'][$j])) {
                        switch ($data[$i]['filter'][$j]['param']) {
                        case 'service':
                            $filterService[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'sid':
                            $filterSid[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'price':
                            $filterPrice[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        }
                    }
                }
            }

            for ($i = 0; $i < count($mappingType); $i++) {
                $data = $this->partner_model->addPublisherPermission($partnerId, $mappingType[$i], $contentId[$i]);

                if(false !== $data && null !== $data){
                    $insertedId[] = $partnerPublisherId = $data;

                    for ($j = 0; $j < count($startDate[$i]); $j++) {
                        $ratioResult = $this->partner_model->addPublisherRatio($partnerPublisherId, $startDate[$i][$j], $endDate[$i][$j], $ratio[$i][$j]);
                        if (false == $ratioResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deletePublisher($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }

                    if (true === isset($filterService[$i])) {
                        for ($j = 0; $j < count($filterService[$i]); $j++) {
                            $serviceResult = $this->partner_model->addPublisherFilter($partnerPublisherId, $filterService[$i][$j], 'service');
                            if (false == $serviceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deletePublisher($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterSid[$i])) {
                        for ($j = 0; $j < count($filterSid[$i]); $j++) {
                            $sidResult = $this->partner_model->addPublisherFilter($partnerPublisherId, $filterSid[$i][$j], 'sid');
                            if (false == $sidResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deletePublisher($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterPrice[$i])) {
                        for ($j = 0; $j < count($filterPrice[$i]); $j++) {
                            $priceResult = $this->partner_model->addPublisherFilter($partnerPublisherId, $filterPrice[$i][$j], 'price');
                            if (false == $priceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deletePublisher($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }
                }
                else{
                    throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                }
            }
            echo respOK($data);
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function editPublisherPermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'partnerPublisherId', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $partnerId = $this->getParam('partnerId');
            $partnerPublisherId = $this->getParam('partnerPublisherId');
            $data = json_decode($this->getParam('data'), 1);

            $mappingType = $contentId = $startdate = $endDate = $ratio = $filterService = $filterSid = $filterPrice = array();
            $insertedId = array();

            for ($i = 0; $i < count($data); $i++) {
                $mappingType[$i] = $data[$i]['mappingType'];
                $contentId[$i] = $data[$i]['content'];

                for ($j = 0; $j < count($data[$i]['ratio']); $j++) {
                    $startDate[$i][] = $data[$i]['ratio'][$j]['startDate'];
                    $endDate[$i][] = $data[$i]['ratio'][$j]['endDate'];
                    $ratio[$i][] = $data[$i]['ratio'][$j]['ratio'];
                }

                for ($j = 0; $j < count($data[$i]['filter']); $j++) {
                    if (true === isset($data[$i]['filter'][$j])) {
                        switch ($data[$i]['filter'][$j]['param']) {
                        case 'service':
                            $filterService[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'sid':
                            $filterSid[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        case 'price':
                            $filterPrice[$i][] = $data[$i]['filter'][$j]['value'];
                            break;
                        }
                    }
                }
            }

            for ($i = 0; $i < count($mappingType); $i++) {
                $data = $this->partner_model->editPublisherPermission($partnerId, $partnerPublisherId, $mappingType[$i], $contentId[$i]);

                if(false !== $data && null !== $data){
                    $insertedId[] = $partnerPublisherId;

                    $this->partner_model->removeAllPublisherRatio($partnerPublisherId);
                    $this->partner_model->removeAllPublisherFilter($partnerPublisherId);

                    for ($j = 0; $j < count($startDate[$i]); $j++) {
                        $ratioResult = $this->partner_model->addPublisherRatio($partnerPublisherId, $startDate[$i][$j], $endDate[$i][$j], $ratio[$i][$j]);
                        if (false == $ratioResult) {
                            foreach ($insertedId AS $key => $value) {
                                $this->partner_model->deletePublisher($value);
                            }

                            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                        }
                    }

                    if (true === isset($filterService[$i])) {
                        for ($j = 0; $j < count($filterService[$i]); $j++) {
                            $serviceResult = $this->partner_model->addPublisherFilter($partnerPublisherId, $filterService[$i][$j], 'service');
                            if (false == $serviceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deletePublisher($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterSid[$i])) {
                        for ($j = 0; $j < count($filterSid[$i]); $j++) {
                            $sidResult = $this->partner_model->addPublisherFilter($partnerPublisherId, $filterSid[$i][$j], 'sid');
                            if (false == $sidResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deletePublisher($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }

                    if (true === isset($filterPrice[$i])) {
                        for ($j = 0; $j < count($filterPrice[$i]); $j++) {
                            $priceResult = $this->partner_model->addPublisherFilter($partnerPublisherId, $filterPrice[$i][$j], 'price');
                            if (false == $priceResult) {
                                foreach ($insertedId AS $key => $value) {
                                    $this->partner_model->deletePublisher($value);
                                }

                                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                            }
                        }
                    }
                }
                else{
                    throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                }
            }
            echo respOK($data);
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPublisherPermission() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array (
                'searchPattern' => $this->input->post('searchPattern', 1),
                'startFrom' => $this->input->post('startFrom', 1),
                'limit' => $this->input->post('limit', 1)
            );
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getPublisherPermission($this->getParam('partnerId'), $this->getParam('searchPattern'), $this->getParam('startFrom'), $this->getParam('limit'));
            write_log('debug', 'CONTENT PERMISSION LIST :: ' . toString($data));

            write_log('debug','call view to return API response');
            if($data != false){
                $records = $data[1];

                if (false !== $records && null != $records) {
                    $publisherList = $ratioIdList = $filterIdList = array();

                    for ($i = 0; $i < count($records); $i++) {
                        if (true !== array_key_exists($records[$i]['id'], $publisherList)) {
                            $publisherList[$records[$i]['id']] = array(
                                'id' => $records[$i]['id'],
                                'mappingType' => $records[$i]['mapping_type'],
                                'content' => $records[$i]['content_id'],
                                'owner' => $records[$i]['owner'],
                                'ratio' => array(
                                    0 => array(
                                        'startDate' => substr($records[$i]['start_time'], 0, 10),
                                        'endDate' => substr($records[$i]['end_time'], 0, 10),
                                        'ratio' => $records[$i]['ratio']
                                    )
                                ),
                                'filter' => array(
                                    0 => array(
                                        'filterId' => $records[$i]['filter_id'],
                                        'partnerPublisherId' => $records[$i]['partner_publisher_id'],
                                        'key' => $records[$i]['key'],
                                        'type' => $records[$i]['type']
                                    )
                                )
                            );

                            $ratioIdList[] = $records[$i]['ratio_id'];
                            $filterIdList[] = $records[$i]['filter_id'];
                        }
                        else {
                            if (true !== in_array($records[$i]['ratio_id'], $ratioIdList)) {
                                $publisherList[$records[$i]['id']]['ratio'][] = array(
                                    'startDate' => substr($records[$i]['start_time'], 0, 10),
                                    'endDate' => substr($records[$i]['end_time'], 0, 10),
                                    'ratio' => $records[$i]['ratio']
                                );
                                $ratioIdList[] = $records[$i]['ratio_id'];
                            }

                            if (true !== in_array($records[$i]['filter_id'], $filterIdList)) {
                                $publisherList[$records[$i]['id']]['filter'][] = array(
                                    'filterId' => $records[$i]['filter_id'],
                                    'partnerPublisherId' => $records[$i]['partner_publisher_id'],
                                    'key' => $records[$i]['key'],
                                    'type' => $records[$i]['type']
                                );
                                $filterIdList[] = $records[$i]['filter_id'];
                            }
                        }
                    }
                }
                else {
                    $publisherList = null;
                }

                $data[1] = $publisherList;
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPublisherPermissionById() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'partnerPublisherId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getPublisherPermissionById($this->getParam('partnerId'), $this->getParam('partnerPublisherId'));

            write_log('debug','call view to return API response');
            if($data != false){
                $temp = $data[1];
                $buffer = $ratioIdList = $filterList = array();

                for ($i = 0; $i < count($temp); $i++) {
                    $buffer['partnerPublisherId'] = $temp[$i]['id'];
                    $buffer['mappingType'] = $temp[$i]['mapping_type'];
                    $buffer['content'] = $temp[$i]['content_id'];

                    if (true !== in_array($temp[$i]['ratio_id'], $ratioIdList)) {
                        $buffer['ratio'][] = array(
                            'startDate' => $temp[$i]['start_time'],
                            'endDate' => $temp[$i]['end_time'],
                            'ratio' => $temp[$i]['ratio']
                        );

                        $ratioIdList[] = $temp[$i]['ratio_id'];
                    }

                    if (true !== in_array($temp[$i]['type'] . $temp[$i]['key'], $filterList)) {
                        $buffer['filter'][] = array(
                            'param' => $temp[$i]['type'],
                            'value' => $temp[$i]['key']
                        );

                        $filterList[] = $temp[$i]['type'] . $temp[$i]['key'];
                    }
                }

                $data[1] = $buffer;
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function removePublisherFilter() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'publisherFilterId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->removePublisherFilter($this->getParam('publisherFilterId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function deletePublisher() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'publisherId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->deletePublisher($this->getParam('publisherId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function removeAccess() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->removeAccess($this->getParam('partnerId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function grantAccess() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->grantAccess($this->getParam('partnerId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPartnerNameById() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'id');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getPartnerNameById($this->getParam('id'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPublisherDownloadReport() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','partnerId','period');
            $optionalParams = array ('contentType'=>NULL, 'searchPattern'=>NULL, 'startFrom'=>NULL, 'limit'=>NULL, 'orderField'=>NULL, 'order'=>NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');
            $this->splitPeriod('period');

            //call model to get data
            $data = $this->partner_model->getPublisherDownloadReport(
                $this->getParam('partnerId'),
                $this->getParam('year'),
                $this->getParam('month'),
                $this->getParam('contentType'),
                $this->getParam('searchPattern'),
                $this->getParam('startFrom'),
                $this->getParam('limit'),
                $this->getParam('orderField'),
                $this->getParam('order')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function addPriceMapping() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $partnerId = $this->getParam('partnerId');
            $data = json_decode($this->getParam('data'), 1);

            $contentCode = $price = array();
            $insertedId = array();

            for ($i = 0; $i < count($data); $i++) {
                $contentCode[$i] = $data[$i]['contentCode'];
                $price[$i] = $data[$i]['price'];
            }

            for ($i = 0; $i < count($contentCode); $i++) {
                $dataResult = $this->partner_model->addPriceMapping($partnerId, $contentCode[$i], $price[$i]);

                if(false === $data || null === $data){
                    throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                }
            }
            echo respOK($dataResult);
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPriceMapping() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'startFrom', 'limit');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getPriceMapping($this->getParam('partnerId'), $this->getParam('searchPattern'), $this->getParam('startFrom'), $this->getParam('limit'));

            if($data != false){
                $records = $data[1];

                if (false !== $records && null != $records) {
                    $priceMappingList = array();

                    for ($i = 0; $i < count($records); $i++) {
                        if (true !== array_key_exists($records[$i]['id'], $priceMappingList)) {
                            $priceMappingList[$records[$i]['id']] = array(
                                'id' => $records[$i]['id'],
                                'contentCode' => $records[$i]['content_code'],
                                'price' => $records[$i]['price']
                            );
                        }
                    }
                }
                else {
                    $priceMappingList = null;
                }

                $data[1] = $priceMappingList;
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function deletePriceMapping() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'priceMappingId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->deletePriceMapping($this->getParam('partnerId'), $this->getParam('priceMappingId'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPriceMappingById() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'priceMappingId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getPriceMappingById($this->getParam('partnerId'), $this->getParam('priceMappingId'));

            write_log('debug','call view to return API response');
            if($data != false){
                $buffer = array(
                    'id' => $data[1][0]['id'],
                    'contentCode' => $data[1][0]['content_code'],
                    'price' => $data[1][0]['price']
                );

                $data[1] = $buffer;
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function editPriceMapping() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'priceMappingId', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $partnerId = $this->getParam('partnerId');
            $priceMappingId = $this->getParam('priceMappingId');
            $data = json_decode($this->getParam('data'), 1);

            $contentCode = $price = array();
            $insertedId = array();

            for ($i = 0; $i < count($data); $i++) {
                $contentCode[$i] = $data[$i]['contentCode'];
                $price[$i] = $data[$i]['price'];
            }

            for ($i = 0; $i < count($contentCode); $i++) {
                $dataResult = $this->partner_model->editPriceMapping($partnerId, $priceMappingId, $contentCode[$i], $price[$i]);

                if(false === $data || null === $data){
                    throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                }
            }
            echo respOK($dataResult);
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function addDataReconciliation() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','shortCode','operator','month','year','data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->jsonToArray('data');

            //call model to get data
            $data = $this->partner_model->addDataReconciliation(
                $this->getParam('shortCode'),
                $this->getParam('operator'),
                $this->getParam('month'),
                $this->getParam('year'),
                $this->getParam('data')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function getDataReconciliation() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password');
            $optionalParams = array ('searchPattern'=>NULL, 'startFrom'=>NULL, 'limit'=>NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
			$this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');

            //call model to get data
            $data = $this->partner_model->getDataReconciliation(
                $this->getParam('searchPattern'),
                $this->getParam('startFrom'),
                $this->getParam('limit')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function getDataReconciliationById() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','id');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
			$this->numericTypeCheck('id');

            //call model to get data
            $data = $this->partner_model->getDataReconciliationById(
                $this->getParam('id')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function updateDataReconciliation() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','id','data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('id');
            $this->jsonToArray('data');

            //call model to get data
            $data = $this->partner_model->updateDataReconciliation(
                $this->getParam('id'),
                $this->getParam('data')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function deleteDataReconciliation() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','id');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
			$this->numericTypeCheck('id');

            //call model to get data
            $data = $this->partner_model->deleteDataReconciliation(
                $this->getParam('id')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function getInternalReconciliation() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','shortCode','operator','month','year');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            $data = $this->partner_model->getInternalReconciliation(
            	$this->getParam('shortCode'),
            	$this->getParam('operator'),
                $this->getParam('month'),
                $this->getParam('year')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function addOperatorSharing() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username', 'password', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $data = json_decode($this->getParam('data'), 1);
            $shortCode = $operator = $sharing = array();
            $hasError = false;

            write_log('debug', ' DATASSSSSSSSS :: ' . toString($data));

            for ($i = 0; $i < count($data); $i++) {
                $temp = $this->partner_model->addOperatorSharing($data[$i]['shortCode'], $data[$i]['operator'], $data[$i]['sharing']);

                if (false === $temp) {
                    $hasError = true;
                    $i = count($data);
                }
            }

            if (false !== $hasError) {
                die(json_encode(array(
                    'status' => 'OK',
                    'message' => 'There was a problem while inserting operator sharing',
                    'data' => ''
                )));
            }

            echo respOK($temp);
            exit;
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function updateOperatorSharing() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username', 'password', 'id', 'data');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            $id = json_decode($this->getParam('id'), 1);
            $data = json_decode($this->getParam('data'), 1);
            $shortCode = $operator = $sharing = array();
            $hasError = false;

            for ($i = 0; $i < count($data); $i++) {
                $data = $this->partner_model->updateOperatorSharing($id, $data[$i]['shortCode'], $data[$i]['operator'], $data[$i]['sharing']);

                if (false === $data) {
                    $hasError = true;
                    $i = count($data);
                }
            }

            if (false !== $hasError) {
                die(json_encode(array(
                    'status' => 'OK',
                    'message' => 'There was a problem while updating operator sharing',
                    'data' => ''
                )));
            }

            echo respOK($data);
            exit;
        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getOperatorSharing() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password');
            $optionalParams = array (
                'searchPattern' => $this->input->post('searchPattern', 1),
                'startFrom' => $this->input->post('startFrom', 1),
                'limit' => $this->input->post('limit', 1)
            );
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');

            //call model to get data
            $data = $this->partner_model->getOperatorSharing($this->getParam('searchPattern'), $this->getParam('startFrom'), $this->getParam('limit'));
            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getOperatorSharingById() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password', 'id');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            $data = $this->partner_model->getOperatorSharingById($this->getParam('id'));
            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function deleteOperatorSharing() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','id');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('id');

            //call model to get data
            $data = $this->partner_model->deleteOperatorSharing($this->getParam('id'));
            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function getPremiumDownloadOperatorReport() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','partnerId','period','shortCode');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');

            //call model to get data
            $data = $this->partner_model->getPremiumDownloadOperatorReport(
                $this->getParam('partnerId'),
                $this->getParam('year'),
                $this->getParam('month'),
                $this->getParam('shortCode')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function getFreeDownloadOperatorReport() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','partnerId','period','shortCode');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');

            //call model to get data
            $data = $this->partner_model->getFreeDownloadOperatorReport(
                $this->getParam('partnerId'),
                $this->getParam('year'),
                $this->getParam('month'),
                $this->getParam('shortCode')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getTextDownloadOperatorReport() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','partnerId','period','shortCode');
            $optionalParams = array ('service'=>NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');

            //call model to get data
            $data = $this->partner_model->getTextDownloadOperatorReport(
                $this->getParam('partnerId'),
                $this->getParam('year'),
                $this->getParam('month'),
                $this->getParam('service'),
                $this->getParam('shortCode')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function getDataReconciliationByShortCodeAndOperator() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','shortCode','operator');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
			$this->numericTypeCheck('id');

            //call model to get data
            $data = $this->partner_model->getDataReconciliationByShortCodeAndOperator(
                $this->getParam('shortCode'),
                $this->getParam('operator')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getOperatorSharingByShortCodeAndOperator() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password', 'shortCode', 'operator');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            $data = $this->partner_model->getOperatorSharingByShortCodeAndOperator($this->getParam('shortCode'), $this->getParam('operator'));
            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPartnerSharingById() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'id');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getPartnerSharingById($this->getParam('id'));

            write_log('debug','call view to return API response');
            if($data != false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

	public function getPublisherDownloadOperatorReport() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array ('username','password','partnerId','period','shortCode');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');

            //call model to get data
            $data = $this->partner_model->getPublisherDownloadOperatorReport(
                $this->getParam('partnerId'),
                $this->getParam('year'),
                $this->getParam('month'),
                $this->getParam('shortCode')
            );

            echo respOK($data);

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getPartnerPrivilegeList() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->getPartnerPrivilegeList($this->getParam('partnerId'));

            write_log('debug','call view to return API response');
            if($data !== false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function partnerUpdatePrivilegeList() {
        write_log('info','METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug','Build & checking parameter');
            $mandatoryParams = array ('username', 'password', 'partnerId', 'privilegeList');
            $optionalParams = array ();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug','call model to get data');
            $data = $this->partner_model->partnerUpdatePrivilegeList($this->getParam('partnerId'), $this->getParam('privilegeList'));

            write_log('debug','call view to return API response');
            if($data !== false){
                echo respOK($data);
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }

        } catch ( Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }
}

