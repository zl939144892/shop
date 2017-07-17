<?php
namespace app\Common\validate;
use think\Validate;
class Featured extends Common
{
	protected $rule = [
		['title', 'require|max:15', '标题不能为空|标题不能超过十五个字符'],
		['image', 'require', '图片不能为空'],
		['type', 'require|number', '所属分类不合法|所属分类不合法'],
	];

	protected $scene = [
		'add' => ['title', 'image', 'type'],
	];
}