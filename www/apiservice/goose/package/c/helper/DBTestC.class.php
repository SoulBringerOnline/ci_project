<?php
namespace Goose\Package\C\Helper;

class DBTestC extends \Libs\DB\DBConnManager {

	const _DATABASE_ = 'test';
	static $readretry = 100;

}