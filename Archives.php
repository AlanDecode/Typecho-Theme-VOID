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
    
    <?php $this->need('includes/banner.php'); ?>

    <div class="wrapper container">
        <section id="archive-list" class="yue">
            <h1 <?php if($setting['titleinbanner']) echo 'hidden'; ?> class="post-title"><?php $this->title(); ?></h1>
            <?php if(!$setting['titleinbanner']): ?>
            <p class="post-meta">
                <?php 
                    echo Utils::getCatNum()." 分类 × ".Utils::getPostNum()." 文章 × ".Utils::getTagNum()." 标签 × ".Utils::getWordCount()." 字";
                ?>
            </p>
            <?php endif; ?>
            <?php $archives = Contents::archives(); $index = 0; foreach ($archives as $year => $posts): ?>
                <section aria-label="<?php echo $year; ?>年归档列表"  class="year<?php if($index > 0) echo ' shrink'; ?>" data-year="<?php echo $year; ?>" data-num="<?php echo count($posts); ?>">
                    <ul>
                <?php foreach($posts as $created => $post): ?>
                        <li data-date="<?php echo date('m-d', $created); ?>"><a class="archive-title" data-words="<?php echo $post['words']; ?>" href="<?php echo $post['permalink']; ?>"><?php echo $post['title']; ?></a></li>
                <?php endforeach; ?>
                    </ul>
                    <a role=button aria-label="收起与展开列表" class="toggle-archive" target="_self" href="javascript:void(0);" onclick="VOID.toggleArchive(this);"><?php if($index > 0) echo '+'; else echo '-'; ?></a>
                </section>
            <?php $index = $index + 1; endforeach; ?>
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