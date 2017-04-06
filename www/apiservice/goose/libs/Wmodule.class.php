<?php
namespace Goose\Libs;

class Wmodule extends \Frame\Module {
	public function __construct($app) {
		parent::__construct($app);
		$this->hook("before", array(get_called_class(),"init"));
		$this->hook("after", array(get_called_class(),"asyn"));
		$this->hook("after", array(get_called_class(),"last"));
	}

	public function run() {}
	public function init() {
		// 主体逻辑代码执行之前
		/* 文件头信息 */
		if(isset($this->request->GET["callback"]) && !empty($this->request->GET["callback"])) {
			$GLOBALS['jsonp_callback'] = $this->request->GET["callback"];
		}
		header('Content-Type: application/json; charset=utf-8');
		//跨域请求的问题
		header('Access-Control-Allow-Origin: *');
		// 记录goose接口请求的GET POST zhuyou header
		$log_env = array(
			'get'=>$this->request->GET,
			'post'=>$this->request->POST,
			'zhuyou'=>$this->request->ZHUYOU
		);
	}

	public function asyn() {
		// 缓冲区输出之后，最后调用的代码
	}

	final public function last() {
		// 最后执行一些关闭连接的操作
		\Libs\Redis\Redis::releaseConns();
		\Libs\Mongo\MongoDB::releaseConns();
	}
}