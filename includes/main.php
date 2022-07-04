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

    <?php $this->need('includes/ldjson.php'); ?>
    <?php $this->need('includes/banner.php'); ?>

    <div class="wrapper container">
        <div class="contents-wrap"> <!--start .contents-wrap-->
            <section id="post" class="float-up">
                <article class="post yue">

                    <?php $postCheck = Utils::isOutdated($this); if($postCheck["is"] && $this->is('post')): ?>
                        <p class="notice">请注意，本文编写于 <?php echo $postCheck["created"]; ?> 天前，最后修改于 <?php echo $postCheck["updated"]; ?> 天前，其中某些信息可能已经过时。</p>
                    <?php endif; ?>

                    <div class="articleBody" class="full">
                        <?php $this->content(); ?>
                    </div>
                    
                    <?php $tags = Contents::getTags($this->cid); if (count($tags) > 0) { 
                        echo '<section class="tags">';
                        foreach ($tags as $tag) {
                            echo '<a href="'.$tag['permalink'].'" rel="tag" class="tag-item">'.$tag['name'].'</a>';
                        }
                        echo '</section>';
                    } ?>

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

                <script>
                (function () {
                    $.each($('iframe'), function(i, item){
                        var src = $(item).attr('src');
                        if (typeof src === 'string' && src.indexOf('player.bilibili.com') > -1) {
                            // $(item).addClass('bili-player');
                            // if (src.indexOf('&high_quality') < 0) {
                            //     src += '&high_quality=1'; // 启用高质量
                            //     $(item).attr('src', src);
                            // }
                            $(item).wrap('<div class="bili-player"></div>');
                        }
                    });
                })();
                </script>

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
