/* eslint-disable no-console */
/* eslint-disable linebreak-style */
/* eslint-disable no-undef */

TOC = {
    toggle: function () {
        $('body').toggleClass('sidebar-show');
    },

    close: function () {
        $('body').removeClass('sidebar-show');
    },

    open: function () {
        $('body').addClass('sidebar-show');
    }
};

VOID_Util = {
    throttle: function (fn, delay, atleast) {
        var timer = null;
        var previous = null;
    
        return function () {
            var now = +new Date();
    
            if ( !previous ) previous = now;
    
            if ( now - previous > atleast ) {
                fn();
                // 重置上一次开始时间为本次结束时间
                previous = now;
            } else {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    fn();
                }, delay);
            }
        };
    },

    clickIn: function (e, el) {
        if (!$(el).length) return false;
        return $(el).has(e.target).length || $(el).get(0) === e.target;
    },

    getDeviceState: function (element) {
        var zIndex;
        if (window.getComputedStyle) {
            // 现代浏览器
            zIndex = window.getComputedStyle(element).getPropertyValue('z-index');
        } else if (element.currentStyle) {
            // ie8-
            zIndex = element.currentStyle['z-index'];
        }
        return parseInt(zIndex, 10);
    },

    getPrefersDarkModeState: function () {
        var indicator = document.createElement('div');
        indicator.className = 'dark-mode-state-indicator';
        document.body.appendChild(indicator);
        return VOID_Util.getDeviceState(indicator) === 11;
    },

    setCookie: function (name, value, time) {
        if (time > 0) {
            document.cookie = name + '=' + escape(value) + ';max-age=' + String(time) + ';path=/';
        } else {
            // session
            document.cookie = name + '=' + escape(value) + ';path=/';
        }
    },

    getCookie: function (name) {
        var reg = new RegExp('(^| )' + name + '=([^;]*)(;|$)');
        var arr = document.cookie.match(reg);
        if (arr)
            return unescape(arr[2]);
        else
            return null;
    }
};

VOID_Lazyload = {
    eventHandler: null,

    finish: function () {
        return $('img.lazyload.loaded').length + $('img.lazyload.error').length == $('img.lazyload').length;
    },

    addEventListener: function () {
        if (!VOID_Lazyload.finish()) {
            window.addEventListener('scroll',VOID_Lazyload.eventHandler);
        }
    },

    removeEventListener: function () {
        if (VOID_Lazyload.finish())
            window.removeEventListener('scroll', VOID_Lazyload.eventHandler);
    },

    inViewport: function (item) {
        var viewPortHeight = document.documentElement.clientHeight; //可见区域高度
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop; //滚动条距离顶部高度
        var offset = 300; // 提前 200 px 加载
        return $(item).offset().top - offset < viewPortHeight + scrollTop 
                    && $(item).offset().top + $(item).height() + offset > scrollTop;
    },

    callback: function () {
        $.each($('img.lazyload:not(.loaded):not(.error)'), function (i, item) {
            if (VOID_Lazyload.inViewport(item)) {
                var img = new Image();
                img.onload = function () {
                    $(item).attr('src', $(item).attr('data-src'));
                    $(item).addClass('loaded');
                    VOID_Lazyload.removeEventListener();
                };
                img.onerror = function () {
                    $(item).addClass('error');
                    VOID_Lazyload.removeEventListener();
                };
                img.src = $(item).attr('data-src');
            }
        });
        VOID_Lazyload.removeEventListener();
    },

    init: function () {
        window.removeEventListener('scroll', VOID_Lazyload.eventHandler);
        if (VOID_Lazyload.eventHandler == null)
            VOID_Lazyload.eventHandler = VOID_Util.throttle(VOID_Lazyload.callback, 200, 500);
        VOID_Lazyload.callback();
        VOID_Lazyload.addEventListener();
    }
};

