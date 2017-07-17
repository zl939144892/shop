<?php
namespace app\admin\controller;

use think\Controller;

class Common extends Controller
{
	protected $obj, $val, $model;// 注意为protected

	public function _initialize()// _initialize为构造函数
    {
    	// 获取控制器
    	$this->model = request()->controller();

        $this->obj = Model($this->model);
        // 调用TP5的validate方法（经过了自定义）
        $this->val = validate($this->model);
    }

	public function status()
	{
		// 获取内容
    	$data = input('get.');
    	// 数据校验
    	$this->check('status', $data);

    	$res = $this->obj->save(['status'=>$data['status']], ['id'=>$data['id']]);
    	if($res) {
    		$this->success('修改成功');
    	}else {
    		$this->error('修改失败');
    	}
	}

	public function save()
    {
        //判断是否为POST请求
        if(!request()->isPost()) {
            $this->error('请求失败');
        }

    	$data = input('post.');//从前端获取数据
        
        //进行validate校验$data，设置场景为add
    	$this->check('add', $data);

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

    //排序逻辑
    public function listorder($id, $listorder) {
        $data = array('listorder' => $listorder, 'id' => $id);

        //进行validate校验$data，设置场景为listorder
        $this->check('listorder', $data);

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

    public function reason($id = 0) {
        $id = input('get.');
        if(intval($id) < 1 && empty($id)) {
            $this->error('参数不合法');
        }

        return $this->fetch('', [
            'id' => $id,
        ]);
    }

    protected function check($scene = '', $data)
    {
        // 数据校验
        if(!$this->val->scene($scene)->check($data)) {
            $this->error($this->val->getError());// 返回错误信息
        }
    }
}