<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index(){
        return $this->fetch();
    }
    
    public function welcome(){
    	// \phpmailer\Email::send('939144892@qq.com',1,1);
    	return $this->fetch();
    }
}