/**
 * Created by GuoRenjie on 2017/4/14.
 */
$(document).ready(function() {

    $("body").css("background-image", "url("+ImgPath+"login.png)");

    $.ajax({
        url: login ,
        type: "GET",
        datatype: "json",

        success: function(json) {
            //登录成功时需要渲染的函数
            if(json.is_err == 0) {
                model.identity = json.result;
                $("body").css("background-image", "none");
                $(".person").css("display", "block");
                $(".navigation").css("display", "block");
                $(".showing").css("display", "block");
                $(".login").css("display", "none");
                $(".contain").css("display", "block");
                ctrl.getMenu();
                ctrl.getInformation();
            }
        }
    })

    //获得学院名称
    $.ajax({
        url: schools_url,
        type: "GET",
        datatype: "json",

        success: function(json) {
            for(var i = 0; i < json.length; i++) {
                $(".school").append('<option value="'+json[i].schoolid+'">'+json[i].schname+'</option>');
            }
        }
    })

    //获得类型名称
    $.ajax({
        url: types_url,
        type: "GET",
        datatype: "json",

        success: function(json) {
            for(var i = 0; i < json.length; i++) {
                $(".type").append('<option value="'+json[i].typeid+'">'+json[i].type+'</option>');
            }
        }
    })
})

var model = {
    //新点击时渲染
    currentPage: 1,
    identity: {},
    pub: {url:"",max_page:""},
    single: {url:"",max_page:""},
    integrative: {url:"",max_page:""},
    manager: {add_url:"",groups_api:""},
    collection: {url:"",max_page:""},
    managemark: {url:"",max_page:""},
    key: {url:"",max_page:""},
    marklist: {url:"",max_page:""},
    online: {url:"",max_page:""},
    log: {url:"",max_page:""},
    //菜单的内容，用数组表示
    menu: ["首页", "舆情公告", "单条信息报送", "综合信息报送", "管理用户", "我的收藏", "管理积分", "管理关键字", "积分列表", "在线人数", "日志"],
    ranks: ["化工学院", "机械学院", "建工学院"],
}

//判断数据是否为空
function isNull(data){
    return (data == "" || data == undefined || data == null) ? true : false;
}

