<?php
//比邻云盘 phpSdk示例 2020年9月27日 By Myxf
require("bilnnb.php"); //引入sdk

$email = ""; //修改为您的登录邮箱
$secret_key = ""; //云盘后台webDav创建的接口密码

$yun = new ext_bilnnYun($email,$secret_key); //实例化方法，并初始化


//列出目录方法开始，参数为目录名称，默认“/”为列出根目录，指定目录以“/”开头，以“/”结尾
$response = $yun->getDirectory("/");
print_r(json_decode($response,true));
//列出目录方法结束


/*
//文件上传方法开始，第一个参数为文件本地路径（绝对路径），第二个参数为上传目录，默认“/”为根目录，指定目录以“/”开头，以“/”结尾
$filePath = "D:\你好世界.txt";
$response = $yun->uploadFile($filePath,"/");
print_r(json_decode($response,true));
//文件上传方法结束
*/

/*
//获取文件外链地址方法开始，参数为文件在云盘中的相对路径，必须以“/”开头，不能有多余“/”符号
$filePath = "/你好世界.txt";
$response = $yun->getDownLoadUrl($filePath);
print_r(json_decode($response,true));
//获取文件外链地址方法结束
*/

/*
//创建文件夹方法开始，参数文件夹相对路径，必须以“/”开头，不能有多余“/”符号
$filePath = "/我要创建目录/";
$response = $yun->newFolder($filePath);
print_r(json_decode($response,true));
//创建文件夹方法结束
*/

/*
//删除文件/文件夹方法开始，参数为文件或文件夹在云盘中的相对路径，必须以“/”开头，不能有多余“/”符号，如果是删除目录则必须以“/”结尾
$filePath = "/测试目录/";
$response = $yun->delFile($filePath);
print_r(json_decode($response,true));
//删除文件方法结束
*/

/*
//移动文件方法开始（重命名同此方法），参数为文件或文件夹在云盘中的相对路径，必须以“/”开头，不能有多余“/”符号，如果是操作目录则必须以“/”结尾
$filePath = "/你好世界.txt"; //原文件路径
$toPath = "/新的-你好世界.txt"; //新路径或名称
$response = $yun->move($filePath,$toPath);
print_r(json_decode($response,true));
//移动文件方法结束
*/
?>