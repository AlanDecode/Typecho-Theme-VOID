<?php
/**
 * archives.php
 * 
 * 搜索、分类、标签等页面
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
        <section id="index-list">
            <h1 <?php if($setting['titleinbanner']) echo 'hidden'; ?> class="post-title"><?php $this->archiveTitle(array(
                'category'  =>  _t('分类 "%s" 下的文章'),
                'search'    =>  _t('包含关键字 "%s" 的文章'),
                'tag'       =>  _t('包含标签 "%s" 的文章'),
                'author'    =>  _t('"%s" 发布的文章')
            ), '', '');  ?></h1>
            <ul>
            <?php while($this->next()): ?>
                <li>
                    <article class="yue" itemscope itemtype="http://schema.org/Article">
                        <div hidden itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                            <meta itemprop="url" content="<?php if($this->fields->banner != '') echo $this->fields->banner; else Utils::gravatar($this->author->mail, 200);  ?>">
                        </div>
                        <a class="title" href="<?php $this->permalink(); ?>">
                            <h1 itemprop="name" data-words="<?php echo mb_strlen(preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $this->content), 'UTF-8'); ?>"><?php $this->title(); ?></h1>
                        </a>
                        <?php if($this->fields->excerpt != ''): ?> 
                            <p itemprop="headline" class="headline single"><?php echo $this->fields->excerpt; ?></p>
                        <?php else: ?>
                            <p class="excerpt" <?php if($this->fields->excerpt == '') echo 'itemprop="headline"'; ?>><?php if(Utils::isMobile()) $this->excerpt(60); else $this->excerpt(100); ?><?php if($this->is('index')) echo " | <a class=\"full-link\" href=\"{$this->permalink}\">阅读全文</a>"; ?></p>
                        <?php endif; ?>
                        <meta itemprop="author" content="<?php $this->author(); ?>">
                        <meta itemprop="datePublished" content="<?php echo date('c', $this->created); ?>">
                        <meta itemprop="dateModified" content="<?php echo date('c', $this->modified); ?>">
                        <meta itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php $this->permalink(); ?>">
                        <div hidden itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
                            <meta itemprop="name" content="<?php $this->options->title(); ?>">
                            <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                                <meta itemprop="url" content="<?php Utils::gravatar($this->author->mail, 200);  ?>">
                            </div>
                        </div>
                    </article>
                </li>
            <?php endwhile; ?>
            </ul>
        </section>
        
        <?php $this->pageNav('<span aria-label="上一页">←</span>', '<span aria-label="下一页">→</span>', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
    </div>
</main>