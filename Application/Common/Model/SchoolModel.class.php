<?php
/**
 * User: cyh
 * Date: 2017/3/24
 * Time: 0:33
 * Description: 学院数据操作
 */

namespace Common\Model;


class SchoolModel extends BaseModel{
    /**
     * 根据页数和页面数量返回积分列表
     * @param int $page
     * @param int $pagesize
     * @return mixed
     */
    public function getSchoolScoreList($page=1,$pagesize=10){
        return  $this->field('schoolid,schname,score')
                     ->order('score desc')
                     ->page($page,$pagesize)->select();
    }
    /**
     * 返回学院总数
     */
    public function getSchoolCount(){
        $res = $this->query('SELECT COUNT(*) AS count FROM __SCHOOL__ ');
        return $res[0]['count'];
    }

    /**
     * 返回单个学院的总积分
     * @param $schoolid
     * @return mixed
     */
    public function getSchoolScore($schoolid){
        $res = $this->field('score')->where(array(
            'schoolid' => $schoolid
        ))->find();
        return $res['score'];
    }

    public function getSchoolNames(){
        return  $this->field('schoolid,schname')->select();
    }
    public function getMaxPage($map = null){
        return max_page($this->where($map)->select());
    }


}