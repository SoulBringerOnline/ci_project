<?php
	class Activity_Model extends CI_Model {
		public function __construct() {
			//$this->connection_ol = new MongoClient($this->config->item('mongodb_gsk_ol'));
			$this->connection_ol = new MongoClient($this->config->item('mongodb_spider'));
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');
		}

		function __destruct() {
			$this->connection_ol->close();
		}

		function get_pc_activity_pos($where) {
			$data = $this->mongo_ol->pcActivityPos->findOne($where);
			return $data;
		}

		function update_pc_activity_pos($where, $data) {
			$ret = $this->mongo_ol->pcActivityPos->update($where, array('$set'=>$data));
			if($ret){
				return true;
			}

			return false;
		}
	}