<?php
namespace app\common\model;

use think\Model;

class Common extends Model
{
	// protected $autoWriteTimestamp = true;//自动添加时间戳
	// 数据库配置里'auto_timestamp' => true, 也能实现
	public function add($data, $status = 0)
	{
		$data['status'] = $status;//设置默认状态
		// $data['create_time'] = time();//添加时间戳
		$this->save($data);//写入数据库，并返回布尔值
		return $this->id;
	}

	// 获取正常状态的一级数据
	public function getNormalFirstData($parentId = 0, $ord = 0)
	{
		if($ord == 0) { // ord 0 则倒序排序
			$data = [
				'status' => 1,
				'parent_id' => $parentId,
			];
			$order = [
				'id' => 'desc',
			];

			return $this->where($data)
				->order($order)
				->select();
		}elseif ($ord == 1) {// ord 1 则条件排序 任何状态
			$data = [
				'parent_id' => $parentId,
			];
			$order = [
				'listorder' => 'desc',
			];

			return $this->where($data)
				->order($order)
				->select();
		}
		return $this->where($data)
				->select();
	}

	// 获取一级分类（不包括被删除的）
	public function getFirstDatas($parentId = 0)
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

	public function updateById($data, $id)
	{
		// allowField 过滤data数组中非数据表中的数据
		return $this->allowField(true)->save($data, ['id'=>$id]);
	}
}