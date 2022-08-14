var scripts = document.getElementsByTagName("script"),
	src = scripts[scripts.length - 1].src,
	_path = "./1.0/", canSendAjax=true,
	w = 0;

function playSound(e) {
	if ($("#" + e).length) {
		document.getElementById(e).currentTime = 0;
		var t = document.getElementById(e).play();
		t && t.catch(function(e) {
			console.error(e)
		}), document.getElementById(e).play()
	}
}

function togglePlay(e) {
	var t = "./tpl/default/css/images/mute.png" === $(".toggleSound").attr("src") ? "./tpl/default/css/images/sound.png" : "./tpl/default/css/images/mute.png";
	return $(".toggleSound").attr("src", t), "./tpl/default/css/images/sound.png" === $(".toggleSound").attr("src") ? $(".toggleSound").attr("style", "width:20px;") : $(".toggleSound").attr("style", "width:10px;"), e[0].paused ? e[0].play() : e[0].pause()
}

function preLoad(e) {
	$("#main_container").prepend('<div id="preloader"><p>' + e + "</p></div>"), $("#preloader").spin("large", "white").center(!0)
}

function appendTeam() {
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "getTeam"
		},
		dataType: "json",
		success: function(e) {
			if (e.error) return errorMessage("Error: " + e.error, e.error, e.error), !1;
			var a = 0;
			$.each(e.team, function(e, t) {
				if (!t) return !0;
				if (!$("#" + t)) return !0;
				var r = $("#" + t);
				$('input[name="s' + e + '"]').val(r.attr("id")), $(".slot." + (e + 1) + ".ui-droppable").append(r), r.data("prevParent", $(".slot." + (e + 1) + ".ui-droppable")), a++
			}), appendStatus(a), setTimeout(function() {
				$("#ingame").show("fast", $("#preloader").remove())
			}, 2e3)
		}
	})
}

function cancelMatch() {
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "cancelMatch"
		},
		dataType: "text",
		success: function() {
			canSendAjax=true;
			clearInterval(match), match = null, $("#popup").fadeOut("slow", function() {
				$("#popup").remove()
			})
		}
	})
}

function updateTeam() {
	var r = "",
		e = [];
	"" != $("input[name=s0]").val() && e.push($("input[name=s0]").val()), "" != $("input[name=s1]").val() && e.push($("input[name=s1]").val()), "" != $("input[name=s2]").val() && e.push($("input[name=s2]").val()), $.each(e, function(e, t) {
		r = 0 == e ? t : r + "," + t
	}), $.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "setTeam",
			i: r
		},
		dataType: "json",
		success: function(e) {
			e.error && errorMessage("Error: " + e.error, e.error, e.error)
		}
	})
}

function appendStatus(e) {
	switch ((!e || e < 0) && (e = 0), e) {
		case 0:
			$(".char_list_text").html("Please drag <b>3</b> more characters!"), 
			$(".private").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click"), 
			$(".ladder").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click"), 
			$(".quick").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click");
			break;
		case 1:
			$(".char_list_text").html("Please drag <b>2</b> more characters!"), 
			$(".private").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click"), 
			$(".ladder").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click"), 
			$(".quick").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click");
			break;
		case 2:
			$(".char_list_text").html("Please drag <b>1</b> more characters!"), 
			$(".private").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click"), 
			$(".ladder").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click"), 
			$(".quick").css({
				opacity: "0.6",
				cursor: "url(./tpl/default/css/images/cursor.png), auto"
			}).unbind("click");
			break;
		case 3:
			$(".char_list_text").html("You are ready to start a game!"),
			$(".private").css({
				opacity: "1",
				cursor: "url(./tpl/default/css/images/active.png), auto"
			}).on('click', function (event) {  
				if (!canSendAjax)
					return;
				canSendAjax=false;
       		 	event.preventDefault();
				popUp("search-private")
			}), 
			$(".ladder").css({
				opacity: "1",
				cursor: "url(./tpl/default/css/images/active.png), auto"
			}).on('click', function (event) { 
				if (!canSendAjax)
					return;
				canSendAjax=false;			
       		 	event.preventDefault();
				popUp("search-ladder")
			}), 
			$(".quick").css({
				opacity: "1",
				cursor: "url(./tpl/default/css/images/active.png), auto"
			}).on('click', function (event) {
				if (!canSendAjax)
					return;
				canSendAjax=false;				
       		 	event.preventDefault();
				popUp("search-quick")
			});
			break;
		default:
			$(".char_list_text").html("Please drag <b>3</b> more characters!"), $(".private").css({
				opacity: "0.6",
				cursor: ""
			}).unbind("click"), $(".ladder").css({
				opacity: "0.6",
				cursor: ""
			}).unbind("click"), $(".quick").css({
				opacity: "0.6",
				cursor: ""
			}).unbind("click")
	}
}! function(i) {
	i.fn.spin = function(r, a) {
		var s = {
			tiny: {
				lines: 8,
				length: 2,
				width: 2,
				radius: 3
			},
			small: {
				lines: 8,
				length: 4,
				width: 3,
				radius: 5
			},
			large: {
				lines: 10,
				length: 8,
				width: 4,
				radius: 8
			}
		};
		if (Spinner) return this.each(function() {
			var e = i(this),
				t = e.data();
			t.spinner && (t.spinner.stop(), delete t.spinner), !1 !== r && ("string" == typeof r && (r = r in s ? s[r] : {}, a && (r.color = a)), t.spinner = new Spinner(i.extend({
				color: e.css("color")
			}, r)).spin(this))
		});
		throw "Spinner class not available."
	}, i.fn.center = function(e) {
		return e = e ? this.parent() : window, this.css({
			position: "absolute",
			top: (i(e).height() - this.outerHeight()) / 2 + i(e).scrollTop() + "px",
			left: (i(e).width() - this.outerWidth()) / 2 + i(e).scrollLeft() + "px"
		}), this
	}
}(jQuery), jQuery.ajaxSetup({
	cache: !1
}), jQuery(document).ajaxError(function(e, t, r) {
	$(document.body).html(t.responseText), errorMessage("AJAX - " + t.status, t.status, t.status)
});
var match = null;

