<?php
namespace app\admin\controller;

use think\Controller;

class Area extends Common
{
	public function index()
	{
		$parentId = input('get.parent_id', 0, 'intval');
		$areas = $this->obj->getFirstDatas($parentId);
		return $this->fetch('', [
			'areas' => $areas,
		]);
	}

	public function add()
	{
		$areas = $this->obj->getNormalFirstData();
		return $this->fetch('',[
			'areas' => $areas,
		]);
	}

	public function edit($id = 0)
	{
		if(intval($id) < 1) {
			$this->error('参数不合法');
		}

		$area = $this->obj->get($id);

		return $this->fetch('', [
			'area' => $area,
			'areas' => $this->obj->getNormalFirstData(),
		]);
	}
}