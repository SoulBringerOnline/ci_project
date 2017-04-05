<?php
    /* ----------------------------------------------------------------------- */
    // - 预定义常量
    /* ----------------------------------------------------------------------- */

    /* 用户 SESSION 的下标 */
    define('SN_SYSTEM', md5(__FILE__));

    /* 根路径的相对路径和绝对路径(保留末尾斜杠) */
    define('DIR_ROOT', str_ireplace('includes/config.php','',str_replace("\\",'/',__FILE__)));
    define('URL_ROOT', str_ireplace(rtrim(str_replace("\\",'/',$_SERVER['DOCUMENT_ROOT']),'/'),'',DIR_ROOT));

    /* ----------------------------------------------------------------------- */
    // - 初始化配置
    /* ----------------------------------------------------------------------- */

	$_CFG = array();

    /* ----------------------------------------------------------------------- */
    // - 数据库登陆信息
    /* ----------------------------------------------------------------------- */
    $_CFG['dbhost'] = '192.168.164.199';       //数据库服务器
    $_CFG['dbname'] = 'gsk_srv';       //数据库名称
    $_CFG['dbuser'] = 'root';       //数据库登陆帐号
    $_CFG['dbpass'] = 'wx123456';       //数据库登陆密码
    $_CFG['tblpre'] = 't_';       //数据表名称前辍

    $_CFG['redis_host'] = "192.168.165.241";    //REDIS服务器
    $_CFG['redis_port'] = 6380; //REDIS端口
    $_CFG['mongodb_gsk_ol'] = "mongodb://192.168.165.240:27017"; //线上服务器MONGO
    $_CFG['mongodb_spider'] = "mongodb://192.168.165.240:27017"; //爬虫MONGO
    $_CFG['mongodb_op'] = "mongodb://192.168.165.240:27017"; //运营MONGO

    /* ----------------------------------------------------------------------- */
    // - 调试信息
    /* ----------------------------------------------------------------------- */
    $_CFG['DEBUG'] = false;
    
    /* ----------------------------------------------------------------------- */
    // - 根级路径配置
    /* ----------------------------------------------------------------------- */
    $_CFG['DIR_ROOT']   = DIR_ROOT;
    $_CFG['URL_ROOT']   = URL_ROOT;

    /* ----------------------------------------------------------------------- */
    // - 环境配置
    /* ----------------------------------------------------------------------- */

    /* 语言 */
    $_CFG['SYS_LANG'] = 'zh';

    /* 皮肤 */
    $_CFG['SYS_SKIN'] = 'default';

    /* 时区 */
    $_CFG['SYS_TIMEZONE'] = 'PRC';

    /* ----------------------------------------------------------------------- */
    // - 路径配置
    /* ----------------------------------------------------------------------- */
    /* 公用文件夹路径 */
    $_CFG['DIR_INC'] = $_CFG['DIR_ROOT'].'includes/';
    $_CFG['DIR_CFG'] = $_CFG['DIR_ROOT'].'config/';
    $_CFG['DIR_HELPER'] = $_CFG['DIR_ROOT'].'helper/';

?>