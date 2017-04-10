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
		$response['max_page'] = count($result)/10;
		echo json_encode($result);
		exit;
	}


	//日志
	public function user(){
		$p = I('post.page');
        if(!empty($p)){
            $p = 1;
        }
		$result = D('User')->getUser($p);
		$response['is_err'] = 0;
		$response['result'  ] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}

	//user
	public function index(){
		$p = I('post.page');
        if(!empty($p)){
            $p = 1;
        }
		$result = D('User')->getUser($p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
        $response['add_url'] = 'Admin/User/add_user';
        $response['groups_api'] = 'Admin/User/getGroups';
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

    public function add_user(){
        $username = trim(I('post.username'));
        $map['username'] = $username;

        //不知道添加用户会不会有学号
       // $stunum = trim(I('post.stunum'));
        //$map['stunum'] = $stunum;

        if(!empty(D('User')->field('username')->where($map)->find())){
            $response['is_err'] = 1;
            $response['result'] = "账号已存在";
        }else{
            $pass = trim(I('post.password'));
            $confirm_pass = trim(I('post.confirm_password'));
            if(!empty($pass) && $pass == $confirm_pass){
                $data['username'] = $username;
                $data['stunum'] = $stunum;
                $data['realname']   = trim(I('post.realname'));
                $data['birthday'] 	= trim(I('post.birthday'));
                $data['birthplace'] = trim(I('post.birthplace'));
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
                // echo json_encode(array($userid, $groupid, $id));
                // exit;
               
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
