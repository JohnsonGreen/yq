<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 权限还没写
 */
class SingleController extends AdminBaseController{
	/**
	 * 单条信息报送(学生)
	 * 有userid约束
	 */

	public function single(){
//		@session_start();
		$map['userid'] = I('session.userid');

		$page = I('post.page');
		$map['product'] = 1;
		$result = D('Message')->getData($map, $page);
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		$response['is_err'] = 0;
		$response['url'] = array(
			'single_del'=>'Admin/Single/del_message'
			'single_update' => 'Admin/Single/update_message'
			'single_love' => 'Admin/Single/love'
			);
		echo json_encode($response);
		exit;
	}

	//单条信息报送（管理员）
	public function single_admin(){
		$page = I('post.page');
		$map['product'] = 1;
		$result = D('Message')->getData($map, $page);
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		$response['is_err'] = 0;
		$response['url'] = array(
			'single_del'=>'Admin/Single/del_message_admin',
			'single_update' => 'Admin/Single/update_message',
			'single_love' => 'Admin/Single/love'
         );
		echo json_encode($response);
		exit;
	}


    //搜索
    public function search(){
        $date1 = strtotime(I('post.date1'));
        $date2 = strtotime(I('post.date2'));
        if($date1)
            $map['createtime'] = array('gt', $date1);
        if($date2)
            $map['createtime'] = array('lt', $date2);

        //关键字
        $key = I('post.keywords');
        if($key)
            $map['title'] = array('like', $key);

        //学院
        $school = I('post.school');
        if($school)
            $map['schname'] = $school;

        //类别
        $type = I('post.type');
        if($type)
            $map['type'] = $type;

        $map['userid'] = I('session.userid');

        $map['product'] = 1;
        $page = I('post.page');
        $result = D('Message')->getData($map, $page);
        $response['result'] = $result;
        $response['max_page'] = count($result)/10;
        $response['is_err'] = 0;
        echo json_encode($response);
        exit;

    }

	/**管理员
	 * 没有userid约束
	 */
	public function search_admin(){
		$date1 = strtotime(I('post.date1'));
		$date2 = strtotime(I('post.date2'));
		$map['createtime'] = array('gt', $date1);
		$map['createtime'] = array('lt', $date2);

		$map['product'] = 1;
		$page = I('post.page');
		$result = D('Message')->getData($map, $page);
		$response['result'] = $result;
		$response['max_page'] = count($result)/10;
		$response['is_err'] = 0;
		echo json_encode($response);
		exit;
	}


	//编辑
	public function update_message(){

	}

    //删除
	public function del_message(){
		$id = trim(I('post.message_id'));
		$result = array();
		$uid = D('Message')->getUidByMessageid($id);
		if($uid != $_SESSION['user']['userid']){
			$result['result'] = '只能删除自己的报送';
			$result['is_err'] = 1;
			echo json_encode($result);
		    exit;
		}

		if(D('Message')->del($id)){
			$result['result'] = 'is_ok';
			$result['is_err'] = 0;
		}else{
			$result['result'] = '数据库错误，请重试！';
			$result['is_err'] = 1;
		}
		echo json_encode($result);
		exit;
	}

	//删除
	public function del_message_admin(){
		$id = trim(I('post.message_id'));
		$result = array();
		if(D('Message')->del($id)){
			$result['result'] = 'is_ok';
			$result['is_err'] = 0;
		}else{
			$result['result'] = '数据库错误，请重试！';
			$result['is_err'] = 1;
		}
		echo json_encode($result);
		exit;
	}
    //收藏
	public function love(){
		$id = trim(I('post.message_id'));
		$result = array();
		if(D('User_collection')->love($id)){
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
    public function add_single_message(){
		//判断在中文逗号
		$keys = trim(I('post.keywords'));
		$pos = strpos($keys, '，');
		$response = array();
		if($pos){
			$response['is_err'] = 1;
			$response['result'] = "出现中文逗号";
		}
		else{
			$user_id = trim($_SESSION['user']['userid']);
			$map['userid'] = $user_id;
			$result = D('User')->where($map)->find();

			//舆情信息输入

			$data['userid'] = $user_id;
			$data['schoolid'] = $result['schoolid'];
			$data['title'] = trim(I('post.title'));

			$data['product'] = 1;
			$data['typeid'] = trim(I('post.typeid'));
			$data['base'] = 5;

			$data['source'] = trim(I('post.source'));
			$data['url'] = trim(I('post.url'));

			$data['title'] = trim(I('post.title'));
			$data['content'] = I('post.content');
			$data['createtime'] = time();
			$data['select'] = 0;
			$data['approval'] = 0;
			$data['warning'] = 0;
			$data['quality'] = 0;
			$data['special'] = 0;
			$data['substract'] = 0;
			$data['add'] = 0;
			$data['score'] = $data['base'] + $data['select'] + $data['approval'] + $data['warning'] + $data['quality'] + $data['special'] - $data['substract'] + $data['add'];

			$data['is_delete'] = 0;

			//UPLOAD
			$upload = new \Think\Upload();// 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
			$upload->exts      =     array('pdf', 'txt', 'doc', 'jpeg', '.docx', 'png', 'jpg');// 设置附件上传类型
			$upload->rootPath  =     __ROOT__.'/Uploads/'; // 设置附件上传根目录
			$upload->savePath  =     ''; // 设置附件上传（子）目录
			// 上传文件

			$info   =   $upload->upload();
			$imgPath = $upload->rootPath.$info['content']['savepath'].$info['content']['savename'];
			if(!$info) {// 上传错误提示错误信息
				$response['is_err'] = 1;
				$result['result'] = $upload->getError();
			}else{// 上传成功
				$response['is_err'] = 0;
				$result['result'] = "is_ok";
			}

			//
			$data['file'] = $imgPath;

			$new_message = D('Message')->add($data);
			if($new_message){
				$response['is_err'] = 0;
				$result['result'] = "is_ok";
			}else{
				$response['is_err'] = 1;
			}

			//关键字

			$key_arr = explode(",",$keys);
			foreach($key_arr as $i => $item){
				$key_data['keyname'] = $item;
				$key_data['messageid'] = $new_message;
				D('Key')->add($key_data);
			}
		}

		echo json_encode($response);
		exit;
	}

}
