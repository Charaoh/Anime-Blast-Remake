var scripts = document.getElementsByTagName("script"),
	src = scripts[scripts.length - 1].src,
	_path = "./2.0/", canSendAjax=true,
	w = 0;
function preLoad(e) {
	$("#selection").prepend('<div id="preloader"><img class="logo" src="/tpl/beta/css/images/logo.png"><p>' + e + "</p></div>"), $("#preloader").spin("large", "white").center(!0)
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

				var r = $(".character_list>div").find("img#"+t);
				$('input[name="s' + e + '"]').val(r.attr("id")), 
				$(".slot." + (e + 1)).append(r), 
				r.data("prevParent", $(".slot." + (e + 1))), a++
			});
			if(a < 3)
				$('.current_equiped').animate({"width":"128px"},"fast");
			appendStatus(a), setTimeout(function() {
				$("#selection").show("fast", $("#preloader").remove())
			}, 2e3)
		}
	})
}
function updateVol(which, volume){
	
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "updateVolume",
			w: which,
			v: volume
		},
		dataType: "json",
		success: function() {
			canSendAjax=true;console.log(which + volume);
		}
	})
}

function cancelMatch() {
	canSendAjax = false;
	clearInterval(match), match = null,
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "cancelMatch"
		},
		dataType: "text",
		success: function() {
			canSendAjax=true;
			 $("#popup").fadeOut("slow", function() {
				$("#popup").remove()
			})
		}
	})
}
function updateTeam() {
	
	var r = "",
		e = [], last = $('.current_equiped').html();
	"" != $("input[name=s0]").val() && e.push($("input[name=s0]").val()), 
	"" != $("input[name=s1]").val() && e.push($("input[name=s1]").val()), 
	"" != $("input[name=s2]").val() && e.push($("input[name=s2]").val());
	if(e.length == 0)
		$('.current_equiped').html('<p class="team-text">No team</p>');
	$('.team_list>p').removeClass('selected');
	$.each(e, function(e, t) {
		var clone = $('.slot').find('img[id='+t+']').clone().removeAttr('class').removeAttr('style');
		if(e==0){
			$('.current_equiped').html(clone);
		}else{
			$('.current_equiped').append(clone);
			$('.current_equiped').animate({"width": "128px"},"fast");
			if(e==2){
				$('.current_equiped').animate({"width":"143px"},"fast",function(){
					$('.current_equiped').append('<img class="save" src="./tpl/beta/css/images/save.png">');
				});
			}
		}
		r = 0 == e ? t : r + "," + t
	});
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "setTeam",
			i: r
		},
		dataType: "json",
		success: function(e) {
			if(e !== null)
				errorMessage("Error: " + e.error, e.error, e.error);
		}
	})
	
}

function getTip(){
	let tipsy = $('.tips>p'), newtip = Math.floor(Math.random() * tipsy.length);
	tiping = setInterval(function(){
		do{
			newtip = Math.floor(Math.random() * tipsy.length);
		}while($('.tip>span').attr('class') == newtip);
		$('.tip>span').fadeOut("slow",function(){
			$('.tip>span').text($(tipsy[newtip]).text()).fadeIn();
			$('.tip>span').attr('class',newtip);
		});
	}, 8000);
	return $(tipsy[newtip]).text();
}

