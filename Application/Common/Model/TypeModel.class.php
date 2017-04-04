<?php
/**
 * User: cyh
 * Date: 2017/3/27
 * Time: 12:47
 * Description: 报送的类型
 */

namespace Common\Model;


class TypeModel extends BaseModel{

    /**
     * 返回所有的类型
     * @return mixed
     */
   public function getTypes(){
       return $this->select();
   }
}