function popUp(e) {
	switch (e || errorMessage("Error: params", "params", "none"), e) {
		case "search-private":
			preLoad("Please wait . . ."), $("#main_container").prepend('<div id="popup"><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=private", function() {
				$("#preloader").fadeOut("slow", function() {
					$("#preloader").remove()
				})
			});
			break;
		case "search-ladder":
			preLoad("Preparing pasta please wait . . ."), $("#main_container").prepend('<div id="popup"><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=ladder", function() {
				$("#preloader").fadeOut("slow", function() {
					$("#preloader").remove()
				}), match = setInterval(function() {
					$(".search").load("./?s=game&mode=search&type=ladder", function() {
						var e = $("#stopMe").attr("content");
						(e = $("#stopMe").attr("content")) && (e = (e = e.split("URL="))[1], window.location = e)
					})
				}, 1e3)
			});
			break;
		case "search-quick":
			preLoad("Decompiling the universe please wait . . ."), $("#main_container").prepend('<div id="popup"><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=quick", function() {
				$("#preloader").fadeOut("slow", function() {
					$("#preloader").remove()
				}), match = setInterval(function() {
					$(".search").load("./?s=game&mode=search&type=quick", function() {
						var e = $("#stopMe").attr("content");
						(e = $("#stopMe").attr("content")) && (e = (e = e.split("URL="))[1], window.location = e)
					})
				}, 1e3)
			});
			break;
		case "match":
			$.ajax({
				url: _path + "/core/ajax.php",
				type: "POST",
				data: {
					f: "checkMatch"
				},
				dataType: "text",
				success: function(e) {
					e && ($("#main_container").prepend(e), match = setInterval(function() {
						$(".search").length || $("#main_container").prepend('<div id="popup"><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=private", function() {
							var e = $("#stopMe").attr("content");
							(e = $("#stopMe").attr("content")) && (e = (e = e.split("URL="))[1], window.location = e)
						})
					}, 1e3))
				}
			});
			break;
		case "submit":
			$.post("./?s=game&mode=search&type=private", $("#form-pb").serialize(), function(e) {
				var t = $(e).filter("h3").text();
				$(".search").html(e), t || (match = setInterval(function() {
					$(".search").length || $("#main_container").prepend('<div id="popup"><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=private", function() {
						var e = $("#stopMe").attr("content");
						(e = $("#stopMe").attr("content")) && (e = (e = e.split("URL="))[1], window.location = e)
					})
				}, 5e3))
			})
	}
}

function sTotal() {
	var e = ["1", "2", "3"],
		t = 0;
	return $(".slot").each(function() {
		-1 == e.indexOf($(this).html()) && t++
	}), t
}

function sID(e) {
	return "slot 1 ui-droppable" == (e = $(e).attr("class")) ? "s0" : "slot 2 ui-droppable" == e ? "s1" : "s2"
}

function cleanInfo() {
	$(".character_list>div").children("img").removeClass("cselected"), $("#character_selected").hide("slide", {
		direction: "right"
	}, "fast", function() {
		w = 0, $(".sel_info_top").html(""), $(".sel_info_top").removeAttr("id"), $(".character_information").html(""), $(".character_stats").html(""), $("#character_selected").css("visibility", "hidden").css("display", "block")
	})
}

