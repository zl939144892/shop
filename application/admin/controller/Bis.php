<?php
namespace app\admin\controller;

use think\Controller;

class Bis extends Common
{
    /**
     * 状态为正常的商户列表
     * @Author   A-Li
     * @DateTime 2017-07-06T12:38:33+0800
     * @return   mixed
     */
    public function index()
    {
        $bis = $this->obj->getBisByStatus(1);

        return $this->fetch('', [
            'bis' => $bis,
        ]);
    }

    public function location()
    {
        $bisId = input('get.bis_id', 0, 'intval');
        if($bisId) {
            $bisLocation = Model('BisLocation')->getBisLocationByBisId($bisId);
            $status = $this->obj->get($bisId)->status;
            if($status == 1) {
                $url = url('index');
            }elseif($status == 0) {
                $url = url('apply');
            }
        }else {
            return $this->error('非法访问');
        }

        return $this->fetch('', [
            'bisLocation' => $bisLocation,
            'url' => $url,
        ]);
    }

    /**
     * 状态为删除的商户列表
     * @Author   A-Li
     * @DateTime 2017-07-06T12:38:33+0800
     * @return   mixed
     */
    public function dellist()
    {
        $bis = $this->obj->getBisByStatus(-1);
        return $this->fetch('', [
            'bis' => $bis,
        ]);
    }

	/**
	 * 入驻申请列表
	 * @Author   A-Li
	 * @DateTime 2017-07-06T12:38:33+0800
	 * @return   mixed
	 */
	public function apply()
	{
		$bis = $this->obj->getBisByStatus();
		return $this->fetch('', [
			'bis' => $bis,
		]);
	}

	public function detail() {
		$id = input('get.id');
		if(empty($id)) {
			return $this->error('ID错误');
		}

		// 获取商户数据
		$bisData = $this->obj->get($id);
		$locationData = Model('BisLocation')->get(['bis_id'=>$id, 'is_main'=>1]);
		$accountData = Model('BisAccount')->get(['bis_id'=>$id, 'is_main'=>1]);

		return $this->fetch('',[
			'bisData' => $bisData,
			'locationData' => $locationData,
			'accountData' => $accountData,
		]);
	}

    public function lodetail() {
        $id = input('get.id');
        if(empty($id)) {
            return $this->error('ID错误');
        }

        // 获取商户数据
        $locationData = Model('BisLocation')->get(['id'=>$id]);

        return $this->fetch('',[
            'locationData' => $locationData,
        ]);
    }
	
	//修改状态
    public function status() {
    	if(request()->isPost()) {
            $data = input('post.');
            if(!$data['reason']) {
	        	$this->error('需输入说明！！');
	        }
        }else {
            $data = input('get.');
        }
        
        if(empty($data) && !$data['id'] && !$data['status']) {
        	$this->error('非法访问！');
        }

        // 数据校验
        $this->check('status', $data);
        
        // ↓标记：如果不是门店则为空，修改状态时作为判断依据
        $locationBisId = ''; 
        if($data['location'] == 1){
            $locationBisId = $data['id'];// 店面ID
            $data['id'] = Model('BisLocation')// 总店ID
                    ->get(['id' => $data['id']])
                    ->bis_id;
        }

        $bis = $this->obj
                    ->get(['id' => $data['id']]);
        // 编辑邮件内容邮件
        $bisName = $bis['name'];
        $accName = Model('BisAccount')
                    ->get(['bis_id' => $data['id']])
                    ->username;
        if($data['status'] == 1) {
            $title = '入驻申请通过！';
            $content = '尊敬的 '.$accName.' 您好，恭喜您的 '.$bisName.' 入驻申请已经被审核通过！';
        }elseif($data['status'] == 2) {
            $title = '入驻申请审核失败！';
            $content = '尊敬的 '.$accName.' 您好，非常抱歉，您的 '.$bisName.' 入驻申请没能通过审核，原因如下：'.$data['reason'];
        }elseif($data['status'] == -1) {
            $title = '商户已被删除！';
            $content = '尊敬的 '.$accName.' 您好，非常抱歉，您的 '.$bisName.' 已经被本平台删除，原因如下：'.$data['reason'];
        }elseif($data['status'] == 0) {
            $title = '商户将被重新审核';
            $content = '尊敬的 '.$accName.' 您好，您的 '.$bisName.' 将被重新审核';
        }else {
            return $this->error('非法操作！');
        }

        if($locationBisId) {
            // 修改状态
            $location = Model('BisLocation')->save(
                ['status' => $data['status']],
                ['id' => $locationBisId]
            );
            $res = 1;// 因为门店没有这两种信息，手动赋值以通过校验
            $account = 1;
        }else {
            // 修改状态
            $res = $this->obj->save(
                ['status' => $data['status']],
                ['id' => $data['id']]
            );
            $location = Model('BisLocation')->save(
            	['status' => $data['status']],
            	['is_main' => 1, 'bis_id' => $data['id']]
            );
            $account = Model('BisAccount')->save(
            	['status' => $data['status']],
            	['is_main' => 1, 'bis_id' => $data['id']]
            );
        }

        if($res && $location && $account) {
	        // 发送邮件
	        \phpmailer\Email::send($bis['email'], $title, $content);
            $this->success('状态更新成功');
        }else {
            $this->error('状态更新失败');
        }

    }
}