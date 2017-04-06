<?php
namespace Goose\Libs;

class Config extends \Frame\Config {

    private $confNamespace;

    function __construct($env) {
        parent::__construct();
        $this->confNamespace = '\\Goose\\Config\\' . $env . '\\';
        $this->setDb();
        $this->setRedis();
        $this->setMemcache();
        $this->setRemote();
        $this->setMongo();
        $this->setAmqp();
        $this->setCall();
        $this->setBMap();
        $this->setSearch();
    }

    private function setDb() {
        $mysqlConfig = $this->confNamespace . 'MySQL';
        $this->db = function () use ($mysqlConfig) {
            return $mysqlConfig::instance()->configs();
        };
    }

    private function setRedis() {
        $redisConfig = $this->confNamespace . 'Redis';
        $this->redis = function () use ($redisConfig) {
            return $redisConfig::instance()->configs();
        };
    }

	private function setMongo() {
		$mongoConfig = $this->confNamespace . 'Mongo';
		$this->mongo = function () use ($mongoConfig) {
			return $mongoConfig::instance()->configs();
		};
	}

    private function setMemcache() {
        $memcacheConfig = $this->confNamespace . 'Memcache';
        $this->memcache = function () use ($memcacheConfig) {
            return $memcacheConfig::instance()->configs();
        };
    }

    private function setRemote() {
        $remoteConfig = $this->confNamespace . 'Remote';
        $this->remote = function () use ($remoteConfig) {
            return $remoteConfig::instance()->configs();
        };
    }

	private function setAmqp() {
		$amqpConfig = $this->confNamespace . 'Amqp';
		$this->amqp = function () use ($amqpConfig) {
			return $amqpConfig::instance()->configs();
		};
	}

    private function setCall() {
        $callConfig = $this->confNamespace . 'Call';
        $this->call = function () use ($callConfig) {
            return $callConfig::instance()->configs();
        };
    }

    private function setBMap() {
        $bMapConfig = $this->confNamespace . 'BMap';
        $this->bMap = function () use ($bMapConfig) {
            return $bMapConfig::instance()->configs();
        };
    }

    private function setSearch() {
        $SearchConfig = $this->confNamespace . 'Search';
        $this->search = function () use ($SearchConfig) {
            return $SearchConfig::instance()->configs();
        };
    }
}
