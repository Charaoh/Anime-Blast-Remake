jQuery.fn.extend({
    live: function (t, a) {
        return this.selector && jQuery(document).on(t, this.selector, a), this
    }
}), $(function () {
	
    preLoad("Loading Character Selection . . ."),
	
        // Attach the event keypress to exclude the F5 refresh
        $(window).bind("load", function () {
    		let t = document.getElementById("selection").offsetWidth,
				a = document.getElementById("selection").offsetHeight;
    		window.resizeTo(t + 16, a + 68),
            $("a").live("click", function (t) {
                t.preventDefault();
                return false;
            });
            appendTeam(), stats = $('.stats'), quicky = $('.teams'), count2 = 1, count = 1,
                $(".character-page").delegate(".skill-list>img, .passive", "click", function (t) {
                    if (!$(this).is(":visible")) return !1;
                    if (!canSendAjax)
                        return;
                    canSendAjax = false;
                    "skill" != $(t.target).attr("class").split(" ")[0] ? cleanInfo() : getSkill($(t.target))
                });
            $(".statistics").hover(function () {
                if (stats.hasClass('stay'))
                    return false;
                stats.removeClass('stay');
                if (stats.hasClass('active')) {
                    $('.statistics>.overlay').css('display', 'none');
                    stats.stop().animate({
                        left: '321px'
                    }, 1000).removeClass('active');
                } else {
                    $('.statistics>.overlay').css('display', 'block');
                    stats.stop().animate({
                        left: '126px'
                    }, 1000).addClass('active');
                }
            }).click(function () {
                count += 1;
                if (count == 2) {
                    $('.statistics>.overlay').css('display', 'none');
                    stats.removeClass('stay');
                    stats.stop().animate({
                        left: '321px'
                    }, 1000);
                    count = 0;
                } else {
                    $('.statistics>.overlay').css('display', 'block');
                    stats.addClass('stay');
                    stats.stop().animate({
                        left: '126px'
                    }, 1000).addClass('active');
                }
            }), $(".quicky").hover(function () {
                if (quicky.hasClass('stay'))
                    return false;
                quicky.removeClass('stay');
                if (quicky.hasClass('active')) {
                    $('.quicky span').removeClass('shadow');
                    $('.current_equiped').stop().animate({ "margin-top": '0px' }, 100)
                    quicky.stop().animate({
                        right: '381px'
                    }, 1000).removeClass('active');
                } else {
                    $('.quicky span').addClass('shadow');
                    quicky.stop().animate({
                        right: '144px'
                    }, 1000, function () {
                        $('.current_equiped').stop().animate({ "margin-top": '-20px' }, 500);
                    }).addClass('active');

                }
            }).click(function () {
                count2 += 1;
                if (count2 == 2) {
                    $('.quicky span').removeClass('shadow');
                    quicky.removeClass('stay');
                    $('.current_equiped').stop().animate({ "margin-top": '0px' }, 100);
                    quicky.stop().animate({
                        right: '381px'
                    }, 1000);
                    count2 = 0;
                } else {
                    $('.quicky span').addClass('shadow');
                    quicky.addClass('stay');
                    quicky.stop().animate({
                        right: '144px'
                    }, 1000, function () {
                        $('.current_equiped').stop().animate({ "margin-top": '-20px' }, 500);
                    }).addClass('active');
                }
            }),
                // On click, check the level.
                originalText = $(".buffering").text(), it = 0,
                buffer = setInterval(function () {
                    $(".buffering").append(".");
                    it++;
                    if (it == 4) {
                        $(".buffering").html(originalText); it = 0;
                    }
                }, 500);
            // Setup our new audio player class and pass it the playlist.
            player = new Player(sfx),
                player.play(),
                click = new Howl({
                    src: ['./tpl/beta/sound/click.mp3'],
                    preload: true,
                    volume: vsfx
                }), slide = new Howl({
                    src: ['./tpl/beta/sound/slide.mp3'],
                    preload: true,
                    volume: vsfx
                });
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
            });
            $(".plus").on("click", function (e) {
                // Check if SFX
                var which = $(this).parent();
                if (which.hasClass('Sounds'))
                    which = click;
                else
                    which = player;
                var vol = which.volume();
                vol += 0.2;
                if (vol > 1) {
                    vol = 1;
                }
                which.volume(vol);
                if (which == click) {
                    slide.volume(vol);
                }
                if (vol !== 1)
                    vol = which.volume().toFixed(1);
                if (which == click)
                    which = 'Sounds';
                else
                    which = 'player';
                $(".musicControls." + which).find("[data-volume='" + vol + "']").removeClass('mute');
                updateVol(which, vol);
            }),
                $(".minus").on("click", function (e) {
                    // Check if SFX
                    var which = $(this).parent();
                    if (which.hasClass('Sounds')) {
                        which = click;
                    } else
                        which = player;
                    var vol = which.volume().toFixed(1);
                    if (vol == 1.0)
                        vol = 1;

                    if (which == click)
                        which = 'Sounds';
                    else
                        which = 'player';
                    $(".musicControls." + which).find("[data-volume='" + vol + "']").addClass('mute');
                    vol -= 0.2;
                    if (vol < 0) {
                        vol = 0;
                    }
                    if (which == 'Sounds') {
                        slide.volume(vol);
                        click.volume(vol);
                    } else {
                        player.volume(vol);
                    }
                    updateVol(which, vol);
                }),
                $(".ratio").on("click", function () {
                    var which = $(this).parent();
                    if (which.hasClass('Sounds'))
                        which = click;
                    else
                        which = player;
                    which.volume($(this).data('volume'));
                    if (which == click) {
                        slide.volume($(this).data('volume'));
                        which = 'Sounds';
                    } else {
                        which = 'player';
                    }
                    var look = $(this);
                    $(".musicControls." + which + ">*").removeClass('mute');
                    $(".musicControls." + which + ">*").each(function (i, elem) {
                        if (!$(this).hasClass('ratio')) return;
                        if (look.data('volume') < $(this).data('volume')) {
                            $(this).addClass('mute')
                        }
                    });
                    updateVol(which, look.data('volume'));
                }),
                $(".nextTrack").on("click", function () {
                    player.skip('next');
                }),
                $(".prevTrack").on("click", function () {
                    player.skip('prev');
                }),
                $(".item").on("click", function (e) {
                    var item = $(this).attr('class').split(/\s+/)[1];
                    $('#middle>*').fadeOut();
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                        $('.' + item + '-2').fadeOut();
                        return false;
                    } else {
                        $('.active').removeClass('active');
                        $(this).addClass('active');
                        $('.' + item + '-2').fadeIn();
                    }
                }),
                $b1 = $('.oimage.one'),
                $b2 = $('.oimage.two'),
                $b3 = $('.oimage.three');

            $(".modeText").hover(function () {
                $(this).fadeOut("fast", function () {
                    $(this).parent().find(".levels").css("display", "block");
                });
            }, function () { console.log("moouseout"); });
            //Every five seconds, run the code within the handler
            setInterval(function () {
                $b1.fadeOut('slow', function () {
                    var $el = $(this),
                        el_source = $el.attr('src'),
                        found = false;
                    do {
                        el_source = './tpl/beta/css/images/b-' + (Math.floor(Math.random() * 9) + 1) + '.png?99999999999999999999999';
                        if (el_source !== $el.attr('src') && el_source !== $b2.attr('src') && el_source !== $b3.attr('src'))
                            found = true;
                    } while (found === false);
                    $b1 = $el;
                    $el.attr('src', el_source).delay(1).fadeIn('slow');
                });
            }, 10000);
            setInterval(function () {
                $b2.fadeOut('slow', function () {
                    var $el = $(this),
                        el_source = $el.attr('src'),
                        found2 = false;
                    do {
                        el_source = './tpl/beta/css/images/b-' + (Math.floor(Math.random() * 9) + 1) + '.png?99999999999999999999999';
                        if (el_source !== $el.attr('src') && el_source !== $b1.attr('src') && el_source !== $b3.attr('src'))
                            found2 = true;
                    } while (found2 === false);
                    $b2 = $el;
                    $el.attr('src', el_source).delay(1).fadeIn('slow');
                });
            }, 10000);
            setInterval(function () {
                $b3.fadeOut('slow', function () {
                    var $el = $(this),
                        el_source = $el.attr('src'),
                        found3 = false;
                    do {
                        el_source = './tpl/beta/css/images/b-' + (Math.floor(Math.random() * 9) + 1) + '.png?99999999999999999999999';
                        if (el_source !== $el.attr('src') && el_source !== $b1.attr('src') && el_source !== $b2.attr('src'))
                            found3 = true;
                    } while (found3 === false);
                    $b3 = $el;
                    $el.attr('src', el_source).delay(1).fadeIn('slow');
                });
            }, 10000);


            $(".close").live("click", function (t) {
                t.preventDefault();
                $('.filters').fadeOut('fast', function () {
                    $('.character_list').fadeOut('fast', function () {
                        $(this).css("width", '90%').fadeIn();
                    });
                });
                return false;
            });
            $(".open").live("click", function (t) {
                t.preventDefault();
                slide.play();
                $('.filters').fadeIn('fast', function () {
                    $('.character_list').fadeOut('fast', function () {
                        $(this).css("width", '70%').fadeIn();
                    });
                });
                return false;
            });
            var criteria = [], classes = [];;
            $('.filter').click(function (e) {
                criteria = [];

                $(this).toggleClass('selecting');
                // Initialize criteria string

                // Set value for all selector
                var showAll = true;

                // Iterate over all criteriaSelectors
                $('.filter').each(function () {
                    // Append selector to criteria
                    if ($(this).hasClass('selecting')) {
                        criteria.push($(this).attr('id'));
                        showAll = false;
                    }
                });
                // Check if results are limited somehow
                if (showAll) {
                    // No criterias were set so show all
                    $('.character_list div').fadeOut();
                    $('.character_list div').each(function () {
                        var item = $(this),
                            itemclass = $(this).attr('class').split(" "), hasClass = false;
                        if (classes.length !== 0) {
                            jQuery.each(itemclass, function (index, elem) {
                                if (classes.indexOf(elem) > -1) {
                                    hasClass = true;
                                }
                            });
                        } else {
                            hasClass = true;
                        }
                        if (hasClass)
                            item.fadeIn();
                        else
                            item.fadeOut();
                    });
                } else {
                    // Hide all items
                    // Show the ones that were selected
                    $('.character_list div').fadeOut();
                    $('.character_list div').each(function () {
                        var item = $(this),
                            itemclass = $(this).attr('class').split(" "), hasClass = false;
                        if (criteria.indexOf(itemclass[0]) == -1) {
                            return;
                        }
                        if (classes.length !== 0) {
                            jQuery.each(itemclass, function (index, elem) {
                                if (classes.indexOf(elem) > -1) {
                                    hasClass = true;
                                }
                            });
                            if (hasClass)
                                item.fadeIn();
                        } else {
                            item.fadeIn();
                        }
                    });
                }

            });
            $('.class').click(function (e) {
                classes = [];
                $(this).toggleClass('selecting');
                // Initialize criteria string

                // Set value for all selector
                var showAll = true;

                // Iterate over all criteriaSelectors
                $('.class').each(function () {
                    // Append selector to criteria
                    if ($(this).hasClass('selecting')) {
                        classes.push($(this).attr('id'));
                        showAll = false;
                    }
                });
                // Check if results are limited somehow
                if (showAll) {
                    // No criterias were set so show all
                    $('.character_list div').fadeOut();
                    $('.character_list div').each(function () {
                        var item = $(this),
                            itemclass = $(this).attr('class').split(" "), hasClass = false;
                        if (criteria.length !== 0 && criteria.indexOf(itemclass[0]) == -1) {
                            item.fadeOut();
                            return;
                        }
                        item.fadeIn();
                    });
                } else {

                    // Show the ones that were selected
                    $('.character_list div').fadeOut();
                    $('.character_list div').each(function () {
                        var item = $(this),
                            itemclass = $(this).attr('class').split(" "), hasClass = false;
                        if (criteria.length !== 0 && criteria.indexOf(itemclass[0]) == -1) {
                            item.fadeOut();
                            return;
                        }
                        jQuery.each(itemclass, function (index, elem) {
                            if (classes.indexOf(elem) > -1) {
                                hasClass = true;
                            }
                        });
                        if (hasClass)
                            item.fadeIn();
                        else
                            item.fadeOut();
                    });
                }

            });
            $('.effects-list>p').click(function (e) {
                var me = $(this).attr('id'), effects = [];
                $(this).toggleClass('selecting');
                $('.effects-list>p.selecting').each(function () {
                    effects.push($(this).attr('id'));
                });
                if ($(this).hasClass('selecting')) {
                    $('.character_list div').each(function () {
                        if ($(this).is(":hidden"))
                            return;
                        if (!$(this).hasClass(me))
                            $(this).fadeOut();
                    });
                } else {
                    $('.character_list div').fadeOut();
                    $('.character_list div').each(function () {
                        var item = $(this),
                            itemclass = $(this).attr('class').split(" "), hasClass = false;
                        if (criteria.length !== 0 && criteria.indexOf(itemclass[0]) == -1) {
                            item.fadeOut();
                            return;
                        }
                        if (classes.length !== 0) {
                            jQuery.each(itemclass, function (index, elem) {
                                if (classes.indexOf(elem) > -1) {
                                    hasClass = true;
                                }
                            });
                            if (hasClass)
                                item.fadeIn();
                        } else if (effects.length !== 0) {
                            jQuery.each(effects, function (index, elem) {
                                if (item.hasClass(elem))
                                    item.fadeIn();
                            });
                        } else
                            item.fadeIn();
                    });
                }
            });
            $(".goback").live("click", function (t) {
                t.preventDefault(),
                    clearInterval(match), match = null, clearInterval(tiping), tiping = null;
                $(this).hasClass("message") ? ($("#popup").remove(), popUp("search-private")) : $(this).hasClass("cancel") ? cancelMatch() : $("#popup").fadeOut("slow", function () {
                    $("#popup").remove()
                    canSendAjax = true;
                })
            }), $('input[name="pbsubmit"]').live("click", function (t) {
                t.preventDefault(),
                    popUp("submit")
            });
            var e = 0,
                i = null, down = { '37': null, '39': null };
            $(document).keyup(function (event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                down[keycode] = null;
            });
            $(document).keydown(function (e) {
                if (e.which == 37) {
                    if (down['37'] !== null) return false
                    down['37'] = true;
                    if (!$('.character_list').is(":visible")) return false;
                    if ($('.character-page').is(":visible")) {
                        var check = $('.shineme').parent(), length = $('.character_list>div').length;
                        do {
                            if (check.index() > 0)
                                check = check.prev();
                            else
                                check = check.parent().children(':last-child');
                        } while (check.find('img').length == 0)
                        getCharacter(check.find('img'))
                        return false;
                    }
                    if ($('.filters').is(":visible")) return false;
                    slide.play();
                    $('.filters').fadeIn('fast', function () {
                        $('.character_list').fadeOut('fast', function () {
                            $(this).css("width", '70%').fadeIn();
                        });
                    });
                    return false;
                } else if (e.which == 39) {
                    if (down['39'] !== null) return false
                    down['39'] = true;
                    if (!$('.character_list').is(":visible")) return false;
                    if ($('.character-page').is(":visible")) {
                        var check = $('.shineme').parent(), length = $('.character_list>div').length;
                        do {
                            if (check.index() + 1 < length)
                                check = check.next();
                            else
                                check = check.parent().children(':first-child');
                        } while (check.find('img').length == 0)
                        getCharacter(check.find('img'))
                        return false;
                    }
                    if (!$('.filters').is(":visible")) return false;
                    click.play();
                    $('.filters').fadeOut('fast', function () {
                        $('.character_list').fadeOut('fast', function () {
                            $(this).css("width", '90%').fadeIn();
                        });
                    });
                }
            });
            $(".close-character").live("click", function () {
                if ($(this).parent().hasClass('buy-out')) {
                    $('.buy-out').fadeOut();
                    clearInterval(slider);
                    return false;
                }
                if (e !== 0) return;
                cleanInfo();
                e = 0;
            });
            $(".character").live("click touchstart", function (t) {
                if (!canSendAjax)
                    return;
                canSendAjax = false;
                var a = $(this);
                clearTimeout(i);
                1 === ++e ? i = setTimeout(function () {
                    getCharacter(a),
                        e = 0
                }, 700) : (clearTimeout(i), e = 0)
            }).on("dblclick", function (t) {
                if (t.preventDefault(), $(this).parent().hasClass("slot"))
                    $('input[name="' + sID($(this).parent()) + '"]').val(""),
                        $(this).appendTo($(this).data("originalParent")),
                        $(this).data("prevParent", $(this).data("originalParent")),
                        appendStatus(sTotal()),
                        updateTeam();
                else {
                    var a = $(this),
                        e = ["1", "2", "3"],
                        i = !1;
                    $(".slot").each(function () {
                        1 != i && -1 !== e.indexOf($(this).html()) &&
                            ($('input[name="' + sID($(this)) + '"]').val(a.attr("id")),
                                $(this).append(a),
                                a.data("prevParent", $(this)),
                                appendStatus(sTotal()), updateTeam(), i = !0)
                    })
                }
            }),
                $(".character_list>div>img").draggable({
                    start: function (e, ui) {
                        $(this).css({
                            display: "none"
                        });
                    },
                    stop: function () {
                        $(this).css({
                            display: "block"
                        })
                    },
                    revertDuration: 0,
                    revert: function (t) {
                        if (t === false && !$(this).parent().hasClass('slot')) return t;
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
                }).each(function () {
                    $(this).data("originalParent", $(this).parent())
                }),
                $("#droppable_slots>div").droppable({
                    hoverClass: "over",
                    drop: function (t, a) {
                        var e = $(this),
                            i = sID(e);
                        a.draggable.data("prevParent") &&
                            a.draggable.data("prevParent") != a.draggable.data("originalParent")
                            && "slot" == a.draggable.data("prevParent").attr("class").split(" ")[0]
                            && $('input[name="' + sID(a.draggable.data("prevParent")) + '"]').val("");
                        e.find(".ui-draggable").length && e.find(".ui-draggable").appendTo(e.find(".ui-draggable").data("originalParent")),
                            e.append(a.draggable),
                            $('input[name="' + i + '"]').val(a.draggable.attr("id")),
                            a.draggable.data("prevParent", $(this)),
                            appendStatus(sTotal()),
                            updateTeam()
                    }
                });
            $(".character-options").delegate(".alternatives, .transformations", "click", function (t) {
                var trans = false;
                "transformations" == $(t.target).attr("class") ? trans = true : trans = false;
                if (trans) {
                    $('.character-avatar>img:visible').fadeOut('fast', function () {
                        var which = ($(this).attr("class").split(" ")[1] == undefined) ? 1 : parseInt($(this).attr("class").split(" ")[1]) + 1;
                        console.log(which);
                        if ($('.transformation.' + which).length)
                            $('.transformation.' + which).fadeIn();
                        else
                            $('.original,.default').fadeIn();
                    });
                    return false;
                }
                if ($('.skill-list.alts').is(":visible")) {
                    $('.skill-list:visible').fadeOut("fast", function () { $('div[class="skill-list"').fadeIn("fast"); });
                    $('.alternatives').html("Alternative Skills");
                    return false;
                }
                $(".skill-list:visible").fadeOut("fast", function () {
                    $(".skill-list.alts").fadeIn();
                    $(".alternatives").html("Return");
                });
            });
            $(".save").live("click", function (t) {
                t.preventDefault();

                swal({
                    title: 'Save this team',
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Name your team",
                            type: "input",
                        },
                    },
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            value: false,
                            visible: true,
                            className: "",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Save",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: false
                        }
                    },
                })
                    .then((value) => {
                        if (value.length == 0) throw 'no-name';
                        else if (value == false) throw null;
                        saveTeam(value);
                    }).catch(err => {
                        if (err == 'no-name')
                            swal("Empty Team Name", "Please provide a name for your team", "error");
                        else {
                            swal.stopLoading();
                            swal.close();
                        }
                    });
                $('.swal-content').prepend($('.current_equiped>*').not('.save,.shuffle').clone());
                return false;
            });
            $(".shuffle").live("click", function (t) {
                var r = false, characters = $(".character_list>div>img").clone();
                console.log(characters);
                $('.slot').each(function (e, t) {
                    if ($(t).find('img').length) return;
                    var chosen = false;
                    do {
                        var random = Math.floor(Math.random() * characters.length);
                        if (!$(characters[random]).parent().find('p.locked').length)
                            chosen = random;
                    } while (chosen === false);
                    elem = null;
                    v = $(characters[chosen]).attr('id');
                    elem = $('.character_list>div').find('img[id=' + v + ']');
                    elem.appendTo($('.slot.' + (e + 1))),
                        elem.data("prevParent", $('.slot.' + (e + 1)));
                    $('.current_equiped').append(elem.clone());
                    $('input[name="s' + e + '"]').val(elem.attr("id"));
                    characters.splice(chosen, 1);
                    r = true;
                });
                if (r) {
                    appendStatus(sTotal());
                    updateTeam();
                }
                return false;
            });
            $("p#team").live("click", function (t) {
                t.preventDefault();
                var ClassList = $(this).attr('class').split(' ');
                if (ClassList.indexOf('selected') > -1) return false;
                selectTeam(ClassList[0], $(this))
                return false;
            });
            $("p#team>span.delete").live("click", function (t) {
                t.preventDefault();
                var ClassList = $(this).parent().attr('class').split(' ');
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this team data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            deleteTeam(ClassList[0]);
                            $(this).parent().remove();
                        } else {
                            swal.stopLoading();
                            swal.close();
                        }
                    });

                return false;
            });

            $(".character,.skill,.class,.filter,.close-menu,.eventItem,.btnOpen,.close-inventory,.buy-out,.effects-list>p,.close-character,.close,.save,.shuffle,.statistics>img,.quicky>span").live("click", function (t) {
                click.play();
            });

            var slider = null;
            $(".i").live("click", function (t) {
                var check = $(this).parent().attr('id'), item = $(this).attr('id');
                $('.buy-out').fadeOut("fast");
                clearInterval(slider);
                if (check == 'characters') {
                    var slanted = $(this).find('div').children('.alts').clone();
                    $('.buy-out').attr('id', item);
                    $('.buy-out').html('<div class="close-character">Close</div><span></span>');
                    slanted.each(function (i, v) {
                        $('.buy-out').append($(this).removeClass('alts'));
                    });
                    $('.buy-out>img').fadeOut();
                    $('.buy-out>img:first').fadeIn("fast");
                    if (slanted.length > 1) {
                        var next = $('.buy-out>img:first');
                        slider = setInterval(function () {
                            next.fadeOut().removeClass('current');
                            next = next.next();
                            if (!next.parent().size() || next.is('p'))
                                next = $('.buy-out>img').eq(0);
                            next.fadeIn("fast").addClass('current');
                        }, 2000);
                    }
                    $('.buy-out').append('<p>BUY</p>');
                    $('.buy-out').fadeIn();
                } else if (check == 'sfx') {
                    swal({
                        title: 'Are your sure you want to buy this sfx pack?',
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                value: false,
                                visible: true,
                                className: "",
                                closeModal: true,
                            },
                            confirm: {
                                text: "Buy",
                                value: true,
                                visible: true,
                                className: "",
                                closeModal: false
                            }
                        }
                    }).then((value) => {
                        if (value == false) throw null;
                        if (!canSendAjax)
                            return;
                        canSendAjax = false;
                        buyThis($(this).attr('id'));
                    }).catch(err => {
                        swal.stopLoading();
                        swal.close();
                    });
                }
            });
   			 $(".eventItem").live("click touchstart", function (t) {
                let item = $(this).attr('id');
                swal({
                        title: 'Are your sure you want to buy this box?',
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                value: false,
                                visible: true,
                                className: "",
                                closeModal: true,
                            },
                            confirm: {
                                text: "Buy",
                                value: true,
                                visible: true,
                                className: "",
                                closeModal: false
                            }
                        }
                    }).then((value) => {
                        if (value == false) throw null;
                        if (!canSendAjax)
                            return;
                        canSendAjax = false;
                        buyBox(item);
                    }).catch(err => {
                        swal.stopLoading();
                        swal.close();
                    });
                
            });
    		$(".btnOpen").live("click touchstart", function (t) {
                let item = $(this).parent().attr('id');
                swal({
                        title: $(this).parent().attr('title'),
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                value: false,
                                visible: true,
                                className: "",
                                closeModal: true,
                            },
                            confirm: {
                                text: "Open",
                                value: true,
                                visible: true,
                                className: "",
                                closeModal: false
                            }
                        }
                    }).then((value) => {
                        if (value == false) throw null;
                        if (!canSendAjax)
                            return;
                        canSendAjax = false;
                        openBox(item);
                    }).catch(err => {
                        swal.stopLoading();
                        swal.close();
                    });
                
            });

            $(".buy-out>p").live("click", function (t) {
                if (!canSendAjax)
                    return;
                canSendAjax = false;
                var papa = $(this).parent().attr('id');
                buyThis(papa);
            });
            $(".menu,.close-menu").live("click touchstart", function (t) {
                $('.settings').toggleClass('alts');
            });
    
    		 $(".close-image").live("click touchstart", function (t) {
                $('.christmasImage').toggleClass('alts');
            });
    
    		$(".inventory,.close-inventory").live("click touchstart", function (t) {
           		if($('.inventory').find('img').hasClass('new')){
                	$('.inventory').find('img').removeClass('new');
                	$('.inventory').find('img').attr("src", "./tpl/christmas/css/images/Inventory.png");
                }
                $('.inventoryContainer').toggleClass('alts');
            });
            $(".settings").live("click touchstart", function (t) {
                if ($(t.target).parents('.container').length == 0 && !$(t.target).is('.container'))
                    $('.settings').toggleClass('alts');
            });
            var fading = [0, 0];
            $(".select").click(function () {
                var what = $(this).attr('class').split(' ')[1], k = 0;
                if (what == 'sfx')
                    k = 1
                if (fading[k] == 1) {
                    $(this).attr("size", '0');
                    $(this).next().fadeIn();
                    if ($(this).attr('value') !== '0') {
                        swal({
                            title: 'Are your sure you want to change this ' + what + ' setting?',
                            buttons: {
                                cancel: {
                                    text: "Cancel",
                                    value: false,
                                    visible: true,
                                    className: "",
                                    closeModal: true,
                                },
                                confirm: {
                                    text: "Save",
                                    value: true,
                                    visible: true,
                                    className: "",
                                    closeModal: false
                                }
                            }
                        }).then((value) => {
                            if (value == false) throw null;
                            if (!canSendAjax)
                                return;
                            canSendAjax = false;
                            change(what, $(this).attr('value'));
                        }).catch(err => {
                            $(this).find('option').removeAttr('selected').filter('[value="0"]').attr('selected', true)
                            swal.stopLoading();
                            swal.close();
                        });
                    }
                    fading[k] = 0;
                    return false;
                }
                var n = $(this).find("option").length;
                $(this).next().fadeOut("fast");
                $(this).attr("size", n);

                fading[k] = 1;
            });
            $(".input").change(function () {
                var what = $(this).attr('class').split(' ')[1];
                swal({
                    title: 'Are your sure you want to change your ' + what + ' bg?',
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            value: false,
                            visible: true,
                            className: "",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Save",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: false
                        }
                    }
                }).then((value) => {
                    if (value == false) throw null;
                    if (!canSendAjax)
                        return;
                    canSendAjax = false;
                    change(what, $(this).attr('value'));
                }).catch(err => {
                    swal.stopLoading();
                    swal.close();
                });
            });
            $(".categorybtn").live("click touchstart", function (t) {
                let me = $(this).parent().data('rel');
                $(".sale").fadeOut("fast", function () {
                    if (me == 'characters')
                        $('.sales').css('width', '95%');
                    $('#' + me).fadeIn();
                    $('.backbtn').fadeIn();
                });
            });
            $(".backbtn").live("click touchstart", function (t) {
                $('.buy-out').fadeOut();
                $('.backbtn').fadeOut("fast", function () {
                    $(".sales>*:visible").fadeOut("fast");
                    $('.sales').removeAttr('style');
                    $(".sale").delay(500).fadeIn();
                });
            });
        });
});