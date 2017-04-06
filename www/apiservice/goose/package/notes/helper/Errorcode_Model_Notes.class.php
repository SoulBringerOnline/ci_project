<?php
	namespace Goose\Package\Notes\Helper;

	class Errorcode_Model_Notes {

		const MODULE_NAME          = 'Notes';
		const ERROR_CREATE_NOTES   = -501;           //创建便签失败
		const ERROR_DELETE_NOTES   = -502;           //删除便签失败
		const ERROR_FIND_WEATHER   = -503;           //查询天气失败
		const ERROR_CREATE_NOTESTEMPLATE   = -504;   //创建便签模板失败
		const ERROR_DELETE_NOTESTEMPLATE   = -505;   //删除便签模板失败
		const ERROR_UPDATE_NOTES   = -506;           //更新便签失败
		const ERROR_UPDATE_NOTESTEMPLATE   = -507;   //更新便签模板失败
		const ERROR_FIND_NOTESTEMPLATE   = -508;     //查询便签模板失败
		const ERROR_EMPTY_KEYWORDS   = -509;         //搜索关键词为空
		const ERROR_SEARCH_RESULT    = -510;         //查询失败
		const ERROR_NOTES_NOT_EXIST  = -511;         //便签不存在
		const ERROR_GROUP_INFO  = -512;              //群组信息错误
		const ERROR_PROJECT_INFO  = -513;            //项目组信息错误
		const ERROR_FRIEND_INFO  = -514;             //好友信息错误
		const ERROR_ADD_SHARE  = -515;               //添加分享失败
		const ERROR_SHARE_NOT_EXIST  = -516;         //分享不存在
	}