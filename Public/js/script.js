$(document).ready(function() {
	view.showMenu();
	//判断是否登陆
	$.ajax({
		url: "/yq/index.php/Index/login",
		type: "GET",
		datatype: "json",

		success: function(json) {
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

	//获得学院名称
	$.ajax({
		url: "/yq/index.php/Admin/Index/school",
		type: "GET",
		datatype: "json",

		success: function(json) {
			for(var i = 0; i < json.length; i++) {
				$(".school").append('<option value="'+json[i].schname+'">'+json[i].schname+'</option>');
			}
		}
	})

	//获得类型名称
	$.ajax({
		url: "/yq/index.php/Admin/Index/type",
		type: "GET",
		datatype: "json",

		success: function(json) {
			for(var i = 0; i < json.length; i++) {
				$(".type").append('<option value="'+json[i].type+'">'+json[i].type+'</option>');
			}
		}
	})
})

var model = {
	//菜单的内容，用数组表示
	data: {},
	menu: ["首页", "舆情公告", "单条信息报送", "综合信息报送", "管理用户", "我的收藏", "管理积分", "管理关键字", "积分列表", "在线人数", "日志"],
	ranks: ["化工学院", "机械学院", "建工学院"],
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
}

var ctrl = {
	//获得首页的内容信息
	getInformation: function() {
		$.ajax({
			url: "/yq/index.php/Admin/Index/index",
			type: "GET",
			datatype: "json",

			success: function(json) {
				model.data = json.result;
				console.log("data has already finished");
				ctrl.getRank();
				ctrl.getNews();
				ctrl.getPublics();
			}
		})
	},

	//获得菜单页的信息
	getMenu: function() {
		$.ajax({
			url: "/yq/index.php/Admin/Index/",
			type: "GET",
			datatype: "json",

			success: function(json) {
				model.menu = [];
				for(var i = 0; i < json.length; i++) {
					model.menu[i] = json[i].permname;
				}
				view.showMenu();
			}
		})
	},

	//获得当前的主页面,调整各个部分的布局方式  
	getTitle: function(i) {
		$(ctrl.getCurrentClass()).css("display", "none");
		$(ctrl.getClickMenu(model.menu[i])).css("display", "block");
		if(ctrl.getCurrentClass() == ".opinion" || ctrl.getCurrentClass() == ".single-post" || ctrl.getCurrentClass() == ".integrative-post" || ctrl.getCurrentClass() == ".collection")
			$(".opinion-search").css("display", "block");
		else
			$(".opinion-search").css("display", "none");
	},
	//获得前三的排名
	getRank: function() {
		model.ranks = [];
		for(var i = 0; i < 3; i++) {
			model.ranks[i] = model.data.score_three[i].schname;
		}
		view.showRank();
	},
	//获取最新报送
	getNews: function() {
		$(".news-content").empty();
		var s = model.data.message;
		for(var i = 0; i < s.length; i++){
			$(".news").append('<span>'+s[i].title+'</span>');
			$(".news").append('<span style="float: right;">'+s[i].createtime+'</span>');
		}
	},
	//获取舆情公告
	getPublics: function() {
		$(".public-content").empty();
		var s = model.data.announcement;
		for(var i = 0; i < s.length; i++) {
			$(".public").append('<span>'+s[i].title+'</span>');
			$(".public").append('<span style="float: right;">'+s[i].createtime+'</span>');
		}
	},

	//获取舆情公告的具体内容
	getPub: function() {
		$.ajax({
			url: "/yq/index.php/Admin/Announce/index",
			type: "GET",
			datatype: "json",

			success: function(json) {
				$(".opinion-content").empty();
				var s = json.result;
				for(var i = 0; i < s.length; i++) {
					$(".opinion-content").append('<div class="opinion-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
					$(".opinion-contentDet"+i).append('<span class="opinion-callback">'+s[i].reply+'</span>');
					$(".opinion-contentDet"+i).append('<span style="text-align: left;" class="opinion-title"><img style="text-align: left; margin-left: 15%;" src="Public/images/sound.png"><a style="margin-left: 20px;" onclick="opdetail('+s[i]+')">'+s[i].title+'</a></span>');
					$(".opinion-contentDet"+i).append('<span class="opinion-author">'+s[i].realname+'</span>');
					$(".opinion-contentDet"+i).append('<span class="opinion-time">'+s[i].createtime+'</span>');
					$(".opinion-contentDet"+i).append('<span class="opinion-operation"><a onclick="ctrl.opedit('+s[i]+')">编辑</a><a onclick="ctrl.opup('+s[i]+')">置顶</a><a onclick="ctrl.opdele('+s[i]+')">删除</a></span>');
				}
			}
		})
	},

	//获得单条信息报送的具体内容
	getSingle: function() {
		$.ajax({
			url: "/yq/index.php/Admin/Message/single",
			type: "GET",
			async:false,
			datatype: "json",

			success: function(json) {
				$(".single-content").empty();
				var s = json;
				for(var i = 0; i < s.length; i++) {
					$(".single-content").append('<div class="single-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
					$(".single-contentDet"+i).append('<span class="single-number">'+s[i].messageid+'</span>');
					$(".single-contentDet"+i).append('<span class="single-type">'+s[i].type+'</span>');
					$(".single-contentDet"+i).append('<span class="single-title"><a style="margin-left: 15%; float: left;" onclick="sidetail('+s[i]+')">'+s[i].title+'</a></span>');
					$(".single-contentDet"+i).append('<span class="single-mark">'+s[i].score+'</span>');
					$(".single-contentDet"+i).append('<span class="single-unit">'+s[i].schname+'</span>');
					$(".single-contentDet"+i).append('<span class="single-time">'+s[i].createtime+'</span>');
					$(".single-contentDet"+i).append('<span class="single-operation"><a onclick="ctrl.siedit('+s[i]+')">编辑</a><a onclick="ctrl.siup('+s[i]+')">置顶</a><a onclick="ctrl.sidele('+s[i]+')">删除</a></span>');
				}
			}
		})
	},

	//获得综合信息报送的内容
	getIntegrative: function() {
		$.ajax({
			url: "/yq/index.php/Admin/Message/single",
			type: "GET",
			datatype: "json",

			success: function(json) {
				$(".integrative-content").empty();
				var s = json;
				for(var i = 0; i < s.length; i++) {
					$(".integrative-content").append('<div class="integrative-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
					$(".integrative-contentDet"+i).append('<span class="integrative-number">'+s[i].messageid+'</span>');
					$(".integrative-contentDet"+i).append('<span class="integrative-type">'+s[i].type+'</span>');
					$(".integrative-contentDet"+i).append('<span class="integrative-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.sidetail('+s[i]+')">'+s[i].title+'</a></span>');
					$(".integrative-contentDet"+i).append('<span class="integrative-mark">'+s[i].score+'</span>');
					$(".integrative-contentDet"+i).append('<span class="integrative-unit">'+s[i].schname+'</span>');
					$(".integrative-contentDet"+i).append('<span class="integrative-time">'+s[i].createtime+'</span>');
					$(".integrative-contentDet"+i).append('<span class="integrative-operation"><a onclick="ctrl.siedit('+s[i]+')">编辑</a><a onclick="ctrl.siup('+s[i]+')">置顶</a><a onclick="ctrl.sidele('+s[i]+')">删除</a></span>');
				}
			}
		})
	},

	//获得管理用户内容
	getManager: function() {
		$.ajax({
			url: "/yq/index.php/Admin/User/index",
			type: "GET",
			datatype: "json",

			success: function(json) {
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
						$(".manager-contentDet"+i).append('<span class="manager-operation"><a onclick="ctrl.maedit('+s[i]+')">编辑</a><a onclick="ctrl.madele('+s[i]+')">删除</a><a onclick="ctrl.machange('+s[i]+')">修改密码</a></span>');
				}
			}
		})
	},

	//获得我的收藏页的内容
	getCollection: function() {
		$.ajax({
			url: "/yq/index.php/Admin/Love/index",
			type: "GET",
			datatype: "json",

			success: function(json) {
				$(".collection-content").empty();
				var s = json.result;
				for(var i = 0; i < s.length; i++) {
					$(".collection-content").append('<div class="collection-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
					$(".collection-contentDet"+i).append('<span class="collection-number">'+s[i].messageid+'</span>');
					$(".collection-contentDet"+i).append('<span class="collection-type">'+s[i].type+'</span>');
					$(".collection-contentDet"+i).append('<span class="collection-title"><a style="margin-left: 15%; float: left;" onclick="codetail('+s[i]+')">'+s[i].title+'</a></span>');
					$(".collection-contentDet"+i).append('<span class="collection-mark">'+s[i].score+'</span>');
					$(".collection-contentDet"+i).append('<span class="collection-unit">'+s[i].schname+'</span>');
					$(".collection-contentDet"+i).append('<span class="collection-time">'+s[i].createtime+'</span>');
					$(".collection-contentDet"+i).append('<span class="collection-click">'+s[i].click+'</span>');
					$(".collection-contentDet"+i).append('<span class="collection-operation"><a onclick="ctrl.codele('+s[i]+')">删除</a></span>');
				}
			}
		})
	},

	//获得管理积分的内容
	getMark: function() {
		$.ajax({
			url: "/yq/index.php/Admin/ManageScore",
			type: "GET",
			datatype: "json",

			success: function(json) {
				$(".managemark-content").empty();
				var s = json.result;
				for(var i = 0; i < s.length; i++) {
					$(".managemark-content").append('<div class="managemark-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
					$(".managemark-contentDet"+i).append('<span class="managemark-number">'+s[i].messageid+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-origin">'+s[i].schname+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-title"><a style="margin-left: 15%; float: left;" onclick="ctrl.markdet('+s[i]+')">'+s[i].title+'</a></span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-total">'+s[i].score+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-mark">'+s[i].base+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-xuanyong">'+s[i].select+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-pishi">'+s[i].approval+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-yujing">'+s[i].warning+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-zhiliang">'+s[i].quality+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-zhuanxiang">'+s[i].special+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-minus">'+s[i].substract+'</span>');
					$(".managemark-contentDet"+i).append('<span class="managemark-operation"><a onclick="ctrl.markdele('+s[i]+')">删除</a></span>');
				}
			}
		})
	},

	//获得管理关键字的内容
	getKeyword: function() {
		$.ajax({
			url: "/yq/index.php/Admin/Key/index",
			type: "GET",
			datatype: "json",

			success: function(json) {
				$(".managekeyword-content").empty();
				var s = json.result;
				for(var i = 0; i < s.length; i++) {
					$(".managekeyword-content").append('<div class="managekeyword-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
					$(".managekeyword-contentDet"+i).append('<span class="managekeyword-number">'+s[i].keyid+'</span>');
					$(".managekeyword-contentDet"+i).append('<span class="managekeyword-keyword">'+s[i].keyname+'</span>');
					$(".managekeyword-contentDet"+i).append('<span class="managekeyword-time">'+s[i].createtime+'</span>');
					$(".managekeyword-contentDet"+i).append('<span class="managekeyword-operation"><a onclick="ctrl.keydele('+s[i]+')">删除</a></span>');
				}
			}
		})
	},

	//获取积分列表的内容
	getList: function() {
		$.ajax({
			url: "/yq/index.php/Admin/ManageScore/getSchoolScoreList",
			type: "GET",
			datatype: "json",

			success: function(json) {
				$(".marklist-content").empty();
				var s = json.result;
				for(var i = 0; i < s.length; i++) {
					$(".marklist-content").append('<div class="marklist-contentDet'+i+'" style="color:black; width: 100%; height: 38px;"></div>');
					$(".marklist-contentDet"+i).append('<span class="marklist-rank">'+s[i].schoolid+'</span>');
					$(".marklist-contentDet"+i).append('<span class="marklist-school">'+s[i].schname+'</span>');
					$(".marklist-contentDet"+i).append('<span class="marklist-total">'+s[i].score+'</span>');
					$(".marklist-contentDet"+i).append('<span class="marklist-detail"><a onclick="ctrl.listdet('+s[i]+')">查看详情</a></span>');
				}
			}
		})
	},

	//获得在线人数的内容
	getOnline: function() {
		$.ajax({
			url: "/yq/index.php/Admin/User/online",
			type: "GET",
			datatype: "json",

			success: function(json) {
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
			}
		})
	},

	//获得日志的内容
	getLog: function() {
		$.ajax({
			url: "/yq/index.php/Admin/User/user",
			type: "GET",
			datatype: "json",

			success: function(json) {
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
		})
	},

	//获得当前显示的页面，返回类的名字
	getCurrentClass: function() {
		if($(".contain").css("display") == "block") {
			ctrl.getPub();
			return ".contain";
		}
		else if($(".opinion").css("display") == "block") {
			return ".opinion";
		}
		else if($(".single-post").css("display") == "block") {
			ctrl.getSingle();
			return ".single-post";
		}
		else if($(".integrative-post").css("display") == "block") {
			ctrl.getIntegrative();
			return ".integrative-post";
		}
		else if($(".manager").css("display") == "block") {
			ctrl.getManager();
			return ".manager";
		}
		else if($(".collection").css("display") == "block") {
			ctrl.getCollection();
			return ".collection";
		}
		else if($(".managemark").css("display") == "block") {
			ctrl.getMark();
			return ".managemark";
		}
		else if($(".managekeyword").css("display") == "block") {
			ctrl.getKeyword();
			return ".managekeyword";
		}
		else if($(".marklist").css("display") == "block") {
			ctrl.getList();
			return ".marklist";
		}
		else if($(".online").css("display") == "block") {
			ctrl.getOnline();
			return ".online";
		}
		else if($(".log").css("display") == "block") {
			ctrl.getLog();
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
		$.ajax({
			url: "",
			type: "POST",

			success: function() {
				alert("保存成功！");
				$(".settings").css("display", "none");
				$(".contain").css("display", "block");
			}
		})
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
	},

	//上传用户发表的舆情信息
	send: function() {
		$.ajax({
			url: "",
			type: "POST",

			success: function() {
				alert("推送成功！");
				$(".send").css("display", "none");
				$(".contain").css("display", "block");
			}
		})
	}
}