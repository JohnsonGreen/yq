<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>天津大学学工信系统</title>
    <link href="/yq/Public/css/style.css?<?php echo rand(3000,4000); ?>" rel="stylesheet">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<header>
    <span>天津大学学工信系统</span>
</header>

<!--移动端导航栏-->
<div class="showing">
    <a href="javascript:void(0)" onclick="showMenu()"><img src="/yq/Public/images//menu.png"
                                                           alt="click this to open the menu"></a>
    <div class="person" style="display: none;">
        <img style="position: relative; top: 5px;" src="/yq/Public/images//user.png">
        <span class="person-identity"></span>&nbsp;|&nbsp;
        <a class="logout" onclick="return logout()">注销</a>&nbsp;|&nbsp;
        <a class="person-setting" onclick="return ctrl.setting(model.identity.userid)">个人设置</a>
        &nbsp;|&nbsp;积分<span class="person-mark"></span>
    </div>
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
        <p>&nbsp;&nbsp;每日积分表</p>
        <p class="rank-first">
            &nbsp;&nbsp;&nbsp;<span>1</span>
        </p>
        <p class="rank-second">
            &nbsp;&nbsp;&nbsp;<span>2</span>
        </p>
        <p class="rank-third">
            &nbsp;&nbsp;&nbsp;<span>3</span>
        </p>
    </div>
</div>



<!--个人设置-->
<div class="settings" style="display: none;">
    <form id="settings">
        <div style="background-color: #e5e5e5; width: 100%; height: 6%; padding-top: 4px; padding-bottom: 4px;"><span style="margin-left: 8%;">基本资料</span></div>
        <div class="settings-content"><span>账号</span><span style="height: 20px; width: 40%; margin-right: 35%; float: right;" class="settings-account"></span></div>
        <div class="settings-content"><span>积分</span><span style="height: 20px; width: 40%; margin-right: 35%; float: right;" class="settings-mark"></span></div>
        <div class="settings-content"><span>真实姓名</span><input class="settings-realname settings-input" type="text" name="realname"></div>
        <div class="settings-content"><span>邮箱</span><input class="settings-mail settings-input" type="text" name="email"></div>
        <div class="settings-content"><span>所属单位</span>
            <select style="float: right; margin-right: 35%; width: 40%;" class="school" name="school">
                <option value="0">全部</option>
            </select>
        </div>
        <div class="settings-content"><span>手机号码</span><input class="settings-phone settings-input" type="text" name="phone"></div>
        <div style="background-color: #e5e5e5; width: 100%; height: 6%; padding-top: 4px; padding-bottom: 4px;"><span style="margin-left: 8%;">修改密码</span></div>
        <div class="settings-content"><span>原始密码</span><input class="originpassword settings-input" type="password" name="originpassword"></div>
        <div class="settings-content"><span>新密码</span><input class="newpassword settings-input" type="password" name="password"></div>
        <div class="settings-content"><span>确认密码</span><input class="confirmpassword settings-input" type="password" name="confirmpassword"></div>
        <button type="button" onclick="return ctrl.save()">保存</button>
    </form>
</div>

<!--详细情报-->
<div class="details" style="display: none;">
    <div class="details-head">
        <span class="details-username correct">上报人：</span>
        <span class="details-schname">上报单位：</span>
        <span class="details-createtime">上报时间：</span>
        <span class="details-type">舆情类型：</span>
    </div>
    <div class="details-mark">
        <span class="details-base correct">报送分：</span>
        <span class="details-add">加分：</span>
        <span class="details-substract">减分：</span>
    </div>

    <div class="details-content">

    </div>

    <h3 style="margin-bottom: 5px; margin-left: 5px;">回复</h3>

		<textarea class="details-comments">

		</textarea>
    <button style="float: left;" type="button" onclick="return view.back(model.lastpage)">返回</button>
    <button type="button" onclick="return ctrl.comment()">发表</button>
</div>