function appendStatus(e) {
	switch ((!e || e < 0) && (e = 0), e) {
		case 0:
			$(".hover").hide();
			$(".char_list_text").html("Please drag <b>3</b> more characters!"), 
			$(".private").css({
				opacity: "0.6"
			}),$(".private").find(".hover").unbind("click"), 
			$(".ladder").css({
				opacity: "0.6"
			}),$(".ladder").find(".hover").unbind("click"), 
			$(".quick").css({
				opacity: "0.6"
			}),$(".quick").find(".hover").unbind("click");
			break;
		case 1:
			$(".hover").hide();
			$(".char_list_text").html("Please drag <b>2</b> more characters!"), 
			$(".private").css({
				opacity: "0.6"
			}),$(".private").find(".hover").unbind("click"), 
			$(".ladder").css({
				opacity: "0.6"
			}),$(".ladder").find(".hover").unbind("click"), 
			$(".quick").css({
				opacity: "0.6"
			});
			$(".quick").find(".hover").unbind("click");
			break;
		case 2:
			$(".hover").hide();
			$(".char_list_text").html("Please drag <b>1</b> more characters!").removeClass('move'), 
			$(".private").css({
				opacity: "0.6"
			}),$(".private").find(".hover").unbind("click"), 
			$(".ladder").css({
				opacity: "0.6"
			}),$(".ladder").find(".hover").unbind("click"),  
			$(".quick").css({
				opacity: "0.6"
			});
			$(".quick").find(".hover").unbind("click");
			break;
		case 3:
			$(".hover").css("display","inline-block");
			$(".char_list_text").html("You are ready to start a game!").addClass('move'),
			$(".private").css({
				opacity: "1"
			});
			$(".private").find(".hover").on('click touchstart', function (event) {  
				if (!canSendAjax)
					return;
				canSendAjax=false;
       		 	event.preventDefault();
				popUp("search-private")
			}), 
			$(".ladder").css({
				opacity: "1"
			});
			$(".ladder").find(".hover").on('click touchstart', function (event) { 
				if (!canSendAjax)
					return;
				canSendAjax=false;			
       		 	event.preventDefault();
				popUp("search-ladder")
			}), 
			$(".quick").css({
				opacity: "1"
			});
			$(".quick").find(".hover").on('click touchstart', function (event) {
				if (!canSendAjax)
					return;
				canSendAjax=false;				
       		 	event.preventDefault();
				popUp("search-quick")
			});
			break;
		default:
			$(".hover").hide();
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
	return true;
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
var match = null,tiping = null;

function popUp(e) {
	switch (e || errorMessage("Error: params", "params", "none"), e) {
		case "search-private":
			preLoad("Please wait . . ."), $("#selection").prepend('<div id="popup"><img src="./tpl/beta/css/images/zoro.gif" class="zoro"><p class="tip"><span>'+getTip()+'</span><br></p><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=private", 
			function() {
				$("#preloader").fadeOut("slow", function() {
					$("#preloader").remove()
				})
			});
			break;
		case "search-ladder":
			preLoad("Preparing pasta please wait . . ."), $("#selection").prepend('<div id="popup"><img src="./tpl/beta/css/images/zoro.gif" class="zoro"><p class="tip"><span>'+getTip()+'</span><br></p><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=ladder", function() {
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
			preLoad("Decompiling the universe please wait . . ."), $("#selection").prepend('<div id="popup"><img src="./tpl/beta/css/images/zoro.gif" class="zoro"><p class="tip"><span>'+getTip()+'</span><br></p><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=quick", function() {
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
					e && ($("#selection").prepend(e), match = setInterval(function() {
						$(".search").length || $("#selection").prepend('<div id="popup"><img src="./tpl/beta/css/images/zoro.gif" class="zoro"><p class="tip"><span>'+getTip()+'</span><br></p><div class="search"></div></div>'), $(".search").load("./?s=game&mode=search&type=private",
						function() {
							var e = $("#stopMe").attr("content");
							(e = $("#stopMe").attr("content")) && (e = (e = e.split("URL="))[1], window.location = e)
						})
					}, 1e3))
				}
			});
			break;
		case "submit":
			$.post("./?s=game&mode=search&type=private", $("#form-pb").serialize(), 
			function(e) {
				var t = $(e).filter("h3").text();
				$(".search").html(e), t || (match = setInterval(function() {
					$(".search").length || $("#selection").prepend('<div id="popup"><img src="./tpl/beta/css/images/zoro.gif" class="zoro"><p class="tip"><span>'+getTip()+'</span><br></p><div class="search"></div></div>'), 
					$(".search").load("./?s=game&mode=search&type=private", function() {
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
	$(".character_list>div").children("img").removeClass("shineme"), 
	$(".character-page").hide("slide", {direction: "right"}, 
	"fast", function() {
		w = 0, 
		$(".header").html(""), $(".header").removeAttr("id"), 
		$(".skill-list").html(""), 
		$(".description").html(""), 
		$(".cooldown").html(""), 
		$(".cost").html(""), 
		$(".skill-class").remove(),
		$(".charater-page").css("visibility", "hidden").css("display", "block")
	})
}

function getCInfo() {
	$("#selection")
	$("#selection").css("pointer-events","none");
	$("body").css("cursor","progress");
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
			$("#selection").css("pointer-events","");
			$("body").removeAttr('style');
			canSendAjax = true;
			if (e.error) return errorMessage("Error: " + e.error, e.error, e.error), cleanInfo(), !1;
			$(".skill-list>*").removeAttr("style"), 
			$(".character-options>*").removeAttr("style"), 
			w = t, $(".header").html(e.name), 
			$(".header").attr("id", t),
			$(".header").removeData("skill"),
			$(".description").html(e.description), 
			$(".character-page-bottom").html(e.stats), 
			$(".character-page").fadeIn("slow",function(){$(".character-page-bottom").fadeIn();});
		}
	})
}

function getSkill(t) {
	var passive = false;
	if(t.hasClass('passive'))
		passive = true;
	if (t = t ? (checkPath(t), t.attr("id")) : 0, $(".character-page").is(":visible") && $(".header").attr("id") == t && $(".header").data("skill")) return getCInfo(), !1;
	$("body").css({"cursor":"progress"});
	$("#selection").css({"pointer-events":"none"});
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "getSkill",
			i: t + "/" + w
		},
		dataType: "json",
		success: function(e) {
			$("#selection").css("pointer-events","");
			$("body").removeAttr('style');
			canSendAjax = true;
			if (e.error) return "undefined character skill" == e.error ? errorMessage("Error: " + e.error, e.error, e.name) : errorMessage("Error: " + e.error, e.error, e.error), cleanInfo(), !1;
			$(".skill-list>img").removeAttr("style");
			$(".character-options>img").removeAttr("style");
			if(passive)
				$(".character-options>img[id=" + t + "]").css({
				border: "1px solid #34d0f1 "
			});
			else
				$(".skill-list>img[id=" + t + "]").css({
				border: "1px solid #34d0f1 "
			});
			$(".header").attr('id', t),$(".header").data("skill",true),
			$(".header").html(e.name), $(".description").html(e.description), $(".character-page-bottom").html(e.stats);
			
		}
	})
}

function getCharacter(t) {
	if (t = t ? (checkPath(t), t.attr("id")) : w, $(".character-page").is(":visible") && 
	$(".header").attr("id") == t) return !1;
	$("body").css({"cursor":"progress"});
	$("#selection").css({"pointer-events":"none"});
	$(".character_list>div").children("img").removeClass("shineme"), 
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "getCharacter",
			i: t
		},
		dataType: "json",
		success: function(e) {
			$("#selection").css("pointer-events","");
			$("body").removeAttr('style');
			canSendAjax = true;
			if (e.error) return "skills" == e.error ? errorMessage("Error: " + e.error, e.error, e.name) : errorMessage("Error: " + e.error, e.error, e.error), cleanInfo(), !1;
			$(".character-page").hide("slide", {
				direction: "right"
			}, "fast", function() {
				$(".character-page-bottom").fadeOut();
				w = t, $(".header").html(e.name), 
				$(".header").attr("id", t),
				$(".character-avatar").html("<span></span>"+e.slanted),
				$(".skill-list").not(".alts").html(e.skills);
				$('div[class="skill-list"').fadeIn("fast");
				$('.alts.skill-list').fadeOut();
				$(".character-options").html("");
				if(e.alts !== undefined){
					$(".alts.skill-list").html(e.alts);
					$(".character-options").append('<p class="alternatives">Alternative Skills</p>');
				}
				if(e.transformations !== undefined){
					$(".character-options").append('<p class="transformations">Alternative Forms</p>');
					$(".character-avatar").append(e.transformations);
				} 
				if(e.passives !== undefined){
					$(".character-options").append('<p class="passives">Passive Abilities</p>'+e.passives);
				} 
				$(".description").html(e.description), 
				$(".character-page-bottom").html(e.stats), 
				$(".character-page").fadeIn("slow",function(){$(".character-page-bottom").fadeIn();}), 
				$(".character_list>div").children("img[id=" + t + "]").addClass("shineme")
				
			})
		}
	})
}
function buyThis(id){
	$("#selection").css("pointer-events","none");
	$("body").css("cursor","progress");
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "buyThis",
			i: id
		},
		dataType: "json",
		success: function(e) {
			$("#selection").css("pointer-events","");
			$("body").removeAttr('style');
			canSendAjax = true;
			if (e.error) return errorMessage("Error: " + e.error, e.error, e.name), !1;
			if(e.character){
				$('.character_list').html(e.character[1]);
				$(".character_list>div>img").draggable({
					start: function(e, ui) {
						$(this).css({
							display: "none"
						});
					},
					stop: function() {
						$(this).css({
							display: "block"
						})
					},
					revertDuration: 0,
					revert: function(t) {
						if(t === false && !$(this).parent().hasClass('slot')) return t;
						return (t !== false && t.hasClass("slot")) || 
						($('input[name="' + sID($(this).parent()) + '"]').val(""), 
						$(this).appendTo($(this).data("originalParent")), 
						$(this).data("prevParent", $(this).data("originalParent")), 
					appendStatus(sTotal()), 
					updateTeam()), !t
					},
					helper: "clone",
					appendTo: "body",
					containment: "#selection"
				}).each(function() {
					$(this).data("originalParent", $(this).parent())
				}),
				$('#characters:first').fadeOut();
				$('#characters:first>.i').remove();
				$('#characters:first').append(e.character[0]);
				$('#characters:first').fadeIn();
				$('.buy-out').fadeOut();
				
			}else if(e.sfx){
				console.log(e);
				$('.select.sfx').append('<option value="'+e.sfx[0]+'">'+e.sfx[0]+'</option>');
				$('#sfx:first').fadeOut();
				$('#sfx:first>.i').remove();
				$('#sfx:first').append(e.sfx[1]).fadeIn();
				$('#sfx:first').fadeIn();
				$('#sfx:first>.i').fadeIn()
			}
			$('.pool').contents().filter(function(){return (this.nodeType == 3);}).remove();
			$('.pool').append(e.gold);
			return swal({
				title: "Successfully bought!",
				text: "You have bought this item",
				icon: 'success'
			});
		}
	})
}
function change(which = null, value){
	if(which == null) return;
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "change"+which,
			i: value
		},
		dataType: "json",
		success: function(e) {
			canSendAjax = true;
			if (e.error) return errorMessage("Error: " + e.error, e.error, e.name), !1;
			if(which == 'selection' || which == 'ingame'){
				if(which == 'selection'){
					
					$('#selection').css('background-image', 'url(' + value + ')');
					$('#selection').css('background-size', 'cover');
					if(value.length == 0)
						$('#selection').removeAttr('style');
				}
				return swal({
				title: "Successfully updated!",
				text: "You have changed the "+which+" bg successfully!",
				icon: 'success'
				})
			}
			return swal({
				title: "Successfully updated!",
				text: "You have changed the "+which+" to: "+value,
				icon: 'success',
				timer: 2000,
				buttons: false,
				closeOnEsc: false,
				closeOnClickOutside: false
			}).then(() => {window.location.reload(true);})
		}
	})
}
function saveTeam(name){
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "saveTeam",
			i: name
		},
		dataType: "json",
		success: function(e) {
			if (e.error) return "save" == e.error ? errorMessage("Error: " + e.error, e.error, e.name) : errorMessage("Error: " + e.error, e.error, e.error), !1;
			$('.team_list').prepend(e.team);
			$('.current_equiped>.save').remove();
			$('.current_equiped').animate({"width": "128px"},"fast");
			return swal({
				title: "Successfully saved",
				text: "Equiped team!",
				icon: 'success'
			});
		}
	})
}
function selectTeam(id, pteam){
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "selectTeam",
			i: id
		},
		dataType: "json",
		success: function(e) {
			if (e.error) 
				return errorMessage("Error: " + e.error, e.error, e.error);
			var e = e.team,counted=0;
			$('.current_equiped').html('');
			$('.slot').each(function(i,v) {
				elem = null;
				if($(this).find('img').length > 0){
					var old = $(this).find('img');
					old.appendTo(old.data("originalParent")),
					old.data("prevParent", old.data("originalParent"))
				}
			});
			$.each(e, function(i,v) {
				elem = null;
				elem = $('.character_list>div').find('img[id='+v+']');
				elem.appendTo($('.slot.'+(i+1))),
				elem.data("prevParent", $('.slot.'+(i+1)));
				$('.current_equiped').append(elem.clone());
				$('input[name="s' + i + '"]').val(elem.attr("id"));
				counted++;
			});
			$('.current_equiped').animate({"width": "128px"},"fast");
			
			$('.team_list>p').removeClass('selected');
			pteam.addClass('selected');
			pteam.prependTo('.team_list');
			appendStatus(counted);
			return true;
		}
	})
}
function deleteTeam(id){
	$.ajax({
		url: _path + "/core/ajax.php",
		type: "POST",
		data: {
			f: "deleteTeam",
			i: id
		},
		dataType: "json",
		success: function(e) {
			//if (e.error) return "deleting" == e.error ? errorMessage("Error: " + e.error, e.error, e.name) : errorMessage("Error: " + e.error, e.error, e.error), !1;
			$('.current_equiped').html('');
			appendStatus(sTotal());
			updateTeam();
			if($('.team_list>p').length == 0)
				$('.team_list').append('<p>Manage your teams here!</p>');
			return swal({
				title: "Successfully deleted",
				icon: 'success'
			});
			
			
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
		case "insuficient":
			return "You have insuficient blast coins...";
		case "notfound":
			return "Sale not found...";
		default:
			return "This message is not defined..." + _path
	}
}