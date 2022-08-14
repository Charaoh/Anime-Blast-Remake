(function() {
    try {
        var $_console$$ = console;
        Object.defineProperty(window, "console", {
            get: function() {
                if ($_console$$._commandLineAPI)
                    throw "Sorry, for security reasons, the script console is deactivated.";
                return $_console$$
            },
            set: function($val$$) {
                $_console$$ = $val$$
            }
        })
    } catch ($ignore$$) {
    }
})();
var Listener, verify, scripts = document.getElementsByTagName("script"),
	src = scripts[scripts.length - 1].src,
	_path = "./1.0/";

function sleep(t) {
	for (var e = (new Date).getTime();
		(new Date).getTime() < e + t;);
}! function(d) {
	d.fn.listener = function(t) {
		var e = turn,
			i = t;

		function a() {
			d.ajax({
				url: _path + "/core/ajax.php",
				type: "POST",
				data: {
					f: "checkStatus"
				},
				dataType: "json",
				success: function(t) {
					! function(t) {
						if ("loser" == t.status || "winner" == t.status) return me = !1, d(window).stopTimer(), clearTimeout(Listener), Listener = null, "loser" == t.status ? d.playSound("lose") : d.playSound("win"), d.ajax({
							url: _path + "/core/ajax.php",
							type: "POST",
							data: {
								f: "return_",
								w: "fin"
							},
							dataType: "json",
							success: function(t) {
								d("#wrapper").prepend(t.result), d(".turnText").html(t.title)
							}
						});
						(t.turn != e || i != t.status) ? "loser" !== t.status && "winner" !== t.status && (window.location = "./battle") : t.status !== i ? "loser" !== t.status && "winner" !== t.status && (window.location = "./battle") : "loser" !== i && "winner" !== i && (Listener = setTimeout(a, 5e3))
					}(t)
				}
			})
		}
		a()
	}, d.fn.startTimer = function(a, n) {
		var s = {
			starting: new Date,
			startTime: 6e4,
			maxTime: 6e4,
			left: 0
		};
		a = a || s[a], n = n || s[n], a == n && 1 != turn && d.playSound("end-turn");
		var r = Math.floor(n / 100);

		function l(t) {
			1 == me && d(".turnText").html(Math.round(s.left / 1e3) + " SECONDS"), d(".timerBar.Left").slideDown("slow").css("width", t + "%")
		}! function t() {
			if ("loser" == status || "winner" == status) return clearTimeout(Timer), Timer = null, !1;
			var e = new Date;
			s.left = s.starting.getTime() + a - e.getTime();
			var i = Math.round(s.left / n * 100);
			0 <= i ? (l(i), Timer = setTimeout(t, r)) : (d(".turnText").html("Ending turn..."), 0 == me ? location.reload() : d("form[name=turn-" + turn + "]").submit());
			return !1
		}()
	}, d.fn.stopTimer = function() {
		clearTimeout(Timer), Timer = null
	}, d.fn.startBg = function() {
		var t = {
				min: 900,
				max: 940
			},
			e = 900;

		function i() {
			t.max - e == 0 && (d("#wrapper").css("background-position", t.min + "px 0px"), e = t.min), e++, d("#wrapper").css("background-position", e + "px 0px")
		}
		setInterval(i, 100)
	}, d.fn.verifySkill = function(e, i, a) {
		d.ajax({
			url: _path + "/core/ajax.php",
			type: "POST",
			data: {
				f: "verifySkill",
				c: e,
				s: i
			},
			dataType: "json",
			success: function(t) {
				if (1 == t.manacap && (d(".error." + e).fadeIn().delay(3e3).fadeOut(function() {
						var t = this;
						setTimeout(function() {
							d(t).siblings(".manaBar").css("box-shadow", "")
						}, 500)
					}), d(".error." + e).siblings(".manaBar").css("box-shadow", "inset 1px 1px 5px red")), 0 == t.result) return !1;
				a == d(window).setOpacity ? a(e, i, t.result) : a(t.result)
			}
		})
	}, d.fn.Getit = function(t, e) {
		d.ajax({
			url: _path + "/core/ajax.php",
			type: "POST",
			data: {
				f: t,
				w: e
			},
			dataType: "json",
			success: function(t) {
				! function(t) {
					t.returned
				}(t.result)
			}
		})
	}, d.fn.Targets = function(t, e, i, a) {
		var s = t,
			n = e,
			u = i,
			r = a;
		return {
			getTargets: function() {
				d.ajax({
					url: _path + "/core/ajax.php",
					type: "POST",
					data: {
						f: "getTargets",
						c: n,
						s: u
					},
					dataType: "json",
					success: function(t) {
						verify.setTargets(t.result)
					}
				})
			},
			setTargets: function(t) {
				var e, i = {
					A: "00",
					B: "01",
					C: "02"
				};
				if (s.closest(".slot").find(".character").addClass("caster"), d(".caster").closest(".slot").find(".animate").after('<p class="casted"></p>'), e = d(".caster").closest(".slot").attr("class").split(" ")[1], "X" != d('input[id="' + i[e] + '-skill"]').val()) return !1;
				d(".cast").addClass("caster"), d('input[id="current"]').val(u);
				i = {
					"00": "A",
					"01": "B",
					"02": "C",
					10: "A",
					11: "B",
					12: "C"
				};
				for (var a in t) {
					var n = "slot";
					10 <= a && (n = "eslot"), d("." + n + "." + i[a] + " > .animate").css("display", "block"), d("." + n + "." + i[a] + " > .character").addClass("targeting")
				}
			},
			checkTarget: function(t) {
				d.ajax({
					url: _path + "/core/ajax.php",
					type: "POST",
					data: {
						f: "checkTarget",
						c: n,
						s: u,
						d: r
					},
					dataType: "json",
					success: function(t) {
						verify.setTargeted(t)
					}
				})
			},
			setTargeted: function(a) {
				var t, e = a.result,
					i = {
						A: "00",
						B: "01",
						C: "02"
					};
				t = d(".caster").closest(".slot").attr("class").split(" ")[1], d('input[id="' + i[t] + '-skill"]').val(u);
				var n = d(".caster").closest(".slot").find(".skills").find("#" + u).attr("src"),
					s = (s = d(".caster").closest(".slot").find(".selected")).position(),
					r = (r = d(".caster").closest(".slot").find(".skills").find("#" + u)).position();
				d(".caster").closest(".slot").find(".skills").find("#" + u).animate({
						top: "0px",
						left: s.left - r.left + 5 + "px"
					}, 250), d(".caster").closest(".slot").find(".skills").find("#" + u).next().css("left", s.left - r.left + 5 + "px"), d(".caster").closest(".slot").find(".skills").find(".skill").each(function() {
						d(this).attr("id") !== u && d(this).addClass("opacity")
					}),
					function t(e, i) {
						setTimeout(function() {
							50 <= i - a.mana && (i = a.mana), e.closest(".slot").find(".manaBar.Left>p").html(i), i != a.mana && t(e, --i)
						}, 12)
					}(d(".caster"), a.character), d(".caster").closest(".slot").find(".manaBar.Left").animate({
						width: a.width + "%",
						display: "block"
					}, 1200), d(".casted").remove(), d(".character").removeClass("caster"), d(".character").removeClass("targeting"), d(".animate").css("display", "none"), d('input[id="current"]').val("X");
				i = {
					"00": "A",
					"01": "B",
					"02": "C",
					10: "A",
					11: "B",
					12: "C"
				};
				for (var l in e) {
					var o = "slot";
					10 <= l && (o = "eslot");
					var c = "";
					d('input[id="' + l + '-targeted"').val().length && "X" !== d('input[id="' + l + '-targeted"').val() && (c = d('input[id="' + l + '-targeted"').val() + ","), d('input[id="' + l + '-targeted"').val(c + u), d("." + o + "." + i[l] + " > .le > .new").append('<img id="' + u + '" class="last" src="' + n + '">')
				}
				d.playSound("choosen")
			}
		}
	}, d.fn.setOpacity = function(t, e, i) {
		0 == i ? d("#" + t).closest(".slot").find(".skills").find("#" + e).hasClass("opacity") || d("#" + t).closest(".slot").find(".skills").find("#" + e).addClass("opacity") : d("#" + e).removeClass("opacity")
	}, d.extend({
		playSound: function() {
			if (d("#" + arguments[0]).length) {
				document.getElementById(arguments[0]).currentTime = 0;
				var t = document.getElementById(arguments[0]).play();
				t && t.catch(function(t) {
					console.error(t)
				})
			}
		}
	}), d.fn.doEnd = function(t) {
		if (t.length < 1) d(window).stopTimer(), clearTimeout(Listener), Listener = null, d('input[name="end"]').val("false"), d("form[name=turn-" + turn + "]").submit();
		else if (-1 == t.indexOf(",")) d(window).stopTimer(), clearTimeout(Listener), Listener = null, d.playSound("end-turn"), sleep(1e3), d('input[name="end"]').val("true"), d("form[name=turn-" + turn + "]").submit();
		else {
			var e = "";
			for (i = 0; i < 3; i++)
				if ("X" != d('input[id="0' + i + '-skill"]').val() && void 0 !== d('input[id="0' + i + '-skill"]').val()) {
					var a = {
						0: "A",
						1: "B",
						2: "C"
					};
					e += '<li><img id="' + d(".slot." + a[i]).find(".skills").find("#" + d('input[id="0' + i + '-skill"]').val()).attr("id") + '" style="margin-right: 10px;" class="fl-l skill" src="' + d(".slot." + a[i]).find(".skills").find("#" + d('input[id="0' + i + '-skill"]').val()).attr("src") + '"/></li>'
				} d("#wrapper").prepend('<div id="popup"><div></div><div class="inner-content"><div class="first"><h1>Skill Queue</h1>Drag your skills in the order you wish to execute them!</div><div class="second"><ul>' + e + '</ul></div><br><button class="button end">End Turn</button><button class="button cancel">Cancel</button></div></div>');
			var n = {
				start: function() {
					d(this).css({
						opacity: "0"
					})
				},
				stop: function() {
					d(this).css({
						opacity: "1"
					})
				},
				helper: "clone",
				containment: "#popup"
			};
			d("#wrapper").find(".second>ul>li>img").draggable(n), d("#wrapper").find(".second>ul>li").droppable({
				drop: function(t, e) {
					var i = d(this),
						a = e.draggable;
					d(a).parent().html(i.html()), d(i).html(a), d("#wrapper").find(".second>ul>li>img").draggable(n)
				}
			})
		}
	}
}(jQuery), jQuery.ajaxSetup({
	cache: !1
}), jQuery(document).ajaxError(function(t, e, i) {
	$(document.body).html(e.responseText)
}), $(window).bind("load", function() {
	var t = document.getElementById("wrapper").offsetWidth,
		e = document.getElementById("wrapper").offsetHeight;
	window.resizeTo(t, e + 64), 1 == turn && $.playSound("start"), "loser" !== match_status && "winner" !== match_status && ($(window).startTimer(startTime, maxTime, turn), $(window).listener(match_status)), "loser" != match_status && "winner" != match_status || (me = !1, clearTimeout(Listener), Listener = null, "loser" == match_status ? $.playSound("lose") : $.playSound("win"), $.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "return_",
			w: "fin"
		},
		dataType: "json",
		success: function(t) {
			$("#wrapper").prepend(t.result), $(".turnText").html(t.title)
		}
	}));
	var a = "b" == $(".render").attr("class").split(" ")[1] ? 330 : 315;
	$(".render").animate({
		left: a + "px"
	}, 1e3);
	var n = 0,
		s = null;
	1 == me && ($(".turnText").on("click", function(t) {
		$(this);
		if (1 === ++n) {
			var e = "";
			for (i = 0; i < 3; i++) "X" != $('input[id="0' + i + '-skill"]').val() && void 0 !== $('input[id="0' + i + '-skill"]').val() && (e.length && (e += ","), e += $('input[id="0' + i + '-skill"]').val());
			$.fn.doEnd(e)
		} else clearTimeout(s), n = 0
	}).on("dblclick", function(t) {
		t.preventDefault(), n = 0
	}), $(".end").live("click", function(t) {
		var e = $(this);
		if (1 === ++n) {
			var i = "";
			e.parent().find(".second").find("ul").find("li").each(function() {
				i.length && (i += ","), i += $(this).children().attr("id")
			}), $('input[name="order"]').val(i), $.fn.stopTimer(), clearTimeout(Listener), Listener = null, $('input[name="end"]').val("true"), $("form[name=turn-" + turn + "]").submit()
		} else clearTimeout(s), n = 0
	}).on("dblclick", function(t) {
		t.preventDefault(), n = 0
	}), $(".skill").live("click", function(t) {
		if ($(this).hasClass("opacity")) return !1;
		var e;
		if (e = $(this).closest(".slot").attr("class").split(" ")[1], "X" !== $('input[id="' + {
				A: "00",
				B: "01",
				C: "02"
			} [e] + '-skill"]').val()) return !1;
		"X" !== $('input[id="current"]').val() && $('input[id="current"]').val("X"), $(".casted").remove(), $(".animate").css("display", "none"), $(".character").removeClass("caster"), $(".character").removeClass("targeting"), verify = $(window).Targets($(this), $(this).closest(".slot").find(".character").attr("id"), $(this).attr("id"), !0), $(window).verifySkill($(this).closest(".slot").find(".character").attr("id"), $(this).attr("id"), verify.getTargets)
	}), $(".animate, .character, .casted").live("click", function(t) {
		if ("X" == $('input[id="current"]').val() || void 0 === $('input[id="current').val()) return !1;
		if (!$(".animate").is(":visible")) return !1;
		var e = $(this).closest(".slot").find(".character").hasClass("targeting") ? "slot" : "eslot";
		if (!$(this).closest("." + e).find(".character").hasClass("targeting")) {
			if ($(".caster").length) {
				var i;
				i = $(".caster").closest(".slot").attr("class").split(" ")[1], $('input[id="' + {
					A: "00",
					B: "01",
					C: "02"
				} [i] + '-skill"]').val("X"), $(".character").removeClass("caster")
			}
			return $(".casted").remove(), $(".animate").css("display", "none"), $(".character").removeClass("targeting"), $('input[id="current"]').val("X"), !1
		}
		verify = $(window).Targets($(".caster"), $(".caster").attr("id"), $('input[id="current"]').val(), $(this).parent().attr("class")), $(window).verifySkill($(".caster").attr("id"), $('input[id="current"]').val(), verify.checkTarget)
	}), $(".le>.new>img").live("click", function(t) {
		var e = {
			"00": $('input[id="00-targeted"').val(),
			"01": $('input[id="01-targeted"').val(),
			"02": $('input[id="02-targeted"').val(),
			10: $('input[id="10-targeted"').val(),
			11: $('input[id="11-targeted"').val(),
			12: $('input[id="12-targeted"').val()
		};
		for (var a in e)
			if (null != e[a] && "X" != e[a]) {
				var n, s = [];
				for (var r in n = e[a].split(",")) n[r] != $(this).attr("id") && s.push(n[r]);
				"" != s ? $('input[id="' + a + '-targeted"').val(s.join()) : $('input[id="' + a + '-targeted"').val("X")
			} for (i = 0; i < 3; i++)
			if ("X" != $('input[id="0' + i + '-skill"]').val() && void 0 !== $('input[id="0' + i + '-skill"]').val() && $('input[id="0' + i + '-skill"]').val() == $(this).attr("id")) {
				$('input[id="0' + i + '-skill"]').val("X");
				var l = {
						0: "A",
						1: "B",
						2: "C"
					},
					o = $(".slot." + l[i]).find(".character").attr("id"),
					c = $(this).attr("id");
				$(".slot." + l[i]).find(".skills").find(".skill").each(function() {
					void 0 !== $(this).attr("id") && $(this).attr("id") == c && ($(this).css("left", "0px"), $(this).next().css("left", "0px")), $(window).verifySkill(o, $(this).attr("id"), $(window).setOpacity)
				});
				var u = $(".slot." + l[i]).find(".manaBar").attr("id");
				$(".slot." + l[i]).find(".manaBar.Left>p").html(u), $(".slot." + l[i]).find(".manaBar.Left").stop().attr("style", "width: " + u + "%;")
			} $('.le>.new>img[id="' + $(this).attr("id") + '"').remove()
	})), $(".goback").live("click", function(t) {
		if (t.preventDefault(), !(void 0).length) return !1;
		$.ajax({
			url: _path + "/core/ajax.php",
			type: "POST",
			data: {
				f: "return_",
				w: "characters",
				v: void 0
			},
			dataType: "json",
			success: function(t) {
				!1 !== t.result && $(".info").html(t.result)
			}
		})
	}), $(".surrender").live("click", function(t) {
		$(this);
		1 === ++n ? ($(window).stopTimer(), clearTimeout(Listener), Listener = null, match_status = "loser", $.playSound("lose"), $.ajax({
			url: _path + "/core/ajax.php",
			type: "POST",
			data: {
				f: "return_",
				w: "surrender"
			},
			dataType: "json",
			success: function(t) {
				!1 !== t.result && ($("#popup").remove(), $(".turnText").html("You have lost!"), $.ajax({
					url: _path + "/core/ajax.php",
					type: "POST",
					data: {
						f: "return_",
						w: "fin"
					},
					dataType: "json",
					success: function(t) {
						!1 !== t.result && $("#wrapper").prepend(t.result)
					}
				}))
			}
		})) : (clearTimeout(s), n = 0)
	}).on("dblclick", function(t) {
		t.preventDefault(), n = 0
	}), $(".cancel").live("click", function(t) {
		$(this);
		1 === ++n ? $("#popup").remove() : (clearTimeout(s), n = 0)
	}).on("dblclick", function(t) {
		t.preventDefault(), n = 0
	}), $(".item.C").live("click", function(t) {
		$(this);
		1 === ++n ? $("#wrapper").prepend('<div id="popup"><div></div><div class="inner-content"><div class="first"><h1>Throwing the flag</h1>You are about to surrender this match... Are you sure?</div><br><button class="button surrender">Surrender</button><button class="button cancel">Cancel</button></div></div>') : (clearTimeout(s), n = 0)
	}).on("dblclick", function(t) {
		t.preventDefault(), n = 0
	}), $(".character, .skill").live("click", function(t) {
		$.playSound("click");
	}), $('a.tooltip').live("click", function(event){
			event.preventDefault();
  });
});