<?php
namespace app\admin\controller;

use think\Controller;

class Deal extends Common
{
    public function index(){
    	$datas = $categoryArrs = $cityArrs = [];// 初始化数据，要用到
        $data = input('get.');
        if($data) {
            // print_r($data);exit;
            // 查询
        	if(!empty($data)){
        		// 把时间这个条件放到最前面，有利于优化SQL的查询速度
    	    	if(!empty($data['start_time']) && !empty($data['end_time'])) {
    		    	$start_time = strtotime($data['start_time']);
    		    	$end_time = strtotime($data['end_time']);
                    // 判断时间段是否合理
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

    public function detail()
    {
        $id = input('get.id');
        if(empty($id)) {
            return $this->error('ID错误');
        }

        $dealData = $this->obj->get($id);

        return $this->fetch('', [
            'dealData' => $dealData,
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
        
        $deal = $this->obj->get(['id'=>$data['id']]);
        // 编辑邮件内容邮件
        $accName = Model('BisAccount')
                    ->get(['bis_id' => $deal['bis_id']])
                    ->username;
        if($data['status'] == 1) {
            $title = '团购申请通过！';
            $content = '尊敬的 '.$accName.' 您好，恭喜您的 '.$deal['name'].' 团购申请已经被审核通过！';
        }elseif($data['status'] == 2) {
            $title = '团购申请审核失败！';
            $content = '尊敬的 '.$accName.' 您好，非常抱歉，您的 '.$deal['name'].' 团购申请没能通过审核，原因如下：'.$data['reason'];
        }elseif($data['status'] == -1) {
            $title = '团购已被删除！';
            $content = '尊敬的 '.$accName.' 您好，非常抱歉，您的 '.$deal['name'].' 已经被本平台删除，原因如下：'.$data['reason'];
        }elseif($data['status'] == 0) {
            $title = '团购将被重新审核';
            $content = '尊敬的 '.$accName.' 您好，您的 '.$deal['name'].' 将被重新审核';
        }else {
            return $this->error('非法操作！');
        }


        // 修改状态
        $res = $this->obj->save(
            ['status' => $data['status']],
            ['id' => $data['id']]
        );

        if($res) {
            $bis = Model('Bis')
                 ->get(['id' => $deal['bis_id']]);
            // 发送邮件
            \phpmailer\Email::send($bis['email'], $title, $content);
            $this->success('状态更新成功');
        }else {
            $this->error('状态更新失败');
        }

    }
}