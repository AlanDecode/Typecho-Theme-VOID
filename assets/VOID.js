/* eslint-disable no-unused-vars */
/* eslint-disable linebreak-style */
/* eslint-disable no-undef */
/* eslint-disable no-console */
// VOID
// Author: 熊猫小A
// Link: https://blog.imalan.cn/archives/247/

console.log(' %c Theme VOID %c https://blog.imalan.cn/archives/247/ ', 'color: #fadfa3; background: #23b7e5; padding:5px;', 'background: #1c2b36; padding:5px;');


function animateTo(distance, time) {
    var $body = (window.opera) ? (document.compatMode == 'CSS1Compat' ? $('html') : $('body')) : $('html,body');
    $body.animate({ scrollTop: distance }, time);
}

// 节流函数
function throttle(fun, delay, time) {
    var timeout,
        startTime = new Date();

    return function () {
        var context = this,
            args = arguments,
            curTime = new Date();

        clearTimeout(timeout);
        // 如果达到了规定的触发时间间隔，触发 handler
        if (curTime - startTime >= time) {
            fun.apply(context, args);
            startTime = curTime;
            // 没达到触发间隔，重新设定定时器
        } else {
            timeout = setTimeout(fun, delay);
        }
    };
}


function checkGoTop() {
    if ($(document).scrollTop() > window.innerHeight) {
        $('#back-top').fadeIn(300);
    } else {
        $('#back-top').fadeOut(200);
    }
}

var getDeviceState = function (element) {
    var zIndex;
    if (window.getComputedStyle) {
        // 现代浏览器
        zIndex = window.getComputedStyle(element).getPropertyValue('z-index');
    } else if (element.currentStyle) {
        // ie8-
        zIndex = element.currentStyle['z-index'];
    }
    return parseInt(zIndex, 10);
};

var getPrefersDarkModeState = function () {
    var indicator = document.createElement('div');
    indicator.className = 'dark-mode-state-indicator';
    document.body.appendChild(indicator);
    return getDeviceState(indicator) === 11;
};