VOID_SmoothScroller = {
    target: null,
    SMOOTH: 15,

    move: function () {
        var cur = document.documentElement.scrollTop;
        var step = Math.ceil(Math.abs(VOID_SmoothScroller.target - cur) / VOID_SmoothScroller.SMOOTH);

        if (Math.abs(VOID_SmoothScroller.target - cur) < 1) {
            VOID_SmoothScroller.removeEventListener();
            return;
        }

        cur >= VOID_SmoothScroller.target ? cur -= step : cur += step;
        document.documentElement.scrollTop = cur;
        requestAnimationFrame(VOID_SmoothScroller.move);
    },

    addEventListener: function () {
        // 需要特别阻止滚轮事件
        var passiveSupported = false;
        try {
            var options = Object.defineProperty({}, 'passive', {
                get: function () {
                    passiveSupported = true;
                    return null;
                }
            });

            window.addEventListener('test', null, options);
        } catch (err) {
            console.log(err);
        }
        window.addEventListener('wheel', VOID_SmoothScroller.stop, 
            passiveSupported ? { passive: false } : false);
        
        window.addEventListener('mousedown', VOID_SmoothScroller.stop);
        window.addEventListener('touchstart', VOID_SmoothScroller.stop);
    },

    removeEventListener: function () {
        window.removeEventListener('wheel', VOID_SmoothScroller.stop);
        window.removeEventListener('mousedown', VOID_SmoothScroller.stop);
        window.removeEventListener('touchstart', VOID_SmoothScroller.stop);
    },

    scrollTo: function (target, offset) {
        if (target === null) return;
        if (typeof(target) == 'object') {
            target = target.getBoundingClientRect().top + document.documentElement.scrollTop;
        } else if (typeof(target) == 'string') {
            target = document.querySelector(target).getBoundingClientRect().top 
                + document.documentElement.scrollTop;
        }
        if (typeof(offset) == 'number') {
            target += offset;
        }
        // 若超出顶部或无法到达
        target = Math.max(target, 0);
        target = Math.min(target, 
            document.documentElement.getBoundingClientRect().height - document.documentElement.clientHeight);

        VOID_SmoothScroller.addEventListener();
        VOID_SmoothScroller.target = target;
        VOID_SmoothScroller.move();
    },

    stop: function (event) {
        if (typeof(event) != 'undefined')
            event.preventDefault();
        VOID_SmoothScroller.scrollTo(document.documentElement.scrollTop);
    }
};

