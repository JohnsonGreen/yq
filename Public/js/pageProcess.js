model.identity.root = "index.php/";


function schdetFirstPage() {

    model.currentPage = 1;
    $.ajax({
        url: model.identity.root + model.marklist.url.scolist_schdetail,
        type: "POST",
        data: {
            "page": model.currentPage,
            "schoolid": model.listdet.schoolid
        },

        success: function (json) {
            view.showschdet(json);
        }
    })
}

function schdetFrontPage(index) {

    if (model.currentPage != 1) {
        model.currentPage--;
        $.ajax({
            url: model.identity.root + model.marklist.url.scolist_schdetail,
            type: "POST",
            data: {
                "page": model.currentPage,
                "schoolid": model.listdet.schoolid
            },

            success: function (json) {
                view.showschdet(json);
            }
        })
    }
}

function schdetNextPage() {
    if (model.currentPage != model.listdet.max_page) {
        model.currentPage++;
        $.ajax({
            url: model.identity.root + model.marklist.url.scolist_schdetail,
            type: "POST",
            data: {
                "page": model.currentPage,
                "schoolid": model.listdet.schoolid
            },

            success: function (json) {
                view.showschdet(json);
            }
        })
    }
}

function schdetMaxPage() {

    model.currentPage = model.listdet.max_page;
    $.ajax({
        url: model.identity.root + model.marklist.url.scolist_schdetail,
        type: "POST",
        data: {
            "page": model.currentPage,
            "schoolid": model.listdet.schoolid
        },

        success: function (json) {
            view.showschdet(json);
        }
    })
}


function logFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 9)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showLog(json);
        }
    })
}

function logFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 9)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showLog(json);
            }
        })
    }
}

function logNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 9)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.log.max_page) {
        model.currentPage++;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showLog(json);
            }
        })
    }
}

function logMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 9)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.log.max_page;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showLog(json);
        }
    })
}

function onlineFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 8)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showOnline(json);
        }
    })
}

function onlineFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 8)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showOnline(json);
            }
        })
    }
}

function onlineNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 8)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.online.max_page) {
        model.currentPage++;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showOnline(json);
            }
        })
    }
}

function onlineMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 8)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.online.max_page;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showOnline(json);
        }
    })
}

function listFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 7)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showList(json);
        }
    })
}

function listFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 7)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showList(json);
            }
        })
    }
}

function listNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 7)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.marklist.max_page) {
        model.currentPage++;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showList(json);
            }
        })
    }
}

function listMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 7)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.marklist.max_page;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showList(json);
        }
    })
}


function keyFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 6)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showKey(json);
        }
    })
}

function keyFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 6)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showKey(json);
            }
        })
    }
}

function keyNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 6)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.key.max_page) {
        model.currentPage++;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showKey(json);
            }
        })
    }
}

function keyMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 6)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.key.max_page;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showKey(json);
        }
    })
}

function managemarkFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 5)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
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
}

function managemarkFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 5)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
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
    }
}

function managemarkNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 5)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.managemark.max_page) {
        model.currentPage++;
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
    }
}

function managemarkMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 5)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.managemark.max_page;
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
}

function collectionFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 4)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
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
            view.showCollection(json);
        }
    })
}

function collectionFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 4)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
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
                view.showCollection(json);
            }
        })
    }
}

function collectionNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 4)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.collection.max_page) {
        model.currentPage++;
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
                view.showCollection(json);
            }
        })
    }
}

function collectionMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 4)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.collection.max_page;
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
            view.showCollection(json);
        }
    })
}

function managerFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 3)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showManager(json);
        }
    })
}

function managerFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 3)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showManager(json);
            }
        })
    }
}

function managerNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 3)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.manager.max_page) {
        model.currentPage++;
        $.ajax({
            url: temp,
            type: "POST",
            data: {"page": model.currentPage},

            success: function (json) {
                view.showManager(json);
            }
        })
    }
}

function managerMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 3)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.manager.max_page;
    $.ajax({
        url: temp,
        type: "POST",
        data: {"page": model.currentPage},

        success: function (json) {
            view.showManager(json);
        }
    })
}

function integrativeFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 2)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
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
            view.showIntegrative(json);
        }
    })
}

function integrativeFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 2)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
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
                view.showIntegrative(json);
            }
        })
    }
}

function integrativeNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 2)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.integrative.max_page) {
        model.currentPage++;
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
                view.showIntegrative(json);
            }
        })
    }
}

function integrativeMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 2)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.integrative.max_page;
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
            view.showIntegrative(json);
        }
    })
}

function singleFirstPage() {

    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 1)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
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
            view.showSingle(json);
        }
    })
}

function singleFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 1)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != 1) {
        model.currentPage--;
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
                view.showSingle(json);
            }
        })
    }
}

function singleNextPage() {

    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 1)
            temp = model.identity.root + model.identity.leftBar[i].api;

    if (model.currentPage != model.single.max_page) {
        model.currentPage++;
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
                view.showSingle(json);
            }
        })
    }
}




function singleMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 1)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = model.single.max_page;
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
            view.showSingle(json);
        }
    })

}


function pubFirstPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 0)
            temp = model.identity.root + model.identity.leftBar[i].api;

    model.currentPage = 1;
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
            view.showPub(json);
        }
    })
}
function pubFrontPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 0)
            temp = model.identity.root + model.identity.leftBar[i].api;
    if (model.currentPage != 1) {
        model.currentPage--;
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
                view.showPub(json);
            }
        })
    }
}
function pubNextPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 0)
            temp = model.identity.root + model.identity.leftBar[i].api;
    if (model.currentPage != model.pub.max_page) {
        model.currentPage++;
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
                view.showPub(json);
            }
        })
    }

}
function pubMaxPage() {
    var temp;
    for (var i = 0; i < model.identity.leftBar.length; i++)
        if (model.identity.leftBar[i].keybind == 0)
            temp = model.identity.root + model.identity.leftBar[i].api;
    model.currentPage = model.pub.max_page;
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
            view.showPub(json);
        }
    })
}