<?php 
	class Pad_Model extends CI_Model
	{
		public function __construct()
		{
			$this->load->database('pad');
		}

		public function get_pads()
        {
            $this->db->select('key');
            $this->db->from('store');
			$this->db->like('key', 'pad:', 'after'); 
			
            return $this->db->get()->result_array();
        }
	}

?>
