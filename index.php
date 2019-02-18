<?php
/**
 * VOID：无类型
 * 
 * 作者：<a href="https://www.imalan.cn">熊猫小A</a>
 * 
 * @package     Typecho-Theme-VOID
 * @author      熊猫小A
 * @version     1.5.1
 * @link        https://blog.imalan.cn/archives/247/
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
?>

<?php 
if(!Utils::isPjax() && !Utils::isAjax()){
    $this->need('includes/head.php');
    $this->need('includes/header.php');
} 
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    <?php $this->pageLink('','next'); ?>
    <?php $this->need('includes/banner.php'); ?>

    <div class="wrapper container">
        <?php if(!Utils::isAjax()): ?>
            <?php $this->next(); ?>
            <section id="new" aria-label="最新文章">
                <div class="section-title">
                    <?php if(Utils::isPluginAvailable('Sticky') && !empty(Helper::options()->plugin('Sticky')->sticky_cids)) echo 'FEATURED'; else echo 'LATEST'; ?>
                </div>
                <a class="item" href="<?php $this->permalink(); ?>"  itemscope="" itemtype="http://schema.org/BlogPosting">
                    <div class="item-content">
                        <h1 itemprop="name" aria-label="文章标题：<?php $this->title(); ?>"><?php $this->title(); ?></h1>
                        <p class=post-meta>
                            <span itemprop="author"><?php $this->author(); ?></span>&nbsp;•&nbsp;   <!-- author -->
                            <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>   <!-- date -->
                        </p>
                        <p itemprop="headline"><?php $this->excerpt(150); ?></p>
                    </div>
                    <?php  $cover = $this->fields->banner; if(empty($cover)) $cover = $setting['defaultCover']; if(empty($cover)) $cover = $setting['defaultBanner']; 
                        if(!Utils::isWeixin()): ?>
                        <?php $lazyID = rand(1,10000); ?>
                        <div class="lazy-wrap loading"><div class="item-banner lazy" data-lazy-id=<?php echo $lazyID; ?>></div></div>
                        <?php Utils::registerLazyImg($cover, $lazyID); ?>
                    <?php else: ?>
                        <div class="lazy-wrap"><div class="item-banner lazy loaded" style="background-image:url(<?php echo $cover; ?>)"></div></div>
                    <?php endif; ?>
                    <?php if($this->fields->banner != ''): ?>
                    <div hidden itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url" content="<?php echo $this->fields->banner; ?>">
                    </div>
                    <?php endif; ?>
                    <div hidden itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
                        <meta itemprop="name" content="<?php echo $this->options->title; ?>">
                        <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                            <meta itemprop="url" content="<?php Utils::gravatar($this->author->email, 256, ''); ?>">
                        </div>
                    </div>
                    <meta itemscope="" itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php $this->permalink(); ?>">
                    <meta itemprop="dateModified" content="<?php echo date('c', $this->modified); ?>">
                </a>
            </section>
        <?php endif; ?>
        <section id="post-list" aria-label="最近文章列表">
            <div class="section-title">RECENT</div>
            <?php while($this->next()): //直接显示摘要是默认选项 ?>
            <a class="item <?php if($this->fields->banner == '' && empty($setting['defaultCover'])) echo 'show-excerpt'; ?> <?php if(Utils::isAjax()) echo 'ajax'; ?>" href="<?php $this->permalink(); ?>" aria-label="最近文章" itemscope="" itemtype="http://schema.org/BlogPosting">
                <?php 
                    if($this->fields->banner != ''){
                        Contents::exportCover($this, $this->fields->banner, 150, false);
                    }else{
                        if(!empty($setting['defaultCover'])){
                            Contents::exportCover($this, $setting['defaultCover'], 150, true);
                        }else{ ?>
                <div class="lazy-wrap">
                    <div class="item-banner lazy loaded" style="background: black">
                        <div class="item-meta">
                        <span><?php $this->excerpt(150); ?></span>
                        </div>
                    </div>
                </div>
                       <?php }
                    } 
                ?>
                <div class="item-content">
                    <h1 itemprop="name"><?php if(Utils::isPluginAvailable('Sticky')) $this->sticky(); $this->title(); ?></h1>
                    <p>
                        <span hidden itemprop="author"><?php $this->author(); ?></span>
                        <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>   <!-- date -->
                        <span>  <!-- statics -->
                            <?php if(Utils::isPluginAvailable('TePostViews') && !$this->is('archive')): ?>
                                <?php $this->viewsNum(); ?>&nbsp;阅读&nbsp;•&nbsp;
                            <?php endif;?>
                            <?php $this->commentsNum(); ?>&nbsp;评论
                        </span>
                    </p>
                </div>
                <?php if($this->fields->banner != ''): ?>
                <div hidden itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                    <meta itemprop="url" content="<?php echo $this->fields->banner; ?>">
                </div>
                <?php endif; ?>
                <div hidden itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
                    <meta itemprop="name" content="<?php echo $this->options->title; ?>">
                    <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url" content="<?php Utils::gravatar($this->author->email, 256, ''); ?>">
                    </div>
                </div>
                <meta itemscope="" itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php $this->permalink(); ?>">
                <meta itemprop="dateModified" content="<?php echo date('c', $this->modified); ?>">
            </a>
            <?php endwhile;?>
        </section>
        <?php if(!$setting['ajaxIndex']): ?>
            <?php $this->pageNav('<span aria-label="上一页">←</span>', '<span aria-label="下一页">→</span>', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
        <?php else: ?>
            <ol class="pager"><li class="current"><a class="ajax-Load" style="width:unset" href="javascript:void(0)" onclick="VOID.ajaxLoad();" no-pjax target="_self">加载更多</a></li></ol>
        <?php endif; ?>
    </div>
</main>

<?php 
if(!Utils::isPjax() && !Utils::isAjax()){
    $this->need('includes/footer.php');
} 
?>