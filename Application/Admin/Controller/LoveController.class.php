<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 权限还没写
 */
class LoveController extends AdminBaseController{
	/**
	 * 搜藏
	 */
	public function index(){
		$p = I('post.page');
		$result = D('User_collection')->getData(null, $p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}

	//日期搜索
	public function search_date(){
		$p = I('post.page');
		$date1 = strtotime(I('post.date1'));
		$date2 = strtotime(I('post.date2'));
		$map['yq_message.createtime'] = array('gt', $date1);
		$map['yq_message.createtime'] = array('lt', $date2);

		$result = D('User_collection')->getData($map, $p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}
	//关键字
	public function search_key(){
		$p = I('post.page');
		$key = I('post.keywords');
		$map['yq_message.title'] = array('like', $key);

		$result = D('User_collection')->getData($map, $p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}
	//学院
	public function search_school(){
		$p = I('post.page');
		$school = I('post.school');
		$map['schname'] = $school;

		$result = D('User_collection')->getData($map, $p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}
	//类别
	public function type(){
		$p = I('post.page');
		$map['type'] = I('post.type');

		$result = D('User_collection')->getData($map, $p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		echo json_encode($response);
		exit;
	}
	//删除
	public function del(){
		$id = I('post.id');
		if(D('User_collection')->del($id)){
			$response['is_err'] = 0;
			$response['result'] = 'is_ok';
		}else{
			$response['is_err'] = 1;
			$response['result'] = '数据库错误，请重试！';
		}
		echo json_encode($response);
		exit;
	}
}
