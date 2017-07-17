<?php
namespace app\index\controller;

use think\Controller;

class Detail extends Common
{
	public function index($id)
	{
		if(!intval($id)){
			$this->error('非法访问！');
		}

		// 查询商品数据
		$deal = Model('Deal')->get($id);
		if(!$deal || $deal->status != 1) {
			$this->error('该商品不存在');
		}

		// 获取分类信息
		$category = Model('Category')->get($deal->category_id);
		// 获取店铺描述
		$bisDescription = Model('Bis')->get($deal->bis_id)->description;
		// 获取门店信息
		$location = Model('BisLocation')->getNormalLocationInId($deal->location_ids);

		$timeFlag = 0;
		if($deal->start_time > time()) {
			$timedata = '';
			$timeFlag = 1;
			$dtime = $deal->start_time - time();
			// floor() 把小数部分向下舍去 0.6->0 1.2->1
			$d = floor($dtime/(3600*24));
			if($d) {
				$timedata .= $d.'天 ';
			}
			$h = floor($dtime%(3600*24)/3600);
			if($h) {
				$timedata .= $h.'小时 ';
			}
			$m = floor($dtime%(3600*24)%3600/60);
			if($m) {
				$timedata .= $m.'分钟 ';
			}
			$s = floor($dtime%(3600*24)%3600%60);
			if($s) {
				$timedata .= $s.'秒 ';
			}
			$this->assign('timedata', $timedata);
		}elseif($deal->end_time > time() || $deal->start_time > time()) {
			$timeFlag = 2;
			$this->assign('timedata', '00天 00小时 00分钟 00秒');
		}if($deal->end_time < time()) {
			$timeFlag = 0;
		}
		// print_r($location);exit;

		return $this->fetch('', [
			'deal' => $deal,
			'title' => $deal->name,
			'category' => $category,
			'location' => $location,
			'timeFlag' => $timeFlag,
			'bisDescription' => $bisDescription,
			'overplus' => $deal->total_count - $deal->buy_count,
		]);
	}
}