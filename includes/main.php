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

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    <?php $lazyID = rand(1,10000); ?>
    <div class="lazy-wrap loading"><div id="banner" class="lazy" data-lazy-id=<?php echo $lazyID; ?>></div></div>
    <?php Utils::registerLazyImg($this->fields->banner != '' ? $this->fields->banner : $this->options->defaultBanner.'?v='.rand(), $lazyID); ?>
    <div class="wrapper container">
        <section id="post">
            <div class="section-title"><?php if($this->is('post')) echo 'POST'; else echo 'PAGE'; ?></div>
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
                <?php 
                    $content = Contents::parseAll($this->content, $this->fields->showTOC == '1');
                    if($this->is('page')) $content = Contents::parseBoard($content);
                    echo $content['content'];
                ?>
                <div id="social">
                    <?php if(Utils::isPluginAvailable('Like')):?>
                        <a href="javascript:;" data-pid="<?php echo $this->cid;?>" class="btn btn-normal post-like">ENJOY <span class="like-num"><?php Like_Plugin::theLike($link = false,$this);?></span></a>
                    <?php endif; ?>
                    <!--link rel="stylesheet" href="https://lab.lepture.com/social/dist/widget.css">
                    <div class="social-button" data-twitter="AlanDecode"
                        data-facebook="lepture" data-weibo="熊猫小A"
                        data-count="true" data-text="<?php Contents::title($this); ?>"
                        data-url="<?php $this->permalink(); ?>"></div>
                    <script src="https://lab.lepture.com/social/dist/widget.js"></script-->
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