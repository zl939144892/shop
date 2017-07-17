<?php
namespace app\admin\controller;

use think\Controller;

class Featured extends Common
{
    public function index(){
    	$type = input('get.type', 0, 'intval');
    	$featured = $this->obj->getFeaturedsByType($type);

        return $this->fetch('', [
        	'type' => $type,
	        'types' => config('featured.featured_type'),
        	'featured' => $featured,
        ]);
    }

    public function add(){
    	if(request()->isPost()) {
    		// 入库的逻辑
    		$data = input('post.');
            // 数据校验
            $this->check('add', $data);

    		$id = $this->obj->add($data);
    		if($id) {
    			$this->success('添加成功');
    		}else {
    			$this->error('添加失败');
    		}
    	}else {
	    	// 获取推荐位类别
	        return $this->fetch('', [
	        	'types' => config('featured.featured_type'),
	        ]);
	    }
    }

}