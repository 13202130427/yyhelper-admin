<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;
class Admin extends Model{
	use SoftDelete;
	protected $createTime = 'create_time';
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';

	protected function setLastLoginIpAttr(){
		return request() ->ip();
	}
	protected function setLastLoginTimeAttr(){
		return date('Y-m-d H:i:s', time());
	}


	protected function setPasswordAttr($value){
		return md5($value);
	}
	/**
	 * 获取指定ID的等级
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getAdminLevel($id){
		return $this->get($id)->adminLevel->level;
	}

	public function getAdminList(){
		$result = $this->with(['adminLevel'=>function($query){$query->field('id,level,info');}])
		->field("password,delete_time",true)
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
	}

	public function getAdminCount(){
		return $this->count();
	}

	public function getAdminStatistics(){
		$list= [];
		$list['all_count'] = $this->count();
		$list['today'] = $this->whereTime('create_time','today')->count();
		$list['yesterday'] = $this->whereTime('create_time','yesterday')->count();
		$list['week'] = $this->whereTime('create_time','week')->count();
		$list['month'] = $this->whereTime('create_time','month')->count();

		return $list;
	}

	public function getAdmin($id){
		$result = $this->get($id);
		$result['level'] = Db::name('adminLevel')->where('id',$result['level_id'])->value('level');
		return $result->toArray();
	}

	public function adminLevelUpdate($id,$level_id){
		$admin = $this->get($id);
		$admin->level_id = $level_id;
		return $admin->save();
	}


	public function adminAdd($data){
		$admin = $this->where('username', $data['username'])->find();
		if($admin){
			return false;
		}
		$this->last_login_ip = $this->setLastLoginIpAttr();
		$this->last_login_time = $this->setLastLoginTimeAttr();
		$this->password = $data['password'];
		$this->username = $data['username'];
		$this->level_id = $data['level_id'];
		return $this->save();
	}


	public function login($username,$password){
		$admin =  $this->where('username',$username)->where('password',md5($password))->find();
		if($admin){	 
            $admin->login_times = $admin->login_times +1;
			$admin->save();
			return true;
		}else{
			return false;
		}
	}



	public function logout($id){
		$admin = $this->get($id);
		$admin->startTrans();
			try{    
				$admin->last_login_ip = $this->setLastLoginIpAttr();
				$admin->last_login_time = $this->setLastLoginTimeAttr();
				$admin->save();
	            $this->commit();
	            return true;
	        }catch (\Exception $e){
	            $this->rollback();
	            return false;
	        }
	}

	// 判断用户是否存在
    public function isExist($arr=[]){
        if(!is_array($arr)) return false;
        if (array_key_exists('id',$arr)) { // 用户ID
            return $this->where('id',$arr['id'])->find();
        }
        if (array_key_exists('username',$arr)) { // 用户名
           return $this->where('username',$arr['username'])->find();
        }
        return false;
    }

    /**
	 * 管理员操作
	 * @param  [int] $id     [管理员ID]
	 * @param  [string] $handle [用户操作] admin_stop 账号停用 admin_start 账号启用 admin_del 账号删除 admin_huanyuan 账号删除还原 
	 * @return [type]         [description]
	 */
	public function admin_handle($id,$handle){
		if($handle == 'admin_stop'){
			$admin = $this->get($id);
			$admin->admin_status = '1';
			return $admin->save();
		}
		if($handle == 'admin_start'){
			$admin = $this->get($id);
			$admin->admin_status = '0';
			return $admin->save();
		}
		if($handle == 'admin_del'){
			$admin = $this->get($id);
			return $admin->delete(); 
		}
		if($handle == 'admin_delete'){
			$admin = $this->onlyTrashed()->find($id);
			return $admin->delete(true); 
		}
		if($handle == 'admin_huanyuan'){
			$admin = $this->onlyTrashed()->find($id);
			return $admin->restore();
		}
		return false;
	}
	public function update_password($id,$password){
		$user = $this->get($id);
		$user->password = $password;
		return $user->save();
	}

	public function adminLevel(){
		return $this->hasOne('adminLevel','id','level_id');
	}

	public function getAdminInfo($id){
		return $this->get($id);
	}

	
}
?>