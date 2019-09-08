<?php
/**
 * main.php
 * 
 * 内容页面主要区域，PJAX 作用区域
 * 适用于巨大文字、巨幅图片
 * 
 * @author      熊猫小A
 * @version     2019-05-08 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
?>

<main id="pjax-container" class="main-excerpt">
    <script>document.querySelector('body>header').classList.remove('force-dark')</script>
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    <style>
        body > footer { display: none; }
        main {display: flex; flex-direction: column; justify-content: center; padding: 17.5vh 0 50px 0;}
    </style>
    <div class="app-landscape theme-dark">
        <div class="mask" id="bg"><div class="mask"></div></div>
        <div class="container" style="margin-bottom: 2rem">
            <article class="yue"  itemscope itemtype="http://schema.org/Article">
                <h1 hidden itemprop="name"><?php $this->title(); ?></h1>
                <span hidden itemprop="author"><?php $this->author(); ?></span>
                <time hidden datetime="<?php echo date('c', $this->created); ?>" itemprop="datePublished"><?php echo date('Y-m-d', $this->created); ?></time>
                <p hidden itemprop="headline"><?php if($this->fields->excerpt!='') echo $this->fields->excerpt; else $this->excerpt(30); ?></p>
                <div itemprop="articleBody">
                <?php $this->content(); ?>
                </div>
                <?php if($this->fields->banner != ''): ?>
                <div hidden itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                    <img src="<?php echo $this->fields->banner; ?>" />
                    <meta itemprop="url" content="<?php echo $this->fields->banner; ?>">
                </div>
                <?php endif; ?>
                <div hidden itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
                    <meta itemprop="name" content="<?php echo $this->options->title; ?>">
                    <meta itemprop="url" content="<?php $this->options->siteUrl(); ?>">
                    <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url" content="<?php Utils::gravatar($this->author->mail, 256, ''); ?>">
                    </div>
                </div>
                <meta itemscope="" itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php $this->permalink(); ?>">
                <meta itemprop="dateModified" content="<?php echo date('c', $this->modified); ?>">
            </article>
        </div>
        <script>
            (function(){
                var applyBg = function (url) {
                    document.getElementById('bg').style.backgroundImage = 'url(' + url + ')';
                    document.getElementById('bg').classList.add('loaded');
                }
                var img_bg = new Image();
                var img_bg_url = "<?php echo $this->fields->banner; ?>";
                img_bg.src = img_bg_url;
                if(img_bg.complete) {
                    applyBg(img_bg_url);
                }
                else {
                    img_bg.onload = function() {
                        applyBg(img_bg_url);
                    };
                }
            })();
        </script>
    </div>
    <!--评论区，可选-->
    <div class="theme-dark" style="width: 100%">
        <?php $this->need('includes/comments.php'); ?>
    </div>
</main>