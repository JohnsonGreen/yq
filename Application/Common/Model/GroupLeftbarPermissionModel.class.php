<?php
/**
 * User: cyh
 * Date: 2017/4/7
 * Time: 18:49
 * Description:
 */

namespace Common\Model;

class GroupLeftbarPermissionModel extends BaseModel{

     public function getLeftBar($groupid){
       return   $this->table('__GROUP_LEFTBAR_PERMISSION__  a')
                     ->join('__LEFTBAR__  b')
                     ->join('__PERMISSION__ c')
                     ->where('a.groupid='.$groupid.' and a.itemid=b.itemid and a.permid=c.permid')
                     ->field('b.itemname as name, c.action as api, b.keybind')
                     ->order('a.itemid asc')->select();
     }

}