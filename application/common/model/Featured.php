<?php
namespace app\common\model;

use think\Model;

class Featured extends Common
{
	public function getFeaturedsByType($type = 0, $limit = 0)
	{
		$data = [
			'type' => $type,
			'status'=> ['neq',-1],
		];

		$order = [
			'id' => 'desc,'
		];

		$res = $this->where($data)
					 ->order($order);
		if($limit) {
			$res = $res->limit($limit);
		}
		return $res->paginate();
	}
}