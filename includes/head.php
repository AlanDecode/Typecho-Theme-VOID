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
$setting = $GLOBALS['VOIDSetting']; 
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
    $banner = '';
    $description = '';
    if($this->is('post') || $this->is('page')){
        if($this->fields->banner != '')
            $banner=$this->fields->banner;
        if($this->fields->excerpt != '')
            $description = $this->fields->excerpt;
    }else{
        $description = Helper::options()->description;
    }
    ?>
    <title><?php Contents::title($this); ?></title>
    <meta name="author" content="<?php $this->author(); ?>" />
    <meta name="description" content="<?php if($description != '') echo $description; else $this->excerpt(50); ?>" />
    <meta property="og:title" content="<?php Contents::title($this); ?>" />
    <meta property="og:description" content="<?php if($description != '') echo $description; else $this->excerpt(50); ?>" />
    <meta property="og:site_name" content="<?php Contents::title($this); ?>" />
    <meta property="og:type" content="<?php if($this->is('post') || $this->is('page')) echo 'article'; else echo 'website'; ?>" />
    <meta property="og:url" content="<?php $this->permalink(); ?>" />
    <meta property="og:image" content="<?php echo $banner; ?>" />
    <meta property="article:published_time" content="<?php echo date('c', $this->created); ?>" />
    <meta property="article:modified_time" content="<?php echo date('c', $this->modified); ?>" />
    <meta name="twitter:title" content="<?php Contents::title($this); ?>" />
    <meta name="twitter:description" content="<?php if($description != '') echo $description; else $this->excerpt(50); ?>" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:image" content="<?php echo $banner; ?>" />
    <?php $this->header('commentReply=&description=&'); ?>

    <!--CSS-->
    <link rel="stylesheet" href="<?php Utils::indexTheme('/assets/bundle.css');?>">
    <link rel="stylesheet" href="<?php Utils::indexTheme('/assets/VOID.css');?>">

    <script>
    VOIDConfig = {
        PJAX : <?php echo $setting['pjax'] ? 'true' : 'false'; ?>,
        searchBase : "<?php Utils::index("/search/"); ?>",
        home: "<?php Utils::index("/"); ?>",
        buildTime : "<?php Utils::getBuildTime(); ?>",
        enableMath : <?php echo $setting['enableMath'] ? 'true' : 'false'; ?>,
        lazyload : <?php echo $setting['lazyload'] ? 'true' : 'false'; ?>,
        colorScheme:  <?php echo $setting['colorScheme']; ?>,
        headerMode: <?php echo $setting['headerMode']; ?>,
        followSystemColorScheme: <?php echo $setting['followSystemColorScheme'] ? 'true' : 'false'; ?>,
        accurateDarkMode: <?php echo $setting['accurateDarkMode'] ? 'true' : 'false'; ?>,
        VOIDPlugin: <?php echo $setting['VOIDPlugin'] ? 'true' : 'false'; ?>,
        likePath: "<?php Utils::index('/action/void_like?up'); ?>",
        lineNumbers: <?php if ($setting['lineNumbers']) {
                if (!Utils::isMobile() || $setting['lineNumbersOnMobile']) {
                    echo 'true';   
                } else {
                    echo 'false';
                }
            } else {
                echo 'false';
            } ?>
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
    function reloadMasonry() {
        if (typeof($) == "function") {
            if (typeof(Masonry) == "function") {
                $('.masonry-item').addClass('masonry-ready');
                if ($('#masonry').length && window.innerWidth >= 768) {
                    $('#masonry').masonry({
                        itemSelector: '.masonry-item',
                        gutter: 30,
                        isAnimated: true,
                    });
                }
            }
            $('.masonry-item').addClass('done');
        }
    }
    </script>
    <?php echo $setting['head']; ?>
    <style>
    <?php if(!$setting['titleinbanner']): ?>
        main>.lazy-wrap{min-height: 0;}
    <?php else: ?>
        <?php if(!empty($setting['desktopBannerHeight'])): ?>
        @media screen and (min-width: 768px){
            main>.lazy-wrap{min-height: <?php echo $setting['desktopBannerHeight']; ?>vh;}
        }
        <?php endif; ?>

        <?php if(!empty($setting['mobileBannerHeight'])): ?>
        @media screen and (max-width: 768px){
            main>.lazy-wrap{min-height: <?php echo $setting['mobileBannerHeight']; ?>vh;}
        }
        <?php endif; ?>
    <?php endif; ?>
    </style>

    <?php if($setting['serifincontent']): ?>
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif+SC:400,700&amp;subset=chinese-simplified" rel="stylesheet">
    <style>div[itemprop=articleBody], .yue, .subtitle {
        font-family: 'Noto Serif SC', 
            -apple-system, BlinkMacSystemFont, "Segoe UI", "Droid Sans", "Helvetica Neue", "PingFang SC","Hiragino Sans GB", "Droid Sans Fallback", "Microsoft YaHei", sans-serif;}</style>
    <?php else: ?>
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700" rel="stylesheet">
    <?php endif; ?>
    </head>