var view = {
    //初始化渲染菜单界面
    showMenu: function() {
        for(var i = 0; i < model.menu.length; i++) {
            $(".menu").append('<li class="menu-items" onclick="return ctrl.getTitle('+i+')">'+'<span>'+model.menu[i]+'</span>'+'</li>');
        }
    },
    //渲染前三的排名
    showRank: function() {
        $(".rank-first").append('<i style="color:black; font-size:14px; font-style: normal;">'+model.ranks[0]+'</i>');
        $(".rank-second").append('<i style="color:black; font-size:14px; font-style: normal;">'+model.ranks[1]+'</i>');
        $(".rank-third").append('<i style="color:black; font-size:14px; font-style: normal;">'+model.ranks[2]+'</i>');
    },

    transform: function() {
        $(ctrl.getCurrentClass()).css("display", "none");
        $(".details").css("display", "block");
        $(".opinion-search").css("display", "none");
    },

    lookArticle: function() {
        $("#article1").css("display", "block");
        $("#article2").css("display", "none");
    },

    editArticle: function() {
        $("#article1").css("display", "none");
        $("#article2").css("display", "block");
    },

    showPub: function(json) {
        $("#pub-currentPage").text(model.currentPage);
        $(".opinion-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".opinion-content").append('<div class="opinion-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".opinion-contentDet"+i).append('<span class="opinion-callback">'+s[i].reply+'</span>');
            if(s[i].file == null)
                $(".opinion-contentDet"+i).append('<span style="text-align: left;" class="opinion-title"><img style="text-align: left; margin-left: 15%;" src="images/content.png"><a style="margin-left: 20px;" onclick="ctrl.opdetail('+s[i].anoceid+')">'+s[i].title+'</a></span>');
            else
                $(".opinion-contentDet"+i).append('<span style="text-align: left;" class="opinion-title"><img style="text-align: left; margin-left: 15%;" src="images/clip.png"><a style="margin-left: 20px;" onclick="ctrl.opdetail('+s[i].anoceid+')">'+s[i].title+'</a></span>');
            $(".opinion-contentDet"+i).append('<span class="opinion-author">'+s[i].realname+'</span>');
            $(".opinion-contentDet"+i).append('<span class="opinion-time">'+s[i].createtime+'</span>');
            $(".opinion-contentDet"+i).append('<span class="opinion-operation"><a onclick="ctrl.opedit('+s[i].anoceid+')">编辑</a><a onclick="ctrl.opup('+s[i].anoceid+')">置顶</a><a onclick="ctrl.opdele('+s[i].anoceid+')">删除</a></span>');
        }
    },

    showSingle: function(json) {
        $("#single-currentPage").text(model.currentPage);
        $(".single-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".single-content").append('<div class="single-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".single-contentDet"+i).append('<span class="single-number">'+s[i].messageid+'</span>');
            $(".single-contentDet"+i).append('<span class="single-type">'+s[i].type+'</span>');
            $(".single-contentDet"+i).append('<span class="single-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.sidetail('+s[i].messageid+')">'+s[i].title+'</a></span>');
            $(".single-contentDet"+i).append('<span class="single-mark">'+s[i].score+'</span>');
            $(".single-contentDet"+i).append('<span class="single-unit">'+s[i].schname+'</span>');
            $(".single-contentDet"+i).append('<span class="single-time">'+s[i].createtime+'</span>');


        }
    },

    showIntegrative: function(json) {
        $("#integrative-currentPage").text(model.currentPage);
        $(".integrative-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".integrative-content").append('<div class="integrative-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".integrative-contentDet"+i).append('<span class="integrative-number">'+s[i].messageid+'</span>');
            $(".integrative-contentDet"+i).append('<span class="integrative-type">'+s[i].type+'</span>');
            $(".integrative-contentDet"+i).append('<span class="integrative-product">'+s[i].proname+'</span>');
            $(".integrative-contentDet"+i).append('<span class="integrative-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.indetail('+s[i].messageid+')">'+s[i].title+'</a></span>');
            $(".integrative-contentDet"+i).append('<span class="integrative-mark">'+s[i].score+'</span>');
            $(".integrative-contentDet"+i).append('<span class="integrative-unit">'+s[i].schname+'</span>');
            $(".integrative-contentDet"+i).append('<span class="integrative-time">'+s[i].createtime+'</span>');
            $(".integrative-contentDet"+i).append('<span class="integrative-operation"><a onclick="ctrl.siedit('+s[i].messageid+')">编辑</a><a onclick="ctrl.inlove('+s[i].messageid+')">收藏</a><a onclick="ctrl.indele('+s[i].messageid+')">删除</a></span>');
        }
    },
    showManager: function(json) {
        $("#manager-currentPage").text(model.currentPage);
        $(".manager-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".manager-content").append('<div class="manager-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".manager-contentDet"+i).append('<span class="manager-number">'+s[i].userid+'</span>');
            $(".manager-contentDet"+i).append('<span class="manager-account">'+s[i].username+'</span>');
            $(".manager-contentDet"+i).append('<span class="manager-unit">'+s[i].schname+'</span>');
            $(".manager-contentDet"+i).append('<span class="manager-attribute">'+s[i].groupname+'</span>');
            $(".manager-contentDet"+i).append('<span class="manager-realname">'+s[i].realname+'</span>');
            if(s[i].groupname.indexOf("管理员") >= 0)
                $(".manager-contentDet"+i).append('<span class="manager-operation">无</span>');
            else//注意有权限的设置要求到时候商量更改
                $(".manager-contentDet"+i).append('<span class="manager-operation"><a onclick="ctrl.maedit('+s[i].userid+')">编辑</a><a onclick="ctrl.madele('+s[i].userid+')">删除</a><a onclick="ctrl.machange('+s[i].userid+')">修改密码</a></span>');
        }
    },

    showCollection: function(json) {
        $("#collection-currentPage").text(model.currentPage);
        $(".collection-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".collection-content").append('<div class="collection-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".collection-contentDet"+i).append('<span class="collection-number">'+s[i].messageid+'</span>');
            $(".collection-contentDet"+i).append('<span class="collection-type">'+s[i].type+'</span>');
            $(".collection-contentDet"+i).append('<span class="collection-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.codetail('+s[i].messageid+')">'+s[i].title+'</a></span>');
            $(".collection-contentDet"+i).append('<span class="collection-mark">'+s[i].score+'</span>');
            $(".collection-contentDet"+i).append('<span class="collection-unit">'+s[i].schname+'</span>');
            $(".collection-contentDet"+i).append('<span class="collection-time">'+s[i].createtime+'</span>');
            $(".collection-contentDet"+i).append('<span class="collection-click">'+s[i].click+'</span>');
            $(".collection-contentDet"+i).append('<span class="collection-operation"><a onclick="ctrl.codele('+s[i].messageid+')">删除</a></span>');
        }
    },

    showManagemark: function(json) {
        $("#managemark-currentPage").text(model.currentPage);
        $(".managemark-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".managemark-content").append('<div class="managemark-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".managemark-contentDet"+i).append('<span class="managemark-number">'+s[i].messageid+'</span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-origin">'+s[i].schname+'</span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.markdet('+s[i].messageid+')">'+s[i].title+'</a></span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-total">'+s[i].score+'</span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-mark">'+s[i].base+'</span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-xuanyong"><input name="select" value="'+s[i].select+'"></span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-pishi"><input name="approval" value="'+s[i].approval+'"></span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-yujing"><input name="warning" value="'+s[i].warning+'"></span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-zhiliang"><input name="quality" value="'+s[i].quality+'"></span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-zhuanxiang"><input name="special" value="'+s[i].special+'"></span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-minus"><input name="substract" value="'+s[i].substract+'"></span>');
            $(".managemark-contentDet"+i).append('<span class="managemark-operation"><a onclick="ctrl.markJudge('+s[i].messageid+')">打分</a></span>');
        }
    },

    showKey: function(json) {
        $("#key-currentPage").text(model.currentPage);
        $(".managekeyword-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".managekeyword-content").append('<div class="managekeyword-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".managekeyword-contentDet"+i).append('<span class="managekeyword-number">'+s[i].keyid+'</span>');
            $(".managekeyword-contentDet"+i).append('<span class="managekeyword-keyword">'+s[i].keyname+'</span>');
            $(".managekeyword-contentDet"+i).append('<span class="managekeyword-time">'+s[i].createtime+'</span>');
            $(".managekeyword-contentDet"+i).append('<span class="managekeyword-operation"><a onclick="ctrl.keydele('+s[i].keyid+')">删除</a></span>');
        }
    },

    showList: function(json) {
        $("#list-currentPage").text(model.currentPage);
        $(".marklist-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".marklist-content").append('<div class="marklist-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".marklist-contentDet"+i).append('<span class="marklist-rank">'+s[i].schoolid+'</span>');
            $(".marklist-contentDet"+i).append('<span class="marklist-school">'+s[i].schname+'</span>');
            $(".marklist-contentDet"+i).append('<span class="marklist-total">'+s[i].score+'</span>');
            $(".marklist-contentDet"+i).append('<span class="marklist-detail"><a onclick="ctrl.listdet('+s[i].schoolid+')">查看详情</a></span>');
        }
    },

    showOnline: function(json) {
        $("#online-currentPage").text(model.currentPage);
        $(".online-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".online-content").append('<div class="online-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".online-contentDet"+i).append('<span class="online-number">'+s[i].userid+'</span>');
            $(".online-contentDet"+i).append('<span class="online-account">'+s[i].username+'</span>');
            $(".online-contentDet"+i).append('<span class="online-school">'+s[i].schname+'</span>');
            $(".online-contentDet"+i).append('<span class="online-type">'+s[i].groupname+'</span>');
            $(".online-contentDet"+i).append('<span class="online-realname">'+s[i].realname+'</span>');
        }
    },

    showLog: function(json) {
        $("#log-currentPage").text(model.currentPage);
        $(".log-content").empty();
        var s = json.result;
        for(var i = 0; i < s.length; i++) {
            $(".log-content").append('<div class="log-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
            $(".log-contentDet"+i).append('<span class="log-number">'+s[i].userid+'</span>');
            $(".log-contentDet"+i).append('<span class="log-admin">'+s[i].username+'</span>');
            $(".log-contentDet"+i).append('<span class="log-ip">'+s[i].lastip+'</span>');
            $(".log-contentDet"+i).append('<span class="log-time">'+s[i].lastlogintime+'</span>');
        }
    }

}



