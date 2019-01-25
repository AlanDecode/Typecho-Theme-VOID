<?php
/**
 * VOID：无类型
 * 
 * 作者：<a href="https://www.imalan.cn">熊猫小A</a>
 * 
 * @package     Typecho-Theme-VOID
 * @author      熊猫小A
 * @version     1.0
 * @link        https://blog.imalan.cn/archives/247/
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

<?php 
if(!Utils::isPjax()){
    $this->need('includes/head.php');
    $this->need('includes/header.php');
} 
?>

<?php 
// load banner and cover
$defaultBanner = $this->options->defaultBanner;
$defaultCover = $this->options->defaultCover != '' ? $this->options->defaultCover : $defaultBanner;
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>

    <?php if(!Utils::isWeixin()): ?>
        <?php $lazyID = rand(1,10000); ?>
        <div class="lazy-wrap loading"><div id="banner" data-lazy-id=<?php echo $lazyID; ?> class="lazy"></div></div>
        <?php Utils::registerLazyImg($defaultBanner.'?v='.rand(), $lazyID); ?>
    <?php else: ?>
        <div class="lazy-wrap"><div id="banner" style="background-image:url(<?php echo $defaultBanner; ?>)" class="lazy loaded"></div></div>
    <?php endif; ?>

    <div class="wrapper container">
        <?php if(!$this->is('archive')): ?>
        <?php $this->next(); ?>
        <section id="new">
            <div class="section-title">LATEST</div>
            <a class="item" href="<?php $this->permalink(); ?>">
                <div class="item-content">
                    <h1><?php $this->title(); ?></h1>
                    <p class=post-meta>
                        <span><?php $this->author(); ?></span>&nbsp;•&nbsp;   <!-- author -->
                        <span><?php echo date('Y-m-d', $this->created); ?></span>   <!-- date -->
                    </p>
                    <p><?php $this->excerpt(90); ?></p>
                    <div class="btn btn-normal">READ MORE </div>
                </div>
                <?php if(!Utils::isWeixin()): ?>
                    <?php $lazyID = rand(1,10000); ?>
                    <div class="lazy-wrap loading"><div class="item-banner lazy" data-lazy-id=<?php echo $lazyID; ?>></div></div>
                    <?php Utils::registerLazyImg($this->fields->banner != '' ? $this->fields->banner : $defaultCover.'?v='.rand(), $lazyID); ?>
                <?php else: ?>
                    <div class="lazy-wrap"><div class="item-banner lazy loaded" style="background-image:url(<?php echo $this->fields->banner != '' ? $this->fields->banner : $defaultCover.'?v='.rand(); ?>)"></div></div>
                <?php endif; ?>
            </a>
        </section>
        <?php endif; ?>
        <section id="post-list" <?php if($this->is('archive')) echo 'class="archive-list"'; ?>>
            <div class="section-title">RECENT</div>
            <?php if($this->have()): ?>
            <?php while($this->next()): ?>
            <a class="item" href="<?php $this->permalink(); ?>">
                <?php $lazyID = rand(1,10000); ?>
                <?php if(!Utils::isWeixin()): ?>
                    <div class="lazy-wrap loading">
                        <div class="item-banner lazy" data-lazy-id=<?php echo $lazyID; ?>>
                        <?php Utils::registerLazyImg($this->fields->banner != '' ? $this->fields->banner : $defaultCover.'?v='.rand(), $lazyID); ?>
                            <div class="item-meta">
                            <span><?php $this->excerpt(120); ?></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="lazy-wrap">
                        <div class="item-banner lazy loaded" style="background-image:url(<?php echo $this->fields->banner != '' ? $this->fields->banner : $defaultCover.'?v='.rand(); ?>)">
                            <div class="item-meta">
                            <span><?php $this->excerpt(120); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="item-content">
                    <h1><?php $this->title(); ?></h1>
                    <p>
                        <span><?php echo date('Y-m-d', $this->created); ?></span>   <!-- date -->
                        <span>  <!-- statics -->
                            <?php if(Utils::isPluginAvailable('TePostViews') && !$this->is('archive')): ?>
                                <?php $this->viewsNum(); ?>&nbsp;阅读&nbsp;•&nbsp;
                            <?php endif;?>
                            <?php $this->commentsNum(); ?>&nbsp;评论
                        </span>
                    </p>
                </div>
            </a>
            <?php endwhile;?>
            <?php else: ?>
            <div class="not-found">
                <h1>糟糕！是 404 的感觉</h1>
                <input onkeydown="enterSearch(this);" type="text" name="search-content" id="search_404" class="text" required placeholder="Try search..." />
                <p><a href="<?php Utils::indexHome('/'); ?>">← 返回首页</a></p>
            </div>
            <?php endif; ?>
        </section>
        <?php $this->pageNav('←', '→', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
    </div>
</main>

<?php 
if(!Utils::isPjax()){
    $this->need('includes/footer.php');
} 
?>