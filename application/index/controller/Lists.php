<?php
namespace app\index\controller;

use think\Controller;

class Lists extends Common
{
    public function index() {
    	$firstCatIds = $sedcategorys = $data = [];
    	// 获取一级栏目
    	$categorys = model('Category')->getNormalByParentId();
    	foreach($categorys as $category) {
    		$firstCatIds[] = $category->id;
    	}

    	// 根据来源页选择的分类对相应分类进行选中
    	$id = input('id', 0, 'intval');
    	if(in_array($id, $firstCatIds)) {// 一级分类
    		$categoryParentId = $id;
    		$data['category_id'] = $id;
    	}elseif ($id) {// 二级分类
    		$category = Model('Category')->get($id);
    		if(!$category || $category->status != 1) {
    			$this->error('数据不合法！');
    		}
    		$categoryParentId = $category->parent_id;
    		$data['se_category_id'] = $id;
    	}else {
    		$categoryParentId = 0;
    	}

    	$data['city_id'] = $this->city->id;
    	// 获取父类下所有子类
    	if($categoryParentId) {
    		$sedcategorys = Model('Category')->getNormalByParentId($categoryParentId);
    	}

    	// 获取数据排序逻辑
    	$order = input('order','');
    	$orderFlag = $order;
    	$deals = Model('Deal')->getDealByConditions($data, $order);

    	return $this->fetch('', [
    		'id' => $id,
    		'deals' => $deals,
    		'categorys' => $categorys,
    		'orderFlag' => $orderFlag,
    		'dealscount' => count($deals),
    		'sedcategorys' => $sedcategorys,
    		'categoryParentId' => $categoryParentId,
    	]);
    }
}