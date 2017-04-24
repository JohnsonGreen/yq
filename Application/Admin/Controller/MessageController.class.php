<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 权限还没写
 */
class MessageController extends AdminBaseController{
	/**
	 * 报送条目的详情页
	 */
	public function index(){
		$id = I('post.messageid');
//		$id = 1;
		$result = D('Message')->getMessage($id);

		//访问量加一
		$map['messageid'] = $id;
		D('Message')->where($map)->setInc('click');
		$response['is_err'] = 0;
		$response['result'] = $result[0];
		$response['max_page'] = D('Message')->getMessagePage($map);

		echo json_encode($response);
		exit;
	}

	/**
	 * 综合信息报送
	 * 有userid约束
	 */
	public function single(){
		@session_start();
		$page = I('post.page');
        $map['yq_message.product'] = array('neq', 1);
		$result = D('Message')->getData($map, $page);

        $cnt = count($result);
        for($i = 0; $i < $cnt;$i++){
            if($result[$i]['userid'] == $_SESSION['user']['userid']){
                $result[$i]['flag'] = 1;
            }else{
                $result[$i]['flag'] = 0;
            }
        }

		$response['result'] = $result;
		$response['is_err'] = 0;
		$response['max_page'] = D('Message')->getMessagePage($map);
		$response['url'] = array(
		    'overall_del'=>'Admin/Message/del_message',
            'overall_love'=>'Admin/Message/love',
            'overall_update'=>'Admin/Message/update_message',
            'overall_add'=>'Admin/Message/add_message',
            'overall_search'=> 'Admin/Message/search_admin',
            'overall_details'=>'Admin/Message/index',
			'add_file'=>'Admin/Index/add_file'
        );
		echo json_encode($response);
		exit;
	}
	//?????????????学生搜索怎么办？？？
	public function search(){

		$date1 = strtotime(I('post.date1'));
		$date2 = strtotime(I('post.date2'));
		if(empty($date1) && !empty($date2)){
            $map['createtime'] =  array('lt', $date2);
        }else if(!empty($date1) && empty($date2)){
            $map['createtime'] = array('gt', $date1);
        }else if($date1 && $date2){
            $map['createtime'] = array(array('gt', $date1), array('lt', $date2));
        }

		//关键字
		$key = I('post.keywords');
		if($key)
			$map['title'] = array('like', '%'.$key.'%');

		//学院
		$school = I('post.school');
		if($school && $school != "全部")
			$map['schname'] = $school;

		//类别
		$type = I('post.type');
		if($type && $type != "全部")
			$map['type'] = $type;

		$map['yq_message.product'] = array('neq', 1);
		$page = I('post.page');
		$result = D('Message')->getData($map, $page);

		$cnt = count($result);
		for($i = 0; $i < $cnt;$i++){
			if($result[$i]['userid'] == $_SESSION['user']['userid']){
				$result[$i]['flag'] = 1;
			}else{
				$result[$i]['flag'] = 0;
			}
		}

		$response['result'] = $result;
		$response['max_page'] = D('Message')->getMessagePage($map);
		$response['is_err'] = 0;
		echo json_encode($response);
		exit;

	}

	//管理员
	//没有userid约束
	//日期搜索
	//综合信息报送（管理员）
	public function single_admin(){
        $map['yq_message.product'] = array('neq', 1);
		$page = I('post.page');
		$result = D('Message')->getData($map, $page);
        $cnt = count($result);
        for($i = 0; $i < $cnt;$i++){
            $result[$i]['flag'] = 1;
        }
		$response['result'] = $result;
		$response['max_page'] = D('Message')->getMessagePage($map);
		$response['is_err'] = 0;
        $response['url'] = array(
            'overall_del'=>'Admin/Message/del_message_admin',
            'overall_love'=>'Admin/Message/love',
            'overall_update'=>'Admin/Message/update_message',
            'overall_add'=>'Admin/Message/add_message',
            'overall_search'=> 'Admin/Message/search_admin',
            'overall_details'=>'Admin/Message/index',
			'add_file'=>'Admin/Index/add_file'
        );
		echo json_encode($response);
		exit;
	}

    public function search_admin(){

        $date1 = strtotime(I('post.date1'));
        $date2 = strtotime(I('post.date2'));
        if($date1 && $date2)
            $map['createtime'] = array(array('gt', $date1), array('lt', $date2));

        //关键字
        $key = I('post.keywords');
        if($key)
            $map['title'] = array('like', '%'.$key.'%');

        //学院
        $school = I('post.school');
        if($school && $school != "全部")
            $map['schname'] = $school;

        //类别
        $type = I('post.type');
        if($type && $type != "全部")
            $map['type'] = $type;
		$map['yq_message.product'] = array('neq', 1);
        $page = I('post.page');
        $result = D('Message')->getData($map, $page);
        $response['result'] = $result;
        $response['max_page'] = D('Message')->getMessagePage($map);
        $response['is_err'] = 0;
        echo json_encode($response);
        exit;

    }

