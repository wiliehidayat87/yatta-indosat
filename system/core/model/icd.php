<?php
class model_icd extends model_base {

        public function save_icd_log($data) {
                $log = manager_logging::getInstance ();
                $log->write ( array ('level' => 'debug', 'message' => "icd_log_save : " . serialize ( $data ) ) );

                $sql = sprintf(
                "INSERT INTO icd_request_log (
                        `status`,
                        `reqid`,
                        `designated_price`,
                        `url_callback`,
                        `created`,
                        `modified`
                 ) VALUES (
                        '%s',
                        '%s',
                        %d,
                        '%s',
                        now(),
                        now()
                 )",
                         mysql_real_escape_string($data->status),
                         mysql_real_escape_string($data->reqid),
                         mysql_real_escape_string($data->designated_price),
                         mysql_real_escape_string($data->url_callback)
                );

                //$log->write ( array ('level' => 'debug', 'message' => "SQL : " . $sql));

                $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();

        }

        public function update_icd_log($data) {
                $log = manager_logging::getInstance ();
                $log->write ( array ('level' => 'debug', 'message' => "icd_log_update: " . serialize ( $data ) ) );

                $sql = sprintf("UPDATE icd_request_log SET grade = '%s' WHERE reqid = '%s'",
                         mysql_real_escape_string($data->grade),
                         mysql_real_escape_string($data->reqid)
                );

        $this->databaseObj->query($sql);
        return true;

        }

        public function read_icd_log($data) {

                $log = manager_logging::getInstance ();
                $log->write ( array ('level' => 'debug', 'message' => "icd_request_read : " . serialize ( $data ) ) );

                $sql = sprintf("
                        SELECT * FROM icd_request_log
                        WHERE
                        reqid = '%s'
                ",
                        mysql_real_escape_string($data->reqid)
                );

                //$log->write ( array ('level' => 'debug', 'message' => "SQL : " . $sql));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) return $data[0]; else return false;

        }

        public function read_icd_grade($data) {

                $log = manager_logging::getInstance ();
                $log->write ( array ('level' => 'debug', 'message' => "icd_grade_read : " . serialize ( $data ) ) );

                $sql = sprintf("
                        SELECT * FROM icd_grade_ref
                        WHERE
                        grade = '%s'
                ",
                        mysql_real_escape_string($data->grade)
                );

                //$log->write ( array ('level' => 'debug', 'message' => "SQL : " . $sql));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) return $data[0]; else return false;

        }

}

