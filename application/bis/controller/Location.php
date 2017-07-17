<?php
namespace app\bis\controller;
use think\Controller;

class Location extends Common
{
	private $obj, $citys, $categorys, $val;

	public function _initialize()
	{
		$this->obj = Model('BisLocation');

		$this->val = validate('Bis');
		// 获取一级城市的数据
		$this->citys = model('City')->getNormalByParentId();
	    // 获取一级分类的数据
		$this->categorys = model('Category')->getNormalByParentId();
	}

	public function index()
	{
		$bisId = $this->getLoginUser()->bis_id;

		$bisL = $this->obj->getBisLocationByBisId($bisId);

		return $this->fetch('', [
			'bisL' => $bisL,
		]);
	}

	public function detail() {
		$id = input('get.id');
		if(empty($id)) {
			return $this->error('ID错误');
		}

		$bisData = $this->obj->get($id);

		$bisId = $this->getLoginUser()->bis_id;
		if($bisId != $bisData->bis_id) {
			return $this->error('非法访问！');
		}

		// 获取商户数据
		if(!$bisData) {
			return $this->error('店铺不存在！');
		}

		$seCategoryId = $bisData->se_category_id;
		if(preg_match('/|/', $seCategoryId)) {
			$seCategoryId = explode('|', $seCategoryId);
		}

		return $this->fetch('',[
			'bisData' => $bisData,
			'citys' => $this->citys,
			'categorys' => $this->categorys,
			'seCategoryId' => $seCategoryId,
			'id' => $id,
		]);
	}

	public function update()
	{
		$id = input('get.id');

		if(request()->isPost()) {
			$locationData = $this->data();
			$locationId = $this->obj->update($locationData, ['id'=>$id]);
	    	if($locationId) {
	    		return $this->success('修改门店信息申请提交成功，请等待平台审核');
	    	}else {
	    		return $this->error('修改门店信息申请提交失败，请重新确认信息填写的正确性');
	    	}
		}else {
			return $this->fetch('',[
				'citys' => $this->citys,
				'categorys' => $this->categorys,
			]);
		}
	}

	public function add()
	{
		if(request()->isPost()) {
			$locationData = $this->data();

	    	$locationId = $this->obj->add($locationData);
	    	if($locationId) {
	    		return $this->success('新增门店申请提交成功，请等待平台审核');
	    	}else {
	    		return $this->error('新增门店申请提交失败，请重新确认信息填写的正确性');
	    	}
		}else {
			return $this->fetch('',[
				'citys' => $this->citys,
				'categorys' => $this->categorys,
			]);
		}
	}

	public function data()
	{
		$bisId = $this->getLoginUser()->bis_id;
		$data = input('post.');

		// 数据校验
		if(!$this->val->scene('bisLocation')->check($data)) {
    		$this->error($this->val->getError());//返回错误信息
    	}
			
		// 把多个二级分类使用 | 进行连接
		$data['cate_id'] = '';
	    if(!empty($data['se_category_id'])) {
	    	$data['cate_id'] = implode('|', $data['se_category_id']);
	    }

	    // 把选择的城市和输入的地址连接
        $address1 = getSeCityName($data['city_id']);
        $address2 = getSeCityName($data['se_city_id']);
        $data['address'] = $address1.$address2.$data['address'];

	    // 获取经纬度
	    $lnglat = \Map::getLngLat($data['address']);
	    if(empty($lnglat) || $lnglat['status'] != 0) {
	    	$this->error('无法获取地图数据，请检查地址输入内容');
	   	}elseif ($lnglat['result']['precise'] != 1) {
	   		$this->error('输入的地址信息过于模糊，请重新输入');
	   	}
		// 门店数据封装成数组
	    $locationData = [
	    	'status' => 0,
	    	'bis_id' => $bisId,
	    	'name' => $data['name'],
	    	'logo' => $data['logo'],
	    	'tel' => $data['tel'],
	    	'contact' => $data['contact'],
	    	'category_id' => $data['category_id'],
    		'se_category_id' => empty($data['se_category_id']) ? '' : implode(',', $data['se_category_id']),
	    	'category_path' => $data['category_id'].','.$data['cate_id'],
	    	'city_id' => $data['city_id'],
	    	'se_city_id' => $data['se_city_id'],
	    	'city_path' => $data['city_id'].','.$data['se_city_id'],
	    	'api_address' => $data['address'],
	    	'open_time' => $data['open_time'],
	    	'content' => empty($data['content']) ? '' : $data['content'],
	    	'is_main' => 0, // 代表的是该条信息为分店信息
	    	'xpoint' => empty($lnglat['result']['location']['lng']) ? '' : $lnglat['result']['location']['lng'],
	    	'ypoint' => empty($lnglat['result']['location']['lat']) ? '' : $lnglat['result']['location']['lat'],
	    ];

	    return $locationData;
	}
}