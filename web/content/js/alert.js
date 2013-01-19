$(document).ready(function() {
    var popup = $("<div id='info-popup'><h4>info</h4><img src='content/image/close_button.png' /></div>");
    var bg = $('<div id="info-popup-bg"></div>');
    var isActive = false;

    $(".info").click(function(e) {

        createAlert(e);

        //Bind events
        $(document).bind('keydown', removeAlert);
        $(window).bind('resize', resizeBg);
        $(bg).bind('click', removeAlert);
        $(popup).find('img').bind('click', removeAlert);

        return false;
    });

    var createAlert = function(e) {
        $('body').append(bg);
        $(bg).fadeTo('fast', 0.3, function(){
            $('body').append(popup);
            var left = $(window).width() / 2 - ($(popup).width() / 2);
            var top = $(window).height() / 2;
            $(popup).css('left', left);
            $(popup).css('top', top - 100);
            setContent($(e.target).data('info'));
        });
        $(bg).css("height", $(document).height());
        $(bg).css("width", $(document).width());
    };

    var removeAlert = function(e) {
        if(e.keyCode !== 27 && e.keyCode !== undefined) return;
        $(bg).unbind('click', removeAlert);
        $(document).unbind('keydown', removeAlert);
        $(document).unbind('keydown', removeAlert);

        $(bg).fadeOut('fast', function() {
            $(bg).remove();
            $(popup).remove();
            $(popup).find('p').empty().remove();
        });
    };

    var resizeBg = function(e) {
        $(bg).css("height", $(document).height());
        $(bg).css("width", $(document).width());
        var left = $(window).width() / 2 - ($(popup).width() / 2);
        $(popup).css('left', left);
    };

    var setContent = function (alertText) {
        var content = $('<p></p>').html(alertText);
        $(popup).append(content);
    };
});