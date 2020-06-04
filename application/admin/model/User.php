<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;
class User extends Model{

	use SoftDelete;
	protected $createTime = 'create_time';
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';

    /**
     * sex字段的获取器
     * @access public
     * @param integer/string  $value 值
     * @return mixed
     */    
    public function getSexAttr($value){
	    $sex = [0=>'未知',1=>'男',2=>'女'];
	    return $sex[$value];
    }
    /**
     * 获取用户列表
     * @return [type] [description]
     */
	public function getUserList(){
		$result = $this->with(
			['route'=>function($query){$query->field('id,name route_name');}]
			)
		->field("login_times,delete_time,ip,page,speed,code,uid",true)
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
	}
	/**
	 * 获取用户列表数量
	 * @return [type] [description]
	 */
	public function getUserCount(){
		return $this->count();
	}
	/**
	 * 获取查询用户列表
	 * @param  [array] $arr [限制值]
	 * @return [type]      [description]
	 */
	public function getUserSelectList($arr=[]){
		if(!is_array($arr)) return false;
        // 用户名
        if (array_key_exists('name',$arr)) { // 用户名
            if (array_key_exists('datemin',$arr)) { // 创建时间前
	           if (array_key_exists('datemax',$arr)) { // 创建时间后
	           		$result =  $this->where('create_time','>',$arr['datemin'])->where('create_time','<',$arr['datemax'])->where('name','like','%'.$arr['name'].'%')->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
	        	}else{
	        		$result =   $this->where('create_time','>',$arr['datemin'])->where('name','like','%'.$arr['name'].'%')->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
	        	}        	
	        }else{
	        	if (array_key_exists('datemax',$arr)) { // 创建时间后
	           		$result =  $this->where('create_time','<',$arr['datemax'])->where('name','like','%'.$arr['name'].'%')->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
	        	}else{
	        		$result =  $this->where('name','like','%'.$arr['name'].'%')->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
	        	}       	
	        }       
        }
        //手机
        if (array_key_exists('phone',$arr)) { // 手机
            if (array_key_exists('datemin',$arr)) { // 创建时间前
	           if (array_key_exists('datemax',$arr)) { // 创建时间后
	           		$result =  $this->where('create_time','>',$arr['datemin'])->where('create_time','<',$arr['datemax'])->where('phone',$arr['phone'])->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
	        	}else{
	        		$result =  $this->where('create_time','>',$arr['datemin'])->where('phone',$arr['phone'])->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
	        	}    	
	        }else{
	        	if (array_key_exists('datemax',$arr)) { // 创建时间后
	           		$result =  $this->where('create_time','<',$arr['datemax'])->where('phone',$arr['phone'])->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
	        	}else{
	        		$result =  $this->where('phone',$arr['phone'])->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
	        	}
	        }      
        }
        if(!array_key_exists('name',$arr) && !array_key_exists('phone',$arr)){
        	//只限制时间
        	if(array_key_exists('datemin',$arr)){
        		if(array_key_exists('datemax',$arr)){
        			$result = $this->where('create_time','>',$arr['datemin'])->where('create_time','<',$arr['datemax'])->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
        		}else{
        			$result = $this->where('create_time','>',$arr['datemin'])->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
        		}
        	}else{
        		if(array_key_exists('datemax',$arr)){
        			$result = $this->where('create_time','<',$arr['datemax'])->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
        		}else{
        			$result = $this->with(['route'=>function($query){$query->field('id,name route_name');}])->field("login_times,delete_time,ip,page,speed,code,uid",true)->select();
        		}
        	}
        }
        $list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
	}
	/**
	 * 获取查询用户列表数量
	 * @param  [array] $arr [限制值]
	 * @return [type]      [description]
	 */
	public function getUserSelectCount($arr=[]){
		if(!is_array($arr)) return false;
        // 用户名
        if (array_key_exists('name',$arr)) { // 用户名
            if (array_key_exists('datemin',$arr)) { // 创建时间前
	           if (array_key_exists('datemax',$arr)) { // 创建时间后
	           		return $this->where('create_time','>',$arr['datemin'])->where('create_time','<',$arr['datemax'])->where('name','like','%'.$arr['name'].'%')->count();
	        	}else{
	        		return  $this->where('create_time','>',$arr['datemin'])->where('name','like','%'.$arr['name'].'%')->count();
	        	}        	
	        }else{
	        	if (array_key_exists('datemax',$arr)) { // 创建时间后
	           		return $this->where('create_time','<',$arr['datemax'])->where('name','like','%'.$arr['name'].'%')->count();
	        	}else{
	        		return $this->where('name','like','%'.$arr['name'].'%')->count();
	        	}       	
	        }       
        }
        //手机
        if (array_key_exists('phone',$arr)) { // 手机
            if (array_key_exists('datemin',$arr)) { // 创建时间前
	           if (array_key_exists('datemax',$arr)) { // 创建时间后
	           		return $this->where('create_time','>',$arr['datemin'])->where('create_time','<',$arr['datemax'])->where('phone',$arr['phone'])->count();
	        	}else{
	        		return $this->where('create_time','>',$arr['datemin'])->where('phone',$arr['phone'])->count();
	        	}    	
	        }else{
	        	if (array_key_exists('datemax',$arr)) { // 创建时间后
	           		return $this->where('create_time','<',$arr['datemax'])->where('phone',$arr['phone'])->count();
	        	}else{
	        		return $this->where('phone',$arr['phone'])->count();
	        	}
	        }      
        }
        if(!array_key_exists('name',$arr) && !array_key_exists('phone',$arr)){
        	//只限制时间
        	if(array_key_exists('datemin',$arr)){
        		if(array_key_exists('datemax',$arr)){
        			return  $this->where('create_time','>',$arr['datemin'])->where('create_time','<',$arr['datemax'])->count();
        		}else{
        			return  $this->where('create_time','>',$arr['datemin'])->count();
        		}
        	}else{
        		if(array_key_exists('datemax',$arr)){
        			return  $this->where('create_time','<',$arr['datemax'])->count();
        		}else{
        			return  $this->count();
        		}
        	}
        }
	}