var ctrl = {
    //个人设置
    setting: function(index) {

        $(ctrl.getCurrentClass()).css("display", "none");
        $(".details").css("display", "none");
        $(".single-send").css("display", "none");
        $(".add").css("display", "none");
        $(".opinion-search").css("display", "none");
        $(".school-detail").css("display", "none");
        $(".settings").css("display", "block");

        $('select option[value='+model.identity.schoolid+']').prop('selected', true);
        $(".settings-account").empty();
        $(".settings-account").text(model.identity.username);
        $(".settings-mark").empty();
        $(".settings-mark").text(model.identity.score);
        $(".settings-realname").val(model.identity.realname);
        $(".settings-mail").val(model.identity.email);
        $(".settings-phone").val(model.identity.phone);
    },

    //获得首页的内容信息
    getInformation: function() {
        $(".person-identity").empty();
        $(".person-identity").append(model.identity.group);
        $(".person-mark").empty();
        $(".person-mark").append(model.identity.score);
        ctrl.getRank();
        ctrl.getNews();
        ctrl.getPublics();
        ctrl.getPub();
    },

    //获得菜单页的信息
    getMenu: function() {
        model.menu = [];
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            model.menu[i] = model.identity.leftBar[i].name;
        }
        view.showMenu();
    },

    //获得舆情的详细信息
    getDetail: function(index) {
        $.ajax({
            url: model.identity.root + model.single.url.single_details,
            type: "POST",
            datatype: "json",
            data:{'messageid':index},
            success: function(json) {
                var s = json.result;
                $(".details-username").empty();
                $(".details-username").append('上报人：' + s.username + " ");
                $(".details-schname").empty();
                $(".details-schname").append('上报单位：' + s.schname);
                $(".details-createtime").empty();
                $(".details-createtime").append('上报时间：' + s.createtime + " ");
                $(".details-type").empty();
                $(".details-type").append('舆情类型：' + s.type + " ");
                $(".details-base").empty();
                $(".details-base").append('报送分：' + s.base + "  ");
                $(".details-select").empty();
                $(".details-select").append('选用分：' + s.select + "  ");
                $(".details-approval").empty();
                $(".details-approval").append('批示分：' + s.approval + "  ");
                $(".details-warning").empty();
                $(".details-warning").append('预警分：' + s.warning + "  ");
                $(".details-quality").empty();
                $(".details-quality").append('质量分：' + s.quality + "  ");
                $(".details-special").empty();
                $(".details-special").append('专项分：' + s.special + "  ");
                $(".details-substract").empty();
                $(".details-substract").append('减分：' + s.substract);
                $(".details-content").empty();
                $(".details-content").append('<h3 style="margin-left:20px;">标题：'+s.title+'</h3>');
                $(".details-content").append('<p style="margin-left:20px;">关键字：'+s.keyword+'</p>');
                $(".details-content").append('<p style="margin-left:20px;">来源网址：<a href="'+s.url+'">'+s.url+'</p>');
                $(".details-content").append('<article id="article1" style="margin-left:20px;">'+s.content+'</article>');
                $(".details-content").append('<textarea name="content" id="article2" style="margin-left:20px;">'+s.content+'</textarea>');
            }
        })
    },

    //获得当前的主页面,调整各个部分的布局方式
    getTitle: function(i) {
        $(ctrl.getCurrentClass()).css("display", "none");
        $(ctrl.getClickMenu(model.menu[i])).css("display", "block");
        showMenu();
        $(".single-send").css("display", "none");
        $(".details").css("display", "none");
        $(".settings").css("display", "none");
        $(".add").css("display", "none");
        $(".school-detail").css("display", "none");
        model.currentPage = 1;
        var current = ctrl.getCurrentClass();
        switch (current) {
            case ".contain":
                ctrl.getPub();
                break;
            case ".single-post":
                ctrl.getSingle();
                break;
            case ".integrative-post":
                ctrl.getIntegrative();
                break;
            case ".manager":
                ctrl.getManager();
                break;
            case ".collection":
                ctrl.getCollection();
                break;
            case ".managemark":
                ctrl.getMark();
                break;
            case ".managekeyword":
                ctrl.getKeyword();
                break;
            case ".marklist":
                ctrl.getList();
                break;
            case ".online":
                ctrl.getOnline();
                break;
            case ".log":
                ctrl.getLog();
                break;
        }
        if(ctrl.getCurrentClass() == ".opinion" || ctrl.getCurrentClass() == ".single-post" || ctrl.getCurrentClass() == ".integrative-post" || ctrl.getCurrentClass() == ".collection")
            $(".opinion-search").css("display", "block");
        else
            $(".opinion-search").css("display", "none");
    },
    //获得前三的排名
    getRank: function() {
        model.ranks = [];
        for(var i = 0; i < 3; i++) {
            model.ranks[i] = model.identity.rank[i].schname;
        }
        view.showRank();
    },
    //获取最新报送
    getNews: function() {
        $(".news-content").empty();
        $.ajax({
            url: model.identity.root + model.identity.leftBar[0].api,
            type: "get",
            datatype: "json",

            success: function(json) {
                var s = json.result.message;
                for(var i = 0; i < s.length; i++){
                    $(".news").append('<div style="width:90%; margin:10px auto;" class="news'+i+'"></div>')
                    $(".news"+i).append('<span>'+s[i].title+'</span>');
                    $(".news"+i).append('<span style="float: right;">'+s[i].createtime+'</span>');
                }
            }
        })
    },
    //获取舆情公告
    getPublics: function() {
        $(".public-content").empty();
        $.ajax({
            url: model.identity.root + model.identity.leftBar[0].api,
            type: "GET",
            datatype: "json",

            success: function(json) {
                var s = json.result.announcement;
                for(var i = 0; i < s.length; i++) {
                    $(".public").append('<div style="width:90%; margin:10px auto;" class="public'+i+'"></div>')
                    $(".public"+i).append('<span>'+s[i].title+'</span>');
                    $(".public"+i).append('<span style="float: right;">'+s[i].createtime+'</span>');
                }
            }
        })
    },

    //获取舆情公告的具体内容
    getPub: function() {

        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 0) {

                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.pub.maxpage = json.max_page;
                        model.single.url = json.url;
                        view.showPub(json);
                    }
                })
            }
        }
    },

    //舆情公告获取详情页
    opdetail: function(index) {
        view.transform();
        ctrl.getDetail(index);
        view.lookArticle();
    },

    //对自己的舆情公告进行编辑，跳转到编辑页
    opedit: function(index) {
        view.transform();
        ctrl.getDetail(index);
        view.editArticle();
    },

    //对自己的舆情公告页进行置顶发，成功重新加载此页
    opup: function(index) {
        $.ajax({
            url: "",
            type: "GET",
            datatype: "json",

            success: function(json) {
                if(json.is_err == 0) {
                    alert("置顶成功！");
                    ctrl.getPub();
                } else {
                    alert("失败，请重试！");
                }
            }
        })
    },

    //删除选择的信息，成功重新加载此页
    opdele: function(index) {
        $.ajax({
            url: "",
            type: "GET",
            datatype: "json",

            success: function(json) {
                if(json.is_err == 0) {
                    alert("删除成功！");
                    ctrl.getPub();
                } else {
                    alert("失败，请重试！");
                }
            }
        })
    },

    //获得单条信息报送的具体内容
    getSingle: function() {
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 1) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.single.maxpage = json.max_page;
                        model.single.url = json.url;
                        view.showSingle(json);
                    }
                })
            }
        }
    },

    sidetail: function(index) {
        view.transform();
        ctrl.getDetail(index);
        view.lookArticle();
    },

    siedit: function(index) {
        view.transform();
        ctrl.getDetail(index);
        view.editArticle();
    },

    silove: function(index) {
        $.ajax({
            url: model.identity.root + model.single.url.single_love,
            type: "POST",
            datatype: "json",
            data: {"messageid":index},
            success: function(json) {
                if(json.is_err == 0) {
                    alert("收藏成功！");
                    ctrl.getSingle();
                } else {
                    alert(json.result);
                }
            }
        })
    },

    sidele: function(index) {
        $.ajax({
            url: model.identity.root + model.single.url.single_del,
            type: "POST",
            datatype: "json",
            data: {"messageid":index},
            success: function(json) {
                if(json.is_err == 0) {
                    alert("删除成功！");
                    ctrl.getSingle();
                } else {
                    alert(json.result);
                }
            }
        })
    },

    //获得综合信息报送的内容
    getIntegrative: function() {
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 2) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.integrative.maxpage = json.max_page;
                        model.integrative.url = json.url;
                        view.showIntegrative(json);
                    }
                })
            }
        }
    },

    indetail: function(index) {
        view.transform();
        ctrl.getDetail(index);
        view.lookArticle();
    },

    inedit: function(index) {
        $.ajax({
            url: model.identity.root + model.integrative.url.overall_update,
            type: "POST",
            datatype: "json",
            data: {"messageid":index},
            success: function() {
                view.transform();
                ctrl.getDetail(index);
                view.editArticle();
            }
        })
    },

    inlove: function(index) {
        $.ajax({
            url: model.identity.root + model.integrative.url.overall_love,
            type: "POST",
            datatype: "json",
            data: {"messageid":index},
            success: function(json) {
                if(json.is_err == 0) {
                    alert("收藏成功！");
                    ctrl.getIntegrative();
                } else {
                    alert(json.result);
                }
            }
        })
    },

    indele: function(index) {
        $.ajax({
            url: model.identity.root + model.integrative.url.overall_del,
            type: "POST",
            datatype: "json",
            data: {"messageid":index},
            success: function(json) {
                if(json.is_err == 0) {
                    alert("删除成功！");
                    ctrl.getIntegrative();
                } else {
                    alert(json.result);
                }
            }
        })
    },

    //获得管理用户内容
    getManager: function() {
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 3) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.manager.maxpage = json.max_page;
                        model.manager.url = json.addurl;
                        view.showManager(json);
                    }
                })
            }
        }
    },

    maedit: function(index) {
        ctrl.setting(index);
    },

    madele: function(index) {

    },

    machange: function(index) {
        ctrl.setting(index);
    },

    //获得我的收藏页的内容
    getCollection: function() {
        for(var i = 0;i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 4) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.collection.maxpage = json.max_page;
                        model.collection.url = json.url;
                        view.showCollection(json);
                    }
                })
            }
        }
    },

    codetail: function(index) {
        view.transform();
        ctrl.getDetail(index);
    },

    codele: function(index) {
        $.ajax({
            url: model.identity.root + model.collection.url.collect_del,
            type: "POST",
            datatype: "json",
            data:{'messageid':index},
            success: function(json) {
                if(json.is_err == 0) {
                    alert("删除成功！");
                    ctrl.getCollection();
                } else {
                    alert("失败，请重试！");
                }
            }
        })
    },

    //获得管理积分的内容
    getMark: function() {
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 5) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.managemark.maxpage = json.max_page;
                        model.managemark.url = json.url;
                        view.showManagemark(json);
                    }
                })
            }
        }
    },

    markdet: function(index) {
        view.transform();
        ctrl.getDetail(index);
        view.lookArticle();
    },

    markJudge: function(index) {

    },

    //获得管理关键字的内容
    getKeyword: function() {
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 6) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.key.maxpage = json.max_page;
                        model.key.url = json.url;
                        view.showKey(json);
                    }
                })
            }
        }
    },

    keydele: function(index) {

    },

    //获取积分列表的内容
    getList: function() {
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 7) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.marklist.maxpage = json.max_page;
                        model.marklist.url = json.url;
                        view.showList(json);
                    }
                })
            }
        }
    },

    //查看学院报送的详情页
    listdet: function(index) {
        $.ajax({
            url: "",
            type: "GET",
            datatype: "json",

            success: function(json) {
                $(".marklist").css("display", "none");
                $(".school-detail").css("display", "block");
                $(".schdet-content").empty();
                var s = json.result;
                for(var i = 0; i < 1; i++){

                }
            }
        })
    },

    //获得在线人数的内容
    getOnline: function() {
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 8) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.online.maxpage = json.max_page;
                        model.online.url = json.url;
                        view.showOnline(json);
                    }
                })
            }
        }
    },

    //获得日志的内容
    getLog: function() {
        for(var i = 0; i < model.identity.leftBar.length; i++) {
            if(model.identity.leftBar[i].keybind == 9) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function(json) {
                        model.log.maxpage = json.max_page;
                        model.online.url = json.url;
                        view.showLog(json);
                    }
                })
            }
        }
    },

    //获得当前显示的页面，返回类的名字
    getCurrentClass: function() {
        if($(".contain").css("display") == "block") {
            return ".contain";
        }
        else if($(".opinion").css("display") == "block") {
            return ".opinion";
        }
        else if($(".single-post").css("display") == "block") {
            return ".single-post";
        }
        else if($(".integrative-post").css("display") == "block") {
            return ".integrative-post";
        }
        else if($(".manager").css("display") == "block") {
            return ".manager";
        }
        else if($(".collection").css("display") == "block") {
            return ".collection";
        }
        else if($(".managemark").css("display") == "block") {
            return ".managemark";
        }
        else if($(".managekeyword").css("display") == "block") {
            return ".managekeyword";
        }
        else if($(".marklist").css("display") == "block") {
            return ".marklist";
        }
        else if($(".online").css("display") == "block") {
            return ".online";
        }
        else if($(".log").css("display") == "block") {
            return ".log";
        }
    },
    //返回单击菜单某行后显示的英文类名
    getClickMenu: function(string) {
        if(string == "首页")
            return ".contain";
        else if (string == "舆情公告")
            return ".opinion";
        else if(string == "单条信息报送")
            return ".single-post";
        else if(string == "综合信息报送")
            return ".integrative-post";
        else if(string == "管理用户")
            return ".manager";
        else if(string == "我的收藏")
            return ".collection";
        else if(string == "管理积分")
            return ".managemark";
        else if(string == "管理关键字")
            return ".managekeyword";
        else if(string == "积分列表")
            return ".marklist";
        else if(string == "在线人数")
            return ".online";
        else if(string = "日志")
            return ".log";
    },

    //保存用户更改的信息
    save:function() {
        if()
        if(!($(".originpassword").val() == "" && $(".newpassword").val() == "" && $(".confirmpassword").val() == "")){
            if(!($(".newpassword").val() == $(".confirmpassword").val() && $(".originpassword").val() != "")){
                alert("错误的用户密码，请重新清空或者输入!");
                return;
            }
        }
        $.ajax({
            url: model.identity.root + model.identity.update,
            type: "POST",
            data: $("#settings").serialize(),

            success: function(json) {
                if(json.is_err == 0) {
                    alert("保存成功！");
                    model.identity.realname = $(".settings-realname").val();
                    model.identity.email = $(".settings-mail").val();
                    model.identity.phone = $(".settings-phone").val();
                    $(".settings").css("display", "none");
                    $(".contain").css("display", "block");
                } else {
                    alert(json.result);
                }
            }
        });

    },

    //上传用户评论的信息
    comments: function() {
        $.ajax({
            url: "",
            type: "POST",

            success: function() {
                alert("发表成功！");
                $(".details").css("display", "none");
                $(".contain").css("display", "block");
            }
        })


        $.ajax({
            url: model.identity.root + model.single.url.single_update,
            type: "POST",
            datatype: "json",
            data: {"messageid":index},
            success: function() {
                view.transform();
            }
        })

    },

    //上传用户发表的舆情信息
    singlesend: function() {
        $.ajax({
            url: model.identity.root + model.single.url.single_add,
            type: "POST",
            data: $('#send').serialize(),
            success: function() {
                alert("推送成功！");
                $(".single-send").css("display", "none");
                $(".single").css("display", "block");
            }
        })
    },

    integrativesend: function() {
        $.ajax({
            url: model.identity.root + model.integrative.url.integrative_add,
            type: "POST",
            data: $('#send').serialize(),

            success: function() {
                alert("推送成功！");
                $(".single-send").css("display", "none");
                $(".integrative").css("display", "block");
            }
        })
    },

    //添加用户
    add:function() {
        if($(".add-newpassword").val() == $(".add-confirmpassword").val())
            if($(".add-newpassword").val() != "" && $(".add-confirmpassword").val() != "")
                $.ajax({
                    url: model.identity.root + model.manager.add_url,
                    type: "POST",

                    data: $("#add").serialize(),

                    success: function() {
                        if(json.is_err == 0){
                            alert("添加成功！");
                            $(".add").css("display", "none");
                            $(".contain").css("display", "block");
                        }else{
                            alert(json.result);
                        }
                    }
                })
            else
                alert("请输入密码！");
        else
            alert("密码不相同！");
    },
}