<?php
namespace app\admin\validate;
use think\Validate;
class Common extends Validate
{
	protected $rule = [
		['name', 'require|max:10', '分类名不能为空|分类名不能超过十个字符'],
		['parent_id', 'number'],
		['id', 'number'],
		['status', 'number|in:-1,0,1', '状态必须为数字|状态范围不合法'],
		['listorder', 'number']
	];

		/*场景设置（不同场景用到的不一样）*/
	protected $scene = [
		'add' => ['name', 'parent_id', 'id'],//添加
		'listorder' => ['id', 'listorder'],//排序
		'status' => ['id', 'status'],//状态
	];
}