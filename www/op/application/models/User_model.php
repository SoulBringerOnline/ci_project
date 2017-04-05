<?php
	class User_Model extends CI_Model {
		public function __construct() {
			//$this->connection_ol = new MongoClient($this->config->item('mongodb_gsk_ol'));
			$this->connection_ol = new MongoClient( $this->config->item('mongodb_spider') );
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');
		}

		function __destruct() {
			$this->connection_ol->close();
		}

		function get_uin($where) {
			$data = $this->mongo_ol->user->findOne($where, array("f_uin"));
			return $data['f_uin'];
		}

	}