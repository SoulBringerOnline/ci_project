<?php
namespace Goose\Modules\System;


class BadCall extends \Goose\Libs\Wmodule {

	public function run() {
		$this->response->setStatus(404);
		$this->response->setBody("The page not found!");
		return TRUE;
	}

	public function asyn() {

	}
}
