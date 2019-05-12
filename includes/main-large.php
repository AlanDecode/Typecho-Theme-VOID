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
    <title hidden>
        <?php Contents::title($this); ?>
    </title>
    <style>
        body > header, body > footer { display: none; }
        main {display: flex; flex-direction: column; justify-content: center; padding: 50px 0;}
    </style>
    <div class="app-1">
        <div class="mask" id="bg"><div class="mask"></div></div>
        <div class="container">
            <a class="brand" href="<?php Utils::indexHome('/'); ?>"><?php if($setting['name']) echo $setting['name']; else echo $this->options->title; ?></a>
            <article class="yue">
                <?php $this->content(); ?>
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
</main>