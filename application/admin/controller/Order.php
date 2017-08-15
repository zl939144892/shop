<?php
namespace app\admin\controller;

use think\Controller;

class Order extends Common
{
    public function index(){
    	$type = input('get.type', 0, 'intval');
    	$orders = $this->obj->getOrder($type);

        return $this->fetch('', [
        	'orders' => $orders,
        ]);
    }
}