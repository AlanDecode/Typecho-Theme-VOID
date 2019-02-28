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
        $banner=$this->fields->banner ? $this->fields->banner : '' ;
        Contents::exportHead($this,$banner);
        $description = '';
        if($this->is('post') || $this->is('page')) {
            if($this->fields->excerpt != '')  $description = $this->fields->excerpt;
        }else{
            $description = Helper::options()->description;
        }
    ?>
    <meta name="description" content="<?php if($description != '') echo $description; else $this->excerpt(50); ?>" />
    <meta property="og:description" content="<?php if($description != '') echo $description; else $this->excerpt(50); ?>" />
    <meta name="twitter:description" content="<?php if($description != '') echo $description; else $this->excerpt(50); ?>" />

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
        tocOffset : -1,
        ajaxIndex : <?php echo $setting['ajaxIndex'] ? 'true' : 'false'; ?>,
        infiniteLoad : <?php echo $setting['infiniteLoad'] ? 'true' : 'false'; ?>,
        nextUrl : -1,
        customNotice : "<?php echo $setting['customNotice']; ?>",
        welcomeWord : <?php echo $setting['welcomeWord'] ? 'true' : 'false'; ?>,
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
    <?php echo $setting['head']; ?>
    <style>
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
    <?php if(!empty($setting['msgBg'])): ?>
    .msg{
        background: <?php echo $setting['msgBg']; ?>
    }
    <?php endif; ?>
    <?php if(!empty($setting['msgColor'])): ?>
    .msg{
        color: <?php echo $setting['msgColor']; ?>
    }
    <?php endif; ?>
    </style>
    </head>