<?php
/**
 * main.php
 * 
 * 内容页面主要区域，PJAX 作用区域
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
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
        <?php Utils::registerLazyImg($this->fields->banner != '' ? $this->fields->banner : $defaultBanner, $lazyID); ?>
    <?php else: ?>
        <div class="lazy-wrap"><div id="banner" style="background-image:url(<?php echo $this->fields->banner != '' ? $this->fields->banner : $defaultBanner; ?>)" class="lazy loaded"></div></div>
    <?php endif; ?>

    <div class="wrapper container">
        <section id="post">
            <article class="post yue">
                <h1 class="post-title"><?php $this->title(); ?>
                    <?php if($this->user->hasLogin()): ?>
                        <sup>
                            <?php if($this->is('post')): ?>
                            <a class="edit-button" target="_blank" href="<?php echo $this->options->adminUrl.'write-post.php?cid='.$this->cid;?>">编辑</a>
                            <?php else: ?>
                            <a class="edit-button" target="_blank" href="<?php echo $this->options->adminUrl.'write-page.php?cid='.$this->cid;?>">编辑</a>
                            <?php endif;?>
                        </sup>    
                    <?php endif;?>
                </h1>
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
                <?php $postCheck = Utils::isOutdated($this); if($postCheck["is"] && $this->is('post')): ?>
                <p class="notice">请注意，本文编写于 <?php echo $postCheck["created"]; ?> 天前，最后修改于 <?php echo $postCheck["updated"]; ?> 天前，其中某些信息可能已经过时。</p>
                <?php endif; ?>
                <?php 
                    $content = Contents::parseAll($this->content, $this->fields->showTOC == '1');
                    if($this->is('page')) $content = Contents::parseBoard($content);
                    echo $content['content'];
                ?>
                <div id="social">
                    <?php if(Utils::isPluginAvailable('Like')):?>
                        <a href="javascript:;" data-pid="<?php echo $this->cid;?>" class="btn btn-normal post-like">ENJOY <span class="like-num"><?php Like_Plugin::theLike($link = false,$this);?></span></a>
                    <?php endif; ?>
                </div>
            </article>
            <!--目录，可选-->
            <?php if($this->fields->showTOC == '1'): ?>
                    <div class="TOC"><?php echo $content['toc']; ?></div>
                    <div class="toggle-toc"><a class="toggle" href="javascript:void(0);" onclick="toggleToc(this);"><span></span></a>
                    </div>
            <?php endif; ?>
            <!--分页-->
            <?php if(!$this->is('page')): ?>
            <div class="post-pager">
                <?php Contents::thePrev($this); ?>
                <?php Contents::theNext($this); ?>
            </div>
            <?php endif; ?>
        </section>

        <!--评论区，可选-->
        <?php if ($this->allow('comment')) $this->need('includes/comments.php'); ?>
    </div>
</main>