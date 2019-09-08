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
$setting = $GLOBALS['VOIDSetting'];
 
if(!Utils::isPjax()){
    $this->need('includes/head.php');
    $this->need('includes/header.php');
} 
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    
    <?php $this->need('includes/banner.php'); ?>

    <div class="wrapper container narrow">
        <div class="tag-cloud yue float-up">
            <h2>Tags</h2>
            <?php $this->widget('Widget_Metas_Tag_Cloud', 'sort=count&ignoreZeroCount=1&desc=1&limit=50')->to($tags); ?>
            <?php if($tags->have()): ?>
            <?php while ($tags->next()): ?>
                <a href="<?php $tags->permalink(); ?>" rel="tag" class="tag-item btn btn-normal btn-narrow" title="<?php $tags->count(); ?> 个话题"><?php $tags->name(); ?></a>
            <?php endwhile; ?>
            <?php else: ?>
                <?php echo('还没有标签哦～'); ?>
            <?php endif; ?>
        </div>
        <section id="archive-list" class="yue float-up">
            <?php $archives = Contents::archives($this); $index = 0; foreach ($archives as $year => $posts): ?>
                <h2><?php echo $year; ?>
                    <span class="num-posts"><?php $post_num = count($posts); echo $post_num; ?> 篇</span>
                    <a no-pjax target="_self" data-num="<?php echo $post_num; ?>" 
                        data-year="<?php echo $year; ?>" 
                        class="toggle-archive" href="javascript:void(0);" 
                        onclick="VOID_Ui.toggleArchive(this); return false;"><?php if($index > 0) echo '+'; else echo '-'; ?>
                    </a>
                </h2>
                <section id="year-<?php echo $year; ?>" 
                    class="year<?php if($index > 0) echo ' shrink'; ?>" 
                    style="max-height: <?php if($index > 0) echo '0'; else echo $post_num*49; ?>px; transition-duration: <?php echo $post_num * 0.03 > 0.8 ? 0.8:$post_num * 0.03; ?>s">
                    <ul>
                    <?php foreach($posts as $created => $post): ?>
                        <li>
                            <a class="archive-title<?php if($setting['VOIDPlugin']) echo ' show-word-count'; ?>" 
                                data-words="<?php if($setting['VOIDPlugin']) echo $post['words']; ?>" 
                                href="<?php echo $post['permalink']; ?>">
                                <span class="date"><?php echo date('m-d', $created); ?></span><?php echo $post['title']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </section>
            <?php $index = $index + 1; endforeach; ?>
        </section>
    </div>
</main>

<?php 
if(!Utils::isPjax()){
    $this->need('includes/footer.php');
} 
?>