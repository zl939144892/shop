<?php
namespace app\bis\controller;
use think\Controller;

class Register extends Controller
{
	private $val;

	public function _initialize()
	{
		$this->val = validate('Bis');
	}
	public function index()
	{
		// 获取一级城市的数据
		$citys = model('City')->getNormalByParentId();
        // 获取一级分类的数据
		$categorys = model('Category')->getNormalByParentId();
		return $this->fetch('',[
			'citys' => $citys,
			'categorys' => $categorys,
		]);
	}

	public function add()
	{
		if(!request()->ispost()) {
			$this->error('请求错误');
		}
		// 获取表单的值
		$data = input('post.');

		// 校验数据
		if(!$this->val->check($data)) {
    		$this->error($this->val->getError());//返回错误信息
    	}

    	// 判断提交的信息是否存在
    	$accountReeult = Model('BisAccount')->get(['username'=>$data['username']]);
    	$bisReeult = Model('Bis')->get(['name'=>$data['name']]);
    	if($accountReeult) {
    		$this->error('该用户名已被注册，请重新填写');
    	}else if($bisReeult) {
    		$this->error('该商户已入驻，请核实信息');
    	}

        // 把选择的城市和输入的地址连接
        $address1 = getSeCityName($data['city_id']);
        $address2 = getSeCityName($data['se_city_id']);
        $data['address'] = $address1.$address2.$data['address'];

    	// 获取经纬度
    	$lnglat = \Map::getLngLat($data['address']);
    	if(empty($lnglat) || $lnglat['status'] != 0) {
    		$this->error('无法获取地图数据，请检查地址输入内容');
    	}elseif ($lnglat['result']['precise'] != 1) {
    		$this->error('输入的地址信息过于模糊，请重新输入');
    	}

    	// 商户基本信息入库
    	$bisData = [
    		'name' => $data['name'],
            'logo' => $data['logo'],
            'licence_logo' => $data['licence_logo'],
    		'city_id' => $data['city_id'],
            'se_city_id' => $data['se_city_id'],
    		'city_path' => $data['city_id'].','.$data['se_city_id'],
            'bank_info' => $data['bank_info'],
    		'description' => $data['description'],
    		'bank_user' => $data['bank_user'],
    		'bank_name' => $data['bank_name'],
    		'faren' => $data['faren'],
    		'faren_tel' => $data['faren_tel'],
            'email' => $data['email'],
    		'money' => $data['money'],
    	];
    	$bisId = model('Bis')->add($bisData);

        // 把多个二级分类使用 | 进行连接
    	$data['cate_id'] = '';
    	if(!empty($data['se_category_id'])) {
    		$data['cate_id'] = implode('|', $data['se_category_id']);
    	}
    	// 总店信息入库
    	$locationData = [
    		'bis_id' => $bisId,
    		'name' => $data['name'],
    		'logo' => $data['logo'],
    		'tel' => $data['tel'],
    		'contact' => $data['contact'],
            'category_id' => $data['category_id'],
    		'se_category_id' =>empty($data['se_category_id']) ? '' : implode(',', $data['se_category_id']),
    		'category_path' => $data['category_id'].','.$data['cate_id'],
    		'city_id' => $data['city_id'],
            'se_city_id' => $data['se_city_id'],
    		'city_path' => $data['city_id'].','.$data['se_city_id'],
    		'api_address' => $data['address'],
    		'open_time' => $data['open_time'],
    		'content' => $data['content'],
    		'is_main' => 1, // 代表的是该条信息为总店信息
    		'xpoint' => $lnglat['result']['location']['lng'],
    		'ypoint' => $lnglat['result']['location']['lat'],
    	];
    	$locationId = model('BisLocation')->add($locationData);

    	// 自动生成密码的加盐字符串
    	$data['code'] = mt_rand(1000,10000);

    	// 账户信息入库
    	$accountData = [
    		'bis_id' => $bisId,
    		'username' => $data['username'],
    		'code' => $data['code'],
    		'password' => md5(md5($data['password']).$data['code']),
    		'is_main' => 1, // 代表的是此账户为店铺总管理员
    	];
    	$accountId = model('BisAccount')->add($accountData);

    	if(!$accountId) {
    		$this->error('申请失败');
    	}

    	// 发送邮件
    	$url = request()->domain().url('bis/register/waiting', ['id' => $bisId]);
    	$title = '入驻申请通知';
    	$content = '您提交的入驻申请须等待平台审核，您可以点击<a href="'.$url.'" target="_blank"> 查看审核状态 </a>来了解审核进度';
    	\phpmailer\Email::send($data['email'], $title, $content);

        $this->success('申请成功', url('register/waiting',['id'=>$bisId]));
	}

	public function waiting($id)
	{
		if(empty($id)) {
            $this->error('非法访问！');
        }
        $detail = Model('Bis')->get($id);

        return $this->fetch('',[
            'detail' =>$detail,
        ]);
	}
}