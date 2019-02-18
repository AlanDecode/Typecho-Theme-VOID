/* eslint-disable no-undef */
/* eslint-disable no-console */
// RAW
// Author: 熊猫小A
// Link: https://www.imalan.cn

console.log(' %c Theme VOID %c https://blog.imalan.cn/ ', 'color: #fadfa3; background: #23b7e5; padding:5px;', 'background: #1c2b36; padding:5px;');

// 节流函数
function throttle(fun, delay, time) {
    var timeout,
        startTime = new Date();

    return function() {
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

var VOID = {
    // 初始化单页应用
    init : function(){
        VOID.parseTOC();
        VOID.showWelcomeWord();
        VOID.parsePhotos();
        VOID.parseUrl();
        hljs.initHighlightingOnLoad();
        VOID.hitokoto();
        VOID.handleLike();
        // 初始化注脚
        $.bigfoot({actionOriginalFN: 'ignore'});
        // 初始化 touch 事件，移动端设备
        $('.item,.board-item').on('touchstart',function(){
            $(this).addClass('hover');
        });
        $('.item,.board-item').on('touchend',function(){
            $(this).removeClass('hover');
        });
        if($('main').offset().top - $(document).scrollTop() < 120){
            $('header,.mobile-search').addClass('dark');
        }else{
            $('header,.mobile-search').removeClass('dark');
        }
        // 监听滚动事件，实现懒加载
        window.addEventListener('scroll',throttle(VOID.lazyLoad,100,1000));
        AjaxComment.init();
        if(VOIDConfig.infiniteLoad && VOIDConfig.ajaxIndex){
            window.addEventListener('scroll',function(){
                setTimeout(function(){
                    var h=$('.footer-info').offset().top;
                    var c = $(document).scrollTop();
                    var wh = $(window).height();  
                    if (Math.ceil(wh+c)>=h){
                        VOID.ajaxLoad();
                    }
                }, 750);
            });
        }
    },

    showWelcomeWord : function(){
        if(VOIDConfig.customNotice != ''){
            setTimeout(function() {
                alert(VOIDConfig.customNotice, 4000);
            }, 200);
        }
        if(VOIDConfig.welcomeWord){
            var text = '';
            if (document.referrer !== '') {
                var referrer = document.createElement('a');
                referrer.href = document.referrer;
                text = '嗨！来自 ' + referrer.hostname + ' 的朋友！';
                var domain = referrer.hostname.split('.')[1];
                if (domain == 'baidu') {
                    text = '嗨！ 来自 百度搜索 的朋友！';
                } else if (domain == 'so') {
                    text = '嗨！ 来自 360搜索 的朋友！';
                } else if (domain == 'google') {
                    text = '嗨！ 来自 谷歌搜索 的朋友！';
                }
            } 
            var now = (new Date()).getHours();
            if (now > 23 || now <= 5) {
                text = text + '你是夜猫子呀？这么晚还不睡觉，明天起的来嘛？';
            } else if (now > 5 && now <= 7) {
                text = text + '早上好！一日之计在于晨，美好的一天就要开始了！';
            } else if (now > 7 && now <= 11) {
                text = text + '上午好！工作顺利嘛，不要久坐，多起来走动走动哦！';
            } else if (now > 11 && now <= 14) {
                text = text + '中午了，工作了一个上午，现在是午餐时间！';
            } else if (now > 14 && now <= 17) {
                text = text + '午后很容易犯困呢，今天的运动目标完成了吗？';
            } else if (now > 17 && now <= 19) {
                text = text + '傍晚了！窗外夕阳的景色很美丽呢，最美不过夕阳红~';
            } else if (now > 19 && now <= 21) {
                text = text + '晚上好，今天过得怎么样？';
            } else if (now > 21 && now <= 23) {
                text = text + '已经这么晚了呀，早点休息吧，晚安~';
            } 
            setTimeout(function() {
                alert(text);
            }, 200);
        }
    },

    // 解析照片集
    parsePhotos : function(){
        var base = 50;
        $.each($('.photos'), function(i, photoSet){
            $.each($(photoSet).children(), function(j, item){
                var img = new Image();
                img.src = $(item).find('img').attr('data-src');
                img.onload = function(){
                    var w = parseFloat(img.width);
                    var h = parseFloat(img.height);
                    $(item).css('width', w*base/h +'px');
                    $(item).css('flex-grow', w*base/h);
                    $(item).find('a').css('padding-top', h/w*100+'%');
                };
            });
        });
    },

    // 解析URL
    parseUrl : function(){
        var domain=document.domain;
        $('a:not(a[href^="#"]):not(".post-like")').each(function(i,item){
            if((!$(item).attr('target') || (!$(item).attr('target')=='' && !$(item).attr('target')=='_self' ))){
                if(item.host!=domain){
                    $(item).attr('target','_blank');
                }
            }
        });

        if(VOIDConfig.PJAX){
            $.each($('a:not(a[target="_blank"], a[no-pjax])'),function(i,item){
                if(item.host==domain){
                    $(item).addClass('pjax');
                }
            });
            $(document).pjax('a.pjax', {
                container: '#pjax-container',
                fragment: '#pjax-container',
                timeout: 8000,
            });
        }
    },

    // 一言
    hitokoto : function(){
        $.ajax({
            url: 'https://v1.hitokoto.cn/?c=a&encode=json',
            async:true,
            success:function(data){
                $('#hitokoto').html(data.hitokoto + ' - 「' + data.from + '」');
            }
        });
    },

    // PJAX 开始前
    beforePjax : function(){
        NProgress.start();
        $('.toggle').removeClass('pushed');
        $('.mobile-search').removeClass('opened');
        $('header').removeClass('opened');
        if($('body').hasClass('modal-open')) VOID.closeModal();
        $('#nav-mobile').fadeOut(200);
        if($('.TOC').length > 0){
            tocbot.destroy();
        }
    },

    alert : function(content, time){
        var errTemplate = '<div class="msg" id="msg{id}">{Text}</div>';
        var id = new Date().getTime();
        $('body').prepend(errTemplate.replace('{Text}', content).replace('{id}', id));
        $.each($('.msg'), function(i,item){
            if($(item).attr('id') != 'msg' + id){
                $(item).css('top', $(item).offset().top - $(document).scrollTop() + $('.msg#msg' + id).outerHeight() + 20 + 'px');
            }
        });
        $('.msg#msg' + id).addClass('show');
        var t = time;
        if(typeof(t) != 'number'){
            t = 2500;
        } 
        setTimeout(function(){
            $('.msg#msg' + id).addClass('hide');
            setTimeout(function(){
                $('.msg#msg' + id).remove();
            }, 1000);
        }, t);
    },

    // 点赞事件处理
    handleLike : function(){
        // 已点赞高亮
        if($('.post-like').length > 0){
            var cookies = $.macaroon('_syan_like') || '';
            $.each($('.post-like'),function(i,item){
                var id = $(item).attr('data-pid');
                if (-1 !== cookies.indexOf(',' + id + ','))  $(item).addClass('done');
            });
            $('.post-like').click(function(){
                $(this).addClass('done');
            });
        }
        // 点赞事件绑定
        if($('.post-like').length > 0){
            $('.post-like').unbind('click');
            $('.post-like').click(function(){
                $(this).addClass('done');
                var th = $(this);
                var id = th.attr('data-pid');
                var cookies = $.macaroon('_syan_like') || '';
                if (!id || !/^\d{1,10}$/.test(id)) return;
                if (-1 !== cookies.indexOf(',' + id + ',')) return alert('您已经赞过了！');
                cookies ? cookies.length >= 160 ? (cookies = cookies.substring(0, cookies.length - 1), cookies = cookies.substr(1).split(','), cookies.splice(0, 1), cookies.push(id), cookies = cookies.join(','), $.macaroon('_syan_like', ',' + cookies + ',')) : $.macaroon('_syan_like', cookies + id + ',') : $.macaroon('_syan_like', ',' + id + ',');
                $.post(likePath,{cid:id},function(){
                    th.addClass('actived');
                    var zan = th.find('.like-num').text();
                    th.find('.like-num').text(parseInt(zan) + 1);
                },'json');
            });
        }
    },

    // PJAX 结束后
    afterPjax : function(){
        NProgress.done();
        VOID.parseTOC();
        VOID.parsePhotos();
        VOID.parseUrl();
        VOID.reload();
        VOID.handleLike();
        AjaxComment.init();
        if(VOIDConfig.welcomeWord){
            alert('欢迎访问 ' + document.title);
        }
        if($('a.next').length){
            VOIDConfig.nextUrl = $('a.next').attr('href');
            $('a.next').remove();
        }
    },

    // 重载与事件绑定
    reload : function(){
        // 重载代码高亮
        $('pre code').each(function(i, block) {hljs.highlightBlock(block);});   
        // 重载 MathJax
        if (typeof MathJax !== 'undefined'){
            MathJax.Hub.Queue(['Typeset',MathJax.Hub]);
        } 
        // 重载百度统计
        if (typeof _hmt !== 'undefined'){
            _hmt.push(['_trackPageview', location.pathname + location.search]);
        }
        // 重新绑定 touch 事件，移动端设备
        $('.item,.board-item').on('touchstart',function(){
            $(this).addClass('hover');
        });
        $('.item,.board-item').on('touchend',function(){
            $(this).removeClass('hover');
        });
        // 重载注脚
        $.bigfoot({actionOriginalFN: 'ignore'});
        // 重载表情
        if($('.OwO').length > 0){
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

    scrollTop : 0,

    // 开启模态框
    openModal : function(){
        VOID.scrollTop = document.scrollingElement.scrollTop;
        document.body.classList.add('modal-open');
        document.body.style.top = -VOID.scrollTop + 'px';
    },

    // 关闭模态框
    closeModal : function () {
        document.body.classList.remove('modal-open');
        document.scrollingElement.scrollTop = VOID.scrollTop;
    },

    toggleArchive : function (item) {
        if($(item).parent().hasClass('shrink')){
            $(item).html('-');
            $(item).parent().removeClass('shrink');
        }
        else{
            $(item).html('+');
            $(item).parent().addClass('shrink');
        }
    },

    // 解析文章目录
    parseTOC : function(){
        if($('.TOC').length > 0){
            tocbot.init({
                // Where to render the table of contents.
                tocSelector: '.TOC',
                // Where to grab the headings to build the table of contents.
                contentSelector: 'div[itemprop=articleBody]',
                // Which headings to grab inside of the contentSelector element.
                headingSelector: 'h2, h3, h4, h5'
            });
        }
    },

    isAjaxLoading : false,

    // AJAX 首页分页
    ajaxLoad : function(){
        if(VOID.isAjaxLoading) return;
        if(VOIDConfig.nextUrl == -2) return;
        if(VOIDConfig.nextUrl == -1){
            if(!$('a.next').length){
                $('a.ajax-Load').parent().html('这里是世界的尽头');
                VOIDConfig.nextUrl = -2;
                return;
            }else{
                VOIDConfig.nextUrl = $('a.next').attr('href');
                $('a.next').remove();
            }
        }
        $('a.ajax-Load').html('加载中...');
        VOID.isAjaxLoading = true;
        $.ajax({
            url: VOIDConfig.nextUrl,
            type: 'get',
            beforeSend: function(request) {
                request.setRequestHeader('X-VOID-AJAX', 'true');
            },
            success: function(data){
                if(!$(data).find('a.next').length){
                    $('a.ajax-Load').parent().removeClass('current');
                    $('a.ajax-Load').parent().html('这里是世界的尽头');
                    VOIDConfig.nextUrl = -2;
                }else{
                    VOIDConfig.nextUrl = $(data).find('a.next').attr('href');
                    $('a.ajax-Load').html('加载更多');
                }
                $('#post-list').append($(data).find('a.item'));
                $('a.item.ajax:not(.ajax-loaded)').addClass('ajax-loaded');
                VOID.isAjaxLoading = false;
                VOID.parseUrl();
                $('.item,.board-item').on('touchstart',function(){
                    $(this).addClass('hover');
                });
                $('.item,.board-item').on('touchend',function(){
                    $(this).removeClass('hover');
                });
            },
            error: function(){
                VOID.isAjaxLoading = false;
                alert('加载失败！请检查网络或者联系博主。');
                $('a.ajax-Load').html('加载更多');
            }
        });
    },

    lazyLoad : function(){
        var viewPortHeight = document.documentElement.clientHeight; //可见区域高度
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop; //滚动条距离顶部高度
        $.each($('img.lazyload'), function(i, item){
            if($(item).offset().top < viewPortHeight + scrollTop && $(item).offset().top + $(item).height() > scrollTop){
                if(!$(item).hasClass('loaded') && !$(item).hasClass('error')){
                    var img = new Image();
                    img.src = $(item).attr('data-src');
                    img.onload = function () {
                        $(item).animate({opacity : 0}, 150);
                        setTimeout(function(){
                            $(item).attr('src',$(item).attr('data-src'));
                            $(item).addClass('loaded');
                            $(item).animate({opacity : 1}, 180);
                        }, 180);
                    };
                    img.onerror = function(){
                        $(item).addClass('error');
                    };
                }
            }
        });
    }
};


// eslint-disable-next-line no-unused-vars
var AjaxComment = {
    noName : '必须填写用户名',
    noMail : '必须填写电子邮箱地址',
    noContent : '必须填写评论内容',
    invalidMail : '邮箱地址不合法',
    commentsOrder : 'DESC',
    commentList : '.comment-list',
    comments : '#comments .comments-title',
    commentReply : '.comment-reply',
    commentForm : '#comment-form',
    respond : '.respond',
    textarea : '#textarea',
    submitBtn : '#comment-submit-button',
    newID : '',
    parentID : '',

    bindClick : function(){
        $(AjaxComment.commentReply + ' a, #cancel-comment-reply-link').unbind('click');
        $(AjaxComment.commentReply + ' a').click(function() { // 回复
            AjaxComment.parentID = $(this).parent().parent().parent().attr('id');
            $(AjaxComment.textarea).focus();
        });
        $('#cancel-comment-reply-link').click(function() { // 取消
            AjaxComment.parentID = '';
        });
    },

    err : function(){
        $(AjaxComment.submitBtn).attr('disabled', false);
        AjaxComment.newID = '';
    },

    finish : function(){
        TypechoComment.cancelReply();
        $(AjaxComment.submitBtn).html('提交评论');
        $(AjaxComment.textarea).val('');
        $(AjaxComment.submitBtn).attr('disabled', false);
        if($('#comment-'+AjaxComment.newID).length > 0){
            var $body = (window.opera) ? (document.compatMode == 'CSS1Compat' ? $('html') : $('body')) : $('html,body');
            $body.animate({scrollTop: $('#comment-' + AjaxComment.newID).offset().top - 50}, 500);
            $('#comment-' + AjaxComment.newID).fadeTo(500 ,1);
        }
        $('.comment-num .num').html(parseInt($('.comment-num .num').html())+1);
        AjaxComment.bindClick();
    },

    init : function(){
        AjaxComment.bindClick();
        $(AjaxComment.commentForm).submit(function() { // 提交事件
            $(AjaxComment.submitBtn).attr('disabled', true);
            
            /* 检查 */
            if ($(AjaxComment.commentForm).find('#author')[0]) {
                if ($(AjaxComment.commentForm).find('#author').val() == '') {
                    alert(AjaxComment.noName);
                    AjaxComment.err();
                    return false;
                }
    
                if ($(AjaxComment.commentForm).find('#mail').val() == '') {
                    alert(AjaxComment.noMail);
                    AjaxComment.err();
                    return false;
                }
    
                var filter = /^[^@\s<&>]+@([a-z0-9]+\.)+[a-z]{2,4}$/i;
                if (!filter.test($(AjaxComment.commentForm).find('#mail').val())) {
                    alert(AjaxComment.invalidMail);
                    AjaxComment.err();
                    return false;
                }
            }

            var textValue = $(AjaxComment.commentForm).find(AjaxComment.textarea).val().replace(/(^\s*)|(\s*$)/g, '');//检查空格信息
            if (textValue == null || textValue == '') {
                alert(AjaxComment.noContent);
                AjaxComment.err();
                return false;
            }
            $(AjaxComment.submitBtn).html('提交中……');
            $.ajax({
                url: $(AjaxComment.commentForm).attr('action'),
                type: $(AjaxComment.commentForm).attr('method'),
                data: $(AjaxComment.commentForm).serializeArray(),
                error: function() {
                    alert('提交失败！请重试。');
                    $(AjaxComment.submitBtn).html('提交评论');
                    AjaxComment.err();
                    return false;
                },
                success: function(data) { //成功取到数据
                    try {
                        if (!$(AjaxComment.commentList, data).length) {
                            var msg = '提交失败！请重试。' + $($(data)[7]).text();
                            alert(msg);
                            $(AjaxComment.submitBtn).html('提交评论');
                            AjaxComment.err();
                            return false;
                        } else {
                            AjaxComment.newID = $(AjaxComment.commentList, data).html().match(/id="?comment-\d+/g).join().match(/\d+/g).sort(function(a, b) {
                                return a - b;
                            }).pop();

                            if ($('.pager .prev').length && AjaxComment.parentID == ''){
                                // 在分页对文章发表评论，无法取得最新评论内容
                                alert('评论成功！请回到评论第一页查看。');
                                AjaxComment.newID = '';
                                AjaxComment.parentID = '';
                                AjaxComment.finish();
                                return false;
                            }

                            var newCommentType = AjaxComment.parentID == '' ? 'comment-parent' : 'comment-child';
                            var newCommentData = '<div itemscope itemtype="http://schema.org/UserComments" id="comment-'+AjaxComment.newID+'" style="opacity:0" class="comment-body '+newCommentType+'">' + $(data).find('#comment-' + AjaxComment.newID).html() + '</div>';
                            
                            // 当页面无评论，先添加一个评论容器
                            if($(AjaxComment.commentList).length <= 0){
                                $('#comments').append('<h3 class="comment-separator"><div class="comment-tab-current"><span class="comment-num">已有 <span class="num">0</span> 条评论</span></div></h3>')
                                    .append('<div class="comment-list"></div>');
                            }

                            if(AjaxComment.parentID == ''){
                                // 无父 id，直接对文章评论，插入到第一个 comment-list 头部
                                $('#comments>.comment-list').prepend(newCommentData);
                                alert('评论成功！');
                                AjaxComment.finish();
                                AjaxComment.newID = '';
                                return false;
                            } else{
                                if($('#'+AjaxComment.parentID).hasClass('comment-parent')){
                                    // 父评论是母评论
                                    if($('#'+AjaxComment.parentID+' > .comment-children').length > 0){
                                        // 父评论已有子评论，插入到子评论列表头部
                                        $('#'+AjaxComment.parentID+' > .comment-children > .comment-list').prepend(newCommentData);
                                    }
                                    else{
                                        // 父评论没有子评论，新建一层包裹
                                        newCommentData = '<div class="comment-children"><div class="comment-list">'+ newCommentData +'</div></div>';
                                        $('#'+AjaxComment.parentID).append(newCommentData);
                                    }
                                }else{
                                    // 父评论是子评论，与父评论平级，并放在后面
                                    $('#'+AjaxComment.parentID).after(newCommentData);
                                }
                                alert('评论成功！');
                                AjaxComment.finish();
                                AjaxComment.parentID = '';
                                AjaxComment.newID = '';
                                return false;
                            }
                        }
                    } catch(e) {
                        window.location.reload();
                    }
                } // end success()
            }); // end ajax()
            return false;
        }); // end submit()
    }
};

$(document).ready(function(){
    window.alert = VOID.alert;
    VOID.init();
});

if(VOIDConfig.PJAX){
    $(document).on('pjax:send',function(){
        VOID.beforePjax();
    });

    $(document).on('pjax:complete',function(){
        VOID.afterPjax();
    });
}

setInterval(function(){
    var times = new Date().getTime() - Date.parse(VOIDConfig.buildTime);
    times = Math.floor(times/1000); // convert total milliseconds into total seconds
    var days = Math.floor( times/(60*60*24) ); //separate days
    times %= 60*60*24; //subtract entire days
    var hours = Math.floor( times/(60*60) ); //separate hours
    times %= 60*60; //subtract entire hours
    var minutes = Math.floor( times/60 ); //separate minutes
    times %= 60; //subtract entire minutes
    var seconds = Math.floor( times/1 ); // remainder is seconds
    $('#uptime').html(days + ' 天 ' + hours + ' 小时 ' + minutes + ' 分 ' + seconds + ' 秒 ');
}, 1000);

window.addEventListener('scroll',function(){
    if($('main').offset().top - $(document).scrollTop() < 120){
        $('header,.mobile-search').addClass('dark');
    }else{
        $('header,.mobile-search').removeClass('dark');
    }
    if(window.innerWidth < 1366) return;
    if($('.TOC').length<1) return;

    if($(document).scrollTop() > VOIDConfig.tocOffset - 20.1){
        $('.TOC').addClass('fixed');
    }
    else{
        $('.TOC').removeClass('fixed');
    }
});

function startSearch(item) {
    var c = $(item).val();
    $(item).val('');
    $(item).blur();
    if(!c || c==''){
        $(item).attr('placeholder','你还没有输入任何信息');
        return;
    }
    var t = VOIDConfig.searchBase + c;
    if(VOIDConfig.PJAX){
        $.pjax({url: t, 
            container: '#pjax-container',
            fragment: '#pjax-container',
            timeout: 8000, });
    }else{
        window.open(t,'_self');
    }
}
// eslint-disable-next-line no-unused-vars
function enterSearch(item){
    var event = window.event || arguments.callee.caller.arguments[0];  
    if (event.keyCode == 13)  {  
        startSearch(item);
    }
}
// eslint-disable-next-line no-unused-vars
function toggleSearch(){
    $('.mobile-search').toggleClass('opened');
    setTimeout(function(){
        if($('.mobile-search').hasClass('opened')){
            $('.mobile-search input').focus();
        }else{
            $('.mobile-search input').blur();
        }
    }, 400);
}
// eslint-disable-next-line no-unused-vars
function toggleNav(item){
    $(item).toggleClass('pushed');
    $('header').toggleClass('opened');
    if($(item).hasClass('pushed')){
        $('#nav-mobile').fadeIn(200);
        VOID.openModal();
    }
    else{
        VOID.closeModal();
        $('#nav-mobile').fadeOut(200);
    }
}
// eslint-disable-next-line no-unused-vars
function toggleToc(item) {
    $('.TOC').toggleClass('show');
    $(item).toggleClass('pushed');
}