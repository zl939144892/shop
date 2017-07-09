<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    
    // public function map()
    // {
    // 	return \Map::staticimage('成都市青羊区上道西城A区');
    // }
}