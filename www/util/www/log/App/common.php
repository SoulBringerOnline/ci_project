<?php

function form_select($name , $class , $param, $data , $seleted_val = 0 )
{
    if( is_array($param) AND !empty( $param[$name] ) )
    {
        $seleted_val = $param[$name] ;
    }
    $info = '<select class="form-control btn btn-' . $class . '" name="' . $name . '">';
    foreach ($data as $key => $item) 
    {
        $info .= '<option value="' . $key .  '"';
        if( $seleted_val == $key )
        {
            $info .= ' selected'; 
        }
        $info .= '>';
        $info .= $item;
    }

    $info .= '</select>';
    return $info;
}

function lava_date( $timestamp)
{
    return date("Y-m-d H:i", $timestamp-28800);
}
    
function lava_countdown( $timestamp )
{
    $day = intval( $timestamp / 86400 ) ;
    $hour = intval( $timestamp / 3600 ) ;
    $min = intval( $timestamp % 3600 / 60 ) ;
    $sec = intval( $timestamp % 60 ) ;
    $str = '';
    if( $day )
    {
        $str .= $day . '天';
    }
    if( $hour )
    {
        $str .= $hour . '小时';
    }
    if( $min )
    {
        $str .= $min . '分';
    }  
    if( $sec )
    {
        $str .= $sec . '秒';
    }       
    return $str;
}

function get_label_class( $id )
{
    $type = $id % 6;
    switch ( $type ) 
    {
    	case 1:
    		return "label label-danger";
    		break;
    	case 2:
    		return "label label-primary";
    		break;
    	case 3:
    		return "label label-info";
    		break;
    	case 4:
            return "label label-default";
    		break;
    	case 5:
    		return "label label-warning";
    		break;
    	default:
            return "label label-success";
    		break;
    }
}
