<?php


namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\AdminLevel as AdminLevelModel;
use app\admin\model\Admin as AdminModel;
class Admin extends Controller{
  //角色管理页面
  public function admin_role(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_look'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作','/admin');
    }


    $AdminLevelModel = new AdminLevelModel();
    $manage_level = $AdminLevelModel->getAdminLevelList();

  	$manage_count = $AdminLevelModel->getAdminLevelCount();
  	

  	$this->assign('manage_level',$manage_level);
  	$this->assign('manage_count',$manage_count);
  	return $this->fetch();
  }
  //管理员列表页面
	public function admin_list(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_look'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }
    $AdminModel = new AdminModel();
    $manage = $AdminModel->getAdminList();

    $manage_count = $AdminModel->getAdminCount();
    $this->assign('manage',$manage);
    $this->assign('manage_count',$manage_count);
		return $this->fetch();
	}
  //添加角色页面
  public function admin_role_add(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_level_add'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

  	return $this->fetch();
  }
  //编辑角色
  public function admin_role_edit(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

    $id = input('id');
    $AdminLevelModel = new AdminLevelModel();
    $manage_level = $AdminLevelModel->getAdminLevel($id);



    $this->assign('manage_level',$manage_level);
    return $this->fetch();
  }
  //添加管理员账号
  public function admin_add(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }
    $AdminLevelModel = new AdminLevelModel();
    $manage_level = $AdminLevelModel->getAdminLevelList();


    $this->assign('manage_level',$manage_level);
    return $this->fetch();
  }
  //管理员权限修改页面
  public function admin_level_edit(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

    $id = input('id');
    $AdminModel = new AdminModel();
    $manage = $AdminModel->getAdmin($id);
    $AdminLevelModel = new AdminLevelModel();
    $manage_level = $AdminLevelModel->getAdminLevelList();

    $this->assign('manage_level',$manage_level);
    $this->assign('manage',$manage);
    return $this->fetch();
  }
   //修改密码页面
  public function change_password(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

    $id = input('id');
    if(!isset($id) || !is_numeric($id)){
      $this->error('传递错误');
    }
    $AdminModel = new AdminModel();
    $manage = $AdminModel->getAdmin($id);

    
    $this->assign('manage',$manage);
    return $this->fetch();
  }
  //添加角色
  public function manage_level_add(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_level_add'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

  	$data = input('post.');
    $AdminLevelModel = new AdminLevelModel();
    $result = $AdminLevelModel->AdminLevelAdd($data);
  	if($result){
      $this->success('修改成功');	
  	}else{
      $this->error('添加失败');
    }
  }
  //删除角色
  public function manage_level_delete(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

	  $id = input('id');
    if(!isset($id) || !is_numeric($id)){
      return false;
    }
    //删除角色
    $AdminLevelModel = new AdminLevelModel();
    $result = $AdminLevelModel->adminLevelDel($id);

    return $result;
  }
  
  //修改角色
  public function manage_level_update(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

  	$data = input('post.');
    
    unset($data['admin_role_save']);
  	$id = input('id');
    //$manage_level = Db::name('admin_level')->where('id',$id)->strict(false)->update($data);

  	$AdminLevelModel = new AdminLevelModel();
    $manage_level = $AdminLevelModel->updateAdminLevel($id,$data);
  	if($manage_level){
  		$this->success('修改成功');
  	}else{
      $this->error('修改失败');
    }
  }
  
  //删除管理员账号
  public function admin_del(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

  	$id = input('id');
  	$AdminModel = new AdminModel();
    $result = $AdminModel->admin_handle($id,'admin_del');
    
    return $result;
    
  }
  
  //管理员权限修改操作
  public function manage_level_edit(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

    $id=$_GET['id'];
    $data = input('post.');
   
    if(!isset($id) || !is_numeric($id)){
      $this->error('参数传递错误');
    }

    $AdminModel = new AdminModel();
    $result = $AdminModel->adminLevelUpdate($id,$data['level_id']);
      if($result){
        $this->success('修改成功');
      }else{
         $this->success('修改失败');
      }
  }

  //添加管理员操作
  public function manage_add(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

    $data = input('post.');

    $AdminModel = new AdminModel();
    $result = $AdminModel->adminAdd($data);

    if($result){
      return true;
    }else{
      return false;
    }
  }

  //停用管理员账号
  public function admin_stop(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

    $id = input('id');
    if(!isset($id) || !is_numeric($id)){
      return false;
    }
    $AdminModel = new AdminModel();
    $result = $AdminModel->admin_handle($id,'admin_stop');
    return $result;
  }

  //启用管理员账号
  public function admin_start(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

    $id = input('id');
    if(!isset($id) || !is_numeric($id)){
      return false;
    }
    $AdminModel = new AdminModel();
    $result = $AdminModel->admin_handle($id,'admin_start');
    return $result;
  }
  
   //修改密码操作
  public function update_password(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['manage_handle'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }

    $id=$_GET['id'];
    $data = input('post.');
   
    if(!isset($id) || !is_numeric($id)){
      $this->error('参数传递错误');
    }

      $AdminModel = new AdminModel();
      $result = $AdminModel->update_password($id,$data['newpassword']);
      if($result){
        $this->success('修改成功');
      }else{
         $this->success('修改失败,与原密码一致');
      }
  }
}
?>