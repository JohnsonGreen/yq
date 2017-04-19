<?php
/**
 * Created by PhpStorm.
 * User: GuoRenjie
 * Date: 2017/3/21
 * Time: 8:46
 */
namespace Common\Model;
use Think\Model;
class AnnounceModel extends Model {
    public function getData($p, $map = null)
    {
        $hints = I('session.user')['hint_announce'];


        $result = $this
            -> join('yq_user on yq_announce.userid = yq_user.userid')
            ->where($map)
            ->order('yq_announce.createtime desc, stick desc')
            ->page($p.',10')
            ->select();

        foreach ($result as $i => $item){
            if(array_search($result[$i]['anoceid'],$hints)){
                $result[$i]['isread'] = 1;
            }else{
                $result[$i]['isread'] = 0;
            }
        }
        return $result;
    }

    public function getMaxPage($map = null){
        $result = $this
            ->join('yq_user on yq_announce.userid = yq_user.userid')
            ->where($map)
            ->order('yq_announce.createtime desc, stick desc')
            ->select();
        return ceil(count($result)/10);
    }

    public function getHint($id){
        $announce_num = D('Announce')->count();
        $map['userid'] = $id;

        $looked = D('Hint')->where($map)->getField('anoceid', true);

        $hint_num = $announce_num - count($looked);
        $result['num'] = $hint_num;


        $map_announce['anoceid'] = array('not in',$looked);
        $result['result'] = $looked;

        return $result;
    }

}
