<?php


namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\User as UserModel;
use app\admin\model\Audit as AuditModel;
class User extends Controller{
  
  //账号列表
  public function user_list(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }

      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_look'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
        //$this->error('当前权限不够，请勿进行当前操作');
      }
      $UserModel = new UserModel();
      $result = $UserModel->getUserList();
      $count = $UserModel->getUserCount();
      $this->assign('data',$result);
      $this->assign('count',$count);
      return $this->fetch();
  }
  //用户查询
  public function user_select(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['user_look'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
      //$this->error('当前权限不够，请勿进行当前操作');
    }
    $data = input('post.');

    $arr = [];
    if(!empty($data['value'])){
      if(is_numeric($data['value']) && strlen($data['value']) == 11){
        //手机类型
        $arr['phone'] = $data['value'];
      }else{
        $arr['name'] = $data['value'];
      }
    }
    if(!empty($data['datemin'])){
      $arr['datemin'] = $data['datemin'];
    }
    if(!empty($data['datemax'])){
      $arr['datemax'] = $data['datemax'];
    }
    
    $UserModel = new UserModel();
    $result = $UserModel->getUserSelectList($arr);

    $count = $UserModel->getUserSelectCount($arr);
    $this->assign('data',$result);
    $this->assign('count',$count);
    return $this->fetch('user/user_list');
  }
  //信息显示
  public function user_show(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_look'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }
      $id = input('id');
      if(!isset($id) || !is_numeric($id)){
        return false;
      }
      $UserModel = new UserModel();
      $result = $UserModel->getUserInfo($id); 

      $this->assign('data',$result);
      return $this->fetch();
  }
  //实名审核
  public function user_audit_person(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
     
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_audit'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }
      $AuditModel = new AuditModel();
      $result = $AuditModel->getPersonAuditList(); 
       $count = $AuditModel->getPersonAuditCount(); 
      $this->assign('data',$result);
      $this->assign('count',$count);
      return $this->fetch();
  }
  //学生认证审核
  public function user_audit_student(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
     
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_audit'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }
      $AuditModel = new AuditModel();
      $result = $AuditModel->getStudentAuditList(); 

      $count = $AuditModel->getStudentAuditCount(); 
      $this->assign('data',$result);
      $this->assign('count',$count);
      return $this->fetch();
  }
   //已删除账号列表
  public function user_deletelist(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_look'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }

      $UserModel = new UserModel();
      $result = $UserModel->getUserDeleteList();
      $count = $UserModel->getUserDeleteCount();
      

      $this->assign('data',$result);
      $this->assign('count',$count);
      return $this->fetch();
  }

  //修改手机页面
  public function change_phone(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_handle'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }

      $id = input('id');
      if(!isset($id) || !is_numeric($id)){
        return false;
      }
      $UserModel = new UserModel();
      $phone = $UserModel->getphone($id);
      
      $this->assign('phone',$phone);
      $this->assign('id',$id);
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

      if($level['user_handle'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }
      $id = input('id');
      if(!isset($id) || !is_numeric($id)){
        $this->error('传递错误');
      }

      $UserModel = new UserModel();
      $name = $UserModel->getname($id);
      
      $this->assign('name',$name);
      $this->assign('id',$id);
      return $this->fetch();
  }

  //修改实名状态
  public function audit_p(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_audit'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }

      $id=input('id');
      $status = input('status');
      if(!isset($id) || !is_numeric($id) ||!isset($status) || !is_numeric($status)){
        $this->error('参数传递错误');
      }
      if($status == 1){//审核通过
        $AuditModel = new AuditModel();
        return $AuditModel->audit_handle($id,'person','1'); 
      }
      if($status == 0){//审核不通过
        $cause = input('cause');
        $AuditModel = new AuditModel();
        return  $AuditModel->audit_handle($id,'person','0',$cause); 
      }
  }
  //修改学生认证状态
  public function audit_s(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_audit'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }

      $id=input('id');
      $status = input('status');
      if(!isset($id) || !is_numeric($id) ||!isset($status) || !is_numeric($status)){
        $this->error('参数传递错误');
      }
      if($status == 1){//审核通过
        $AuditModel = new AuditModel();
        return $AuditModel->audit_handle($id,'student','1'); 
      }

      if($status == 0){//审核不通过
        $cause = input('cause');
        $AuditModel = new AuditModel();
        return $AuditModel->audit_handle($id,'student','0',$cause); 
      }
  }
  //批量删除账号
  public function user_alldel(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['user_del'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
     // $this->error('当前权限不够，请勿进行当前操作');
    }
    $data = input('post.');   
    if(!isset($data['id'])){
      return false;
    }
    $array = explode(',', $data['id']);
    $UserModel = new UserModel();
    $UserModel ->startTrans();
    try{ 
        foreach ($array as $key => $value) {
           $UserModel->user_handle($value,'user_del');
        }
        $UserModel->commit();
        return true;
    }catch(\Exception $e){
        $UserModel->rollback();
        return false;
    }
  }

  //批量永久删除账号
  public function user_alldelete(){
    session_start();
    $manage_id = is_manage();

    if(!$manage_id){
      alert('请登录','jump','admin/login');
    }
    
    if(!$level = getLevel($manage_id)){
      alert('当前用户无权限角色','close');
    }

    if($level['user_del'] == 0){
      alert('当前权限不够，请勿进行当前操作','close');
     // $this->error('当前权限不够，请勿进行当前操作');
    }
    $data = input('post.');   
    if(!isset($data['id'])){
      return false;
    }
    $array = explode(',', $data['id']);
    $UserModel = new UserModel();
    $UserModel ->startTrans();
    try{ 
        foreach ($array as $key => $value) {
           $UserModel->user_handle($value,'user_delete');
        }
        $UserModel->commit();
        return true;
    }catch(\Exception $e){
        $UserModel->rollback();
        return false;
    }
  }

  //停用账号
  public function user_stop(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_handle'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }

      $id = input('id');
      if(!isset($id) || !is_numeric($id)){
        return false;
      }
      $UserModel = new UserModel();
      $result = $UserModel->user_handle($id,'user_stop');
      return $result;
  }

  //启用账号
  public function user_start(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_handle'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }

      $id = input('id');
      if(!isset($id) || !is_numeric($id)){
        return false;
      }

      $UserModel = new UserModel();
      $result = $UserModel->user_handle($id,'user_start');
      return $result;
  }
  //删除账号
  public function user_del(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_del'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }
      $id = input('id');
      if(!isset($id) || !is_numeric($id)){
        return false;
      }
      $UserModel = new UserModel();
      $result = $UserModel->user_handle($id,'user_del');
      return $result;
  }
  //永久删除账号
  public function user_delete(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_del'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }

      $id = input('id');
      if(!isset($id) || !is_numeric($id)){
        return false;
      }
      $UserModel = new UserModel();
      $result = $UserModel->user_handle($id,'user_delete');
      return $result;
  }
  //还原删除账号
  public function user_huanyuan(){
      session_start();
      $manage_id = is_manage();

      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_del'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }
      $id = input('id');
      if(!isset($id) || !is_numeric($id)){
        return false;
      }
      
      $UserModel = new UserModel();
      $result = $UserModel->user_handle($id,'user_huanyuan');
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

      if($level['user_handle'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }
      $id=$_GET['id'];
      $data = input('post.');   
      if(!isset($id) || !is_numeric($id)){
        $this->error('参数传递错误');
      }
      $UserModel = new UserModel();
      $result = $UserModel->update_password($id,$data['newpassword']);
      if($result){
        $this->success('修改成功');
      }else{
         $this->success('修改失败,与原密码一致');
      }
  }
  //修改手机操作
  public function update_phone(){
      session_start();
      $manage_id = is_manage();
      if(!$manage_id){
        alert('请登录','jump','admin/login');
      }
      if(!$level = getLevel($manage_id)){
        alert('当前用户无权限角色','close');
      }

      if($level['user_handle'] == 0){
        alert('当前权限不够，请勿进行当前操作','close');
       // $this->error('当前权限不够，请勿进行当前操作');
      }
      $id=$_GET['id'];
      $data = input('post.');   
      if(!isset($id) || !is_numeric($id)){
        $this->error('参数传递错误');
      }
      $UserModel = new UserModel();
      $result = $UserModel->update_phone($id,$data['phone']);
      if($result){
        $this->success('修改成功');
      }else{
         $this->success('修改失败,与原手机号码一致');
      }
  }
}
?>