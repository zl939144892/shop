<?php
namespace app\common\model;

use think\Model;

class Category extends Common
{
	public function getNormalRecommendCategoryByParentId($parentId = 0)
	{
		if(is_array($parentId)) {
			$parentId = ['in', implode(',', $parentId)];
		}
		$data = [
			'parent_id' => $parentId,
			'status' => 1,
		];

		$order = [
			'listorder' => 'desc',
			'id' => 'desc',
		];


		$res =  $this->where($data)
			->order($order);
			
		return $res->select();
		
	}
}