var VOID = {
    // 初始化单页应用
    init: function () {
        VOID.countWords();
        VOID.parseTOC();
        VOID.parsePhotos();
        VOID.parseUrl();
        VOID.hitokoto();
        VOID.handleLike();
        pangu.spacingElementByTagName('p');
        // 高亮
        VOID.highlight();
        // 初始化注脚
        $.bigfoot({ actionOriginalFN: 'ignore' });
        // 初始化 touch 事件，移动端设备
        $('.board-item').on('touchstart', function () {
            $(this).addClass('hover');
        });
        $('.board-item').on('touchend', function () {
            $(this).removeClass('hover');
        });
        checkGoTop();
        // 监听滚动事件，实现懒加载
        if (VOIDConfig.lazyload) {
            window.addEventListener('scroll', throttle(VOID.lazyLoad, 100, 1000));
        }
        // headroom
        if (VOIDConfig.headerMode == 0) {
            var header = document.querySelector('body>header');
            var headroom = new Headroom(header, { offset: 60 });
            // initialise
            headroom.init();
        }
        AjaxComment.init();
    },

    // 解析照片集
    parsePhotos: function () {
        var base = 50;
        $.each($('.photos'), function (i, photoSet) {
            $.each($(photoSet).children(), function (j, item) {
                var img = new Image();
                img.src = $(item).find('img').attr('data-src');
                img.onload = function () {
                    var w = parseFloat(img.width);
                    var h = parseFloat(img.height);
                    $(item).css('width', w * base / h + 'px');
                    $(item).css('flex-grow', w * base / h);
                    $(item).find('a').css('padding-top', h / w * 100 + '%');
                };
            });
        });
    },

    // 解析URL
    parseUrl: function () {
        var domain = document.domain;
        $('a:not(a[href^="#"]):not(".post-like")').each(function (i, item) {
            if ((!$(item).attr('target') || (!$(item).attr('target') == '' && !$(item).attr('target') == '_self'))) {
                if (item.host != domain) {
                    $(item).attr('target', '_blank');
                }
            }
        });

        if (VOIDConfig.PJAX) {
            $.each($('a:not(a[target="_blank"], a[no-pjax])'), function (i, item) {
                if (item.host == domain) {
                    $(item).addClass('pjax');
                }
            });
            $(document).pjax('a.pjax', {
                container: '#pjax-container',
                fragment: '#pjax-container',
                timeout: 8000
            });
        }
    },

    // 一言
    hitokoto: function () {
        $.ajax({
            url: 'https://v1.hitokoto.cn/?c=a&encode=json',
            async: true,
            success: function (data) {
                $('#hitokoto').html(data.hitokoto + ' - 「' + data.from + '」');
            }
        });
    },

    // PJAX 开始前
    beforePjax: function () {
        NProgress.start();
        $('.toggle').removeClass('pushed');
        $('.mobile-search').removeClass('opened');
        $('header').removeClass('opened');
        if ($('body').hasClass('modal-open')) VOID.closeModal();
        $('#nav-mobile').fadeOut(200);
        if ($('.TOC').length > 0) {
            tocbot.destroy();
        }
    },

    alert: function (content, time) {
        var errTemplate = '<div class="msg" id="msg{id}">{Text}</div>';
        var id = new Date().getTime();
        $('body').prepend(errTemplate.replace('{Text}', content).replace('{id}', id));
        $.each($('.msg'), function (i, item) {
            if ($(item).attr('id') != 'msg' + id) {
                $(item).css('top', $(item).offset().top - $(document).scrollTop() + $('.msg#msg' + id).outerHeight() + 20 + 'px');
            }
        });
        $('.msg#msg' + id).addClass('show');
        var t = time;
        if (typeof (t) != 'number') {
            t = 2500;
        }
        setTimeout(function () {
            $('.msg#msg' + id).addClass('hide');
            setTimeout(function () {
                $('.msg#msg' + id).remove();
            }, 1000);
        }, t);
    },

    // 点赞事件处理
    handleLike: function () {
        // 已点赞高亮
        if ($('.post-like').length > 0) {
            var cookies = $.macaroon('_syan_like') || '';
            $.each($('.post-like'), function (i, item) {
                var id = $(item).attr('data-pid');
                if (-1 !== cookies.indexOf(',' + id + ',')) $(item).addClass('done');
            });
            $('.post-like').click(function () {
                $(this).addClass('done');
            });
        }
        // 点赞事件绑定
        if ($('.post-like').length > 0) {
            $('.post-like').unbind('click');
            $('.post-like').click(function () {
                $(this).addClass('done');
                var th = $(this);
                var id = th.attr('data-pid');
                var cookies = $.macaroon('_syan_like') || '';
                if (!id || !/^\d{1,10}$/.test(id)) return;
                if (-1 !== cookies.indexOf(',' + id + ',')) return VOID.alert('您已经赞过了！');
                cookies ? cookies.length >= 160 ? (cookies = cookies.substring(0, cookies.length - 1), cookies = cookies.substr(1).split(','), cookies.splice(0, 1), cookies.push(id), cookies = cookies.join(','), $.macaroon('_syan_like', ',' + cookies + ',')) : $.macaroon('_syan_like', cookies + id + ',') : $.macaroon('_syan_like', ',' + id + ',');
                $.post(likePath, { cid: id }, function () {
                    th.addClass('actived');
                    var zan = th.find('.like-num').text();
                    th.find('.like-num').text(parseInt(zan) + 1);
                }, 'json');
            });
        }
    },

    // PJAX 结束后
    afterPjax: function () {
        NProgress.done();
        var hash = new URL(window.location.href).hash;
        if (hash != '') {
            animateTo($(hash).offset().top - 80, 500);
        }
        if ($('#banner').length) {
            $('body>header').removeClass('no-banner');
        } else {
            $('body>header').addClass('no-banner');
        }
        VOID.countWords();
        VOID.parseTOC();
        VOID.parsePhotos();
        VOID.parseUrl();
        VOID.reload();
        VOID.handleLike();
        AjaxComment.init();
        checkGoTop();
    },

    // 重载与事件绑定
    reload: function () {
        // 重载代码高亮
        VOID.highlight();
        // 重载 MathJax
        if (typeof MathJax !== 'undefined') {
            MathJax.Hub.Queue(['Typeset', MathJax.Hub]);
        }
        // 重载百度统计
        if (typeof _hmt !== 'undefined') {
            _hmt.push(['_trackPageview', location.pathname + location.search]);
        }
        // 重载 pangu.js
        pangu.spacingElementByTagName('p');
        // 重载社交分享
        getSocial();
        // 重新绑定 touch 事件，移动端设备
        $('.board-item').on('touchstart', function () {
            $(this).addClass('hover');
        });
        $('.board-item').on('touchend', function () {
            $(this).removeClass('hover');
        });
        // 重载注脚
        $.bigfoot({ actionOriginalFN: 'ignore' });
        // 重载表情
        if ($('.OwO').length > 0) {
            new OwO({
                logo: 'OωO',
                container: document.getElementsByClassName('OwO')[0],
                target: document.getElementsByClassName('input-area')[0],
                api: '/usr/themes/VOID/assets/libs/owo/OwO_01.json',
                position: 'down',
                width: '400px',
                maxHeight: '250px'
            });
        }
    },

    scrollTop: 0,

    // 开启模态框
    openModal: function () {
        VOID.scrollTop = document.scrollingElement.scrollTop;
        document.body.classList.add('modal-open');
        document.body.style.top = -VOID.scrollTop + 'px';
    },

    // 关闭模态框
    closeModal: function () {
        document.body.classList.remove('modal-open');
        document.scrollingElement.scrollTop = VOID.scrollTop;
    },

    toggleArchive: function (item) {
        if ($(item).parent().hasClass('shrink')) {
            $(item).html('-');
            $(item).parent().removeClass('shrink');
        }
        else {
            $(item).html('+');
            $(item).parent().addClass('shrink');
        }
    },

    // 解析文章目录
    parseTOC: function () {
        if ($('.TOC').length > 0) {
            var toc_option = {
                // Where to render the table of contents.
                tocSelector: '.TOC',
                // Where to grab the headings to build the table of contents.
                contentSelector: 'div[itemprop=articleBody]',
                // Which headings to grab inside of the contentSelector element.
                headingSelector: 'h2, h3, h4, h5',
                // 收缩深度
                collapseDepth: 6
            };
            tocbot.init(toc_option);
        }
    },

    highlight: function () {
        $.each($('pre code:not([class*="language-"])'), function (i, item) {
            var c = $(item).attr('class');
            if (typeof (c) != 'undefined') {
                c = 'language-' + c.replace('lang-', '');
            } else {
                c = 'language-';
            }
            $(item).addClass(c);
        });
        
        Prism.highlightAll();
    },

    lazyLoad: function () {
        var viewPortHeight = document.documentElement.clientHeight; //可见区域高度
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop; //滚动条距离顶部高度
        $.each($('img.lazyload'), function (i, item) {
            if ($(item).offset().top < viewPortHeight + scrollTop && $(item).offset().top + $(item).height() > scrollTop) {
                if (!$(item).hasClass('loaded') && !$(item).hasClass('error')) {
                    var img = new Image();
                    img.src = $(item).attr('data-src');
                    img.onload = function () {
                        $(item).animate({ opacity: 0 }, 150);
                        setTimeout(function () {
                            $(item).attr('src', $(item).attr('data-src'));
                            $(item).addClass('loaded');
                            $(item).animate({ opacity: 1 }, 180);
                        }, 180);
                    };
                    img.onerror = function () {
                        $(item).addClass('error');
                    };
                }
            }
        });
    },

    countWords: function () {
        if ($('#totalWordCount').length) {
            var total = 0;
            $.each($('a.archive-title'), function (i, item) {
                total += parseInt($(item).attr('data-words'));
            });
            $('#totalWordCount').html(total);
        }
    }
};

