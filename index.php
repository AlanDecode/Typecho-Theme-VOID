<?php
/**
 * VOID：无类型
 * 
 * 作者：<a href="https://www.imalan.cn">熊猫小A</a>
 * 
 * @package     Typecho-Theme-VOID
 * @author      熊猫小A
 * @version     3.0.0
 * @link        https://blog.imalan.cn/archives/247/
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting']; 

if(!Utils::isPjax()){
    $this->need('includes/head.php');
    $this->need('includes/header.php');
} 
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    
    <?php $this->need('includes/banner.php'); ?>

    <div class="wrapper container wide">
        <section id="index-list">
            <ul id="masonry">
            <?php while($this->next()): ?>
                <li id="<?php $this->cid(); ?>" class="masonry-item style-<?php 
                        if($this->fields->showfullcontent=='1' && $this->fields->bannerascover == '2') {
                            echo '1';
                        } else {
                            echo $this->fields->bannerascover;
                        } 
                    ?>">
                    <article class="yue" itemscope itemtype="http://schema.org/Article">
                        <?php if($this->fields->banner != ''): ?>
                            <a href="<?php $this->permalink(); ?>">
                                <div class="banner" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                    <img src="<?php echo $this->fields->banner;?>">
                                    <meta itemprop="url" content="<?php echo $this->fields->banner; ?>">
                                </div>
                            </a>
                        <?php endif; ?>
                        <div class="content-wrap">
                            <div class="post-meta-index">
                                <span hidden itemprop="author"><?php $this->author(); ?></span>
                                <time datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('M d, Y', $this->created); ?></time>
                                <?php if($setting['VOIDPlugin']): ?>
                                    <span class="word-count">+ <?php echo $this->wordCount; ?> 字</span>
                                <?php endif; ?>
                            </div>
                        
                            <a href="<?php $this->permalink();?>"><h1 class="title" itemprop="name"><?php $this->title(); ?></h1></a>

                            <?php if($this->fields->excerpt != '') echo "<p itemprop=\"headline\" class=\"headline content\">{$this->fields->excerpt}</p>"; ?>

                            <div itemprop="articleBody">
                            <?php if($this->fields->showfullcontent != '1'): ?>
                                <?php if($this->fields->excerpt == ''): ?>
                                    <p itemprop="headline"><?php if(Utils::isMobile()) $this->excerpt(60); else $this->excerpt(80); ?></p>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php $this->content(); ?>
                            <?php endif; ?>
                            </div>

                            <meta itemprop="dateModified" content="<?php echo date('c', $this->modified); ?>">
                            <meta itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php $this->permalink(); ?>">
                            <div hidden itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
                                <meta itemprop="name" content="<?php $this->options->title(); ?>">
                                <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                                    <meta itemprop="url" content="<?php Utils::gravatar($this->author->mail, 200);  ?>">
                                </div>
                            </div>
                        </div>
                    </article>
                </li>
                <script>MasonryCtrler.watch("<?php $this->cid(); ?>");</script>
            <?php endwhile; ?>
            </ul>
        </section>
        <?php $this->pageNav('<span aria-label="上一页">←</span>', '<span aria-label="下一页">→</span>', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
    </div>
</main>

<?php
if(!Utils::isPjax()){
    $this->need('includes/footer.php');
} 
?>