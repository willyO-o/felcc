
; (function ($) {

    var defaults = {
        'url': null,
        'autoload': true,
        'data': {
            'page': 1,
            'size': 10,
        },
        'before': function () {
            $(this.loading).css("height", "100px").fadeIn();
        },
        'after': function (elementsLoaded) {
            if (elementsLoaded.length > 0) {
                $(this.loading).fadeOut();
                $(elementsLoaded).fadeInWithDelay();
            }
        },
        'heightOffset': 20,
        'loading': '#loading',
        'loadingText': 'Cargando...',
        'loadingNomoreText': 'Sin Resultados.',
        'manuallyText': 'click para cargar Manual.',
        'dataTemplateCallback': function (data) {
            var html = "<li style='opacity:0;-moz-opacity: 0;filter: alpha(opacity=0);'><p>" + data + "</p></li>";
            return html;
        },
        'elementCountTemplate': 'Listando <b>{count} </b> elementos de <b>{total} </b> encontrados',
        'elementCountSelector': null,
    };

    var updateElementCountMessage = function (opts, data) {
        if (opts.elementCountSelector && $(opts.elementCountSelector).length) {
            var count = opts.data.page * opts.data.size;
            count = count > data.total ? data.total : count;
            var message = opts.elementCountTemplate.replace('{count}', count)
                .replace('{total}', data.total);
            $(opts.elementCountSelector).html(message).show();
        }
    };

    var resetElementCountMessage = function (opts) {
        if (opts.elementCountSelector && $(opts.elementCountSelector).length) {
            $(opts.elementCountSelector).hide(); // Oculta el contenedor al resetear
        }
    };


    $.fn.resetScrollPagination = function (newData) {
        var opts = $(this).data('scrollPaginationOpts');
        opts.data = newData;
        opts.data.page = 1;
        $(this).data('scrollPaginationOpts', opts);
        $(this).html('');
        $(this).attr('scrollPagination', 'enabled');
        resetElementCountMessage(opts);
        $.fn.scrollPagination.loadContent(this);
    };

    $.fn.scrollPagination = function (options) {
        return this.each(function () {
            var opts = $.extend({}, defaults, options);
            $(this).data('scrollPaginationOpts', opts);
            $.fn.scrollPagination.init($(this));
        });
    };

    $.fn.stopScrollPagination = function (obj = null) {
        if (obj == null) {
            return this.each(function () {
                $(this).attr('scrollPagination', 'disabled');
            });
        } else {
            var opts = $(obj).data('scrollPaginationOpts');
            $(opts.loading).html(opts.loadingNomoreText).fadeIn();
            $(obj).attr('scrollPagination', 'disabled');
        }
    };

    $.fn.scrollPagination.loadContent = function (obj) {
        var opts = $(obj).data('scrollPaginationOpts');
        var target = opts.scroller;
        $(opts.loading).html(opts.loadingText);

        if (opts.before != null) {
            opts.before();
        }
        $(obj).children().attr('rel', 'loaded');

        var ajaxRequest = {
            type: opts.method === 'post' ? 'post' : 'get',
            url: opts.url,
            data: opts.data,
        };

        if ($(obj).data('requestRunning')) {
            return;
        }
        $(obj).data('requestRunning', true);

        $.ajax(ajaxRequest)
            .done(function (response) {
                var data = response.datos; // Asegurarse de usar la estructura correcta del JSON
                var html = "";
                if (data != null && data.length > 0) {
                    $.each(data, function (index, value) {
                        html += opts.dataTemplateCallback(value);
                    });
                    $(obj).append(html);
                    updateElementCountMessage(opts, response); // Usar response para el contador
                    if (data.length < opts.data.size) {
                        $.fn.stopScrollPagination(obj);
                    } else {
                        opts.data.page++;
                    }
                } else {
                    $.fn.stopScrollPagination(obj);
                }
                var objectsRendered = $(obj).children('[rel!=loaded]');
                if (opts.after != null) {
                    opts.after(objectsRendered);
                }
                $(obj).data('requestRunning', false)
            })
            .fail(function () {
                $(obj).data('requestRunning', false)
                console.error('Error fetching data.');
            });
    };

    $.fn.scrollPagination.init = function (obj) {
        var opts = $(obj).data('scrollPaginationOpts');
        var target = opts.scroller || obj;
        $(obj).attr('scrollPagination', 'enabled');
        var lastScrollLeft = $(target).scrollLeft();

        if ($(obj).children().length === 0) {
            $.fn.scrollPagination.loadContent(obj);
        }

        if (opts.autoload === true) {
            $(target).scroll(function (event) {
                var newScrollLeft = $(target).scrollLeft();
                if (lastScrollLeft !== newScrollLeft) {
                    lastScrollLeft = newScrollLeft;
                    return;
                }

                if ($(obj).attr('scrollPagination') == 'enabled') {
                    var mayLoadContent = $(target).scrollTop() + $(target).innerHeight() + opts.heightOffset >= $(target)[0].scrollHeight;
                    if (mayLoadContent) {
                        $.fn.scrollPagination.loadContent(obj);
                    }
                } else {
                    event.stopPropagation();
                }
            });
        } else {
            opts.loadingText = opts.manuallyText;
            $(opts.loading).html(opts.loadingText).fadeIn().on('click', function (event) {
                if ($(obj).attr('scrollPagination') == 'enabled') {
                    $.fn.scrollPagination.loadContent(obj);
                } else {
                    event.stopPropagation();
                }
            });
        }
    };

    $.fn.fadeInWithDelay = function () {
        var delay = 0;
        return this.each(function () {
            $(this).delay(delay).animate({ opacity: 1 }, 300);
            delay += 100;
        });
    };
})(jQuery);

