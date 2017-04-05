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

    function client_ip(){   
        $ip = getenv('REMOTE_ADDR');   
        $ip1 = getenv('HTTP_X_FORWARDED_FOR');   
        $ip2 = getenv('HTTP_CLIENT_IP');   
        $ip1 ? $ip = $ip1 : '';   
        $ip2 ? $ip = $ip2 : '';   
        return $ip;   
    }

    function attr_api($attr_name , $attr_value = 0){
        $attr_value = $attr_value == 0 ? 1 : $attr_value;
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        $tlv_attr_name = pack('Cna*' , 4,  strlen( $attr_name ) , $attr_name);
        $tlv_attr_value = pack('CnN' , 2,  4 , $attr_value);
        $pkg = pack('C', 0x02 ) . $tlv_attr_name . $tlv_attr_value . pack('C' , 0x03);
        socket_sendto($sock, $pkg, strlen($pkg), 0, '10.128.63.250', 16100);
        socket_close($sock);
    }


?>
