<?php
namespace Goose\Config\Webapi;
//require_once '/home/work/conf/api/MySQLConfigApi.php';
use Goose\Libs\config\MySQLConfigApi;

class MySQL extends \Goose\Libs\Singleton  {

    const MYSQL_KEY = 'test';

    public function configs() {
        return MySQLConfigApi::GetCfgByServKey(self::MYSQL_KEY);
    }
}
