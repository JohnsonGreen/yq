$(document).ready(function () {
    view.showMenu();
    $("body").css("background-image", "url(" + ImgPath + "login.png)");

    document.onkeydown = function(e){
        var ev = document.all ? window.event : e;
        if(ev.keyCode==13) {
            sub();
        }
    }

    $.ajax({
        url: login,
        type: "GET",
        datatype: "json",

        success: function (json) {
            //登录成功时需要渲染的函数
            if (json.is_err == 0) {

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

        success: function (json) {
            for (var i = 0; i < json.length; i++) {
                $(".school").append('<option value="' + json[i].schoolid + '">' + json[i].schname + '</option>');
            }
        }
    })

    //获得类型名称
    $.ajax({
        url: types_url,
        type: "GET",
        datatype: "json",

        success: function (json) {
            for (var i = 0; i < json.length; i++) {
                $(".type").append('<option value="' + json[i].typeid + '">' + json[i].type + '</option>');
            }
        }
    })
})

var model = {
    //新点击时渲染
    kepa: 0,
    tempid: "",
    currentPage: 1,
    lastpage: "",
    identity: {},
    pub: {url: "", max_page: "", currentid: ""},
    single: {url: "", max_page: "", messageid: ""},
    integrative: {url: "", max_page: "", messageid: ""},
    manager: {url: "", groups_api: "",target:""},
    collection: {url: "", max_page: ""},
    managemark: {url: "", max_page: ""},
    key: {url: "", max_page: ""},
    marklist: {url: "", max_page: ""},
    online: {url: "", max_page: ""},
    log: {url: "", max_page: ""},
    listdet: {url: "", max_page: 1, schoolid: ""},
    reply: {url: ""},
    //菜单的内容，用数组表示
    menu: [],
    ranks: [],
}

//判断数据是否为空
function isNull(data) {
    return (data == "" || data == undefined || data == null) ? true : false;
}

var view = {
    //初始化渲染菜单界面
    showMenu: function () {
        for (var i = 0; i < model.menu.length; i++) {
            $(".menu").append('<li class="menu' + i + ' menu-items" onmouseover="return ctrl.mouseover(' + i + ')" onmouseout="ctrl.mouseout(' + i + ')" onclick="return ctrl.getTitle(' + i + ')">' + '<span>' + model.menu[i] + '</span>' + '</li>');
        }
    },
    //渲染前三的排名
    showRank: function () {
        $(".rank-first").append('<i style="color:black; font-size:14px; font-style: normal;">' + model.ranks[0] + '</i>');
        $(".rank-second").append('<i style="color:black; font-size:14px; font-style: normal;">' + model.ranks[1] + '</i>');
        $(".rank-third").append('<i style="color:black; font-size:14px; font-style: normal;">' + model.ranks[2] + '</i>');
    },

    transform: function () {
        $(ctrl.getCurrentClass()).css("display", "none");
        $(".details").css("display", "block");
        $(".opinion-search").css("display", "none");
    },

    lookArticle: function () {
        $(".article1").css("display", "block");
        $(".article2").css("display", "none");
    },

    editArticle: function () {
        $(".article1").css("display", "none");
        $(".article2").css("display", "block");
    },

    front: function () {
        $(".school-detail").css("display", "none");
        $(".marklist").css("display", "block");
    },

    back: function (index) {
        $(".details").css("display", "none");
        $(index).css("display", "block");
        if (ctrl.getCurrentClass() == ".opinion" || ctrl.getCurrentClass() == ".single-post" || ctrl.getCurrentClass() == ".integrative-post" || ctrl.getCurrentClass() == ".collection")
            $(".opinion-search").css("display", "block");
    },
    showPub: function (json) {
        $("#pub-currentPage").text(model.currentPage);
        $(".opinion-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            console.log(s[i].isread);
            $(".opinion-content").append('<div class="opinion-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".opinion-contentDet" + i).append('<span class="opinion-callback">' + (i - (-1)) + '</span>');
            if (s[i].file == null) {
                $(".opinion-contentDet" + i).append('<span style="text-align: left;" class="opinion-title"><img style="text-align: left; margin-left: 15%;" src="' + ImgPath + 'content.png"><a style="margin-left: 20px;" onclick="ctrl.opdetail(' + s[i].anoceid + ')">' + s[i].title + '</a></span>');
            }
            else {
                $(".opinion-contentDet" + i).append('<span style="text-align: left;" class="opinion-title"><img style="text-align: left; margin-left: 15%;" src="' + ImgPath + 'clip.png"><a style="margin-left: 20px;" onclick="ctrl.opdetail(' + s[i].anoceid + ')">' + s[i].title + '</a></span>');
            }
            $(".opinion-contentDet" + i).append('<span class="opinion-author">' + s[i].realname + '</span>');
            $(".opinion-contentDet" + i).append('<span class="opinion-time">' + s[i].createtime + '</span>');
            if (model.identity.groupid == "3"){
                if(!isNull(s[i].stick) && s[i].stick == '1')
                    $(".opinion-contentDet" + i).append('<span class="opinion-operation"><a onclick="ctrl.opedit(' + s[i].anoceid + ')">编辑</a><a onclick="ctrl.opdown(' + s[i].anoceid + ')">取消</a><a onclick="ctrl.opdele(' + s[i].anoceid + ')">删除</a></span>');
                else
                    $(".opinion-contentDet" + i).append('<span class="opinion-operation"><a onclick="ctrl.opedit(' + s[i].anoceid + ')">编辑</a><a onclick="ctrl.opup(' + s[i].anoceid + ')">置顶</a><a onclick="ctrl.opdele(' + s[i].anoceid + ')">删除</a></span>');
            }
            else{
                $(".opinion-contentDet" + i).append('<span class="opinion-operation">无</span>');
            }

        }
    },

    showSingle: function (json) {
        $("#single-currentPage").text(model.currentPage);
        $(".single-content").empty();
        var s = json.result;

        for (var i = 0; i < s.length; i++) {
            $(".single-content").append('<div class="single-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".single-contentDet" + i).append('<span class="single-number">' + s[i].messageid + '</span>');
            $(".single-contentDet" + i).append('<span class="single-type">' + s[i].type + '</span>');
            if (s[i].file == null)
                $(".single-contentDet" + i).append('<span style="text-align: left;" class="single-title"><img style="text-align: left; margin-left: 15%;" src="' + ImgPath + 'content.png"><a style="margin-left: 20px;" onclick="ctrl.sidetail(' + s[i].messageid + ')">' + s[i].title + '</a></span>');
            else
                $(".single-contentDet" + i).append('<span style="text-align: left;" class="single-title"><img style="text-align: left; margin-left: 15%;" src="' + ImgPath + 'clip.png"><a style="margin-left: 20px;" onclick="ctrl.sidetail(' + s[i].messageid + ')">' + s[i].title + '</a></span>');
            $(".single-contentDet" + i).append('<span class="single-mark">' + s[i].score + '</span>');
            $(".single-contentDet" + i).append('<span class="single-unit">' + s[i].schname + '</span>');
            $(".single-contentDet" + i).append('<span class="single-time">' + s[i].createtime + '</span>');

            var str = '<span class="single-operation">';
            if (!isNull(s[i].flag) && s[i].flag == 1) {
                str += '<a onclick="ctrl.siedit(' + s[i].messageid + ')">编辑</a>';
            }
            if (!isNull(s[i].coflag) && s[i].coflag == 1)
                str += '<a onclick="ctrl.silove(' + s[i].messageid + ')">收藏</a>';
            else
                str += '<a title="收藏" href ="javascript:return false;" onclick="return false;" style="cursor: default;text-decoration:none;"><span style="opacity: 0.6">收藏</span></a>';
            if (!isNull(s[i].flag) && s[i].flag == 1) {
                str += '<a onclick="ctrl.sidele(' + s[i].messageid + ')">删除</a>';
            }
            str += '</span>';
            $(".single-contentDet" + i).append(str);
        }
    },

    showIntegrative: function (json) {
        $("#integrative-currentPage").text(model.currentPage);
        $(".integrative-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".integrative-content").append('<div class="integrative-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".integrative-contentDet" + i).append('<span class="integrative-number">' + s[i].messageid + '</span>');
            if(!isNull(s[i].type))
                $(".integrative-contentDet" + i).append('<span class="integrative-type">' + s[i].type + '</span>');
            else
                $(".integrative-contentDet" + i).append('<span class="integrative-type">无</span>');
            $(".integrative-contentDet" + i).append('<span class="integrative-product">' + s[i].proname + '</span>');
            if (s[i].file == null)
                $(".integrative-contentDet" + i).append('<span style="text-align: left;" class="integrative-title"><img style="text-align: left; margin-left: 15%;" src="' + ImgPath + 'content.png"><a style="margin-left: 20px;" onclick="ctrl.indetail(' + s[i].messageid + ')">' + s[i].title + '</a></span>');
            else
                $(".integrative-contentDet" + i).append('<span style="text-align: left;" class="integrative-title"><img style="text-align: left; margin-left: 15%;" src="' + ImgPath + 'clip.png"><a style="margin-left: 20px;" onclick="ctrl.indetail(' + s[i].messageid + ')">' + s[i].title + '</a></span>');
            $(".integrative-contentDet" + i).append('<span class="integrative-mark">' + s[i].score + '</span>');
            $(".integrative-contentDet" + i).append('<span class="integrative-unit">' + s[i].schname + '</span>');
            $(".integrative-contentDet" + i).append('<span class="integrative-time">' + s[i].createtime + '</span>');

            var str = '<span class="single-operation">';
            if (!isNull(s[i].flag) && s[i].flag == 1) {
                str += '<a onclick="ctrl.inedit(' + s[i].messageid + ')">编辑</a>';
            }
            if (!isNull(s[i].coflag) && s[i].coflag == 1)
                str += '<a onclick="ctrl.inlove(' + s[i].messageid + ')">收藏</a>';
            else
                str += '<a title="收藏" href ="javascript:return false;" onclick="return false;" style="cursor: default;text-decoration:none;"><span style="opacity: 0.6">收藏</span></a>';

            if (!isNull(s[i].flag) && s[i].flag == 1) {
                str += '<a onclick="ctrl.indele(' + s[i].messageid + ')">删除</a>';
            }
            str += '</span>';
            $(".integrative-contentDet" + i).append(str);

            //$(".integrative-contentDet"+i).append('<span class="integrative-operation"><a onclick="ctrl.siedit('+s[i].messageid+')">编辑</a><a onclick="ctrl.inlove('+s[i].messageid+')">收藏</a><a onclick="ctrl.indele('+s[i].messageid+')">删除</a></span>');
        }
    },

    showManager: function (json) {
        $("#manager-currentPage").text(model.currentPage);
        $(".manager-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".manager-content").append('<div class="manager-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".manager-contentDet" + i).append('<span class="manager-number">' + s[i].userid + '</span>');
            $(".manager-contentDet" + i).append('<span class="manager-account">' + s[i].username + '</span>');
            $(".manager-contentDet" + i).append('<span class="manager-unit">' + s[i].schname + '</span>');
            $(".manager-contentDet" + i).append('<span class="manager-attribute">' + s[i].groupname + '</span>');
            $(".manager-contentDet" + i).append('<span class="manager-realname">' + s[i].realname + '</span>');
            if (s[i].groupname.indexOf("管理员") >= 0)
                $(".manager-contentDet" + i).append('<span class="manager-operation">无</span>');
            else//注意有权限的设置要求到时候商量更改
                $(".manager-contentDet" + i).append('<span class="manager-operation"><a onclick="ctrl.maedit(' + s[i].userid + ')">编辑</a><a onclick="ctrl.madele(' + s[i].userid + ')">删除</a><a onclick="ctrl.machange(' + s[i].userid + ')">修改密码</a></span>');
        }
    },

    showCollection: function (json) {
        $("#collection-currentPage").text(model.currentPage);
        $(".collection-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".collection-content").append('<div class="collection-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".collection-contentDet" + i).append('<span class="collection-number">' + s[i].messageid + '</span>');
            $(".collection-contentDet" + i).append('<span class="collection-type">' + s[i].type + '</span>');
            $(".collection-contentDet" + i).append('<span class="collection-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.codetail(' + s[i].messageid + ')">' + s[i].title + '</a></span>');
            $(".collection-contentDet" + i).append('<span class="collection-mark">' + s[i].score + '</span>');
            $(".collection-contentDet" + i).append('<span class="collection-unit">' + s[i].schname + '</span>');
            $(".collection-contentDet" + i).append('<span class="collection-time">' + s[i].createtime + '</span>');
            $(".collection-contentDet" + i).append('<span class="collection-click">' + s[i].click + '</span>');
            $(".collection-contentDet" + i).append('<span class="collection-operation"><a onclick="ctrl.codele(' + s[i].messageid + ')">删除</a></span>');
        }
    },

    showManagemark: function (json) {
        $("#managemark-currentPage").text(model.currentPage);
        $(".managemark-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".managemark-content").append('<div class="managemark-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".managemark-contentDet" + i).append('<span class="managemark-number">' + s[i].messageid + '</span>');
            $(".managemark-contentDet" + i).append('<span class="managemark-origin">' + s[i].schname + '</span>');
            $(".managemark-contentDet" + i).append('<span class="managemark-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.markdet(' + s[i].messageid + ')">' + s[i].title + '</a></span>');
            $(".managemark-contentDet" + i).append('<span id="marktotal' + i + '" class="managemark-total">' + s[i].score + '</span>');
            $(".managemark-contentDet" + i).append('<span id="markbase' + i + '" class="managemark-mark">' + s[i].base + '</span>');
            $(".managemark-contentDet" + i).append('<span class="managemark-add"><input id="markadd' + i + '" name="add" value="' + s[i].add + '"></span>');
            $(".managemark-contentDet" + i).append('<span class="managemark-minus"><input id="markminus' + i + '" name="substract" value="' + s[i].substract + '"></span>');
            $(".managemark-contentDet" + i).append('<span class="managemark-operation"><a onclick="ctrl.markJudge(' + s[i].messageid + ',' + i + ')">打分</a></span>');
        }
    },

    showKey: function (json) {
        $("#key-currentPage").text(model.currentPage);
        $(".managekeyword-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".managekeyword-content").append('<div class="managekeyword-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".managekeyword-contentDet" + i).append('<span class="managekeyword-number">' + s[i].keyid + '</span>');
            $(".managekeyword-contentDet" + i).append('<span class="managekeyword-keyword">' + s[i].keyname + '</span>');
            $(".managekeyword-contentDet" + i).append('<span class="managekeyword-time">' + s[i].createtime + '</span>');
            $(".managekeyword-contentDet" + i).append('<span class="managekeyword-operation"><a onclick="ctrl.keydele(' + s[i].keyid + ')">删除</a></span>');
        }
    },

    showList: function (json) {
        $("#list-currentPage").text(model.currentPage);
        $(".marklist-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".marklist-content").append('<div class="marklist-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".marklist-contentDet" + i).append('<span class="marklist-rank">' + s[i].schoolid + '</span>');
            $(".marklist-contentDet" + i).append('<span class="marklist-school">' + s[i].schname + '</span>');
            $(".marklist-contentDet" + i).append('<span class="marklist-total">' + s[i].score + '</span>');
            $(".marklist-contentDet" + i).append('<span class="marklist-detail"><a onclick="ctrl.listdet(' + s[i].schoolid + ')">查看详情</a></span>');
        }
    },

    showOnline: function (json) {
        $("#online-currentPage").text(model.currentPage);
        $(".online-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".online-content").append('<div class="online-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".online-contentDet" + i).append('<span class="online-number">' + s[i].userid + '</span>');
            $(".online-contentDet" + i).append('<span class="online-account">' + s[i].username + '</span>');
            $(".online-contentDet" + i).append('<span class="online-school">' + s[i].schname + '</span>');
            $(".online-contentDet" + i).append('<span class="online-type">' + s[i].groupname + '</span>');
            $(".online-contentDet" + i).append('<span class="online-realname">' + s[i].realname + '</span>');
        }
    },

    showLog: function (json) {
        $("#log-currentPage").text(model.currentPage);
        $(".log-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".log-content").append('<div class="log-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".log-contentDet" + i).append('<span class="log-number">' + s[i].userid + '</span>');
            $(".log-contentDet" + i).append('<span class="log-admin">' + s[i].username + '</span>');
            $(".log-contentDet" + i).append('<span class="log-ip">' + s[i].lastip + '</span>');
            $(".log-contentDet" + i).append('<span class="log-time">' + s[i].lastlogintime + '</span>');
        }
    },

    showschdet: function (json, index) {
        $("#schdet-currentPage").text(model.currentPage);
        $(".schdet-content").empty();
        var s = json.result;
        for (var i = 0; i < s.length; i++) {
            $(".schdet-content").append('<div class="schdet-contentDet' + i + '" style="color:black; width: 100%; height: 38px;"></div>');
            $(".schdet-contentDet" + i).append('<span class="schdet-number">' + s[i].proid + '</span>');
            $(".schdet-contentDet" + i).append('<span class="schdet-type">' + s[i].type + '</span>');
            $(".schdet-contentDet" + i).append('<span class="schdet-send">' + s[i].proname + '</span>');
            $(".schdet-contentDet" + i).append('<span class="schdet-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.schdetdet(' + s[i].messageid + ')">' + s[i].title + '</a></span>');
            $(".schdet-contentDet" + i).append('<span class="schdet-mark">' + s[i].score + '</span>');
            $(".schdet-contentDet" + i).append('<span class="schdet-person">' + s[i].username + '</span>');
            $(".schdet-contentDet" + i).append('<span class="schdet-time">' + s[i].createtime + '</span>');

            if (!isNull(s[i].flag) && s[i].flag == 1)
                $(".schdet-contentDet" + i).append('<span class="schdet-operation"><a onclick="ctrl.schdetdele(' + s[i].messageid + ')">删除</a></span>');
        }
    }
}

