<?php
/**
 * User: cyh
 * Date: 2017/3/23
 * Time: 22:24
 * Description: 报送信息处理
 */

namespace Common\Model;
use Common\Model\BaseModel;

class MessageModel extends BaseModel{

    /**
     *  根据学院和类型、页数和页面数量返回积分
     * @param int $page
     * @param int $pagesize
     * @param $type
     * @return mixed
     */
    public function getScore($page=0,$pagesize=5,$type){

        $where = 'a.schoolid = b.schoolid && a.is_delete = 0 ';
        if(!empty($type)){
            if(!empty($type['schoolid']))
                $where .= ' && a.schoolid = '.$type['schoolid'];
            if(!empty($type['typeid']))
                $where .= ' && a.typeid = '.$type['typeid'];
        }
        return  $this->table('__MESSAGE__  a')
                     ->join('__SCHOOL__  b')
                     ->field('a.messageid,b.schname,a.title,a.score,a.base,a.select,a.approval,a.warning,a.quality,a.special,a.substract,a.add')
                     ->where($where)
                     ->order('messageid desc')
                     ->page($page,$pagesize)->select();
    }

    /**
     *  根据学院和类型返回报送总条数
     * @param $type
     * @return mixed
     */
    public function getMessageCount($type){

        $where = ' WHERE is_delete = 0 ';
        if(!empty($type)){
            if(!empty($type['schoolid'])){
                $where .= ' and schoolid = '.$type['schoolid'];
            }
            if(!empty($type['typeid'])){
                $where .= ' and typeid = '.$type['typeid'];
            }

        }
        $res = $this->query('SELECT COUNT(*)  count FROM __MESSAGE__ '.$where);
        return $res[0]['count'];
    }

    /**
     * 根据报送id返回总分
     * @param $messageid
     * @return mixed
     */
    public function getSingleScore($messageid){
        $res = $this->field('score')->where(array(
            'messageid' => $messageid
        ))->find();
        return $res['score'];
    }

    /**
     * 根据报送id返回基础分
     * @param $messageid
     * @return mixed
     */
    public function getSingleBase($messageid){
        $res = $this->field('base')->where(array(
            'messageid' => $messageid
        ))->find();
        return $res['base'];
    }



    /**
     *  根据报送id返回学院id
     * @param $messageid
     * @return mixed
     */
    public function getSingleSchoolId($messageid){
         $res = $this->field('schoolid')->where(array(
            'messageid' => $messageid
        ))->find();
        return $res['schoolid'];
    }

    /**
     *  根据报送id返回用户id
     * @param $messageid
     * @return mixed
     */
    public function getSingleUserid($messageid){
        $res = $this->field('userid')->where(array(
            'messageid' => $messageid
        ))->find();
        return $res['userid'];
    }


    //获取数据
    public function getData($map, $page = 1){
        $map['is_delete'] = 0;
        if(!$page)
            $page = 1;
        $message = $this
            ->join('LEFT JOIN yq_school on yq_message.schoolid = yq_school.schoolid')
            ->join('LEFT JOIN yq_type on yq_message.typeid = yq_type.typeid')
            ->join('LEFT JOIN yq_product on yq_message.product = yq_product.proid')
            ->where($map)
            ->field('yq_product.proid ,yq_product.proname,messageid, file, userid, schname,yq_message.add,yq_message.substract,yq_message.score, title, content, createtime, click, type')
            ->order('createtime desc')
            ->page($page.', 10')
            ->select();
        $message = stand_date($message);
//        cout($message);

        return $message;
    }
    //软删除舆情
    public function del($id,$userid=null){
        $data['messageid'] = $id;
        if(!empty($userid)){
            $res = $this->where($data)->field('userid')->find();
            if($res['userid'] != $userid){
                return false;
            }
        }
        $data['is_delete'] = 1;
        D('UserCollection')->delAllByid($id);   //删除所有收藏
        if($this->save($data)){
            return true;
        }else{
            return false;
        }
    }

    public function getUidByMessageid($messageid){
        $res = $this->where(array('messageid'=>$messageid))->field('userid')->find();
        return $res['userid']; 
    }

    public function getMessage($id){
        $map['messageid'] = $id;
        $message = $this
            ->join('LEFT JOIN yq_school on yq_message.schoolid = yq_school.schoolid')
            ->join('LEFT JOIN yq_type on yq_message.typeid = yq_type.typeid')
            ->join('LEFT JOIN yq_user on yq_message.userid = yq_user.userid')
            ->where($map)
            ->field('yq_message.messageid,yq_message.keyword,url,base,substract,add,yq_message.userid, schname, yq_message.score, yq_message.title, yq_message.content, yq_message.createtime, click, type, username, file')
            ->select();

        //请不要再把select改成find了，OK?
//        cout($message);

            $message = stand_date($message);
        return $message;
    }

    public function getMessageDetails($id){
        $map['messageid'] = $id;
        $message = $this
            ->join('LEFT JOIN yq_school on yq_message.schoolid = yq_school.schoolid')
            ->join('LEFT JOIN yq_type on yq_message.typeid = yq_type.typeid')
            ->join('LEFT JOIN yq_user on yq_message.userid = yq_user.userid')
            ->where($map)
            ->field('yq_message.messageid,yq_message.keyword,url,base,select,approval,warning,quality,special,substract,add,yq_message.userid, schname, yq_message.score, yq_message.title, yq_message.content, yq_message.createtime, click, type, username')
            ->find();

        //请不要再把select改成find了，OK?
//        cout($message);

        $message['createtime'] = date('Y-m-d H:i:s', $message['createtime']);;

        return $message;
    }


    public function getDetail($schoolid, $p = 1){
        $map['yq_message.schoolid'] = $schoolid;
//        $map['yq_message.schoolid'] = 1;
        $map['yq_message.is_delete'] = 0;
        $message = $this
            ->join('LEFT JOIN yq_user on yq_message.userid = yq_user.userid')
            ->join('LEFT JOIN yq_school on yq_message.schoolid = yq_school.schoolid')
            ->join('LEFT JOIN yq_type on yq_message.typeid = yq_type.typeid')
            ->join('LEFT JOIN yq_product on yq_message.product = yq_product.proid')
            ->where($map)
            ->field('yq_product.proid ,yq_product.proname,yq_message.messageid,yq_message.substract,yq_message.add, yq_message.userid, yq_user.username,schname, yq_message.score, title, content, yq_message.createtime, click, type')
            ->order('createtime desc')
            ->page($p.', 10')
            ->select();
        $message = stand_date($message);
        return $message;
    }

    public function getMessagePage($map = null){

        $map['is_delete'] = 0;
        $cnt = $this
            ->join('LEFT JOIN yq_school on yq_message.schoolid = yq_school.schoolid')
            ->join('LEFT JOIN yq_type on yq_message.typeid = yq_type.typeid')
            ->join('LEFT JOIN yq_product on yq_message.product = yq_product.proid')
            ->where($map)
            ->count('*');
        $pages = ceil($cnt/10);
        if($pages == 0)
            $pages = 1;
        return $pages;
    }




}