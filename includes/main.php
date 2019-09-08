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
if($this->fields->bannerStyle > 0) {
    $setting['bannerStyle'] = $this->fields->bannerStyle-1;
}
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>

    <?php $this->need('includes/banner.php'); ?>

    <div class="wrapper container">
        <div class="contents-wrap"> <!--start .contents-wrap-->
            <section id="post" class="float-up">
                <article class="post yue" itemscope itemtype="http://schema.org/Article">
                    <h1 hidden itemprop="name"><?php $this->title(); ?></h1>
                    <span hidden itemprop="author"><?php $this->author(); ?></span>
                    <time hidden datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>

                    <p <?php if($this->fields->excerpt=='' || !$setting['showHeadlineInPost']) echo 'hidden'?> class="headline" itemprop="headline"><?php if($this->fields->excerpt!='') echo $this->fields->excerpt; else $this->excerpt(30); ?></p>

                    <?php if($this->fields->banner != ''): ?>
                        <div <?php if($setting['bannerStyle'] == 0 || $setting['bannerStyle'] == 2 || $setting['bannerStyle'] == 4 || $this->is('page')) echo 'hidden'; ?> class="post-banner" itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                            <a no-pjax data-fancybox="gallery" href="<?php echo $this->fields->banner; ?>">
                            <?php if(Helper::options()->lazyload == '1'): ?>
                                <img class="lazyload" src="<?php echo Contents::getPlaceHolder(); ?>" data-src="<?php echo $this->fields->banner; ?>" />
                            <?php else: ?>
                                <img src="<?php echo $this->fields->banner; ?>" />
                            <?php endif; ?>                            
                            </a>
                            <meta itemprop="url" content="<?php echo $this->fields->banner; ?>">
                        </div>
                    <?php endif; ?>

                    <?php $postCheck = Utils::isOutdated($this); if($postCheck["is"] && $this->is('post')): ?>
                        <p class="notice">请注意，本文编写于 <?php echo $postCheck["created"]; ?> 天前，最后修改于 <?php echo $postCheck["updated"]; ?> 天前，其中某些信息可能已经过时。</p>
                    <?php endif; ?>

                    <div itemprop="articleBody" class="full">
                        <?php $this->content(); ?>
                    </div>
                    
                    <?php $tags = Contents::getTags($this->cid); if (count($tags) > 0) { 
                        echo '<section class="tags">';
                        foreach ($tags as $tag) {
                            echo '<a href="'.$tag['permalink'].'" rel="tag" class="tag-item btn btn-normal btn-narrow">'.$tag['name'].'</a>';
                        }
                        echo '</section>';
                    } ?>

                    <div hidden itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
                        <meta itemprop="name" content="<?php echo $this->options->title; ?>">
                        <meta itemprop="url" content="<?php $this->options->siteUrl(); ?>">
                        <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                            <meta itemprop="url" content="<?php Utils::gravatar($this->author->mail, 256, ''); ?>">
                        </div>
                    </div>
                    <meta itemscope="" itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php $this->permalink(); ?>">
                    <meta itemprop="dateModified" content="<?php echo date('c', $this->modified); ?>">
                    <div class="social-button" 
                        data-url="<?php $this->permalink(); ?>"
                        data-title="<?php Contents::title($this); ?>" 
                        data-excerpt="<?php $this->fields->excerpt(); ?>"
                        data-img="<?php $this->fields->banner(); ?>" 
                        data-twitter="<?php if($setting['twitterId']!='') echo $setting['twitterId']; else $this->author(); ?>"
                        data-weibo="<?php if($setting['weiboId']!='') echo $setting['weiboId']; else $this->author(); ?>"
                        <?php if($this->fields->banner != '') echo 'data-image="'.$this->fields->banner.'"';?>>
                        <?php if(!empty($setting['reward'])):?>
                            <a data-fancybox="gallery-reward" role=button aria-label="赞赏" data-src="#reward" href="javascript:;" class="btn btn-normal btn-highlight">赏杯咖啡</a>
                            <div hidden id="reward"><img src="<?php echo $setting['reward']; ?>"></div>
                        <?php endif; ?>
                        <?php if($setting['VOIDPlugin']):?>
                            <a role=button 
                                aria-label="为文章点赞" 
                                id="social" 
                                href="javascript:void(0);" onclick="VOID_Vote.vote(this);" 
                                data-item-id="<?php echo $this->cid;?>" 
                                data-type="up"
                                data-table="content"
                                class="btn btn-normal post-like vote-button"
                            >ENJOY <span class="value"><?php echo $this->likes; ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <a aria-label="分享到微博" href="javascript:void(0);" onclick="Share.toWeibo(this);" class="social-button-icon"><i class="voidicon-weibo"></i></a>
                        <a aria-label="分享到Twitter" href="javascript:void(0);" onclick="Share.toTwitter(this);" class="social-button-icon"><i class="voidicon-twitter"></i></a>
                    </div>
                </article>
                
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
        </div> <!--end .contents-wrap-->
        <!--目录，可选-->
        <?php if($this->fields->showTOC == '1'): ?>
            <div class="toc-mask" onclick="TOC.close();"></div>
            <div aria-label="文章目录" class="TOC"></div>
            <style>
            #toggle-toc { display: block; }
            </style>
        <?php endif;?>
    </div>
    <!--评论区，可选-->
    <?php $this->need('includes/comments.php'); ?>
</main>