var ctrl = {
    mouseover: function (i) {
        $(".menu" + i).css("background-color", "#4d8aa8");
        $(".menu" + i).css("color", "white");
    },

    mouseout: function (i) {
        $(".menu" + i).css("background-color", "#e5e5e5");
        $(".menu" + i).css("color", "black");
        $(ctrl.getCurrentMenu()).css("background-color", "#4d8aa8");
        $(ctrl.getCurrentMenu()).css("color", "white");
    },
    //个人设置
    setting: function (index) {

        $(ctrl.getCurrentClass()).css("display", "none");
        $(".details").css("display", "none");
        $(".single-send").css("display", "none");
        $(".add").css("display", "none");
        $(".opinion-search").css("display", "none");
        $(".school-detail").css("display", "none");
        $(".settings").css("display", "block");

        $('select option[value=' + index.schoolid + ']').prop('selected', true);
        $(".settings-account").empty();
        $(".settings-account").text(index.username);
        $(".settings-mark").empty();
        $(".settings-mark").text(index.score);
        $(".settings-unit").empty();
        $(".settings-unit").text(index.schname);
        $(".settings-realname").val(index.realname);
        $(".settings-mail").val(index.email);
        $(".settings-phone").val(index.phone);
        $("#userid").val(index.userid);
    },

    //获得首页的内容信息
    getInformation: function () {
        $(".person-identity").empty();
        $(".person-identity").append(model.identity.group);
        $(".person-mark").empty();
        $(".person-mark").append(model.identity.score);
        $(".system-message").empty();
        $(".system-message").append(model.identity.hint_num);
        ctrl.getRank();
        ctrl.getNews();
        ctrl.getPublics();
        ctrl.getPub();
    },

    //获得菜单页的信息
    getMenu: function () {
        model.menu = [];
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            model.menu[i] = model.identity.leftBar[i].name;
        }
        view.showMenu();
    },

    //获得公告页的详情
    getPubDetail: function (index) {
        model.kepa = 1;
        model.pub.currentid = index;
        $.ajax({
            url: model.identity.root + model.pub.url.announce_details,
            type: "POST",
            datatype: "json",
            async: false,
            data: {'anoceid': index},
            success: function (json) {
                var s = json.result;
                $(".details-base").empty();
                $(".details-add").empty();
                $(".details-substract").empty();
                $(".details-type").empty();
                $(".details-username").empty();
                $(".details-username").append('上报人：' + s.username + " ");
                $(".details-schname").empty();
                $(".details-schname").append('上报单位：' + s.schname);
                $(".details-createtime").empty();
                $(".details-createtime").append('上报时间：' + s.createtime + " ");
                $(".details-content").empty();
                $(".details-content").append('<h3 style="margin-left:20px;">标题：' + s.title + '</h3>');
                if (s.file != null)
                    $(".details-content").append('<p style="margin-left:20px;">文件下载：<a target="_blank" href="' + s.file + '">' + s.file + '</p>');
                $(".details-content").append('<div class="article1 froala-element1" style="margin-left:20px;">' + s.content + '</div>');
                $(".details-content").append('<section class="article2" style="margin-left:20px; width: 90%; height: 150px; text-align: left;" id="editor"> <div id="edit" style="margin-top: 30px;"> </div> </section>');
                $('#edit').editable({inlineMode: false, alwaysBlank: true});
                $(".froala-element")[0].innerHTML = s.content;
            }
        })
    },

    //获得舆情的详细信息
    getDetail: function (index, t) {
        model.kepa = 1;
        model.single.messageid = index;
        model.integrative.messageid = index;
        if (t == 0)
            temp = model.identity.root + model.single.url.single_details;
        else
            temp = model.identity.root + model.integrative.url.overall_details;
        //if(model.)
        $.ajax({
            url: temp,
            type: "POST",
            datatype: "json",
            async: false,
            data: {'messageid': index},
            success: function (json) {
                var s = json.result;
                $(".details-username").empty();
                $(".details-username").append('上报人：' + s.username + " ");
                $(".details-schname").empty();
                $(".details-schname").append('上报单位：' + s.schname);
                $(".details-createtime").empty();
                $(".details-createtime").append('上报时间：' + s.createtime + " ");
                $(".details-type").empty();
                if(!isNull(s.type))
                    $(".details-type").append('舆情类型：' + s.type + "&nbsp;");
                $(".details-base").empty();
                $(".details-base").append('报送分：' + s.base + "  ");
                $("details-add").empty();
                $("details-add").append('加分：' + s.add);
                $(".details-substract").empty();
                $(".details-substract").append('减分：' + s.substract);
                $(".details-content").empty();
                $(".details-content").append('<h3 style="margin-left:20px;">标题：' + s.title + '</h3>');
                if (s.file != null)
                    $(".details-content").append('<p style="margin-left:20px;">文件下载：<a target="_blank" href="' + s.file + '">' + s.file + '</p>');
                $(".details-content").append('<p style="margin-left:20px;">关键字：' + s.keyword + '</p>');
                $(".details-content").append('<p style="margin-left:20px;">来源网址：<a target="_blank" href="' + s.url + '">' + s.url + '</p>');
                $(".details-content").append('<div class="article1 froala-element1" style="margin-left:20px;">' + s.content + '</div>');
                $(".details-content").append('<section class="article2" style="margin-left:20px; width: 90%; height: 150px; text-align: left;" id="editor"> <div id="edit" style="margin-top: 30px;"> </div> </section>');
                $('#edit').editable({inlineMode: false, alwaysBlank: true});
                $(".froala-element")[0].innerHTML = s.content;
            }
        })
    },


    //获得舆情的详细信息
    getDetailMarkList: function (index) {
        $.ajax({
            url: model.identity.root + model.listdet.url.scolist_details,
            type: "POST",
            datatype: "json",
            async: false,
            data: {'messageid': index},
            success: function (json) {
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
                $("details-add").empty();
                $("details-add").append('加分：' + s.add);
                $(".details-substract").empty();
                $(".details-substract").append('减分：' + s.substract);
                $(".details-content").empty();
                $(".details-content").append('<h3 style="margin-left:20px;">标题：' + s.title + '</h3>');
                $(".details-content").append('<p style="margin-left:20px;">关键字：' + s.keyword + '</p>');
                $(".details-content").append('<p style="margin-left:20px;">来源网址：<a href="' + s.url + '">' + s.url + '</p>');
                $(".details-content").append('<div id="article1 " class="froala-element1" style="margin-left:20px;">' + s.content + '</div>');

            }
        })
    },

    //获得当前的主页面,调整各个部分的布局方式
    getTitle: function (i) {
        $(".school").val(0);
        $(".type").val(0);
        if(i == 1) {
            model.lastpage = ".opinion";
            $('.opinion-hide').css('display','none');
        }
        $(ctrl.getCurrentMenu()).css("background-color", "#e5e5e5");
        $(ctrl.getCurrentMenu()).css("color", "black");
        $(ctrl.getCurrentClass()).css("display", "none");
        $(ctrl.getClickMenu(model.menu[i])).css("display", "block");
        showMenu();
        $(".single-send").css("display", "none");
        $(".details").css("display", "none");
        $(".settings").css("display", "none");
        $(".add").css("display", "none");
        $(".school-detail").css("display", "none");
        $(".menu" + i).css("background-color", "#4d8aa8");
        $(".menu" + i).css("color", "white");
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
        if (ctrl.getCurrentClass() == ".opinion" || ctrl.getCurrentClass() == ".single-post" || ctrl.getCurrentClass() == ".integrative-post" || ctrl.getCurrentClass() == ".collection")
            $(".opinion-search").css("display", "block");
        else
            $(".opinion-search").css("display", "none");
    },
    //获得前三的排名
    getRank: function () {
        model.ranks = [];
        for (var i = 0; i < 3; i++) {
            model.ranks[i] = model.identity.rank[i].schname;
        }
        view.showRank();
    },
    //获取最新报送
    getNews: function () {
        $(".news-content").empty();
        $.ajax({
            url: model.identity.root + model.identity.leftBar[0].api,
            type: "get",
            datatype: "json",

            success: function (json) {
                var s = json.result.message;
                for (var i = 0; i < s.length; i++) {
                    $(".news").append('<div style="width:90%; margin:10px auto;" class="news' + i + '"></div>')
                    $(".news" + i).append('<span>' + s[i].title + '</span>');
                    $(".news" + i).append('<span style="float: right;">' + s[i].createtime + '</span>');
                }
            }
        })
    },
    //获取舆情公告
    getPublics: function () {
        $(".public-content").empty();
        $.ajax({
            url: model.identity.root + model.identity.leftBar[0].api,
            type: "POST",
            datatype: "json",

            success: function (json) {
                var s = json.result.announcement;
                for (var i = 0; i < s.length; i++) {
                    $(".public").append('<div style="width:90%; margin:10px auto;" class="public' + i + '"></div>')
                    $(".public" + i).append('<span>' + s[i].title + '</span>');
                    $(".public" + i).append('<span style="float: right;">' + s[i].createtime + '</span>');
                }
            }
        })
    },

    //获取舆情公告的具体内容
    getPub: function () {
        model.lastpage = ".opinion";
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 0) {

                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "POST",
                    datatype: "json",

                    success: function (json) {
                        model.pub.max_page = json.max_page;
                        model.pub.url = json.url;
                        view.showPub(json);
                    }
                })
            }
        }
        if (model.identity.groupid != "3")
            $(".opinion-output").css("display", "none");
    },

    pubCurrentPage: function() {
        var temp;
        for (var i = 0; i < model.identity.leftBar.length; i++)
            if (model.identity.leftBar[i].keybind == 0)
                temp = model.identity.root + model.identity.leftBar[i].api;
            $.ajax({
                url: temp,
                type: "POST",
                data: {
                    "page": model.currentPage,
                    "date1": $("#startDate").val(),
                    "date2": $("#endDate").val(),
                },

                success: function (json) {
                    model.pub.max_page = json.max_page;
                    view.showPub(json);
                }
            })
    },

    //舆情公告获取详情页
    opdetail: function (index) {
        $('#pubButton').css('display','none');

        view.transform();
        ctrl.getPubDetail(index);
        view.lookArticle();
    },

    //对自己的舆情公告进行编辑，跳转到编辑页
    opedit: function (index) {
        view.transform();
        ctrl.getPubDetail(index, 0);
        view.editArticle();
    },

    //对自己的舆情公告页进行置顶发，成功重新加载此页
    opup: function (index) {
        $.ajax({
            url: model.identity.root + model.pub.url.announce_stick,
            type: "POST",
            data: {
                "id": index,
                "page": model.currentPage
            },

            success: function (json) {
                if (json.is_err == 0) {
                    alert("置顶成功！");
                    ctrl.getPub();
                } else {
                    alert("失败，请重试！");
                }
            }
        })
    },

    //对自己的舆情公告页进行取消置顶发，成功重新加载此页
    opdown: function (index) {
        $.ajax({
            url: model.identity.root + model.pub.url.announce_down,
            type: "POST",
            data: {
                "id": index,
                "page": model.currentPage
            },

            success: function (json) {
                if (json.is_err == 0) {
                    alert("取消置顶成功！");
                    ctrl.getPub();
                } else {
                    alert("失败，请重试！");
                }
            }
        })
    },

    //删除选择的信息，成功重新加载此页
    opdele: function (index) {
        if (confirm("确定要删除吗？")) {
            $.ajax({
                url: model.identity.root + model.pub.url.announce_del,
                type: "POST",
                data: {
                    "id": index,
                    "page": model.currentPage
                },

                success: function (json) {
                    if (json.is_err == 0) {
                        alert("删除成功！");
                        ctrl.pubCurrentPage();
                    } else {
                        alert("失败，请重试！");
                    }
                }
            })
        }
    },

    //获得单条信息报送的具体内容
    getSingle: function () {
        $('.opinion-hide').css('display','inline');
        model.lastpage = ".single-post";
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 1) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        // $(".school").val(0);
                        // $(".type").val(0);
                        model.single.max_page = json.max_page;
                        model.single.url = json.url;

                        view.showSingle(json);
                    }
                })
            }
        }
    },

    singleCurrentPage: function() {

        var temp;
        for (var i = 0; i < model.identity.leftBar.length; i++)
            if (model.identity.leftBar[i].keybind == 1)
                temp = model.identity.root + model.identity.leftBar[i].api;
            $.ajax({
                url: temp,
                type: "POST",
                data: {
                    "page": model.currentPage,
                    "date1": $("#startDate").val(),
                    "date2": $("#endDate").val(),
                    "keywords": $("#searchSomething").val(),
                    "school": $(".school").eq(1).find("option:selected").text(),
                    "type": $(".type").eq(1).find("option:selected").text()
                },
                success: function (json) {
                    model.single.max_page = json.max_page;
                    view.showSingle(json);
                }
            })
    },

    sidetail: function (index) {
        $('#pubButton').css('display','none');
        view.transform();
        ctrl.getDetail(index, 0);
        view.lookArticle();
    },

    siedit: function (index) {
        $('#pubButton').css('display','block');
        view.transform();
        ctrl.getDetail(index, 0);
        view.editArticle();
    },

    silove: function (index) {
        $.ajax({
            url: model.identity.root + model.single.url.single_love,
            type: "POST",
            datatype: "json",
            data: {"messageid": index},

            success: function (json) {
                if (json.is_err == 0) {
                    alert("收藏成功！");
                    ctrl.singleCurrentPage();
                } else {
                    alert("失败，请重试！");
                }
            }
        })
    },

    sidele: function (index) {
        if (confirm("确定要删除吗？")) {
            $.ajax({
                url: model.identity.root + model.single.url.single_del,
                type: "POST",
                datatype: "json",
                data: {
                    "messageid": index
                },
                success: function (json) {
                    if (json.is_err == 0) {
                        alert("删除成功！");
                        ctrl.singleCurrentPage();
                    } else {
                        alert("失败，请重试！");
                    }
                }
            })
        }
    },

    //获得综合信息报送的内容
    getIntegrative: function () {
        model.lastpage = ".integrative-post";
        $('.opinion-hide').css('display','inline');
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 2) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        // $(".school").val(0);
                        // $(".type").val(0);
                        model.integrative.max_page = json.max_page;
                        model.integrative.url = json.url;

                        view.showIntegrative(json);
                    }
                })
            }
        }
    },

    integrativeCurrentPage: function() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 2)
            temp = model.identity.root + model.identity.leftBar[i].api;
        $.ajax({
            url: temp,
            type: "POST",
            data: {
                "page": model.currentPage,
                "date1": $("#startDate").val(),
                "date2": $("#endDate").val(),
                "keywords": $("#searchSomething").val(),
                "school": $(".school").eq(1).find("option:selected").text(),
                "type": $(".type").eq(1).find("option:selected").text()
            },

            success: function (json) {
                model.integrative.max_page = json.max_page;
                view.showIntegrative(json);
            }
        })

  },


    indetail: function (index) {
        $('#pubButton').css('display','none');
        view.transform();
        ctrl.getDetail(index, 1);
        view.lookArticle();
    },

    inedit: function (index) {
        $('#pubButton').css('display','block');
        view.transform();
        ctrl.getDetail(index, 1);
        view.editArticle();
    },

    inlove: function (index) {
        $.ajax({
            url: model.identity.root + model.integrative.url.overall_love,
            type: "POST",
            datatype: "json",
            data: {"messageid": index},

            success: function (json) {
                if (json.is_err == 0) {
                    alert("收藏成功！");
                    ctrl.integrativeCurrentPage();
                } else {
                    alert("失败，请重试！");
                }
            }
        })
    },

    indele: function (index) {
        if (confirm("确定要删除吗")) {
            $.ajax({
                url: model.identity.root + model.integrative.url.overall_del,
                type: "POST",
                datatype: "json",
                data: {
                    "messageid": index,
                    "page": model.currentPage
                },

                success: function (json) {
                    if (json.is_err == 0) {
                        alert("删除成功！");
                        ctrl.integrativeCurrentPage();
                    } else {
                        alert("失败，请重试！");
                    }
                }
            })
        }
    },

    //获得管理用户内容
    getManager: function () {
        model.lastpage = ".manager";
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 3) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        model.manager.max_page = json.max_page;
                        model.manager.url = json.url;
                        model.manager.groups_api = json.groups_api;
                        view.showManager(json);
                    }
                })
            }
        }
    },

    maedit: function (index) {
        model.manager.target = 'mapers';
        $('#settings > div:not(.change-password)').css('display','block');
        $('.change-password').css('display','none');
        $.ajax({
            url: model.identity.root + model.identity.findinfo,
            type: "post",
            data: {
                "userid": index,
            },
            success: function (json) {
                if(json.is_err==0){
                    ctrl.setting(json.result);
                }else{
                    alert(json.result);
                }
            }
        });

    },

    madele: function (index) {
        if (confirm("确定要删除吗？")) {

            $.ajax({
                url: model.identity.root + model.manager.url.user_ban,
                type: "post",
                data: {
                    "id": index,
                    "page": model.currentPage
                },

                success: function (json) {
                    view.showManager(json);
                }
            })
        }
    },

    machange: function (index) {
        model.manager.target = 'machan';
        $('#settings > div:not(.change-password)').css('display','none');
        $('.change-password').css('display','block');
        $.ajax({
            url: model.identity.root + model.identity.findinfo,
            type: "post",
            data: {
                "userid": index,
            },
            success: function (json) {
                if(json.is_err==0){
                    ctrl.setting(json.result);
                }else{
                    alert(json.result);
                }
            }
        });

    },

    //获得我的收藏页的内容
    getCollection: function () {
        model.lastpage = ".collection";
        $('.opinion-hide').css('display','inline');
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 4) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        // $(".school").val(0);
                        // $(".type").val(0);
                        model.collection.max_page = json.max_page;
                        model.collection.url = json.url;

                        view.showCollection(json);
                    }
                })
            }
        }
    },

    codetail: function (index) {
        view.transform();
        ctrl.getDetail(index, 1);
        view.lookArticle();
    },

    codele: function (index) {
        if (confirm("确定要删除吗？")) {
            model.currentPage = 1;
            $.ajax({
                url: model.identity.root + model.collection.url.collect_del,
                type: "POST",
                data: {
                    'messageid': index,
                    "page": model.currentPage
                },
                success: function (json) {
                    if (json.is_err == 0) {
                        alert("删除成功！");
                        ctrl.getCollection();
                    } else {
                        alert("失败，请重试！");
                    }
                }
            })
        }
    },

    //获得管理积分的内容
    getMark: function () {
        model.lastpage = ".managemark";
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 5) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        model.managemark.max_page = json.max_page;
                        model.managemark.url = json.url;
                        view.showManagemark(json);
                    }
                })
            }
        }
    },

    managemarkCurrentPage : function(){
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 5)
            temp = model.identity.root + model.identity.leftBar[i].api;
        $.ajax({
            url: temp,
            type: "POST",
            data: {
                "page": model.currentPage,
                "schoolid": $(".school").eq(4).find("option:selected").val(),
                "typeid": $(".type").eq(2).find("option:selected").val()
            },

            success: function (json) {
                view.showManagemark(json);
            }
        })
},

    markdet: function (index) {
        view.transform();
        ctrl.getDetail(index, 0);
        view.lookArticle();
    },

    markJudge: function (index, number) {
        $.ajax({
            url: model.identity.root + model.managemark.url.magscore_mark,
            type: "POST",
            datatype: "json",
            data: {
                "messageid": index,
                "add": $("#markadd" + number).val(),
                "substract": $("#markminus" + number).val(),
            },
            success: function (json) {
                if (json.is_err == 0) {
                    $("#marktotal" + number).text(json.result.score);
                    //view.showKey(json);
                } else {
                    alert(json.result);
                }

            }
        })
    },

    //获得管理关键字的内容
    getKeyword: function () {
        model.lastpage = ".managekeyword";
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 6) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        model.key.max_page = json.max_page;
                        model.key.url = json.url;
                        view.showKey(json);
                    }
                })
            }
        }
    },

    keydele: function (index) {
        if (confirm("确定要删除吗？")) {

            $.ajax({
                url: model.identity.root + model.key.url.key_del,
                type: "post",
                data: {
                    "id": index,
                    "page": model.currentPage
                },
                success: function (json) {
                    model.key.max_page = json.max_page;
                    view.showKey(json);
                }
            })
        }
    },

    //获取积分列表的内容
    getList: function () {
        model.lastpage = ".marklist";
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 7) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        model.marklist.max_page = json.max_page;
                        model.marklist.url = json.url;
                        view.showList(json);
                    }
                })
            }
        }
    },

    //查看学院报送的详情页
    listdet: function (index) {
        model.lastpage = ".school-detail";
        $.ajax({
            url: model.identity.root + model.marklist.url.scolist_schdetail,
            type: "POST",
            datatype: "json",
            data: {"schoolid": index},
            success: function (json) {
                model.listdet.schoolid = index;
                $(".marklist").css("display", "none");
                $(".school-detail").css("display", "block");
                model.listdet.url = json.url;
                model.listdet.max_page = json.max_page;
                view.showschdet(json, index);
            }
        })
    },

    schdetdet: function (index) {
        $("#pubButton").css("display", "none");
        $(".school-detail").css("display", "none");
        view.transform();
        ctrl.getDetailMarkList(index);
        view.lookArticle();

    },

    schdetdele: function (index) {
        if (confirm("确定要删除吗？")) {
            $.ajax({
                url: model.identity.root + model.listdet.url.scolist_del,
                type: "POST",
                data: {
                    'messageid': index,
                    "page": model.currentPage
                },
                success: function (json) {
                    if (json.is_err == 0) {
                        alert("删除成功！");
                        $.ajax({
                            url: model.identity.root + model.marklist.url.scolist_schdetail,
                            type: "POST",
                            data: {
                                "page": model.currentPage,
                                "schoolid": model.listdet.schoolid
                            },
                            success: function (json) {
                                if (json.is_err == 0) {
                                    model.listdet.max_page = json.max_page;
                                    view.showschdet(json);
                                }
                            }
                        });
                    } else {
                        alert(json.result);
                    }
                }
            })
        }
    },
    //获得在线人数的内容
    getOnline: function () {
        model.lastpage = ".online";
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 8) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        model.online.max_page = json.max_page;
                        model.online.url = json.url;
                        view.showOnline(json);
                    }
                })
            }
        }
    },

    //获得日志的内容
    getLog: function () {
        model.lastpage = ".log";
        for (var i = 0; i < model.identity.leftBar.length; i++) {
            if (model.identity.leftBar[i].keybind == 9) {
                $.ajax({
                    url: model.identity.root + model.identity.leftBar[i].api,
                    type: "GET",
                    datatype: "json",

                    success: function (json) {
                        model.log.max_page = json.max_page;
                        model.online.url = json.url;
                        view.showLog(json);
                    }
                })
            }
        }
    },

    //获得当前显示的页面，返回类的名字
    getCurrentClass: function () {
        if ($(".contain").css("display") == "block") {
            return ".contain";
        }
        else if ($(".opinion").css("display") == "block") {
            return ".opinion";
        }
        else if ($(".single-post").css("display") == "block") {
            return ".single-post";
        }
        else if ($(".integrative-post").css("display") == "block") {
            return ".integrative-post";
        }
        else if ($(".manager").css("display") == "block") {
            return ".manager";
        }
        else if ($(".collection").css("display") == "block") {
            return ".collection";
        }
        else if ($(".managemark").css("display") == "block") {
            return ".managemark";
        }
        else if ($(".managekeyword").css("display") == "block") {
            return ".managekeyword";
        }
        else if ($(".marklist").css("display") == "block") {
            return ".marklist";
        }
        else if ($(".online").css("display") == "block") {
            return ".online";
        }
        else if ($(".log").css("display") == "block") {
            return ".log";
        }
    },
    //返回单击菜单某行后显示的英文类名
    getClickMenu: function (string) {
        if (string == "首页")
            return ".contain";
        else if (string == "舆情公告")
            return ".opinion";
        else if (string == "单条信息报送")
            return ".single-post";
        else if (string == "综合信息报送")
            return ".integrative-post";
        else if (string == "管理用户")
            return ".manager";
        else if (string == "我的收藏")
            return ".collection";
        else if (string == "管理积分")
            return ".managemark";
        else if (string == "管理关键字")
            return ".managekeyword";
        else if (string == "积分列表")
            return ".marklist";
        else if (string == "在线人数")
            return ".online";
        else if (string = "日志")
            return ".log";
    },

    //保存用户更改的信息
    save: function () {

        da = {};
        if( model.manager.target == 'machan'){    //修改密碼
            if (!($(".newpassword").val() != "" && $(".newpassword").val() == $(".confirmpassword").val() && $(".originpassword").val() != "")) {
                    alert("请检查您的输入！");
                    return;
            }
           da = {
                "password":$('#newpass').val(),
                "originpassword":$('#oldpass').val(),
                 "userid":$('#userid').val()
                };
        }else if( model.manager.target = 'mapers'){   //修改個人信息
           da = {
                "realname":$('#realname').val(),
                "email":$('#email').val(),
                "phone":$('#phone').val(),
                "userid":$('#userid').val()
           }

        }
        $.ajax({
            url: model.identity.root + model.identity.update,
            type: "POST",
            data: da,
            success: function (json) {
                if (json.is_err == 0) {
                    alert("保存成功！");
                    $(".settings").css("display", "none");
                    $(".contain").css("display", "block");
                    $('#newpass').val("");
                    $('#oldpass').val("");
                    $('#conpass').val("");
                } else {
                    alert(json.result);
                }
            }
        })
    },

    //上传用户评论的信息
    comment: function () {
        if (model.lastpage == ".opinion")
            $.ajax({
                url: model.identity.root + model.pub.url.announce_update,
                type: "POST",
                data: {
                    "content": $(".froala-element")[0].innerHTML,
                    "announceid": model.pub.currentid
                },

                success: function () {
                    alert("发表成功！");
                    $(".details-comments").val("");
                    $(".details").css("display", "none");
                    $(".opinion-search").css("display", "block");
                    $(".opinion").css("display", "block");
                }
            })
        else if (model.lastpage == ".single-post") {
            $.ajax({
                url: model.identity.root + model.single.url.single_update,
                type: "POST",
                data: {
                    "content": $(".froala-element")[0].innerHTML,
                    "messageid": model.single.messageid
                },

                success: function () {
                    alert("发表成功！");
                    $(".details-comments").val("");
                    $(".details").css("display", "none");
                    $(".opinion-search").css("display", "block");
                    $(".single-post").css("display", "block");
                }
            })
        } else if (model.lastpage == ".integrative-post") {
            $.ajax({
                url: model.identity.root + model.integrative.url.overall_update,
                type: "POST",
                data: {
                    "content": $(".froala-element")[0].innerHTML,
                    "messageid": model.integrative.messageid
                },

                success: function () {
                    alert("发表成功！");
                    $(".details-comments").val("");
                    $(".details").css("display", "none");
                    $(".opinion-search").css("display", "block");
                    $(".integrative-post").css("display", "block");
                }
            })
        }

        //$.ajax({
        //	url: model.identity.root + model.single.url.single_update,
        //	type: "POST",
        //	datatype: "json",
        //	data: {"messageid":index},
        //	success: function() {
        //		view.transform();
        //	}
        //})
    },

    //上传用户发表的舆情信息
    singlesend: function () {
        if ($(".send-title input").val() == "" || $(".froala-element")[0].innerHTML == "") {
            alert("请完成基本的标题和内容！");
        } else {
            if ($(".send-key").css("display") == "block") {
                $.ajax({
                    url: model.identity.root + model.single.url.single_add,
                    type: "POST",
                    cache: false,
                    data: {
                        "typeid": $(".type").val(),
                        "title": $(".send-title input").val(),
                        "keyword": $(".send-key input").val(),
                        "source": $(".send-webpage input").val(),
                        "url": $(".send-website input").val(),
                        "content": $(".froala-element")[model.kepa].innerHTML
                    },

                    success: function (json) {
                        model.tempid = json.newid;
                        alert("推送成功！");
                        $(".single-send").css("display", "none");
                        $(".single-post").css("display", "block");
                        $(".send-title input").val("");
                        $(".send-key input").val("");
                        $(".send-webpage input").val("");
                        $(".send-website input").val("");
                        $(".froala-element")[model.kepa].innerHTML = "";
                        ctrl.getSingle();
                        if (ctrl.getCurrentClass() == ".opinion" || ctrl.getCurrentClass() == ".single-post" || ctrl.getCurrentClass() == ".integrative-post" || ctrl.getCurrentClass() == ".collection")
                            $(".opinion-search").css("display", "block");

                        $.ajax({
                            url: model.identity.root + model.single.url.add_file + "/?id=" + model.tempid,
                            type: "POST",
                            data: new FormData($('#send')[0]),
                            processData: false,
                            contentType: false,
                            success: function (json) {
                                alert(json.result);
                            }
                        })
                    }
                })

            } else if ($(".send-product").css("display") == "block") {
                $.ajax({
                    url: model.identity.root + model.integrative.url.overall_add,
                    type: "POST",
                    cache: false,
                    data: {
                        "proid": $(".product").val(),
                        "typeid": $(".type").val(),
                        "title": $(".send-title input").val(),
                        "content": $(".froala-element")[model.kepa].innerHTML
                    },
                    success: function (json) {
                        model.tempid = json.newid;
                        alert("推送成功！");
                        $(".single-send").css("display", "none");
                        $(".integrative-post").css("display", "block");
                        $(".send-title input").val("");
                        $(".send-key input").val("");
                        $(".send-webpage input").val("");
                        $(".send-website input").val("");
                        $(".froala-element")[model.kepa].innerHTML = "";
                        ctrl.getIntegrative();
                        if (ctrl.getCurrentClass() == ".opinion" || ctrl.getCurrentClass() == ".single-post" || ctrl.getCurrentClass() == ".integrative-post" || ctrl.getCurrentClass() == ".collection")
                            $(".opinion-search").css("display", "block");

                        $.ajax({
                            url: model.identity.root + model.integrative.url.add_file + "/?id=" + model.tempid,
                            type: "POST",
                            data: new FormData($('#send')[0]),
                            processData: false,
                            contentType: false,
                            success: function (json) {
                                alert(json.result);
                            }
                        })
                    }
                })
            } else {
                $.ajax({
                    url: model.identity.root + model.pub.url.announce_add,
                    type: "POST",
                    data: {
                        "title": $(".send-title input").val(),
                        "content": $(".froala-element")[model.kepa].innerHTML,
                    },
                    success: function (json) {
                        model.tempid = json.newid;
                        alert("推送成功！");
                        $(".single-send").css("display", "none");
                        $(".opinion").css("display", "block");
                        $(".send-title input").val("");
                        $(".froala-element")[model.kepa].innerHTML = "";
                        ctrl.getPub();
                        if (ctrl.getCurrentClass() == ".opinion" || ctrl.getCurrentClass() == ".single-post" || ctrl.getCurrentClass() == ".integrative-post" || ctrl.getCurrentClass() == ".collection")
                            $(".opinion-search").css("display", "block");

                        $.ajax({
                            url: model.identity.root + model.pub.url.add_file + "/?id=" + model.tempid + "&ispub=1",
                            type: "POST",
                            data: new FormData($('#send')[0]),
                            processData: false,
                            contentType: false,
                            success: function (json) {
                                alert(json.result);
                            }
                        })
                    }
                })
            }
        }
    },

    integrativesend: function () {
        $.ajax({
            url: model.identity.root + model.integrative.url.integrative_add,
            type: "POST",
            data: {
                "file": new FormData($("#send")[0]),
                "title": $(".send-title input").val(""),
                "content": $(".froala-element")[model.kepa].innerHTML
            },

            success: function () {
                alert("推送成功！");
                $(".single-send").css("display", "none");
                $(".integrative-post").css("display", "block");
            }
        })
    },

    //添加用户
    add: function () {
        if ($(".add-newpassword").val() == $(".add-confirmpassword").val())
            if ($(".add-newpassword").val() != "" && $(".add-confirmpassword").val() != "")
                $.ajax({
                    url: model.identity.root + model.manager.url.maguser_add,
                    type: "POST",
                    data: $("#add").serialize(),
                    success: function (json) {
                        if (json.is_err == 0) {
                            alert("添加成功！");
                            $(".add").css("display", "none");
                            $(".contain").css("display", "block");
                        } else {
                            alert(json.result);
                        }
                    }
                })
            else
                alert("请输入密码！");
        else
            alert("密码不相同！");
    },

    lookArticle: function () {
        $("#article1").css("display", "block");
        $("#article2").css("display", "none");
    },

    editArticle: function () {
        $("#article1").css("display", "none");
        $("#article2").css("display", "block");
    },

    getCurrentMenu: function () {
        var s = ctrl.getCurrentClass();
        switch (s) {
            case ".contain":
                return ".menu0";
            case ".opinion":
                return ".menu1";
            case ".single-post":
                return ".menu2";
            case ".integrative-post":
                return ".menu3";
            case ".manager":
                return ".menu4";
            case ".collection":
                if(model.identity.groupid == 3)
                    return ".menu5";
                else if(model.identity.groupid == 2)
                    return ".menu4";
                else if(model.identity.groupid == 1)
                    return ".menu3";
            case ".managemark":
                return ".menu6";
            case ".managekeyword":
                return ".menu7";
            case ".marklist":
                if(model.identity.groupid == 3)
                    return ".menu8";
                else
                    return ".menu5";
            case ".online":
                return ".menu9";
            case ".log":
                return ".menu10";
        }
    }
}