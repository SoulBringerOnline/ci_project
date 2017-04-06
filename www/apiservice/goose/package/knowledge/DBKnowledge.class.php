<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/10/29
 * Time: 17:51
 */
namespace Goose\Package\Knowledge;

use \Goose\Package\Knowledge\Helper\DBKnowledge_Manager;
use \Libs\Mongo\MongoDB;

class DBKnowledge {

	// 数据处理类C
	private static $intance = NULL;
	private  $mongo_ol =null;
	private function __construct() {
		$this->mongo_ol = MongoDB::getMongoDB("online","gsk_ol");
	}

	public static function instatnce() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	/**
	 * 根据规范ID获取规范目录
	 * @param $standard_id 规范id
	 * @return mixed
	 */
	public function get_catalog_info($standard_id) {
		$sql = "SELECT catalog_file, catalog_hash, zipfile FROM standard_info_outline WHERE id=:_id";
		$result = DBKnowledge_Manager::getConn()->read($sql, array('_id'=>$standard_id));
		return $result[0];
	}

	/**
	 * 根据规范目录id获取正文
	 * @param $catalog_id 规范目录id
	 * @return mixed
	 */
	public function get_knowledge_info($catalog_id)
	{
		$sql = "SELECT hash, hash_file, next_catalog_id, next_catalog_hash, next_catalog_file FROM catalog_outline WHERE id=:_id";
		$result = DBKnowledge_Manager::getConn()->read($sql, array('_id'=>$catalog_id));
		return $result[0];
	}

	/**
	 * 根据工艺id获取正文
	 * @param $technology_id 工艺id
	 * @return mixed
	 */
	public function get_technology_info($technology_id)
	{
		$sql = "SELECT down_file, down_hash FROM technology_info_outline WHERE id=:_id";
		$result = DBKnowledge_Manager::getConn()->read($sql, array('_id'=>$technology_id));
		return $result[0];
	}

	/**
	 * @param $start
	 * @param $end
	 * @return mixed
	 */
	public function get_standard_online_list($start, $end)
	{
		if(empty($end))
		{
			$start = 0;
			$end =10;
		}
		$sql = "select id, name, number, online_time from standard_info_outline order by online_time DESC, id DESC limit :_start, :_end";
		$list = DBKnowledge_Manager::getConn()->read($sql, array('_start'=>$start, '_end'=>$end));
		$result['list'] = &$list;
		$sql_count = "select id from standard_info_outline";
		$result['count'] = count(DBKnowledge_Manager::getConn()->read($sql_count, array()));
		//判断是否需要new标志（前三条）
		if ($start == 0)
		{
			for($i=0; $i<3; $i++)
			{
				//处于前三且跟当前时间相差7天
				if((time() - strtotime($list[$i]['online_time'])) < 7*86400)
				{
					$list[$i]['new_flag'] = true;
				}
			}
//			for($i=0; $i<count($list); $i++)
//			{
//				if(strtotime($list[$i]['online_time']) >= strtotime("2015-12-10"))
//				{
//					$list[$i]['new_flag'] = true;
//				}
//			}
		}
		return $result;
	}

	/**
	 * 判断用户是否加入 通过项目认证的项目
	 * @param $uin
	 */
	public function is_pass_prj_member($uin)
	{
		$res = false;
		$prj_list = $this->mongo_ol->user->findOne(array('f_uin'=>intval($uin)), array('f_project_list'));
		if(count($prj_list['f_project_list']) != 0)
		{
			foreach($prj_list['f_project_list'] as $item)
			{
				if($item['f_status'])//首先，项目没解散
				{
					$prj = $this->mongo_ol->project->findOne(array('f_prj_id' => $item['f_prj_id']), array('f_auth_status'));
					if(!is_null($prj))
					{
						if(isset($prj['f_auth_status'])&&($prj['f_auth_status'] == 3))//其次，项目通过项目认证
						{
							$res =true;
							break;
						}
					}
				}
			}
		}

		return $res;
	}

	public function is_exist_user($uin)
	{
		$count = $this->mongo_ol->user->count(array('f_uin'=>intval($uin)));
		if($count === 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
} 