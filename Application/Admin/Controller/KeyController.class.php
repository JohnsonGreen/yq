<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 后台首页控制器
 */
class KeyController extends AdminBaseController{
	/**
	 * 个人设置
	 */
	public function index(){
		$p = I('post.page');
        if(empty($p)){
            $p = 1;
        }
		$response['is_er'] = 0;
		$result = D('Key')->getKeys($p);
		$response['result'] = $result;
		$response['max_page'] = D('Key')->getMaxPage();
		echo json_encode($response);
		exit;
	}



}
