<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 后台首页控制器
 */
class IndexController extends AdminBaseController{
    /**
     * 首页
     */
    public function index(){
       // echo ceil(20/10);
        @session_start();
        //获取组的id以及对应权限
        $group_id = $_SESSION['user']['groupid'];
        $user_id = $_SESSION['user']['userid'];


//		$group_id = 3;
//		$user_id = 3;

        if($group_id == 1){
            $map['userid'] = $user_id;
            $message = D('Message')->where($map)->order('createtime desc')->select();
        }
        else{
            $message = D('Message')->order('createtime desc')->select();
        }
        $announcement = D('Announce')->order('createtime desc')->order('stick')->select();
        $score = D('School')->order('score desc')->limit(3)->select();

        $message = stand_date($message);
        $announcement = stand_date($announcement);

        $result['announcement'] = $announcement;
        $result['message'] = $message;
        $result['score_three'] = $score;
//		cout($result);


        $res['is_err'] = 1;
        if($result)
            $res['is_err'] = 0;
        $res['result'] = $result;
        echo json_encode($res);
        exit;
    }
    /**
     * elements
     */
    public function login(){
        @session_start();
        $map['username'] = trim(I('post.username'));
        $map['password'] = md5(trim(I('post.password')));

//		$map['username'] = "admin";
//		$map['password'] = "123456";

        $data = $map;
        $res = D('User')->where($map)->find();
        $result['is_err'] = 0;

        if($res){
            $result['is_err'] = 0;
            //更新用户登录信息
            $data['lastlogintime'] = time();
            $data['lastip'] = getIP();
            $session_id = session_id();
            $data['session_id'] = $session_id;
            D('User')->save($data);

            //获取数据
            $group_id = D('User_group')->where(array('userid'=>$res['userid']))->getField('groupid');

            $session_user = array(
                'user_id' 	=> $res['userid'],
                'user_name' => $res['username'],
                'group_id' 	=> $group_id
            );

            session($session_user);

            $result['result'] = $res;
        }
        else{
            $result['is_err'] = 1;
            $result['result'] = "用户未找到!";
        }
        $result = json_encode($result);
        echo $result;
        exit;
    }
    //退出
    public function logout(){
        session(null);
        $result = array('is_err' => 0, 'result'=>'退出成功！');
        echo json_encode($result);
        exit;
//        $this->success('退出成功、前往登录页面',U('Home/Index/index'));
    }


    /**
     * 以下是一部分接口
     */
    //权限元素接口
    public function elements(){
        @session_start();
        $group_id =  $_SESSION['user']['groupid'];
//		$group_id = 1;
        $right = D('Group_permission')->join('yq_permission on yq_group_permission.permid = yq_permission.permid')->where(array('groupid' => $group_id))->field('yq_group_permission.permname, action')->select();

        session('right',$right);
        echo json_encode($right);
        exit;
    }
    //学院列表
    public function school(){
        $result = D('School')->select();
        echo json_encode($result);
        exit;
    }
    //类别列表
    public function type(){
        $result = D('Type')->select();
        echo json_encode($result);
        exit;
    }

}
