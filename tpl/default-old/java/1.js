
jQuery.fn.extend({
live: function(t, a) {
		return this.selector && jQuery(document).on(t, this.selector, a), this
	}
}), $(function() {
	preLoad("Loading Character Selection . . ."),
	
	// Attach the event keypress to exclude the F5 refresh
	$(window).bind("load", function() {
		
		var t = document.getElementById("main_container").offsetWidth,
		a = document.getElementById("main_container").offsetHeight;
		window.resizeTo(t, a + 64),
		appendTeam(),
		$("#character_selected").delegate(".sel_info_top, .character_information>h2, .character_information>p, .skill_tree>img", "click", function(t) {
			if (!$(this).is(":visible")) return !1;
			"skill" != $(t.target).attr("class") ? cleanInfo() : getSkill($(t.target))
		}), $(".toggleSound").live("click", function(t) {
			togglePlay($("#background_audio"))
		}),
		$(".minus, .plus").each(function(){
			$(this).data("opacityLevel","0.8");
			$(this).data("volumeLevel","1");
		}),
		// On click, check the level.
		$(".plus").on("click", function(){
			$("#background_audio").get(0).volume += 0.1;
			/*var opacityLevel = parseFloat( $(this).data("opacityLevel") );
			var volumeLevel = parseFloat( $(this).data("volumeLevel") );
			// Increment it and ensure to stay between 0.2 and 0.8
			opacityLevel += 0.1;
			if(opacityLevel == 1){
				opacityLevel = 0.1;
			}if(opacityLevel >= 0.4){
				volumeLevel +=0.1
			}
			volumeLevel += 0.1;
			if(volumeLevel > 1){
				volumeLevel = 1;
			}
			$(".minus, .plus").each(function(){
				$(this).data("opacityLevel",opacityLevel);
				$(this).data("volumeLevel",volumeLevel);
			})
			// Apply the new level to opacity and audio volume.
			//$(this).css("opacity",opacityLevel);
			$("#background_audio").get(0).volume = volumeLevel;*/

		}),
		$(".minus").on("click", function(){
			$("#background_audio").get(0).volume -= 0.1;

			/*if(volumeLevel > 0){
				volumeLevel = 0;
			}
			$(".minus, .plus").each(function(){
				$(this).data("opacityLevel",opacityLevel);
				$(this).data("volumeLevel",volumeLevel);
			})
			// Apply the new level to opacity and audio volume.
			//$(this).css("opacity",opacityLevel);
			$("#background_audio").get(0).volume = volumeLevel;*/

		})
		, $(".goback").live("click", function(t) {
			t.preventDefault(),
			$(this).hasClass("message") ? ($("#popup").remove(), popUp("search")) : $(this).hasClass("cancel") ? cancelMatch() : $("#popup").fadeOut("slow", function() {
				$("#popup").remove()
			})
		}), $('input[name="pbsubmit"]').live("click", function(t) {
			t.preventDefault(),
			popUp("submit")
		});
		var e = 0,
		i = null;
		$("#draggable>div>img").on("click", function(t) {
			var a = $(this);
			1 ===
			++
			e ? i = setTimeout(function() {
				getCharacter(a),
				e = 0
			}, 700) : (clearTimeout(i), e = 0)
		}).on("dblclick", function(t) {
			if (t.preventDefault(), $(this).parent().hasClass("slot")) $('input[name="' + sID($(this).parent()) + '"]').val(""),
			$(this).appendTo($(this).data("originalParent")),
			$(this).data("prevParent", $(this).data("originalParent")),
			appendStatus(sTotal()),
			updateTeam();
			else {
				var a = $(this),
				e = ["1", "2", "3"],
				i = !1;
				$(".slot").each(function() {
					1 != i && -1 !== e.indexOf($(this).html()) && ($('input[name="' + sID($(this)) + '"]').val(a.attr("id")), $(this).append(a), a.data("prevParent", $(this)), appendStatus(sTotal()), updateTeam(), i = !0)
				})
			}
		}), $("#draggable>div>img").draggable({
			start: function() {
				$(this).css({
					display: "none"
				})
			},
				stop: function() {
				$(this).css({
				display: "block"
				})
			},
			revertDuration: 0,
			revert: function(t) {
				if(t === false && !$(this).parent().hasClass('slot')) return t;
			return (t !== false && t.hasClass("slot")) || ($('input[name="' + sID($(this).parent()) + '"]').val(""), $(this).appendTo($(this).data("originalParent")), $(this).data("prevParent", $(this).data("originalParent")), appendStatus(sTotal()), updateTeam()), !t
			},
helper: "clone",
appendTo: ".character_list",
containment: "#ingame"
		}).each(function() {
			$(this).data("originalParent", $(this).parent())
		}), $("#droppable_slots>div").droppable({
hoverClass: "over",
drop: function(t, a) {
				var e = $(this),
				i = sID(e);
				a.draggable.data("prevParent") && a.draggable.data("prevParent") != a.draggable.data("originalParent") && "slot" == a.draggable.data("prevParent").attr("class").split(" ")[0] && $('input[name="' + sID(a.draggable.data("prevParent")) + '"]').val("");
				e.find(".ui-draggable").length && e.find(".ui-draggable").appendTo(e.find(".ui-draggable").data("originalParent")),
				e.append(a.draggable),
				$('input[name="' + i + '"]').val(a.draggable.attr("id")),
				a.draggable.data("prevParent", $(this)),
				appendStatus(sTotal()),
				updateTeam()
			}
		}), $(".filter").on("click", function(t) {
			var a = $(this);
			1 ===
			++
			e ? i = setTimeout(function() {
				if (a.attr("id")) {
					$(".filter_container > *").each(function() {
						jQuery(this).hasClass("selected") && jQuery(this).removeClass("selected")
					});
					var t = a.attr("id");
					$(".character_list > *").each(function() {
						jQuery(this).find("img").attr("data-rel") != t ? jQuery(this).fadeOut() : jQuery(this).fadeIn()
					}),
					a.toggleClass("selected")
				}
				e = 0
			}, 700) : (clearTimeout(i), e = 0)
		}).on("dblclick", function(t) {
			t.preventDefault(),
			$(".filter_container > *").each(function() {
				jQuery(this).hasClass("selected") && jQuery(this).removeClass("selected")
			}),
			$(".character_list > *").each(function() {
				jQuery(this).fadeIn()
			})
		})
	})
});

