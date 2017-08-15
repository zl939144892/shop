<?php
namespace app\Common\model;
use think\Model;

class Deal extends Common
{
	public function getNormalDeals($data = []) {
		$data = [
			'status' => ['neq',-1],
			'end_time' => ['gt', time()],
		];

		$result = $this->where($data)
					   ->paginate();
		return $result;
	}

	public function getNormalDealsByBisId($id = 0) {
		if($id == 0 || !is_numeric($id)){
			exception('非法访问！');
		}else {
			$data = [
				'status' => ['neq',-1],
				'end_time' => ['gt', time()],
			];

			$result = $this->where($data)
						   ->paginate();
			return $result;
		}
	}

	/**
	 * [根据分类和城市获取相关数据]
	 * @Author   A-Li
	 * @DateTime 2017-07-14T11:55:16+0800
	 * @param    [分类]                   $id     [description]
	 * @param    [城市]                   $cityId [description]
	 * @param    [条数]                   $limit  [description]
	 * @return   [type]                           [description]
	 */
	public function getNormalDealByCategoryCity($cityId, $limit=10)
	{
		$data = [
			'end_time' => ['gt', time()],
			'city_id' => $cityId,
			'status' => 1,
		];

		$order = [
			'listorder' => 'desc',
			'id' => 'desc',
		];

		$res = $this->where($data)
			 		->order($order);
		if($limit) {
			$res = $res->limit($limit);
		}

		return $res->select()->toArray();
	}

	public function getDealByConditions($data = [], $orders)
	{
		if($orders = 1) {
			$order['buy_count'] = 'desc';
		}else if($orders = 2) {
			$order['current_price'] = 'desc';
		}else if($orders = 3) {
			$order['create_time'] = 'desc';
		}

		#find_in_set();

		$order['id'] = 'desc';

		$datas[] = 'end_time > '.time();
		if(!empty($data['se_category_id'])) {
			$datas[] = "find_in_set(".$data["se_category_id"].",se_category_id)";
		}
		if(!empty($data['category_id'])) {
			$datas[] = 'category_id = '.$data['category_id'];
		}
		if(!empty($data['city_id'])) {
			$datas[] = 'city_id = '.$data['city_id'];
		}
		$datas[] = 'status = 1';
		
		
		return $this->where(implode(' AND ', $datas))
			->order($order)
			->paginate();
	}
}