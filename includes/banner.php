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

if($this->is('post')) {
    if($this->fields->bannerStyle > 0) {
        $setting['bannerStyle'] = $this->fields->bannerStyle-1;
    }
    if($setting['bannerStyle'] == 4) { // 强制不显示
        $banner = '';
        $blur = false;
    } else {
        $banner = $this->fields->banner;
        if($setting['bannerStyle'] == 1)
            $banner = ''; 
        $blur = $setting['bannerStyle'] >= 2;
    }
}
if($this->is('page')){
    $banner = $this->fields->banner;
}
?>

<div class="lazy-wrap
    <?php 
        if(empty($banner)) echo ' no-banner';
        else echo ' loading dark';
        if($this->is('index')) echo ' index';?>">

    <?php if(!empty($banner)): ?>
        <div id="banner" class="<?php if($blur) echo 'blur'; ?>">
            <img class="lazyload instant" data-src="<?php echo $banner; ?>">
        </div>
        <script>$('body>header').removeClass('force-dark').removeClass('no-banner');</script>
    <?php else: ?>
        <script>$('body>header').addClass('force-dark').addClass('no-banner');</script>
        <style>main>.lazy-wrap{min-height: 0;}</style>
    <?php endif; ?>

    <?php if($setting['bannerStyle']>=2 && $this->is('post')): ?>
        <style>main>.lazy-wrap{min-height: 0;}</style>
    <?php endif; ?>

    <?php if(!$this->is('index')): ?>
        <div class="banner-title">
            <h1 class="post-title">
                <?php if(!$this->is('archive')): ?>
                    <?php $this->title(); ?>
                <?php else: ?>
                    <?php $this->archiveTitle(array(
                            'category'  =>  _t('分类 "%s" 下的文章'),
                            'search'    =>  _t('包含关键字 "%s" 的文章'),
                            'tag'       =>  _t('包含标签 "%s" 的文章'),
                            'author'    =>  _t('"%s" 发布的文章')
                        ), '', '');  ?>
                <?php endif;?>
            </h1>
            <?php if(!$this->is('archive')): ?>
                <p class="post-meta">
                    <?php if($this->template == 'Archives.php') {
                        echo Utils::getCatNum()." 分类 × ".Utils::getPostNum()." 文章 × ".Utils::getTagNum()." 标签";
                        if($setting['VOIDPlugin']) echo ' × <span id="totalWordCount"></span> 字';
                    } else{ ?>
                        <span itemprop="author"><a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></span>&nbsp;•&nbsp;
                        <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>
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