var DarkModeSwitcher = {
    // TimeZone => [lat, lon]
    mapTzGeo: {
        'America/Denver': [39.7645187, -104.9951977],
        'Europe/London': [51.5287352, -0.3817843],
        'America/Chicago': [41.8339042, -88.0121562],
        'America/Asuncion': [-25.2966809, -57.6681298],
        'America/Montevideo': [-34.8207362, -56.3765247],
        'Asia/Beirut': [33.8892846, 35.4692627],
        'Pacific/Auckland': [-36.8621448, 174.5852782],
        'America/Los_Angeles': [34.0207305, -118.6919292],
        'America/New_York': [40.6976701, -74.2598739],
        'America/Halifax': [43.7085882, -63.475903],
        'America/Godthab': [64.1791647, -51.7768494],
        'Asia/Dubai': [25.0757073, 54.9475461],
        'Asia/Jakarta': [-6.2293867, 106.6894293],
        'Asia/Shanghai': [31.2243084, 120.9162622],
        'Australia/Sydney': [-33.8473567, 150.6517817],
        'Asia/Tokyo': [35.5062896, 138.6484937],
        'Asia/Dhaka': [23.7808875, 90.2792377],
        'Asia/Baku': [40.3947695, 49.7148734],
        'Australia/Brisbane': [-27.3798035, 152.4327106],
        'Pacific/Noumea': [-22.2642742, 166.4098471],
        'Pacific/Majuro': [7.1045756, 171.3526867],
        'Pacific/Tongatapu': [-21.1695566, -175.3350296],
        'Asia/Baghdad': [33.3118944, 44.2158179],
        'Asia/Karachi': [25.1933895, 66.5949598],
        'Africa/Johannesburg': [-26.1713505, 27.9699839],
        'default': [39.9390731, 116.1172655] // BeiJing as default
    },

    // sunset、sunrise 为格式化至当日的 Date 对象
    switchColorScheme: function (sunset, sunrise) {
        var current = new Date();
        // 格式化为小时
        var sunset_s = sunset.getHours() + sunset.getMinutes() / 60;
        var sunrise_s = sunrise.getHours() + sunrise.getMinutes() / 60;
        var current_s = current.getHours() + current.getMinutes() / 60;
        // 若不存在 cookie，根据时间判断，并设置 cookie
        if (document.cookie.replace(/(?:(?:^|.*;\s*)theme_dark\s*=\s*([^;]*).*$)|^.*$/, '$1') === '') {
            if (current_s > sunset_s || current_s < sunrise_s) {
                document.body.classList.add('theme-dark');
                if (current_s > sunset_s) // 如果当前为夜晚，日出时间应该切换至第二日
                    sunrise = new Date(sunrise.getTime() + 3600000 * 24);
                // 现在距日出还有 (s)
                var toSunrise = (sunrise.getTime() - current.getTime()) / 1000;
                // 设置 cookie
                var cookieString = 'theme_dark=1;max-age=' + parseInt(toSunrise) + ';path=/';
                document.cookie = cookieString;
                VOID.alert('日落了，夜间模式已开启。');
            } else {
                document.body.classList.remove('theme-dark');
            }
        } else {
            // 若存在 cookie，根据 cookie 判断
            var night = document.cookie.replace(/(?:(?:^|.*;\s*)theme_dark\s*=\s*([^;]*).*$)|^.*$/, '$1') || '0';
            if (night == '0') {
                document.body.classList.remove('theme-dark');
            } else if (night == '1') {
                document.body.classList.add('theme-dark');
            }
        }
    },

    checkColorSchemeFallback: function () {
        var TimeZone = jstz.determine();
        TimeZone = TimeZone.name();
        if (DarkModeSwitcher.mapTzGeo.hasOwnProperty(TimeZone)) TimeZone = DarkModeSwitcher.mapTzGeo[TimeZone];
        else TimeZone = DarkModeSwitcher.mapTzGeo['default'];
        sunset = new Date().sunset(TimeZone[0], TimeZone[1]);
        sunrise = new Date().sunrise(TimeZone[0], TimeZone[1]);
        // 全部转换至当天
        sunset = new Date(new Date().setHours(sunset.getHours(), sunset.getMinutes(), 0));
        sunrise = new Date(new Date().setHours(sunrise.getHours(), sunrise.getMinutes(), 0));
        DarkModeSwitcher.switchColorScheme(sunset, sunrise);
    },

    checkColorScheme: function () {
        if (VOIDConfig.colorScheme != 0) return;
        if (getPrefersDarkModeState() && VOIDConfig.followSystemColorScheme) {
            document.body.classList.add('theme-dark');
            var night = document.cookie.replace(/(?:(?:^|.*;\s*)theme_dark\s*=\s*([^;]*).*$)|^.*$/, '$1') || '0';
            if (night != '1') {
                VOID.alert('已为您开启深色模式。');
            }
            document.cookie = 'theme_dark=1;max-age=7200;path=/';
        } else {
            if (!VOIDConfig.accurateDarkMode) {
                DarkModeSwitcher.checkColorSchemeFallback();
            } else {
                if ('geolocation' in navigator) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        sunset = new Date().sunset(position.coords.latitude, position.coords.longitude);
                        sunrise = new Date().sunrise(position.coords.latitude, position.coords.longitude);
                        // 全部转换至当天
                        sunset = new Date(new Date().setHours(sunset.getHours(), sunset.getMinutes(), 0));
                        sunrise = new Date(new Date().setHours(sunrise.getHours(), sunrise.getMinutes(), 0));
                        DarkModeSwitcher.checkColorScheme(sunset, sunrise);
                    }, function () {
                        DarkModeSwitcher.checkColorSchemeFallback();
                    });
                } else {
                    DarkModeSwitcher.checkColorSchemeFallback();
                }
            }
        }
    }
};

