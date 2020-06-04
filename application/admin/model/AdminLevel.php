<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class AdminLevel extends Model{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';
	public function getAdminLevelList(){
		//$supper = $this->get(1);
		//$result = $supper->admin;
		$result = $this->field('id,level,info')->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		foreach ($list as $key => $value) {
			$name = '';
			$result1 = $this->get($value['id'])->admin;
			foreach ($result1 as $key1 => $value1) {
				$name = $name . ',' . $value1['username'];
			}
			$name = trim($name,',');
			$list[$key]['username'] = $name;
		}
		return $list;
	}

	public function getAdminList(){
		$result = $this->with(['admin'=>function($query){$query->field('id,username,level_id');}])
		->field("id,level")
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
	}

	public function getAdminLevelCount(){
		return $this->count();
	}


	public function getAdminLevel($id){
		return $this->get($id)->toArray();
	}

	public function adminLevelDel($id){
		$adminLevel = $this->get($id);
		$admin = $this->get($id)->admin;
		foreach ($admin as $key => $value) {
			$value->level_id = '2';
			$value->save();
		}
		return $adminLevel->delete();
	}

	public function updateAdminLevel($id,$data){
		return  $this->save($data, ['id' => $id]);
	}

	public function adminLevelAdd($data){
		return $this->allowField(true)->save($data);
	}

	public function admin(){
		return $this->hasMany('admin','level_id','id');

	}



	
}
?>