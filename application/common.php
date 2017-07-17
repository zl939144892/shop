<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

// 根据状态返回前端样式
function status($status)
{
	if($status == 1) {
		$str = '<span class="label label-success radius">正常</span>';
	}elseif($status == 0) {
		$str = '<span class="label label-danger radius">待审</span>';
	}elseif($status == 2) {
		$str = '<span class="label label-danger radius">已驳回</span>';
	}elseif($status == 3) {
		$str = '<span class="label label-danger radius">已过期</span>';
	}else {
		$str = '<span class="label label-danger radius">已删除</span>';
	}

	return $str;
}

function isMain($isMain)
{
	if($isMain == 1) {
		$str = '<i class="Hui-iconfont">&#xe6a8;</i>';
	}elseif($isMain == 0) {
		$str = '<i class="Hui-iconfont">&#xe608;</i>';
	}else {
		$str = '<i class="Hui-iconfont">&#xe60b;</i>';
	}

	return $str;
}

/**
 * 访问远程URL（用于API）
 * @param $type 0 get  1 post
 */
function doCurl($url, $type = 0, $data = []) {
	$ch = curl_init();// 初始化
	// 设置选项
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	if($type == 1) {
		// post
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}

	//执行并获取内容
	$output = curl_exec($ch);
	//释放curl句柄
	curl_close($ch);
	return $output;
}

// 商户入驻申请状态查询界面的文案
function bisRegister($status) {
	if($status == 1) {
		$str = '恭喜，商户入驻申请成功通过';
	}elseif($status == 0) {
		$str = '入驻申请审核中，审核完成后系统将会发送邮件进行通知，请留意';
	}elseif($status == 2) {
		$str = '非常抱歉，您提交的材料有误，详情请参见邮件内容，修改完成后重新提交';
	}else {
		$str = '该申请不存在';
	}
	return $str;
}

// 通用分页样式
function pagination($obj) {
	if(!$obj) {
		return '';
	}

	$params = request()->param();// 获取当前页面链接中的参数

	return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->appends($params)->render().'</div>';
}

// 审核页面获取城市
function getSeCityName($path) {
	if(empty($path)) {
		return '';
	}

	$cityId = $path;

	$city = Model('City')->get($cityId);
	return $city->name;
}

// 审核页面获取分类
function getCategoryName($path) {
	if(empty($path)) {
		return '';
	}
	if(preg_match('/|/', $path)) {
		$categoryPath = explode('|', $path);
		$categoryId = $categoryPath;
		for($i = 0; $i < count($categoryPath); $i++) {
			$category[$i] = Model('Category')->get($categoryId[$i]);
			$name[$i] = $category[$i]->name;
		}

		$categoryname = implode(' ', $name);
	}else {
		$categoryId = $path;
		$category = Model('Category')->get($categoryId);
		$categoryname = $category->name;
	}
	
	return $categoryname;
}

// 审核页面获取门店名
function getLocationName($path) {
	if(empty($path)) {
		return '';
	}
	if(preg_match('/,/', $path)) {
		$locationPath = explode(',', $path);
		$locationId = $locationPath;
		for($i = 0; $i < count($locationPath); $i++) {
			$location[$i] = Model('BisLocation')->get($locationId[$i]);
			$name[$i] = $location[$i]->name;
		}

		$locationname = implode(' ', $name);
	}else {
		$locationId = $path;
		$location = Model('BisLocation')->get($locationId);
		$locationname = $location->name;
	}
	
	return $locationname;
}

/**
 * AJAX返回信息
 * @Author   A-Li
 * @DateTime 2017-07-08T15:06:55+0800
 * @param    [type]                   $status  [description]
 * @param    string                   $message [description]
 * @param    array                    $data    [description]
 * @return   [type]                            [description]
 */
function show($status, $message='', $data=[]) {
	return [
		'status' => intval($status),
		'message' => $message,
		'data' => $data,
	];
}

function countLocation($ids) {
	if(preg_match('/,/', $ids)) {
		$arr = explode(',', $ids);
		return count($arr);
	}else if($ids) {
		return 1;
	}
}

// 设置订单号
function setOrderSn() {
	list($t1, $t2) = explode(' ', microtime());
	$t3 = explode('.', $t1*10000);
	return $t2.$t3[0].(rand(10000,99999));
}