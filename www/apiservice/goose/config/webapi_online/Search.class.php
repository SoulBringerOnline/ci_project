<?php
	/**
	 * 搜索配置文件
	 * Date: 2015/12/14
	 */

	namespace Goose\Config\Webapi;

	class Search extends \Goose\Libs\Singleton{
		public function configs() {
			return array (
					'searchUrl'=>'http://api.zy.work.glodon.com/search/notes/{uid}/{keywords}/{type}?pageNum={page}&maxNum={size}',
					'createUrl'=>'http://api.zy.work.glodon.com/search/notes/index/add/{id}',
					'deleteUrl'=>'http://api.zy.work.glodon.com/search/notes/index/delete/{id}',
					'updateUrl'=>'http://api.zy.work.glodon.com/search/notes/index/update/{id}',
			);
		}
	}