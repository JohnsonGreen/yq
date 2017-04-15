<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 权限还没写
 */
class SingleController extends AdminBaseController{


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
	 * 单条信息报送(学生)
	 * 有userid约束
	 */

	public function single(){
		@session_start();
		$map['userid'] = $_SESSION['user']['userid'];
		$page = I('post.page');
		$map['yq_message.product'] = 1;
		$result = D('Message')->getData($map, $page);
		$cnt = count($result);
		for($i = 0; $i < $cnt;$i++){
		   $result[$i]['flag'] = 1;
        }
		$response['result'] = $result;
		$response['max_page'] =D('Message')->getMessagePage($map);
		$response['is_err'] = 0;
		$response['url'] = array(
			'single_del'=>'Admin/Single/del_message',
			'single_update' => 'Admin/Single/update_message',
			'single_love' => 'Admin/Single/love',
            'single_add'=>'Admin/Single/add_single_message',
            'single_search'=>'Admin/Single/search',
            'single_details'=>'Admin/Single/index'
			);
		echo json_encode($response);
		exit;
	}

    //单条信息报送（老师）
    public function single_teacher(){
        @session_start();
        $page = I('post.page');
        $map['yq_message.product'] = 1;
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
        $response['url'] = array(
            'single_del'=>'Admin/Single/del_message',
            'single_update' => 'Admin/Single/update_message',
            'single_love' => 'Admin/Single/love',
            'single_add'=>'Admin/Single/add_single_message',
            'single_search'=>'Admin/Single/search_teacher',
            'single_details'=>'Admin/Single/index'
        );
        echo json_encode($response);
        exit;
    }

	//单条信息报送（管理员）
	public function single_admin(){
		$page = I('post.page');
        $map['yq_message.product'] = 1;
		$result = D('Message')->getData($map, $page);
        $cnt = count($result);
        for($i = 0; $i < $cnt;$i++){
            $result[$i]['flag'] = 1;
        }
		$response['result'] = $result;
		$response['max_page'] = D('Message')->getMessagePage($map);
		$response['is_err'] = 0;
		$response['url'] = array(
			'single_del'=>'Admin/Single/del_message_admin',
			'single_update' => 'Admin/Single/update_message_admin',
			'single_love' => 'Admin/Single/love',
            'single_add'=>'Admin/Single/add_single_message',
            'single_search'=>'Admin/Single/search_admin',
            'single_details'=>'Admin/Single/index'
         );
		echo json_encode($response);
		exit;
	}

    //搜索(学生)
    public function search(){
        @session_start();
        $date1 = strtotime(I('post.date1'));
        $date2 = strtotime(I('post.date2'));
        if(empty($date1) && !empty($date2)){
            $map['createtime'] =  array('lt', $date2  + 3600000*24);
        }else if(!empty($date1) && empty($date2)){
            $map['createtime'] = array('gt', $date1);
        }else if($date1 && $date2){
            $map['createtime'] = array(array('gt', $date1), array('lt', $date2  + 3600000*24));
        }

        //关键字
        $key = I('post.keywords');
        if($key)
            $map['title'] = array('like', $key);

        //学生
        $map['userid'] = $_SESSION['user']['userid'];

        //类别
        $type = I('post.type');
        if($type && $type != "全部")
            $map['type'] = $type;

        $map['yq_message.product'] = array('eq', 1);

        $page = I('post.page');
        $result = D('Message')->getData($map, $page);

        $cnt = count($result);
        for($i = 0; $i < $cnt;$i++){
            $result[$i]['flag'] = 1;
        }

        $response['result'] = $result;
        $response['max_page'] = D('Message')->getMessagePage($map);
        $response['is_err'] = 0;
        echo json_encode($response);
        exit;

    }


