<?php
/** 
 * archives
 *  
 * @author      熊猫小A
 * @version     2019-01-17 0.1
 * 
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
        <section id="post" style="margin-bottom:3rem;">
            <article class="post yue">
                <h1 class="post-title"><?php  $this->archiveTitle(array(
                        'category'  =>  _t('分类 "%s" 下的文章'),
                        'search'    =>  _t('包含关键字 "%s" 的文章'),
                        'tag'       =>  _t('包含标签 "%s" 的文章'),
                        'author'    =>  _t('"%s" 发布的文章')
                    ), '', '');  ?></h1>
                <section class="archives detail">
                    <ul>
                    <?php while($this->next()): ?>
                    <li data-date="<?php echo date('m-d', $this->created); ?>" >
                        <a href="<?php $this->permalink(); ?>"
                            data-words="<?php echo mb_strlen(preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $this->content), 'UTF-8'); ?>">
                            <h4><?php $this->title(); ?></h4>
                        </a>
                        <p><?php $this->excerpt(100); ?></p>
                    </li>
                    <?php endwhile; ?>
                    </ul>
                </section>
            </article>
        </section>
        <?php $this->pageNav('←', '→', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
    </div>
</main>

<?php 
if(!Utils::isPjax()){
    $this->need('includes/footer.php');
} 
?>