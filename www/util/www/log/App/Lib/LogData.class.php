<?php
class LogData{
    public function get( $query ){
		try{
		    $m = new MongoClient("mongodb://192.168.165.240:27017");
		    $db = new MongoDB($m, 'gsk');
		    $where = array();
			$f = array();
			$f['query_limit'] = isset($query['query_limit']) ? intval($query['query_limit']) : 500;
			$f['query_text'] = isset($query['query_text']) ? trim($query['query_text']) : '';
			$f['query_page'] = isset($query['query_page']) ? intval($query['query_page']) : 0;
            // if(strlen($f['query_text']) == 0 )
            // {
            //     $f['query_text'] = 'msg_type=1';
            // }

			$search_criteria = array( );
			if( strlen($f['query_text']) )
			{
				$query_param = explode(" ", $f['query_text']);
				foreach ($query_param as $q)
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
                        case 'f_name':
                        case 'f_cmd_info':
                            $search_criteria[$key] = new MongoRegex('/' .  $value . '/');  
                            break;
                        case 'f_uin':
                        case 'f_dye':
                        // case 'f_msg_type':
                            $value = intval($value);
                            
                        default:
                            $search_criteria[$key] = array( $flag => $value );
                            break;
                        }
                    }
                }
            }
            if( $f['query_page'] > 0 )
            {
                $skip = $f['query_limit'] * $f['query_page'];
                $cursor = $db->log->find( $search_criteria )->sort( array( 'f_time' => -1 ) )->limit( $f['query_limit'] )->skip( $skip );
            }
            else
            {
                $cursor = $db->log->find( $search_criteria )->sort( array( 'f_time' => -1 ) )->limit( $f['query_limit'] );
            }
            
            $m->close();
            return  $cursor;
        } catch(MongoConnectionException $e) {
            Log::fatal('[MONGO-ERROR]' . $e->getMessage() );
        }
        return FALSE;
    }
}
