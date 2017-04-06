<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/12/23
 * Time: 10:52
 */
	namespace Goose\Package\Outservice\Helper;

	class  SDKRuntimeException extends \Exception {
		public function errorMessage()
		{
			return $this->getMessage();
		}

	}