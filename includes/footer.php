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
            <div role=button aria-hidden="true" id="toggle-setting-pc" class="ctrler-item hidden-xs">
                <a target="_self" href="javascript:void(0);" style="transform: translateX(-2px);" onclick="toggleSettingPanel(this, 1);"><i class="voidicon-cog"></i></a>
            </div>
            <div role=button aria-label="展开或关闭文章目录" class="ctrler-item" id="toggle-toc">
                <a target="_self" href="javascript:void(0);" onclick="TOC.toggle()">←</a>
            </div>
        </aside>

        <!--站点设置面板-->
        <aside id="setting-panel">
            <section>
                <div id="toggle-night">
                    <a href="javascript:void(0)" onclick="DarkModeSwitcher.toggleByHand();"><i></i></a>
                </div>
                <div id="adjust-text-container">
                    <div class="adjust-text-item">
                        <a href="javascript:void(0)" onclick="adjustTextsize(false);"><i class="voidicon-font"></i>-</a>
                        <span id="current_textsize"></span>
                        <a href="javascript:void(0)" onclick="adjustTextsize(true);"><i class="voidicon-font"></i>+</a>
                    </div>
                    <div class="adjust-text-item">
                        <a class="font-indicator <?php if(!Utils::isSerif($setting)) echo ' checked'; ?>" href="javascript:void(0)" onclick="toggleSerif(this, false);">Sans</a>
                        <a class="font-indicator <?php if(Utils::isSerif($setting)) echo ' checked'; ?>" href="javascript:void(0)" onclick="toggleSerif(this, true);">Serif</a>
                    </div>
                </div>
            </section>
            <section id="links">
            <?php if($this->user->hasLogin()): ?>
                <a class="link" no-pjax title="登出" href="<?php $this->options->logoutUrl(); ?>"><i class="voidicon-logout"></i></a>
            <?php else: ?>   
                <a class="link" id="toggleLoginForm" href="javascript:void(0)" onclick="toggleLoginForm()"><i class="voidicon-login"></i></a>       
            <?php endif; ?> 
            
                <a class="link" title="RSS" target="_blank" href="<?php $this->options->feedUrl(); ?>"><i class="voidicon-rss"></i></a>
                <?php
                    foreach ($setting['link'] as $link) {
                        echo "<a class=\"link\" title=\"{$link['name']}\" target=\"{$link['target']}\" href=\"{$link['href']}\"><i class=\"voidicon-{$link['icon']}\"></i></a>";
                    }
                ?>
            </section>
            <?php if(!$this->user->hasLogin()): ?>
                <section id="login-panel">
                    <form action="<?php $this->options->loginAction()?>" method="post" name="login" role="form">
                        <div>
                            <input type="text" name="name" autocomplete="username" placeholder="请输入用户名" required/>
                            <input type="password" name="password" autocomplete="current-password" placeholder="请输入密码" required/>
                            <input type="hidden" name="referer" value="<?php 
                                if($this->is('index')) $this->options->siteUrl();
                                else $this->permalink();
                            ?>">
                        </div>
                        <div>
                            <button class="btn btn-normal" type="button" onclick="$('#login-panel').removeClass('show');">关闭</button>
                            <button class="btn btn-normal" type="submit">登录</button>
                        </div>
                    </form>
                </section>
            <?php endif; ?> 
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
        <script src='<?php Utils::indexTheme('/assets/libs/mathjax/2.7.4/MathJax.js'); ?>'></script>
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
