<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
header('content-type:application/json;charset=utf8');
/**
 * 后台首页控制器
 */
class AnnounceController extends AdminBaseController{
	/**
	 * 首页
	 */

	public function index(){

		$p = I('post.page');
		if(empty($p))
			$p = 1;

		$announcement = D('Announce')->getData($p, null);

		$res['is_err'] = 1;
		if($announcement)
			$res['is_err'] = 0;
		$res['result'] = $announcement;
		$res['max_page'] = D('Announce')->getMaxPage(null);
		//权限问题管理员才能有添加公告权限，我没判断
		$res['url'] = array(
			'announce_search'=>'Admin/Announce/search',
			'announce_add'=>'Admin/Announce/add_announce',
            'announce_details'=>'Admin/Announce/detail'
		);
		echo json_encode($res);
		exit;
	}

	public function search(){
		$p = I('post.page');
		if(empty($p))
			$p = 1;

		$date1 = strtotime(I('post.date1'));
		$date2 = strtotime(I('post.date2'));

        if(empty($date1) && !empty($date2)){
            $map['createtime'] =  array('lt', $date2  + 3600000*24);
        }else if(!empty($date1) && empty($date2)){
            $map['createtime'] = array('gt', $date1);
        }else if($date1 && $date2){
            $map['createtime'] = array(array('gt', $date1), array('lt', $date2  + 3600000*24));
        }

		$key = I('post.keywords');
		if($key)
			$map['title'] = array('like', $key);

		$result = D('Announce')->getData($p, $map);

		$res['is_err'] = 1;
		if($result)
			$res['is_err'] = 0;

		$res['result'] = $result;
		$res['max_page'] = D('Announce')->getMaxPage($map);
		echo json_encode($res);
		exit;

	}


	public function key_search(){
		$key = I('post.keywords');
		$map['title'] = array('like', $key);
		$result = D('Announce')	->join('yq_user on yq_announce.userid = yq_user.userid')
			->where($map)
			->order('yq_announce.createtime desc, stick desc')->select();
		$result = stand_date($result);
		$res['is_err'] = 1;
		if($result)
			$res['is_err'] = 0;
		$res['result'] = $result;
		$res['max_page'] =max_page($result);
		echo json_encode($res);
		exit;
	}

	//置顶
	public function stick(){
		$id = I('post,id');
		$data['anoceid'] = $id;
		$data['stick'] = 1;
		if(D('Announce')->save($data)){
			$result['is_err'] = 0;
			$result['result'] = 'is_ok';
		}else{
			$result['is_err'] = 1;
			$result['result'] = '数据库错误，请重试！';
		}
		echo json_encode($result);
		exit;
	}

	//增加
	public function add_announce(){
		//标题输入最长长度
        @session_start();
		$max_title_length = 10;

		$data['userid'] = $_SESSION['usesr']['userid'];
		$data['title'] = trim(I('post.title'));
		$data['content'] = trim(I('post.content'));

		$data['creattime'] = time();

//
//        $upload = new \Think\Upload();// 实例化上传类
//        $upload->maxSize   =     3145728 ;// 设置附件上传大小
//        $upload->exts      =     array('pptx','ppt','xls','xlsx','pdf', 'txt', 'doc', 'jpeg', 'docx', 'png', 'jpg');// 设置附件上传类型
//        $upload->rootPath  =     __ROOT__.'/Uploads/'; // 设置附件上传根目录
//        $upload->savePath  =     ''; // 设置附件上传（子）目录
//
//        $info   =   $upload->upload();
//        $imgPath = '';
//        if(!$info) {// 上传错误提示错误信息
//            $response['is_err'] = 1;
//            $result['result'] = $upload->getError();
//        }else{// 上传成功
//            $response['is_err'] = 0;
//            $result['result'] = "is_ok";
//            $imgPath = $upload->rootPath.$info['content']['savepath'].$info['content']['savename'];
//        }
//        $data['file'] = $imgPath;



       // if($data['userid'] && $data['title'] && $data['content'] && $data['creattime']){
			if(strlen($data['title']) > $max_title_length ){
				$result['is_err'] = 1;
				$result['reusult'] = '标题太长，请重试！';
			}
			elseif(D('Announce')->add($data)){
				$result['is_err'] = 0;
				$result['reusult'] = 'is_ok';
			}else{
				$result['is_err'] = 1;
				$result['reusult'] = 'kasdjfhds';
			}
		//}else{
		//	$result['is_err'] = 1;
		//	$result['reusult'] = '3333333333333';
		//}

		echo json_encode($result);
		exit;

	}

	//编辑
	public function update_announce(){
		$data['userid'] = I('session.userid');
		$data['title'] = trim(I('post.title'));
		$data['content'] = trim(I('post.content'));
		$data['creattime'] = time();
		if($data['userid'] && $data['title'] && $data['content'] && $data['creattime']){
			if(D('Announce')->save($data)){
				$result['is_err'] = 0;
				$result['reusult'] = 'is_ok';
			}else{
				$result['is_err'] = 1;
				$result['reusult'] = '数据库错误，请重试！';
			}
		}else{
			$result['is_err'] = 1;
			$result['reusult'] = '所填资料不全，请重试！';
		}
		echo json_encode($result);
		exit;
	}

	//删除
	public function del_announce(){
		$id = I('post,id');
		$map['anoceid'] = $id;
		if(D('Announce')->where($map)->delete()){
			$result['is_err'] = 0;
			$result['result'] = 'is_ok';
		}else{
			$result['is_err'] = 1;
			$result['result'] = '数据库错误，请重试！';
		}
		echo json_encode($result);
		exit;
	}

	public function detail(){
	
		$result = D()->table('__ANNOUNCE__ a')->join('__USER__ b')->join('__SCHOOL__ c')->field('a.file,a.title,a.content,a.createtime,b.userid,b.username,c.schname')->where('a.anoceid='.I('post.anoceid').' and a.userid=b.userid and b.schoolid=c.schoolid')->find();
		$result['createtime'] = date('Y-m-d H:i:s', $result['createtime']);
		//$result = stand_date($result);

        $data['userid'] = I('session.user')['userid'];
        $data['announceid'] = I('post.anoceid');
        if(!(D('Hint')->where($data)->find())){
            D('Hint')->add($data);
        }

		$response['result'] = $result;
		$response['is_err'] = 0;
		echo json_encode($response);
		exit;
	}

	/**
	 * elements
	 */



}
