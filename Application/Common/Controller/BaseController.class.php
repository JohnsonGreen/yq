<?php
namespace Common\Controller;
use Think\Controller;
/**
 * Base基类控制器
 */
class BaseController extends Controller{
    /**
     * 记录每次请求的时间
     */
    public function _initialize(){
        $data['userid'] = I('session.user')['userid'];
        if(!empty($data['userid'])){
            $data['updatetime'] = time();
            D('User')->save($data);
        }
    }

}
