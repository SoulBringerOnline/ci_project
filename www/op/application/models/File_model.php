<?php
class File_Model extends CI_Model {
	private $connection_ol = null;
	public function __construct() {
		$this->load->database();

		$this->connection_ol = new MongoClient( $this->config->item('mongodb_gsk_ol') );
		//$this->connection_ol = new MongoClient( $this->config->item('mongodb_spider') );
		$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');
	}

	public function __destruct() {
		$this->connection_ol->close();
	}


	public function getFileList($fid=1) {
		$list = $this->mongo_ol->filesystem->find(array("f_fpid" => new MongoInt32($fid),"f_status"=>new MongoInt32(1) ), array());

		$list = iterator_to_array($list);

		return $list;
	}

	public function getFileInfo($id=1) {
		$list = $this->mongo_ol->filesystem->find(array("f_fid" => new MongoInt32($id) ), array());

		$list = iterator_to_array($list);

		return array_pop($list);
	}

	public function getPathInfo($fid, $des_id = 0) {

		$path_info = array();
		$f_fid = $fid;
		while($f_fid != $des_id) {
			$list = $this->mongo_ol->filesystem->find(array("f_fid" => new MongoInt32($f_fid) ), array());
			$list = iterator_to_array($list);
			$list = array_pop($list);
			if(empty($list)) {
				break;
			}
			$path_info[] = $list;
			$f_fid = $list['f_fpid'];
		}

		return $path_info;
	}

	public function getNewFileId() {
		$list =  $this->mongo_ol->ids->find(array('f_tablename'=>'specail_file_system'));
		$list = iterator_to_array($list);
		$list = array_pop($list);
		$cnt= $list['f_usedid'];
		$this->mongo_ol->ids->update(array('f_tablename'=>'specail_file_system' ),
			array('$inc' => array( 'f_usedid' => 1 )));

		return $cnt;
	}

	public function uploadFile($fpid, $file_info) {
		$this->mongo_ol->filesystem->insert($file_info);
		// 更新父节点的儿子数

		$this->mongo_ol->filesystem->update(array("f_fid"=>new MongoInt32($fpid)), array('$inc' => array( 'f_soncount' => new MongoInt32(1) )));

		return true;
	}

	public function editFile($fid, $file_info) {
		//$this->mongo_ol->filesystem->insert($file_info);
		// 更新父节点的儿子数
		$this->mongo_ol->filesystem->update(array("f_fid"=>new MongoInt32($fid)), array('$set' =>$file_info));

		return true;
	}

	public function deleteFile($fid) {
		$file_info = $this->getFileInfo($fid);

		$this->mongo_ol->filesystem->update(array('f_fid'=>new MongoInt32($fid)),array('$set' => array( 'f_status' => new MongoInt32(0) )) );
		$fpid = $file_info['f_fpid'];
		// 更新父节点的儿子数
		$this->mongo_ol->filesystem->update(array("f_fid"=>new MongoInt32($fpid)), array('$inc' => array( 'f_soncount' => -1 )));

		return true;
	}
}
