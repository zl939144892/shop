<?php
namespace app\admin\controller;

use think\Controller;

class Category extends Controller
{
    private $obj, $val, $gnf;
    public function _initialize()//_initialize为构造函数
    {
        //以下语句被多次使用，所以进行一个优化
        $this->obj = model('Category');
        //调用TP5的validate方法（经过了自定义）
        $this->val = validate('Category');
        //获取正常状态的一级分类
        $this->gnf = $this->obj->getNormalFirstCategory();
    }

    public function index()
    {
        $parentId = input('get.parent_id', 0, 'intval');
        $parentName = '';
        if($parentId) {
            $parentName = $this->obj
                          ->get(['id'=>$parentId])
                          ->name;
        }
        $categorys = $this->obj->getFirstCategorys($parentId);
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
            'categorys' => $this->gnf,
            'parentId' => $parentId,
        ]);
    }

    public function save()
    {
        //判断是否为POST请求
        if(!request()->isPost()) {
            $this->error('请求失败');
        }

    	$data = input('post.');//从前端获取数据
        

        //进行validate校验$data，设置场景为add
        if(!$this->val->scene('add')->check($data)) {
    		$this->error($this->val->getError());//返回错误信息
    	}

        //如果POST数据存在id，则直接更新到数据库
        //因为添加的操作不会传id值，这是编辑操作
        if(!empty($data['id'])) {
            return $this->update($data);
        }

        //把$data提交到Model层
        $res = $this->obj->add($data);

        //对返回的数据进行判断
        if($res) {
            $this->success('新增成功');
        }else {
            $this->error('新增失败');
        }
    }

    //编辑页面
    public function edit($id = 0)
    {
        if(intval($id) < 1) {
            $this->error('参数不合法');
        }

        $category = $this->obj->get($id);//此处get($id)中的$id必须为主键

        return $this->fetch('', [//注意前面要有 '', 是默认方法
            'categorys' => $this->gnf,
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

    //排序逻辑
    public function listorder($id, $listorder) {
        $data = array('listorder' => $listorder, 'id' => $id);

        //进行validate校验$data，设置场景为listorder
        if(!$this->val->scene('listorder')->check($data)) {
            $this->error($this->val->getError());//返回错误信息
        }

        $res = $this->obj->save(
            ['listorder' => $listorder], 
            ['id' => $id]
        );

        if($res) {
            $this->result($_SERVER['HTTP_REFERER'], 1, '排序成功');
        }else {
            $this->error($_SERVER['HTTP_REFERER'], 0, '排序失败');
        }//$_SERVER['HTTP_REFERER']作用为显示当前连接的上一个连接的地址
    }

    //修改状态
    public function status() {
        $data = input('get.');

        //进行validate校验$data，设置场景为status
        if(!$this->val->scene('status')->check($data)) {
            $this->error($this->val->getError());//返回错误信息
        }

        $res = $this->obj->save(
            ['status' => $data['status']],
            ['id' => $data['id']]
        );

        if($res) {
            $this->success('状态更新成功');
        }else {
            $this->error('状态更新失败');
        }
    }
}