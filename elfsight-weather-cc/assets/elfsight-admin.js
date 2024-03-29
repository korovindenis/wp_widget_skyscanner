/*
    Weather
    Version: 1.1.0
    Release date: Thu Apr 16 2020

    https://elfsight.com

    Copyright (c) 2020 Elfsight, LLC. ALL RIGHTS RESERVED
*/

"use strict";
! function (e, t) {
    var i, a, n, s, o, r, d, g, p, l, c, f = !1;

    function h() {
        this.pages = []
    }
    h.prototype = {
        constructor: h,
        add: function (t, i) {
            if (!t || !i || !e.isFunction(i)) return !1;
            this.pages[t] = i() || {}
        },
        get: function (e) {
            return this.pages[e] || !1
        },
        show: function (t, i) {
            var r, p = this,
                l = this.get(t);
            return !!l && ("init" in l && e.isFunction(l.init) && (r = l.init(i)), e.when(r).then(function () {
                d.hasClass("elfsight-admin-other-products-hidden-permanently") || d.toggleClass("elfsight-admin-other-products-hidden", "widgets" !== t), a.removeClass("elfsight-admin-loading"), s.removeClass("active"), s.filter("[data-elfsight-admin-page=" + t + "]").addClass("active"), g.length && g.removeClass("elfsight-admin-page-active"), g = n.filter("[data-elfsight-admin-page-id=" + t + "]"), o.css("min-height", g.outerHeight()), g.addClass("elfsight-admin-page-animation elfsight-admin-page-active"), setTimeout(function () {
                    g.removeClass("elfsight-admin-page-animation"), "function" == typeof p.onPageChanged && p.onPageChanged(t)
                }, 200)
            }), l)
        }
    };
    var m = new h;

    function u() {
        this.popups = []
    }
    t.elfsightAdminPagesController = m, u.prototype = {
        constructor: u,
        add: function (t, i) {
            if (!t || !i || !e.isFunction(i)) return !1;
            this.popups[t] = i() || {}
        },
        get: function (e) {
            return this.popups[e] || !1
        },
        hide: function (e) {
            (p = r.filter("[data-elfsight-admin-popup-id=" + e + "]")).removeClass("elfsight-admin-popup-active")
        },
        show: function (t, i) {
            var a, n = this.get(t);
            return !!n && ("init" in n && e.isFunction(n.init) && (a = n.init(i)), e.when(a).then(function () {
                p.length && p.removeClass("elfsight-admin-popup-active"), (p = r.filter("[data-elfsight-admin-popup-id=" + t + "]")).addClass("elfsight-admin-popup-animation elfsight-admin-popup-active"), setTimeout(function () {
                    p.removeClass("elfsight-admin-popup-animation")
                }, 200)
            }), n)
        }
    };
    var v = new u;
    e(function () {
        i = e(".elfsight-admin"), a = e(".elfsight-admin-main"), n = e("[data-elfsight-admin-page-id]"), s = e("[data-elfsight-admin-page]"), o = e(".elfsight-admin-pages-container"), r = e("[data-elfsight-admin-popup-id]"), d = e(".elfsight-admin-other-products"), g = e(), p = e(), l = a.attr("data-elfsight-admin-slug"), c = a.attr("data-elfsight-admin-user") ? JSON.parse(a.attr("data-elfsight-admin-user")) : {}, m.add("welcome", e.noop), m.add("widgets", function () {
            var t = [],
                i = "true" === a.attr("data-elfsight-admin-widgets-clogged"),
                n = e(".elfsight-admin-page-widgets"),
                s = e(".elfsight-admin-page-widgets-list", n),
                o = e(".elfsight-admin-template-widgets-list-item", n),
                r = e(".elfsight-admin-template-widgets-list-empty", n);
            s.on("click", ".elfsight-admin-page-widgets-list-item-actions-remove", function (t) {
                var i = e(this),
                    a = i.attr("data-widget-id");
                i.closest(".elfsight-admin-page-widgets-list-item").addClass("elfsight-admin-page-widgets-list-item-removed"), u("widgets/remove", {
                    id: a
                }, "post", !0), t.preventDefault()
            }), s.on("click", ".elfsight-admin-page-widgets-list-item-actions-restore a", function (t) {
                var i = e(this),
                    a = i.attr("data-widget-id");
                i.closest(".elfsight-admin-page-widgets-list-item").removeClass("elfsight-admin-page-widgets-list-item-removed"), u("widgets/restore", {
                    id: a
                }, "post", !0), t.preventDefault()
            }), s.tablesorter({
                cssAsc: "elfsight-admin-page-widgets-list-sort-asc",
                cssDesc: "elfsight-admin-page-widgets-list-sort-desc",
                cssHeader: "elfsight-admin-page-widgets-list-sort",
                headers: {
                    0: {},
                    1: {},
                    2: {},
                    3: {
                        sorter: !1
                    }
                }
            });
            var d = function () {
                var i = e("tbody", s).empty();
                e.isArray(t) && t.length ? (e.each(t, function (t, a) {
                    var n = e(o.html()),
                        s = "[" + l.split("-").join("_") + ' id="' + a.id + '"]';
                    n.find(".elfsight-admin-page-widgets-list-item-name a").attr("href", "#/edit-widget/" + a.id + "/").text(a.name);
                    var r = new Date(1e3 * (a.time_updated || a.time_created));
                    n.find(".elfsight-admin-page-widgets-list-item-date").text(function (e) {
                        e instanceof Date || (e = new Date(Date.parse(e)));
                        return ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][e.getMonth()] + " " + e.getDate() + ", " + e.getFullYear()
                    }(r)), n.find(".elfsight-admin-page-widgets-list-item-shortcode-hidden").text(s);
                    var d = n.find(".elfsight-admin-page-widgets-list-item-shortcode-input").val(s),
                        g = n.find(".elfsight-admin-page-widgets-list-item-shortcode-copy-trigger").attr("data-clipboard-text", s),
                        p = new ClipboardJS(g.get(0));
                    p.on("success", function () {
                        g.addClass("elfsight-admin-page-widgets-list-item-shortcode-copy-trigger-copied").find("span").text("Copied"), setTimeout(function () {
                            g.removeClass("elfsight-admin-page-widgets-list-item-shortcode-copy-trigger-copied").find("span").text("Copy")
                        }, 5e3)
                    }), p.on("error", function () {
                        var e = n.find(".elfsight-admin-page-widgets-list-item-shortcode-copy-error").show();
                        d.select(), setTimeout(function () {
                            e.hide()
                        }, 5e3)
                    }), n.find(".elfsight-admin-page-widgets-list-item-shortcode-value").text(s), n.find(".elfsight-admin-page-widgets-list-item-actions-edit").attr("href", "#/edit-widget/" + a.id + "/"), n.find(".elfsight-admin-page-widgets-list-item-actions-duplicate").attr("href", "#/edit-widget/" + a.id + "/duplicate/"), n.find(".elfsight-admin-page-widgets-list-item-actions-remove, .elfsight-admin-page-widgets-list-item-actions-restore a").attr("data-widget-id", a.id), n.appendTo(i)
                }), s.trigger("update")) : e(r.html()).appendTo(i)
            };
            return {
                init: function (a, n) {
                    return u("widgets/list").then(function (a) {
                        if (a.status) {
                            if (t = a.data, !(i || e.isArray(t) && t.length)) return m.show("welcome"), e.Deferred().reject(a).promise();
                            d(), f = !0
                        }
                    })
                }
            }
        }), m.add("edit-widget", function () {
            var i, a, n, s = e(".elfsight-admin-page-edit-widget"),
                o = e(".elfsight-admin-page-edit-widget-form-submit", s),
                r = e(".elfsight-admin-page-edit-widget-form-apply", s),
                d = e(".elfsight-admin-page-edit-widget-unsaved", s),
                g = e(".elfsight-admin-page-edit-widget-form-cancel", s),
                p = e(".elfsight-admin-page-edit-widget-name-input", s),
                l = "elfsight-admin-page-edit-widget-form-editor",
                h = l + "-clone",
                w = e("." + h, s).parent(),
                C = e("." + h, s),
                S = !1,
                y = JSON.parse(C.attr("data-elfsight-admin-editor-settings")),
                b = JSON.parse(C.attr("data-elfsight-admin-editor-preferences")),
                x = JSON.parse(C.attr("data-elfsight-admin-editor-preview-url")),
                _ = C.attr("data-elfsight-admin-editor-observer-url") || null;
            _ && (_ = JSON.parse(_));
            var P = function (e) {
                    e,
                    d.toggleClass("elfsight-admin-page-edit-widget-unsaved-visible", e),
                    e ? t.addEventListener("beforeunload", T) : t.removeEventListener("beforeunload", T)
                },
                T = function (e) {
                    e.preventDefault(), e.returnValue = "Widget has unsaved changed"
                },
                D = function (t) {
                    var n = p.val(),
                        s = {};
                    a.getData() ? s = a.getData() : i && (s = i.options);
                    var o = i ? "widgets/update" : "widgets/add",
                        r = {
                            name: n || "Untitled",
                            options: encodeURIComponent(JSON.stringify(s))
                        };
                    i && (r.id = i.id), u(o, r, "post").done(function (a) {
                        a.status && (a.id && (i = {
                            id: a.id
                        }, f = !0, v.popups.rating.open(!0, 3e4)), e.isFunction(t) && t())
                    })
                };
            return o.on("click", function () {
                e("html, body").animate({
                    scrollTop: 0
                }), D(function () {
                    P(!1), hasher.setHash("widgets/")
                })
            }), r.on("click", function () {
                D(), P(!1)
            }), g.on("click", function () {
                hasher.setHash("widgets/"), P(!1)
            }), {
                init: function (t, o) {
                    var r = function () {
                        p.val(i ? i.name : ""), n && n.remove(), (n = C.clone().removeClass(h).addClass(l)).appendTo(w), angular.module("elfsightEditor", ["elfsightAppsEditor"]).controller("AppController", ["$elfsightAppsEditor", "$scope", "$rootScope", "$timeout", function (t, s, o, r) {
                            a = t, o.user = c, S = !1;
                            var d = {
                                parent: n,
                                previewUrl: x,
                                observerUrl: _ || void 0,
                                settings: e.extend(!0, {}, y),
                                preferences: b,
                                enableCustomCSS: !1,
                                enableCloudProperties: !1,
                                onChange: function (e) {
                                    S ? P(!0) : S = !0
                                }
                            };
                            i && (d.widget = {
                                data: i.options
                            }), t.init(d)
                        }]), n.attr("ng-controller", "AppController as app"), angular.bootstrap(n, ["elfsightEditor"])
                    };
                    if (t && t.id) return u("widgets/list", {
                        id: t.id
                    }).then(function (a) {
                        if (a.status) {
                            if (!a.data.length) return m.show("error", {
                                message: "There is no widget with id " + t.id + "."
                            }), e.Deferred().reject(a).promise();
                            i = a.data[0], f = !0, r(), P(!1), s.toggleClass("elfsight-admin-page-edit-widget-new", !!t.duplicate), t.duplicate && (i = null)
                        }
                    }, function () {
                        i = null
                    });
                    i = null, r(), s.addClass("elfsight-admin-page-edit-widget-new")
                }
            }
        }), m.add("support", e.noop), m.add("preferences", function () {
            var t = e(".elfsight-admin-page-preferences-form"),
                i = function (t, i) {
                    var a = i.find(".elfsight-admin-page-preferences-option-save");
                    a.addClass("elfsight-admin-page-preferences-option-save-loading"), e.ajax({
                        type: "POST",
                        url: pluginParams.restApiUrl + "update-preferences",
                        data: t,
                        dataType: "json",
                        beforeSend: function (e) {
                            e.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce)
                        }
                    }).done(function (e) {
                        var t = i.find(".elfsight-admin-page-preferences-option-save-success"),
                            n = i.find(".elfsight-admin-page-preferences-option-save-error");
                        a.removeClass("elfsight-admin-page-preferences-option-save-loading"), e.success ? (n.text(""), t.addClass("elfsight-admin-page-preferences-option-save-success-visible"), setTimeout(function () {
                            t.removeClass("elfsight-admin-page-preferences-option-save-success-visible")
                        }, 2e3)) : e.error && n.text(e.error)
                    })
                },
                a = function (e, t) {
                    var i = e.getSession().getScreenLength() * e.renderer.lineHeight + e.renderer.scrollBar.getWidth();
                    t.height(i.toString() + "px"), e.resize()
                },
                n = ace.edit("elfsightPreferencesSnippetCSS");
            n.setOption("useWorker", !1), n.setTheme("ace/theme/monokai"), n.getSession().setMode("ace/mode/css"), n.commands.addCommand({
                name: "save",
                bindKey: {
                    win: "Ctrl-S",
                    mac: "Command-S"
                },
                exec: function () {
                    var t = e(".elfsight-admin-page-preferences-option-css");
                    i({
                        preferences_custom_css: n.getSession().doc.getValue()
                    }, t)
                }
            }), a(n, e("#elfsightPreferencesSnippetCSS")), n.getSession().on("change", function () {
                a(n, e("#elfsightPreferencesSnippetCSS"))
            });
            var s = ace.edit("elfsightPreferencesSnippetJS");
            s.setOption("useWorker", !1), s.setTheme("ace/theme/monokai"), s.getSession().setMode("ace/mode/javascript"), s.commands.addCommand({
                name: "save",
                bindKey: {
                    win: "Ctrl-S",
                    mac: "Command-S"
                },
                exec: function () {
                    var t = e(".elfsight-admin-page-preferences-option-js");
                    i({
                        preferences_custom_js: s.getSession().doc.getValue()
                    }, t)
                }
            }), a(s, e("#elfsightPreferencesSnippetJS")), s.getSession().on("change", function () {
                a(s, e("#elfsightPreferencesSnippetJS"))
            }), t.find(".elfsight-admin-page-preferences-option-save").click(function (t) {
                t.preventDefault();
                var a = e(this),
                    o = a.closest(".elfsight-admin-page-preferences-option"),
                    r = a.closest(".elfsight-admin-page-preferences-option-input-container").find('input[type="text"]'),
                    d = {};
                r.each(function (t, i) {
                    d[e(i).attr("name")] = e(i).val()
                }), o.is(".elfsight-admin-page-preferences-option-css") && (d.preferences_custom_css = n.getSession().doc.getValue()), o.is(".elfsight-admin-page-preferences-option-js") && (d.preferences_custom_js = s.getSession().doc.getValue()), i(d, o)
            }), t.find('[name="preferences_force_script_add"]').change(function () {
                var t = e(this),
                    a = t.closest(".elfsight-admin-page-preferences-option");
                i({
                    option: {
                        name: "force_script_add",
                        value: t.is(":checked") ? "on" : "off"
                    }
                }, a)
            }), t.find('[name="preferences_access_role"]').change(function () {
                var t = e(this),
                    a = t.closest(".elfsight-admin-page-preferences-option");
                i({
                    option: {
                        name: "access_role",
                        value: t.val()
                    }
                }, a)
            }), t.find('[name="preferences_auto_upgrade"]').change(function () {
                var t = e(this),
                    a = t.closest(".elfsight-admin-page-preferences-option");
                i({
                    option: {
                        name: "auto_upgrade",
                        value: t.is(":checked") ? "on" : "off"
                    }
                }, a)
            })
        }), m.add("activation", function () {
            var t = e(".elfsight-admin-page-activation-form"),
                a = e(".elfsight-admin-page-activation-form-purchase-code-input", t),
                n = e(".elfsight-admin-page-activation-form-activated-input", t),
                s = e(".elfsight-admin-page-activation-form-supported-until-input", t),
                o = e(".elfsight-admin-page-activation-form-host-input", t),
                r = e(".elfsight-admin-page-activation-form-activation-button", t),
                d = e(".elfsight-admin-page-activation-form-activation", t),
                g = e(".elfsight-admin-page-activation-form-activation-confirm-no", t),
                p = e(".elfsight-admin-page-activation-form-activation-confirm-yes", t),
                c = (e(".elfsight-admin-page-activation-form-deactivation", t), e(".elfsight-admin-page-activation-form-deactivation-button", t)),
                f = e(".elfsight-admin-page-activation-form-deactivation-confirm-no", t),
                h = e(".elfsight-admin-page-activation-form-deactivation-confirm-yes", t),
                m = e(".elfsight-admin-page-activation-form-message-success", t),
                u = e(".elfsight-admin-page-activation-form-message-error", t),
                v = e(".elfsight-admin-page-activation-form-message-fail", t),
                w = e(".elfsight-admin-page-activation-faq"),
                C = e(".elfsight-admin-page-activation-faq-list-item", w),
                S = e(".elfsight-admin-page-support-ticket-iframe"),
                y = S.attr("src"),
                b = null,
                x = t.attr("data-activation-url"),
                _ = t.attr("data-activation-version"),
                P = function (t, i, n) {
                    e.ajax({
                        type: "POST",
                        url: pluginParams.restApiUrl + "update-activation-data",
                        data: {
                            purchase_code: t,
                            supported_until: n || 0,
                            activated: i
                        },
                        beforeSend: function (e) {
                            e.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce)
                        }
                    }), a.prop("readonly", i)
                };
            r.click(function (e) {
                e.preventDefault(), e.stopPropagation(), T({
                    purchaseCode: a.val(),
                    host: o.val()
                })
            });
            var T = function (a) {
                var o = arguments.length > 1 && void 0 !== arguments[1] && arguments[1];
                return e.ajax({
                    url: x,
                    dataType: "jsonp",
                    data: {
                        action: "purchase_code",
                        slug: l + "-cc",
                        host: a.host,
                        purchase_code: a.purchaseCode,
                        version: _,
                        force_update: o
                    }
                }).done(function (e) {
                    if (e.verification) {
                        b = e.verification;
                        var o = !!e.verification.valid;
                        n.val(o), s.val(e.verification.supported_until || 0), b.valid ? (i.removeClass("elfsight-admin-activation-invalid").addClass("elfsight-admin-activation-activated"), u.text("").hide(), v.hide(), m.show(), y = y.replace(/purchase_code=(.*)#/, "purchase_code=" + a.purchaseCode + "#"), S.attr("src", y), P(a.purchaseCode, o, b.supported_until)) : (i.removeClass("elfsight-admin-activation-activated").toggleClass("elfsight-admin-activation-invalid", !!a.purchaseCode), m.hide(), v.hide(), u.text(b.error).show()), b.exception && "PC_REGISTERED_TO_ANOTHER" === b.exception && (u.text("").hide(), d.find(".elfsight-admin-page-activation-form-activation-confirm-caption-message").html(b.error), t.addClass("elfsight-admin-page-activation-form-activation-confirm-visible"))
                    }
                }).fail(function () {
                    i.removeClass("elfsight-admin-activation-activated").addClass("elfsight-admin-activation-invalid"), n.val(!1), m.hide(), u.hide(), v.show(), P(a.purchaseCode, !1)
                })
            };
            c.click(function (e) {
                e.preventDefault(), e.stopPropagation(), t.addClass("elfsight-admin-page-activation-form-deactivation-confirm-visible")
            }), f.click(function (e) {
                e.preventDefault(), e.stopPropagation(), t.removeClass("elfsight-admin-page-activation-form-deactivation-confirm-visible")
            }), h.click(function (r) {
                r.preventDefault(), r.stopPropagation(), t.removeClass("elfsight-admin-page-activation-form-deactivation-confirm-visible");
                var d = a.val(),
                    g = o.val();
                e.ajax({
                    url: x,
                    dataType: "jsonp",
                    data: {
                        action: "deactivate",
                        slug: l + "-cc",
                        host: g,
                        purchase_code: d,
                        version: _
                    }
                }).done(function (e) {
                    a.val(""), n.val("false"), s.val(0), i.removeClass("elfsight-admin-activation-activated"), m.hide(), v.hide(), u.hide(), P("", !1)
                })
            }), g.click(function (e) {
                e.preventDefault(), e.stopPropagation(), t.removeClass("elfsight-admin-page-activation-form-activation-confirm-visible")
            }), p.click(function (e) {
                e.preventDefault(), e.stopPropagation(), T({
                    purchaseCode: a.val(),
                    host: o.val()
                }, !0)
            }), w.find(".elfsight-admin-page-activation-faq-list-item-question").click(function () {
                var t = e(this).closest(".elfsight-admin-page-activation-faq-list-item");
                C.not(t).removeClass("elfsight-admin-page-activation-faq-list-item-active"), t.toggleClass("elfsight-admin-page-activation-faq-list-item-active")
            })
        }), m.add("error", function () {
            var t = e(".elfsight-admin-page-error");
            return {
                init: function (i) {
                    i && i.message && e(".elfsight-admin-page-error-message", t).text(i.message)
                }
            }
        }), v.add("rating", function () {
            var t = e(".elfsight-admin-header-rating"),
                i = t.find("input[name=rating-header]"),
                a = e(".elfsight-admin-popup-rating"),
                n = a.find("form"),
                s = a.find("input[name=rating-popup]"),
                o = a.find(".elfsight-admin-popup-textarea"),
                r = a.find(".elfsight-admin-popup-text"),
                d = a.find(".elfsight-admin-popup-footer-button-ok"),
                g = a.find(".elfsight-admin-popup-footer-button-close"),
                p = localStorage.getItem("popupRatingShowed") ? localStorage.getItem("popupRatingShowed") : Math.floor(Date.now() / 1e3),
                l = parseInt(p) + 86400 < Math.floor(Date.now() / 1e3),
                c = function (e) {
                    var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 1e3;
                    setTimeout(function () {
                        if (a.length && !a.hasClass("elfsight-admin-popup-sent")) {
                            var t = !~hasher.getHash().indexOf("edit-widget") && !~hasher.getHash().indexOf("add-widget");
                            (!e || e && l && t && f) && v.show("rating"), localStorage.setItem("popupRatingShowed", Math.floor(Date.now() / 1e3))
                        }
                    }, t)
                };
            setTimeout(function () {
                f && t && (c(!0, 3e4), t.slideDown())
            }, 5e3), i.on("change", function () {
                var t = parseInt(e(this).val());
                c(!1, 0), setTimeout(function () {
                    s.filter('[value="' + t + '"]').prop("checked", !0), m(t)
                }, 400), e(this).prop("checked", !1)
            });
            var m = function (e) {
                d.removeClass("elfsight-admin-popup-footer-button-hide"), o.toggleClass("elfsight-admin-popup-textarea-hide", 5 === e), r.toggleClass("elfsight-admin-popup-text-hide", e < 5)
            };
            return s.on("change", function () {
                m(parseInt(e(this).val()))
            }), d.on("click", function (i) {
                i.preventDefault();
                var s = parseInt(n.find('input[name="rating-popup"]:checked').val()),
                    r = n.find("textarea").val();
                5 === s && h(e(i.target).attr("href")), s < 5 && "" === r ? o.toggleClass("elfsight-admin-popup-textarea-error", !0) : (o.toggleClass("elfsight-admin-popup-textarea-error", !1), e.ajax({
                    type: "POST",
                    url: pluginParams.restApiUrl + "rating-send",
                    data: {
                        value: s,
                        comment: r
                    },
                    beforeSend: function (e) {
                        e.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce)
                    }
                }).then(function () {
                    a.addClass("elfsight-admin-popup-sent"), g.text("OK"), d.addClass("elfsight-admin-popup-footer-button-hide"), t.slideUp(), localStorage.removeItem("popupRatingShowed")
                }))
            }), g.on("click", function () {
                v.hide("rating"), d.addClass("elfsight-admin-popup-footer-button-hide"), o.addClass("elfsight-admin-popup-textarea-hide"), r.addClass("elfsight-admin-popup-text-hide"), s.prop("checked", !1)
            }), {
                init: function (e, t) {
                    return !0
                },
                open: c
            }
        });
        var h = function (e, i) {
                var a = 940,
                    n = 700,
                    s = ["width=" + a, "height=" + n, "menubar=no", "toolbar=no", "resizable=yes", "scrollbars=yes", "left=" + (t.screen.availLeft + t.screen.availWidth / 2 - a / 2), "top=" + (t.screen.availTop + t.screen.availHeight / 2 - n / 2)];
                t.open(e, i, s.join(","))
            },
            u = function (t, i, n, s) {
                return i = "post" === (n = "post" === n ? "post" : "get") ? JSON.stringify(i) : i, e.ajax({
                    url: pluginParams.restApiUrl + t,
                    dataType: "json",
                    data: i,
                    contentType: "application/json",
                    type: n,
                    beforeSend: function (e) {
                        e.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce), e.setRequestHeader("X-HTTP-Method-Override", n), s || a.addClass("elfsight-admin-loading")
                    }
                }).always(function () {
                    s || a.removeClass("elfsight-admin-loading")
                }).then(function (t) {
                    return t.status ? t : (m.show("error", {
                        message: "An error occurred during your request process. Please, try again."
                    }), e.Deferred().reject(t).promise())
                }, function (e) {
                    return m.show("error", {
                        message: "An error occurred during your request process. Please, try again."
                    }), e
                })
            };
        if (t.crossroads && t.hasher) {
            crossroads.addRoute("/add-widget/", function () {
                m.show("edit-widget")
            }), crossroads.addRoute("/edit-widget/{id}/", function (e) {
                m.show("edit-widget", {
                    id: e
                })
            }), crossroads.addRoute("/edit-widget/{id}/duplicate/", function (e) {
                m.show("edit-widget", {
                    id: e,
                    duplicate: !0
                })
            }), crossroads.addRoute("/{page}/", function (e) {
                e && -1 === e.indexOf("!") && (m.show(e) || m.show("error", {
                    message: "The requested page was not found."
                }))
            });
            var w = function (e, t) {
                crossroads.parse(e)
            };
            hasher.initialized.add(w), hasher.changed.add(w), hasher.init(), hasher.getHash() || hasher.setHash("widgets/")
        }
    })
}(jQuery, window);
