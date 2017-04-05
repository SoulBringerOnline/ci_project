<?php 
	class GSK_Model extends CI_Model
	{
		public function __construct()
		{
			$this->load->database();
		}

		public function get_channel()
		{
			$this->db->select('*');
            $this->db->from('t_channel');
            
            return $this->db->get()->result_array();
		}
		
		public function get_versions($os)
        {
            $this->db->select('*');
            $this->db->from('t_version');
            $this->db->where('f_os' , $os);
            $this->db->limit(30);
            $this->db->order_by('f_version' , 'desc');

            return $this->db->get()->result_array();
        }

		public function get_pub_version($os, $channel){
			$this->db->select('*');
			$this->db->from('t_channel_log');
			$this->db->where('f_id' , $channel);
			$this->db->where('f_os' , $os);
			$this->db->limit(30);
			$this->db->order_by('f_time' , 'desc');

			return $this->db->get()->result_array();
		}

        function update_pub_info($data)
	    {
			$this->db->update_batch('t_channel', $data, 'f_id');
		    $this->log_version_pub($data);
			return true;
		}

		function log_version_pub($data) {
			foreach($data as $item) {
				$item['f_os'] = "2";
				if(isset($item['f_android_version'])) {
					$item['f_os'] = "3";
				}
				$item['f_time'] = time();
				$this->db->replace('t_channel_log', $item);
			}

			return true;
		}

	}

?>
