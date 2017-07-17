<?php
namespace app\admin\controller;

use think\Controller;

class Category extends Common
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
        $categorys = $this->obj->getFirstDatas($parentId);
        return $this->fetch('',[
                'categorys' => $categorys,
                'parentId' => $parentId,
                'parentName'=> $parentName,
            ]);
    }

    public function add()
    {
        $parentId = input('get.parent_id', 0, 'intval');
    	return $this->fetch('', [//注意前面要有 '', 是默认方法
            'categorys' => $this->obj->getNormalFirstData(),
            'parentId' => $parentId,
        ]);
    }
    
    //编辑页面
    public function edit($id = 0)
    {
        if(intval($id) < 1) {
            $this->error('参数不合法');
        }

        $category = $this->obj->get($id);//此处get($id)中的$id必须为主键

        return $this->fetch('', [//注意前面要有 '', 是默认方法
            'categorys' => $this->obj->getNormalFirstData(),
            'category' => $category,
        ]);
    }

    //更新数据
    public function update($data) {
        $res =  $this->obj->save(
            $data, 
            ['id' => intval($data['id'])] //save第二个参数要传更新的条件
        );
        if($res) {
            $this->success('更新成功');
        }else {
            $this->error('更新失败');
        }
    }

    
}