<?php
namespace app\common\model;

use think\Model;

class Order extends Common
{
	public function getOrder($status)
	{
		$data = [
			'status'=> $status,
		];

		return $this->where($data)
					->paginate();
	}
}