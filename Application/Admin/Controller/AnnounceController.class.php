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
            'announce_details'=>'Admin/Announce/detail',
			'announce_update'=>'Admin/Announce/update_announce',
			'announce_stick'=>'Admin/Announce/stick',
			'announce_del'=>'Admin/Announce/del',
			'add_file'=>'Admin/Index/add_file'
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

       if($date1 && $date2){
            $map['yq_announce.createtime'] = array(array('gt', $date1), array('lt', $date2  + 3600000*24));
        }
//		cout($_POST);
		$key = I('post.keywords');
		if($key)
			$map['title'] = array('like', '%'.$key.'%');

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
		$id = I('post.id');
		$data['anoceid'] = $id;
		$data['stick'] = 1;
		$data['yq_announce.updatetime']=time();


		if(D('Announce')->save($data)){
			$result['is_err'] = 0;
			$p = I('post.page');
			if(empty($p))
				$p = 1;
			$announcement = D('Announce')->getData($p, null);
			$result['result'] = $announcement;
			$result['max_page'] = D('Announce')->getMaxPage(null);
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
		$max_title_length = 20;

		$data['userid'] = $_SESSION['user']['userid'];
		$data['title'] = trim(I('post.title'));
		$data['content'] = trim(I('post.content'));

		$data['creattime'] = time();

		if($data['title'] && $data['content']){
			if(strlen($data['title']) > $max_title_length ){
				$result['is_err'] = 1;
				$result['reusult'] = '标题太长，请重试！';
			}else{
				$anoceid = D('Announce')->add($data);
				if($anoceid){
					$result['is_err'] = 0;
					$result['result'] = "推送成功！";
					$result['newid'] = $anoceid;
				}else{
					$result['is_err'] = 0;
					$result['result'] = "数据库错误，请重试！";
				}

			}
		}
		echo json_encode($result);
		exit;

	}

	//编辑
	public function update_announce(){
		$data['userid'] = I('session.user')['userid'];
		$data['anoceid'] = I('post.announceid');
		$data['content'] = trim(I('post.content'));

		if($data['userid'] && $data['content']){
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
	public function del(){
		$id = I('post.id');
		$map['anoceid'] = $id;
		if(D('Announce')->where($map)->delete()){
			$result['is_err'] = 0;
			$p = I('post.page');
			if(empty($p))
				$p = 1;
			$announcement = D('Announce')->getData($p, null);
			$result['result'] = $announcement;
			$result['max_page'] = D('Announce')->getMaxPage(null);
		}else{
			$result['is_err'] = 1;
			$result['result'] = '数据库错误，请重试！';
		}
		echo json_encode($result);
		exit;
	}

	public function detail(){
	
		$result = D()->table('__ANNOUNCE__ a')->join('__USER__ b')->join('__SCHOOL__ c')->field('a.anoceid,a.file,a.title,a.content,a.createtime,b.userid,b.username,c.schname')->where('a.anoceid='.I('post.anoceid').' and a.userid=b.userid and b.schoolid=c.schoolid')->find();
		$result['createtime'] = date('Y-m-d H:i:s', $result['createtime']);
		//$result = stand_date($result);

        $data['userid'] = I('session.user')['userid'];
        $data['anoceid'] = I('post.anoceid');

		$response['is_err'] = 0;

        if(!(D('Hint')->where($data)->find())){

            $judge = D('Hint')->add($data);
			if($judge)
				$response['is_err'] = 0;
			else{
				$response['is_err'] = 1;
			}
        }

		$response['result'] = $result;

		echo json_encode($response);
		exit;
	}

	/**
	 * elements
	 */



}
