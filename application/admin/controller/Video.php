<?php

namespace app\admin\controller;
use think\Controller;
use think\Db;
class Video extends Controller{

	public function play(){
		$url = $_GET['url'];
		$this->assign('videourl',$url);
		return $this->fetch();
	}

}