VOID_Ui = {
    checkGoTop: function () {
        if ($(document).scrollTop() > window.innerHeight) {
            $('#go-top').addClass('show');
        } else {
            $('#go-top').removeClass('show');
        }
    },

    checkHeader: function () {
        if (VOIDConfig.headerMode == 2) return;
        var tr = $('.lazy-wrap').height();
        if ($(document).scrollTop() > tr) {
            $('body>header').addClass('pull-up');
        } else {
            $('body>header').removeClass('pull-up');
        }
    },

    checkScrollTop: function () {
        if (VOID_Util.getCookie('void_pos') != null && parseFloat(VOID_Util.getCookie('void_pos')) != -1) {
            VOID_SmoothScroller.scrollTo(parseFloat(VOID_Util.getCookie('void_pos')), -60);
            VOID_Util.setCookie('void_pos', -1);
        } else {
            VOID_SmoothScroller.stop();
        }
    },

    toggleSearch: function () {
        $('.mobile-search-form').toggleClass('opened');
        $('.mobile-search-form input').focus();
    },

    toggleNav: function (item) {
        $(item).toggleClass('pushed');
        $('header').toggleClass('opened');
        TOC.close();
        if ($(item).hasClass('pushed')) {
            $('#nav-mobile').fadeIn(200);
            VOID_Ui.openModal();
        }
        else {
            VOID_Ui.closeModal();
            $('#nav-mobile').fadeOut(200);
        }
    },

    toggleSettingPanel: function () {
        if(!$('body').hasClass('setting-panel-show')) {
            if ($('#login-panel').length)
                $('#login-panel').removeClass('show');
            $('#setting-panel').show();
            setTimeout(function () {
                $('body').addClass('setting-panel-show');
            }, 50); // 改变 display 时 transition 总是失效，需要延迟一下
        } else {
            $('body').removeClass('setting-panel-show');
            setTimeout(function () {
                $('#setting-panel').hide();
            }, 300);
        }
    },

    toggleSerif: function (item, serif) {
        $('.font-indicator').removeClass('checked');
        $(item).addClass('checked');
        if (serif) {
            if ($('#stylesheet_noto').length < 1)
                $('body').append('<link id="stylesheet_noto" href="https://fonts.googleapis.com/css?family=Noto+Serif+SC:400,700&amp;subset=chinese-simplified" rel="stylesheet">');
            $('body').addClass('serif');
            VOID_Util.setCookie('serif', '1', 2592000); // 一个月
        } else {
            if ($('#stylesheet_droid').length < 1)
                $('body').append('<link id="stylesheet_droid" href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700" rel="stylesheet">');
            $('body').removeClass('serif');
            VOID_Util.setCookie('serif', '0', 2592000);
        }
    },

    adjustTextsize: function (up) {
        var current = parseInt($('body').attr('fontsize'));

        if (up) {
            if (current >= 5) {
                VOID.alert('已经是最大了！');
                return;
            }
            $('body').attr('fontsize', String(current + 1));
        } else {
            if (current <= 1) {
                VOID.alert('已经是最小了！');
                return;
            }
            $('body').attr('fontsize', String(current - 1));
        }

        VOID_Util.setCookie('textsize', $('body').attr('fontsize'), 2592000);
    },

    toggleLoginForm: function () {
        $('#login-panel').toggleClass('show');
        $('#login-panel input[name=referer]').val(window.location.href);

        if ($('#loggin-form').hasClass('need-refresh') && $('#login-panel').hasClass('show')) {
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: {void_action: 'getLoginAction'},
                success: function (data) {
                    $('form#loggin-form').attr('action', data);
                    $('#loggin-form').removeClass('need-refresh');
                },
                error: function () {
                    VOID.alert('请求登陆参数错误。请在刷新后尝试登陆。');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            });
        }
    },

    lazyload: function () {
        VOID_Lazyload.init();
    },

    headroom: function () {
        if (VOIDConfig.headerMode == 0) {
            var header = document.querySelector('body>header');
            var headroom = new Headroom(header, { offset: 60 });
            headroom.init();
        }
    },

    toggleArchive: function (item) {
        var year = '#year-' + $(item).attr('data-year');
        if ($(year).hasClass('shrink')) {
            $(item).html('-');
            $(year).removeClass('shrink');
            var num = parseInt($(item).attr('data-num'));
            $(year).css('max-height',  num * 49 + 'px');
        }
        else {
            $(item).html('+');
            $(year).addClass('shrink');
            $(year).css('max-height', '0');
        }
    },

    rememberPos: function () {
        VOID_Util.setCookie('void_pos', String($(document).scrollTop()));
    },

    scrollTop: 0,

    // 开启模态框
    openModal: function () {
        VOID_Ui.scrollTop = document.scrollingElement.scrollTop;
        document.body.classList.add('modal-open');
        document.body.style.top = -VOID_Ui.scrollTop + 'px';
    },

    // 关闭模态框
    closeModal: function () {
        document.body.classList.remove('modal-open');
        document.scrollingElement.scrollTop = VOID_Ui.scrollTop;
    },

    reset: function () {
        $('.toggle').removeClass('pushed');
        $('.mobile-search').removeClass('opened');
        $('header').removeClass('opened');
        $('#setting-panel').removeClass('show');
        if ($('body').hasClass('modal-open')) {
            VOID_Ui.closeModal();
        }
        $('#nav-mobile').fadeOut(200);
        TOC.close();
        if ($('.TOC').length > 0) {
            tocbot.destroy();
        }
    },

    MasonryCtrler: {
        masonry: function () {
            $('#masonry').addClass('masonry').masonry({
                itemSelector: '.masonry-item',
                gutter: 30,
                isAnimated: false,
                transitionDuration: 0
            });
        },
        init: function () {
            if (VOID_Ui.MasonryCtrler.check() && VOIDConfig.indexStyle == 0) {
                $('.masonry-item').addClass('masonry-ready');
                VOID_Ui.MasonryCtrler.masonry();
            }
            $('.masonry-item').addClass('done');
        },
        check: function () {
            return $('#masonry').length && window.innerWidth >= 768;
        },
        watch: function (id) {
            var el = document.getElementById(id);
            new ResizeSensor(el, function () {
                if (VOID_Ui.MasonryCtrler.check() && $('#masonry').hasClass('masonry')) {
                    VOID_Ui.MasonryCtrler.masonry();
                }
            });
        }
    },

    DarkModeSwitcher: {
        checkColorScheme: function () {
            // 非自动模式
            if (VOIDConfig.colorScheme != 0) {
                return;
            }
    
            if (VOIDConfig.followSystemColorScheme && VOID_Util.getPrefersDarkModeState()) { // 自动模式跟随系统
                document.body.classList.add('theme-dark');
                var night = VOID_Util.getCookie('theme_dark');
                if (night != '1') {
                    VOID.alert('已为您开启深色模式。');
                }
                VOID_Util.setCookie('theme_dark', '1', 7200);
            } else { // 自动模式，定时            
                // 若不存在 cookie，根据时间判断，并设置 cookie
                if (VOID_Util.getCookie('theme_dark') == null) {
                    // 全部转换至当天
                    sunset = new Date(new Date().setHours(
                        Math.floor(VOIDConfig.darkModeTime.start),
                        60 * (VOIDConfig.darkModeTime.start - Math.floor(VOIDConfig.darkModeTime.start)), 0));
                    sunrise = new Date(new Date().setHours(
                        Math.floor(VOIDConfig.darkModeTime.end),
                        60 * (VOIDConfig.darkModeTime.end - Math.floor(VOIDConfig.darkModeTime.end)), 0));
        
                    var current = new Date();
                    // 格式化为小时
                    var sunset_s = VOIDConfig.darkModeTime.start;
                    var sunrise_s = VOIDConfig.darkModeTime.end;
                    var current_s = current.getHours() + current.getMinutes() / 60;

                    if (current_s > sunset_s || current_s < sunrise_s) {
                        document.body.classList.add('theme-dark');
                        if (current_s > sunset_s) // 如果当前为夜晚，日出时间应该切换至第二日
                            sunrise = new Date(sunrise.getTime() + 3600000 * 24);
                        // 现在距日出还有 (s)
                        var toSunrise = (sunrise.getTime() - current.getTime()) / 1000;
                        // 设置 cookie
                        VOID_Util.setCookie('theme_dark', '1', parseInt(toSunrise));
                        VOID.alert('日落了，夜间模式已开启。');
                    } else {
                        document.body.classList.remove('theme-dark');
                    }
                } else {
                    // 若存在 cookie，根据 cookie 判断
                    night = VOID_Util.getCookie('theme_dark');
                    if (night == '0') {
                        document.body.classList.remove('theme-dark');
                    } else if (night == '1') {
                        document.body.classList.add('theme-dark');
                    }
                }
            }
        },
    
        toggleByHand: function () {
            $('#toggle-night').addClass('switching');
            setTimeout(function () {
                $('body').toggleClass('theme-dark');
                if ($('body').hasClass('theme-dark')) {
                    VOID_Util.setCookie('theme_dark', '1', 0);
                } else {
                    VOID_Util.setCookie('theme_dark', '0', 0);
                }
                setTimeout(function () {
                    $('#toggle-night').removeClass('switching');
                }, 1000);
            }, 600);
        }
    },

    Swiper: {
        clientX: null,
        clientY: null,
        // move: function (e) {
        //     return;
        // },

        start: function(e) {
            this.clientX = e.originalEvent.changedTouches[0].clientX;
            this.clientY = e.originalEvent.changedTouches[0].clientY;
        },

        end: function (e) {
            // 垂直滚动距离
            if (Math.abs(this.clientY - e.originalEvent.changedTouches[0].clientY) > 30) {
                $('body').removeClass('setting-panel-show');
                setTimeout(function () {
                    $('#setting-panel').hide();
                }, 300);
            }
            this.clientX = null;
            this.clientY = null;
        }
    }
};

(function () {
    if ('ontouchstart' in document) {
        $(document).on('touchstart', function (e) {
            VOID_Ui.Swiper.start(e);
        });
        // $(document).on('touchmove', function () {
        //     VOID_Ui.checkHeader();
        // });
        $(document).on('touchend', function (e) {
            VOID_Ui.Swiper.end(e);
        });
    }
    $(document).on('scroll', function () {
        VOID_Ui.checkGoTop();
        VOID_Ui.checkHeader();
        if (!('ontouchstart' in document)) {
            $('body').removeClass('setting-panel-show');
            setTimeout(function () {
                $('#setting-panel').hide();
            }, 300);
        }
    });
})();