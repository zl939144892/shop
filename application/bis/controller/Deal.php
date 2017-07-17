<?php
namespace app\bis\controller;
use think\Controller;

class Deal extends Common
{
	public function index()
	{
		// 
		return $this->fetch();
	}

	public function add()
	{
		$bisId = $this->getLoginUser()->bis_id;
		if(request()->isPost()) {
			// 插入逻辑
			$data = input('post.');
			// 校验数据
			
			$location = model('BisLocation')->get($data['location_ids'][0]);
			$deals = [
				'bis_id' => $bisId,
				'name' => $data['name'],
				'image' => $data['image'],
				'category_id' => $data['category_id'],
    			'se_category_id' => empty($data['se_category_id']) ? '' : implode(',', $data['se_category_id']),
				'city_id' => $data['city_id'],
				'se_city_id' => $data['se_city_id'],
				'location_ids' => empty($data['location_ids']) ? '' : implode(',', $data['location_ids']),
				'start_time' => strtotime($data['start_time']),
				'end_time' => strtotime($data['end_time']),
				'total_count' => $data['total_count'],
				'origin_price' => $data['origin_price'],
				'current_price' => $data['current_price'],
				'coupons_begin_time' => strtotime($data['coupons_begin_time']),
				'coupons_end_time' => strtotime($data['coupons_end_time']),
				'notes' => $data['notes'],
				'description' => $data['description'],
				'bis_account_id' => $this->getLoginUser()->id,
				'xpoint' => $location->xpoint,
				'ypoint' => $location->ypoint,
			];

			$id = Model('Deal')->add($deals);
			if($id) {
				$this->success('添加成功', url('deal/index'));
			}else {
				$this->error('添加失败');
			}
		}else {
			// 获取一级城市的数据
			$citys = model('City')->getNormalByParentId();
	        // 获取一级分类的数据
			$categorys = model('Category')->getNormalByParentId();
			return $this->fetch('',[
				'citys' => $citys,
				'categorys' => $categorys,
				'bislocation' => model('BisLocation')->getNormalLocationByBisId($bisId),
			]);
		}
	}
}