	public function getUserStatistics(){
		$list= [];
		$list['all_count'] = $this->count();
		$list['today'] = $this->whereTime('create_time','today')->count();
		$list['yesterday'] = $this->whereTime('create_time','yesterday')->count();
		$list['week'] = $this->whereTime('create_time','week')->count();
		$list['month'] = $this->whereTime('create_time','month')->count();

		return $list;
	}
	/**
	 * 获取用户删除列表
	 * @return [type] [description]
	 */
	public function getUserDeleteList(){
		$result = $this->onlyTrashed()->field("id,name,sex,phone,create_time,status")->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
	}
	/**
	 * 获取用户删除列表数量
	 * @return [type] [description]
	 */
	public function getUserDeleteCount(){
		return $this->onlyTrashed()->count();
	}
	
	/**
	 * 获取用户数据
	 * @param  [int] $id [用户ID]
	 * @return [type]     [description]
	 */
	public function getUserInfo($id){
		$result = $this->withTrashed()->field("name,city,province,country,photo")->find($id);
		return $result->toArray();
	}
	/**
	 * 用户操作
	 * @param  [int] $id     [用户ID]
	 * @param  [string] $handle [用户操作] user_stop 账号停用 user_start 账号启用 user_del 账号删除 user_huanyuan 账号删除还原 
	 * @return [type]         [description]
	 */
	public function user_handle($id,$handle){
			$user = $this->get($id);
		if($handle == 'user_stop'){
			$user->status = '1';
			return $user->save();
		}
		if($handle == 'user_start'){
			$user->status = '0';
			return $user->save();
		}
		if($handle == 'user_del'){
			$result = Db::name('uid')->where('id',$user->uid)->update(['status'=>0]);
			return $user->delete(); 
		}
		if($handle == 'user_delete'){
			$user = $this->onlyTrashed()->find($id);
			return $user->delete(true); 
		}
		if($handle == 'user_huanyuan'){
			$user = $this->onlyTrashed()->find($id);
			$user->restore();
		    return  Db::name('uid')->where('id',$user->uid)->update(['status'=>1]);
		}
		return false;
	}
	public function update_password($id,$password){
		$user = $this->get($id);
		$user->password = md5($password);
		return $user->save();
	}
	public function update_phone($id,$phone){
		$user = $this->get($id);
		$user->phone = $phone;
		return $user->save();
	}
	public function uid(){
		return $this->hasOne('uid','id','uid');
	}
	public function audit(){
		return $this->belongsTo('audit');
	}

	public function route(){
		return $this->hasOne('route','id','route_id');
	}

	public function getname($id){
		return $this->where('id',$id)->value('name');
	}

	public function getphone($id){
		return $this->where('id',$id)->value('phone');
	}
}
?>