function getCInfo() {
	var t = w;
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "getCharacter",
			i: t
		},
		dataType: "json",
		success: function(e) {
			if (e.error) return errorMessage("Error: " + e.error, e.error, e.error), cleanInfo(), !1;
			$(".character_selected>*").removeAttr("style"), $(".sel_info_top").html(e.character + '<div class="skill_tree"></div>'), $(".sel_info_top").attr("id", t), $(".skill_tree").html(e.skills), $(".character_information").html("<h2>" + e.name + "</h2><p></p>"), $(".character_information>p").html(e.description), $(".character_stats").html(e.stats)
		}
	})
}

function getSkill(t) {
	if (t = t ? (checkPath(t), t.attr("id")) : 0, $("#character_selected").is(":visible") && $(".sel_info_top>img").attr("id") == t && "skill" == $(".sel_info_top>img").attr("class")) return getCInfo(), !1;
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "getSkill",
			i: t + "/" + w
		},
		dataType: "json",
		success: function(e) {
			if (e.error) return "undefined character skill" == e.error ? errorMessage("Error: " + e.error, e.error, e.name) : errorMessage("Error: " + e.error, e.error, e.error), cleanInfo(), !1;
			$(".skill_tree>img").removeAttr("style"), $(".skill_tree>img[id=" + t + "]").css({
				border: "1px solid #34d0f1 "
			}), $(".sel_info_top>img").remove(), $(".sel_info_top").prepend(e.skill), $(".sel_info_top>img").css({
				border: "1px solid #34d0f1 "
			}), $(".character_information").html("<h2>" + e.name + "</h2><p></p>"), $(".character_information>p").html(e.description), $(".character_stats").html(e.stats)
		}
	})
}

function getCharacter(t) {
	if (t = t ? (checkPath(t), t.attr("id")) : w, $("#character_selected").is(":visible") && $(".sel_info_top").attr("id") == t) return !1;
	$(".character_list>div").children("img").removeClass("cselected"), $.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "getCharacter",
			i: t
		},
		dataType: "json",
		success: function(e) {
			if (e.error) return "skills" == e.error ? errorMessage("Error: " + e.error, e.error, e.name) : errorMessage("Error: " + e.error, e.error, e.error), cleanInfo(), !1;
			$("#character_selected").hide("slide", {
				direction: "left"
			}, "fast", function() {
				w = t, $(".sel_info_top").html(e.character + '<div class="skill_tree"></div>'), $(".sel_info_top").attr("id", t), $(".skill_tree").html(e.skills), $(".character_information").html("<h2>" + e.name + "</h2><p></p>"), $(".character_information>p").html(e.description), $(".character_stats").html(e.stats), $("#character_selected").show("slide", {
					direction: "left"
				}, "slow").css("visibility", "visible"), $(".character_list>div").children("img[id=" + t + "]").addClass("cselected")
			})
		}
	})
}

function checkPath(e) {
	var t = e.attr("src").split("/");
	t = (t = t[t.length - 1].split("."))[0], e.attr("id") != t && e.attr("id", t)
}
var eid = 1;

function errorMessage(e, t, r) {
	var a = 0;
	$(".message").each(function() {
		a += $(this).outerHeight()
	}), $("body").prepend('<div class="message error ' + eid + '"><h3>' + e + "</h3><p>" + determineMessage(t, r) + "</p></div>");
	var s = $(".message.error." + eid);
	s.css({
		"margin-top": a + "px"
	}), s.on("click", function() {
		s.hide("slide", {
			direction: "up"
		}, "fast", function() {
			eid--, s.remove()
		})
	});
	setTimeout(function() {
		eid--, s.fadeOut("slow", function() {
			s.remove()
		})
	}, 5e3), s.timer, eid++
}

function determineMessage(e, t) {
	switch (e) {
		case 404:
			return "Server could not access request, please contact an administrator!";
		case "function":
			return "Undefined function, please refresh the page...";
		case "params":
			return "Undefined parameters, please refresh the page...";
		case "undefined":
			return "Undefined character ID, please refresh the page...";
		case "skills":
			return "A skill set was not defined for <b>" + t + "</b>...";
		case "invalid":
			return "Invalid account data, please re-log...";
		case "undefined skill":
			return "Undefined skill ID, please refresh the page...";
		case "undefined character skill":
			return "<b>" + t + "</b> does not have this skill...";
		case "character":
			return "You dont have this character unlocked...";
		case "group":
			return "Your group does not have access to this character...";
		default:
			return "This message is not defined..." + _path
	}
}