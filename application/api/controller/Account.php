<?php
namespace app\api\controller;
use think\Controller;

class Account extends Controller
{
	private $obj;

	public function _initialize() {
		$this->obj = model('BisAccount');
	}

	public function checkUserNameExist() {
		$username = input('post.username');
		$str = $this->obj->checkUserNameExist($username);
		if($str) {
			return show(0, '该用户已注册！请重新输入用户名');
		}
	}
	
	public function index()
	{
		return $this->fetch();
	}
}