/**
 * @author Alexander Manzyuk <admsev@gmail.com>
 * Copyright (c) 2012 Alexander Manzyuk - released under MIT License
 * https://github.com/admsev/jquery-play-sound
 * Usage: $.playSound('http://example.org/sound.mp3');
 **/

(function($) {

    $.extend({
        playSound: function() {
            if ($('#' + arguments[0]).length) {
                document.getElementById(arguments[0]).currentTime = 0;
                document.getElementById(arguments[0]).play();
            } else
                return $("<audio id='" + arguments[0] + "' autoplay='autoplay' style='display:none;' controls='controls'>\n\
    <source src='./1.0/tpl/default/sound/" + arguments[0] + ".mp3' />\n\
< /audio").appendTo('body');
        }
    });

})(jQuery);
