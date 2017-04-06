<?php
namespace Goose\Libs\Http;

/**
 * http请求通用request
 * chaowang@20150129
 */
use \Libs\Http\Util;
final class Request extends \Libs\Http\BasicRequest
{
    public function __construct($app) {
        parent::__construct($app);

        $this->request_data['COOKIE'] = Util::slashes(Util::unmarkAmps($_COOKIE));
        $this->request_data['REQUEST'] = Util::slashes(Util::unmarkAmps($_REQUEST));
	    $_ZHUYOU_EXTENSION = empty($this->request_data['headers']['Zhuyou-Extension']) ? array() : \Goose\Libs\Util\Util::split_str($this->request_data['headers']['Zhuyou-Extension']);
        $this->request_data['ZHUYOU'] = Util::slashes(Util::unmarkAmps($_ZHUYOU_EXTENSION));
        $this->request_data['time'] = $_SERVER['REQUEST_TIME'];
        $this->request_data['ip'] = $this->getIP();
        $this->request_data['userAgent'] = empty($this->request_data['headers']['User-Agent']) ? '' : trim($this->request_data['headers']['User-Agent']);
        //$this->app = $app->request;
        $this->app = $app;
    }

    
    function __call ($method, $args) {
        array_unshift($args, $method);
        if (in_array($method, array('get', 'post', 'request')))  {
            return call_user_func_array(array($this, 'input'), $args);
        }
        return null; 
    }
    
    public function input($method, $name) {
        $args    = func_get_args();
        $request = $this->app->request->{strtoupper($method)};

        if (!isset($request[$name])) {
            $length = func_num_args();
            if ($length == 2) {
                return null;
            }
            return $args[2];
        } else {
            return $request[$name];
        }
    }

    public function isMob() {
        return isset($_COOKIE['app_access_token']) || isset($_COOKIE['access_token']);
    }

    private function getIP() {
        static $ip;

        if (isset($ip)) {
            return $ip;
        }

        if (empty($this->request_data['headers']['Clientip'])) {
            $ip = '127.0.0.1';
        } elseif ( ! strpos($this->request_data['headers']['Clientip'], ',')) {
            $ip = $this->request_data['headers']['Clientip'];
        }
        else {
            $hosts = explode(',', $this->request_data['headers']['Clientip']);
            foreach ($hosts as $host) {
                $host = trim($host);
                if ($host != 'unknown') {
                    $ip = $host;
                    break;
                }
            }
        }
        return $ip;
    }
}
