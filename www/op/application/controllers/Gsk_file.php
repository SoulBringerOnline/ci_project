<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Gsk_file extends GSK_Controller {
		public function __construct()
		{
			parent::__construct();
			$this->load->model('file_model');
		}

		public function index() {
			$fid = $_REQUEST['fid'];
			$env = $_REQUEST['env'];

			$data = array();
			if(empty($fid)) {
				$fid = 1;
			}
			$file_list = $this->file_model->getFileList($fid);
			$file_info = $this->file_model->getFileInfo($fid);
			$path_info = $this->file_model->getPathInfo($fid,0);


			$data["list"] = $file_list;
			$data['info'] = $file_info;
			$data['path'] = $path_info;
			$data['fid'] = $fid;

			$data['content'] = 'gsk/file/list';
			$this->load->view('include/layout' , $data );
		}

		public function addFile() {
			//
			$name = $_REQUEST['name'];
			$sort = $_REQUEST['sort'];
			$type = $_REQUEST['type']; // 0  是文件，1是文件夹
			$fpid = $_REQUEST['fpid'];
			$size = intval($_REQUEST["size"]);

			$url = $_REQUEST['path']?$_REQUEST['path']:"";

			// 获得父节点的信息  level、soncount、

			$parent_file_info = $this->file_model->getFileInfo($fpid);
			$level = $parent_file_info["f_level"] + 1;

			$ftype = $type?99:1;
			$sort  = $type?199:10;
			$project_id = "2015120900000001";

			$file_id = $this->file_model->getNewFileId();

			$data = array(
				"f_name"=> $name,
				//"f_project_id"=> $project_id,
			    "f_type"=>new MongoInt32($ftype),
			    "f_down_url"=> $url,
			    "f_size"=>new MongoInt32($size),
				"f_playtime"=> new MongoInt32(0),
			    "f_uploadtime"=> new MongoInt32(time()),
			    "f_cre_uin"=> new MongoInt32(10005),
			    "f_cre_name"=> "知识礼包",
			    "f_tag"=> "",
			    "f_fid"=> new MongoInt32($file_id),
			    "f_fpid"=> new MongoInt32($fpid),
			    "f_level"=> new MongoInt32($level),
			    "f_status"=> new MongoInt32(1),
			    "f_used_type"=> new MongoInt32(2),
			    "f_soncount"=> 0,
			    "f_otherinfo"=> "$project_id:",
				"f_sort_type"=> new MongoInt32($sort)
			);

			// 上传一个文件
			$this->file_model->uploadFile($fpid,$data);

			$file_info = $this->file_model->getFileInfo($file_id);

			//
			echo json_encode(array("code"=>0, 'msg'=>'ok', 'file'=>$file_info));

			$this->notifyToSearch("add", $file_info);
		}


		public function deleteFile() {
			$fid = $_REQUEST['fid'];
			$project_id = "2015120900000001";
			$file_info = $this->file_model->getFileInfo($fid);
			if(empty($file_info)) {
				echo json_encode(array("code"=>-1, 'msg'=>'文件不存在'));
				return;
			}

			if($file_info["f_status"] == 0) {
				echo json_encode(array("code"=>-1, 'msg'=>'文件已经删除'));
				return;
			}

			if($file_info['f_type'] == 99 && $file_info['f_soncount'] != 0)
			{
				echo json_encode(array("code"=>-1, 'msg'=>'文件夹不为空'));
				return;
			}
			$this->file_model->deleteFile($fid);

			$this->notifyToSearch("del", $file_info);

			echo json_encode(array("code"=>0, 'msg'=>'ok'));
		}


		public function editFile() {
			$name = $_REQUEST['name'];
			$sort = $_REQUEST['sort'];
			//$type = $_REQUEST['type']; // 0  是文件，1是文件夹
			$fid = $_REQUEST['fid'];

			$data = array(
				"f_name"=> $name,
			);

			// 上传一个文件
			$this->file_model->editFile($fid,$data);

			$file_info = $this->file_model->getFileInfo($fid);

			//

			echo json_encode(array("code"=>0, 'msg'=>'ok', 'file'=>$file_info));
			$this->notifyToSearch("edit", $file_info);
		}

		private function notifyToSearch($op, $file_info, $env = "online") {
			$domain = "http://api.zy.glodon.com";
			if($env == "offline") {
				$domain = "http://api.zy.work.glodon.com";
			}
			$project_id = "2015120900000001";
			$url = "";
			switch($op) {
				case "del":
					$url = $domain."/search/file/index/del/";
					break;
				case "add":
					$url = $domain."/search/file/index/add/";
					break;
				case "edit":
					$url = $domain."/search/file/index/add/";
					break;
			}

			$fid = $file_info["f_fid"];
			if($file_info['f_type'] != 99) {
				$url .= "{$project_id}/{$fid}";
				//$ret = file_get_contents($url);

				// 1. 初始化
				$ch = curl_init();
				// 2. 设置选项，包括URL
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				// 3. 执行并获取HTML文档内容
				$output = curl_exec($ch);
				// 4. 释放curl句柄
				curl_close($ch);
				file_put_contents("/tmp/test1", array($url,$output), FILE_APPEND);
			}
		}
	}