    //搜索
    public function search_teacher(){
        @session_start();
        $date1 = strtotime(I('post.date1'));
        $date2 = strtotime(I('post.date2'));
        if(empty($date1) && !empty($date2)){
            $map['createtime'] =  array('lt', $date2  + 3600000*24);
        }else if(!empty($date1) && empty($date2)){
            $map['createtime'] = array('gt', $date1);
        }else if($date1 && $date2){
            $map['createtime'] = array(array('gt', $date1), array('lt', $date2  + 3600000*24));
        }

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

        $map['yq_message.product'] = array('eq', 1);

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

	/**管理员
	 * 没有userid约束
	 */
	public function search_admin(){

        @session_start();
        $date1 = strtotime(I('post.date1'));
        $date2 = strtotime(I('post.date2'));
        if(empty($date1) && !empty($date2)){
            $map['createtime'] =  array('lt', $date2 + 3600000*24);
        }else if(!empty($date1) && empty($date2)){
            $map['createtime'] = array('gt', $date1);
        }else if($date1 && $date2){
            $map['createtime'] = array(array('gt', $date1), array('lt', $date2 + 3600000*24));
        }

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

        $map['yq_message.product'] = array('eq', 1);

        $page = I('post.page');
        $result = D('Message')->getData($map, $page);

        $cnt = count($result);
        for($i = 0; $i < $cnt;$i++){
            $result[$i]['flag'] = 1;
        }
        $response['result'] = $result;
        $response['max_page'] = D('Message')->getMessagePage($map);
        $response['is_err'] = 0;
        echo json_encode($response);
        exit;
	}


    //编辑
    public function update_message(){

        //判断在中文逗号
        @session_start();
        $keys = trim(I('post.keywords'));
        $pos = strpos($keys, '，');
        $response = array();
        if($pos){
            $response['is_err'] = 1;
            $response['result'] = "出现中文逗号";
        }
        else{

            $user_id = trim($_SESSION['user']['userid']);
            $data['messageid'] = trim(I('post.messageid'));
            $result = D('Message')->field('userid')->where($data)->find();
            if($result['userid'] != $user_id){
                $response['is_err'] = 1;
                $response['result'] = "只能更新自己的条目";
                echo json_encode($response);
                exit;
            }

            //舆情信息输入

            $data['userid'] = trim(I('post.userid'));
            $data['schoolid'] = trim(I('post.schoolid'));
            $data['title'] = trim(I('post.title'));
            $data['product'] = 1;
            $data['typeid'] = trim(I('post.typeid'));
            $data['base'] = 5;
            $data['source'] = trim(I('post.source'));
            $data['url'] = trim(I('post.url'));
            $data['title'] = trim(I('post.title'));
            $data['content'] = I('post.content');

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
                $response['result'] = $upload->getError();
                echo json_encode($response);
                exit;
            }

            $data['file'] = $imgPath;
            $new_message = D('Message')->save($data);
            if($new_message){
                $response['is_err'] = 0;
                $response['result'] = "更新成功";
            }else{
                $response['is_err'] = 1;
                $response['result'] = "更新失败";
            }

            $key_arr = explode(',',$keys);
            foreach($key_arr as $i => $item){
                $key_data['keyname'] = $item;
                $key_data['messageid'] = $new_message;
                D('Key')->add($key_data);
            }
        }

        echo json_encode($response);
        exit;
    }


    //编辑
    public function update_message_admin(){

        //判断在中文逗号
        @session_start();
        $keys = trim(I('post.keywords'));
        $pos = strpos($keys, '，');
        $response = array();
        if($pos){
            $response['is_err'] = 1;
            $response['result'] = "出现中文逗号";
        }
        else{

            //舆情信息输入
            $data['messageid'] = trim(I('post.messageid'));
            $data['userid'] = trim(I('post.userid'));
            $data['schoolid'] = trim(I('post.schoolid'));
            $data['title'] = trim(I('post.title'));
            $data['product'] = 1;
            $data['typeid'] = trim(I('post.typeid'));
            $data['base'] = 5;
            $data['source'] = trim(I('post.source'));
            $data['url'] = trim(I('post.url'));
            $data['title'] = trim(I('post.title'));
            $data['content'] = I('post.content');

            //UPLOAD
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('pdf', 'txt', 'doc', 'jpeg', '.docx', 'png', 'jpg');// 设置附件上传类型
            $upload->rootPath  =     __ROOT__.'/Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            // 上传文件

            $info   =   $upload->upload();
            var_dump($info);
            exit;
            $imgPath = $upload->rootPath.$info['content']['savepath'].$info['content']['savename'];
            if(!$info) {// 上传错误提示错误信息
                $response['is_err'] = 1;
                $response['result'] = $upload->getError();
                echo json_encode($response);
                exit;
            }

            $data['file'] = $imgPath;
            $new_message = D('Message')->save($data);
            if($new_message){
                $response['is_err'] = 0;
                $response['result'] = "更新成功";
            }else{
                $response['is_err'] = 1;
                $response['result'] = "更新失败";
            }

            $key_arr = explode(',',$keys);
            foreach($key_arr as $i => $item){
                $key_data['keyname'] = $item;
                $key_data['messageid'] = $new_message;
                D('Key')->add($key_data);
            }
        }

        echo json_encode($response);
        exit;
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
		$id = trim(I('post.messageid'));
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
			$result['result'] = '收藏成功';
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
		$keys = trim(I('post.keyword'));
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
			$data['source'] = trim(I('post.source'));
			$data['url'] = trim(I('post.url'));
            $data['keyword'] = $keys;
			$data['title'] = trim(I('post.title'));
			$data['content'] = I('post.content');

			$data['createtime'] = time();
            $data['base'] = 5;
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
			$upload->exts      =     array('pptx','ppt','xls','xlsx','pdf', 'txt', 'doc', 'jpeg', 'docx', 'png', 'jpg');// 设置附件上传类型
			$upload->rootPath  =     __ROOT__.'/Uploads/'; // 设置附件上传根目录
			$upload->savePath  =     ''; // 设置附件上传（子）目录
			// 上传文件
			$info   =   $upload->upload();
			$imgPath = "";

			if(!$info) {// 上传错误提示错误信息
				$response['is_err'] = 1;
				$result['result'] = $upload->getError();
			}else{// 上传成功
				$response['is_err'] = 0;
				$result['result'] = "is_ok";
                $imgPath = $upload->rootPath.$info['content']['savepath'].$info['content']['savename'];
			}

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
