<?php
namespace app\Common\validate;
use think\Validate;
class User extends Common
{
	protected $rule = [
		['username', 'require|max:20', '用户名不能为空|用户名不能超过二十个字符'],
		['email', 'require|email', '邮箱不能为空|邮箱格式不正确'],
		['password', 'require', '密码不能为空'],
	];

	protected $scene = [
		'login' => ['username', 'password'],
	];
}