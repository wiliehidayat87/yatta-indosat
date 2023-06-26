<?php
class indosat_model_pushbuffer extends model_base {
	public function save(model_data_pushbuffer $data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$sql = sprintf ( "
			INSERT INTO
				push_buffer(
					pid,
					src,
					dest,
					oprid,
					service,
					subject,
					message,
					price,
					stat,
					created,
					tid,
					`type`
				)
			VALUES(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					NOW(),
					'%s',
					'%s'
				)", 

		mysql_real_escape_string ( $data->pid ), mysql_real_escape_string ( $data->src ), mysql_real_escape_string ( $data->dest ), mysql_real_escape_string ( $data->oprid ), mysql_real_escape_string ( $data->service ), mysql_real_escape_string ( $data->subject ), mysql_real_escape_string ( $data->message ), mysql_real_escape_string ( $data->price ), mysql_real_escape_string ( $data->stat ), mysql_real_escape_string ( $data->tid ), mysql_real_escape_string ( trim($data->type) ) );
		
		$query = $this->databaseObj->query ( $sql );
		return $this->databaseObj->last_insert_id ();
	}
	public function update(model_data_pushbuffer $data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$sql = sprintf ( "UPDATE push_buffer SET stat = '%s' where id = '%s'", $data->stat, $data->id );
		return $this->databaseObj->query ( $sql );
	}
	public function execPushbuffer($pid, $service) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$broadcast_config = loader_config::getInstance ()->getConfig ( 'broadcast' );
		$config = loader_config::getInstance ()->getConfig ( 'database' )->profile ['connBroadcast'];
		$user = $config ['username'];
		$pass = $config ['password'];
		$db = $config ['database'];
		$driver = $config ['driver'];
		
		$log->write ( array ('level' => 'debug', 'message' => 'nohup ' . $driver . ' -u' . $user . '  -p' . $pass . ' ' . $db . ' -q --skip-column-names -B -e "SELECT * FROM push_buffer WHERE pid = \"' . mysql_escape_string ( $pid ) . '\" AND service = \"' . mysql_real_escape_string ( $service ) . '\" AND stat = \"ON_QUEUE\"" | sed "s/\t/\t/g;s/^//;s/$//;s/\n//g" >"' . $broadcast_config->bufferPath . '"/pushBuffer' . $pid . $service . '.tsv &' ) );
		shell_exec ( 'nohup ' . $driver . ' -u' . $user . '  -p' . $pass . ' ' . $db . ' -q --skip-column-names -B -e "SELECT * FROM push_buffer WHERE pid = \"' . mysql_escape_string ( $pid ) . '\" AND service = \"' . mysql_real_escape_string ( $service ) . '\" AND stat = \"ON_QUEUE\"" | sed "s/\t/\t/g;s/^//;s/$//;s/\n//g" >"' . $broadcast_config->bufferPath . '"/pushBuffer' . $pid . $service . '.tsv &' );
		return TRUE;
	}
	public function clearBuffer($deleteList) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$deleteList = implode ( ",", $deleteList );
		$sql = sprintf ( "DELETE FROM push_buffer where id in(%s)", $deleteList );
		return $this->databaseObj->query ( $sql );
	}
}