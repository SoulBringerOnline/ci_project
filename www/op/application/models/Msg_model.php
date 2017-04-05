<?php
	class Msg_Model extends CI_Model {
		public function __construct() {
			$this->connection_ol = new MongoClient( $this->config->item('mongodb_gsk_ol') );
			//$this->connection_ol = new MongoClient( $this->config->item('mongodb_spider') );
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');
		}

		function __destruct(){
			$this->connection_ol->close();
		}

		// 添加Msg
		public function add_msg($param){
			$this->mongo_ol->baseSystemMsg->insert($param);
			return true;
		}

		//删除Msg
		public function del_Msg($baseid) {
			$this->mongo_ol->baseSystemMsg->remove(array("f_baseid"=>$baseid));
			return true;
		}

		//修改Msg
		public function edit_msg($param, $baseid) {
			$ret = $this->mongo_ol->baseSystemMsg->update(
				array('f_baseid' => $baseid),
	    		array('$set' => $param)
			);

			return true;
		}
		
		public function get_msg_by_baseid($baseid) {
			return $this->mongo_ol->baseSystemMsg->findOne(array("f_baseid"=>trim($baseid)));
		}
		//Msg list
		public function get_msg($filter) {
			$p = $f = $list = array();
			
		    $f['order_fd']   = '_id';
		    $f['order_type'] = -1; 
			$f['query'] = isset($filter['query']) ? trim($filter['query']) : '';

			/* 设置分页信息 */
		    $p['cur_page'] = isset($filter['cur_page']) ? intval($filter['cur_page']) : 1;
		    $p['rows_page']  = 50;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array( );
			if( strlen($f['query']) )
			{
				$filter_param = explode(" ", $f['query']);
				foreach ($filter_param as $q)
				{
					$key = '';
					$value = '';
					$flag = false;
                    $item = explode("=", $q);
                    if( $flag == false AND count($item) == 2 )
                    {
                    	$key = 'f_' . $item[0];
                        $value = $item[1];
                        $flag = '$eq';

                        switch( $key )
                        {
	                        case 'f_msginfo':
	                        case 'f_baseid':
	                            $where[$key] = new MongoRegex('/' .  $value . '/');  
	                            break;
	                        case 'f_begintime':
	                        case 'f_finishtime':
	                       	case 'f_type':
	                       	case 'f_type':
	                            $value = intval($value);
	                            
	                        default:
	                            $where[$key] = array( $flag => $value );
	                            break;
                        }
                    }
                }
            }

		    /* 列表对象赋值 */
		    $list['data']  = iterator_to_array( $this->mongo_ol->baseSystemMsg->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
		    $list['filter'] = $f;
		    $list['page'] = $p;

		    /* 返回 */
		    return $list;
		}
	}