<?php
namespace app\index\controller;

use think\Controller;

class Common extends Controller
{
	protected $obj, $val, $model, $city, $account = '';// 注意为protected

	public function _initialize()// _initialize为构造函数
    {
    	// 获取控制器
    	$this->model = request()->controller();

        $this->obj = Model($this->model);
        // 调用TP5的validate方法（经过了自定义）
        $this->val = validate($this->model);

        // 获取省份数据
        $citys = Model('City')->getNormalFirstData(0, 1);
        // 获取首页分类数据
        $cats = $this->getRecommendCats();
        $this->getCity($citys);

        $this->assign('cats', $cats);
        $this->assign('citys', $citys);
        $this->assign('city', $this->city);
        $this->assign('title', 'o2o团购网');
        $this->assign('user', $this->getLoginUser());
        $this->assign('controller', strtolower($this->model));
    }

    protected function check($scene = '', $data)
    {
        // 数据校验
        if(!$this->val->scene($scene)->check($data)) {
            $this->error($this->val->getError());// 返回错误信息
        }
    }

    public function getCity($citys)
    {
        foreach($citys as $city) {
            if($city['is_default'] == 1) {
                $defaultid = $city['id'];
                break;
            }
        }
        $defaultid = $defaultid ? $defaultid : '1';

        if(session('cityid', '', 'o2o') && !is_numeric(input('get.city'))) {
            $cityid = session('cityid', '', 'o2o');
        }else {
            $cityid = input('get.city', $defaultid, 'trim');
            session('cityid', $cityid, 'o2o');
        }
        $this->city = Model('City')->where(['id'=>$cityid])->find();

    }

    public function getLoginUser()
    {
        if(!$this->account) {
            $this->account = session('o2o_user', '', 'o2o');
        }
        return $this->account;
    }

    /**
     * 获取首页推荐中的商品分类数据
     * @Author   A-Li
     * @DateTime 2017-07-12T11:38:50+0800
     * @return   [type]                   [description]
     */
    public function getRecommendCats()
    {
        $parentIds = $sedcatArr = $recomCats = [];

        // 获取一级数据
        $cats = Model('Category')->getNormalRecommendCategoryByParentId();

        // 遍历出所有的parentId
        foreach($cats as $cat) {
            $parentIds[] = $cat->id;
        }

        // 获取二级数据
        $sedCats = Model('Category')->getNormalRecommendCategoryByParentId($parentIds);

        // 将二级数据封装成数组
        foreach ($sedCats as $sedCat) {
            $sedcatArr[$sedCat->parent_id][] = [
                'id' => $sedCat->id,
                'name' => $sedCat->name,
            ];
        }
        // 将一级和二级数据封装成二维数组
        foreach ($cats as $cat) {
            // 第一个参数是一级分类的name，第二个参数是此一级分类下的所有二级分类数据
            $recomCats[$cat->id] = [$cat->name, empty($sedcatArr[$cat->id]) ? [] : $sedcatArr[$cat->id]];
        }
        return $recomCats;
    }
}