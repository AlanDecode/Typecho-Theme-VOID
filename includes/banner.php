<?php
/** 
 * banner.php
 *  
 * @author      ç†ŠçŒ«å°�A
 * @version     2019-01-17 0.1
 * 
*/ 
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
?>

<?php if(!Utils::isWeixin()): ?>
    <?php $lazyID = rand(1,10000); ?>
    <div class="lazy-wrap loading <?php if($setting['titleinbanner'] && !$this->is('index')) echo 'dark'; ?>">
        <div id="banner" data-lazy-id=<?php echo $lazyID; ?> class="lazy"></div>
        <?php 
            if($this->is('post') || $this->is('page')){
                Utils::registerLazyImg($this->fields->banner != '' ? $this->fields->banner : $setting['defaultBanner'], $lazyID); 
            }else{
                Utils::registerLazyImg($setting['defaultBanner'], $lazyID);
            }
        ?>
<?php else: ?>
    <div class="lazy-wrap <?php if($setting['titleinbanner'] && !$this->is('index')) echo 'dark'; ?>">
        <div id="banner" style="background-image:url(<?php echo $this->fields->banner != '' ? $this->fields->banner : $setting['defaultBanner']; ?>)" class="lazy loaded"></div>
<?php endif; ?>
    <?php if($setting['titleinbanner'] && !$this->is('index')): ?>
        <div class="banner-title">
            <h1 class="post-title">
                <?php if(!$this->is('archive')): ?>
                    <?php $this->title(); ?>
                    <?php if($this->user->hasLogin()): ?>
                        <sup>
                            <?php if($this->is('post')): ?>
                            <a class="edit-button" target="_blank" href="<?php echo $this->options->adminUrl.'write-post.php?cid='.$this->cid;?>">编辑</a>
                            <?php else: ?>
                            <a class="edit-button" target="_blank" href="<?php echo $this->options->adminUrl.'write-page.php?cid='.$this->cid;?>">编辑</a>
                            <?php endif;?>
                        </sup>    
                    <?php endif;?>
                <?php else: ?>
                    <?php $this->archiveTitle(array(
                            'category'  =>  _t('分类 "%s" 下的文章'),
                            'search'    =>  _t('包含关键字 "%s" 的文章'),
                            'tag'       =>  _t('包含标签 "%s" 的文章'),
                            'author'    =>  _t('"%s" 发布的文章')
                        ), '', '');  ?>
                <?php endif;?>
            </h1>
            <?php if(!$this->is('archive')): ?>
                <p class="post-meta">
                    <?php if($this->template == 'Archives.php') 
                        echo Utils::getCatNum()." 分类 × ".Utils::getPostNum()." 文章 × ".Utils::getTagNum()." 标签 × ".Utils::getWordCount()." 字";
                    else{ ?>
                        <span itemprop="author"><?php $this->author(); ?></span>&nbsp;•&nbsp;
                        <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>
                        &nbsp;•&nbsp;<?php $this->commentsNum(); ?>&nbsp;评论
                        <?php 
                            if(Utils::isPluginAvailable('TePostViews'))
                            {
                                echo '&nbsp;•&nbsp;';
                                $this->viewsNum();
                                echo '&nbsp;阅读';
                            }
                        ?>
                    <?php } ?>
                </p>
            <?php endif;?>
        </div>
    <?php endif; ?>
</div>