<!--上报舆情-->
<form id="send" class="send single-send" enctype="multipart/form-data" style="display: none;">
    <div class="send-product send-items correct">
        <span>产品类型</span>
        <select onchange="return toggleProduct()" class="product" name="pronameid">
            <option value="2">舆情专报</option>
            <option value="3">舆情扫描</option>
        </select>
    </div>

    <div class="send-type send-items correct">
        <span>舆情类型</span>
        <select class="type" name="typeid">
        </select>
    </div>

    <div class="send-title send-items correct">
        <span>舆情标题</span>
        <input type="text" name="title"/>
    </div>

    <div class="send-key send-items correct">
        <span>舆情关键字</span>
        <input type="text" name="keyword"/>
    </div>

    <div class="send-webpage send-items correct">
        <span>来源网站</span>
        <input type="text" name="source"/>
    </div>

    <div class="send-website send-items correct">
        <span>来源网址</span>
        <input type="text" name="url"/>
    </div>

    <div class="send-items correct">
        <span>上传文件</span>
        <input type="text" id="a" readonly="readonly"/>
        <a href="javascript:void(0);" class="input">浏览
            <input type="file" id="file" name="file"/>
        </a><br>
        <label style="font-size: 12px;">请上传txt,pdf,doc,jpeg,docx,jpg,png,jpg格式</label>
        <script type="text/javascript">
            var input1 = document.getElementById("file");// 获得控件对象
            input1.onchange = inputPath; //当控件对象 input1 有变化时执行函数 inputPath
            function inputPath() {
                var input2 = document.getElementById("a"); // 获取 input 对象 input2
                input2.value = input1.value;  // 将控件 input1 的值赋给 input2
            }
        </script>
    </div>

    <div class="send-items correct">
        <span style="vertical-align: top;">编辑正文</span>
        <textarea name="content">

        </textarea>
    </div>
    <button type="button" onclick="return ctrl.singlesend()">发布</button>
</form>

<!--添加用户-->
<form id="add" class="add" method="POST" style="display: none;">
    <div style="background-color: #e5e5e5; width: 100%; height: 6%; padding-top: 4px; padding-bottom: 4px;"><span
            style="margin-left: 8%;">基本资料</span></div>
    <div class="settings-content"><span>账号</span><input class="add-account settings-input" type="text" name="username">
    </div>
    <div class="settings-content"><span>真实姓名</span><input class="add-realname settings-input" type="text"
                                                          name="realname"></div>
    <div class="settings-content"><span>邮箱</span><input class="add-mail settings-input" type="text" name="email"></div>
    <div class="settings-content"><span>所属单位</span>
        <select style="float: right; margin-right: 35%; width: 40%;" class="school" name="schoolid">

        </select>
    </div>
    <div class="settings-content"><span>用户身份</span>
        <select style="float: right; margin-right: 35%; width: 40%;" class="user_identity" name="groupid">

        </select>
    </div>
    <div class="settings-content"><span>手机号码</span><input class="add-phone settings-input" type="text" name="phone">
    </div>
    <div style="background-color: #e5e5e5; width: 100%; height: 6%; padding-top: 4px; padding-bottom: 4px;"><span
            style="margin-left: 8%;">设置密码</span></div>
    <div class="settings-content"><span>密码</span><input class="newpassword settings-input" type="password"
                                                        name="password"></div>
    <div class="settings-content"><span>确认密码</span><input class="confirmpassword settings-input" type="password"
                                                          name="confirm_password"></div>
    <button type="button" onclick="return ctrl.add()">保存</button>
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
            <select class="school" name="school">
                <option value="0" selected>全部</option>
            </select>
            <span>类别</span>
            <select class="type" name="type">
                <option value="0" selected>全部</option>
            </select>
        </div>
        <button type="button" onclick="return search()">搜索</button>
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
            <a onclick="return pubFirstPage()">首页</a>
            <a onclick="return pubFrontPage()">上一页</a>
            <span id="pub-currentPage">1</span>
            <a onclick="return pubNextPage()">下一页</a>
            <a onclick="return pubMaxPage()">末页</a>
            <div class="opinion-output">
                <img src="/yq/Public/images/feedback_write.png">
				<a style="cursor: pointer;" onclick="return public()">发布公告</a>
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
            <a onclick="return singleFirstPage()">首页</a>
            <a onclick="return singleFrontPage()">上一页</a>
            <span id="single-currentPage">1</span>
            <a onclick="return singleNextPage()">下一页</a>
            <a onclick="return singleMaxPage()">末页</a>
            <div class="single-output">
                <img src="/yq/Public/images/feedback_write.png">
                <a style="cursor: pointer;" onclick="return singlesend()">上报舆情</a>
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
        <div class="integrative-product">产品类型</div>
        <div class="integrative-title">标题</div>
        <div class="integrative-mark">评分</div>
        <div class="integrative-unit">上报单位</div>
        <div class="integrative-time">发表时间</div>
        <div class="integrative-operation">操作</div>

        <div class="integrative-content">

        </div>
        <div class="integrative-page">
            <a onclick="return integrativeFirstPage()">首页</a>
            <a onclick="return integrativeFrontPage()">上一页</a>
            <span id="integrative-currentPage">1</span>
            <a onclick="return integrativeNextPage()">下一页</a>
            <a onclick="return integrativeMaxPage()">末页</a>
            <div class="integrative-output">
                <img src="/yq/Public/images/feedback_write.png">
                <a style="cursor: pointer;" onclick="return integrativesend()">上报舆情</a>
            </div>
        </div>
    </div>
