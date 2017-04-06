<?php
    date_default_timezone_set('Asia/Shanghai');
    $max_file_size=2000000;     //上传文件大小限制, 单位BYTE
    $destination_folder="uploadimg/"; //上传文件路径
    $suport_img_types = array(
            'png'=>1,
            'jpg'=>1,
            'jpeg'=>1,
            'gif'=>1,
            'bmp'=>1,
        );

    require_once './aliyun/aliyun.php';
    use \Aliyun\OSS\OSSClient;
    $client = OSSClient::factory(array(
        'AccessKeyId' => 'QW6kF97kVPu5JMPA',
        'AccessKeySecret' => 'qGlrp55o3btu2k72d9yE2F5qPkzCRw',
    ));
   // var_dump($client);exit;

   



    /*上传Object start*/
	$files = array_keys($_FILES);
	$file_tmp_name = $files[0];
   if (!is_uploaded_file($_FILES[$file_tmp_name]['tmp_name']))
    //是否存在文件
    {
         echo "图片不存在!";
         exit;
    }

    $file = $_FILES[$file_tmp_name];
    if($max_file_size < $file["size"])
    //检查文件大小
    {
        echo "文件太大!";
        exit;
    }
 
    $contents = file_get_contents($_FILES[$file_tmp_name]['tmp_name']);
    $file_type = getFileType( $contents);

    if(!$suport_img_types[ $file_type]){
         echo "图片格式不支持!";
         exit;
    }

    $file_name = "op/".md5($_FILES[$file_tmp_name]['tmp_name'].time());

    //测试了下图片上传
    // 获取图片类型
    $contenttype = "image/".$file_type;
	  $ss = array(
		    'Bucket' => 'zhuyou-p',
		    'Key' =>  $file_name,
		    'Content' => file_get_contents($_FILES[$file_tmp_name]["tmp_name"]),
		    'ContentLength' => $file["size"],
            'Endpoint'=>"http://oss-cn-beijing.aliyuncs.com",
            "ContentType" => 'image/'. $file_type
        );

        $ss = $client->putObject($ss);
        $url ="http://"."p.zy.glodon.com/".urlencode($file_name);
    $ret = array('ret'=>0, "msg"=>"ok", "pic_url"=>$url);    

        echo json_encode($ret);
	/*上传Object end*/
    function getFileType($bin)
    {
        $strInfo  = @unpack("C2chars", $bin);

        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);

        $fileType = '';
        switch ($typeCode)
        {
            case 7790:
                $fileType = 'exe';
                break;
            case 7784:
                $fileType = 'midi';
                break;
            case 8297:
                $fileType = 'rar';
                break;
            case 255216:
                $fileType = 'jpg';
                break;
            case 7173:
                $fileType = 'gif';
                break;
            case 6677:
                $fileType = 'bmp';
                break;
            case 13780:
                $fileType = 'png';
                break;
            case 208207:
                $fileType = 'doc';
                break;
            case 8075:
                $fileType = 'docx';
                break;
            default:
                $fileType = 'unknown';
                ;
        }

        return $fileType;
    }
