<?php
namespace Goose\Package\Knowledge\Helper;

class DBKnowledge_Manager extends \Libs\DB\DBConnManager {

	const _DATABASE_ = 'api_zy_wireapp';
	static $readretry = 100;

}