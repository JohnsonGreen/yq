<?php
namespace Common\Model;

class UserGroupModel extends BaseModel {
 
    public function addGroupUser($userid,$groupid){
      return $this->add(array('userid'=>$userid,'groupid'=>$groupid));
    }

}