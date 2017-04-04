<?php
/**
 * Created by PhpStorm.
 * User: GuoRenjie
 * Date: 2017/3/21
 * Time: 8:46
 */
namespace Common\Model;
use Think\Model;
class UserCollectionModel extends Model {

    //收藏
    public function love($id){

        $data['userid'] = I('session,userid');
        $data['messageid'] = $id;
        if($this->add($data)){
            return true;
        }else{
            return false;
        }
    }

    public function getData($map = array(), $p = 1){
        @session_start();

        $map['yq_user_collection.userid'] = I('session.userid');
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

    public function del($id){
        $map['messageid'] = $id;
        $map['userid'] = I('session.userid');
        if($this->where($map)->delete()){
            return true;
        }else{
            return false;
        }
    }

}
