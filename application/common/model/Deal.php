<?php
namespace app\Common\model;
use think\Model;

class Deal extends Common
{
	public function getNormalDeals($data = []) {
		$data['status'] = 1;

		$result = $this->where($data)
					   ->paginate();
		return $result;
	}
}