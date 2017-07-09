<?php
namespace app\api\controller;
use think\Controller;

class Bis extends Controller
{
	private $obj;

	public function _initialize() {
		$this->obj = model('Bis');
	}

	public function checkBisNameExist() {
		$name = input('post.name');
		$str = $this->obj->checkBisNameExist($name);
		if($str) {
			return show(0, '该商户已入驻！请核对后重新输入');
		}
	}
	
	public function index()
	{
		return $this->fetch();
	}
}