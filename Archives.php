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
                    <?php $this->author(); ?>&nbsp;•&nbsp;
                    <?php echo date('Y-m-d', $this->created); ?>&nbsp;•&nbsp;
                    <a href="#comments"><?php $this->commentsNum(); ?>&nbsp;评论</a>
                    <?php 
                        if(Utils::isPluginAvailable('TePostViews'))
                        {
                            echo '&nbsp;•&nbsp;';
                            $this->viewsNum();
                            echo '&nbsp;阅读';
                        }
                    ?>
                </p>
                <div id="archives">
                    <?php $this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);?>
                    <ol>
                    <?php while($archives->next()): ?>
                    <li>
                        <div class="meta">
                            <span class="date"><?php echo date('M d\<\s\p\a\n\> l\<\/\s\p\a\n\>',$archives->created); ?></span>
                            <span class="year"><?php echo date('Y',$archives->created); ?></span>
                        </div>
                        <div class="content">
                            <span class="title"><a href="<?php $archives->permalink(); ?>"><?php $archives->title(); ?></a></span>
                        </div>
                    </li>
                    <?php endwhile; ?>
                    </ol>
                </div>
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