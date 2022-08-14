let Listener, verify, scripts = document.getElementsByTagName("script"),
    src = scripts[scripts.length - 1].src,
    canSendAjax = true,
    _path = `./${gVersion}/`,
    cload = false,
    Timer = null,
    skipuiload = false,
    bg = { "win": ["./tpl/beta/sound/win.mp3"], "lose": ["./tpl/beta/sound/lose.mp3"] },
    efx = { "end": ["./tpl/beta/sound/end.mp3"], "start": ["./tpl/beta/sound/start.mp3"], "click": ["./tpl/beta/sound/click.mp3"], "impossible": ["./tpl/beta/sound/impossible.mp3"], "taking": ["./tpl/beta/sound/taking.mp3"], "choosen": ["./tpl/beta/sound/choosen.mp3"], "death": ["./tpl/beta/sound/death.mp3"], "transform": ["./tpl/beta/sound/transformation.mp3"] };
for (key in efx) {
    efx[key] = new Howl({
        src: efx[key][0],
        html5: true,
        preload: true,
        volume: Number(vsfx)
    })
};
for (key in bg) {
    bg[key] = new Howl({
        src: bg[key][0],
        html5: true,
        preload: true,
        volume: Number(mvol)
    })
};

function getCharacters() {
    if (cload) return true;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            characters = JSON.parse(this.responseText);
            cload = false;
            Object.freeze(cload);
        }
    };
    xmlhttp.open("GET", "./inc/database.php?c=" + characters, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send();
}
window.onload = getCharacters();
! function (i) {
    i.fn.spin = function (r, a) {
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
        if (Spinner) return this.each(function () {
            var e = i(this),
                t = e.data();
            t.spinner && (t.spinner.stop(), delete t.spinner), !1 !== r && ("string" == typeof r && (r = r in s ? s[r] : {}, a && (r.color = a)), t.spinner = new Spinner(i.extend({
                color: e.css("color")
            }, r)).spin(this))
        });
        throw "Spinner class not available."
    }, i.fn.center = function (e) {
        return e = e ? this.parent() : window, this
    }
}(jQuery);
(function ($) {
    $.fn.updateAvatars = function (team) {
        let transform = false,
            d = {
                "00": ".slot.A",
                "01": ".slot.B",
                "02": ".slot.C",
                "10": ".eslot.A",
                "11": ".eslot.B",
                "12": ".eslot.C"
            };

        $.each(team, function (index, chunk) {
            if (health[index] === undefined) return;
            if (health[index]['total'] == '0') return;
            let tempc = $(team[index]['image']),
                cimage = $(d[index]).find('.character-frame').children(":first-child");
            $(d[index]).find('.damaged').append(tempc);
            if (tempc.prop('outerHTML') !== cimage.prop('outerHTML')) {
                tempc.remove();
                if ($(d[index]).find('.damaged').hasClass('expand'))
                    $(d[index]).find('.damaged').removeClass('expand').hide();
                $(d[index]).find('.damaged').show(0, function () {
                    $(this).addClass('expand'), cimage.replaceWith(team[index]['image']);
                    $(this).children(":first-child").replaceWith(team[index]['image'])
                });
                transform = true;
                if (team[index]['original'])
                    transform = false;
            } else
                tempc.remove();
        });
        if (transform)
            efx["transform"].play();
    },
        $.fn.updateBars = function (what, last, now, object = false) {
            let d = {
                "00": "slot.A",
                "01": "slot.B",
                "02": "slot.C",
                "10": "eslot.A",
                "11": "eslot.B",
                "12": "eslot.C"
            };
            if (what == 'Healths') {
                jQuery.each(now, function (index, chunk) {
                    if (now == undefined || now[index] == undefined) return;
                    if (last[index] === undefined) {
                        if ($("." + d[index]).find('.manaBar').length === 0)
                            $("." + d[index]).find('.le').after(object.manas[index].bar.full);
                        $("." + d[index]).find('.character-frame').attr('id', object.team[index].id);
                        if ($("." + d[index]).find('.character-frame').find('.brief').length === 0) {
                            $("." + d[index]).find('.character-frame>*').eq(0).replaceWith(object.team[index]['image']);
                            $("." + d[index]).find('.character-frame').append(object.healths[index].bar.brief);
                            $("." + d[index]).find('.character-frame').append(object.manas[index].bar.brief);
                        }
                        last[index] = {
                            total: 1,
                            width: 0
                        }
                    }
                    if (last[index]['total'] !== now[index]['total']) {
                        if (last[index]['total'] - now[index]['total'] > 0) {
                            if ($("." + d[index]).find('.damaged').hasClass('expand'))
                                $("." + d[index]).find('.damaged').removeClass('expand').hide();
                            $("." + d[index]).find('.damaged').show(0, function () { $(this).addClass('expand') });
                            ! function t(e, i) {
                                setTimeout(function () {
                                    if ((i - now[index]['total']) >= 50)
                                        i = now[index]['total']; //  quick skip
                                    e.find('.brief.health>p').html(i)
                                    if (i == 0) {
                                        efx["death"].play();
                                        e.find('.character-frame').children(":first-child").replaceWith('<p>X</p>');
                                        e.find('.brief.health').fadeOut();
                                        e.find('.brief.mana').fadeOut();
                                    }
                                    if (i != now[index]['total']) {
                                        i--
                                        t(e, i);
                                    }
                                }, 12)
                            }($("." + d[index]), last[index]['total']);
                            $("." + d[index]).find(".healthBar.Left").animate({
                                height: now[index]['width'] + "%",
                                display: "block"
                            }, "fast", function () {
                                if (now[index]['background'].length > 0) {
                                    $("." + d[index]).find(".healthBar.Left").css('background', now[index]['background']);
                                    $("." + d[index]).find(".brief.health").css('background', now[index]['background']);
                                }
                            });
                        } else if (last[index]['total'] - now[index]['total'] < 0) {
                            ! function t(e, i) {
                                setTimeout(function () {
                                    if ((now[index]['total'] - i) >= 50)
                                        i = now[index]['total']; //  quick skip
                                    e.find('.brief.health>p').html(i);
                                    if (i != now[index]['total']) {
                                        i++
                                        t(e, i);
                                    }
                                }, 12)
                            }($("." + d[index]), last[index]['total']);
                            $("." + d[index]).find(".healthBar.Left").animate({
                                height: now[index]['width'] + "%",
                                display: "block"
                            }, "fast", function () {
                                if (now[index]['background'].length > 0) {
                                    $("." + d[index]).find(".healthBar.Left").css('background', now[index]['background']);
                                    $("." + d[index]).find(".brief.health").css('background', now[index]['background']);
                                    if (!$("." + d[index]).find(".brief.health").is(':visible')) {
                                        $("." + d[index]).find(".brief.health").fadeIn();
                                        $("." + d[index]).find(".brief.mana").fadeIn();
                                    }
                                } else {
                                    $("." + d[index]).find(".healthBar.Left").css('background', ''),
                                        $("." + d[index]).find(".brief.health").css('background', '');
                                }
                            });
                        }
                    }
                });
            } else {
                jQuery.each(last, function (index, chunk) {
                    if (now == undefined || now[index] == undefined) return;
                    if (last[index]['total'] !== now[index]['total']) {
                        if (last[index]['total'] - now[index]['total'] > 0) {
                            ! function t(e, i) {
                                setTimeout(function () {
                                    if ((i - now[index]['total']) >= 50)
                                        i = now[index]['total']; //  quick skip
                                    e.find('.brief.mana>p').html(i)
                                    if (i != now[index]['total']) {
                                        i--
                                        t(e, i);
                                    }
                                }, 12)
                            }($("." + d[index]), last[index]['total']);
                            $("." + d[index]).find(".manaBar.Left").animate({
                                width: now[index]['width'] + "%",
                                display: "block"
                            }, "fast");
                        } else if (last[index]['total'] - now[index]['total'] < 0) {
                            ! function t(e, i) {
                                setTimeout(function () {
                                    if ((now[index]['total'] - i) >= 50)
                                        i = now[index]['total']; //  quick skip
                                    e.find('.brief.mana>p').html(i);
                                    if (i != now[index]['total']) {
                                        i++
                                        t(e, i);
                                    }
                                }, 12)
                            }($("." + d[index]), last[index]['total']);
                            $("." + d[index]).find(".manaBar.Left").animate({
                                width: now[index]['width'] + "%",
                                display: "block"
                            }, "fast");
                        }
                    }
                });
            }
        },
        $.fn.barCheck = function (data) {
            let d = {
                "00": ".slot.A",
                "01": ".slot.B",
                "02": ".slot.C",
                "10": ".eslot.A",
                "11": ".eslot.B",
                "12": ".eslot.C"
            };
            jQuery.each(data, function (key, val) {
                if (key > 2) {
                    if (val.bar !== undefined) {
                        $(d[key]).find('.character-frame').append(val.bar);
                    } else {
                        let foundBar = $(d[key]).find('.character-frame').find('.brief.mana');
                        if (foundBar !== undefined)
                            foundBar.fadeOut('slow', function () {
                                $(this).remove();
                            });
                    }
                }
            });

        },
        $.fn.sleep = function (t) {
            for (var e = (new Date).getTime();
                (new Date).getTime() < e + t;);
        },
        $.fn.loadUp = function () {
            $("#wrapper").spin("large", "white").center(!0),
                $("#wrapper").css("pointer-events", "none"),
                //$("body").css("cursor","url(tpl/beta/css/images/loading.gif), auto");
                $("body").css("cursor", "progress");
        },
        $.fn.cleanse = function () {
            $(".spinner").remove();
            $("#wrapper").css("pointer-events", ""),
                $("body").css("cursor", "");
        };
})(jQuery);
! function (d) {
    var updateUI;
    d.fn.listener = function (t) {
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
                success: function (t) {
                    if ("loser" == t.status || "winner" == t.status) return me = !1, d(window).stopTimer(), clearTimeout(Listener), Listener = null, "loser" == t.status ? bg["lose"].play() : bg["win"].play(), d.ajax({
                        url: _path + "/core/ajax.php",
                        type: "POST",
                        data: {
                            f: "return_",
                            w: "fin"
                        },
                        dataType: "json",
                        success: function (t) {
                            d.fn.cleanse();
                            d(".turnText").html(t.title), d("." + t.status).find('span').html(t.result), d("." + t.status).fadeIn();
                        }
                    });

                },
                complete: function (t) {
                    t = JSON.parse(t.responseText);
                    (t.turn != e || i != t.status) ? "loser" !== t.status && "winner" !== t.status && ( /* window.location = "./battle" */ updateUI()) : "loser" !== i && "winner" !== i && (Listener = setTimeout(a, 5e3))
                }
            })
        }
        a(),
            updateUI = function () {

                d(window).stopTimer(),
                    clearTimeout(Listener),
                    Listener = null;
                if ($('.casted').length)
                    $('.casted').remove();
                if ($('.animate').is(':visible'))
                    $('.animate').hide();
                d.ajax({
                    url: _path + "/core/ajax.php",
                    type: "POST",
                    data: {
                        f: "checkStatus",
                        i: 1
                    },
                    dataType: "json",
                    success: function (t) {
                        d.fn.loadUp();
                        me = t.update.match["ME"];
                        let e = ["0", "1"];
                        if (!skipuiload) {
                            skipuiload = true;
                            d.fn.updateBars('Healths', health, t.healths, t);
                            health = t.healths;
                            d.fn.updateBars('Manas', mana, t.manas, t);
                            d.fn.barCheck(t.team);
                            mana = t.manas;
                            d.fn.updateAvatars(t.team);
                            for (var a in e) {
                                for (i = 0; i < 3; i++) {
                                    let l = {
                                        0: "A",
                                        1: "B",
                                        2: "C"
                                    },
                                        skills;
                                    d('input[id="' + a + '' + i + '-targeted"').val("X")
                                    if (a === "1") {
                                        if (t.team['1' + i]['active'] !== undefined) {
                                            d(".eslot." + l[i]).find('.le').html(t.team['1' + i]['active']);
                                            //d(".eslot." + l[i]).find('.le').find('.preview').addClass('expand');
                                            d(".eslot." + l[i]).find('.le').append('<div class="fl-l new"></div>');
                                        } else {
                                            d(".eslot." + l[i]).find('.le').html('<div class="fl-l new"></div>');
                                        }
                                        if (t.healths['1' + i]['total'] == '0') {
                                            d(".eslot." + l[i]).find('.le').html('<div class="fl-l new"></div>');
                                        }
                                    } else {
                                        if (t.team['0' + i]['active'] !== undefined) {
                                            d(".slot." + l[i]).find('.le').html(t.team['0' + i]['active']);
                                            //d(".slot." + l[i]).find('.le').find('.preview').addClass('expand');
                                            d(".slot." + l[i]).find('.le').append('<div class="new"></div>');
                                        } else {
                                            d(".slot." + l[i]).find('.le').html('<div class="new"></div>');
                                        }
                                        if (t.healths['0' + i]['total'] == '0') {
                                            d(".slot." + l[i]).find('.le').html('<div class="new"></div>');
                                        }
                                    }
                                }
                            }
                            if (d(".preview").length) {
                                d(".preview").show(0, function () {
                                    if (!d(this).hasClass("expand"))
                                        d(this).addClass('expand')
                                    else
                                        d(this).remove();
                                });
                            }
                        }
                        if (t.status == 'playerTurn') {
                            if (t.update.match["ME"]) {

                                me = true;
                                d("form").attr('name', 'turn-' + t.turn);
                                for (i = 0; i < 3; i++) {
                                    let l = {
                                        0: "A",
                                        1: "B",
                                        2: "C"
                                    },
                                        skills;
                                    d('input[id="0' + i + '-skill"]').val("X");
                                    o = d(".slot." + l[i]).find(".character-frame").attr("id"),
                                        c = d(this).attr("id");

                                    if (t.healths['0' + i]['total'] == '0') {
                                        d(".slot." + l[i]).find(".skills").html("");
                                        d(".slot." + l[i]).find("div[class=manaBar]").fadeOut(0,
                                            function () {
                                                d(".slot." + l[i]).find(".skills").addClass('wait');
                                            });
                                    } else {
                                        // If revived--->
                                        if (d(".slot." + l[i]).find(".skills").hasClass('wait')) {
                                            d(".slot." + l[i]).find(".skills").prepend('<img class="selected fl-l opacity" src="https://www.anime-blast.com/tpl/default/css/images/select.png" style="display: none;">')
                                            d(".slot." + l[i]).find(".skills").removeClass('wait');
                                        }
                                        d(".slot." + l[i]).find(".skills>*").slice(1).remove();
                                        d(".slot." + l[i]).find("div[class=manaBar]").removeAttr("style");
                                        d(".slot." + l[i]).find(".skills").removeAttr("style");
                                        d(".slot." + l[i]).find(".skills").removeAttr("style");
                                        d(".slot." + l[i]).find(".selected").removeClass('opacity').fadeIn('fast');
                                    }

                                    d.each(t.team['0' + i]['skills'], function (index, chunk) {
                                        //if(index ) return false;
                                        //skills += t.team['0'+i]['skills'][index];
                                        d(".slot." + l[i]).find(".skills").append(t.team['0' + i]['skills'][index]);
                                        /*if(index == 3)
                                            d(".slot." + l[i]).find(".skills").append(skills);*/
                                    });
                                }
                                available = t.team['targets'];
                                d(".casted").remove(),
                                    d(".animate").css("display", "none"),
                                    d(".character-frame").removeClass("targeting"),
                                    d('input[id="order"').val("X");
                                d('input[id="current"').val("X");
                                d('input[id="end"').val("false");
                                d('.turnText,.endMatch').html('End Turn');
                            }
                            /* else{
                                                        match_status = 'winner';
                                                        $.ajax({
                                                            url: _path + "/core/ajax.php",
                                                            type: "POST",
                                                            data: {
                                                                f: "return_",
                                                                w: "surrender",
                                                                i: "win"
                                                            },
                                                            dataType: "json",
                                                            success: function(t) {
                                                                !1 !== t.result && ($("#popup").remove(), $(".turnText").html("You have won!"), $.ajax({
                                                                url: _path + "/core/ajax.php",
                                                                type: "POST",
                                                                data: {
                                                                    f: "return_",
                                                                    w: "fin"
                                                                },
                                                                dataType: "json",
                                                                success: function(t) {
                                                                    bg["win"].play(), 
                                                                    $(".turnText").html(t.title),
                                                                    $(".winner").find('span').html(t.result), 
                                                                    $(".winner").fadeIn();
                                                                }
                                                                }))
                                                            }
                                                        })
                                                        return false;
                                                    } */
                            skipuiload = false;
                        } else if (t.status == 'checking') {
                            if (t.update.match["ME"]) {
                                d(window).stopTimer(),
                                    clearTimeout(Listener),
                                    Listener = null,
                                    d.ajax({
                                        type: "GET",
                                        url: "./battle",
                                        data: {},
                                        success: function (data) {

                                            updateUI();

                                            return false;
                                        }
                                    });
                                return false;
                            }
                            /*else{
                                                        if(!skipuiload){
                                                        match_status = 'winner';
                                                        $.ajax({
                                                            url: _path + "/core/ajax.php",
                                                            type: "POST",
                                                            data: {
                                                                f: "return_",
                                                                w: "surrender",
                                                                i: "win"
                                                            },
                                                            dataType: "json",
                                                            success: function(t) {
                                                                !1 !== t.result && ($("#popup").remove(), $(".turnText").html("You have won!"), $.ajax({
                                                                url: _path + "/core/ajax.php",
                                                                type: "POST",
                                                                data: {
                                                                    f: "return_",
                                                                    w: "fin"
                                                                },
                                                                dataType: "json",
                                                                success: function(t) {
                                                                    bg["win"].play(), 
                                                                    $(".turnText").html(t.title),
                                                                    $(".winner").find('span').html(t.result), 
                                                                    $(".winner").fadeIn();
                                                                }
                                                                }))
                                                            }
                                                        })
                                                        return false;
                                                        } 
                                                    }*/
                        }
                        d.fn.cleanse();
                        d(".numberTurn,.timer>div>p,.toggleButton>p").html(t.prettyStatus);
                        match_status = t.status;
                        turn = t.turn;
                        d(window).startTimer(t.startTime, t.maxTime, t.turn), d(window).listener(t.status);
                    }
                })
            }
    }, d.fn.startTimer = function (a, n) {
        if (Timer !== null)
            d.fn.stopTimer();
        var s = {
            starting: new Date,
            startTime: 6e4,
            maxTime: 6e4,
            left: 0
        },
            e = new Date;
        a = a || s[a], n = n || s[n], (Math.round((s.starting.getTime() + a - e.getTime()) / 1e3)) >= 58 && 1 != turn && me && efx["end"].play();
        var r = Math.floor(n / 100);
        1 == me && d(".turnText,.endMatch").css({ opacity: 0, visibility: "visible" }).animate({ opacity: 1 }, 500);

        function l(t) {
            1 == me && d(".numberTurn,.timer>div>p,.toggleButton>p").html(Math.round(s.left / 1e3) + " Seconds left"), d(".timerBar.Left,.timer>div").slideDown("slow").css("width", t + "%")
        } ! function t() {
            if ("loser" == match_status || "winner" == match_status) return clearTimeout(Timer), Timer = null, !1;
            e = new Date;
            s.left = s.starting.getTime() + a - e.getTime();
            var i = Math.round(s.left / n * 100);
            0 <= i ? (l(i), Timer = setTimeout(t, r)) : (d(".turnText").html("Ending turn..."), 0 == me ? updateUI() : d("form[name=turn-" + turn + "]").submit());
            return !1
        }()
    }, d.fn.stopTimer = function () {
        clearTimeout(Timer), Timer = null
    }, d.fn.updateVolume = function (which, volume) {
        $.ajax({
            url: _path + "/core/ajax.php",
            type: "POST",
            data: {
                f: "updateVolume",
                w: which,
                v: volume
            },
            dataType: "json",
            success: function () {
                canSendAjax = true;
            }
        })
    },
        d.fn.verifySkill = function (e, i, a) {
            d.ajax({
                url: _path + "/core/ajax.php",
                type: "POST",
                data: {
                    f: "verifySkill",
                    c: e,
                    s: i
                },
                dataType: "json",
                success: function (t) {
                    if (1 == t.manacap && 0 == t.result) return !1;
                    a == d(window).setOpacity ? a(e, i, t.result) : a(t.result)
                }
            })
        }, d.fn.Getit = function (t, e) {
            d.ajax({
                url: _path + "/core/ajax.php",
                type: "POST",
                data: {
                    f: t,
                    w: e
                },
                dataType: "json",
                success: function (t) {
                    ! function (t) {
                        t.returned
                    }(t.result)
                }
            })
        }, d.fn.Targets = function (t, e, i, a) {
            var s = t,
                n = e,
                u = i,
                r = a;
            return {
                getTargets: function () {
                    let i = {
                        "A": "00",
                        "B": "01",
                        "C": "02"
                    },
                        e = s.closest(".slot").attr("class").split(" ")[1];
                    verify.setTargets(available[i[e]][u]['targets']);
                },
                setTargets: function (t) {
                    var e, i = {
                        A: "00",
                        B: "01",
                        C: "02"
                    };

                    if (s.closest(".slot").find(".character-frame").addClass("caster"), d(".caster").closest(".slot").find(".animate").after('<p class="casted"></p>'), e = d(".caster").closest(".slot").attr("class").split(" ")[1], "X" != d('input[id="' + i[e] + '-skill"]').val()) return !1;
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
                        10 <= a && (n = "eslot"), d("." + n + "." + i[a] + " > .animate").css("display", "block"),
                            d("." + n + "." + i[a] + " > .character-frame").addClass("targeting")
                    }
                },
                checkTarget: function (t) {
                    let i = {
                        "A": "00",
                        "B": "01",
                        "C": "02",
                        "slot A": "00",
                        "slot B": "01",
                        "slot C": "02",
                        "eslot A": "10",
                        "eslot B": "11",
                        "eslot C": "12"
                    },
                        e = s.closest(".slot").attr("class").split(" ")[1],
                        a = available[i[e]][u]['available'].split("|"),
                        tempcopy = { ...available[i[e]][u]['targets'] },
                        finalTarget = {}
                    for (var k = 0; k < a.length; k++) {
                        let targeted = {};
                        let specific = false;
                        if (a[k] == 'E/1' || a[k] == 'A/1') {
                            specific = true
                        }

                        if (a[k] == 'S') {
                            if (targeted[i[e]] === undefined)
                                targeted[i[e]] = tempcopy[i[e]];
                        } else {

                            for (var key in tempcopy) {
                                if (a[k].indexOf('E') !== -1 && key < 10) continue;
                                else if (a[k].indexOf('A') !== -1 && key >= 10) continue;
                                if (specific) {
                                    if (key !== i[r]) continue;
                                }

                                if (targeted[key] === undefined)
                                    targeted[key] = tempcopy[key];
                            }
                        }
                        // Check if or is set
                        if (Object.keys(targeted).length > 0 && targeted[i[r]] !== undefined && available[i[e]][u].condition == 'OR') {
                            finalTarget = targeted;
                            break;
                        }
                        finalTarget = Object.assign(targeted, finalTarget);
                    }
                    verify.setTargeted(finalTarget);
                },
                setTargeted: function (a) {
                    var t, e = a,
                        i = {
                            A: "00",
                            B: "01",
                            C: "02"
                        };
                    t = d(".caster").closest(".slot").attr("class").split(" ")[1], d('input[id="' + i[t] + '-skill"]').val(u);
                    var n = d(".caster").closest(".slot").find(".skills").find("#" + u).attr("src"),
                        s = (s = d(".caster").closest(".slot").find(".selected")).position(),
                        r = (r = d(".caster").closest(".slot").find(".skills").find("#" + u)).position();
                    d(".caster").closest(".slot").find(".skills").find("#" + u).toggleClass('selection');
                    d(".caster").closest(".slot").find(".skills").find("#" + u).animate({
                        top: "12px",
                        left: s.left - r.left - 19 + "px",
                        width: "50px",
                        height: "50px"
                    }, 250), d(".caster").closest(".slot").find(".skills").find("#" + u).next().css("left", s.left - r.left + 5 + "px"),
                        d(".caster").closest(".slot").find(".skills").find(".skill").each(function () {
                            d(this).attr("id") !== u && d(this).addClass("opacity")
                        }), a = available[i[d(".caster").closest('.slot').attr('class').split(' ')[1]]][u]['costs'], /*characters[d(".caster").attr('id')] , m =Object.keys(a["SKILLS"]).find(k=>a["SKILLS"][k]["ID"]===u), */ resulted = mana[i[d(".caster").closest('.slot').attr('class').split(' ')[1]]]['total'] - a;

                    function t(e, i) {
                        setTimeout(function () {
                            if ((i - resulted) >= 50)
                                i = resulted; //  quick skip    
                            e.closest('.slot').find('.brief.mana>p').html(i)
                            if (i != resulted) {
                                i--
                                t(e, i);
                            }
                        }, 12)
                    } (d(".caster"), mana[i[d(".caster").closest('.slot').attr('class').split(' ')[1]]]['total']), d(".caster").closest(".slot").find(".manaBar.Left").animate({
                        width: Math.round((resulted / 100) * 100) + "%",
                        display: "block"
                    }, 1200), d(".casted").remove(), d(".character-frame").removeClass("caster"), d(".character-frame").removeClass("targeting"), d(".animate").css("display", "none"), d('input[id="current"]').val("X");

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
                    efx["choosen"].play();
                }
            }
        }, d.fn.setOpacity = function (t, e, i) {
            0 == i ? d("#" + t).closest(".slot").find(".skills").find("#" + e).hasClass("opacity") || d("#" + t).closest(".slot").find(".skills").find("#" + e).addClass("opacity") : d("#" + e + ".skill").removeClass("opacity")
        }, d.fn.doEnd = function (t) {
            if (t.length < 1) d(window).stopTimer(), /* clearTimeout(Listener), Listener = null, */ d('input[name="end"]').val("false"), d("form[name=turn-" + turn + "]").submit();
            else if (-1 == t.indexOf(",")) d(window).stopTimer(), /* clearTimeout(Listener), Listener = null, */ d('input[name="end"]').val("true"), d("form[name=turn-" + turn + "]").submit();
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
                    }
                d("#wrapper").prepend('<div id="popup" class="queue"><div class="inner"><h1 class="title">Skill Queue</h1>Drag your skills in the order you wish to execute them!<div class="queue"><ul>' + e + '</ul></div><br><button class="button end">End Turn</button><button class="button cancel">Cancel</button></div></div>'), d('.queue').fadeIn();
                var n = {
                    start: function () {
                        d(this).css({
                            opacity: "0"
                        })
                    },
                    stop: function () {
                        d(this).css({
                            opacity: "1"
                        })
                    },
                    helper: "clone",
                    containment: "#popup"
                };
                d("#wrapper").find(".queue>ul>li>img").draggable(n), d("#wrapper").find(".queue>ul>li").droppable({
                    drop: function (t, e) {
                        var i = d(this),
                            a = e.draggable;
                        d(a).parent().html(i.html()), d(i).html(a), d("#wrapper").find(".queue>ul>li>img").draggable(n)
                    }
                })
            }
        }
}(jQuery), jQuery.ajaxSetup({
    cache: !1
}), jQuery(document).ajaxError(function (t, e, i) {
    $(document.body).html(e.responseText)
}), $(window).bind("load", function () {
    var t = document.getElementById("wrapper").offsetWidth,
        e = document.getElementById("wrapper").offsetHeight;
    window.resizeTo(t, e + 64), 1 == turn && efx["start"].play(), "loser" !== match_status && "winner" !== match_status && ($(window).startTimer(startTime, maxTime, turn), $(window).listener(match_status)), "loser" != match_status && "winner" != match_status || (me = !1, clearTimeout(Listener), Listener = null, "loser" == match_status ? bg["lose"].play() : bg["win"].play(), $.ajax({
        url: _path + "/core/ajax.php",
        type: "POST",
        data: {
            f: "return_",
            w: "fin"
        },
        dataType: "json",
        success: function (t) {
            $(".turnText").html(t.title), $("." + match_status).find('span').html(t.result), $("." + match_status).fadeIn();
        }
    }));
    var n = 0,
        s = null;
    if (0 == me && match_status == 'playerTurn' || 0 == me && match_status == 'checking') {
        if ($(".selected").is(":visible")) {
            $(".selected").addClass('opacity').fadeOut('fast', function () {
                $("div[class=manaBar]").animate({ "width": "396px" }, "fast");
                $(".skills").animate({ "background-position-x": "-66px" }, "fast");
            });
            $(".turnText").css({ opacity: 0, visibility: "hidden" });
        }
    };
    $(".turnText,.endMatch").live("click", function (t) {
        if (!me) return false;
        $(this);
        if (1 === ++n) {
            var e = "";
            for (i = 0; i < 3; i++) "X" != $('input[id="0' + i + '-skill"]').val() && void 0 !== $('input[id="0' + i + '-skill"]').val() && (e.length && (e += ","), e += $('input[id="0' + i + '-skill"]').val());
            $.fn.doEnd(e)
        } else clearTimeout(s), n = 0
    }).on("dblclick", function (t) {
        t.preventDefault(), n = 0
    }), $('form').on('submit', function (e) {
        if (!me) return false;
        e.preventDefault();
        $(window).stopTimer();
        $(window).loadUp();
        $('#popup.queue').fadeOut("fast", function () { $(this).remove(); });
        $('.cooldown').fadeOut();
        $.ajax({
            type: 'post',
            url: './battle',
            data: $('form').serialize(),
            success: function () {
                if ($(".selected").is(":visible")) {
                    $(".selected").addClass('opacity').fadeOut('fast', function () {
                        $("div[class=manaBar]").animate({ "width": "396px" }, "fast");
                        $(".skills").animate({ "background-position-x": "-66px" }, "fast");
                    });
                    $(".turnText,.endMatch").css({ opacity: 0, visibility: "hidden" });
                    $(".numberTurn,.timer>div>p,.toggleButton>p").html("Ending turn...");
                    $(".content-popup").animate({ 'margin-top': '-114px' }, '1000');
                    $(".toggleButton>p").slideDown();
                    $('.drop').toggleClass('drop');
                    for (i = 0; i < 3; i++) {
                        $('input[id="0' + i + '-skill"]').val("X");
                        var l = {
                            0: "A",
                            1: "B",
                            2: "C"
                        };
                        $(".slot." + l[i]).find(".tooltip").removeAttr('style');
                        o = $(".slot." + l[i]).find(".character-frame").attr("id"),
                            c = $(this).attr("id");
                        $(".slot." + l[i]).find(".skills").find(".skill").each(function () {
                            $(this).removeAttr("style");
                            if (!$(this).hasClass('opacity'))
                                $(this).addClass('opacity');
                        });
                    }
                }
            }
        });

    }), $(".end").live("click", function (t) {
        if (!me) return false;
        var e = $(this);
        if (1 === ++n) {
            var i = "";
            e.parent().find(".queue").find("ul").find("li").each(function () {
                i.length && (i += ","), i += $(this).children().attr("id")
            }), $('input[name="order"]').val(i), $.fn.stopTimer(), /*clearTimeout(Listener), Listener = null, */ $('input[name="end"]').val("true"), $("form[name=turn-" + turn + "]").submit()
        } else clearTimeout(s), n = 0
    }).on("dblclick", function (t) {
        t.preventDefault(), n = 0
    }), $(".skill").live("click", function (t) {
        if (!me) return false;
        if ($(this).closest('div').hasClass('skillset')) return !1;
        if ($(this).hasClass("opacity")) return !1;
        var e;
        if (e = $(this).closest(".slot").attr("class").split(" ")[1], "X" !== $('input[id="' + {
            A: "00",
            B: "01",
            C: "02"
        }[e] + '-skill"]').val()) return !1;
        "X" !== $('input[id="current"]').val() && $('input[id="current"]').val("X"),
            $(".casted").remove(), $(".animate").css("display", "none"),
            $(".character-frame").removeClass("caster"), $(".character-frame").removeClass("targeting"),
            verify = $(window).Targets($(this), $(this).closest(".slot").find(".character-frame").attr("id"), $(this).attr("id"), !0),
            $(window).verifySkill($(this).closest(".slot").find(".character-frame").attr("id"), $(this).attr("id"), verify.getTargets)
    }), $(".animate, .character-frame, .casted, .hover").live("click", function (t) {
        if (!me) return false;
        if ("X" == $('input[id="current"]').val() || void 0 === $('input[id="current').val()) return !1;
        if (!$(".animate").is(":visible")) return !1;
        var e = $(this).closest(".slot").find(".character-frame").hasClass("targeting") ? "slot" : "eslot";
        if (!$(this).closest("." + e).find(".character-frame").hasClass("targeting")) {
            if ($(".caster").length) {
                var i;
                i = $(".caster").closest(".slot").attr("class").split(" ")[1], $('input[id="' + {
                    A: "00",
                    B: "01",
                    C: "02"
                }[i] + '-skill"]').val("X"), $(".character-frame").removeClass("caster")
            }
            return $(".casted").remove(), $(".animate").css("display", "none"), $(".character-frame").removeClass("targeting"), $('input[id="current"]').val("X"), !1
        }
        verify = $(window).Targets($(".caster"), $(".caster").attr("id"), $('input[id="current"]').val(), $(this).parent().attr("class")),
            $(window).verifySkill($(".caster").attr("id"), $('input[id="current"]').val(), verify.checkTarget)
    }), $(".le>.new>img,.skill.selection").live("click touchstart", function (t) {
        if (!me) return false;
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
            }
        for (i = 0; i < 3; i++)
            if ("X" != $('input[id="0' + i + '-skill"]').val() && void 0 !== $('input[id="0' + i + '-skill"]').val() && $('input[id="0' + i + '-skill"]').val() == $(this).attr("id")) {
                $('input[id="0' + i + '-skill"]').val("X");
                var l = {
                    0: "A",
                    1: "B",
                    2: "C"
                },
                    o = $(".slot." + l[i]).find(".character-frame").attr("id"),
                    c = $(this).attr("id");
                $(".slot." + l[i]).find(".skills").find(".skill").each(function () {
                    void 0 !== $(this).attr("id") && $(this).attr("id") == c && ($(this).removeAttr("style"), $(this).next().css("left", "0px")), $(window).verifySkill(o, $(this).attr("id"), $(window).setOpacity)
                });
                var u = $(".slot." + l[i]).find(".manaBar").attr("id");
                $(".slot." + l[i]).find(".brief.mana>p").html(u), $(".slot." + l[i]).find(".manaBar.Left").stop().attr("style", "width: " + u + "%;")
            }
        $('.le>.new>img[id="' + $(this).attr("id") + '"').remove()
        $(this).toggleClass('selection');
    }), $(document).keyup(function (e) {
        if (e.key === "Escape") {
            if ($('.transparency').is(':visible'))
                $('.transparency').fadeOut();
        }
    }), $(".transparency").live("click touchstart", function (t) {
        if ($(t.target).is('.transparency'))
            $('.transparency').fadeOut();
    });
    let character;
    $(".character-frame, .hover").live("click", function (t) {
        if ($(this).hasClass('character-frame') || $(this).hasClass('hover')) {
            if ($('.transparency').is(':visible'))
                $('.transparency').fadeout();
            character = characters[$(this).prev().attr('id')], img = $(this).prev().children(":first").clone().removeClass('flip');

            $('.character-layout').find('.character-portrait').html(img);
            $('.character-layout').find('h2').html(character["NAME"]);
            $('.character-layout').find('.description').html(character["DESCRIPTION"]);
            $('.character-layout').find('.details').html('HEALTH ' + character["HEALTH"] + '<span>MANA ' + character["MANA"] + '</span>');
            $('.character-layout').find('.skillset').html('');
            $('.character-layout').find('.classes').css('display', 'none');
            let prev = "",
                next = "",
                last;
            jQuery.each(character['SKILLS'], function (i, val) {
                if (i > 3) return false;
                $('.skillset').append('<p class="pointed"><span>˅</span>' + val['IMAGE'] + '</p>');
                last = i;
            });
            if (last < character['SKILLS'].length - 1) {
                prev = character['SKILLS'].length - 1;
                if (last + 1 !== character['SKILLS'].length - 1)
                    next = last + 1;
            }
            if (prev !== "") {
                $('.skillset').prepend(character['SKILLS'][prev]['IMAGE']);
                $('.skillset>#' + character['SKILLS'][prev]["ID"]).addClass('prev').addClass('opacity');
            }
            if (next !== "") {
                $('.skillset').append(character['SKILLS'][next]['IMAGE']);
                $('.skillset>#' + character['SKILLS'][next]["ID"]).addClass('next').addClass('opacity');
            }
            $('.character-layout>.skillset').find('.pointed>span').css('display', 'none');
            $('.transparency').fadeIn();
            efx["click"].play();
        }
    }), $(".skill").live("click", function (t) {
        if (!$(this).closest('div').hasClass('skillset')) return !1;
        $(this).closest('div').find('.pointed>span').css('display', 'none');
        let sid = $(this).attr('id');
        sid = Object.keys(character["SKILLS"]).find(k => character["SKILLS"][k]["ID"] === sid);
        $('.character-layout').find('h2').html(character["SKILLS"][sid]["NAME"]);
        $('.character-layout').find('.description').html(character["SKILLS"][sid]["DESCRIPTION"]);
        $('.character-layout').find('.details').html('Cooldown: ' + character["SKILLS"][sid]["COOLDOWN"] + '<span>MANA ' + character["SKILLS"][sid]["COST"] + '</span>');
        $('.character-layout').find('.classes').html(character["SKILLS"][sid]["CLASSES"]).fadeIn();
        if ($(this).parent().hasClass('pointed')) {
            $(this).prev().fadeIn();
        } else if ($(this).hasClass('prev')) {
            $(this).closest('div').find('.skill').each((index, element) => {
                // Find last next element
                let elem = element;
                let position = Object.keys(character["SKILLS"]).find(k => character["SKILLS"][k]["ID"] === $(elem).attr('id'));
                if (position - 1 < 0)
                    position = character["SKILLS"].length - 1;
                else if (position - 1 > character["SKILLS"].length - 1)
                    position = position || 0;
                else
                    position--;
                $(elem).replaceWith(character["SKILLS"][position]['IMAGE']);
                if ($(element).hasClass('prev')) {
                    $('.skillset>#' + character["SKILLS"][position]['ID']).addClass('prev').addClass('opacity');
                } else if ($(element).hasClass('next')) {
                    $('.skillset>#' + character["SKILLS"][position]['ID']).addClass('next').addClass('opacity');
                }

            });
            $('.skillset').children(':first').next().find('span').fadeIn();
        } else if ($(this).hasClass('next')) {
            $(this).closest('div').find('.skill').each((index, element) => {
                // Find last next element
                let elem = element;
                let position = Object.keys(character["SKILLS"]).find(k => character["SKILLS"][k]["ID"] === $(elem).attr('id'));
                if (1 + (+position) > character["SKILLS"].length - 1)
                    position = 0;
                else
                    position = 1 + (+position);
                $(elem).replaceWith(character["SKILLS"][position]['IMAGE']);
                if ($(element).hasClass('prev')) {
                    $('.skillset>#' + character["SKILLS"][position]['ID']).addClass('prev').addClass('opacity');
                } else if ($(element).hasClass('next')) {
                    $('.skillset>#' + character["SKILLS"][position]['ID']).addClass('next').addClass('opacity');
                }

            });
            $('.skillset').children(':last').prev().find('span').fadeIn();
        }
    }),
        $(".surrender,.surrenderBtn").live("click", function (t) {
            if (!canSendAjax)
                return;
            canSendAjax = false;
            swal({
                title: "Are you sure?",
                text: "You will forfeit the match",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $(window).stopTimer(),
                            clearTimeout(Listener),
                            Listener = null,
                            $.ajax({
                                url: _path + "/core/ajax.php",
                                type: "POST",
                                data: {
                                    f: "return_",
                                    w: "surrender"
                                },
                                dataType: "json",
                                success: function (t) {
                                    canSendAjax = true;
                                    !1 !== t.result && ($("#popup").remove(),
                                        $(".turnText").html("You have lost!"),
                                        $.ajax({
                                            url: _path + "/core/ajax.php",
                                            type: "POST",
                                            data: {
                                                f: "return_",
                                                w: "fin"
                                            },
                                            dataType: "json",
                                            success: function (t) {
                                                match_status = "loser",
                                                    bg["lose"].play(),
                                                    $(".turnText").html(t.title),
                                                    $(".loser").find('span').html(t.result),
                                                    $(".loser").fadeIn();
                                            }
                                        }))
                                }
                            });
                    } else {
                        canSendAjax = true;
                        swal.stopLoading();
                        swal.close();
                    }
                });
        }), $(".cancel").live("click", function (t) {
            canSendAjax = true;
            $(this);
            1 === ++n ? $("#popup").remove() : (clearTimeout(s), n = 0)
        }).on("dblclick", function (t) {
            t.preventDefault(), n = 0
        }), $(".item.C").live("click", function (t) {
            $(this);
            1 === ++n ? $("#wrapper").prepend('<div id="popup"><div></div><div class="inner-content"><div class="first"><h1>Throwing the flag</h1>You are about to surrender this match... Are you sure?</div><br><button class="button surrender">Surrender</button><button class="button cancel">Cancel</button></div></div>') : (clearTimeout(s), n = 0)
        }).on("dblclick", function (t) {
            t.preventDefault(), n = 0
        }), $(".character-frame, .skill").live("click", function (t) {
            if (!$(this).hasClass('opacity'))
                efx["click"].play();
        }), $('a.tooltip').live("click", function (event) {
            event.preventDefault();
        }), $('.skills>.skill.opacity').live("click", function () { efx["impossible"].play(); });
    $('.toggleButton').live("click", function (event) {
        if (!$(this).hasClass('drop')) {
            $(this).parent().animate({ 'margin-top': '0px' }, '1000');
            $(this).find('p').slideUp();
        } else {
            $(this).parent().animate({ 'margin-top': '-114px' }, '1000');
            $(this).find('p').slideDown();
        }
        $(this).toggleClass('drop');
    });
    let bcounter = $(".musicControls").nextAll(),
        originalText = $(".buffering").text(),
        it = 0,
        buffer = setInterval(function () {
            $(".buffering").append(".");
            it++;
            if (it == 4) {
                $(".buffering").html(originalText);
                it = 0;
            }
        }, 1);
    bcounter = bcounter.length;

    $(".musicControls>*").each(function (i, elem) {
        if (!$(this).hasClass('ratio')) return;
        let which = $(this).closest('.musicControls').attr('class').split(" ")[1];
        if (which == 'player')
            which = 'mvol';
        else
            which = 'vsfx';
        if ($(this).data('volume') > eval(which)) {
            $(this).addClass('mute')
        } else {
            $(this).removeClass('mute');
        }
        if (!--bcounter)
            $('.buffering').fadeOut('fast', function () { $(".soundControllers").fadeIn(), clearInterval(buffer), buffer = null; });
    });
    $(".plus").on("click", function (e) {
        // Check if SFX
        var which = $(this).parent(),
            vol;
        if (which.hasClass('Sounds')) {
            which = efx;
            vol = which["click"].volume();
        } else {
            which = bg;
            vol = which["start"].volume();
        }
        vol += 0.2;
        if (vol > 1) {
            vol = 1;
        }
        vol = Number(vol).toFixed(1);
        $.each(which, function (index, chunk) {
            which[index].volume(vol);
        });
        if (which == efx)
            which = 'Sounds';
        else
            which = 'player';
        if (vol == 1.0)
            vol = 1;
        $(window).updateVolume(which, vol);
        $(".musicControls." + which).find("[data-volume='" + vol + "']").removeClass('mute');
    }),
        $(".minus").on("click", function (e) {
            // Check if SFX
            var which = $(this).parent(),
                vol;
            if (which.hasClass('Sounds')) {
                which = efx;
                vol = which["click"].volume();
            } else {
                which = bg;
                vol = which["start"].volume();
            }

            if (which == efx)
                which = 'Sounds';
            else
                which = 'player';

            vol = Number(vol).toFixed(1);
            if (vol == 1.0)
                vol = 1;
            $(".musicControls." + which).find("[data-volume='" + vol + "']").addClass('mute');
            vol -= 0.2;
            if (vol < 0) {
                vol = 0;
            }
            $(window).updateVolume(which, vol);
            if (which == 'Sounds')
                which = efx;
            else
                which = bg;
            $.each(which, function (index, chunk) {
                which[index].volume(vol);
            });
        }),
        $(".ratio").on("click", function () {
            var which = $(this).parent().attr('class').split(" ")[1],
                type, vol = $(this).data('volume');
            if (which == 'Sounds')
                type = efx;
            else
                type = bg;
            $.each(type, function (index) {
                type[index].volume(vol);
            });
            var look = $(this);
            $(".musicControls." + which + ">*").removeClass('mute');
            $(".musicControls." + which + ">*").each(function (i, elem) {
                if (!$(this).hasClass('ratio')) return;
                if (look.data('volume') < $(this).data('volume')) {
                    $(this).addClass('mute')
                }
            });
            $(window).updateVolume(which, vol);
        });
    $(".menu,.close-menu").live("click touchstart", function (t) {
        $('.settings').toggleClass('alts');
    });
    $(".settings").live("click touchstart", function (t) {
        if ($(t.target).parents('.container').length == 0 && !$(t.target).is('.container'))
            $('.settings').toggleClass('alts');
    });
});