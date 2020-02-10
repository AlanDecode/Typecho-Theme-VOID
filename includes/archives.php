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

    <?php $this->need('includes/ldjson.php'); ?>
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
                        <article class="yue">
                            <?php if($this->fields->banner != ''): ?>
                                <div class="banner">
                                    <?php if (Helper::options()->lazyload == '1'): ?>
                                        <?php if($setting['bluredLazyload']): ?>
                                            <img src="<?php echo Contents::genBluredPlaceholderSrc($this->fields->banner); ?>" class="blured-placeholder">
                                        <?php endif; ?>
                                        <img class="lazyload" data-src="<?php echo $this->fields->banner;?>">
                                    <?php else: ?>
                                        <img src="<?php echo $this->fields->banner;?>">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="content-wrap">
                                <div class="post-meta-index">
                                    <time datetime="<?php echo date('c', $this->created); ?>"><?php echo date('M d, Y', $this->created); ?></time>
                                    <?php if($setting['VOIDPlugin']): ?>
                                        <span class="word-count">+ <?php echo $this->wordCount; ?> 字</span>
                                    <?php endif; ?>
                                </div>

                                <h1 class="title"><?php $this->title(); ?></h1>
                                <?php if($this->fields->excerpt != ''): ?> 
                                    <p class="headline single"><?php echo $this->fields->excerpt; ?></p>
                                <?php else: ?>
                                    <p class="excerpt"><?php if(Utils::isMobile()) $this->excerpt(60); else $this->excerpt(100); ?><?php if($this->is('index')) echo " | <a class=\"full-link\" href=\"{$this->permalink}\">阅读全文</a>"; ?></p>
                                <?php endif; ?>
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