</div>

<!--管理用户页-->
<div class="manager" style="display: none;">
    <form>
        <span>学院搜索</span>
        <select class="school" name="school">
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
            <a onclick="return managerFirstPage()">首页</a>
            <a onclick="return managerFrontPage()">上一页</a>
            <span id="manager-currentPage">1</span>
            <a onclick="return managerNextPage()">下一页</a>
            <a onclick="return managerMaxPage()">末页</a>
            <div class="manager-output">
                <a style="cursor: pointer;" onclick="return add()">+添加用户</a>
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
            <a onclick="return collectionFirstPage()">首页</a>
            <a onclick="return collectionFrontPage()">上一页</a>
            <span id="collection-currentPage">1</span>
            <a onclick="return collectionNextPage()">下一页</a>
            <a onclick="return collectionMaxPage()">末页</a>
        </div>
    </div>
</div>

<!--管理积分页-->
<div class="managemark" style="display: none;">
    <form id="marksearch">
        <span>学院搜索</span>
        <select id="markschool" class="school" name="schoolid">
            <option value="0" selected>全部</option>
        </select>
        <div class="managemark-secondchoose">
            <span>类别</span>
            <select id="marktype" class="type" name="typeid">
                <option value="0" selected>全部</option>
            </select>
            <button type="button" onclick="return marksearch()">搜索</button>
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
        <span class="managemark-add">加分</span>
        <span class="managemark-minus">减分</span>
        <span class="managemark-operation">操作</span>

        <div class="managemark-content">

        </div>

        <div class="managemark-page">
            <a onclick="managemarkFirstPage()">首页</a>
            <a onclick="managemarkFrontPage()">上一页</a>
            <span id="managemark-currentPage">1</span>
            <a onclick="managemarkNextPage()">下一页</a>
            <a onclick="managemarkMaxPage()">末页</a>
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
            <a onclick="keyFirstPage()">首页</a>
            <a onclick="keyFrontPage()">上一页</a>
            <span id="key-currentPage">1</span>
            <a onclick="keyNextPage()">下一页</a>
            <a onclick="keyMaxPage()">末页</a>
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
            <a onclick="return listFirstPage()">首页</a>
            <a onclick="return listFrontPage()">上一页</a>
            <span id="list-currentPage">1</span>
            <a onclick="return listNextPage()">下一页</a>
            <a onclick="return listMaxPage()">末页</a>
        </div>
    </div>
</div>

