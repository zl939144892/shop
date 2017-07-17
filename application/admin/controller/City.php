<?php
namespace app\admin\controller;

use think\Controller;

class City extends Common
{
    public function index()
    {
    	$parentId = input('get.parent_id', 0, 'intval');
        $parentName = '';
        if($parentId) {
            $parentName = $this->obj
                          ->get(['id'=>$parentId])
                          ->name;
        }
    	$citys = $this->obj->getNormalFirstData($parentId, 1);
        return $this->fetch('', [
        	'citys' => $citys,
        	'parentId' => $parentId,
            'parentName'=> $parentName,
        ]);
    }

    public function add()
    {
        $parentId = input('get.parent_id', 0, 'intval');
        return $this->fetch('', [//注意前面要有 '', 是默认方法
            'citys' => $this->obj->getNormalFirstData(),
            'parentId' => $parentId,
        ]);
    }
}