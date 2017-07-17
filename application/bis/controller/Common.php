<?php
namespace app\bis\controller;
use think\Controller;

class Common extends Controller
{
	private $account;
	public function _initialize()
	{
		// 判断用户是否登陆
		$isLogin = $this->isLogin();
		if(!$isLogin) {
			return $this->redirect(url('login/index'));
		}
	}

	public function isLogin()
	{
		// 获取session
		$user = $this->getLoginUser();
		if($user && $user->id) {
			return true;
		}
		return false;
	}

	public function getLoginUser()
	{
		if(!$this->account) {
			$this->account = session('bisAccount', '', 'bis');
		}
		return $this->account;
	}
}