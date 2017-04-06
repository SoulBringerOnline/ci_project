<?php
namespace Goose\Config\Webapi;

class Redis extends \Goose\Libs\Singleton {
    public function configs() {
        return array(
            'servers' => array(
                array('host' => '192.168.165.241','port' => '6380')
            ),
        );
    }
}
