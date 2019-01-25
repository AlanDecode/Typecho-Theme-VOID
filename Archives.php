<?php
/** 
 * Archives
 *
 * @package custom
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
        <?php Utils::registerLazyImg($this->fields->banner != '' ? $this->fields->banner : $defaultBanner.'?v='.rand(), $lazyID); ?>
    <?php else: ?>
        <div class="lazy-wrap"><div id="banner" style="background-image:url(<?php echo $this->fields->banner != '' ? $this->fields->banner : $defaultBanner.'?v='.rand(); ?>)" class="lazy loaded"></div></div>
    <?php endif; ?>

    <div class="wrapper container">
        <section id="post">
            <article class="post yue">
                <h1 class="post-title"><?php $this->title(); ?></h1>
                <p class="post-meta">
                    <?php 
                        echo Utils::getCatNum()." 分类 × ".Utils::getPostNum()." 文章 × ".Utils::getTagNum()." 标签 × ".Utils::getWordCount()." 字";
                    ?>
                </p>
                <?php $archives = Contents::archives(); $index = 0; foreach ($archives as $year => $posts): ?>
                    <section  class="year archives <?php if($index > 0) echo 'shrink'; ?>" data-year="<?php echo $year; ?>" data-num="<?php echo count($posts); ?>">
                        <ul>
                    <?php foreach($posts as $created => $post): ?>
                            <li data-date="<?php echo date('m-d', $created); ?>"><a data-words="<?php echo $post['words']; ?>" href="<?php echo $post['permalink']; ?>"><?php echo $post['title']; ?></a></li>
                    <?php endforeach; ?>
                        </ul>
                        <a class="toggle-archive" target="_self" href="javascript:void(0);" onclick="VOID.toggleArchive(this);"><?php if($index > 0) echo '+'; else echo '-'; ?></a>
                    </section>
                <?php $index = $index + 1; endforeach; ?>
            </article>
        </section>

        <!--评论区，可选-->
        <?php if ($this->allow('comment')) $this->need('includes/comments.php'); ?>
    </div>
</main>

<?php 
if(!Utils::isPjax()){
    $this->need('includes/footer.php');
} 
?>