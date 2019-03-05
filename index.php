<?php
/**
 * VOID：无类型
 * 
 * 作者：<a href="https://www.imalan.cn">熊猫小A</a>
 * 
 * @package     Typecho-Theme-VOID
 * @author      熊猫小A
 * @version     1.6.2
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
if($setting['simpleIndex']) {
    $this->need('includes/archives.php');
} else {
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    <?php $this->pageLink('','next'); ?>

    <?php if($setting['defaultBanner'] != '' && !$setting['indexNoBanner']) $this->need('includes/banner.php'); ?>
    
    <div class="wrapper container wide">
        <section id="post-list" aria-label="最近文章列表" <?php if($setting['defaultBanner'] == '' || $setting['indexNoBanner']) echo 'class="no-banner"'; ?>>
            <?php while($this->next()): ?>
            <?php 
                $banner = '';
                if(!empty($setting['defaultCover'])) $banner = $setting['defaultCover'];
                if($this->fields->bannerascover != '0'){
                    if($this->fields->banner != '') $banner = $this->fields->banner;
                }
            ?>
            <a class="item <?php if($banner == '') echo 'no-banner'; ?> <?php if(Utils::isAjax()) echo 'ajax'; ?>" href="<?php $this->permalink(); ?>" aria-label="最近文章" itemscope="" itemtype="http://schema.org/BlogPosting">
                <?php if($banner != ''): ?>
                    <?php $lazyID = rand(1,10000); if(!Utils::isWeixin()){ ?>
                        <div class="lazy-wrap loading">
                            <div class="item-banner lazy" data-lazy-id=<?php echo $lazyID; ?>>
                            <?php Utils::registerLazyImg($banner, $lazyID); ?>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class="lazy-wrap">
                            <div class="item-banner lazy loaded" style="background-image:url(<?php echo $banner; ?>)">
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                        <div class="lazy-wrap">
                            <div class="item-banner lazy loaded" style="background: white">
                            </div>
                        </div>
                <?php endif; ?>
                <div class="item-content">
                    <span>
                        <span hidden itemprop="author"><?php $this->author(); ?></span>
                        <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>   <!-- date -->
                    </span>
                    <h1 itemprop="name"><?php if(Utils::isPluginAvailable('Sticky')) $this->sticky(); $this->title(); ?></h1>
                    <p><?php if($this->fields->excerpt!='') echo $this->fields->excerpt; else $this->excerpt(30); ?></p>
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

<?php }
if(!Utils::isPjax() && !Utils::isAjax()){
    $this->need('includes/footer.php');
} 
?>