DarkModeSwitcher.checkColorScheme();

var AjaxComment = {
    noName: '必须填写用户名',
    noMail: '必须填写电子邮箱地址',
    noContent: '必须填写评论内容',
    invalidMail: '邮箱地址不合法',
    commentsOrder: 'DESC',
    commentList: '.comment-list',
    comments: '#comments .comments-title',
    commentReply: '.comment-reply',
    commentForm: '#comment-form',
    respond: '.respond',
    textarea: '#textarea',
    submitBtn: '#comment-submit-button',
    newID: '',
    parentID: '',

    bindClick: function () {
        $(AjaxComment.commentReply + ' a, #cancel-comment-reply-link').unbind('click');
        $(AjaxComment.commentReply + ' a').click(function () { // 回复
            AjaxComment.parentID = $(this).parent().parent().parent().attr('id');
            $(AjaxComment.textarea).focus();
        });
        $('#cancel-comment-reply-link').click(function () { // 取消
            AjaxComment.parentID = '';
        });
    },

    err: function () {
        $(AjaxComment.submitBtn).attr('disabled', false);
        AjaxComment.newID = '';
    },

    finish: function () {
        TypechoComment.cancelReply();
        $(AjaxComment.submitBtn).html('提交评论');
        $(AjaxComment.textarea).val('');
        $(AjaxComment.submitBtn).attr('disabled', false);
        if ($('#comment-' + AjaxComment.newID).length > 0) {
            animateTo($('#comment-' + AjaxComment.newID).offset().top - 50, 500);
            $('#comment-' + AjaxComment.newID).fadeTo(500, 1);
        }
        $('.comment-num .num').html(parseInt($('.comment-num .num').html()) + 1);
        AjaxComment.bindClick();
        VOID.highlight();
    },

    init: function () {
        AjaxComment.bindClick();
        $(AjaxComment.commentForm).submit(function () { // 提交事件
            $(AjaxComment.submitBtn).attr('disabled', true);

            /* 检查 */
            if ($(AjaxComment.commentForm).find('#author')[0]) {
                if ($(AjaxComment.commentForm).find('#author').val() == '') {
                    VOID.alert(AjaxComment.noName);
                    AjaxComment.err();
                    return false;
                }

                if ($(AjaxComment.commentForm).find('#mail').val() == '') {
                    VOID.alert(AjaxComment.noMail);
                    AjaxComment.err();
                    return false;
                }

                var filter = /^[^@\s<&>]+@([a-z0-9]+\.)+[a-z]{2,4}$/i;
                if (!filter.test($(AjaxComment.commentForm).find('#mail').val())) {
                    VOID.alert(AjaxComment.invalidMail);
                    AjaxComment.err();
                    return false;
                }
            }

            var textValue = $(AjaxComment.commentForm).find(AjaxComment.textarea).val().replace(/(^\s*)|(\s*$)/g, '');//检查空格信息
            if (textValue == null || textValue == '') {
                VOID.alert(AjaxComment.noContent);
                AjaxComment.err();
                return false;
            }
            $(AjaxComment.submitBtn).html('提交中');
            $.ajax({
                url: $(AjaxComment.commentForm).attr('action'),
                type: $(AjaxComment.commentForm).attr('method'),
                data: $(AjaxComment.commentForm).serializeArray(),
                error: function () {
                    VOID.alert('提交失败！请重试。');
                    $(AjaxComment.submitBtn).html('提交评论');
                    AjaxComment.err();
                    return false;
                },
                success: function (data) { //成功取到数据
                    try {
                        if (!$(AjaxComment.commentList, data).length) {
                            var msg = '提交失败！请重试。' + $($(data)[7]).text();
                            VOID.alert(msg);
                            $(AjaxComment.submitBtn).html('提交评论');
                            AjaxComment.err();
                            return false;
                        } else {
                            AjaxComment.newID = $(AjaxComment.commentList, data).html().match(/id="?comment-\d+/g).join().match(/\d+/g).sort(function (a, b) {
                                return a - b;
                            }).pop();

                            if ($('.pager .prev').length && AjaxComment.parentID == '') {
                                // 在分页对文章发表评论，无法取得最新评论内容
                                VOID.alert('评论成功！请回到评论第一页查看。');
                                AjaxComment.newID = '';
                                AjaxComment.parentID = '';
                                AjaxComment.finish();
                                return false;
                            }

                            var newCommentType = AjaxComment.parentID == '' ? 'comment-parent' : 'comment-child';
                            var newCommentData = '<div itemscope itemtype="http://schema.org/UserComments" id="comment-' + AjaxComment.newID + '" style="opacity:0" class="comment-body ' + newCommentType + '">' + $(data).find('#comment-' + AjaxComment.newID).html() + '</div>';

                            // 当页面无评论，先添加一个评论容器
                            if ($(AjaxComment.commentList).length <= 0) {
                                $('#comments').append('<h3 class="comment-separator"><div class="comment-tab-current"><span class="comment-num">已有 <span class="num">0</span> 条评论</span></div></h3>')
                                    .append('<div class="comment-list"></div>');
                            }

                            if (AjaxComment.parentID == '') {
                                // 无父 id，直接对文章评论，插入到第一个 comment-list 头部
                                $('#comments>.comment-list').prepend(newCommentData);
                                VOID.alert('评论成功！');
                                AjaxComment.finish();
                                AjaxComment.newID = '';
                                return false;
                            } else {
                                if ($('#' + AjaxComment.parentID).hasClass('comment-parent')) {
                                    // 父评论是母评论
                                    if ($('#' + AjaxComment.parentID + ' > .comment-children').length > 0) {
                                        // 父评论已有子评论，插入到子评论列表头部
                                        $('#' + AjaxComment.parentID + ' > .comment-children > .comment-list').prepend(newCommentData);
                                    }
                                    else {
                                        // 父评论没有子评论，新建一层包裹
                                        newCommentData = '<div class="comment-children"><div class="comment-list">' + newCommentData + '</div></div>';
                                        $('#' + AjaxComment.parentID).append(newCommentData);
                                    }
                                } else {
                                    // 父评论是子评论，与父评论平级，并放在后面
                                    $('#' + AjaxComment.parentID).after(newCommentData);
                                }
                                VOID.alert('评论成功！');
                                AjaxComment.finish();
                                AjaxComment.parentID = '';
                                AjaxComment.newID = '';
                                return false;
                            }
                        }
                    } catch (e) {
                        window.location.reload();
                    }
                } // end success()
            }); // end ajax()
            return false;
        }); // end submit()
    }
};

