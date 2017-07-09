<?php
namespace app\admin\controller;

use think\Controller;

class Area extends Controller
{
	//详细注释查看category.php
	private $obj, $val, $gnf;

	public function _initialize() {
		$this->obj = model('Area');
		$this->val = validate('Area');
		$this->gnf = $this->obj->getNormalFirstCategory();
	}

	public function index() {
		print_r(\Map::getLngLat('北京天安门广场'));
		$parentId = input('get.parent_id', 0, 'intval');
		$areas = $this->obj->getFirstCategorys($parentId);
		return $this->fetch('', [
			'areas' => $areas,
		]);
	}

	public function add() {
		$areas = $this->obj->getNormalFirstCategory();
		return $this->fetch('',[
			'areas' => $areas,
		]);
	}

	public function save() {
		if(!request()->isPost()) {
			$this->error('请求失败');
		}

		$data = input('post.');

		if(!$this->val->scene('add')->check($data)){
			$this->error($this->val->getError());
		}

		if(!empty($data['id'])) {
			return $this->update($data);
		}

		$res = $this->obj->add($data);

		if($res) {
			return $this->success('新增成功');
		}else {
			return $this->error('新增失败');
		}
	}

	public function edit($id = 0) {
		if(intval($id) < 1) {
			$this->error('参数不合法');
		}

		$area = $this->obj->get($id);

		return $this->fetch('', [
			'area' => $area,
			'areas' => $this->gnf,
		]);
	}
}