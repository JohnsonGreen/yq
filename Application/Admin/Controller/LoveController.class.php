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
		if(empty($p))
			$p = 1;
		$result = D('UserCollection')->getData(null, $p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = D('UserCollection')->getDataPage(null);
		$response['url']=array(
			'collect_del'=>'Admin/Love/del',
			'collect_search' => 'Admin/Love/search'
		);
		echo json_encode($response);
		exit;
	}

	//日期搜索
	public function search(){
		$p = I('post.page');
		if(empty($p))
			$p = 1;

		$date1 = strtotime(I('post.date1'));
		$date2 = strtotime(I('post.date2'));
		if($date1 && $date2)
			$map['createtime'] = array(array('gt', $date1), array('lt', $date2));

		//关键字
		$key = I('post.keywords');
		if($key)
			$map['title'] = array('like', $key);

		//学院
		$school = I('post.school');
		if($school && $school != "全部")
			$map['schname'] = $school;

		//类别
		$type = I('post.type');
		if($type && $type != "全部")
			$map['type'] = $type;

		$result = D('User_collection')->getData($map, $p);
		$response['is_err'] = 0;
		$response['result'] = $result;
		$response['max_page'] = D('UserCollection')->getDataPage($map);
		echo json_encode($response);
		exit;
	}
//	//关键字
//	public function search_key(){
//		$p = I('post.page');
//		$key = I('post.keywords');
//		$map['yq_message.title'] = array('like', $key);
//
//		$result = D('User_collection')->getData($map, $p);
//		$response['is_err'] = 0;
//		$response['result'] = $result;
//		$response['max_page'] = max_page($result);
//		echo json_encode($response);
//		exit;
//	}
//	//学院
//	public function search_school(){
//		$p = I('post.page');
//		$school = I('post.school');
//		$map['schname'] = $school;
//
//		$result = D('User_collection')->getData($map, $p);
//		$response['is_err'] = 0;
//		$response['result'] = $result;
//		$response['max_page'] = max_page($result);
//		echo json_encode($response);
//		exit;
//	}
//	//类别
//	public function type(){
//		$p = I('post.page');
//		$map['type'] = I('post.type');
//
//		$result = D('UserCollection')->getData($map, $p);
//		$response['is_err'] = 0;
//		$response['result'] = $result;
//		$response['max_page'] = max_page($result);
//		echo json_encode($response);
//		exit;
//	}
	//删除
	public function del(){
		$id = I('post.messageid');
		if(D('UserCollection')->del($id)){
			$response['is_err'] = 0;
			$response['result'] = '删除成功';
		}else{
			$response['is_err'] = 1;
			$response['result'] = '数据库错误，请重试！';
		}
		echo json_encode($response);
		exit;
	}
}
