<?php
/**
 * Created by PhpStorm.
 * User: GuoRenjie
 * Date: 2017/3/21
 * Time: 8:46
 */
namespace Common\Model;

class UserCollectionModel extends BaseModel{

    //收藏
    public function love($id){
        @session_start();
        $data['userid'] = $_SESSION['user']['userid'];
        $data['messageid'] = $id;
        if($this->add($data)){
            return true;
        }else{
            return false;
        }
    }

    public function getData($map = array(), $p = 1){
        @session_start();
        $map['yq_user_collection.userid'] = $_SESSION['user']['userid'];
//        $map['yq_user_collection.userid'] = 1;
        $result = $this
            ->join('left join yq_message a on yq_user_collection.messageid = a.messageid')
            ->join('yq_type on a.typeid = yq_type.typeid')
            ->join('yq_school on a.schoolid = yq_school.schoolid')
            ->field('a.messageid, a.userid, schname, a.score, a.title, a.content, a.createtime, a.click, type')
            ->order('id desc')
            ->where($map)
            ->page($p.', 10')
            ->select();

        $result = stand_date($result);
        return $result;
    }

    public function getDataPage($map = array()){
        @session_start();
        $map['yq_user_collection.userid'] = $_SESSION['user']['userid'];
//        $map['yq_user_collection.userid'] = 1;
        $result = $this
            ->join('left join yq_message a on yq_user_collection.messageid = a.messageid')
            ->join('yq_type on a.typeid = yq_type.typeid')
            ->join('yq_school on a.schoolid = yq_school.schoolid')
            ->field('a.messageid, a.userid, schname, a.score, a.title, a.content, a.createtime, a.click, type')
            ->order('id desc')
            ->where($map)
            ->select();

        return max_page($result);
    }

    public function del($id){
        @session_start();
        $map['messageid'] = $id;
        $map['userid'] = $_SESSION['user']['userid'];
        if($this->where($map)->delete()){
            return true;
        }else{
            return false;
        }
    }

    public function delAllByid($id){
        $map['messageid'] = $id;
        if($this->where($map)->delete()){
            return true;
        }else{
            return false;
        }
    }

    public function getMaxPage($map = null){
        return max_page($this->where($map)->select());
    }

    public function getUserCollection(){
        $res = $this->where(array('userid'=>I('session.user')['userid']))->field('messageid')->select();
        $coll = array();
        foreach ($res as $item){
            array_push($coll,$item['messageid']);
        }
        return $coll;
    }

}
