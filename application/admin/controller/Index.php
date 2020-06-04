<?php


namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Admin as AdminModel;
use app\admin\model\Video as VideoModel;
use app\admin\model\News as NewsModel;
use app\admin\model\Route as RouteModel;
use app\admin\model\User as UserModel;
use app\admin\model\Broadcast as BroadcastModel;
class Index extends Controller{
  
  //登录页面
  public function login(){
    session_start();
    $manage_id = is_manage();

    if($manage_id){
      alert('已登录请勿重复登录','jump','index');
    }


    return $this->fetch();
  }

  
  //后台页面
  public function index(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    $AdminModel = new AdminModel();
    $level = $AdminModel->getAdminLevel($manage_id);

    $BroadcastModel = new BroadcastModel();
    $broadcast = $BroadcastModel->getBroadcastCount($manage_id);

    $this->assign('level',$level);
    $this->assign('broadcast',$broadcast);
    return $this->fetch();
  }
   //后台欢迎页面
  public function welcome(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    $AdminModel = new AdminModel();
    $res = $AdminModel->getAdminInfo($manage_id);
    $admin = $AdminModel->getAdminStatistics();
    $UserModel = new UserModel();
    $user = $UserModel->getUserStatistics();
    $VideoModel = new VideoModel();
    $video = $VideoModel->getVideoStatistics();
    $NewsModel = new NewsModel();
    $news = $NewsModel->getNewsStatistics();
    $RouteModel = new RouteModel();
    $route = $RouteModel->getRouteStatistics();

    //var_dump($_SERVER);die;

    $this->assign('time',$res['last_login_time']);
    $this->assign('times',$res['login_times']);
    $this->assign('ip',$res['last_login_ip']);
    $this->assign('user',$user);
    $this->assign('video',$video);
    $this->assign('news',$news);
    $this->assign('route',$route);
    $this->assign('admin',$admin);
   $this->assign('server',$_SERVER);
    return $this->fetch();
  }
  
  //登出
  public function logout(){
    session_start();
    if(!$manage_id=is_manage()){
        alert('请登录','jump','admin/login');
    }
    $AdminModel = new AdminModel();
    if($AdminModel->logout($manage_id)){
      session(null);
    }else{
      $this->error('登出失败');
    }
    
    alert('退出成功','jump','login');

  }

  // 验证码检测
    public function check($code='')
    {
        if (!captcha_check($code)) {
            $this->error('验证码错误');
        } else {
            return true;
        }
    }

  //后台登录验证
  public function login_insert(){
    session_start();
    $manage_id = is_manage();

    if($manage_id){
      alert('已登录请勿重复登录','jump','index');
    }

    
    $data = input('post.');
    $this->check($data['vcode']);//验证码验证

    //判断当前用户名是否存在
    $AdminModel = new AdminModel();
    if($AdminModel->isExist(['username'=>$data['username']])){
      if($AdminModel->login($data['username'],$data['password'])){
            session("manage.username", $data['username']);
            session("manage.password", $data['password']);
            $this->success('登录成功', '/admin');
      }else{
         $this->error('密码错误');
      }
    }else{
       $this->error('当前账号不存在');
    }

  }
}
?>