<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 后台首页控制器
 */
class UserController extends AdminBaseController{
	/**
	 * 个人设置
	 */
	public function personal(){
		//post接受
		$data['realname'] 	= I('post.realname');
		$data['birthday'] 	= I('post.birthday');
		$data['birthplace'] = I('post.birthplace');
		$data['email'] 		= I('post.email');
		$data['school']		= I('post.school');
		$data['phone'] 		= I('post.phone');
		$data['username']	= I('post.username');
		$res = arr_clean($data);//更新以及确认是否为空

		//密码
		$old_pass = trim(I('post.old_password'));
		$new_pass = trim(I('post.new_password'));
		$confirm_pass = trim(I('post.confirm_password'));

		//约束
		$map['password'] = md5($old_pass);
		$map['username'] = $data['username'];
		$result = array();
		$response = array();

		//更改密码,密码有东西
		if($old_pass && $new_pass && $confirm_pass){
			if(!(D('User')->where($map)->find())){
				$result['old_password'] = "is false";
			}
			elseif($new_pass != $confirm_pass){
				$result['old_password'] = "is ok";
				$result['confirm'] = "confirm false";
			}else{
				$result['old_password'] = "is ok";
				$result['confirm'] = "is ok";
				$data['password'] = md5($new_pass);
				$response['is_err'] = 1;
				if(D('User')->save($data) && $res){
					$response['is_err'] = 0;
				}
				$response['result'] = $result;
				echo json_encode($response);
				exit;
			}
		}
		else{
			//保存，密码没东西
			$response['is_err'] = 1;
			if(D('User')->save($data) && $res){
				$response['is_err'] = 0;
			}
			echo json_encode($response);
			exit;
		}
	}


	//在线
	public function online(){
		$p = I('post.page');
		$result = D('User')->getOnline($p);

		$response['result'] = $result;
		$response['is_err'] = 0;
		$response['max_page'] = count($result)/10;
		echo json_encode($result);
		exit;
	}


	//日志
	public function user(){
		$p = I('post.page');
		$result = D('User')->getUser($p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}
	//user
	public function index(){
		$p = I('post.page');
		$result = D('User')->getUser($p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}
	//封印用户
	public function ban(){
		$id = I('post.id');
		if(D('User')->ban($id)){
			$response['is_err'] = 0;
			$response['result'] = 'is_ok';
		}else{
			$response['is_err'] = 1;
			$response['result'] = '数据库错误，请重试！';
		}
		echo json_encode($response);
		exit;
	}

	public function school_search(){
		$map['schname'] = trim(I('post.school'));
		$p = I('post.page');
		$result = D('User')->getUser($p, $map);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}

	public function user_search(){
		$map['username'] = trim(I('post.username'));
		$p = I('post.page');
		$result = D('User')->getUser($p, $map);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}


}
