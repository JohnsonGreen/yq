<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>天津大学舆情系统</title>
	<link href="/yq/Public/css/style.css" rel="stylesheet">
	<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
	<header>
		<span>天津大学舆情系统</span>
	</header>

	<!--移动端导航栏-->
	<div class="showing">
		<a href="javascript:void(0)" onclick="showMenu()"><img src="/yq/Public/images/menu.png" alt="click this to open the menu"></a>
		<!--记得到时候加上
		<a style="float: right;" href="javascript:void(0)" onclick="showAdmin()"><img src="/yq/Public/images/admin.png" align="click this to open the admin"></a>-->
	</div>

	<!--登录界面-->
	<form class="login" id="login" method="POST">
		<div class="inner">
			<strong>用户登录</strong>
			<img class="user_img" src="/yq/Public/images/user.png">
			<input class="user" type="text" name="username" placeholder="输入用户名">
			<img class="password_img" src="/yq/Public/images/lock.png">
			<input class="password" type="password" name="password" placeholder="输入密码">
			<button type="button" class="button" onclick="return sub()">登 录</button>
			<!--忘记密码的链接-->
			<span class="forget"></span>
		</div>
	</form>

	<!--导航栏自成体系-->
	<div class="navigation" style="display: none;">
			<div class="menu">
				
			</div>
			<div class="rank">
				<p>每日积分表</p>
				<p class="rank-first">
					<span>1</span>
				</p>
				<p class="rank-second">
					<span>2</span>
				</p>
				<p class="rank-third">
					<span>3</span>
				</p>
			</div>
		</div>
	
	<!--个人设置-->
	<div class="settings" style="display: none;">
		<form>
			<div style="background-color: #e5e5e5; width: 100%; height: 6%; padding-top: 4px; padding-bottom: 4px;"><span style="margin-left: 8%;">基本资料</span></div>
			<div class="settings-content"><span>账号</span></div>
			<div class="settings-content"><span>积分</span></div>
			<div class="settings-content"><span>真实姓名</span></div>
			<div class="settings-content"><span>生日</span></div>
			<div class="settings-content"><span>籍贯</span></div>
			<div class="settings-content"><span>邮箱</span></div>
			<div class="settings-content"><span>所属单位</span></div>
			<div class="settings-content"><span>手机号码</span></div>
			<div style="background-color: #e5e5e5; width: 100%; height: 6%; padding-top: 4px; padding-bottom: 4px;"><span style="margin-left: 8%;">修改密码</span></div>
			<div class="settings-content"><span>原始密码</span></div>
			<div class="settings-content"><span>新密码</span></div>
			<div class="settings-content"><span>确认密码</span></div>
			<button type="submit" onclick="return ctrl.save()">保存</button>
		</form>
	</div>

	<!--详细情报-->
	<div class="details" style="display: none;">
		<div class="details-head">
			<span class="details-username correct">上报人：</span>
			<span class="details-schname">上报单位：</span>
			<span class="details-createtime">上报时间：</span>
			<span class="details-type">舆情类型：</span>
			<span><a href="">编辑</a><a style="margin-left: 5px;" href="">删除</a></span>
		</div>
		<div class="details-mark">
			<span class="details-base correct">报送分：</span>
			<span class="details-select">选用分：</span>
			<span class="details-approval">批示分：</span>
			<span class="details-warning">预警分：</span>
			<span class="details-quality">质量分：</span>
			<span class="details-special">专项分：</span>
			<span class="details-substract">减分：</span>
		</div>

		<div class="details-content">
			
		</div>

		<h3 style="margin-bottom: 5px; margin-left: 5px;">回复</h3>

		<textarea class="details-comments">
			
		</textarea>

		<button type="submit" onclick="return ctrl.comment()">发表</button>
	</div>

	<!--上报舆情-->
	<form class="send" method="POST" enctype="multipart/form-data" style="display: none;">
		<div class="send-items correct">
			<span>舆情类型</span>
			<select class="type" name="choose_type">
				<option value="全部" selected>全部</option>
			</select>
		</div>

		<div class="send-items correct">
			<span>舆情标题</span>
			<input type="text" name="otitle">
		</div>

		<div class="send-items correct">
			<span>舆情关键字</span>
			<input type="text" name="okeyword">
		</div>

		<div class="send-items correct">
			<span>来源网站</span>
			<input type="text" name="osource">
		</div>

		<div class="send-items correct">
			<span>来源网址</span>
			<input type="text" name="ourl">
		</div>

		<div class="send-items correct">
			<span>上传文件<span>
			<input type="text" id="a" readonly="readonly" />
			 <a href="javascript:void(0);" class="input">浏览
			  <input type="file" id="file">
			 </a><br>
    		<label style="font-size: 12px;">请上传txt,pdf,doc,jpeg,docx,jpg,png,jpg格式</label>
    		<script type="text/javascript">
			 	var input1 = document.getElementById("file");// 获得控件对象
			 	input1.onchange = inputPath; //当控件对象 input1 有变化时执行函数 inputPath
			 	function inputPath() {
			  		var input2 = document.getElementById("a"); // 获取 input 对象 input2
			  		input2.value=input1.value;  // 将控件 input1 的值赋给 input2 
				}
			</script> 
		</div>

		<div class="send-items correct">
			<span style="vertical-align: top;">编辑正文</span>
			<textarea>
				
			</textarea>
		</div>
		<button type="submit" onclick="return ctrl.send()">发布</button>
	</form>
	
	<!--首页-->
	<div class="contain" style="display: none;">

		<div class="news">
			<p>&nbsp;&nbsp;最新报送</p>
			<div class="news-content"></div>
		</div>

		<div class="public">
			<p>&nbsp;&nbsp;舆情公告</p>
			<div class="public-content"></div>
		</div>
	</div>

	<!--日期+搜索栏-->
	<div class="opinion-search" style="display: none;">
			<span>日期筛选</span>
			<input id="startDate" type="text" name="date1" onclick="startDate()">
			<span>至</span>
			<input id="endDate" type="text" name="date2" onclick="endDate()">
			<div class="keysearch">
				<span>关键字搜索</span>
				<input id="searchSomething" type="text" name="keywords">
				<div class="opinion-hide">
					<span>学院搜索</span>
					<select class="school" name="choose_school">
						<option value="全部" selected>全部</option>
					</select>
					<span>类别</span>
					<select class="type" name="choose_type">
						<option value="全部" selected>全部</option>
					</select>
				</div>
				<button>搜索</button>
			</div>
				<table id="i_am_a_table" border="0" cellpadding="0" cellspacing="0" width="180">
				<tr>
					<td id=cc></td>
				</tr>
			</table>
	</div>

	<!--舆情公告页-->
	<div class="opinion" style="display: none;">
		<div class="opinion-ctrl" style="width: 100%; height: 20px; font-size: 20px;">
			<span class="opinion-left" style="float: left; display: none;" onclick="opinionLeft()">&#8592;</span>
			<span class="opinion-right" style="float: right;" onclick="opinionRight()">&#8594;</span>
		</div>
		<div class="opinion-all">
			<div class="opinion-callback">回复</div>
			<div class="opinion-title">主题</div>
			<div class="opinion-author">作者</div>
			<div class="opinion-time">发表时间</div>
			<div class="opinion-operation">操作</div>
			<div class="opinion-content">
				
			</div>
			<div class="opinion-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
				<div class="opinion-output">
					<img src="/yq/Public/images/feedback_write.png">
					<a href="">发布公告</a>
				</div>
			</div>
		</div>
	</div>

	<!--单条信息报送页-->
	<div class="single-post" style="display: none;">
		<div class="single-ctrl" style="width: 100%; height: 20px; font-size: 20px;">
			<span class="single-left" style="float: left; display: none;" onclick="singleLeft()">&#8592;</span>
			<span class="single-right" style="float: right;" onclick="singleRight()">&#8594;</span>
		</div>
		<div class="single-all">
			<div class="single-number">编号</div>
			<div class="single-type">舆情类别</div>
			<div class="single-title">标题</div>
			<div class="single-mark">评分</div>
			<div class="single-unit">上报单位</div>
			<div class="single-time">发表时间</div>
			<div class="single-operation">操作</div>

			<div class="single-content">
				
			</div>
			<div class="single-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
				<div class="single-output">
					<img src="/yq/Public/images/feedback_write.png">
					<a href="">上报舆情</a>
				</div>
			</div>
		</div>
	</div>

	<!--综合信息报送页-->
	<div class="integrative-post" style="display: none;">
		<div class="integrative-ctrl" style="width: 100%; height: 20px; font-size: 20px;">
			<span class="integrative-left" style="float: left; display: none;" onclick="integrativeLeft()">&#8592;</span>
			<span class="integrative-right" style="float: right;" onclick="integrativeRight()">&#8594;</span>
		</div>
		<div class="integrative-all">
			<div class="integrative-number">编号</div>
			<div class="integrative-type">舆情类别</div>
			<div class="integrative-title">标题</div>
			<div class="integrative-mark">评分</div>
			<div class="integrative-unit">上报单位</div>
			<div class="integrative-time">发表时间</div>
			<div class="integrative-operation">操作</div>

			<div class="integrative-content">
				
			</div>
			<div class="integrative-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
				<div class="integrative-output">
					<img src="/yq/Public/images/feedback_write.png">
					<a href="">上报舆情</a>
				</div>
			</div>
		</div>
	</div>

	<!--管理用户页-->
	<div class="manager" style="display: none;">
		<form>
			<span>学院搜索</span>
			<select class="school" name="choose_school">
				<option value="全部" selected>全部</option>
			</select>

			<div class="manager-search">
				<span>账号</span>
				<input type="text" name="account">
				<button>搜索</button>
			</div>
		</form>

		<div class="manager-ctrl" style="width: 100%; height: 20px; font-size: 20px;">
			<span class="manager-left" style="float: left; display: none;" onclick="managerLeft()">&#8592;</span>
			<span class="manager-right" style="float: right;" onclick="managerRight()">&#8594;</span>
		</div>

		<div class="manager-all">
			<span class="manager-number">编号</span>
			<span class="manager-account">账号</span>
			<span class="manager-unit">单位</span>
			<span class="manager-attribute">权限属性</span>
			<span class="manager-realname">真实姓名</span>
			<span class="manager-operation">操作</span>

			<div class="manager-content">
			
			</div>

			<div class="manager-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
				<div class="manager-output">
					<a href="">+上报舆情</a>
				</div>
			</div>
		</div>
	</div>

	<!--我的收藏页-->
	<div class="collection" style="display: none;">
		<div class="collection-ctrl" style="width: 100%; height: 20px; font-size: 20px;">
			<span class="collection-left" style="float: left; display: none;" onclick="collectionLeft()">&#8592;</span>
			<span class="collection-right" style="float: right;" onclick="collectionRight()">&#8594;</span>
		</div>
		<div class="collection-all">
			<span class="collection-number">编号</span>
			<span class="collection-type">舆情类别</span>
			<span class="collection-title">标题</span>
			<span class="collection-mark">评分</span>
			<span class="collection-unit">上报单位</span>
			<span class="collection-time">发表时间</span>
			<span class="collection-click">点击量</span>
			<span class="collection-operation">操作</span>

			<div class="collection-content">
			
			</div>

			<div class="collection-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
			</div>
		</div>
	</div>

	<!--管理积分页-->
	<div class="managemark" style="display: none;">
		<form>
			<span>学院搜索</span>
			<select class="school" name="choose_school">
				<option value="全部" selected>全部</option>
			</select>
			<div class="managemark-secondchoose">
				<span>类别</span>
				<select class="type" name="choose_type">
					<option value="全部" selected>全部</option>
				</select>
				<button>搜索</button>
			</div>
		</form>

		<div class="managemark-ctrl" style="width: 100%; height: 20px; font-size: 20px;">
			<span class="managemark-left" style="float: left; display: none;" onclick="managemarkLeft()">&#8592;</span>
			<span class="managemark-right" style="float: right;" onclick="managemarkRight()">&#8594;</span>
		</div>
		<div class="managemark-all">
			<span class="managemark-number">编号</span>
			<span class="managemark-origin">来源</span>
			<span class="managemark-title">标题</span>
			<span class="managemark-total">总分</span>
			<span class="managemark-mark">评分</span>
			<span class="managemark-xuanyong">选用分</span>
			<span class="managemark-pishi">批示分</span>
			<span class="managemark-yujing">预警分</span>
			<span class="managemark-zhiliang">质量分</span>
			<span class="managemark-zhuanxiang">专项分</span>
			<span class="managemark-minus">减分</span>
			<span class="managemark-operation">操作</span>

			<div class="managemark-content">
				
			</div>

			<div class="managemark-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
			</div>
		</div>
	</div>

	<!--管理关键字页-->
	<div class="managekeyword" style="display: none;">
		<div class="managekeyword-all">
			<span class="managekeyword-number">序号</span>
			<span class="managekeyword-keyword">关键字</span>
			<span class="managekeyword-time">时间</span>
			<span class="managekeyword-operation">操作</span>

			<div class="managekeyword-content"></div>

			<div class="managekeyword-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
			</div>
		</div>
	</div>

	<!--积分列表页-->
	<div class="marklist" style="display: none;">
		<div class="marklist-all">
			<span class="marklist-rank">排行</span>
			<span class="marklist-school">学院</span>
			<span class="marklist-total">总分</span>
			<span class="marklist-detail">详情</span>

			<div class="marklist-content"></div>

			<div class="marklist-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
			</div>
		</div>
	</div>

	<!--在线人数页-->
	<div class="online" style="display: none;">
		<div class="online-all">
			<span class="online-number">编号</span>
			<span class="online-account">账号</span>
			<span class="online-school">学院</span>
			<span class="online-type">权限属性</span>
			<span class="online-realname">真实姓名</span>

			<div class="online-content"></div>

			<div class="online-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
			</div>
		</div>
	</div>

	<!--日志页-->
	<div class="log" style="display: none;">
		<div class="log-all">
			<span class="log-number">编号</span>
			<span class="log-admin">用户名</span>
			<span class="log-ip">登录IP</span>
			<span class="log-time">时间</span>

			<div class="log-content"></div>

			<div class="log-page">
				<a href="">首页</a>
				<a href="">上一页</a>
				<span>1</span>
				<a href="">下一页</a>
				<a href="">末页</a>
			</div>
		</div>
	</div>

	<!--底页-->
	<footer>
		<span>&copy;天津大学舆情系统</span>
	</footer>
	<script>
		  var root = 'index.php/';
	</script>
	<script type="text/javascript" src="/yq/Public/js/jQuery.js"></script>
	<script type="text/javascript" src="/yq/Public/js/script.js"></script>
	<script type="text/javascript">
		//验证表单
		function sub() {
			//使用人物权限
			function person() {
				var identity = "";
				this.getIdentity = function() {
					return identity;
				}
				this.setIdentity = function(index) {
					identity = index;
				}
			}

			$.ajax({
				url:   "Index/login",
				type: "POST",
				//datatype: "json",
				data: $("#login").serialize(),

				success: function(json) {
					//登录失败是需要渲染的函数
					if(json.is_err == 1) {
						$(".forget").text("错误的用户名或密码!");
					}

					//登录成功时需要渲染的函数
					if(json.is_err == 0) {
						$(".navigation").css("display", "block");
						$(".showing").css("display", "block");
						$(".login").css("display", "none");
						$(".contain").css("display", "block");
						//model.menu = [];
						//ctrl.getMenu();
						ctrl.getInformation();
						for(var i = 0; i < json.length; i++) {
							model.menu[i] = json[i].permname;
						} 
					}
				}
			})	


		}
		//移动端推拉菜单栏
		function showMenu() {
			var k = $(".navigation");
			if(k.css('left') == "0px")
				k.css('left', "-120px");
			else
				k.css('left', "0px");
		}
		//移动端推拉用户栏
		//function showAdmin() {
		//
		//}
	</script>
	<script type="text/javascript" src="/yq/Public/js/arrowctrl.js"></script>
	<script type="text/javascript" src="/yq/Public/js/calendar.js">
		//生成日历的脚本
	</script>
	<script type="text/javascript">
		//生成日历的脚本控制
		var The_Year,The_Day,The_Month;
		var today;
		var Firstday;
		today = new Date();
		The_Year = today.getYear();
		The_Month = today.getMonth() + 1;
		The_Day = today.getDate();
		Firstday = GetWeekday(The_Year,The_Month);
		ShowCalender(The_Year,The_Month,The_Day,Firstday);
	</script>
</body>
</html>