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
		if(!empty($p)){
		    $p = 1;
        }
		$result = D('User')->getOnline($p);
		$response['result'] = $result;
		$response['is_err'] = 0;
		$response['max_page'] = D('User')->getOnlinePage();
		$response['url'] = array(
			'online_user'=>'Admin/User/online'
		);
		echo json_encode($response);
		exit;
	}


	//日志
	public function log(){
		$p = I('post.page');
		$result = D('User')->getUser($p, null);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = D('User')->getLogPage();
		$response['url'] = array(
			'log_user'=>'Admin/User/index'
		);
		echo json_encode($response);
		exit;
	}

	//user
	public function index(){
		$p = I('post.page');

		$result = D('User')->getUser($p, null);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = D('User')->getLogPage();
        $response['add_url'] = 'Admin/User/add_user';
        $response['groups_api'] = 'Admin/User/getGroups';
		$response['url'] = array(
			'maguser'=>'Admin/User/index',
			'user_search'=>'Admin/User/search',
			'user_ban'=>'Admin/User/ban',
			'groups_api'=>'Admin/User/getGroups',
            'maguser_add'=>'Admin/User/add_user'
		);
		echo json_encode($response);
		exit;
	}
	//封印用户
	public function ban(){
		$id = I('post.id');
		if(D('User')->ban($id)){
			$response['is_err'] = 0;
			$p = I('post.page');

			$result = D('User')->getUser($p, null);
			$response['is_err'] = 0;
			$response['result'] = $result;
			$response['max_page'] = D('User')->getLogPage();
			$response['add_url'] = 'Admin/User/add_user';
			$response['groups_api'] = 'Admin/User/getGroups';
			$response['url'] = array(
				'maguser'=>'Admin/User/index',
				'user_search'=>'Admin/User/search',
				'user_ban'=>'Admin/User/ban',
				'groups_api'=>'Admin/User/getGroups',
                'maguser_add'=>'Admin/User/add_user'
			);
		}else{
			$response['is_err'] = 1;
			$response['result'] = '数据库错误，请重试！';
		}
		echo json_encode($response);
		exit;
	}

	public function search(){
		$school = trim(I('post.school'));
		$username = trim(I('post.username'));
		if($school && $school != "全部")
			$map['schname'] = $school;
		if($username)
			$map['username'] = $username;

		$p = I('post.page');
		if(empty($p))
			$p = 1;
		$result = D('User')->getUser($p, $map);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = D('User')->getLogPage($map);
		echo json_encode($response);
		exit;
	}


    public function add_user(){
        $username = trim(I('post.username'));
        $map['username'] = $username;

        if(!empty(D('User')->field('username')->where($map)->find())){
            $response['is_err'] = 1;
            $response['result'] = "账号已存在";
        }else{
            $pass = trim(I('post.password'));
            $confirm_pass = trim(I('post.confirm_password'));
            if(!empty($pass) && $pass == $confirm_pass){
                $data['username'] = $username;
                $data['realname']   = trim(I('post.realname'));
                $data['email'] 		= trim(I('post.email'));
                $data['schoolid']   = trim(I('post.schoolid'));
                $data['phone'] 		= trim(I('post.phone'));
                $data['score'] 		= 0;
                $data['ban']		= 0;
                $data['createtime'] = time();
                $data['lastip'] 	= 0;
                $data['password']   = md5($pass);
                $data['lastlogintime'] = 0;
                $data['updatetime']    = 0;
                $userid = D('User')->add($data);
                $groupid = trim(I('post.groupid'));
                
                $id = D('UserGroup')->addGroupUser($userid,$groupid);

               
                if(!empty($id)){
                    $response['is_err'] = 0;
                    $response['result'] = "添加用户成功！";
                }else{
                    $response['is_err'] = 1;
                    $response['result'] = "数据库错误，请重试！";
                }
            }else{
                $response['is_err'] = 1;
                $response['result'] = "密码验证错误！";
            }
        }

        echo json_encode($response);
        exit;
    }

    public function getGroups(){
       echo json_encode(array(
           "is_err" => 0,
           "result"=> D('Group')->getGroups()
       ));
       exit;
    }

   
}
