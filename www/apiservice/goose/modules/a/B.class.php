<?php
namespace Goose\Modules\A;

use \Goose\Package\C\C;

class B extends \Goose\Libs\Wmodule {


	public function run() {

        var_dump($_GET);

        echo "echo a statement!";

        setcookie("hello","nihao");  

        var_dump($_COOKIE['hello']);
      
        $ss;
        foreach($ss as $a) {
            echo $a;
        }
        return;
        if(!$this->session->checkToken()){
            echo "token invalid";
        }
        echo "token valid";
        return;
		// 主体逻辑
	    //--a\ 获取参数
	    var_dump($this->request->REQUEST);
	    // var_dump($this->request->POST);
	    // var_dump($this->request->COOKIE);
	    //1、调用C的db数据接口
	    $dbdata = C::instatnce()->getDataFromDB();
	    $this->app->log->log('testlogname',"dbdata=......start");
	    $this->app->log->log('testlogname',$dbdata);
	    $this->app->log->log('testlogname',"dbdata=......end");
	    //2、调用C的redis数据接口
	    $redisdata = C::instatnce()->getDataFromRedis();
	    $this->app->log->log('testlogname',"redis data=......start");
	    $this->app->log->log('testlogname',$redisdata);
	    $this->app->log->log('testlogname',"redisdata=......end");
	    //3、调用C的mongo数据接口
	    $mongodata = C::instatnce()->getDataFromMongo();
	    $result = array("msg"=>'ok', 'ret'=>0, 'info' => $dbdata);
	    $this->app->response->setBody($result);
	    return TRUE;
    }

	public function asyn() {
		$this->app->log->log('testlogname', "final asyn........输出完后处理这里");
	}

}
