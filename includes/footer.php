<?php
/**
 * footer.php
 *
 * 底栏
 *
 * @author      熊猫小A
 * @version     2019-01-15 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

        <script>
            var serviceWorkerUri = '/VOIDCacheRule.js';
            if ('serviceWorker' in navigator) {  
                navigator.serviceWorker.register(serviceWorkerUri).then(function() {
                if (navigator.serviceWorker.controller) {
                    console.log('Assets cached by the controlling service worker.');
                } else {
                    console.log('Please reload this page to allow the service worker to handle network operations.');
                }
                }).catch(function(error) {
                console.log('ERROR: ' + error);
                });
            } else {
                console.log('Service workers are not supported in the current browser.');
            }
        </script>
        <script src="<?php Utils::indexTheme('/assets/jquery/jquery.min.js'); ?>"></script>
        <?php $this->footer(); ?>
        <script src="<?php Utils::indexTheme('/assets/bundle-edfc93f7bb.js'); ?>"></script>
        <script src="<?php Utils::indexTheme('/assets/VOID-287b9cae59.js'); ?>"></script>

        <?php if($this->options->enableMath=='1'): ?>
        <script src='<?php Utils::indexTheme('/assets/mathjax/2.7.4/MathJax.js'); ?>' async></script>
        <script type="text/x-mathjax-config">
            MathJax.Hub.Config({
            tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
            });
        </script>
        <?php endif; ?>
        <script>
        if($(".OwO").length > 0){
            new OwO({
                logo: 'OωO',
                container: document.getElementsByClassName('OwO')[0],
                target: document.getElementsByClassName('input-area')[0],
                api: '<?php Utils::indexTheme('/assets/owo/OwO.json'); ?>',
                position: 'down',
                width: '400px',
                maxHeight: '250px'
            });
        }
        </script>
        <?php if($this->options->pjax=='1'): ?>
        <script>
        $(document).on('pjax:complete',function(){
            <?php echo $this->options->pjaxreload; ?>
        })
        </script>
        <?php endif; ?>
        <footer>
            <div class="container">
                <section data-title="Recent Guests">   <!-- 最近访客 -->
                    <div class="avatar-list">
                        <?php 
                            $recentComments=null;
                            $this->widget('Widget_Comments_Recent','ignoreAuthor=true&pageSize=14')->to($recentComments);
                            while($recentComments->next()): ?>
                        <a href="<?php $recentComments->permalink(); ?>"><?php $recentComments->gravatar(64, ''); ?></a>
                        <?php endwhile; ?>
                    </div>
                </section>
                <section data-title="Site Info">   <!-- 一言与页底信息 -->
                    <p>感谢陪伴：<span id="uptime"></span></p>
                    <p id="hitokoto"></p>
                </section>
            </div>
            <div class="container footer-info">
                Powered by Typecho
                <a href="https://blog.imalan.cn/archives/247/">Theme VOID</a>
                <div><?php echo $this->options->footer; ?></div>
            </div>
        </footer>
    </body>
</html>
