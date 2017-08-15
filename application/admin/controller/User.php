<?php
namespace app\admin\controller;

use think\Controller;

class User extends Common
{
    public function index(){
    	$type = input('get.type', 0, 'intval');
    	$users = $this->obj->getUser($type);

        return $this->fetch('', [
        	'users' => $users,
        ]);
    }
}