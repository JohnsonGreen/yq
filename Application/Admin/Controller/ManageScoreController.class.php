<?php
/**
 * User: cyh
 * Date: 2017/3/24
 * Time: 0:45
 * Description: 积分列表管理
 */

namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');


class ManageScoreController extends AdminBaseController{

    /**
     * 默认全部学院和全部类别的积分
     */
    public function index(){
        $this->getScores();
    }

    /**
     * 带有学院参数和类别参数的积分管理
     */
    public function getScores(){

        $map = I('post.');
        $schoolid = $map['schoolid'];
        $typeid = $map['typeid'];
        $page = $map['page'];
        $pagesize = $map['pagesize'];

        $type = array();
        if(!empty($schoolid) && $schoolid != '0')
            $type['schoolid'] = $schoolid;
        if(!empty($typeid) && $typeid != '0')
            $type['typeid'] = $typeid;
        if(empty($page))
            $page=1;
        if(empty($pagesize))
            $pagesize=10;

        $this->returnJson(
            D('Message')->getScore($page,$pagesize,$type)
        );
    }

    /**
     * 更新各项分数数据，加分减分
     */
    public function updateScore(){

        $map=I('post.');
        $data=array();
        if(empty($map['messageid']))
             $this->returnJson();

        $data['score'] = 0;
         foreach($map as $k => $val){
             if(!empty($val) && $k != 'messageid') {
                 $data[$k] = intval($val);
                 if($data[$k] < 0)  $data[$k] = 0;
                 $data['score'] += $data[$k];
             }
         }

//         if(!empty($map['base'])){
//             $data['base'] = $map['base'];
//             $data['score'] += intval($map['base']);
//         }
//         if(!empty($map['select'])){
//             $data['select'] = $map['select'];
//             $data['score'] += intval($map['select']);
//         }
//         if(!empty($map['approval'])){
//             $data['approval'] = $map['approval'];
//             $data['score'] += intval($map['approval']);
//         }
//         if(!empty($map['warning'])){
//             $data['warning'] = $map['warning'];
//             $data['score'] += intval($map['warning']);
//         }
//         if(!empty($map['quality'])){
//             $data['quality'] = $map['quality'];
//             $data['score'] += intval($map['quality']);
//         }
//         if(!empty($map['special'])){
//             $data['special'] = $map['special'];
//             $data['score'] += intval($map['special']);
//         }
//         if(!empty($map['substract'])){
//             $data['substract'] = $map['substract'];
//             $data['score'] += intval($map['substract']);
//         }
//         if(!empty($map['add'])){
//             $data['add'] = $map['add'];
//             $data['score'] += intval($map['add']);
//         }

         $message = D('Message');
         $school = D('School');
         $user = D('User');
         $score = $message->getSingleScore($map['messageid']);
         $schoolid = $message->getSingleSchoolId($map['messageid']);
         $userid = $message->getSingleUserid($map['messageid']);

         $sch = array();
         $usr = array();
         if($data['score'] < 0)  $data['score'] = 0;

         $sch['score'] = $school->getSchoolScore($schoolid);   //学院分调整
         $sch['score'] += $data['score'] - intval($score);
         if($sch['score'] < 0)  $sch['score'] = 0;
         $school->editData(array('schoolid' => $schoolid),$sch);


         $usr['score'] = $user->getSingleScore($userid);      //报送所属人的分数调整
         var_dump($usr['score']);
         $usr['score'] += $data['score'] - intval($score);
         if($usr['score'] < 0)  $usr['score'] = 0;
         $user->editData(array('userid' => $userid),$usr);

         var_dump($usr);
         $this->returnJson(
             $message->editData(array(
                 'messageid' => $map['messageid']
             ),$data)
         );

    }

    /**
     * 删除一条报送，返回提示信息
     */
    public function deleteMessage(){
        $map['messageid'] = I('post.messageid');
        $data['is_delete'] = 1;
        $this->returnJson(
            D('Message')->editData($map,$data)
        );
    }

    /**
     * 返回管理积分页的总页数
     */
    public function getMessagePages(){
        $map = I('post.');
        $type = array();
        if (!empty($map['schoolid']))
            $type['schoolid'] = $map['schoolid'];
        if (!empty($map['typeid']))
            $type['typeid'] = $map['typeid'];
        $count = D('Message')->getMessageCount($type);
        $pages = 0;
        if(!empty($count)){
            $pages += intval($count/10) + 1;          //10个一页
        }
        $data['pages'] = $pages;
        $this->returnJson($data);
    }

    /**
     * 返回积分列表中学院的页数
     * @return int
     */
    public function getSchoolPages(){
        $data['pages'] = intval((D('School')->getSchoolCount())/10) + 1;
        $this->returnJson($data);
    }

    /**
     * 返回所有的学院名及对应的schoolid
     */
    public function getSchoolNames(){
        $data['schoolid'] = '0';
        $data['schname'] = '全部';
        $res = D('School')->getSchoolNames();
        array_unshift($res,$data);
        $this->returnJson($res);
    }

    /**
     * 返回所有的类型名及对应的typeid
     */
    public function getTypes(){
        $data['typeid'] = '0';
        $data['type'] = '全部';
        $res =D('Type')->getTypes();
        array_unshift($res,$data);
        $this->returnJson($res);
    }

    /**
     * 返回积分列表
     */
    public function getSchoolScoreList(){
        $map = I('post.');
        if(empty($map['page']))
            $map['page']=1;
        if(empty($map['pagesize']))
            $map['pagesize']=10;
        $this->returnJson(D('School')->getSchoolScoreList($map['page'],$map['pagesize']));
    }

    /**
     * 返回json数据
     * @param $data
     */
    private function returnJson($data,$info='操作失败，请重试'){
        $res = '';
        if(empty($data)){
            $res['is_err'] = '1';
            $res['result'] = $info;
        }else{
            $res['is_err'] = '0';
            $res['result'] = $data;
        }
        echo json_encode($res);
        exit;
    }




}