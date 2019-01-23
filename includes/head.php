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
    <link rel="stylesheet" href="<?php Utils::indexTheme('/assets/bundle-84083e4c24.css');?>">
    <link rel="stylesheet" href="<?php Utils::indexTheme('/assets/VOID-c8346d7a34.css');?>">
    
    <script>
    VOIDConfig = {
        PJAX : <?php echo $this->options->pjax == '1' ? 'true' : 'false'; ?>,
        searchBase : "<?php Utils::index("/search/"); ?>",
        buildTime : "<?php Utils::getBuildTime(); ?>",
        tocOffset : 0,
        bannerHeightType : <?php if($this->options->desktopBannerHeight && $this->options->desktopBannerHeight !='') echo '"percentage",bannerHeight : '.$this->options->desktopBannerHeight; else echo '"px"';?>
    }
    if(VOIDConfig.bannerHeightType == "percentage"){
        VOIDConfig.tocOffset = window.innerHeight * VOIDConfig.bannerHeight / 100 + 88;
    }else{
        VOIDConfig.tocOffset = 488;
    }
    var likePath="<?php Utils::index('/action/like?up'); ?>";
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