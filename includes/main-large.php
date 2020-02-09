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
    <?php $this->need('includes/ldjson.php'); ?>

    <style>
        body > footer { display: none; }
        main {display: flex; flex-direction: column; justify-content: center; padding: 17.5vh 0 50px 0;}
    </style>
    <div class="app-landscape theme-dark">
        <div class="mask" id="bg"><div class="mask"></div></div>
        <div class="container" style="margin-bottom: 2rem">
            <article class="yue">
                <div class="articleBody">
                    <?php $this->content(); ?>
                </div>
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
                if(!img_bg.complete) {
                    img_bg.onload = function() {
                        applyBg(img_bg_url);
                    };
                    img_bg.src = img_bg_url;
                }
                else {
                    img_bg.src = img_bg_url;
                    applyBg(img_bg_url);
                }
            })();
        </script>
    </div>
    <!--评论区，可选-->
    <div class="theme-dark" style="width: 100%">
        <?php $this->need('includes/comments.php'); ?>
    </div>
</main>