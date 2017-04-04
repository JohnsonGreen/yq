<?php
/**
 * Created by PhpStorm.
 * User: GuoRenjie
 * Date: 2017/3/21
 * Time: 8:46
 */
namespace Common\Model;
use Think\Model;
class KeyModel extends Model {

    public function getKeys($p = 1){
        $result = $this
            ->join('LEFT JOIN yq_message on yq_key.messageid = yq_message.messageid')
            ->field('keyid, keyname, yq_message.createtime')
            ->page($p.',10')
            ->select();
        $result = stand_date($result);

        return $result;
    }


}
