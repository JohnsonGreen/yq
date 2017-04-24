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
		$response['is_err'] = 0;
		$result = D('Key')->getKeys($p);
		$response['result'] = $result;
		$response['max_page'] = D('Key')->getMaxPage();
		$response['url']=array(
			'key_del'=>'Admin/Key/del'
		);
		echo json_encode($response);
		exit;
	}
	public function del(){
		$id = I('post.id');
		$map['keyid'] = $id;
		if(D('Key')->where($map)->delete()){
			$response['is_err'] = 0;
			$p = I('post.page');
			if(empty($p))
				$p = 1;
			$result = D('Key')->getKeys($p);
			$response['result'] = $result;
			$response['max_page'] = D('Key')->getMaxPage();
		}else{
			$response['is_err'] = 1;
			$response['result'] = '数据库错误，请重试！';
		}
		echo json_encode($response);
		exit;
	}



}
