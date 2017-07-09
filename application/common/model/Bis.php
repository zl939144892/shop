<?php
namespace app\common\model;

use think\Model;

class Bis extends Common
{
	public function checkBisNameExist($name)
	{
		return $this->get(['name'=>$name]);
	}

	public function getBisByStatus($status = 0)
	{
		$order = [
			'id' => 'desc',
		];

		$data = [
			'status' => $status,
		];
		return $this->where($data)
			 		->order($order)
			 		->paginate();
	}
}