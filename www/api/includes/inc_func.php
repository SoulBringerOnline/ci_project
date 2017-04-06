<?php
    /* ----------------------------------------------------------------------- */
    // - 字符安全类函数
    /* ----------------------------------------------------------------------- */

    /**
    * 递归方式的对变量中的特殊字符进行转义和反转义
    *
    * @params mix  $str  数组或者字符串
    *
    * @return mix
    */
    function addslashes_deep( $str )
    {
        return is_array($str) ? array_map('addslashes_deep', $str) : addslashes($str);
    }

    function stripslashes_deep( $str )
    {
        return is_array($str) ? array_map('stripslashes_deep', $str) : stripslashes($str);
    }

    /**
    * 对 MYSQL LIKE 的内容进行转义
    *
    * @params str  $str  要转义的字符
    *
    * @return str
    */
    function mysql_like_slash( $str )
    {
        return strtr( $str, array("\\\\"=>"\\\\\\\\", '_'=>'\_', '%' => '\%') );
    }

    /**
    * 变量值格式化
    *
    * @params mix  $value   要修饰的值
    * @params str  $modify  修饰类型
    * @params str  $attrib  修饰类型的属性
    *
    * @return str  返回修饰后的字符
    */
    function f( $value, $modify, $attrib = '' )
    {
        switch( $modify ){
            /* html 编码( & , " , < , > , 空格, 换行 ) */
        case 'html': $value = strtr( htmlspecialchars($value), array(' '=>'&nbsp;',"\r\n"=>'<br />',"\n"=>'<br />') ); break;

            /* html formc 编码( & , " , < , > ) */
        case 'formc': $value = htmlspecialchars($value); break;

            /* html js string 编码(以单引号为边界). 编码( " ) 转义( ' => \' ) */
        case 'hstr': $value = addslashes( strtr($value,array('"'=>'&quot;')) ); break;

            /* 字符截取。默认截取80个字 */
        case 'truncate': $value = sub_str($value, (intval($attrib)?intval($attrib):80), true); break;

            /* 字符颜色，格式化成FONT。默认红色 */
        case 'color': $value = '<font color="'. ($attrib?$attrib:'#ff0000') .'">'. $value .'</font>'; break;

            /* 变量默认值。默认值：'', false, 0, '0', null, array() ) */
        case 'default' : $value = empty($value) ? $attrib : $value; break;

            /* 格式化时间。默认格式YYYY-MM-DD HH:II, 无效值则返回'' */
        case 'date': $value = ($value>=57600&&$value<=2147443200) ? date(f($attrib,'default','Y-m-d H:i'),$value):''; break;
        }

        return $value;
    }

    /**
    * 变量值格式化, 并输出结果
    */
    function e( $value, $modify = '', $attrib = '' )
    {
        echo f($value, $modify, $attrib);
    }


    /* ----------------------------------------------------------------------- */
    // - 字符函数
    /* ----------------------------------------------------------------------- */

    /**
    * 截取UTF-8编码下字符串的函数
    *
    * @params str  $str     被截取的字符串
    * @params int  $length  截取的长度
    * @params bol  $append  是否附加省略号
    *
    * @return str
    */
    function sub_str( $str, $length = 0, $append = true )
    {
        $str = trim($str);
        $len = strlen($str);

        if( $length == 0 || $length >= $len ){
            return $str;
        }
        else if( $length < 0 ){
            $length = $len + $length;
            if( $length < 0 ){
                $length = $len;
            }
        }

        if( function_exists('mb_substr') ){
            $newstr = mb_substr($str, 0, $length, 'UTF-8');
        }else if( function_exists('iconv_substr') ){
            $newstr = iconv_substr($str, 0, $length, 'UTF-8');
        }else{
            $newstr = trim_right(substr($str, 0, $length));
        }

        if( $append && $str != $newstr ){
            $newstr .= '...';
        }

        return $newstr;
    }

    /**
    * 去除字符串右侧可能出现的乱码
    *
    * @params str  $str  字符串
    *
    * @return str  
    */
    function trim_right( $str )
    {
        $len = strlen( preg_replace('/[\x00-\x7F]+/', '', $str) ) % 3;

        if( $len > 0 ){
            $str = substr($str, 0, 0-$len);
        }

        return $str;
    }

    /**
    * 获取数据表名（加前辍处理）
    *
    * @params str  $tname   数据表名
    *
    * @return str  
    */
    function tname( $tname )
    {
        global $_CFG;
        return $_CFG['tblpre'].$tname;
    }

    /* ------------------------------------------------------ */
    // - 异步JSON
    /* ------------------------------------------------------ */

    /**
    * 将数据格式化成JSON
    * 
    * @params str  $error    指明是否有错
    * @params str  $message  错误消息
    * @params str  $content  内容
    * @params arr  $append   追加JSON项
    */
    function make_json_response( $error = 0, $message = '', $content = '', $append = array() )
    {
        /* 初始化 */
        $res = array( 'error'=>$error, 'message'=>$message, 'content'=>$content );

        /* 辅助项 */
        foreach( $append AS $key=>$value ){
            $res[$key] = $value;
        }

        /* JSON编码，输出 */
        exit( json_encode($res) );
    }

    function make_json_ok( $message = '', $content = '', $append = array() )
    {
        make_json_response(0, $message, $content, $append);
    }

    function make_json_fail( $message = '' )
    {
        make_json_response(1, $message);
    }


    /* ----------------------------------------------------------------------- */
    // - 其他函数
    /* ----------------------------------------------------------------------- */
    /**
    * 页面跳转
    */
    function redirect( $url )
    {
        header('location:'.$url); exit();
    }

    /**
    * 将字节转成可阅读格式
    *
    * @params int  $num  要转化的数字
    */
    function bitunit( $num )
    {
        $unit = array(' B',' KB',' MB',' GB');

        for ( $i = 0 ; $i < count($unit); $i++ ){
            /* 1024B 会显示为 1KB */
            if( $num >= pow(2, 10*$i)-1 ){
                $bit_size = (ceil($num / pow(2, 10*$i)*100)/100) . $unit[$i];
            }
        }

        return $bit_size;
    }


    /* ------------------------------------------------------ */
    // - HTML文件下载头
    /* ------------------------------------------------------ */

    /**
    * HTTP导出
    *
    * @params str  $file     导出的文件名
    * @params str  $data     导出的数据
    * @params str  $oencode  输出编码，'UTF-8', 'GB2312'
    */
    function http_download( $file, $data, $oencode = 'UTF-8' )
    {
        /* 输出数据导出的文件头 */
        http_download_header($file);

        /* 编码并输出数据 */
        echo http_download_encode($data, $oencode); exit();
    }
    function http_download_header( $file )
    {
        /* 文件扩展名 */
        $ext = end( explode('.',$file) );

        /* HTML文件头的内容类型 */
        $ctype = array();
        $ctype['sql'] = 'text/plain';
        $ctype['csv'] = 'application/vnd.ms-excel';

        /* 输出文件头 */
        header('Content-Type: '.$ctype[$ext]);                      //文件类型
        header('Content-disposition: attachment; filename='.$file); //文件名称
    }
    function http_download_encode( $str, $oencode, $iencode = 'UTF-8' )
    {
        if( function_exists('mb_convert_encoding') ){
            return mb_convert_encoding($str, $oencode, $iencode);
        }

        if( function_exists('iconv') ){
            return iconv($iencode, $oencode.'//IGNORE', $str);
        }

        return $str;
    }

    function client_ip(){   
        $ip = getenv('REMOTE_ADDR');   
        $ip1 = getenv('HTTP_X_FORWARDED_FOR');   
        $ip2 = getenv('HTTP_CLIENT_IP');   
        $ip1 ? $ip = $ip1 : '';   
        $ip2 ? $ip = $ip2 : '';   
        return $ip;   
    }

	function split_str($str)
	{
		$result = array();
		$tempdata = explode("&", $str);
		foreach($tempdata as $data)
		{
			list($key, $value) = explode("=", $data);
			$result[$key] = $value;
		}
		return $result;
	}

	function human_time($timestamp)
	{
		if(empty($timestamp))
		{
			return null;
		}
		else
		{
			return date("Y-m-d H:i", $timestamp);
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
					return '工程砖家大评比';
					break;
				default:
					return $action_id;
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
			}
		}
	}

	//发送http get方式请求
	function send_request_get($url)
	{
		if(empty($url)) exit;

		//初始化
		$ch = curl_init();

		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		//执行并获取HTML文档内容
		$output = curl_exec($ch);

		//释放curl句柄
		curl_close($ch);
	}

?>