$(document).ready(function () {
    VOID.init();
});

if (VOIDConfig.PJAX) {
    $(document).on('pjax:send', function () {
        VOID.beforePjax();
    });

    $(document).on('pjax:complete', function () {
        VOID.afterPjax();
    });
}

setInterval(function () {
    var times = new Date().getTime() - Date.parse(VOIDConfig.buildTime);
    times = Math.floor(times / 1000); // convert total milliseconds into total seconds
    var days = Math.floor(times / (60 * 60 * 24)); //separate days
    times %= 60 * 60 * 24; //subtract entire days
    var hours = Math.floor(times / (60 * 60)); //separate hours
    times %= 60 * 60; //subtract entire hours
    var minutes = Math.floor(times / 60); //separate minutes
    times %= 60; //subtract entire minutes
    var seconds = Math.floor(times / 1); // remainder is seconds
    $('#uptime').html(days + ' 天 ' + hours + ' 小时 ' + minutes + ' 分 ' + seconds + ' 秒 ');
}, 1000);

window.addEventListener('scroll', function () {
    checkGoTop();
    if (VOIDConfig.headerColorScheme && !$('body>header').hasClass('no-banner') && VOIDConfig.headerMode != 2) {
        var tr = $(window).width() > 767 ? 150 : 80;
        if ($(document).scrollTop() > tr) {
            $('body>header').addClass('dark');
        } else {
            $('body>header').removeClass('dark');
        }
    }
});

