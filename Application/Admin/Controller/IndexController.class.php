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

        @session_start();
        //获取组的id以及对应权限
        $group_id = $_SESSION['user']['groupid'];
        $user_id = $_SESSION['user']['userid'];

        if($group_id == 1){
            $map['userid'] = $user_id;
            $message = D('Message')->where($map)->order('createtime desc')->select();
        }
        else{
            $message = D('Message')->order('createtime desc')->limit(10)->select();
        }
        $announcement = D('Announce')->order('createtime desc')->order('stick')->limit(10)->select();
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
     * 修改个人信息，个人设置
     */
    public function update(){
        @session_start();
        $data = I('post.');
        $us = D('User');
        if(!empty($data['originpassword'])){
            if($_SESSION['user']['groupid'] != '3'){
                echo json_encode(array(
                    'is_err' => '1',
                    'result' => '没有权限删除密码！'
                ));
                exit;
            }
           $user = $us->findByUserId($data['userid']);
           if(!empty($user) && $user['password'] == md5(trim($data['originpassword']))){
               $data['password'] = md5($data['password']);
           }else{
              echo json_encode(array(
                  'is_err' => '1',
                  'result' => '原始密码不正确，请重试！'
              ));
              exit;
           }
            $us->editData(array('userid'=>$data['userid']),$data);
        }else{
            $dta['realname'] = $data['realname'];
            $dta['email'] = $data['email'];
            $dta['phone'] = $data['phone'];
            if($_SESSION['user']['groupid'] == '3'){                      //管理员
                $us->editData(array('userid'=>$data['userid']),$dta);
            }else{
                $us->editData(array('userid'=>$_SESSION['userid']),$dta);
            }
        }
        echo json_encode(array(
            'is_err' => '0',
            'result' => '修改信息成功'
        ));
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

    public function add_file(){
        $id = I('get.id');
        $table = I('get.ispub');
        $data = array();

        if($table == 1){
            $link = D('Announce');
            $data['anoceid'] = $id;
        }
        else{
            $link = D('Message');
            $data['messageid'] = $id;
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('pdf', 'txt', 'doc', 'jpeg', 'docx', 'png', 'jpg','xls','xlsx');// 设置附件上传类型
        $upload->rootPath  =     'Public/Upload/'; // 设置附件上传根目录
        $upload->savePath  =     'Files/'; // 设置附件上传（子）目录
        // 上传文件

        $info   =   $upload->upload();


        $imgPath = "";
        if(!$info) {// 上传错误提示错误信息
            $response['is_err'] = 1;
            $response['result'] = $upload->getError();
        }else{// 上传成功
            $imgPath = $upload->rootPath.$info['file']['savepath'].$info['file']['savename'];
            $data['file'] = $imgPath;
            if($link->save($data)){
                $response['is_err'] = 0;
                $response['result'] = "文件上传成功";
            }
        }

        echo json_encode($response);
        exit;
    }
    

}
