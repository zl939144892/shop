<?php
namespace app\common\model;

use think\Model;

class BisAccount extends Common
{
	public function checkUserNameExist($username)
	{
		return $this->get(['username'=>$username]);
	}

	public function updateById($data, $id)
	{
		// allowField 过滤data数组中非数据表中的数据
		return $this->allowField(true)->save($data, ['id'=>$id]);
	}
}