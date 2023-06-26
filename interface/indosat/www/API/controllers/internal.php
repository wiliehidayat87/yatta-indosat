<?php

class Internal extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        die('where are you going?');
    }

    public function getShortCode() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        //try {
        //building
        write_log('debug', 'Build & checking parameter');
        $mandatoryParams = array('username', 'password');
        $optionalParams = array('searchPattern' => NULL, 'orderField' => NULL, 'order' => NULL, 'startFrom' => NULL, 'limit' => NULL);
        /*$this->createParameters($mandatoryParams, $optionalParams);
        $this->numericTypeCheck('limit');
        $this->numericTypeCheck('startFrom');
        $this->orderTypeCheck('order');*/

        //call model to get data
        write_log('debug', 'call model to get data');
        $data = $this->internal_model->getShortCode(
                $this->getParam('searchPattern'), $this->getParam('orderField'), $this->getParam('order')
                , $this->getParam('startFrom'), $this->getParam('limit'));

        write_log('debug', 'call view to return API response');
        if ($data != false) {
            echo respOK($data);
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
        }

        /* 	} catch ( Exception $e) {
          echo respNOK($e->getMessage());
          exit;
          } */
    }

    public function getOperator() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'shortCode');
            $optionalParams = array('searchPattern' => NULL, 'orderField' => NULL, 'order' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            /*$this->createParameters($mandatoryParams, $optionalParams);
            //checking
//			$this->securityCheckArray('shortCode');
            $this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');
            $this->orderTypeCheck('order');*/

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getOperator($this->getParam('shortCode'), $this->getParam('searchPattern'), $this->getParam('orderField'), $this->getParam('order')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getOperatorReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'period', 'shortCode');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            //checking
            $this->splitPeriod('period');

            if ($this->getParam('operatorId') != NULL) {
                $this->securityCheckArray('operatorId');
                $this->splitOperatorId('operatorId');
            }

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getOperatorReport($this->getParam('year')
                    , $this->getParam('month'), $this->getParam('shortCode'), $this->getParam('operatorId'));

//                        error_log(print_r($data,true), 3, "/tmp/l7.log");

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getOperatorChargingReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'period', 'operatorId', 'type');
            $optionalParams = array('shortCode');
            $this->createParameters($mandatoryParams, $optionalParams);
            //checking
            $this->splitPeriod('period');
            $this->messageTypeCheck('type');
