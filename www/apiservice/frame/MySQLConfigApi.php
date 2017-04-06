<?php

/**** example**************************

require_once '/home/work/conf/api/MySQLConfigApi.php';
$cfg =  MySQLConfigApi::GetCfgByServDB('virus', 'dolphin');    
 
***************************************/


if(class_exists('MySQLConfigApi')) {
    return;
}

class MySQLConfigApi {
    
    #const CFG_FILE_PATH = '/home/work/conf/real/bj/mysql/online/xxx.mysql.ini'; //xxx用来替换key
    const CFG_FILE_PATH = '/home/work/conf/mysql/xxx.mysql.ini';
    private static $allCfg = array();
    
    
    public static function GetCfgByServKey($key) {
     //   file_put_contents('/tmp/caaaa', $key."\n", FILE_APPEND);
        if (empty($key)) {
            return array();
        }
        
        if (!empty(self::$allCfg[$key])) {
            return self::$allCfg[$key];
        }
        
        $data = self::GetCfgFromFile($key);
    //    if($key=='passport')
   //     file_put_contents('/tmp/caaaa', print_r($data,1)."\n", FILE_APPEND);
        self::$allCfg[$key] = $data;
        return self::$allCfg[$key];
    }
    
    
    public static function GetCfgByServDB($key, $db) {       
        if (empty($key) || empty($db)) {
            return NULL;
        } 
        $data = self::GetCfgByServKey($key);

        
        if (empty($data)) {
            return NULL;
        }    
        
        return $data[$db];
    }
    
    
    private static function GetCfgFromFile($key) {
        $file = str_replace('xxx', $key, self::CFG_FILE_PATH);
        
        if (!is_file($file)) {
            return NULL;
        }
        
        $handle = fopen($file, 'r');
        if (!$handle) {
            return NULL;           
        }
       
        $data = array();
        while (!feof($handle)) {
            $line = fgets($handle);
            
            if ($line === FALSE ) {
                continue;
            }
            
            $line = trim($line);
                        
            if (empty($line) || $line[0] == '#') {  //以#开头的是注释
                continue;
            }
            
            $pattern = "/[\s]+/";
            $items = preg_split($pattern, $line);
            
            if (empty($items)) {
                continue;
            }
            
            
            $arr = array();
            foreach ($items as $item) {            
                $keyValue = explode('=', $item);
               
                $key = $keyValue[0];
                $value = $keyValue[1];
                
                if (empty($key) || $value === '' || $value === NULL){
                    continue;
                }   
                
                $key = strtoupper($key);
                $arr[$key] = $value;                               
            }
            
            if (empty($arr['DB'])) {
                continue;
            }
            
            if (empty($arr['HOST']) || empty($arr['USER'])) {
                continue;
            }
            
            if (isset($arr['PASS']) && !isset($arr['PASSWORD'])) {
                $arr['PASSWORD'] = $arr['PASS'];    
            }            
            
            $db = $arr['DB'];
            $master = $arr['MASTER'] > 0 ? TRUE : FALSE;
            
            
            if (!isset($data[$db])) {
                $data[$db] = array();    
            }
            
            if ($master) {
                $data[$db]['MASTER'] = $arr;    
            } else {                
                if (!isset($data[$db]['SLAVES'])) {
                    $data[$db]['SLAVES'] = array();    
                }  
                $data[$db]['SLAVES'][] = $arr;
            }              
        }

        fclose($handle);
        return $data;      
    } 
    
} 
