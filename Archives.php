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

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    <?php $lazyID = rand(1,10000); ?>
    <div class="lazy-wrap loading"><div id="banner" class="lazy" data-lazy-id=<?php echo $lazyID; ?>></div></div>
    <?php Utils::registerLazyImg($this->fields->banner != '' ? $this->fields->banner : $this->options->defaultBanner.'?&v='.rand(), $lazyID); ?>
    <div class="wrapper container">
        <section id="post">
            <div class="section-title"><?php if($this->is('post')) echo 'POST'; else echo 'PAGE'; ?></div>
            <article class="post yue">
                <h1 class="post-title"><?php $this->title(); ?></h1>
                <p class="post-meta">
                    <?php $this->author(); ?>&nbsp;•&nbsp;
                    <?php echo date('Y-m-d', $this->created); ?>&nbsp;•&nbsp;
                    <a href="#comments"><?php $this->commentsNum(); ?>&nbsp;Comments</a>
                    <?php 
                        if(Utils::isPluginAvailable('TePostViews'))
                        {
                            echo '&nbsp;•&nbsp;';
                            $this->viewsNum();
                            echo '&nbsp;Views';
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