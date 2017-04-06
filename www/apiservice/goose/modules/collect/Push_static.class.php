<?php
namespace Goose\Modules\Collect;

class Push_static extends \Goose\Libs\Wmodule {
	public function run() {
		if(!$this->session->checkToken()) {
			//
		}
		$mongo_op = \Libs\Mongo\MongoDB::getMongoDB("op","gsk");
		$request = array_merge($this->request->REQUEST, $this->request->ZHUYOU);
		$request['time'] = date("Y-m-d H:i:s",time());
		$request['timestamp'] = time();
		if(count($request) >0) {
			$mongo_op->report->insert($request);
		}
		$this->app->response->setBody(time());
	}
}