let canSendAjax = true;
$(document).ready(function() {
    $("#submit-login").click(function() {
        let elementParent = $(".container-options");
        elementParent.css({
            'cursor': "wait",
            "opacity": "0.5"
        });
        var username = $("#uname").val();
        var pass = $("#pass").val();
        var remember = '0';

        if ($('#remember').prop('checked')) {
            remember = '1';
        }
        if (username != "" && pass != "") {
            $(".error").css("display", "none");
            $.ajax({
                url: './core/login.php',
                type: 'POST',
                data: { username: username, pass: pass, remember: remember },
                success: function(result) {
                    if (result == 1) {
                        $(".error.false").css("display", "contents").delay(5000).fadeOut();

                        elementParent.css({
                            'cursor': "",
                            "opacity": ""
                        });
                    } else if (result == 0) {
                        $(".error.empty").css("display", "contents").delay(5000).fadeOut();

                        elementParent.css({
                            'cursor': "",
                            "opacity": ""
                        });
                    } else if (result == 'logged') {
                        setTimeout(() => {
                            refreshUCP();
                        }, 500);
                    }
                },
                error: function() {
                    elementParent.css({
                        'cursor': "",
                        "opacity": ""
                    });

                }
            });
        } else {
            if (username.length == 0) {
                $(".error.username").css("display", "contents").delay(5000).fadeOut();
            }
            if (pass.length == 0) {
                $(".error.password").css("display", "contents").delay(5000).fadeOut();
            }
            elementParent.css({
                'cursor': "",
                "opacity": ""
            });
        }
    });
    $(".skill-layout h6.alternative").click(function() {
        var key = jQuery(this).parent('.skill-layout').attr('data-key');
        $('div[data-key=' + key + ']').each(function(index) {
            var dad = $(this).children('.skill').attr('data-papa');

            if (typeof dad !== 'undefined') {
                $('div[data-key=' + dad + ']').css('display', 'none');
                $(this).css('display', 'inline-block');
            }
        });
    });
    $(".skill-layout h6.original").click(function() {
        var dad = $(this).parent('.skill-layout').children('.skill').attr('data-papa');
        $('div[data-key=' + dad + ']').css('display', 'inline-block');
        $(this).parent('.skill-layout').css('display', 'none');
    });
    $("#submit-register").click(function() {
        var username = $("#reguname").val();
        var confpass = $("#confpass").val();
        var pass = $("#regpass").val();
        var email = $("#regemail").val();
        var modal = $(".rModal");
        var span = $(".regClose")[0];


        if (username != "" && pass != "" && confpass != "" && email != "") {
            $(".error").css("display", "none");
            $.ajax({
                url: './core/register.php',
                type: 'POST',
                data: { username: username, pass: pass, confpass: confpass, email: email },
                success: function(result) {
                    if (result == 1) {
                        $(".error.match").css("display", "contents").delay(5000).fadeOut();
                    }
                    if (result == 2) {
                        $(".error.password").css("display", "contents").delay(5000).fadeOut();
                    }
                    if (result == 3) {
                        $(".error.username").css("display", "contents").delay(5000).fadeOut();
                    }
                    if (result == 4) {
                        $(".error.email").css("display", "contents").delay(5000).fadeOut();
                    }
                    if (result == 5 || result == 6 || result == 7 || result == 8) {
                        $(".error.false").css("display", "contents").delay(5000).fadeOut();
                    }
                    if (result == 'logged') {
                        refreshUCP();

                        setTimeout(function() { modal.css("display", "block"); }, 100);
                        setTimeout(function() {
                            span.onclick = function() {
                                modal.css("display", "none");
                            }
                        }, 150);


                    }
                }
            });
        } else {
            if (username.length == 0) {
                $(".error.username").css("display", "contents").delay(5000).fadeOut();
            }
            if (pass.length == 0) {
                $(".error.password").css("display", "contents").delay(5000).fadeOut();
            }
            if (confpass.length == 0) {
                $(".error.conf").css("display", "contents").delay(5000).fadeOut();
            }
            if (confpass != pass) {
                $(".error.match").css("display", "contents").delay(5000).fadeOut();
            }
            if (email.length == 0) {
                $(".error.email").css("display", "contents").delay(5000).fadeOut();
            }
        }
    });
    $(document).on("click", ".button.task", function(e) {
        $(".two").slideToggle("slow");
    });
    $(document).on("click", ".alternative", function(e) {
        $(".alt").slideToggle("slow");
    });
    $('.slider').bxSlider({ "auto": false, "touchEnabled": false, "adaptiveHeight": true, "pager": true, "speed": 500, "controls": true, "startSlide": 0, "randomStart": false, "slideMargin": 0, "autoControls": false });
    $('input').focus(function() {
        $(this).attr('placeholder', '');
        $('.message.' + $(this).attr('data-placeholder')).css('display', 'contents');
    }).blur(function() {
        $('.message.' + $(this).attr('data-placeholder')).css('display', 'none');
        $(this).attr('placeholder', $(this).attr('data-placeholder'))
    });
    $(document).on("click", "span.reg", function(e) {
        $(".logMe").text('Register');
        $(".login").fadeOut(500, function() {
            $("div.registration").fadeIn();
        });
    });
    $(document).on("click", "span.log", function(e) {
        $(".logMe").text('Login to aBlast');
        $("div.registration").fadeOut(500, function() {
            $("div.login").fadeIn();
        });
    });

    $('#myInput').keyup(function() {
        var tr = $('#characters>.character-layout');
        if ($(this).val().length >= 2) {
            //var inputdata = $.trim($("#trainername").val());

            var noElem = true;
            var val = $.trim(this.value).toLowerCase();

            el = tr.filter(function() {
                return $(this).find('h2').text().toLowerCase().match(val);
            });
            if (el.length >= 1) {
                noElem = false;
            }

            tr.not(el).fadeOut();
            el.fadeIn();

        } else {
            tr.fadeIn();

        }
    });
    $('#myAnime').keyup(function() {
        var tr = $('.anime-listing');
        if ($(this).val().length >= 2) {
            //var inputdata = $.trim($("#trainername").val());

            var noElem = true;
            var val = $.trim(this.value).toLowerCase();

            el = tr.filter(function() {
                return $(this).find('h2').text().toLowerCase().match(val);
            });
            if (el.length >= 1) {
                noElem = false;
            }

            tr.not(el).fadeOut();
            el.fadeIn();

        } else {
            tr.fadeIn();

        }
    });
    $('#myClan').keyup(function() {
        var tr = $('.clan-container');
        if ($(this).val().length >= 2) {
            //var inputdata = $.trim($("#trainername").val());

            var noElem = true;
            var val = $.trim(this.value).toLowerCase();

            el = tr.filter(function() {
                return $(this).find('h2').text().toLowerCase().match(val);
            });
            if (el.length >= 1) {
                noElem = false;
            }

            tr.not(el).parent().fadeOut();
            el.fadeIn();

        } else {
            tr.parent().fadeIn();

        }
    });

    $('img[data-enlargable]').addClass('img-enlargable').click(function() {
        var src = $(this).attr('src');
        var modal;

        function removeModal() {
            modal.remove();
            $('body').off('keyup.modal-close');
        }
        modal = $('<div>').css({
            background: 'RGBA(0,0,0,.5) url(' + src + ') no-repeat center',
            backgroundSize: 'contain',
            width: '100%',
            height: '100%',
            position: 'fixed',
            zIndex: '10000',
            top: '0',
            left: '0',
            cursor: 'zoom-out'
        }).click(function() {
            removeModal();
        }).appendTo('body');
        //handling ESC
        $('body').on('keyup.modal-close', function(e) {
            if (e.key === 'Escape') { removeModal(); }
        });
    });

});

function refreshUCP() {
    $.ajax({
        url: './core/ajax.php',
        type: 'POST',
        data: { f: 'getUCP' },
        success: function(result) {
            if (result != false) {
                $(".first").html(result);
            }
        }
    });
}


function popup_page(setfilename, setwindowtitle, setwidth, setheight) {
    window.open(setfilename, '', 'border=0,toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=' + setwidth + ',height=' + setheight + '');
}