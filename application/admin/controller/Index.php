<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Common
{
    public function index(){
        return $this->fetch();
    }
    

    // \phpmailer\Email::send('939144892@qq.com',1,1);
    public function welcome()
    {
    	// halt($_SERVER);
        return $this->fetch('' ,[
                'system' => php_uname(),
                'PHPVersion' => PHP_VERSION,
                'date' => date('Y-m-d H:i:s',time()),
                'serverAddr' => $_SERVER['SERVER_ADDR'],
                'serverPort' => $_SERVER['SERVER_PORT'],
                'serverName' => $_SERVER['SERVER_NAME'],
                'apacheVersion' => apache_get_version(),
                'maxFileSize' => ini_get('upload_max_filesize'),
        ]);
    }
}