<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
header('content-type:application/json;charset=utf8');
class IndexController extends HomeBaseController{


    public function index(){
        $this->display();
    }

    /**
     * 登录
     */
    public function login(){
        @session_start();
        if(IS_POST){
            // 做一个简单的登录 组合where数组条件
            $map=I('post.');
            // dump($map);
            // exit();
           // $map['password']=md5($map['password']);
            $data=M('User')->where($map)->find();
            if (empty($data)) {
                echo json_encode(array(
                    'is_err' => '1',
                    'result' => '账号或密码错误'
                ));
                exit;
            }else{
                $sch = M('School');
                $school = $sch->field('schname')->where('schoolid='.$data['schoolid'])->find();
                $rank =  $sch->order('score desc')->limit(3)->select();    //首页学院分数排名
                $group = M()->table('__USER_GROUP__ a')->field('b.groupid,b.groupname')->join('__GROUP__ b')->where('a.userid='.$data['userid'].'&&a.groupid=b.groupid')->find();
                $leftBar = D('GroupLeftbarPermission')->getLeftBar($group['groupid']);
                $root = 'index.php/';
                $logout = 'Home/Index/logout';

                $_SESSION['user']=array(
                    'userid'=>$data['userid'],
                    'username'=>$data['username'],
                    'group' => $group['groupname'],
                    'groupid' => $group['groupid'],
                    'realname'=>$data['realname'],
                    'email'=>$data['email'],
                    'phone'=>$data['phone'],
                    'school'=>$school['schname'],
                    'score'=>$data['score'],
                    'lastip'=>$data['lastip'],
                    'ip'=>get_client_ip(1),
                    'currentLoginTime'=> time(),
                    'lastlogintime' => date("Y-m-d H:i:s", $data['lastlogintime']),
                    'root'=>$root,
                    'logout'=>$logout,
                    'leftBar'=>$leftBar,
                    'rank'=>$rank
                );

                echo json_encode(array(
                    'is_err' => '0',
                    'result' => $_SESSION['user']
                ));
                exit;
            }
        }else{
            if(check_login()){
                echo json_encode(array(
                    'is_err' => '0',
                    'result' => $_SESSION['user']
                ));
                exit;
            }else{
                echo json_encode(array(
                    'is_err' => '1',
                    'result' => '非法参数'
                ));
                exit;
            }
        }
    }

    /**
     * 退出
     */
    public function logout(){
        @session_start();
        $map['userid'] = $_SESSION['user']['userid'];
        $data['lastip'] = $_SESSION['user']['ip'];
        $data['lastlogintime'] = $_SESSION['user']['currentLoginTime'];
        D('User')->editData($map,$data);
        session('user',null);
        echo json_encode(array(
           "is_err" => '0',
            "result" => '退出成功'
        ));
        exit;
    }


}

