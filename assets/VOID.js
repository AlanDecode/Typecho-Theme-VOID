/* eslint-disable no-undef */
/* eslint-disable no-console */
// RAW
// Author: 熊猫小A
// Link: https://www.imalan.cn

console.log(' %c Theme VOID %c https://blog.imalan.cn/ ', 'color: #fadfa3; background: #23b7e5; padding:5px;', 'background: #1c2b36; padding:5px;');

var VOID = {
    // 初始化单页应用
    init : function(){
        VOID.parsedPhotos();
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
    },

    // 解析照片集
    parsedPhotos : function(){
        var base = 50;
        $.each($('.photos'), function(i, photoSet){
            $.each($(photoSet).children(), function(j, item){
                var img = new Image();
                img.src = $(item).find('img').attr('src');
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
        $('header').removeClass('opened');
        if($('body').hasClass('modal-open')) VOID.closeModal();
        $('#nav-mobile').fadeOut(200);
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
        VOID.parsedPhotos();
        VOID.parseUrl();
        VOID.reload();
        VOID.handleLike();
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
                api: '/usr/themes/VOID/assets/libs/owo/OwO.json',
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

$(document).scroll(function(){
    if(window.outerWidth < 1366) return;
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