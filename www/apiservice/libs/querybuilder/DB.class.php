<?php
/**
 * ┏┓     ┏┓
 *┏┛┻━━━━━┛┻┓
 *┃         ┃ 　
 *┃    ━    ┃
 *┃ ┳┛   ┗┳ ┃　
 *┃         ┃　　　　
 *┃    ┻    ┃　　
 *┃         ┃　　　　
 *┗━┓     ┏━┛
 *  ┃     ┃   神兽保佑,代码无BUG！　　　　　　　
 *  ┃     ┃
 *  ┃     ┗━┓
 *  ┃       ┣┓　　　　
 *  ┃       ┏┛
 *  ┗┓┓┏━┳┓┏┛
 *   ┃┫┫ ┃┫┫
 *   ┗┻┛ ┗┻┛
 *
 * @author: Tian Shuang
 * @since: 15/6/25 下午8:19
 * @description:
 */

namespace Libs\QueryBuilder;


class DB {
    /**
     * @var QueryBuilder
     */
    protected static $queryBuilderInstance = array();

    protected static $instance;

    protected static $database;

    private function __construct() {}

    private function __clone() {}


    public static function database($database)
    {
        $queryBuilder = static::factory($database);
        return $queryBuilder;
    }

    public static function table($table)
    {
        $queryBuilder = static::factory(static::$database);
        $queryBuilder->table($table);
        return $queryBuilder;
    }

    private static function factory($database)
    {
        if(empty($database)) {
            throw new Exception('No table specified.', 501);
        }
        if (!static::$queryBuilderInstance[$database]) {
            $connection = Connection::getConn($database);
            static::$queryBuilderInstance[$database] = new QueryBuilder($connection);
        }
        static::$database = static::$queryBuilderInstance[$database]->getConnection()->getDatabase();

        return static::$queryBuilderInstance[$database];
    }

}