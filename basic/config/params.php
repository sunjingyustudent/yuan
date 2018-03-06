<?php

return [
    'adminEmail' => 'admin@example.com',
    'oss'=>'sunjingyu.oss-cn-shanghai.aliyuncs.com',
     'UPLOAD_SITEIMG_OSS' => array (
        'maxSize' => 30 * 1024 * 1024,//文件大小
        'rootPath' => './',
        'saveName' => array ('uniqid', ''),
        'savePath' => 'aliyun/',    //保存路径
        'driver' => 'Aliyun',//上传驱动文件
        'driverConfig' => array (
            'AccessKeyId' => 'LTAIXI2jNjs28wG6',    //AccessKeyId
            'AccessKeySecret' => 'FOBMkMk5LVweID4jue8bEvGI9avQ3A',//AccessKeySecret
            'domain' => 'sunjingyu.oss-cn-shanghai.aliyuncs.com',        //域名
            'Bucket' => 'sunjingyu',         //Bucket
            'Endpoint' => 'http://oss-cn-shanghai.aliyuncs.com',//所属的节点 杭州的服务器
        )),
    "templateid_sms_1" => "3059487",
    "AppKey_sms" =>"0154bdce25f88fed13fbc150a36183c2",
    "AppSecret_sms" =>"5b29d5cfb09c",
    ""
];
