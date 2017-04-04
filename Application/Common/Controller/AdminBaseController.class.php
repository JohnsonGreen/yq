<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * admin 基类控制器
 */
class AdminBaseController extends BaseController{ 
	/**
	 * 初始化方法
	 */
	public function _initialize(){
		parent::_initialize();
		$auth=new Auth();
		$rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		$result=$auth->check($rule_name,$_SESSION['user']['userid']);

		if(!$result){
//            echo json_encode(array(
//                'is_err' => '1',
//                'result' => '您没有权限访问'
//            ));
//            exit;
            //$this->error('您没有权限访问');
		}
	}
}


//权限控制类
class Auth
{

    //默认配置
    protected $_config = array(
        'AUTH_ON'           => true, // 认证开关
        'AUTH_TYPE'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'AUTH_GROUP'        => 'group', // 用户组数据表名
        'AUTH_GROUP_ACCESS' => 'user_group', // 用户-用户组关系表
        'AUTH_RULE'         => 'permission', // 权限规则表
        'AUTH_GROUP_RULE'   => 'group_permission', // 用户组-权限规则关联表
        'AUTH_USER'         => 'user', // 用户信息表
    );

    public function __construct()
    {
        $prefix                             = C('DB_PREFIX');
        $this->_config['AUTH_GROUP']        = $prefix . $this->_config['AUTH_GROUP'];
        $this->_config['AUTH_RULE']         = $prefix . $this->_config['AUTH_RULE'];
        $this->_config['AUTH_USER']         = $prefix . $this->_config['AUTH_USER'];
        $this->_config['AUTH_GROUP_ACCESS'] = $prefix . $this->_config['AUTH_GROUP_ACCESS'];
        $this->_config['AUTH_GROUP_RULE'] = $prefix . $this->_config['AUTH_GROUP_RULE'];
        if (C('AUTH_CONFIG')) {
            //可设置配置项 AUTH_CONFIG, 此配置项为数组。
            $this->_config = array_merge($this->_config, C('AUTH_CONFIG'));
        }
    }

    /**
     * 检查权限
     * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param uid  int           认证用户的id
     * @param string mode        执行check的模式
     * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid, $type = 1, $mode = 'url', $relation = 'or')
    {
        if (!$this->_config['AUTH_ON']) {
            return true;
        }

        $authList = $this->getAuthList($uid, $type); //获取用户需要验证的所有有效规则列表
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        $list = array(); //保存验证通过的规则名
        if ('url' == $mode) {    //将$_REQUEST数组中的大写字母全部变成小写字母
            $REQUEST = unserialize(strtolower(serialize($_REQUEST)));
        }
        foreach ($authList as $auth) {
          if(in_array($auth, $name)) {
                $list[] = $auth;
           }
        }
        if ('or' == $relation and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ('and' == $relation and empty($diff)) {
            return true;
        }
        return false;
    }

    /**
     * 根据用户id获取用户组,返回值为数组
     * @param  uid int     用户id
     * @return array       用户所属的用户组 array(
     *     array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *     ...)
     */
    public function getGroups($uid)
    {
        static $groups = array();
        if (isset($groups[$uid])) {
            return $groups[$uid];
        }

        $user_groups = M()
            ->table($this->_config['AUTH_GROUP_ACCESS'] . ' a')
            ->where('a.userid='.$uid)
            ->join($this->_config['AUTH_GROUP'] . " g ON a.groupid=g.groupid")
            ->field('a.userid,a.groupid,g.groupname')->select();
        $groups[$uid] = $user_groups ?: array();
        return $groups[$uid];
    }

    /**
     * 根据用户组获取用户组的权限
     * @param $groupid
     * @return mixed
     */
    public function getRules($groupid)
    {
        static $rules = array();
        if (isset($rules[$groupid])) {
            return $rules[$groupid];
        }

        $group_rules = M()
            ->table($this->_config['AUTH_GROUP_RULE'] . ' a')
            ->where("a.groupid='$groupid'")
            ->join($this->_config['AUTH_RULE'] . " p on a.permid=p.permid")
            ->field('a.permid,a.groupid,p.permname,p.action')->select();
        $rules[$groupid] = $group_rules ?: array();
        return $rules[$groupid];
    }

    /**
     * 获得权限列表
     * @param integer $uid  用户id
     * @param integer $type
     */
    protected function getAuthList($uid, $type)
    {
        static $_authList = array(); //保存用户验证通过的权限列表
        $t                = implode(',', (array) $type);
        if (isset($_authList[$uid . $t])) {
            return $_authList[$uid . $t];
        }
        if (2 == $this->_config['AUTH_TYPE'] && isset($_SESSION['_AUTH_LIST_' . $uid . $t])) {
            return $_SESSION['_AUTH_LIST_' . $uid . $t];
        }

        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids    = array(); //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $grouprules = $this->getRules($g['groupid']);
            foreach ($grouprules as $val)
                $ids[] = $val['permid'];
        }



        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid . $t] = array();
            return array();
        }

        $map = array(
            'permid'     => array('in', $ids)
        );
        //读取用户组所有权限规则
        $rules = M()->table($this->_config['AUTH_RULE'])->where($map)->field('action')->select();

        //循环规则，判断结果。
        $authList = array(); //
        foreach ($rules as $rule) {
            $authList[] = strtolower($rule['action']);
        }

        $_authList[$uid . $t] = $authList;
        if (2 == $this->_config['AUTH_TYPE']) {
            //规则列表结果保存到session
            $_SESSION['_AUTH_LIST_' . $uid . $t] = $authList;
        }
        return array_unique($authList);
    }

}

