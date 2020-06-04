<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;
class Broadcast extends Model{
	use SoftDelete;
	protected $createTime = 'create_time';
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';
    /**
     * 批量发送站内广播
     * @param  [int] $send_id   [发送者ID]
     * @param  [arr] $accept_id [接收者ID]
     * @param  [string] $tilte     [标题]
     * @param  [text] $info      [内容]
     * @return [bool]            [description]
     */
	public function sendBroadcast($send_id,$accept_id=[],$title,$info){
		$list = [];
		$this->startTrans();
		try{    
			foreach ($accept_id as $key => $value) {
				$list[$key] = [
					'send_admin_id'=>$send_id,
					'accept_admin_id'=>$value,
					'title'=>$title,
					'info'=>$info
				];			
			}
			$this->saveAll($list);
			$this->commit();
            return true;
        }catch (\Exception $e){
            $this->rollback();
            return false;
        }
		
	}

	public function getBroadcast($id){
		$result = $this->where('accept_admin_id',$id)->order('status','asc')->select();
		$list= [];
		foreach ($result as $key => $value) {
			$result[$key]['send_username'] = Db::name('admin')->where('id',$value['send_admin_id'])->value('username');
		}
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
	}

	public function getBroadcastCount($id){
		return $this->where('accept_admin_id',$id)->where('status',0)->count();
	}

	public function read($id){
		$broadcast = $this->get($id);
		$broadcast->status = 1;
		return $broadcast->save();
	}


	
}
?>