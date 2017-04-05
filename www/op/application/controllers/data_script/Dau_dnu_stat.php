<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dau_dnu_stat extends CI_Controller {

		function __construct() {
			parent::__construct();
		}

		public function index() {
			$dash_data =array();
			foreach (array(15,17,20) as $channel) {

				$dash_data[$channel] = $this->get_dash_stat_main($channel);
			}

			$fp = fopen('dau_dun.csv', 'w');

			$t = 0;
			foreach ($dash_data  as $k=>$item)
			{
				$temp =array();
				array_push($temp, $k);
				fputcsv($fp, $temp);
				$temp =array();
				array_push($temp, "dau");
				fputcsv($fp, $temp);
				foreach($item['dau'] as $item2)
				{
					$temp =array();
					$day_dau = $item2['day'];
					$value_dau = $item2['v'];
					array_push($temp, $day_dau);
					array_push($temp, $value_dau);
					fputcsv($fp, $temp);
				}
				$temp =array();
				array_push($temp, "dun");
				fputcsv($fp, $temp);
				foreach($item['dnu'] as $item3)
				{
					$temp =array();
					$day_dau = $item3['day'];
					$value_dau = $item3['v'];
					array_push($temp, $day_dau);
					array_push($temp, $value_dau);
					fputcsv($fp, $temp);
				}
			};
			fclose($fp);

			echo "over";
		}

		public function get_dash_stat_main($channel = 0) {
			$cur_time = time();
			$redis_gsk = new Redis();
			$_CFG['redis_host'] = "10.128.63.250";
			$_CFG['redis_port'] = 6380;
			$redis_gsk->connect( $_CFG['redis_host'], $_CFG['redis_port'] );
			$cur_time = time();
			$cur_day = intval( $cur_time / 86400 );
			$cur_hour = intval( $cur_time / 3600 );

			$stat['dau'] = $stat['dnu'] = array();
			foreach (range(0, 120) as $k) {
				$day = human_date($cur_time - (120 - $k) * 86400 );

				$key = 'STAT_CHANNEL_DAU#' . $channel . '#' . ($cur_day - 120 + $k);
				$value = $redis_gsk->get($key);
				$value = empty($value)?0:$value;
				array_push( $stat['dau'], array('day' => $day , 'v' =>$value));

				$key = 'STAT_CHANNEL_DNU#' . $channel . '#' . ($cur_day - 120 + $k);
				$value = $redis_gsk->get($key);
				$value = empty($value)?0:$value;
				array_push( $stat['dnu'], array('day' => $day , 'v' => $value));
			}
			$redis_gsk->close();
			return $stat;
		}

	}







