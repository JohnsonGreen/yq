<?php
/**
 * User: cyh
 * Date: 2017/3/27
 * Time: 14:24
 * Description:
 */

namespace Common\Model;


class UserModel extends BaseModel{
    /**
     *  根据报送userid返回score
     * @param $messageid
     * @return mixed
     */
   public function getSingleScore($userid){
       $res = $this->field('score')->where(array(
           'userid' => $userid
       ))->find();
       return $res['score'];
   }

    //日志
    public function getUser($page = 1, $map = null){
        $map['ban'] = 0;
        if(empty($page))
            $page = 1;
        $result = $this
            ->join('LEFT JOIN yq_school on yq_user.schoolid = yq_school.schoolid')
            ->join('LEFT JOIN yq_user_group a on yq_user.userid = a.userid')
            ->join('LEFT JOIN yq_group on a.groupid = yq_group.groupid')
            ->field('yq_user.userid, yq_user.username, lastip, lastlogintime, schname, groupname, realname')
            ->where($map)
            ->page($page.', 10')
            ->order('lastlogintime desc')
            ->select();
        $result = stand_date($result);
        return $result;
    }

    public function getLogPage($map = null){
        $result = $this
            ->join('LEFT JOIN yq_school on yq_user.schoolid = yq_school.schoolid')
            ->join('LEFT JOIN yq_user_group a on yq_user.userid = a.userid')
            ->join('LEFT JOIN yq_group on a.groupid = yq_group.groupid')
            ->field('yq_user.userid, yq_user.username, lastip, lastlogintime, schname, groupname, realname')
            ->where($map)
            ->select();
        foreach($result as $i => $item){
            $result[$i]['lastlogintime'] = date('Y-m-d H:i:s', $result[$i]['lastlogintime']);

        }
        return max_page($result);
    }

    public function ban($id)
    {
        $map['userid'] = $id;
        $map['ban'] = 1;
        return $this->save($map);

    }

    public function getOnline($p){

        $temp_time = time()-10*60;
        $map['updatetime'] = array('gt', $temp_time);

        $result = $this
            -> join('LEFT JOIN yq_school on yq_user.schoolid = yq_school.schoolid')
            -> join('LEFT JOIN yq_user_group a on yq_user.userid = a.userid')
            -> join('LEFT JOIN yq_group on a.groupid = yq_group.groupid')
            -> where($map)
            -> field('yq_user.userid, yq_user.username, lastip, lastlogintime, schname, groupname, realname')
            -> page($p.', 10')
            -> select();

        $result = stand_date($result);
        return $result;
    }

    public function getOnlinePage()
    {
        $temp_time = time()-10*60;
        $map['updatetime'] = array('gt', $temp_time);

        $result = $this
            -> join('LEFT JOIN yq_school on yq_user.schoolid = yq_school.schoolid')
            -> join('LEFT JOIN yq_user_group a on yq_user.userid = a.userid')
            -> join('LEFT JOIN yq_group on a.groupid = yq_group.groupid')
            -> where($map)
            -> field('yq_user.userid, yq_user.username, lastip, lastlogintime, schname, groupname, realname')
            -> select();
        return max_page($result);
    }

    public function findByUserId($userid){
        $data['ban'] = 0;
        $data['userid'] = $userid;
        return $this->where($data)->find();
    }

    //
    public function findInfo($userid){
        $data['ban'] = 0;
        $data['userid'] = $userid;
        return $this->join('yq_school ON yq_school.schoolid = yq_user.schoolid')->field('schname,yq_user.score,userid,username,email,phone,realname')->where($data)->find();
    }


    public function getMaxPage($map = null){
        return max_page($this->where($map)->select());
    }



}