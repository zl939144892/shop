<?php
namespace app\common\model;

use think\Model;

class User extends Common
{
	public function add($data = [], $status = 1)
	{
		// 如果数据不是数组
		if(!is_array($data)) {
			exception('数据错误！');
		}
		$data['status'] = $status;//设置默认状态

		return $this->data($data)
					->allowField(true)
					->save();
		
	}

	/**
	 * 根据用户名获取用户信息
	 * @Author   A-Li
	 * @DateTime 2017-07-10T20:19:57+0800
	 * @param    [type]                   $username [description]
	 * @return   [type]                             [description]
	 */
	public function getUserByUsername($username)
	{
		if(!$username) {
			exception('用户名不合法');
		}

		$data = ['username' => $username];
		return $this->where($data)
					->find();
	}

	public function getUser($status)
	{
		$data = [
			'status'=> $status,
		];

		return $this->where($data)
					->paginate();
	}
}