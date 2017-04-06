<?php
namespace Goose\Config\Webapi;

class Redis extends \Goose\Libs\Singleton {
    public function configs() {
        return array(
            'servers' => array(
                array('host' => 'mswire-0313d49e-4be4.gcache.glodon.com', 'port' => '10003'),
            ),
//		    'writeHost' => 'http://10.6.4.179:8081/write',
//		    'xwriteHost' => 'http://10.6.4.179:8081/xwrite',
//            'readHosts' => array(
//                'http://10.6.4.179:8081/read',
//            )
        );
    }
}

