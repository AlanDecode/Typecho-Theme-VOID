<?php
/**
 * head.php
 * 
 * <head>
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<!DOCTYPE HTML>
<html>
    <head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php 
        $banner=$this->fields->banner ? $this->fields->banner : '' ;
        Contents::exportHead($this,$banner);
    ?>

    <?php $this->header('commentReply=&description=&'); ?>

    <!--CSS-->
    <link rel="stylesheet" href="<?php Utils::indexTheme('/assets/VOID.2019012003.min.css');?>">
    <link rel="stylesheet" href="<?php Utils::indexTheme('/assets/hljs/styles/atom-one-light.css');?>">
    <link rel="stylesheet" href="<?php Utils::indexTheme('/assets/owo/owo.min.css'); ?>" />
    <link rel="stylesheet" href="<?php Utils::indexTheme('/assets/fancybox/jquery.fancybox.min.css');?>">
    
    <script>
    VOIDConfig = {
        PJAX : <?php echo $this->options->pjax == '1' ? 'true' : 'false'; ?>,
        buildTime : "<?php if($this->options->buildTime != '') echo $this->options->buildTime; else  echo '2019-01-18 12:00'; ?>",
        tocOffset : 0,
        bannerHeightType : <?php if($this->options->desktopBannerHeight && $this->options->desktopBannerHeight !='') echo '"percentage",bannerHeight : '.$this->options->desktopBannerHeight; else echo '"px"';?>
    }
    if(VOIDConfig.bannerHeightType == "percentage"){
        VOIDConfig.tocOffset = window.innerHeight * VOIDConfig.bannerHeight / 100 + 88;
    }else{
        VOIDConfig.tocOffset = 488;
    }
    var likePath="<?php Utils::index('/action/like?up'); ?>";
    function startSearch(item) {
        var searchBase = "<?php Utils::index('/search/'); ?>";
        var c = $(item).val();
        if(!c || c==""){
            $("item").attr("placeholder","你还没有输入任何信息");
            return;
        }
        var t = searchBase + c;
        if(VOIDConfig.PJAX){
            $.pjax({url: t, 
                container: '#pjax-container',
                fragment: '#pjax-container',
                timeout: 8000, })
        }else{
            window.open(t,"_self");
        }
    }
    function enterSearch(item){
        var event = window.event || arguments.callee.caller.arguments[0];  
        if (event.keyCode == 13)  {  
            startSearch(item);
        }
    }
    function toggleNav(item){
        $(item).toggleClass("pushed");
        if($(item).hasClass("pushed")){
            $("#nav-mobile").fadeIn(200);
            VOID.openModal();
        }
        else{
            VOID.closeModal();
            $("#nav-mobile").fadeOut(200);
        }
    }
    function toggleToc(item) {
        $(".TOC").toggleClass("show");
        $(item).toggleClass("pushed");
    }
    function registerLazyLoadImg(url, target){
        let background = new Image();
        background.src = url;
        background.onload = function () {
            let el = document.querySelector(target);
            el.style.backgroundImage = "url("+url+")";
            el.parentElement.classList.remove("loading");
            el.classList.add("loaded");
        }
    }
    </script>
    <script src="<?php Utils::indexTheme('/assets/owo/owo.js'); ?>"></script>
    <?php echo $this->options->head; ?>
    <style>
    <?php if($this->options->desktopBannerHeight && $this->options->desktopBannerHeight !=''): ?>
    @media screen and (min-width: 768px){
        main>.lazy-wrap{height: <?php echo $this->options->desktopBannerHeight ?>vh;}
        main{margin-top: calc(<?php echo $this->options->desktopBannerHeight ?>vh - 150px)}
    }
    <?php endif; ?>
    <?php if($this->options->mobileBannerHeight && $this->options->mobileBannerHeight !=''): ?>
    @media screen and (max-width: 768px){
        main>.lazy-wrap{height: <?php echo $this->options->mobileBannerHeight ?>vh;}
        main{margin-top: calc(<?php echo $this->options->mobileBannerHeight ?>vh - 100px)}
    }
    <?php endif; ?>
    </style>
    </head>