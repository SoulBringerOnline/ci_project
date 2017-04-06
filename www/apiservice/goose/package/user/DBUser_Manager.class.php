<?php

namespace Goose\Package\User;

use \Libs\Mongo\MongoDB;

class DBUser_Manager {

    private static $intance = NULL;
    private  $mongo_ol =null;
    private function __construct() {
        $this->mongo_ol = MongoDB::getMongoDB("online","gsk_ol");
    }

    public static function instance() {
        if(self::$intance === NULL) {
            self::$intance = new self();
        }
        return self::$intance;
    }

    public function get_user_uin($where)
    {
        $user = $this->mongo_ol->user->findOne($where, array('f_uin'));

        return $user['f_uin'];
    }

    public function get_user_phone($where)
    {
        $user = $this->mongo_ol->user->findOne($where, array('f_phone'));

        return $user['f_phone'];
    }

    public function get_user_name($where)
    {
        $user = $this->mongo_ol->user->findOne($where, array('f_name'));

        return $user['f_name'];
    }

    public function get_fields($where, $fields) {
        $result = $this->mongo_ol->user->findOne($where, $fields);
        if($result){
            return $result;
        }

        return false;
    }

    public function checkFriend($uin, $friend_uin) {
        $where = array('f_uin'=>$uin);
        $result = $this->mongo_ol->user->findOne($where, array('f_friend_list'));
        if(!$result){
            return false;
        }
        $friendList = $result['f_friend_list'];
        foreach ($friendList as $val) {
            if($val['f_uin'] == $friend_uin){
                return true;
            }
        }

        return false;
    }

    public function checkGroup($uin, $group_id) {
        $where = array('f_uin'=>$uin);
        $result = $this->mongo_ol->user->findOne($where, array('f_im_group'));
        if(!$result){
            return false;
        }
        $imGroup = $result['f_im_group'];
        foreach ($imGroup as $val) {
            if($val['f_group_id'] == $group_id){
                return true;
            }
        }

        return false;
    }

    public function checkProject($uin, $prj_id) {
        $where = array('f_uin'=>$uin);
        $result = $this->mongo_ol->user->findOne($where, array('f_project_list'));
        if(!$result){
            return false;
        }
        $friendList = $result['f_project_list'];
        foreach ($friendList as $val) {
            if($val['f_prj_id'] == $prj_id){
                return true;
            }
        }

        return false;
    }
} 
