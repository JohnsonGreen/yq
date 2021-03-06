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

        $result['result'] = D('Message')->getScore($page,$pagesize,$type);
        $result['max_page'] = $this->getScorePage();
        $result['is_err'] = 0;
        $result['url'] = array(
            'magscore_search' => 'Admin/ManageScore/getScores',
            'magscore_mark' => 'Admin/ManageScore/updateScore',
            'scolist_details' => 'Admin/ManageScore/getScoreDetails'
        );
        echo json_encode($result);
        exit;
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

        $result['result'] = D('Message')->getScore($page,$pagesize,$type);
        $result['max_page'] = $this->getScorePage();
        $result['is_err'] = 0;
        echo json_encode($result);
        exit;
    }

    /**
     * 更新各项分数数据，加分减分
     */
    public function updateScore(){

        $map=I('post.');
        $data=array();
        if(empty($map['messageid']))
             $this->returnJson();

         foreach($map as $k => $val){
             if(!empty($val) && $k != 'messageid') {
                 $data[$k] = intval($val);
                 if($data[$k] < 0)  $data[$k] = 0;
             }
         }

         $message = D('Message');
         $school = D('School');
         $user = D('User');
         $res = $message->where(array('messageid' =>$map['messageid']))->field('schoolid,userid,base,score')->find();

         $data['score'] = intval($res['base']) + $data['add'] - $data['substract'];
         if($data['score'] < 0)  $data['score'] = 0;
         $change = $data['score'] - intval($res['score']);

         $sch = array();
         $usr = array();
         if($data['score'] < 0)  $data['score'] = 0;

         $sch['score'] = $school->getSchoolScore($res['schoolid']);   //学院分调整
         $sch['score'] += $change;
         if($sch['score'] < 0)  $sch['score'] = 0;
         $school->editData(array('schoolid' =>$res['schoolid']),$sch);

         $usr['score'] = $user->getSingleScore($res['userid']);      //报送所属人的分数调整
         $usr['score'] += $change;
         if($usr['score'] < 0)  $usr['score'] = 0;
         $user->editData(array('userid' => $res['userid']),$usr);
         $message->editData(array('messageid' => $map['messageid']),$data);

         echo json_encode(array(
             'is_err' => 0,
             'result' => array('score'=>$data['score'])
         ));
         exit;

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
        $pages =  ceil($count/10);
        $data['pages'] = $pages;
        $this->returnJson($data);

    }

    private function getScorePage(){
        $map = I('post.');
        $type = array();
        if (!empty($map['schoolid']))
            $type['schoolid'] = $map['schoolid'];
        if (!empty($map['typeid']))
            $type['typeid'] = $map['typeid'];
        $count = D('Message')->getMessageCount($type);
        return ceil($count/10);
    }

    /**
     * 返回积分列表中学院的页数
     * @return int
     */
    public function getSchoolPages(){
        return  ceil((D('School')->getSchoolCount())/10);
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

        $result['is_err'] = 0;
        $result['result'] = D('School')->getSchoolScoreList($map['page'],$map['pagesize']);
        $result['max_page'] = $this->getSchoolPages();
        $result['url'] = array(
            'scolist_schdetail' => 'Admin/ManageScore/getScoreDetails'
        );
       echo json_encode($result);
       exit;
    }

    public function getSchoolScoreList_teacher(){
        $map = I('post.');
        if(empty($map['page']))
            $map['page']=1;
        if(empty($map['pagesize']))
            $map['pagesize']=10;

        $result['is_err'] = 0;
        $result['result'] = D('School')->getSchoolScoreList($map['page'],$map['pagesize']);
        $result['max_page'] = $this->getSchoolPages();
        $result['url'] = array(
            'scolist_schdetail' => 'Admin/ManageScore/getScoreDetails_teacher'
        );
        echo json_encode($result);
        exit;
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
        $res['max_page'] = $this->getScorePage();
        echo json_encode($res);
        exit;
    }


    public function getScoreDetails(){
        $p = I('post.page');

       // echo json_encode(I('post.'));
       // exit;
        if(empty($p))
            $p = 1;
        $schoolid = trim(I('post.schoolid'));
        $result = D('Message')->getDetail($schoolid, $p);
        $cnt = count($result);
        for($i = 0; $i < $cnt;$i++) {
                $result[$i]['flag'] = 1;
        }
        $response['is_err'] = 0;
        $response['result'] = $result;
        $response['url'] = array(
             'scolist_details' => 'Admin/ManageScore/allDetail',
             'scolist_del'=>'Admin/ManageScore/delDetail'
        );

        $response['max_page'] = ceil($this->getAllDetailsPages($schoolid)/10);
        echo json_encode($response);
        exit;
    }


    public function getScoreDetails_teacher(){
        $p = I('post.page');

        // echo json_encode(I('post.'));
        // exit;
        if(empty($p))
            $p = 1;
        $schoolid = trim(I('post.schoolid'));
        $result = D('Message')->getDetail($schoolid, $p);
        $cnt = count($result);
        for($i = 0; $i < $cnt;$i++) {
            if($result[$i]['userid'] == I('session.user')['userid'])
                $result[$i]['flag'] = 1;
            else
                $result[$i]['flag'] = 0;
        }
        $response['is_err'] = 0;
        $response['result'] = $result;
        $response['url'] = array(
            'scolist_details' => 'Admin/ManageScore/allDetail',
            'scolist_del'=>'Admin/ManageScore/delDetail_teacher'
        );

        $response['max_page'] = ceil($this->getAllDetailsPages($schoolid)/10);
        echo json_encode($response);
        exit;
    }

    private function getAllDetailsPages($schoolid){
        $map['is_delete'] = 0;
        $map['schoolid'] = $schoolid;
        $res = D('Message')->where($map)->count('*');
       return $res;
    }


    //返回点击标题出现的详情页
    public function allDetail(){
        $id = I('post.messageid');
        $response['is_err'] = 0;
        $response['result'] = D('Message')->getMessageDetails($id);
        echo json_encode($response);
        exit;
    }

    public function delDetail(){
        $id = I('post.messageid');
        if(D('Message')->del($id)){
            $response['is_err'] = 0;
            $response['result'] = '删除成功！';
        }else{
            $response['is_err'] = 0;
            $response['result'] = '删除失败！';
        }
        echo json_encode($response);
        exit;
    }

    public function delDetail_teacher(){
        $id = I('post.messageid');
        $ids = D('Message')->where(array('userid'=>I('session.user')['userid']))->field('messageid')->select();
        $flag = false;
        foreach($ids as $item) {
            if($id == $item['messageid']){
                $flag = true;
            }
        }
        if($flag) {
            if(D('Message')->del($id)){
                $response['is_err'] = 0;
                $response['result'] = '删除成功！';
            }else{
                $response['is_err'] = 1;
                $response['result'] = '删除失败！';
            }
        }else {
                $response['is_err'] = 1;
                $response['result'] = '只能删除自己的条目！';
        }

        echo json_encode($response);
        exit;
    }



}