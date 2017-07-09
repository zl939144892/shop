<?php
namespace app\Common\model;
use think\Model;

class City extends Common
{
	public function getNormalCitys()
	{
		$data = [
			'status' => 1,
		];

		return $this->where($data)
					->select();
	}
}