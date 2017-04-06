<?php
namespace Goose\Libs\Http;

/**
 * http请求通用response
 */

final class Response extends \Libs\Http\BasicResponse
{

    /**
     * json render to json 
     * resource render to resource
     */
    private $renderType = 'json';
    private $errCode = 0;
    private $message = 'ok';


    public function setRenderType($type) {
        $this->renderType = $type;
    }

    public function setContent($code = 0, $msg = 'ok', $body = NULL) {
        $this->errCode = $code;
        $this->message = $msg;
        $method = 'build' . ucfirst($this->renderType) . 'Body';
        if (method_exists($this, $method)) {
            $body = $this->$method($body);
        }
        $this->setBody($body);
    }

    protected function buildJsonBody(&$body) {
        $body = array('error_code' => $this->errCode, 'message' => $this->message, 'data' => $body);
        return $body;
    }

    //返回成功
    public function success($data = '', $code = 0, $msg = 'success!') {
        $this->setContent($code, $msg, $data);
    }

    //返回失败
    public function error($code = 40001, $msg = 'error!', $data = '') {
        $this->setContent($code, $msg, $data);
    }
	/**
	 * 将数据格式化成JSON
	 *
	 * @params str  $error    指明是否有错
	 * @params str  $message  错误消息
	 * @params str  $content  内容
	 * @params arr  $append   追加JSON项
	 */
	function make_json_response( $code = 0, $message = '', $content = '', $append = array() )
	{
		/* 初始化 */
		$res = array( 'code'=>$code, 'message'=>$message, 'content'=>$content );

		/* 辅助项 */
		foreach( $append AS $key=>$value ){
			$res[$key] = $value;
		}

		/* JSON编码，输出 */
		$this->setBody($res);
	}

	function make_json_ok( $message = '', $content = '', $append = array() )
	{
		$this->make_json_response(0, $message, $content, $append);
	}

	function make_json_fail( $message = '' )
	{
		$this->make_json_response(1, $message);
	}

	function make_jsonp_ok($message = '', $content = '', $callback="callback" ) {
		$this->make_jsonp_response(0, $message, $content, $callback);
	}

	function make_jsonp_fail( $message = '', $callback="callback" )
	{
		$this->make_jsonp_response(1, $message, $callback);
	}


	function make_jsonp_response($code, $message, $callback) {

	}



}