//                        error_log($this->getParam('type'), 3, "/tmp/l7.log");
            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getOperatorChargingReport(
                    $this->getParam('year'), $this->getParam('month'), $this->getParam('operatorId'), $this->getParam('type'), $this->getParam('shortCode')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getSubjectReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'period');
            $optionalParams = array('shortCode' => NULL, 'operatorId' => NULL, 'searchPattern' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            $this->splitPeriod('period');

            //checking
            $this->securityCheckArray('operatorId');
            $this->securityCheckArray('shortCode');

            //call model to get data
            $data = $this->internal_model->getSubjectReport($this->getParam('shortCode')
                    , $this->getParam('operatorId'), $this->getParam('year'), $this->getParam('month')
                    , $this->getParam('searchPattern')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getSubjectOperatorReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'period', 'subject');
            $optionalParams = array('shortCode' => NULL, 'operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            $this->splitPeriod('period');

            //checking
            $this->securityCheckArray('operatorId');
            $this->securityCheckArray('shortCode');

            //call model to get data
            $data = $this->internal_model->getSubjectOperatorReport($this->getParam('subject'), $this->getParam('shortCode')
                    , $this->getParam('operatorId'), $this->getParam('year'), $this->getParam('month'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getServiceReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'shortCode', 'period');
            $optionalParams = array('operatorId' => NULL, 'searchPattern' => NULL, 'orderField' => NULL, 'order' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');
            $this->securityCheckArray('operatorId');
            $this->numericTypeCheck('startFrom');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getServiceReport($this->getParam('shortCode'), $this->getParam('year')
                    , $this->getParam('month'), $this->getParam('operatorId')
                    , $this->getParam('searchPattern')
                    , $this->getParam('orderField'), $this->getParam('order')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getServiceOperatorReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'shortCode', 'period', 'service');
            $optionalParams = array('operatorId' => NULL, 'searchPattern' => NULL, 'orderField' => NULL, 'order' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');
            $this->securityCheckArray('operatorId');
            $this->numericTypeCheck('startFrom');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getServiceOperatorReport($this->getParam('shortCode'), $this->getParam('year')
                    , $this->getParam('month'), $this->getParam('service')
                    , $this->getParam('operatorId'), $this->getParam('searchPattern')
                    , $this->getParam('orderField'), $this->getParam('order')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getServiceOperatorSubjectReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'shortCode', 'period', 'service', 'operatorId');
            $optionalParams = array('searchPattern' => NULL, 'orderField' => NULL, 'order' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');
            $this->numericTypeCheck('startFrom');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getServiceOperatorSubjectReport($this->getParam('shortCode'), $this->getParam('year')
                    , $this->getParam('month'), $this->getParam('service')
                    , $this->getParam('operatorId'), $this->getParam('searchPattern')
                    , $this->getParam('orderField'), $this->getParam('order')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getService() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'shortCode');
            $optionalParams = array('searchPattern' => NULL, 'orderField' => NULL, 'order' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            //checking
            $this->securityCheckArray('shortCode');
            $this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');
            $this->orderTypeCheck('order');

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getService($this->getParam('shortCode')
                    , $this->getParam('searchPattern'), $this->getParam('orderField'), $this->getParam('order')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getClosereasonReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'shortCode', 'period');
            $optionalParams = array('operatorId' => NULL, 'service' => NULL, 'sorting' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            if (!is_numeric($this->getParam('period'))) {
                $this->splitPeriod('period');
                $year = $this->getParam('year');
                $month = $this->getParam('month');
                $dateRangeStart = "$year-$month-01";
                if (date("Y") == $year && (int) date("m") == (int) $month) {
                    $maxDate = (int) date("d") - 1;
                } else {
                    $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                }
                $dateRangeEnd = "$year-$month-$maxDate";
            } else {
                $period = $this->getParam('period');
                $date = new DateTime('now');

                date_sub($date, new DateInterval("P" . ($period - 1) . "D"));
                $dateRangeStart = $date->format("Y-m-d");
                $dateRangeEnd = date('Y-m-d');
            }
            $this->securityCheckArray('service');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getClosereasonReport(
                    $this->getParam('shortCode'), $dateRangeStart
                    , $dateRangeEnd, $this->getParam('service')
                    , $this->getParam('operatorId'), $this->getParam('sorting')
                    , $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getClosereasonServiceReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'shortCode', 'period', 'closereason');
            $optionalParams = array('operatorId' => NULL, 'service' => NULL, 'sorting' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            if (!is_numeric($this->getParam('period'))) {
                $this->splitPeriod('period');
                $year = $this->getParam('year');
                $month = $this->getParam('month');
                $dateRangeStart = "$year-$month-01";
                if (date("Y") == $year && (int) date("m") == (int) $month) {
                    $maxDate = (int) date("d");
                } else {
                    $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                }
                $dateRangeEnd = "$year-$month-$maxDate";
            } else {
                $period = $this->getParam('period');
                $date = new DateTime('now');
                date_sub($date, new DateInterval("P" . ($period - 1) . "D"));
                $dateRangeStart = $date->format("Y-m-d");
                $dateRangeEnd = date('Y-m-d');
            }
            $this->securityCheckArray('service');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getClosereasonServiceReport(
                    $this->getParam('shortCode'), $dateRangeStart
                    , $dateRangeEnd
                    , $this->getParam('closereason'), $this->getParam('service')
                    , $this->getParam('operatorId'), $this->getParam('sorting')
                    , $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getMedia() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password');
            $optionalParams = array('searchPattern' => NULL, 'orderField' => NULL, 'order' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');
            $this->orderTypeCheck('order');

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getMedia(
                    $this->getParam('searchPattern'), $this->getParam('orderField'), $this->getParam('order')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getSubject() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password');
            $optionalParams = array('searchPattern' => NULL, 'orderField' => NULL, 'order' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('limit');
            $this->numericTypeCheck('startFrom');
            $this->orderTypeCheck('order');

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getSubject(
                    $this->getParam('searchPattern'), $this->getParam('orderField'), $this->getParam('order')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getSubscriberReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'period', 'shortCode');
            $optionalParams = array('operatorId' => NULL, 'service' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');
            $this->securityCheckArray('operatorId');
            $this->numericTypeCheck('startFrom');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getSubscriberReport($this->getParam('shortCode'), $this->getParam('year')
                    , $this->getParam('month'), $this->getParam('operatorId')
                    , $this->getParam('service')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getUserReportxxx() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'period', 'shortCode');
            $optionalParams = array('operatorId' => NULL, 'service' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->splitPeriod('period');
            $this->securityCheckArray('operatorId');
            $this->numericTypeCheck('startFrom');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getUserReport($this->getParam('shortCode'), $this->getParam('year')
                    , $this->getParam('month'), $this->getParam('operatorId')
                    , $this->getParam('service')
                    , $this->getParam('startFrom'), $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getUserReport() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'startFrom', 'limit');
            $optionalParams = array('adn' => NULL, 'service' => NULL, 'operatorId' => NULL, 'date' => NULL, 'channel' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('startFrom');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getUserReport($this->getParam('username'), $this->getParam('password'), $this->getParam('adn'), $this->getParam('operatorId'), $this->getParam('service'), $this->getParam('date')
                    , $this->getParam('channel'), $this->getParam('startFrom'), $this->getParam('limit'));

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getContentOwner() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password');
            $optionalParams = array('searchPattern' => NULL, 'startFrom' => NULL, 'limit' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            $this->numericTypeCheck('startFrom');
            $this->numericTypeCheck('limit');

            //call model to get data
            $data = $this->internal_model->getContentOwner(
                    $this->getParam('searchPattern'), $this->getParam('startFrom'), $this->getParam('limit')
            );

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDownloadReportDaily() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'year', 'month');
            $optionalParams = array('contentOwner' => NULL, 'contentType' => NULL, 'operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            $data = $this->internal_model->getDownloadReportDaily(
                    $this->getParam('year'), $this->getParam('month'), $this->getParam('operatorId'), $this->getParam('contentOwner'), $this->getParam('contentType')
            );

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDownloadReportMonthly() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            $mandatoryParams = array('username', 'password', 'year');
            $optionalParams = array('contentOwner' => NULL, 'contentType' => NULL, 'operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            $data = $this->internal_model->getDownloadReportMonthly(
                    $this->getParam('year'), $this->getParam('operatorId'), $this->getParam('contentOwner'), $this->getParam('contentType')
            );

            echo respOK($data);
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getTrafficReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'period', 'shortCode');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            //checking
            $this->splitPeriod('period');
            $this->securityCheckArray('operatorId');
            $this->securityCheckArray('serviceId');
            $this->securityCheckArray('shorCode');

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getTrafficReportChart($this->getParam('year')
                    , $this->getParam('month'), $this->getParam('shortCode'), $this->getParam('service'), $this->getParam('operatorId'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getTrafficChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('top' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getTrafficChart($this->getParam('startDate'), $this->getParam('endDate')
                    , $this->getParam('top'), $this->getParam('grouping'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getRevenueReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'period', 'service', 'shortCode');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //checking
            $this->splitPeriod('period');
            $this->securityCheckArray('operatorId');
            $this->securityCheckArray('service');
            $this->securityCheckArray('shortCode');

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getRevenueReportChart($this->getParam('year'), $this->getParam('month')
                    , $this->getParam('shortCode'), $this->getParam('service'), $this->getParam('operatorId'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getRevenueChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'top' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getRevenueChart($this->getParam('startDate'), $this->getParam('endDate')
                    , $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('top'), $this->getParam('grouping'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }
    
    public function getRevenueChartl7() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'top' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getRevenueChartl7($this->getParam('startDate'), $this->getParam('endDate')
                    , $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('top'), $this->getParam('grouping'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getUserReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('shortCode' => NULL, 'operatorId' => NULL, 'top' => NULL, 'isPercentage' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getUserReportChart($this->getParam('startDate'), $this->getParam('endDate'), $this->getParam('shortCode')
                    , $this->getParam('operatorId')
                    , $this->getParam('top'), $this->getParam('grouping'), $this->getParam('isPercentage'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDownloadContentReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate');
            $optionalParams = array('operatorId' => NULL, 'contentType' => NULL, 'top' => NULL, 'isPercentage' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDownloadContentReportChart($this->getParam('startDate'), $this->getParam('endDate'), $this->getParam('operatorId'), $this->getParam('contentType'), $this->getParam('top'), $this->getParam('isPercentage'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getCloseReasonReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'service' => NULL, 'sorting' => NULL, 'top' => NULL, 'isPercentage' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getCloseReasonReportChart($this->getParam('startDate'), $this->getParam('endDate'), $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('service'), $this->getParam('sorting'), $this->getParam('top'), $this->getParam('isPercentage'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyRevenueReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'top' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyRevenueReportChart($this->getParam('startDate'), $this->getParam('endDate')
                    , $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('top'), $this->getParam('grouping'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
                exit;
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }
    
    public function getDailyRevenueReportChartl7() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'top' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyRevenueReportChartl7($this->getParam('startDate'), $this->getParam('endDate')
                    , $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('top'), $this->getParam('grouping'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
                exit;
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyTrafficReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('shortcode' => NULL, 'top' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyTrafficReportChart($this->getParam('startDate'), $this->getParam('endDate')
                    , $this->getParam('shortcode'), $this->getParam('top'), $this->getParam('grouping'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyRevenuePercentageReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'top' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyRevenuePercentageReportChart($this->getParam('startDate'), $this->getParam('endDate')
                    , $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('top'), $this->getParam('grouping'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailySubcriberSubtotalReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'service' => NULL, 'top' => NULL, 'isPercentage' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailySubcriberSubtotalReportChart($this->getParam('startDate'), $this->getParam('endDate')
                    , $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('service')
                    , $this->getParam('top'), $this->getParam('grouping'), $this->getParam('isPercentage'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailySubcriberRegUnregReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'service' => NULL, 'top' => NULL, 'isPercentage' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailySubcriberRegUnregReportChart(
                    $this->getParam('startDate'), $this->getParam('endDate'), $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('service'), $this->getParam('top'), $this->getParam('grouping'), $this->getParam('isPercentage')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyUserReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate', 'grouping');
            $optionalParams = array('shortCode' => NULL, 'operatorId' => NULL, 'top' => NULL, 'isPercentage' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyUserReportChart($this->getParam('startDate'), $this->getParam('endDate'), $this->getParam('shortCode')
                    , $this->getParam('operatorId')
                    , $this->getParam('top'), $this->getParam('grouping'), $this->getParam('isPercentage'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyDownloadContentReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate');
            $optionalParams = array('operatorId' => NULL, 'contentOwner' => NULL, 'contentType' => NULL, 'top' => NULL, 'isPercentage' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyDownloadContentReportChart($this->getParam('startDate'), $this->getParam('endDate'), $this->getParam('operatorId'), $this->getParam('contentOwner'), $this->getParam('contentType'), $this->getParam('top'), $this->getParam('isPercentage'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyDownloadContentPercentageChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year', 'month');
            $optionalParams = array('operatorId' => NULL, 'contentOwner' => NULL, 'contentType' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyDownloadContentPercentageChart(
                    $this->getParam('month'), $this->getParam('year'), $this->getParam('operatorId'), $this->getParam('contentOwner'), $this->getParam('contentType')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getMonthlyDownloadContentPercentageChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year');
            $optionalParams = array('operatorId' => NULL, 'contentOwner' => NULL, 'contentType' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getMonthlyDownloadContentPercentageChart(
                    $this->getParam('year'), $this->getParam('operatorId'), $this->getParam('contentOwner'), $this->getParam('contentType')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyTrafficServicePercentageChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year', 'month', 'shortcode');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyTrafficServicePercentageChart(
                    $this->getParam('month'), $this->getParam('year'), $this->getParam('shortcode'), $this->getParam('operatorId')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }
    
    public function getDailyTrafficServicePercentageChartl7() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year', 'month', 'shortcode');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyTrafficServicePercentageChartl7(
                    $this->getParam('month'), $this->getParam('year'), $this->getParam('shortcode'), $this->getParam('operatorId')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyRevenuePercentageChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year', 'month', 'shortcode');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyRevenuePercentageChart(
                    $this->getParam('month'), $this->getParam('year'), $this->getParam('shortcode'), $this->getParam('operatorId')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyUserPercentageChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year', 'month', 'shortcode');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyUserPercentageChart(
                    $this->getParam('month'), $this->getParam('year'), $this->getParam('shortcode'), $this->getParam('operatorId')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailySubscriberPercentageChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year', 'month', 'shortcode');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailySubscriberPercentageChart(
                    $this->getParam('month'), $this->getParam('year'), $this->getParam('shortcode'), $this->getParam('operatorId')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyCloseReasonPercentageChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'period', 'shortcode');
            $optionalParams = array('operatorId' => NULL, 'service' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyCloseReasonPercentageChart(
                    $this->getParam('period'), $this->getParam('shortcode'), $this->getParam('operatorId'), $this->getParam('service')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getMonthlyDownloadContentReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year');
            $optionalParams = array('operatorId' => NULL, 'contentOwner' => NULL, 'contentType' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getMonthlyDownloadContentReportChart(
                    $this->getParam('year'), $this->getParam('operatorId'), $this->getParam('contentOwner'), $this->getParam('contentType')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getMonthlyContentOwnerReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'year');
            $optionalParams = array('operatorId' => NULL, 'contentType' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getMonthlyContentOwnerReportChart(
                    $this->getParam('year'), $this->getParam('operatorId'), $this->getParam('contentType')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getTopServiceHighSubscriber() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'period');
            $optionalParams = array('shortcode' => NULL, 'operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getTopServiceHighSubscriber(
                    $this->getParam('period'), $this->getParam('shortcode'), $this->getParam('operatorId')
            );

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDailyCloseReasonReportChart() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'startDate', 'endDate');
            $optionalParams = array('operatorId' => NULL, 'shortcode' => NULL, 'service' => NULL, 'sorting' => NULL, 'top' => NULL, 'isPercentage' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDailyCloseReasonReportChart($this->getParam('startDate'), $this->getParam('endDate'), $this->getParam('operatorId'), $this->getParam('shortcode'), $this->getParam('service'), $this->getParam('sorting'), $this->getParam('top'), $this->getParam('isPercentage'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getAllDashboard() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'userId');
            $optionalParams = array();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getAllDashboard($this->getParam('userId'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getDashboard() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'userId', 'index');
            $optionalParams = array();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getDashboard($this->getParam('userId'), $this->getParam('index'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function addDashboard() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'userId', 'param');
            $optionalParams = array();
            $this->createParameters($mandatoryParams, $optionalParams);

            $index = 1;
            $response = $this->internal_model->getAllDashboard($this->getParam('userId'));
            if ($response != false) {
                $index = count($response) + 1;
            }

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->addDashboard($this->getParam('userId'), $index, $this->getParam('param'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function modifyDashboard() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'userId', 'param', 'id');
            $optionalParams = array();
            $this->createParameters($mandatoryParams, $optionalParams);

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->modifyDashboard($this->getParam('param'), $this->getParam('id'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function swapDashboard() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'userId', 'before', 'after');
            $optionalParams = array();
            $this->createParameters($mandatoryParams, $optionalParams);

            $idBefore = $this->internal_model->getDashboardIdByIndex($this->getParam('userId'), $this->getParam('before'));

            if ($idBefore != true) {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                exit;
            }

            $idAfter = $this->internal_model->getDashboardIdByIndex($this->getParam('userId'), $this->getParam('after'));

            if ($idAfter != true) {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                exit;
            }

            //call model to get data
            write_log('debug', 'call model to update data');
            $response = $this->internal_model->swapDashboard($idBefore, $this->getParam('after'));

            if ($response != true) {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
                exit;
            }

            $data = $this->internal_model->swapDashboard($idAfter, $this->getParam('before'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function deleteDashboard() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'userId', 'id');
            $optionalParams = array();
            $this->createParameters($mandatoryParams, $optionalParams);

            $data = $this->internal_model->deleteDashboard($this->getParam('id'));

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                $response = $this->internal_model->getAllDashboard($this->getParam('userId'));
                if ($response != false) {
                    $index = 1;
                    foreach ($response as $i => $v) {
                        $reIndex = $this->internal_model->swapDashboard($v['id'], $index);
                        if ($reIndex != false) {
                            $index++;
                        }
                    }
                }
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

    public function getOperatorReportL7() {
        write_log('info', 'METHOD CALL: ' . __METHOD__);
        try {
            //building
            write_log('debug', 'Build & checking parameter');
            $mandatoryParams = array('username', 'password', 'period', 'shortCode', 'type');
            $optionalParams = array('operatorId' => NULL);
            $this->createParameters($mandatoryParams, $optionalParams);
            //checking
            $this->splitPeriod('period');

            if ($this->getParam('operatorId') != NULL) {
                $this->securityCheckArray('operatorId');
                $this->splitOperatorId('operatorId');
            }

            //call model to get data
            write_log('debug', 'call model to get data');
            $data = $this->internal_model->getOperatorReportL7($this->getParam('year')
                    , $this->getParam('month'), $this->getParam('shortCode'), $this->getParam('operatorId'), $this->getParam('type'));

//                        error_log($this->getParam('shortCode'), 3, "/tmp/l7.log");die();

            write_log('debug', 'call view to return API response');
            if ($data != false) {
                echo respOK($data);
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_UNKNOWNERROR, "Empty model return"));
            }
        } catch (Exception $e) {
            echo respNOK($e->getMessage());
            exit;
        }
    }

}

