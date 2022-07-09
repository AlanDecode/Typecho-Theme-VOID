<?php
/** 
 * banner.php
 *  
 * @author      熊猫小A
 * @version     2019-01-17 0.1
 * 
*/ 
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
$banner = $setting['defaultBanner'];
$blur = false;

if($this->is('post') || $this->is('page')) {
    $banner = $this->fields->bannerStyle < 2 ? $this->fields->banner : '';
    $blur = $this->fields->bannerStyle == 1;
}
?>

<div class="lazy-wrap
    <?php 
        if(empty($banner)) echo ' no-banner';
        else echo ' loading dark';
        if($this->is('index')) echo ' index';
        if($this->is('archive') && !$this->have()) echo ' not-found'; ?>">

    <?php if(!empty($banner)): ?>
        <div id="banner" class="<?php if($blur) echo 'blur'; ?>">
            <?php if($setting['browserLevelLoadingLazy']): ?>
                <img class="lazyload browserlevel-lazy" src="<?php echo $banner; ?>" loading="lazy">
            <?php else: ?>
                <?php if($setting['bluredLazyload']): ?>
                    <img src="<?php echo Contents::genBluredPlaceholderSrc($banner); ?>" class="blured-placeholder remove-after">
                <?php endif; ?>
                <img class="lazyload" data-src="<?php echo $banner; ?>">
            <?php endif; ?>
        </div>
        <script>$('body>header').removeClass('force-dark').removeClass('no-banner');</script>
    <?php else: ?>
        <script>$('body>header').addClass('force-dark').addClass('no-banner');</script>
        <style>main>.lazy-wrap{min-height: 0;}</style>
    <?php endif; ?>

    <?php if(!$this->is('index')): ?>
        <div class="banner-title">
            <h1 class="post-title">
                <?php if(!$this->is('archive')): ?>
                    <?php $this->title(); ?>
                <?php else: ?>
                    <?php if ($this->have()): ?>
                        <?php $this->archiveTitle(array(
                            'category'  =>  _t('分类 "%s" 下的文章'),
                            'search'    =>  _t('包含关键字 "%s" 的文章'),
                            'tag'       =>  _t('包含标签 "%s" 的文章'),
                            'author'    =>  _t('"%s" 发布的文章')
                        ), '', '');  ?>
                    <?php else: ?>
                        <span class="glitch">0</span>
                    <?php endif; ?>
                <?php endif;?>
            </h1>
            <?php if(!$this->is('archive')): ?>
                <p class="post-meta">
                    <?php if($this->template == 'Archives.php') {
                        echo Utils::getCatNum()." 分类 × ".Utils::getPostNum()." 文章 × ".Utils::getTagNum()." 标签";
                        if($setting['VOIDPlugin']) echo ' × <span id="totalWordCount"></span> 字';
                    } else{ ?>
                        <span><a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></span>&nbsp;•&nbsp;
                        <time datetime="<?php echo date('c', $this->created); ?>"><?php echo date('Y-m-d', $this->created); ?></time>
                        &nbsp;•&nbsp;<a no-pjax target="_self" href="javascript:void(0);" onclick="VOID_SmoothScroller.scrollTo('#comments', -60)"><?php $this->commentsNum(); ?>&nbsp;评论</a>
                        <?php if($setting['VOIDPlugin']) echo '&nbsp;•&nbsp;<span>'.$this->viewsNum.'&nbsp;阅读</span>'; ?>
                        <?php if($this->user->hasLogin()): ?>
                            <?php if($this->is('post')): ?>
                            &nbsp;•&nbsp;<a target="_blank" href="<?php echo $this->options->adminUrl.'write-post.php?cid='.$this->cid;?>">编辑</a>
                            <?php else: ?>
                            &nbsp;•&nbsp;<a target="_blank" href="<?php echo $this->options->adminUrl.'write-page.php?cid='.$this->cid;?>">编辑</a>
                            <?php endif;?>
                        <?php endif;?>
                    <?php } ?>
                </p>
            <?php endif;?>
        </div>
    <?php elseif($this->is('index')): ?>
        <?php 
            $title = Helper::options()->title; 
            if($setting['indexBannerTitle']!='') $title = $setting['indexBannerTitle'];
            $subtitle = Helper::options()->description;
            if($setting['indexBannerSubtitle']!='') $subtitle = $setting['indexBannerSubtitle'];
        ?>
        <div class="banner-title index<?php if(!empty($banner)) echo ' force-normal'; ?>">
            <h1 class="post-title"><span class="brand"><span><?php echo $title; ?></span></span><br><span class="subtitle"><?php echo $subtitle; ?></span></h1>
        </div>
    <?php endif; ?>
</div>