		//删除
	public function del_message(){
        @session_start();
		$id = trim(I('post.messageid'));
		$result = array();
		if(D('Message')->del($id,$_SESSION['user']['userid'])){
			$result['is_err'] = 0;
			$result['result'] = '删除成功！';
		}else{
			$result['is_err'] = 1;
			$result['result'] = '删除失败！';
		}
		echo json_encode($result);
		exit;
	}

    public function del_message_admin(){
        $id = trim(I('post.messageid'));
        $result = array();
        if(D('Message')->del($id)){
            $result['is_err'] = 0;
            $result['result'] = 'is_ok';
        }else{
            $result['is_err'] = 1;
            $result['result'] = '数据库错误，请重试！';
        }
        echo json_encode($result);
        exit;
    }

	//收藏
	public function love(){
        @session_start();
		$id = trim(I('post.messageid'));
		$result = array();
        $uc = D('UserCollection');
        $map['userid'] = $_SESSION['user']['userid'];
        $map['messageid'] = $id;
        $res = $uc->where($map)->field('messageid')->find();
        if(!empty($res)){
            $result['result'] = '已收藏';
            $result['is_err'] = 1;
            echo json_encode($result);
            exit;
        }
		if($uc->love($id)){
			$result['result'] = 'is_ok';
			$result['is_err'] = 0;
		}
		else{
			$result['result'] = '数据库错误，请重试！';
			$result['is_err'] = 1;
		}
		echo json_encode($result);
		exit;
	}
	/**
	 * elements
	 */


	//增加
	public function add_message(){

		$user_id = trim($_SESSION['user']['userid']);
		$map['userid'] = $user_id;
		$result = D('User')->where($map)->find();

		//舆情信息输入

		$data['userid'] = $user_id;
		$data['schoolid'] = $result['schoolid'];
		$data['title'] = trim(I('post.title'));
		$product = trim(I('post.product'));
		$response = array();

		if($product == "舆情专报"){
			$data['product'] = 2;
			$data['typeid'] = trim(I('post.typeid'));
			$data['base'] = 10;
		}
		elseif($product == "舆情扫描"){
			$data['product'] = 3;
			$data['base'] = 5;
		}
		else{
			$response['is_err'] = 1;
			$response['result'] = "数据库错误，请重试！";
		}

		$data['title'] = trim(I('post.title'));
		$data['content'] = I('post.content');
		$data['createtime'] = time();

		$data['substract'] = 0;
		$data['add'] = 0;
		$data['score'] = $data['base']  - $data['substract'] + $data['add'];

		$data['is_delete'] = 0;
		$messageid = D('Message')->add($data);
		if($messageid){
			$response['is_err'] = 0;
			$response['result'] = "is_ok";
			$response['newid'] = $messageid;
		}else{
			$response['is_err'] = 1;
			$response['result'] = "数据不完整请重试！";
		}

		echo json_encode($response);
		exit;
	}

	//编辑
	public function update_message(){
		$user_id = trim($_SESSION['user']['userid']);
		$map['userid'] = $user_id;
		$result = D('User')->where($map)->find();

		//舆情信息输入
		$data['userid'] = $user_id;
//		$data['schoolid'] = $result['schoolid'];
//		$data['title'] = trim(I('post.title'));
		$product = trim(I('post.product'));
		$response = array();

//		if($product == "舆情专报"){
//			$data['product'] = 2;
//			$data['typeid'] = trim(I('post.typeid'));
//			$data['base'] = 10;
//		}
//		elseif($product == "舆情扫描"){
//			$data['product'] = 3;
//			$data['base'] = 5;
//		}
//		else{
//			$response['is_err'] = 1;
//			$response['result'] = "数据库错误，请重试！";
//		}

//		$data['title'] = trim(I('post.title'));
		$data['content'] = trim(I('post.content'));
//		$data['createtime'] = time();
//

		//UPLOAD
//		$upload = new \Think\Upload();// 实例化上传类
//		$upload->maxSize   =     3145728 ;// 设置附件上传大小
//		$upload->exts      =     array('pdf', 'txt', 'doc', 'jpeg', '.docx', 'png', 'jpg');// 设置附件上传类型
//		$upload->rootPath  =     __ROOT__.'/Uploads/'; // 设置附件上传根目录
//		$upload->savePath  =     ''; // 设置附件上传（子）目录
//		// 上传文件
//
//		$info   =   $upload->upload();
//		$imgPath = $upload->rootPath.$info['content']['savepath'].$info['content']['savename'];
//		if(!$info) {// 上传错误提示错误信息
//			$response['is_err'] = 1;
//			$result['result'] = $upload->getError();
//		}else{// 上传成功
			$response['is_err'] = 0;
			$result['result'] = "is_ok";
//		}

		//
//		$data['file'] = $imgPath;
		$data['messageid']=I('post.messageid');
//		cout($data);
		if(D('Message')->save($data)){
			$response['is_err'] = 0;
			$result['result'] = "is_ok";
		}else{
			$response['is_err'] = 1;
		}

		echo json_encode($response);
		exit;
	}



}
