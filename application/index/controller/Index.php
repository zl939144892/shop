<?php
namespace app\index\controller;

use think\Controller;

class Index extends Common
{
    public function index()
    {
        $parentIds = $dealArr = $catId = [];
    	// 首页大图推荐位数据
    	$bFeatured =  Model('Featured')->getFeaturedsByType(0,5);
    	$rFeatured = Model('Featured')->get(['type' => 1]);

    	// 商品数据
        $deals = Model('Deal') ->getNormalDealByCategoryCity($this->city->id);

        $cats = Model('Category')->getNormalRecommendCategoryByParentId();
        foreach($cats as $cat) {
            $parentIds[] = $cat->id;
        }
        foreach ($deals as $deal) {
            $dealArr[$deal['category_id']][] = [
                'id' => $deal['id'],
                'name' => $deal['name'],
                'image' => $deal['image'],
                'buy_count' => $deal['buy_count'],
                'location_ids' => $deal['location_ids'],
                'origin_price' => $deal['origin_price'],
                'current_price' => $deal['current_price'],
            ];
            $catId[] = $deal['category_id'];
        }

        return $this->fetch('', [
        	'bFeatured' => $bFeatured,
        	'rFeatured' => $rFeatured,
            'deals' => $dealArr,
            'catId' => $catId,
        ]);
    }
}