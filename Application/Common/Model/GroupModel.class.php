<?php
/**
 * User: cyh
 * Date: 2017/4/8
 * Time: 23:18
 * Description:
 */

namespace Common\Model;


class GroupModel extends BaseModel {
    public function getGroups(){
       return $this->select();
    }
    public function getMaxPage($map = null){
        return max_page($this->where($map)->select());
    }


}