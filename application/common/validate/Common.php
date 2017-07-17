<?php
namespace app\Common\validate;
use think\Validate;
class Common extends Validate
{
	protected $rule = [
		['name', 'require|max:10', '名称不能为空|名称不能超过十个字符'],
		['parent_id', 'number'],
		['id', 'number'],
		['status', 'number|in:-1,0,1,2', '状态必须为数字|状态范围不合法'],
		['listorder', 'number'],
		['city_id' , 'require|number|notIn:0', '所属城市不能为空|city_id必须为数字|必须选择所属城市'],
	    ['se_city_id' , 'require|number|notIn:0', '所属二级城市不能为空|se_city_id必须为数字|必须选择所属二级城市'],
	];

		/*场景设置（不同场景用到的不一样）*/
	protected $scene = [
		'add' => ['name', 'parent_id', 'id'],//添加
		'listorder' => ['id', 'listorder'],//排序
		'status' => ['id', 'status'],//状态
	];
}