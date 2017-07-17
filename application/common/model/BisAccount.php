<?php
namespace app\common\model;

use think\Model;

class BisAccount extends Common
{
	public function checkUserNameExist($username)
	{
		return $this->get(['username'=>$username]);
	}
}