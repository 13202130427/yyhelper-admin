<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class Audit extends Model{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';

 public function getPersonAuditList(){
	$result = $this->with(['user'=>function($query){$query->field('id,name');}])
 		->where('type',0)->where('status',0)
		->field("edu_bg,start_time,type,university_id,status",true)
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
 }
 public function getPersonAuditCount(){
 	return $this->where('type',0)->where('status',0)->count();
 }

 public function getStudentAuditList(){
 	$result = $this->with(['user'=>function($query){$query->field('id,name');},
 		'university'=>function($query){$query->field('id,name');}])
 		->where('type',1)->where('status',0)
		->field("type,status",true)
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
 }

 public function getStudentAuditCount(){
 	return $this->where('type',1)->where('status',0)->count();
 }

/**
 * 审核操作
 * @param  [int] $id     [审核ID]
 * @param  [string] $obj    [审核对象] student 学生认证 person 实名认证
 * @param  [int] $status [审核状态] 0 未通过 1 通过
 * @param  [string] $cause  [原因] 审核失败原因
 * @return [bool]         [description]
 */
 public function audit_handle($id,$obj,$status,$cause=null){
 	try{ 
 		$this->startTrans();
 		$audit = $this->get($id);
 		$user = $audit->user;
 		if($obj == 'student'){
	 		if($status == 0){   
	            $audit->save(['status'=>2,'cause'=>$cause]);
	            $user->save(['student_status'=>0]);        
	 		}
	 		if($status == 1){	   
	            $audit->save(['status'=>1]);
	            $user->save(['student_status'=>1]);   
	 		}
	 	}
	 	if($obj == 'person'){
	 		if($status == 0){
	 			$audit->save(['status'=>2,'cause'=>$cause]);
	 			$user->save(['person_status'=>0]);
	 		}
	 		if($status == 1){
	 			$audit->save(['status'=>1]);
	 			$user->save(['person_status'=>1]);
	 		}
	 	}
        $this->commit();
        return true;
    }catch(\Exception $e){
        $this->rollback();
        return false;
    }
 }


 public function user(){
	return $this->hasOne('user','id','userId');
}

public function university(){
	return $this->hasOne('university','id','university_id');
}
	
}
?>