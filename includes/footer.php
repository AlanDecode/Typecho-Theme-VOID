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
        <footer>
            <div class="container">
                <section>
                    <p>感谢陪伴：<span id="uptime"></span></p>
                    <p>© <?php echo date('Y '); ?> <span class="brand"><?php echo $this->options->title; ?></span></p>
                </section>
                <section>
                    <p>Powered by <a href="http://typecho.org/">Typecho</a> • <a href="https://blog.imalan.cn/archives/247/">Theme VOID</a></p>
                    <p><?php echo $setting['footer']; ?></p>
                </section>
            </div>
        </footer>

        <!--侧边控制按钮-->
        <aside id="ctrler-panel">
            <div class="ctrler-item" id="go-top">
                <a target="_self" aria-label="返回顶部" href="javascript:void(0);" onclick="$.scrollTo(0, 300);">↑</a>
            </div>
            <div role=button aria-label="展开或关闭文章目录" class="ctrler-item" id="toggle-toc">
                <a target="_self" href="javascript:void(0);" onclick="TOC.toggle()">←</a>
            </div>
        </aside>

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
        <?php else: ?>
        <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for(let registration of registrations) {
                registration.unregister()
            }}).catch(function(err) {
                console.log('Service Worker registration failed: ', err);
            });
        }
        </script>
        <?php endif; ?>
        <script src="<?php Utils::indexTheme('/assets/bundle.js'); ?>"></script>
        <?php if($setting['enableMath']): ?>
        <script src='<?php Utils::indexTheme('/assets/libs/mathjax/2.7.4/MathJax.js'); ?>' async></script>
        <?php endif; ?>
        <script src="<?php Utils::indexTheme('/assets/VOID.js'); ?>"></script>
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
