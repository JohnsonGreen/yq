<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 后台首页控制器
 */
class AnnounceController extends AdminBaseController{
	/**
	 * 首页
	 */

	public function index(){
		@session_start();
		$announcement = D('Announce')->join('left join yq_user on yq_announce.userid = yq_user.userid')
										->order('yq_announce.createtime desc, stick desc')->select();

		$announcement = stand_date($announcement);
//		cout($announcement);
		$res['is_err'] = 1;
		if($announcement)
			$res['is_err'] = 0;
		$res['result'] = $announcement;
		$res['max_page'] = count($announcement)/10;
		echo json_encode($res);
		exit;
	}

	public function search(){
		$date1 = strtotime(I('post.date1'));
		$date2 = strtotime(I('post.date2'));
		$map['createtime'] = array('gt', $date1);
		$map['createtime'] = array('lt', $date2);
		$result = D('Announce')	->join('yq_user on yq_announce.userid = yq_user.userid')
								->where($map)
								->order('yq_announce.createtime desc, stick desc')->select();
		$result = stand_date($result);
		$res['is_err'] = 1;
		if($result)
			$res['is_err'] = 0;

		$res['result'] = $result;
		$res['max_page'] = count($result)/10;
		echo json_encode($res);
		exit;

	}
	public function key_search(){
		$key = I('post.keywords');
		$map['title'] = array('like', $key);
		$result = D('Announce')	->join('yq_user on yq_announce.userid = yq_user.userid')
			->where($map)
			->order('yq_announce.createtime desc, stick desc')->select();
		$result = stand_date($result);
		$res['is_err'] = 1;
		if($result)
			$res['is_err'] = 0;
		$res['result'] = $result;
		$res['max_page'] = count($result)/10;
		echo json_encode($res);
		exit;
	}

	//置顶
	public function stick(){
		$id = I('post,id');
		$data['anoceid'] = $id;
		$data['stick'] = 1;
		if(D('Announce')->save($data)){
			$result['is_err'] = 0;
			$result['result'] = 'is_ok';
		}else{
			$result['is_err'] = 1;
			$result['result'] = '数据库错误，请重试！';
		}
		echo json_encode($result);
		exit;
	}

	//增加
	public function add_announce(){
		//标题输入最长长度
		$max_title_length = 10;


		$data['userid'] = I('session.userid');
		$data['title'] = trim(I('post.title'));
		$data['content'] = trim(I('post.content'));

		$data['creattime'] = time();
		if($data['userid'] && $data['title'] && $data['content'] && $data['creattime']){
			if(strlen($data['title']) > $max_title_length ){
				$result['is_err'] = 1;
				$result['reusult'] = '标题太长，请重试！';
			}
			elseif(D('Announce')->add($data)){
				$result['is_err'] = 0;
				$result['reusult'] = 'is_ok';
			}else{
				$result['is_err'] = 1;
				$result['reusult'] = '数据库错误，请重试！';
			}
		}else{
			$result['is_err'] = 1;
			$result['reusult'] = '所填资料不全，请重试！';
		}

		echo json_encode($result);
		exit;

	}

	//编辑
	public function update_announce(){
		$data['userid'] = I('session.userid');
		$data['title'] = trim(I('post.title'));
		$data['content'] = trim(I('post.content'));
		$data['creattime'] = time();
		if($data['userid'] && $data['title'] && $data['content'] && $data['creattime']){
			if(D('Announce')->save($data)){
				$result['is_err'] = 0;
				$result['reusult'] = 'is_ok';
			}else{
				$result['is_err'] = 1;
				$result['reusult'] = '数据库错误，请重试！';
			}
		}else{
			$result['is_err'] = 1;
			$result['reusult'] = '所填资料不全，请重试！';
		}
		echo json_encode($result);
		exit;
	}

	//删除
	public function del_announce(){
		$id = I('post,id');
		$map['anoceid'] = $id;
		if(D('Announce')->where($map)->delete()){
			$result['is_err'] = 0;
			$result['result'] = 'is_ok';
		}else{
			$result['is_err'] = 1;
			$result['result'] = '数据库错误，请重试！';
		}
		echo json_encode($result);
		exit;
	}

	public function detail(){
		$map['anoceid'] = I('post,id');
		$result = D('Announce')->where($map)->find();
		$result = stand_date($result);
		$response['result'] = $result;
		$response['is_err'] = 0;
		echo json_encode($response);
		exit;
	}

	/**
	 * elements
	 */



}
