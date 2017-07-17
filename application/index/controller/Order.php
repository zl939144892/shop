<?php
namespace app\index\controller;

use think\Controller;

class Order extends Common
{
	public function index() {
		$user = $this->getLoginUser();
		if(!$user) {
    		$this->error('请先登录','user/login');
    	}

    	$id = input('post.id', 0, 'intval');
    	if(!$id) {
    		$this->error('非法访问');
    	}
    	$dealCount = input('post.deal_count', 0, 'intval');
    	$totalPrice = input('post.total_price', 0, 'intval');

    	$deal = Model('Deal')->find($id);
    	if(!$deal || $deal->status != 1) {
    		$this->error('商品不存在');
    	}

    	if(empty($_SERVER['HTTP_REFERER'])) {
    		$this->error('请求不合法');
    	}

    	$OrderSn = setOrderSn();
    	// var_dump($dealCount);exit;
    	$data = [
    		'deal_id' => $id,
    		'user_id' => $user->id,
    		'out_trade_no' => $OrderSn,
    		'deal_count' => $dealCount,
    		'total_price' => $totalPrice,
    		'username' => $user->username,
    		'referer' => $_SERVER['HTTP_REFERER'],
    	];

    	try {
    		$orderId = $this->obj->add($data, 1);
    	}catch(\Exception $e) {
    		$this->error('订单处理失败');
    	}

    	$this->redirect(url('pay/index',['id' => $orderId]));
	}

    public function confirm() {
    	if(!$this->getLoginUser()) {
    		$this->error('请先登录','user/login');
    	}

    	$id = input('get.id', 0, 'intval');
    	if(!$id) {
    		$this->error('非法访问');
    	}
    	$count = input('get.count', 1, 'intval');

    	$deal = Model('Deal')->find($id);

    	if(!$deal || $deal->status != 1) {
    		$this->error('商品不存在');
    	}
    	$deal = $deal->toArray();

    	return $this->fetch('', [
    		'controller' => 'pay',
    		'count' => $count,
    		'deal' => $deal,
    	]);
    }
}