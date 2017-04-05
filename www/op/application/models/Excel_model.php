<?php
	class Excel_model extends CI_Model
	{
		public function __construct()
		{
			$this->connection_ol = new MongoClient( $this->config->item('mongodb_gsk_ol') );
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');
		}

		function __destruct(){
			$this->connection_ol->close();
		}

		public  function insert_question_bank($data = array(), $type)
		{

			foreach($data as $item)
			{
				$msg = array();
				$msg['f_num'] = $item[0];
				$msg['f_question'] = $item[1];
				$msg['f_options_A'] = $item[2];
				$msg['f_options_B'] = $item[3];
				$msg['f_options_C'] = $item[4];
				$msg['f_options_D'] = $item[5];
				$msg['f_answer'] = $item[6];
				$msg['f_type'] = new MongoInt32($type);

				$this->mongo_ol->question_bank->insert($msg);
			}
		}
	}
?>
