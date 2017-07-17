<?php
namespace app\index\controller;

use think\Controller;

class Pay extends Common
{
    public function index() {
    	$user = $this->getLoginUser();
		if(!$user) {
    		$this->error('请先登录','user/login');
    	}

    	$id = input('get.id', 0, 'intval');
    	if(!$id) {
    		$this->error('非法访问');
    	}

    	$deal = Model('Order')->find($id);
    	// $deal = $deal->toArray();
    	// var_dump($deal);exit;
    	return $this->fetch('', [
    		'controller' => 'pay',
    		'deal' => $deal,
    	]);
    }

    public function paysuccess() {
    	return $this->fetch();
    }
}