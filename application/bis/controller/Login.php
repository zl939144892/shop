<?php
namespace app\bis\controller;
use think\Controller;

class Login extends Controller
{
	private $obj;

	public function _initialize()
	{
		$this->obj = model('BisAccount');
	}

	public function index()
	{
		if(request()->isPost()) {
			// 登陆逻辑
			// 获取相关数据
			$data = input('post.');

			// 数据校验
			if(!validate('Bis')->scene('login')->check($data)) {
	    		$this->error(validate('Bis')->getError());//返回错误信息
	    	}

			// 通过用户名获取用户相关信息
			$ret = $this->obj->get(['username'=>$data['username']]);
			if(!$ret) {
				$this->error('该用户不存在');
			}elseif($ret->status != 1) {
				$this->error('该用户信息正在审核中');
			}

			// 密码校验
			if(md5(md5($data['password']).$ret['code']) != $ret->password){
				$this->error('密码不正确');
			}

			$this->obj->updateById(['last_login_time'=>time(), 'last_login_ip'=>request()->ip()], $ret->id);

			// 保存用户信息到session中  bisAccount是变量名  bis是作用域
			session('bisAccount', $ret, 'bis');

			return $this->success('登陆成功', url('index/index'));
		}else {
			// 获取session
			$account = session('bisAccount', '', 'bis');

			if($account && $account->id) {
				return $this->redirect('index/index');
			}
			return $this->fetch();
		}
	}

	public function logout()
	{
		// 清楚session
		session(null, 'bis');
		$this->redirect('login/index');
	}
}