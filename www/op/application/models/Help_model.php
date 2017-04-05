<?php
	class Help_Model extends CI_Model
	{
		public function __construct()
		{
			$this->connection_ol = new MongoClient( $this->config->item('mongodb_gsk_ol') );
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');
		}

		function __destruct(){
			$this->connection_ol->close();
		}

		/**
		 * 获取帮助文档
		 * @param $filter
		 * @return array
		 */
		public function get_help($filter)
		{
			$p = $f = $list = array();

			/* 排序字段初始化 */
			$f['order_fd']   = 'f_help_submit_time';
			$f['order_type'] = -1;

			/* 设置分页信息 */
			$p['cur_page'] = isset($filter['cur_page']) ? intval($filter['cur_page']) : 1;
			$p['rows_page']  = 50;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array();
			if(!empty($filter['f_help_title']))
			{
				$where['f_help_title'] = $filter['f_help_title'];
				$f['f_help_title'] = $filter['f_help_title'];
			}
			if(!empty($filter['f_help_classify']))
			{
				if($filter['f_help_classify'] != 8)
				{
					$where['f_help_classify'] = $filter['f_help_classify'];
					$f['f_help_classify'] = $filter['f_help_classify'];
				}
			}
			if(!empty($filter['f_comment_question'])||($filter['f_comment_question']!=null))
			{
				$where['f_comment_question'] = $filter['f_comment_question'];
				$f['f_comment_question'] = $filter['f_comment_question'];
			}

			/* 列表对象赋值 */
			$list['data']  =  $this->mongo_ol->help_document->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) );
			$list['filter'] = $f;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		public function get_a_help($help_id)
		{
			$help = NULL;
			$d = $this->mongo_ol->help_document->find( array('f_help_id' => $help_id ) );
			foreach ($d as $item) {
				if( $item['f_help_id'] == $help_id )
				{
					$help = $item;
					break;
				}
			}
			return $help;
		}

		/**
		 * 添加帮助文档
		 * @param $help
		 * @return bool
		 */
		public function add_help($help)
		{
			$data = array();
			$_id = new MongoId();
			$data['f_help_id'] = empty($help['help_id'])? $_id . '':$help['help_id'];
			$data['f_help_title'] =  $help['help_title'];
			$data['f_help_content'] =  $help['help_content'];
			$data['f_help_sort'] =  new MongoInt32($help['help_sort']);
			$data['f_comment_question'] =  $help['comment_question'];
			$data['f_help_classify'] =  new MongoInt32($help['help_classify']);
			$data['f_help_submit_time'] =  new MongoInt64(time());
			if(!empty($data['f_help_title']) && !empty($data['f_help_content']) )
			{
				$this->mongo_ol->help_document->insert($data);
				return true;
			}
			else
			{
				return false;
			}
		}

		public function edit_help($help)
		{
			$data = array();
			$help_id = $help['help_id'];
			if( !empty($help_id) && strlen($help_id)  &&
				( $this->mongo_ol->help_document->count( array('f_help_id' => $help_id ) ) == 1 )){
				$data['f_help_title'] =  $help['help_title'];
				$data['f_help_content'] =  $help['help_content'];
				$data['f_help_sort'] =  new MongoInt32($help['help_sort']);
				$data['f_comment_question'] =  $help['comment_question'];
				$data['f_help_classify'] =  new MongoInt32($help['help_classify']);
				$this->mongo_ol->help_document->update(
					array('f_help_id' => $help_id ),
					array('$set' => $data) );
				return True;
			}
			return False;
		}

		public function del_news($help_id)
		{
			if(!empty($help_id)){
				$this->mongo_ol->help_document->remove(array('f_help_id' => $help_id ));
				return True;
			}
			return False;
		}
	}
?>
