<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 后台首页控制器
 */
class ReplyController extends AdminBaseController{

	public function index(){
		$map['messageid'] = trim(I('post.id'));
		$result = D('Reply')->where($map)->select();
		$response['result'] = $result;
		$response['max_page'] = max_page($result);
		$response['is_err'] = 1;
		echo json_encode($response);
		exit;
	}
	public function add(){
		$data['userid'] = I('session.user_id');
		$data['reply_content'] = trim(I('post.content'));
		$data['reply_messageid'] = trim(I('post.id'));
		$insert_id = D('Reply')->add($data);
		$response = array();
		if($insert_id){
			$response['is_err'] = 0;
			$response['result'] = 'is_ok';
		}else{
			$response['is_err'] = 1;
			$response['result'] = '数据库错误，请重试！';
		}
		echo json_encode($response);
		exit;
	}
	public function del(){
		$map['replyid'] = trim(I('post.id'));
		if(D('Reply')->where($map)->delete()){
			$response['is_err'] = 0;
			$response['result'] = 'is_ok';
		}else{
			$response['is_err'] = 1;
			$response['result'] = '数据库错误，请重试！';
		}
		echo json_encode($response);
		exit;
	}
}
