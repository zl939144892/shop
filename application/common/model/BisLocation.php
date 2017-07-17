<?php
namespace app\common\model;

use think\Model;

class BisLocation extends Common
{
	public function getBisLocationByStatus($status = 0)
	{
		$data = [
			'status' => $status,
		];

		return $this->where($data)
			 		->paginate();
	}

	public function getBisLocationByBisId($bisId)
	{
		$data = [
			'bis_id' => $bisId,
		];

		return $this->where($data)
			 		->paginate();
	}

	public function getNormalLocationByBisId($bisId)
	{
		$data = [
			'bis_id' => $bisId,
			'status' =>1,
		];

		return $this->where($data)
					->select();
	}

	public function getNormalLocationInId($ids)
	{
		$data = [
			'id' => ['in', $ids],
			'status' =>1,
		];

		return $this->where($data)
					->select();
	}
}