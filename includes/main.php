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
$setting = $GLOBALS['VOIDSetting'];
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>

    <?php $this->need('includes/banner.php'); ?>

    <div class="wrapper container">
        <section id="post">
            <article class="post yue" itemscope itemtype="http://schema.org/Article">
                <h1 <?php if($setting['titleinbanner']) echo 'hidden'; ?> itemprop="name" class="post-title"><?php $this->title(); ?></h1>
                <p <?php if($setting['titleinbanner']) echo 'hidden'; ?> class="post-meta">
                    <span itemprop="author"><?php $this->author(); ?></span>&nbsp;•&nbsp;
                    <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>
                    &nbsp;•&nbsp;
                    <a href="#comments"><?php $this->commentsNum(); ?>&nbsp;评论</a>
                    <?php 
                        if(Utils::isPluginAvailable('TePostViews'))
                        {
                            echo '&nbsp;•&nbsp;';
                            $this->viewsNum();
                            echo '&nbsp;阅读';
                        }
                    ?>
                    <?php if($this->user->hasLogin()): ?>
                        <?php if($this->is('post')): ?>
                        &nbsp;•&nbsp;<a target="_blank" href="<?php echo $this->options->adminUrl.'write-post.php?cid='.$this->cid;?>">编辑</a>
                        <?php else: ?>
                        &nbsp;•&nbsp;<a target="_blank" href="<?php echo $this->options->adminUrl.'write-page.php?cid='.$this->cid;?>">编辑</a>
                        <?php endif;?>
                    <?php endif;?>
                </p>
                <?php $postCheck = Utils::isOutdated($this); if($postCheck["is"] && $this->is('post')): ?>
                <p class="notice">请注意，本文编写于 <?php echo $postCheck["created"]; ?> 天前，最后修改于 <?php echo $postCheck["updated"]; ?> 天前，其中某些信息可能已经过时。</p>
                <?php endif; ?>
                <p <?php if($this->fields->excerpt=='') echo 'hidden'?> class="headline" itemprop="headline"><?php if($this->fields->excerpt!='') echo $this->fields->excerpt; else $this->excerpt(30); ?></p>
                <div itemprop="articleBody">
                <?php $this->content(); ?>
                </div>
                <?php if($this->fields->banner != ''): ?>
                <div hidden itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                    <meta itemprop="url" content="<?php echo $this->fields->banner; ?>">
                </div>
                <?php endif; ?>
                <div hidden itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
                    <meta itemprop="name" content="<?php echo $this->options->title; ?>">
                    <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url" content="<?php Utils::gravatar($this->author->mail, 256, ''); ?>">
                    </div>
                </div>
                <meta itemscope="" itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php $this->permalink(); ?>">
                <meta itemprop="dateModified" content="<?php echo date('c', $this->modified); ?>">
                <div class="social-button" 
                    data-twitter="<?php if($setting['twitterId']!='') echo $setting['twitterId']; else $this->author(); ?>"
                    data-weibo="<?php if($setting['weiboId']!='') echo $setting['weiboId']; else $this->author(); ?>"
                    data-text="<?php $this->title(); ?>"
                    data-url="<?php $this->permalink(); ?>"
                    <?php if($this->fields->banner != '') echo 'data-image="'.$this->fields->banner.'"';?>>
                    <?php if(Utils::isPluginAvailable('Like')):?>
                        <a role=button aria-label="点赞" id="social" href="javascript:;" data-pid="<?php echo $this->cid;?>" class="btn btn-normal post-like">ENJOY <span class="like-num"><?php Like_Plugin::theLike($link = false,$this);?></span></a>
                    <?php endif; ?>
                </div>
            </article>
            <!--目录，可选-->
            <?php if($this->fields->showTOC == '1'): ?>
                    <div aria-label="文章目录" class="TOC"></div>
                    <div role=button aria-label="展开或关闭文章目录" class="toggle-toc"><a target="_self" class="toggle" href="javascript:void(0);" onclick="toggleToc(this);"><span></span></a>
                    </div>
            <?php endif; ?>
            <!--分页-->
            <?php if(!$this->is('page')): ?>
            <div class="post-pager"><?php $prev = Contents::thePrev($this); $next = Contents::theNext($this); ?>
                <?php if($prev): ?>
                    <div class="prev">
                        <a href="<?php $prev->permalink(); ?>"><h2><?php $prev->title(); ?></h2></a>
                        <?php echo $prev->fields->excerpt != '' ? "<p>{$prev->fields->excerpt}</p>" : ''; ?>
                    </div>
                <?php else: ?>
                    <div class="prev">
                        <h2>没有了</h2>
                    </div>
                <?php endif; ?>
                <?php if($next): ?>
                    <div class="next">
                        <a href="<?php $next->permalink(); ?>"><h2><?php $next->title(); ?></h2></a>
                        <?php echo $next->fields->excerpt != '' ? "<p>{$next->fields->excerpt}</p>" : ''; ?>
                    </div>
                <?php else: ?>
                    <div class="next">
                        <h2>没有了</h2>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </section>
        <!--评论区，可选-->
        <?php if ($this->allow('comment')) $this->need('includes/comments.php'); ?>
    </div>
</main>