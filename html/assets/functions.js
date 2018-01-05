var loadingImage = '<img src="/img/blue-focus.png" class="fa fa-spin" style="width: 20%; margin: 20% 40%; opacity: .5">';

var typewatch = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    }
})();

var ajax = (function () {
    var LocalAjax = function (o) {
        if (typeof o === 'object')
            $.extend(this, o, true);
    };
    LocalAjax.prototype = {
        base: '/api.php',
        url: '',

        get: function (data) {
            this.url = this.base;
            return this._call('GET', data);
        },
        post: function (data) {
            this.url = this.base;
            return this._call('POST', data);
        },
        update: function (data) {
            this.url = this.base;
            return this._call('UPDATE', data);
        },
        remove: function (data) {
            this.url = this.base;
            return this._call('DELETE', data);
        },
        loadRequests: function () {
            var _this = this;
            $('.ajax-request').each(function (key, value) {
                var container = $(this);
                var target = container.attr('data-ajax-target');
                var property = container.attr('data-ajax-property');
                if (container.attr('status') !== 'done' && isScrolledIntoView(container)) {
                    container.attr('status', 'queued');
                    container.html(loadingImage);
                    $.ajax({
                        url: container.attr('data-ajax-url'),
                        type: "GET",
                        dataType: 'json',
                        data: {ajaxRequest: true},
                        success: function (result) {
                            if (typeof target !== typeof undefined && target !== false) {
                                var css = {};
                                css[property] = 'url("' + result.data + '")';
                                // console.log(target);
                                // console.log(property);
                                // console.log(result.data);
                                $(target).css(css);
                                container.remove();
                            } else {
                                container.html(result.content.replace('{replace}', result.data))
                            }
                            container.attr('status', 'done');
                        }
                    })
                }
            });
        },

        _call: function (type, data) {
            console.log(data);
            data.ajaxRequest = true;
            return $.ajax({
                url: this.url,
                type: type,
                dataType: 'json',
                data: data
            });
        }
    };
    return new LocalAjax();
})();


var toast = function (message, type, timeout, to) {
    message = typeof message !== 'undefined' ? message : "Toasty!";
    type = typeof type !== 'undefined' ? type : "info";
    if (['info', 'success', 'danger', 'warning'].indexOf(type) < 0) {
        type = 'info';
    }
    to = typeof to !== 'undefined' ? to : (document.getElementById('messages') !== null ? '#messages' : 'body');
    var close = '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';

    // console.log([message, type, timeout, to]);
    var messageDiv = document.createElement("DIV");
    if (typeof timeout !== 'undefined') {
        close = '<div class="progress transition" style="height: 5px; bottom: -23px; left: 0; width: 0; position: absolute; background-color: rgba(0, 0, 0, 0.3)"></div>';
        $(messageDiv).html(message + '' + close);
        $(messageDiv).css({position: 'relative'});
        timeout = typeof timeout === 'number' ? timeout : 5000;
        timeout = timeout < 5000 ? 5000 : timeout;
        var progressDiv = $(messageDiv).children('.progress');
        var interval = 100;
        var progress = 0;
        var timespent = interval;
        var progressTimer = setInterval(function () {
            timespent += interval;
            progress = parseFloat((timespent / timeout) * 100);
            $(progressDiv).width(progress + '%');
            console.log(progress);
            if (progress === 100) {
                clearInterval(progressTimer);
                messageDiv.remove('slow');
            }
        }, interval);
    } else {
        $(messageDiv).html(message + '' + close);
    }
    $(messageDiv).addClass('paper text-center alert alert-' + type);

    $(to).prepend(messageDiv);
    console.log('added!');
};

function isScrolledIntoView(elem) {
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}