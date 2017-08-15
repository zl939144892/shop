<?php
namespace app\bis\controller;
use think\Controller;

class Index extends Common
{
	public function index()
	{
        $users = $this->getLoginUser();
        $user = $users['username'];

		return $this->fetch('', [
            'user' => $user,
        ]);
	}
}