<!--查看学院报送详情页-->
<div class="school-detail" style="display: none;">

    <div class="schdet-ctrl" style="width: 100%; height: 20px; font-size: 20px;">
        <span class="schdet-left" style="float: left; display: none;" onclick="schdetLeft()">&#8592;</span>
        <span class="schdet-right" style="float: right;" onclick="schdetRight()">&#8594;</span>
    </div>
    <div class="schdet-all">
        <span class="schdet-number">编号</span>
        <span class="schdet-type">舆情类别</span>
        <span class="schdet-send">报送类别</span>
        <span class="schdet-title">标题</span>
        <span class="schdet-mark">评分</span>
        <span class="schdet-person">上报人</span>
        <span class="schdet-time">发表时间</span>
        <span class="schdet-operation">操作</span>

        <div class="schdet-content"></div>

        <div class="schdet-page">
            <a class="front" onclick="return view.front()">返回上一级</a>
            <a onclick="return schdetFirstPage()">首页</a>
            <a onclick="return schdetFrontPage()">上一页</a>
            <span id="schdet-currentPage">1</span>
            <a onclick="return schdetNextPage()">下一页</a>
            <a onclick="return schdetMaxPage()">末页</a>
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
            <a onclick="return onlineFirstPage()">首页</a>
            <a onclick="return onlineFrontPage()">上一页</a>
            <span id="online-currentPage">1</span>
            <a onclick="return onlineNextPage()">下一页</a>
            <a onclick="return onlineMaxPage()">末页</a>
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
            <a onclick="return logFirstPage()">首页</a>
            <a onclick="return logFrontPage()">上一页</a>
            <span id="log-currentPage">1</span>
            <a onclick="return logNextPage()">下一页</a>
            <a onclick="return logMaxPage()">末页</a>
        </div>
    </div>
</div>



<!--底页-->
<footer>
    <span>&copy;天津大学学工信系统</span>
</footer>
<script type="text/javascript" src="/yq/Public/js/jQuery.js"></script>
<script>
    var login = "<?php echo U('Home/Index/login');?>";
    var ImgPath = "/yq/Public/images/";
    var schools_url = "<?php echo U('Home/Index/school');?>";
    var types_url = "<?php echo U('Home/Index/type');?>";
