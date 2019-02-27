<?php
/**
 * VOID：无类型
 * 
 * 作者：<a href="https://www.imalan.cn">熊猫小A</a>
 * 
 * @package     Typecho-Theme-VOID
 * @author      熊猫小A
 * @version     1.6
 * @link        https://blog.imalan.cn/archives/247/
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
?>

<?php 
if(!Utils::isPjax() && !Utils::isAjax()){
    $this->need('includes/head.php');
    $this->need('includes/header.php');
} 
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    <?php $this->pageLink('','next'); ?>
    <?php $this->need('includes/banner.php'); ?>

    <div class="wrapper container wide">
        <section id="post-list" aria-label="最近文章列表">
            <?php while($this->next()): //直接显示摘要是默认选项 ?>
            <a class="item <?php if($this->fields->banner == '' && empty($setting['defaultCover'])) echo 'no-banner'; ?> <?php if(Utils::isAjax()) echo 'ajax'; ?>" href="<?php $this->permalink(); ?>" aria-label="最近文章" itemscope="" itemtype="http://schema.org/BlogPosting">
                <?php 
                    if($this->fields->banner != ''){
                        Contents::exportCover($this, $this->fields->banner, false);
                    }else{
                        if(!empty($setting['defaultCover'])){
                            Contents::exportCover($this, $setting['defaultCover'], true);
                        }else{ ?>
                <div class="lazy-wrap">
                    <div class="item-banner lazy loaded" style="background: white">
                    </div>
                </div>
                       <?php }
                    } 
                ?>
                <div class="item-content">
                    <span>
                        <span hidden itemprop="author"><?php $this->author(); ?></span>
                        <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>   <!-- date -->
                    </span>
                    <h1 itemprop="name"><?php if(Utils::isPluginAvailable('Sticky')) $this->sticky(); $this->title(); ?></h1>
                    <p><?php $this->excerpt(30); ?></p>
                </div>
                <?php if($this->fields->banner != ''): ?>
                <div hidden itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                    <meta itemprop="url" content="<?php echo $this->fields->banner; ?>">
                </div>
                <?php endif; ?>
                <div hidden itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
                    <meta itemprop="name" content="<?php echo $this->options->title; ?>">
                    <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url" content="<?php Utils::gravatar($this->author->email, 256, ''); ?>">
                    </div>
                </div>
                <meta itemscope="" itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php $this->permalink(); ?>">
                <meta itemprop="dateModified" content="<?php echo date('c', $this->modified); ?>">
            </a>
            <?php endwhile;?>
        </section>
        <?php if(!$setting['ajaxIndex']): ?>
            <?php $this->pageNav('<span aria-label="上一页">←</span>', '<span aria-label="下一页">→</span>', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
        <?php else: ?>
            <ol class="pager"><li class="current"><a class="ajax-Load" style="width:unset" href="javascript:void(0)" onclick="VOID.ajaxLoad();" no-pjax target="_self">加载更多</a></li></ol>
        <?php endif; ?>
    </div>
</main>

<?php 
if(!Utils::isPjax() && !Utils::isAjax()){
    $this->need('includes/footer.php');
} 
?>