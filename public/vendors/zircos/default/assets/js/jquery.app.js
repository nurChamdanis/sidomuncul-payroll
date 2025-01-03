function executeFunctionByName(e, s) {
    for (var t = [].slice.call(arguments).splice(2), l = e.split("."), i = l.pop(), r = 0; r < l.length; r++) s = s[l[r]];
    return s[i].apply(this, t)
}

function resizeitems() {
    if ($.isArray(resizefunc))
        for (i = 0; i < resizefunc.length; i++) window[resizefunc[i]]()
}

function initscrolls() {
    jQuery.browser.mobile !== !0 && ($(".slimscroller").slimscroll({
        height: "auto",
        size: "5px"
    }), $(".slimscrollleft").slimScroll({
        height: "auto",
        position: "right",
        size: "5px",
        color: "#dcdcdc",
        wheelStep: 5
    }))
}

function toggle_slimscroll(e) {
    $("#wrapper").hasClass("enlarged") ? ($(e).css("overflow", "inherit").parent().css("overflow", "inherit"), $(e).siblings(".slimScrollBar").css("visibility", "hidden")) : ($(e).css("overflow", "hidden").parent().css("overflow", "hidden"), $(e).siblings(".slimScrollBar").css("visibility", "visible"))
}! function(e) {
    "use strict";
    var s = function() {
        this.$body = e("body"), this.$openLeftBtn = e(".open-left"), this.$menuItem = e("#sidebar-menu a")
    };
    s.prototype.openLeftBar = function() {
        e("#wrapper").toggleClass("enlarged"), e("#wrapper").addClass("forced"), e("#wrapper").hasClass("enlarged") && e("body").hasClass("fixed-left") ? e("body").removeClass("fixed-left").addClass("fixed-left-void") : !e("#wrapper").hasClass("enlarged") && e("body").hasClass("fixed-left-void") && e("body").removeClass("fixed-left-void").addClass("fixed-left"), e("#wrapper").hasClass("enlarged") ? e(".left ul").removeAttr("style") : e(".subdrop").siblings("ul:first").show(), toggle_slimscroll(".slimscrollleft"), e("body").trigger("resize")
    }, s.prototype.menuItemClick = function(s) {
        e("#wrapper").hasClass("enlarged") || (e(this).parent().hasClass("has_sub"), e(this).hasClass("subdrop") ? e(this).hasClass("subdrop") && (e(this).removeClass("subdrop"), e(this).next("ul").slideUp(350), e(".pull-right i", e(this).parent()).removeClass("md-remove").addClass("md-add")) : (e("ul", e(this).parents("ul:first")).slideUp(350), e("a", e(this).parents("ul:first")).removeClass("subdrop"), e("#sidebar-menu .pull-right i").removeClass("md-remove").addClass("md-add"), e(this).next("ul").slideDown(350), e(this).addClass("subdrop"), e(".pull-right i", e(this).parents(".has_sub:last")).removeClass("md-add").addClass("md-remove"), e(".pull-right i", e(this).siblings("ul")).removeClass("md-remove").addClass("md-add")))
    }, s.prototype.init = function() {
        var s = this,
            t = navigator.userAgent,
            l = t.match(/iP/i) ? "touchstart" : "click";
        this.$openLeftBtn.on(l, function(e) {
            e.stopPropagation(), s.openLeftBar()
        }), s.$menuItem.on(l, s.menuItemClick), e("#sidebar-menu ul li.has_sub a.active").parents("li:last").children("a:first").addClass("active").trigger("click")
    }, e.Sidemenu = new s, e.Sidemenu.Constructor = s
}(window.jQuery),
function(e) {
    "use strict";
    var s = function() {
        this.$body = e("body"), this.$fullscreenBtn = e("#btn-fullscreen")
    };
    s.prototype.launchFullscreen = function(e) {
        e.requestFullscreen ? e.requestFullscreen() : e.mozRequestFullScreen ? e.mozRequestFullScreen() : e.webkitRequestFullscreen ? e.webkitRequestFullscreen() : e.msRequestFullscreen && e.msRequestFullscreen()
    }, s.prototype.exitFullscreen = function() {
        document.exitFullscreen ? document.exitFullscreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitExitFullscreen && document.webkitExitFullscreen()
    }, s.prototype.toggle_fullscreen = function() {
        var e = this,
            s = document.fullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled;
        s && (document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement ? e.exitFullscreen() : e.launchFullscreen(document.documentElement))
    }, s.prototype.init = function() {
        var e = this;
        e.$fullscreenBtn.on("click", function() {
            e.toggle_fullscreen()
        })
    }, e.FullScreen = new s, e.FullScreen.Constructor = s
}(window.jQuery),
function(e) {
    "use strict";
    var s = function() {
        this.VERSION = "1.0.0", this.AUTHOR = "irfan", this.SUPPORT = "irfan@arkamaya.co.id", this.pageScrollElement = "html, body", this.$body = e("body")
    };
    s.prototype.onDocReady = function(s) {
        FastClick.attach(document.body), resizefunc.push("initscrolls"), resizefunc.push("changeptype"), e(".animate-number").each(function() {
            e(this).animateNumbers(e(this).attr("data-value"), !0, parseInt(e(this).attr("data-duration")))
        }), e(window).resize(debounce(resizeitems, 100)), e("body").trigger("resize"), e(".right-bar-toggle").on("click", function(s) {
            e("#wrapper").toggleClass("right-bar-enabled")
        })
    }, s.prototype.init = function() {
        var s = this;
        e(document).ready(s.onDocReady), e.Sidemenu.init(), e.FullScreen.init()
    }, e.App = new s, e.App.Constructor = s
}(window.jQuery),
function(e) {
    "use strict";
    e.App.init()
}(window.jQuery);
var toggle_fullscreen = function() {},
    w, h, dw, dh, changeptype = function() {
        w = $(window).width(), h = $(window).height(), dw = $(document).width(), dh = $(document).height(), jQuery.browser.mobile === !0 && $("body").addClass("mobile").removeClass("fixed-left"), $("#wrapper").hasClass("forced") || (w > 1024 ? ($("body").removeClass("smallscreen").addClass("widescreen"), $("#wrapper").removeClass("enlarged")) : ($("body").removeClass("widescreen").addClass("smallscreen"), $("#wrapper").addClass("enlarged"), $(".left ul").removeAttr("style")), $("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left") ? $("body").removeClass("fixed-left").addClass("fixed-left-void") : !$("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left-void") && $("body").removeClass("fixed-left-void").addClass("fixed-left")), toggle_slimscroll(".slimscrollleft")
    },
    debounce = function(e, s, t) {
        var l, i;
        return function() {
            var r = this,
                n = arguments,
                o = function() {
                    l = null, t || (i = e.apply(r, n))
                },
                a = t && !l;
            return clearTimeout(l), l = setTimeout(o, s), a && (i = e.apply(r, n)), i
        }
    };
$(document).ready(function() {
	var wlh = '';
    $("#sidebar-menu a").each(function() {
		if (this.href == SITE_URL + location.pathname.split('/')[2])
		{
			$(this).addClass("active")
			, $(this).parent().addClass("active")
			, $(this).parent().parent().prev().addClass("active")
			, $(this).parent().parent().prev().click()
		}
        // this.href == window.location.href && 
		// (
		// 	$(this).addClass("active")
		// 	, $(this).parent().addClass("active")
		// 	, $(this).parent().parent().prev().addClass("active")
		// 	, $(this).parent().parent().prev().click()
		// )
    })
});