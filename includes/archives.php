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

    <div class="wrapper container <?php if($setting['indexStyle'] == 1) echo 'narrow'; else echo 'wide'; ?>">
        <section id="index-list" class="float-up">
            <h1 hidden class="post-title"><?php $this->archiveTitle(array(
                'category'  =>  _t('分类 "%s" 下的文章'),
                'search'    =>  _t('包含关键字 "%s" 的文章'),
                'tag'       =>  _t('包含标签 "%s" 的文章'),
                'author'    =>  _t('"%s" 发布的文章')
            ), '', '');  ?></h1>
            <ul id="masonry">
            <?php while($this->next()): ?>
                <?php $bannerAsCover = $this->fields->bannerascover; if($this->fields->banner == '') $bannerAsCover='0'; ?>
                <li id="p-<?php $this->cid(); ?>"  class="masonry-item style-<?php echo $bannerAsCover; ?>">
                    <a href="<?php $this->permalink(); ?>">    
                        <article class="yue itemscope itemtype="http://schema.org/Article">
                            <?php if($this->fields->banner != ''): ?>
                                <div class="banner" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                    <?php if (Helper::options()->lazyload == '1'): ?>
                                        <img class="lazyload instant" src="<?php echo Contents::getPlaceHolder(); ?>" data-src="<?php echo $this->fields->banner;?>">
                                    <?php else: ?>
                                        <img src="<?php echo $this->fields->banner;?>">
                                    <?php endif; ?>
                                    <meta itemprop="url" content="<?php echo $this->fields->banner; ?>">
                                </div>
                            <?php endif; ?>
                            <div class="content-wrap">
                                <div class="post-meta-index">
                                    <span hidden itemprop="author"><?php $this->author(); ?></span>
                                    <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('M d, Y', $this->created); ?></time>
                                    <?php if($setting['VOIDPlugin']): ?>
                                        <span class="word-count">+ <?php echo $this->wordCount; ?> 字</span>
                                    <?php endif; ?>
                                </div>

                                <h1 class="title" itemprop="name"><?php $this->title(); ?></h1>
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
                                    <meta itemprop="url" content="<?php $this->options->siteUrl(); ?>">
                                    <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                                        <meta itemprop="url" content="<?php Utils::gravatar($this->author->mail, 200);  ?>">
                                    </div>
                                </div>
                            </div>
                        </article>
                    </a>
                </li>
                <script>VOID_Ui.MasonryCtrler.watch("p-<?php $this->cid(); ?>");</script>
            <?php endwhile; ?>
            </ul>
        </section>
        
        <?php $this->pageNav('<span aria-label="上一页">←</span>', '<span aria-label="下一页">→</span>', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
    </div>
</main>