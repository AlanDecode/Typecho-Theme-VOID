/* eslint-disable linebreak-style */
/* eslint-disable no-undef */
/* eslint-disable no-console */
// RAW
// Author: 熊猫小A
// Link: https://www.imalan.cn

console.log(' %c Theme VOID %c https://blog.imalan.cn/ ', 'color: #fadfa3; background: #23b7e5; padding:5px;', 'background: #1c2b36; padding:5px;');

// eslint-disable-next-line no-unused-vars
function animateTo(distance, time){
    var $body = (window.opera) ? (document.compatMode == 'CSS1Compat' ? $('html') : $('body')) : $('html,body');
    $body.animate({scrollTop: distance}, time);
}

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

// eslint-disable-next-line no-unused-vars
function checkGoTop(){
    if($(document).scrollTop() > window.innerHeight){
        $('#back-top').fadeIn(300);
    }else{
        $('#back-top').fadeOut(200);
    }
}

var VOID = {
    // 初始化单页应用
    init : function(){
        VOID.parseTOC();
        VOID.parsePhotos();
        VOID.parseUrl();
        hljs.initHighlightingOnLoad();
        VOID.hitokoto();
        VOID.handleLike();
        pangu.spacingElementByTagName('p');
        // 初始化注脚
        $.bigfoot({actionOriginalFN: 'ignore'});
        // 初始化 touch 事件，移动端设备
        $('.board-item').on('touchstart',function(){
            $(this).addClass('hover');
        });
        $('.board-item').on('touchend',function(){
            $(this).removeClass('hover');
        });
        checkGoTop();
        // 监听滚动事件，实现懒加载
        if(VOIDConfig.lazyload){
            window.addEventListener('scroll',throttle(VOID.lazyLoad,100,1000));
        }
        // headroom
        if(VOIDConfig.headerMode == 0){
            var header = document.querySelector('body>header');
            var headroom  = new Headroom(header, {offset: 60});
            // initialise
            headroom.init(); 
        }
        AjaxComment.init();
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
                scrollTo: false
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
                if (-1 !== cookies.indexOf(',' + id + ',')) return VOID.alert('您已经赞过了！');
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
        var hash = new URL(window.location.href).hash;
        if(hash != ''){
            animateTo($(hash).offset().top - 80, 500);
        }else{
            animateTo(0, 500);
        }
        if($('#banner').length){
            $('body>header').removeClass('no-banner');
        }else{
            $('body>header').addClass('no-banner');
        }
        VOID.parseTOC();
        VOID.parsePhotos();
        VOID.parseUrl();
        VOID.reload();
        VOID.handleLike();
        AjaxComment.init();
        checkGoTop();
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
        // 重载 pangu.js
        pangu.spacingElementByTagName('p');
        // 重载社交分享
        getSocial();
        // 重新绑定 touch 事件，移动端设备
        $('.board-item').on('touchstart',function(){
            $(this).addClass('hover');
        });
        $('.board-item').on('touchend',function(){
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

(function(){
    if(VOIDConfig.colorScheme == 0){
        // 若不存在 cookie，根据时间判断，并设置 cookie
        if(document.cookie.replace(/(?:(?:^|.*;\s*)theme_dark\s*=\s*([^;]*).*$)|^.*$/, '$1') === ''){
            if(new Date().getHours() >= 22 || new Date().getHours() < 7){
                document.body.classList.add('theme-dark');
                document.cookie = 'theme_dark=1;max-age=1800;path=/';
                VOID.alert('夜间模式开启');
            }else{
                document.body.classList.remove('theme-dark');
            }
        // 若存在 cookie，根据 cookie 判断
        }else{
            var night = document.cookie.replace(/(?:(?:^|.*;\s*)theme_dark\s*=\s*([^;]*).*$)|^.*$/, '$1') || '0';
            if(night == '0'){
                document.body.classList.remove('theme-dark');
            }else if(night == '1'){
                document.body.classList.add('theme-dark');
                VOID.alert('夜间模式开启');
            }
        }
    }
})();


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
            animateTo($('#comment-' + AjaxComment.newID).offset().top - 50, 500);
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
                error: function() {
                    VOID.alert('提交失败！请重试。');
                    $(AjaxComment.submitBtn).html('提交评论');
                    AjaxComment.err();
                    return false;
                },
                success: function(data) { //成功取到数据
                    try {
                        if (!$(AjaxComment.commentList, data).length) {
                            var msg = '提交失败！请重试。' + $($(data)[7]).text();
                            VOID.alert(msg);
                            $(AjaxComment.submitBtn).html('提交评论');
                            AjaxComment.err();
                            return false;
                        } else {
                            AjaxComment.newID = $(AjaxComment.commentList, data).html().match(/id="?comment-\d+/g).join().match(/\d+/g).sort(function(a, b) {
                                return a - b;
                            }).pop();

                            if ($('.pager .prev').length && AjaxComment.parentID == ''){
                                // 在分页对文章发表评论，无法取得最新评论内容
                                VOID.alert('评论成功！请回到评论第一页查看。');
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
                                VOID.alert('评论成功！');
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
                                VOID.alert('评论成功！');
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
    checkGoTop();
    if(VOIDConfig.headerColorScheme && !$('body>header').hasClass('no-banner') && VOIDConfig.headerMode != 2) {
        var tr = $(window).width() > 767 ? 150 : 80;
        if ($(document).scrollTop() > tr){
            $('body>header').addClass('dark');
        }else{
            $('body>header').removeClass('dark');
        }
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
    $('.toggle-toc').toggleClass('pushed');
    $(item).toggleClass('pushed');
}
// eslint-disable-next-line no-unused-vars
function goTop(time) {
    animateTo(0, time);
}