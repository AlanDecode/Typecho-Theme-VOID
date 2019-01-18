// RAW
// Author: 熊猫小A
// Link: https://www.imalan.cn

console.log(` %c Theme VOID %c https://blog.imalan.cn/ `, `color: #fadfa3; background: #23b7e5; padding:5px;`, `background: #1c2b36; padding:5px;`);

VOID = {
    // 初始化单页应用
    init : function(){
        NProgress.configure({ showSpinner: false });
        VOID.parsedPhotos();
        VOID.parseUrl();
        hljs.initHighlightingOnLoad();
        VOID.hitokoto();
        var cookies = $.macaroon('_syan_like') || "";
        $.each($(".post-like"),function(i,item){
            var id = $(item).attr('data-pid');
            if (-1 !== cookies.indexOf("," + id + ","))  $(item).addClass("done");
        })
        $(".post-like").click(function(){
            $(this).addClass("done");
        })
    },

    // 解析照片集
    parsedPhotos : function(){
        var nPhotos=$("article .photos img").length;
        var parsedPhotos=0;
        $.each($("article .photos"),function(i,item){
            var MinHeight=10000000000000;
            $.each($(item).find("img"),function(ii,iitem){
                var theImage = new Image(); 
                theImage.onload=function(){
                    $(iitem).parent().attr("data-height",String(theImage.height));
                    $(iitem).parent().attr("data-width",String(theImage.width));
                    MinHeight=MinHeight<theImage.height?MinHeight:theImage.height;
                    $(item).attr("data-min-h",String(MinHeight));
                    parsedPhotos++;
                    if(parsedPhotos>=nPhotos){
                        $.each($("article .photos a"),function(i,item){
                            $(item).css("flex",String(parseFloat($(item).parent().attr("data-min-h"))/parseFloat($(item).attr("data-height"))));
                        })
                    }
                }
                theImage.src = $(iitem).attr( "src"); 
            })
        })
    },

    // 解析URL
    parseUrl : function(){
        var domain=document.domain;
        $(`a:not(a[href^="#"]):not('.post-like')`).each(function(i,item){
            if((!$(item).attr("target") || (!$(item).attr("target")=="" && !$(item).attr("target")=="_self" ))){
                if(item.host!=domain){
                    $(item).attr("target","_blank");
                }
            }
        })

        if(VOIDConfig.PJAX){
            $.each($('a:not(a[target="_blank"], a[no-pjax])'),function(i,item){
                if(item.host==domain){
                    $(item).addClass("pjax");
                }
            })
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
            url: " https://v1.hitokoto.cn/?c=a&encode=json",
            async:true,
            success:function(data){
                $("#hitokoto").html(data.hitokoto + ` - 「` + data.from + `」`);
            }
        });
    },

    // PJAX 开始前
    beforePjax : function(){
        NProgress.start();
        $(".toggle").removeClass("pushed");
        if($("body").hasClass("modal-open")) VOID.closeModal();
        $("#nav-mobile").fadeOut(200);
    },

    // PJAX 结束后
    afterPjax(){
        NProgress.done();
        VOID.parsedPhotos();
        VOID.parseUrl();
        VOID.reload();
    },

    // 重载与事件绑定
    reload : function(){
        // 重载代码高亮
        $("pre code").each(function(i, block) {hljs.highlightBlock(block);});   
        // 重载 MathJax
        if (typeof MathJax !== 'undefined'){
            MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
        } 
        // 重载百度统计
        if (typeof _hmt !== 'undefined'){
            _hmt.push(['_trackPageview', location.pathname + location.search]);
        }
        // 重新绑定文章点赞事件
        $(".post-like").click(function(){
            $(this).addClass("done");
        })
        $(".post-like").on("click", function(){
            var th = $(this);
            var id = th.attr('data-pid');
            var cookies = $.macaroon('_syan_like') || "";
            if (!id || !/^\d{1,10}$/.test(id)) return;
            if (-1 !== cookies.indexOf("," + id + ",")) return alert("您已经赞过了！");
            cookies ? cookies.length >= 160 ? (cookies = cookies.substring(0, cookies.length - 1), cookies = cookies.substr
    (1).split(","), cookies.splice(0, 1), cookies.push(id), cookies = cookies.join(","), $.macaroon("_syan_like", "," + cookies + 
    ",")) : $.macaroon("_syan_like", cookies + id + ",") : $.macaroon("_syan_like", "," + id + ",");
            $.post(likePath,{
            cid:id
            },function(data){
            th.addClass('actived');
            var zan = th.find('.like-num').text();
            th.find('.like-num').text(parseInt(zan) + 1);
            },'json');
        });
        // 已点赞按钮高亮
        var cookies = $.macaroon('_syan_like') || "";
        $.each($(".post-like"),function(i,item){
            var id = $(item).attr('data-pid');
            if (-1 !== cookies.indexOf("," + id + ","))  $(item).addClass("done");
        })
    },

    scrollTop : 0,

    // 开启模态框
    openModal : function(){
        VOID.scrollTop = document.scrollingElement.scrollTop;
        document.body.classList.add("modal-open");
        document.body.style.top = -VOID.scrollTop + 'px';
    },

    // 关闭模态框
    closeModal : function () {
        document.body.classList.remove("modal-open");
        document.scrollingElement.scrollTop = VOID.scrollTop;
    }
}

$(document).ready(function(){
    VOID.init();
})

if(VOIDConfig.PJAX){
    $(document).on('pjax:send',function(){
        VOID.beforePjax();
    })

    $(document).on('pjax:complete',function(){
        VOID.afterPjax();
    })
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
    $("#uptime").html(days + " 天 " + hours + " 小时 " + minutes + " 分 " + seconds + " 秒 ");
}, 1000);

$(document).scroll(function(){
    if(window.innerWidth <= 768) return;
    if($(".TOC").length<1) return;
    if($(document).scrollTop() > 400){
        $(".TOC").addClass("fixed");
    }
    else{
        $(".TOC").removeClass("fixed");
    }
})