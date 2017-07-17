<?php
namespace app\common\validate;
use think\Validate;
class Bis extends Common{
	protected $rule = [
		['id', 'number'],
		['name' , 'require|max:25', '商户名称不能为空|商户名称长度不能超过25字符'],
	    
	    ['logo' , 'require', '缩略图不能为空'],
	    ['licence_logo' , 'require', '营业执照不能为空'],
	    ['bank_info' , 'require|min:19|max:21', '银行账号不能为空|银行账号长度不能少于19位|银行账号长度不能超过21位'],
	    ['bank_name' , 'require|min:4', '开户行名称不能为空|开户行名称长度不能少于4字符'],
	    ['bank_user' , 'require|min:2', '开户人姓名不能为空|开户人姓名长度不能少于2字符'],
	    ['faren' , 'require|min:2', '法人不能为空|法人长度不能少于2字符'],
	    ['faren_tel' , 'require|min:5', '法人电话不能为空|法人电话长度不能少于5位'],
	    ['email' , 'require|email', '邮箱不能为空|邮箱格式不正确'],
	    ['tel' , 'require|min:5', '电话不能为空|电话长度不能少于5位'],
	    ['contact' , 'require|max:8', '联系人不能为空|联系人长度不能超过8字符'],
	    ['category_id' , 'require|number|notIn:0', '所属分类不能为空|category_id必须为数字|必须选择所属分类'],
	    ['address' , 'require', '商户地址不能为空'],
	    ['open_time' , 'require', '营业时间不能为空'],
	    ['username' , 'require|max:15', '用户名不能为空|用户名不能超过15字符'],
	    ['password' , 'require|min:8', '密码不能为空|密码长度不能少于8位'],
	    ['status', 'number|in:-1,0,1,2', '状态必须为数字|状态范围不合法'],
		['description', 'require|min:20', '商户介绍必须填写|写商户介绍的时候走点心，多写点吧'],
		['content', 'require|min:20', '门店简介必须填写|写门店简介的时候走点心，多写点吧'],
	];

	protected $scene = [
		'status' => ['id', 'status'],
		'login' => ['password', 'username'],
		'bisLocation' => ['name', 'city_id', 'se_city_id', 'logo', 'content', 'category_id', 'address', 'tel', 'contact', 'open_time'],
	];
}