<?php
namespace Goose\Libs;

class Script extends \Frame\Script {
	public function __construct($app) {
		parent::__construct($app);
		$this->hook("before", array(get_called_class(),"init"));
		$this->hook("after", array(get_called_class(),"asyn"));
	}

	public function run() {}
	public function init() {
		// 主体逻辑代码执行之前
	}

	public function asyn() {
		// 缓冲区输出之后，最后调用的代码
	}

}