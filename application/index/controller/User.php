<?php
namespace app\index\controller;

use think\Controller;

class User extends Common
{
    public function login()
    {
        // 判断是否已经登陆
        $user = session('o2o_user', '','o2o');
        if($user && $user['id']) {
            $this->redirect(url('index/index'));
        }
        return $this->fetch();
    }

    public function register()
    {
    	if(request()->isPost()) {
    		$data = input('post.');

            // 检查验证码
            if(!$data['verifycode']) {
                $this->error('请输入验证码');
            }elseif(!captcha_check($data['verifycode'])) {
    			// 校验失败
    			$this->error('验证码有误');
    		}

    		// 数据校验
            if($data['password'] != $data['repassword']) {
                $this->error('两次密码不一致');
            }
    		$this->check('', $data);

    		// 自动生成密码的加盐字符串
    		$data['code'] = mt_rand(1000,10000);
    		$data['password'] = md5(md5($data['password'].$data['code']).$data['code']);

    		try {
    			$res = $this->obj->add($data);
    		}catch (\Exception $e) {
    			$this->error($e->getMessage());
    		}

    		if($res) {
    			$this->success('注册成功', url('user/login'));
    		}else {
    			$this->error('注册失败');
    		}
    	}else {
        	return $this->fetch();
    	}
    }


    public function logincheck()
    {
        if(!request()->isPost()) {
            $this->error('非法提交');
        }

        $data = input('post.');

        // 数据校验
        $this->check('login', $data);

        try {
            $user = $this->obj->getUserByUsername($data['username']);
        }catch(\Exception $e) {
            $this->error($e->getMessage());
        }

        if(!$user || $user->status != 1) {
            $this->error('该用户不存在！');
        }

        // 校验密码
        $password = md5(md5($data['password'].$user['code']).$user->code);
        if($password != $user->password) {
            $this->error('密码输入错误！');
        }

        // 登陆成功后的操作
        $this->obj->updateById([
            'last_login_time'=>time(),
            'last_login_ip'=>request()->ip()], $user['id']);

        // 讲用户信息记录到session
        session('o2o_user', $user, 'o2o');
        return $this->success('登陆成功', url('index/index'));

    }

    public function logout() {
        session(null, 'o2o');
        $this->redirect(url('user/login'));
    }
}