</script>
<!-- ?<?php echo rand(3000,4000); ?> -->
<script type="text/javascript" src="/yq/Public/js/script.js?<?php echo rand(3000,4000); ?>"></script>
<script type="text/javascript">
    //验证表单
    function sub() {
        $.ajax({
            url: login,
            type: "POST",
            datatype: "json",
            data: $("#login").serialize(),

            success: function (json) {
                //登录失败是需要渲染的函数
                if (json.is_err == 1) {
                    $(".forget").text("错误的用户名或密码!");
                }

                //登录成功时需要渲染的函数
                if (json.is_err == 0) {
                    $("body").css("background-image", "none");
                    model.identity = json.result;
                    $(".navigation").css("display", "block");
                    $(".showing").css("display", "block");
                    $(".login").css("display", "none");
                    $(".contain").css("display", "block");
                    $(".person").css("display", "block");
                    ctrl.getMenu();
                    ctrl.getInformation();
                }
            }
        })
    }
    //注销用户
    function logout() {
        $.ajax({
            url: model.identity.root + model.identity.logout,
            type: "POST",
            success: function () {
                alert("注销成功！");
                location.reload();
            }
        })
    }
    //移动端推拉菜单栏
    function showMenu() {
        var k = $(".navigation");
        if (k.css('left') == "0px")
            k.css('left', "-120px");
        else
            k.css('left', "0px");
    }
    //上报舆情按钮
    function singlesend() {
        $(ctrl.getCurrentClass()).css("display", "none");
        $(".single-send").css("display", "block");
        $(".opinion-search").css("display", "none");
        $(".send-product").css("display", "none");
        $(".send-type").css("display", "block");
        $(".send-key").css("display", "block");
        $(".send-website").css("display", "block");
        $(".send-webpage").css("display", "block");
        $(".send-type").css("display", "block");
    }
    function integrativesend() {
        $(ctrl.getCurrentClass()).css("display", "none");
        $(".single-send").css("display", "block");
        $(".opinion-search").css("display", "none");
        $(".send-product").css("display", "block");
        $(".send-type").css("display", "block");
        $(".send-key").css("display", "none");
        $(".send-website").css("display", "none");
        $(".send-webpage").css("display", "none");
    }
    function add() {
        $(ctrl.getCurrentClass()).css("display", "none");
        $(".add").css("display", "block");
        $(".opinion-search").css("display", "none");
        //添加身份选取菜单
        $.ajax({
            url: model.identity.root + model.manager.groups_api,
            type: "GET",
            datatype: "json",
            success: function (json) {
                if (json.is_err == 0) {
                    $(".user_identity").empty();
                    for (var i = 0; i < json.result.length; i++) {
                        $(".user_identity").append('<option value="' + json.result[i].groupid + '">' + json.result[i].groupname + '</option>');
                    }
                }
                else
                    alert(json.result);
            }
        })
    }
      //管理员发布公告
		function public() {
			integrativesend();
			$(".send-product").css("display", "none");
			$(".send-type").css("display", "none");
		}
    //改变产品类型时需要改变的舆情类型是否显示
    function toggleProduct() {
        var temp = $(".product").val();
        if (temp == 2)
            $(".send-type").css("display", "block");
        if (temp == 3)
            $(".send-type").css("display", "none");
    }

    function marksearch(){
        $.ajax({
            url: model.identity.root + model.managemark.url.magscore_search,
            type: "POST",
            data: $("#marksearch").serialize(),

            success: function(json) {
                model.managemark.max_page = json.max_page;
                model.currentPage = 1;
                view.showManagemark(json);
            }
        })
    }

    function search() {
        var s = ctrl.getCurrentClass();
        console.log(s);
        if(s == ".single-post") {
            $.ajax({
                url: model.identity.root + model.single.url.single_search,
                type: "POST",
                data: {
                    date1: $("#startDate").val(),
                    date2: $("#endDate").val(),
                    keywords: $("#searchSomething").text(),
                    school: $(".school").eq(2).find("option:selected").text(),
                    type: $(".type").eq(1).find("option:selected").text()
                },

                success: function(json) {
                    model.single.max_page = json.max_page;
                    model.currentPage = 1;
                    view.showSingle(json);
                }
            })
        } else if (s == ".integrative-post") {
            $.ajax({
                url: model.identity.root + model.integrative.url.overall_search,
                type: "POST",
                data: {
                    date1: $("#startDate").val(),
                    date2: $("#endDate").val(),
                    keywords: $("#searchSomething").val(),
                    school: $(".school").eq(2).find("option:selected").text(),
                    type: $(".type").eq(1).find("option:selected").text()
                },

                success: function(json) {
                    model.integrative.max_page = json.max_page;
                    model.currentPage = 1;
                    view.showIntegrative(json);
                }
            })
        } else if(s == ".opinion") {
            $.ajax({
                url: model.identity.root + model.pub.url.announce_search,
                type: "POST",
                data: {
                    date1: $("#startDate").val(),
                    date2: $("#endDate").val(),
                    keywords: $("#searchSomething").text(),
                    school: $(".school").eq(2).find("option:selected").text(),
                    type: $(".type").eq(1).find("option:selected").text()
                },

                success: function(json) {
                    model.pub.max_page = json.max_page;
                    model.currentPage = 1;
                    view.showPub(json);
                }
            })
        } else if(s == ".collection") {
            $.ajax({
                url: model.identity.root + model.collection.url.collect_search,
                type: "POST",
                data: {
                    date1: $("#startDate").val(),
                    date2: $("#endDate").val(),
                    keywords: $("#searchSomething").val(),
                    school: $(".school").eq(2).find("option:selected").text(),
                    type: $(".type").eq(1).find("option:selected").text()
                },

                success: function(json) {
                    model.collection.max_page = json.max_page;
                    model.currentPage = 1;
                    view.showCollection(json);
                }
            })
        }
    }
</script>
<script type="text/javascript" src="/yq/Public/js/arrowctrl.js?<?php echo rand(3000,4000); ?>"></script>
<script type="text/javascript" src="/yq/Public/js/calendar.js?<?php echo rand(3000,4000); ?>"></script>
<script type="text/javascript" src="/yq/Public/js/pageProcess.js?<?php echo rand(3000,4000); ?>">
</script>


<script type="text/javascript">
//生成日历的脚本控制
var The_Year, The_Day, The_Month;
var today;
var Firstday;
today = new Date();
The_Year = today.getYear();
The_Month = today.getMonth() + 1;
The_Day = today.getDate();
Firstday = GetWeekday(The_Year, The_Month);
ShowCalender(The_Year, The_Month, The_Day, Firstday);
</script>
</body>
</html>