<?php
    //标签class
    function get_random_state( $id )
    {
        if(is_numeric($id))
        {
            $type = $id % 6;
        }
        elseif(is_string($id))
        {
            $type = crc32($id) % 6; 
        }
        
        switch ( $type ) 
        {
            case 1:
                return "default";
                break;
            case 2:
                return "primary";
                break;
            case 3:
                return "success";
                break;
            case 4:
                return "info";
                break;
            case 5:
                return "warning";
                break;
            default:
                return "danger";
                break;
        }
    }

	//标签class
	function get_state( $id )
	{
		switch ( intval($id) )
		{
			case 0:
				return "warning";
				break;
			case 1:
				return "success";
				break;
			case -1:
				return "danger";
				break;
			default:
				return "danger";
				break;
		}
	}


	//human_time
    function human_time($timestamp)
    {
	    if(empty($timestamp))
	    {
		    return null;
	    }
	    else
	    {
		    return date("Y-m-d H:i:s", $timestamp);
	    }
    }

    function human_date($timestamp)
    {
	    if(empty($timestamp))
	    {
		    return null;
        }
	    else
	    {
		    return date("Y-m-d", $timestamp);
        }
    }

    function get_random_news_img( $id )
    {
        $type = 3;
        if(is_numeric($id))
        {
            $type = $id % 20 + 1;
        }
        elseif(is_string($id))
        {
            $type = crc32($id) % 20 + 1;
        }
        else
        {
            $type = rand(1, 20);
        }
        // return "http://static.zhulong.com/photo/small/201508/18/145350clesfesiatxwmfbt_0_0_250_187.jpg";
        return 'http://img.zy.glodon.com/news/' . $type . '.jpg';
    }

    function strip_html_tags($content){
        // $c = strip_tags($content , '<p><br><br/><img>');
        // $c = preg_replace("/style=(.+?['|\"])/i",'',$c);
        // $c = preg_replace("/class=.+?['|\"]/i",'',$c);
        // preg_match ("/<(img|IMG)(.*)(src|SRC)=[\"|'|]{0,}[\"|'|\s]{0,}/isU",$c,$out); 
        $c = preg_replace("/<p (.*?)>/","<p>",strip_tags($content , '<b><p><br><br/><img>'));
        $c = preg_replace("/style=(.+?['|\"])/i",'',$c);
        $c = preg_replace("/class=.+?['|\"]/i",'',$c);
        $c = str_replace('<br>', '<p></p>',  $c);
        $c = str_replace('<br/>', '<p></p>',  $c);

        return $c;
        // return preg_replace("/<p (.*)[\"|'| ]>/", "<p>", strip_tags($content , '<p><br><br/><img>'));
    }

    function pages($cur_page, $params = array(), $max_page = null, $max_count = null){
        $param = '';
	    unset($params['p']);
        foreach ($params as $key => $value) {
            $param .= '&' . $key . '=' . $value;
        }
        if($cur_page < 20)
        {
            $start_page = 1; 
            $end_page = 20;
        }
        else
        {
            $start_page = $cur_page - 10;
            $end_page = $cur_page + 10;
        }
	    if(!is_null($max_page))
	    {
		    if($max_page < $end_page)
		    {
			    $end_page = $max_page;
		    }
	    }

	    if(is_null($max_page))
	    {
		    echo '<ul class="pagination pagination-sm">';
		    foreach (range($start_page, $end_page) as $p){
			    if($cur_page == $p){echo '<li class="active">';}else{echo '<li>';}
			    echo '<a href="' . current_url() . '?p=' . $p . $param . '">';
			    echo $p ;
			    echo '</a>';
			    echo '</li>';
		    }
	    }
	    else
	    {
		    if($max_page != 0)
		    {
			    echo '<ul class="pagination pagination-sm">';
			    foreach (range($start_page, $end_page) as $p){
				    if($cur_page == $p){echo '<li class="active">';}else{echo '<li>';}
				    echo '<a href="' . current_url() . '?p=' . $p . $param . '">';
				    echo $p ;
				    echo '</a>';
				    echo '</li>';
			    }
		    }
	    }
	    if(!is_null($max_count))
	    {
		    echo '<li><a class="label label-success">共'.$max_count.'条</a></li>';
	    }
        echo '</ul>';
    }

	if ( ! function_exists('paginations')) {
		/**
		 * 分页函数
		 *
		 * @param int $total 总条目
		 * @param int $page	当前页码
		 * @param int $pagesize 每页条数
		 * @param int $offset 页码显示数量控制（n*2+1）
		 * @param string $url 基础URL
		 * @param string $tag 页码标签
		 * @return string
		 */
		function paginations($total, $page = 1, $pagesize = 20, $offset = 3, $url = null)
		{
			if($total <= $pagesize) return '';
			$page = max(intval($page), 1);
			$pages = ceil($total/$pagesize);
			$page = min($pages, $page);
			$prepage = max($page-1, 1);
			$nextpage = min($page+1, $pages);
			$from = max($page - $offset, 2);
			if ($pages - $page - $offset < 1) $from = max($pages - $offset*2 - 1, 2);
			$to = min($page + $offset, $pages-1);
			if ($page - $offset < 2) $to = min($offset*2+2, $pages-1);
			$more = 1;
			if ($pages <= ($offset*2+5))
			{
				$from = 2;
				$to = $pages;
				$more = 0;
			}
			$str = '';
			if ($page <= 1)
			{
				$str .= '<li class="paginate_button previous disabled" aria-controls="jq-datatables-example" tabindex="0" id="jq-datatables-example_previous"><a href="#">上一页</a></li>';
			}
			else
			{
				$str .= '<li class="paginate_button next" aria-controls="jq-datatables-example" tabindex="0" id="jq-datatables-example_next"><a href="'.paginations_url($url, $prepage).'">上一页</a></li>';
			}

			$str .= $page == 1 ? '<li class="paginate_button active" aria-controls="jq-datatables-example" tabindex="0"><a href="javascript:void(0);">1</a></li>' : '<li class="paginate_button " aria-controls="jq-datatables-example" tabindex="0"><a href="'.paginations_url($url, 1).'">1'.($from > 2 && $more ? ' ...' : '').'</a></li>';
			if ($to >= $from)
			{
				for($i = $from; $i <= $to; $i++)
				{
					$str .= $i == $page ? '<li class="paginate_button active" aria-controls="jq-datatables-example" tabindex="0"><a href="javascript:void(0);">'.$i.'</a></li>' : '<li class="paginate_button " aria-controls="jq-datatables-example" tabindex="0"><a href="'.paginations_url($url, $i).'">'.$i.'</a></li>';
				}
			}
			if ($page >= $pages)
			{
				$str .= '<li class="paginate_button previous disabled" aria-controls="jq-datatables-example" tabindex="0" id="jq-datatables-example_previous"><a href="#">下一页</a></li>';
			}
			else
			{
				$str .= '<li class="paginate_button next" aria-controls="jq-datatables-example" tabindex="0" id="jq-datatables-example_next"><a href="'.paginations_url($url, $nextpage).'">下一页</a></li>';
			}
			return $str;
		}

		function paginations_url($url, $page) {
			return str_replace("p%", $page, $url);
		}
	}

	function action_type($action_id)
	{
		if(empty($action_id))
		{
			return null;
		}
		else
		{
			switch ($action_id)
			{
				case 1:
					return "积分兑换流量";
				    break;
				case 2:
					return "新手任务兑换流量";
					break;
				case 20000:
					return '"工程砖家大评比"';
					break;
				default:
					return $action_id;
					break;
			}
		}
	}

	function carrieroperator_type($type)
	{
		if(empty($type))
		{
			return null;
		}
		else
		{
			switch ($type)
			{
				case "yidong":
					return "移动";
					break;
				case "liantong":
					return "联通";
					break;
				case "dianxin":
					return "电信";
					break;
				default:
					return $type;
					break;
			}
		}
	}

	function exchange_type($type)
	{
		if(empty($type))
		{
			return null;
		}
		else
		{
			switch ($type)
			{
				case "移动":
					return "yidong";
					break;
				case "联通":
					return "liantong";
					break;
				case "电信":
					return "dianxin";
					break;
				default:
					return $type;
					break;
			}
		}
	}

	function flow_exchange_status($status)
	{
		if(empty($status))
		{
			return '支付中...';
		}
		else
		{
			switch ($status)
			{
				case -10:
				case -30:
				case 0:
					return "支付中...";
					break;
				case 1:
					return "成功";
					break;
				case -1:
				case -20:
					return "失败";
					break;
				default:
					return $status;
					break;
			}
		}
	}

	function exchange_status($status)
	{
		if(is_null($status))
		{
			return 1;
		}
		else
		{
			switch ($status)
			{
				case '支付中':
					return 0;
					break;
				case '成功':
					return 1;
					break;
				case '失败':
					return -1;
					break;
				default:
					return $status;
					break;
			}
		}
	}

	function promotion_exchange_status($status)
	{
		if(empty($status))
		{
			return '审核中...';
		}
		else
		{
			switch ($status)
			{
				case 0:
					return "审核中...";
					break;
				case 1:
					return "审核通过";
					break;
				case -1:
					return "未通过";
					break;
				default:
					return $status;
					break;
			}
		}
	}

	function help_exchange_type($status)
	{
		if(empty($status))
		{
			return '其他';
		}
		else
		{
			switch ($status)
			{
				case 1:
					return "知识";
					break;
				case 2:
					return "项目";
					break;
				case 3:
					return "看点";
					break;
				case 4:
					return "账号与登录";
					break;
				case 5:
					return "对话";
					break;
				case 6:
					return "我的";
					break;
				default:
					return "其他";
					break;
			}
		}
	}

	function proj_state_type($status)
	{
		if(empty($status))
		{
			return '未认证';
		}
		else
		{
			switch ($status)
			{
				case 1:
					return "未认证";
					break;
				case 2:
					return "认证中";
					break;
				case 3:
					return "认证通过";
					break;
				case 4:
					return "认证未通过";
					break;
				case 999:
					return "权限调整";
					break;
				default:
					return "未认证";
					break;
			}
		}
	}

	function project_type($status)
	{
		if(empty($status))
		{
			return '其他';
		}
		else
		{
			switch ($status)
			{
				case 1:
					return "建筑工程";
					break;
				case 2:
					return "市政工程";
					break;
				case 3:
					return "电力工程";
					break;
				case 4:
					return "铁路工程";
					break;
				case 5:
					return "公路工程";
					break;
				case 6:
					return "冶金工程";
					break;
				case 7:
					return "石化工程";
					break;
				case 8:
					return "其他";
					break;
				default:
					return $status;
					break;
			}
		}
	}

	/**
	 * 计算项目工期 /天
	 * @param $start
	 * @param $end
	 * @return float
	 */
	 function get_proj_time($start, $end)
	{
		$start = empty($start)? 0: intval($start);
		$end = empty($end)? 0: intval($end);

		return ($end - $start) / 86400;
	}

	//发送http get方式请求
	function send_request_get($url)
	{
		if(empty($url)) exit;

		//初始化
		$ch = curl_init();

		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT,60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		//执行并获取HTML文档内容
		$output = curl_exec($ch);

		//释放curl句柄
		curl_close($ch);
	}

	function activity_position_state($state)
	{
		if(empty($state))
		{
			return '未上线';
		}
		else
		{
			switch ($state)
			{
				case 0:
					return "未上线";
					break;
				case 1:
					return "已上线";
					break;
				default:
					return $state;
					break;
			}
		}
	}

	function activity_position_name($type)
	{
		if(empty($type))
		{
			return '';
		}
		else
		{
			switch ($type)
			{
				case 1:
					return "下载配置页（PC端）";
					break;
				case 2:
					return "App启动页";
					break;
				case 3:
					return "下载配置页（手机端）";
					break;
				default:
					return $type;
					break;
			}
		}
	}

	function acticity_operater_type($type)
	{
		if(empty($type))
		{
			return '';
		}
		else
		{
			switch ($type)
			{
				case 1:
					return "添加";
					break;
				case 2:
					return "修改";
					break;
				default:
					return $type;
					break;
			}
		}
	}

?>
