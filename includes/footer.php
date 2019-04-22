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
        <footer <?php if(!$setting['showRecentGuest']) echo 'class="no-rg"' ?>>
            <div class="container">
                <section>
                    <?php if($setting['showRecentGuest']): ?>
                        <div class="avatar-list">
                            <?php 
                                $recentComments=Contents::getRecentComments(14);
                                foreach ($recentComments as $comment){ ?>
                                    <a href="<?php echo $comment['permalink']; ?>"><img class="avatar" alt="<?php echo $comment['author'] ?>" src="<?php Utils::gravatar($comment['mail'], 64, ''); ?>" width="64" height="64"></a>
                            <?php } ?>
                        </div>
                    <?php else: ?>
                        <p>感谢陪伴：<span id="uptime"></span></p>
                        <p id="hitokoto"></p>
                    <?php endif; ?>
                </section>
                <section>
                    <?php if($setting['showRecentGuest']): ?>
                        <p>感谢陪伴：<span id="uptime"></span></p>
                        <p id="hitokoto"></p>
                    <?php endif; ?>
                    <p>© <?php echo date('Y '); ?> <span class="brand"><?php echo $this->options->title; ?></span></p>
                    <p>Powered by <a href="http://typecho.org/">Typecho</a> • <a href="https://blog.imalan.cn/archives/247/">Theme VOID</a></p>
                    <p><?php echo $setting['footer']; ?></p>
                </section>
            </div>
        </footer>
        <div id="back-top" onclick="VOID.goTop();"><div></div></div>

        <?php if(!empty($setting['serviceworker'])): ?>
        <script>
            var serviceWorkerUri = '/<?php echo $setting['serviceworker']; ?>';
            if ('serviceWorker' in navigator) {  
                navigator.serviceWorker.register(serviceWorkerUri).then(function() {
                if (navigator.serviceWorker.controller) {
                    console.log('Service woker is registered and is controlling.');
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
        <?php endif; ?>
        <script src="<?php Utils::indexTheme('/assets/bundle.js'); ?>"></script>
        <script src="<?php Utils::indexTheme('/assets/VOID.js'); ?>"></script>
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
        <?php $this->footer(); ?>
    </body>
</html>
