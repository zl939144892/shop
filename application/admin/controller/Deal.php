<?php
namespace app\admin\controller;

use think\Controller;

class Deal extends Controller
{
	private $obj;

    public function _initialize()//_initialize为构造函数
    {
        $this->obj = Model('Deal');
    }

    public function index(){
    	$data = input('get.');
    	// 查询
    	$datas = $categoryArrs = $cityArrs = [];// 初始化数据，要用到
    	if(!empty($data)){
    		// 把时间这个条件放到最前面，有利于优化SQL的查询速度
	    	if(!empty($data['start_time']) && !empty($data['end_time'])) {
		    	$start_time = strtotime($data['start_time']);
		    	$end_time = strtotime($data['end_time']);

	    		if($start_time < $end_time) {
		    		$datas['create_time'] = [
		    			['gt', $start_time],
		    			['lt', $end_time],
		    		];
	    		}
	    	}
	    	if(!empty($data['category_id'])) {
	    		$datas['category_id'] = $data['category_id'];
	    	}
	    	if(!empty($data['city_id'])) {
	    		$datas['city_id'] = $data['city_id'];
	    	}
	    	if(!empty($data['name'])) {
	    		$datas['name'] = ['like', '%'.$data['name'].'%'];

	    	}
	    }

    	$deals = $this->obj->getNormalDeals($datas);

    	$categorys = Model('Category')->getNormalByParentId();
    	// 把分类的数据遍历出来
    	foreach ($categorys as $category) {
    		$categoryArrs[$category->id] = $category->name;
    	}

    	$citys = Model('City')->getNormalByParentId();

    	$cityss = Model('City')->getNormalCitys();
    	// 把城市的数据遍历出来
    	foreach ($cityss as $city) {
    		$cityArrs[$city->id] = $city->name;
    	}

        return $this->fetch('', [
        	'categorys' => $categorys,
        	'citys' => $citys,
        	'deals' => $deals,
        	'category_id' => empty($data['category_id']) ? '' : $data['category_id'],
        	'city_id' => empty($data['city_id']) ? '' : $data['city_id'],
        	'se_city_id' => empty($data['se_city_id']) ? '' : $data['se_city_id'],
        	'name' => empty($data['name']) ? '' : $data['name'],
        	'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
        	'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
        	'cityArrs' => $cityArrs,
        	'categoryArrs' => $categoryArrs,
        ]);
    }
}