<?php
namespace app\bis\controller;
use think\Controller;

class Index extends Common
{
	public function index()
	{
		return $this->fetch();
	}
}