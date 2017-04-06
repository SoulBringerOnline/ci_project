<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/24
 * Time: 17:25
 */
	namespace Goose\Modules\Test;
	use \Goose\Libs\Util\Util;


class Test extends \Goose\Libs\Wmodule{

	public function run()
	{
		$uid = intval($this->session->uid);
		$this->response->make_json_ok('', $uid);

//		$code = $_REQUEST['code'];
////		$uin = '107236';
//		$pwd = $_REQUEST['pwd'];
//		$code =md5($code);
//
//		$code_md5_16 = substr($code, 0, 16);//验证码先md5加密再取前16位
//
//		$md5_pwd = Util::gsk_encrypt($code_md5_16, md5($pwd));
//		$this->response->make_json_ok('', array('pwd'=>base64_encode($md5_pwd)));
//		$new = 'qwe123456';
//		$old = 'qwe12345';
//		$uid = '105062';
//
//		$md5_uid = md5($uid);
//
//		$last_new = md5(md5($new).$md5_uid);
//
//		$last_old = md5(md5($old).$md5_uid);
//		$this->response->make_json_ok('', array('md5_uid'=>$md5_uid, 'new'=>$last_new, 'old'=>$last_old, 'old_pwd'=>md5($old)));

//		$pwd = '123456789';
//		$uid = '107236';
//
//		$md_pwd = md5($pwd);
//		$encypt_pwd = md5($md_pwd.md5($uid));
//
//		$this->response->make_json_ok('', array('passwd'=>$encypt_pwd, 'md_pwd'=>$md_pwd));
	}
} 