function startSearch(item) {
    var c = $(item).val();
    $(item).val('');
    $(item).blur();
    if (!c || c == '') {
        $(item).attr('placeholder', '你还没有输入任何信息');
        return;
    }
    var t = VOIDConfig.searchBase + c;
    if (VOIDConfig.PJAX) {
        $.pjax({
            url: t,
            container: '#pjax-container',
            fragment: '#pjax-container',
            timeout: 8000,
        });
    } else {
        window.open(t, '_self');
    }
}

function enterSearch(item) {
    var event = window.event || arguments.callee.caller.arguments[0];
    if (event.keyCode == 13) {
        startSearch(item);
    }
}

function toggleSearch() {
    $('.mobile-search').toggleClass('opened');
    setTimeout(function () {
        if ($('.mobile-search').hasClass('opened')) {
            $('.mobile-search input').focus();
        } else {
            $('.mobile-search input').blur();
        }
    }, 400);
}

function toggleNav(item) {
    $(item).toggleClass('pushed');
    $('header').toggleClass('opened');
    if ($(item).hasClass('pushed')) {
        $('#nav-mobile').fadeIn(200);
        VOID.openModal();
    }
    else {
        VOID.closeModal();
        $('#nav-mobile').fadeOut(200);
    }
}

function toggleToc(item) {
    $('.TOC').toggleClass('show');
    $('.toggle-toc').toggleClass('pushed');
    $(item).toggleClass('pushed');
}

function goTop(time) {
    animateTo(0, time);
}