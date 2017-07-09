<?php
namespace app\common\model;

use think\Model;

class Common extends Model
{
	// protected $autoWriteTimestamp = true;//自动添加时间戳
	// 数据库配置里'auto_timestamp' => true, 也能实现
	public function add($data)
	{
		$data['status'] = 0;//设置默认状态
		// $data['create_time'] = time();//添加时间戳
		$this->save($data);//写入数据库，并返回布尔值
		return $this->id;
	}

	// 获取正常状态的一级分类
	public function getNormalFirstCategory()
	{
		$data = [
			'status' => 1,
			'parent_id' => 0,
		];

		$order = [
			'id' => 'desc',
		];

		return $this->where($data)
			->order($order)
			->select();
	}

	// 获取一级分类（不包括被删除的）
	public function getFirstCategorys($parentId = 0)
	{
		$data = [
			'parent_id' => $parentId,
			'status' => ['neq',-1],
		];

		$order = [
			'listorder' => 'desc',
			'id' => 'desc',
		];

		return $this->where($data)
			->order($order)
			->paginate();//分页
	}

	// 根据parentId来获取信息
	public function getNormalByParentId($parent_id = 0) {
		$data = [
			'status' => 1,
			'parent_id' => $parent_id,
		];

		return $this->where($data)
			->select();
	}
}