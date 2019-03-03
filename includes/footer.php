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
$setting = $GLOBALS['VOIDSetting'];
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
        <script src="<?php Utils::indexTheme('/assets/bundle.js'); ?>"></script>
        <script src="<?php Utils::indexTheme('/assets/VOID.js'); ?>"></script>
        <?php $this->footer(); ?>

        <?php if($setting['enableMath']): ?>
        <script src='<?php Utils::indexTheme('/assets/libs/mathjax/2.7.4/MathJax.js'); ?>' async></script>
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
                api: '<?php Utils::indexTheme('/assets/libs/owo/OwO_01.json'); ?>',
                position: 'down',
                width: '400px',
                maxHeight: '250px'
            });
        }
        </script>
        <?php if($setting['pjax']): ?>
        <script>
            $(document).on('pjax:complete',function(){
                <?php echo $setting['pjaxreload']; ?>
            })
            <?php if(Utils::isPluginAvailable('ExSearch')): ?>
            function ExSearchCall(item){
                if (item && item.length) {
                    $('.ins-close').click(); // 关闭搜索框
                    let url = item.attr('data-url'); // 获取目标页面 URL
                    $.pjax({url: url, 
                        container: '#pjax-container',
                        fragment: '#pjax-container',
                        timeout: 8000, }); // 发起一次 PJAX 请求
                }
            }
            <?php endif; ?>
        </script>
        <?php endif; ?>
        <link rel="stylesheet" href="https://lab.lepture.com/social/dist/widget.css">
        <footer>
            <div class="container">
                <section data-title="Recent Guests">   <!-- 最近访客 -->
                    <div class="avatar-list">
                        <?php 
                            $recentComments=Contents::getRecentComments(12);
                            foreach ($recentComments as $comment){ ?>
                                <a href="<?php echo $comment['permalink']; ?>"><img class="avatar" alt="<?php echo $comment['author'] ?>" src="<?php Utils::gravatar($comment['mail'], 64, ''); ?>" width="64" height="64"></a>
                        <?php } ?>
                    </div>
                </section>
                <section data-title="Site Info">   <!-- 一言与页底信息 -->
                    <p>感谢陪伴：<span id="uptime"></span></p>
                    <p id="hitokoto"></p>
                    <p>Powered by <a href="http://typecho.org/">Typecho</a> • <a href="https://blog.imalan.cn/archives/247/">Theme VOID</a></p>
                    <p><?php echo $setting['footer']; ?></p>
                </section>
            </div>
        </footer>
    </body>
</html>
