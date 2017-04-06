<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/12/9
 * Time: 16:27
 */
	date_default_timezone_set('Asia/Shanghai');
	$max_file_size=280000000;     //上传文件大小限制, 单位BYTE
	$destination_folder="libao"; //上传文件路径


	require_once './aliyun/aliyun.php';
	use \Aliyun\OSS\OSSClient;
	$client = OSSClient::factory(array(
		'AccessKeyId' => 'QW6kF97kVPu5JMPA',
		'AccessKeySecret' => 'qGlrp55o3btu2k72d9yE2F5qPkzCRw',
	));
	// var_dump($client);exit;





	/*上传Object start*/

	if (!is_uploaded_file($_FILES["upfile"]['tmp_name']))
		//是否存在文件
	{
		echo "图片不存在!";
		exit;
	}

	$file = $_FILES["upfile"];
	if($max_file_size < $file["size"])
		//检查文件大小
	{
		echo "文件太大!";
		exit;
	}

	$contents = file_get_contents($_FILES["upfile"]['tmp_name']);

	$dir = "libao";

	$file_name = "$destination_folder/".(time()."_".$_FILES["upfile"]['name']);

	//测试了下图片上传
	// 获取图片类型
	$contenttype = "image/png";
	$ss = array(
		'Bucket' => 'zhuyou-f',
		'Key' =>  $file_name,
		'Content' => $contents,
		'ContentLength' => $file["size"],
		'Endpoint'=>"http://oss-cn-beijing.aliyuncs.com",
	);

	$ss = $client->putObject($ss);
	$url ="http://"."ugc.zy.glodon.com/".urlencode($file_name);
	$ret = array('ret'=>0, "msg"=>"ok", "url"=>$url, "path"=>$file_name, "file_name"=>$_FILES["upfile"]['name'], "file_size"=>$file["size"]);

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