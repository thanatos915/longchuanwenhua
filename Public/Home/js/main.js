function alert(f, c, b) {
    var e = new iDialog();
    var a = {
        classList: "alert",
        title: "",
        close: "",
        content: '<div class="icon success"></div><div style="padding:10px 30px;line-height:23px;text-align:center;font-size:16px;color:#ffffff;">' + f + "</div>"
    };
    var g = null;
    if (b) {
        a.btns = [{
            id: "",
            name: "确定",
            onclick: "fn.call();",
            fn: function(d) { ! b.call() && d.die();
                c && clearTimeout(g)
            }
        }]
    }
    e.open(a);
    if (c) {
        g = setTimeout(function() {
            e.die();
            clearTimeout(g)
        },
        c)
    }
}
function alertNew(f, c, b) {
    var e = new iDialog();
    var a = {
        classList: "alert",
        title: "",
        close: "",
        content: '<div class="icon success"></div><div style="padding:10px 30px;line-height:23px;text-align:center;font-size:16px;color:#ffffff;">' + f + "</div>"
    };
    var g = null;
    if (b) {
        a.btns = [{
            id: "",
            name: "确定",
            onclick: "fn.call();",
            fn: function(d) { ! b.call() && d.die();
                c && clearTimeout(g)
            }
        }]
    }
    e.open(a);
    if (c) {
        g = setTimeout(function() {
            e.die();
            clearTimeout(g)
        },
        c)
    }
}
function confirm(f, c, b) {
    var e = new iDialog();
    var a = {
        classList: "tip",
        title: "",
        close: "",
        content: f
    };
    a.btns = [{
        id: "",
        name: "确定",
        onclick: "fn.call();",
        fn: function(d) {
            c && c.call(this);
            d.die()
        }
    },
    {
        id: "",
        name: "取消",
        onclick: "fn.call();",
        fn: function(d) {
            b && b.call(this);
            d.die()
        }
    }];
    e.open(a)
}
function tip(c, a) {
    var b = new iDialog();
    b.open({
        classList: "tip",
        title: "",
        close: "",
        content: c,
        btns: [{
            id: "",
            name: "确定",
            onclick: "fn.call();",
            fn: function(d) {
                console.log("queding")
            }
        },
        {
            id: "",
            name: "取消",
            onclick: "fn.call();",
            fn: function(d) {
                d.die()
            }
        }]
    })
}
function loading(a) {
    if (a) {
        window.loader = new iDialog();
        window.loader.open({
            classList: "loading",
            title: "",
            close: "",
            content: ""
        })
    } else {
        window.loader.die();
        delete window.loader
    }
}
var my = (function() {
    _my = function() {};
    _my.prototype = {
        changeImg: function(b, a, d) {
            var c = this;
            if (b.files.length < 1) {
                return c
            }
            setTimeout(function() {
                c.uploadImg(b, d)
            },
            500);
            return c
        },
        uploadImg: function(a, e) {
            var d = this;
            var c = {
                id: 6,
                username: "webAdd",
                header_img_id: a.files[0] || {},
                form_action: "/Webnewmemberscore/uploadhead",
                base_url: "http://115.28.20.245:3000/headers/"
            };
            for (var b in e) {
                c[b] = e[b]
            }
            var f = window.ajax2(c.form_action, {
                type: "POST",
                async: true,
                data: c,
                timeout: 10000 * 6,
                callback: function(g) {
                    if (0 == g.result) {
                        alert(g.message)
                    } else {
                        a.parentNode.querySelectorAll("img")[0].src = c.base_url + g.data.header_img_id
                    }
                    setTimeout(function() {
                        a.parentNode.removeAttribute("data-upload-state")
                    },
                    500)
                }
            });
            f.onprogress = function(g) {
                a.parentNode.setAttribute("data-upload-state", Math.floor(g) + "%")
            };
            return d
        }
    };
    return new _my()
})();
function getVCode(b, a, f, c) {
    if (f) {
        var e = document.getElementById(f);
        var d = {
            telephone: $.trim(e[c].value)
        };
        if (!d.telephone) {
            alert("请输入手机号", 1000);
            return
        }
    } else {
        var d = {}
    }
    b.setAttribute("disabled", "disabled");
    b.value = "60秒后可重新获取";
    $.ajax({
        url: "data/getVCode.json",
        type: "post",
        data: d,
        dataType: "JSON",
        success: function(g) {
            if (1 == g.result) {
                var h = 60;
                var i = function() {
                    setTimeout(function() {
                        h--;
                        if (h > 0) {
                            b.value = h + "秒后可重新获取";
                            i()
                        } else {
                            b.removeAttribute("disabled");
                            b.value = "获取验证码"
                        }
                    },
                    1000)
                };
                i()
            } else {
                alert("失败", 1500)
            }
        }
    })
}
function getLocation(c, f, d) {
    var a = [];
    try {
        a.push(aLocation["0"][c]);
        a.push(aLocation["0," + c][f]);
        a.push(aLocation["0," + c + "," + f][d])
    } catch(b) {} finally {
        return a.join(" ")
    }
}
var integral = (function() {
    var a = function(c) {
        this.pageIndex = 0;
        this.pageSize = 10;
        this.maxPage = Infinity;
        this.TPL = "<tr><td>{info} </td><td>{time} </td><td>{integral}</td></tr>";
        this.req = {
            pageIndex: this.pageIndex,
            pageSize: this.pageSize
        };
        for (var b in c) {
            this.req[b] = c[b]
        }
    };
    a.prototype = {
        getData: function(b) {
            var c = this;
            c.req.pageIndex++;
            loading(true);
            $.ajax({
                url: c.url || "data/integralList.json",
                type: "post",
                data: c.req,
                dataType: "JSON",
                success: function(d) {
                    loading(false);
                    if (1 == d.result) {
                        c.total = d.total;
                        c.maxPage = Math.ceil(d.total / c.pageSize);
                        b.call(c, c.formatData(d.data))
                    } else {
                        c.total = d.total || c.total;
                        alert("失败", 1500)
                    }
                }
            });
            return c
        },
        nextPage: function(b) {
            var c = this;
            c.getData(b);
            return c
        },
        formatData: function(c) {
            var b = this;
            return iTemplate.makeList(b.TPL, c,
            function(e, d) {})
        }
    };
    return a
})();
document.addEventListener("WeixinJSBridgeReady",
function onBridgeReady() {
    WeixinJSBridge.call("hideToolbar")
});
document.addEventListener("WeixinJSBridgeReady",
function onBridgeReady() {
    WeixinJSBridge.call("hideOptionMenu")
});