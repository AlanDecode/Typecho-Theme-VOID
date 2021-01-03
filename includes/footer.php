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
            <div class="container wide">
                <section>
                    <p>© <?php echo date('Y '); ?> <span class="brand"><?php echo $this->options->title; ?></span></p>
                    <p>感谢陪伴：<span id="uptime"></span></p>
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
                <a target="_self" aria-label="返回顶部" href="javascript:void(0);" style="transform: translateX(-2px);" onclick="VOID_SmoothScroller.scrollTo(0);"><i class="voidicon-up"></i></a>
            </div>

            <?php if($this->user->hasLogin()): ?>
                <div class="ctrler-item hidden-xs">
                    <a target="_blank" aria-label="进入后台" href="<?php $this->options->adminUrl(); ?>" style="transform: translateX(-2px);"><i class="voidicon-login"></i></a>
                </div>
                <div class="ctrler-item hidden-xs">
                    <a target="_blank" aria-label="管理评论" href="<?php $this->options->adminUrl('manage-comments.php'); ?>" style="transform: translateX(-2px);"><i class="voidicon-comment"></i></a>
                </div>
            <?php endif; ?>

            <div aria-label="展开或关闭设置面板" id="toggle-setting-pc" class="ctrler-item hidden-xs">
                <a target="_self" href="javascript:void(0);" style="transform: translateX(-2px);" onclick="VOID_Ui.toggleSettingPanel();"><i class="voidicon-cog"></i></a>
            </div>
            <div aria-label="展开或关闭文章目录" class="ctrler-item" id="toggle-toc">
                <a target="_self" href="javascript:void(0);" style="margin-left: -2px" onclick="TOC.toggle()"><i class="voidicon-left"></i></a>
            </div>
        </aside>

        <!--站点设置面板-->
        <aside hidden id="setting-panel">
            <section>
                <div id="toggle-night">
                    <a target="_self" href="javascript:void(0)" onclick="VOID_Ui.DarkModeSwitcher.toggleByHand();"><i></i></a>
                </div>
                <div id="adjust-text-container">
                    <div class="adjust-text-item">
                        <a target="_self" href="javascript:void(0)" onclick="VOID_Ui.adjustTextsize(false);"><i class="voidicon-font"></i>-</a>
                        <span id="current_textsize"></span>
                        <a target="_self" href="javascript:void(0)" onclick="VOID_Ui.adjustTextsize(true);"><i class="voidicon-font"></i>+</a>
                    </div>
                    <div class="adjust-text-item">
                        <a target="_self" class="font-indicator <?php if(!Utils::isSerif($setting)) echo ' checked'; ?>" href="javascript:void(0)" onclick="VOID_Ui.toggleSerif(this, false);">Sans</a>
                        <a target="_self" class="font-indicator <?php if(Utils::isSerif($setting)) echo ' checked'; ?>" href="javascript:void(0)" onclick="VOID_Ui.toggleSerif(this, true);">Serif</a>
                    </div>
                </div>
            </section>
            <section id="links">
                <?php if(!$this->user->hasLogin()): ?>
                    <a target="_self" class="link" href="javascript:void(0)" onclick="VOID_Ui.toggleLoginForm()"><i class="voidicon-user"></i></a>       
                <?php endif; ?>
                <a class="link" title="RSS" target="_blank" href="<?php $this->options->feedUrl(); ?>"><i class="voidicon-rss"></i></a>
                <?php
                    foreach ($setting['link'] as $link) {
                        echo "<a class=\"link\" title=\"{$link['name']}\" target=\"{$link['target']}\" href=\"{$link['href']}\"><i class=\"voidicon-{$link['icon']}\"></i></a>";
                    }
                ?>
            </section>
            <section id="login-panel" <?php if($this->user->hasLogin()) echo 'class="force-show"'; ?>>
                <?php if(!$this->user->hasLogin()): ?>
                    <form action="<?php $this->options->loginAction()?>" id="loggin-form" method="post" name="login" role="form">
                        <div id="loggin-inputs">
                            <input type="text" name="name" autocomplete="username" placeholder="请输入用户名" required/>
                            <input type="password" name="password" autocomplete="current-password" placeholder="请输入密码" required/>
                            <input type="hidden" name="referer" value="<?php 
                                if($this->is('index')) $this->options->siteUrl();
                                else $this->permalink();
                            ?>">
                        </div>
                        <div class="buttons" id="loggin-buttons">
                            <button class="btn btn-normal" type="button" onclick="$('#login-panel').removeClass('show');$('#setting-panel').removeClass('show')">关闭</button>
                            <button class="btn btn-normal" type="submit" onclick="VOID_Ui.rememberPos()">登录</button>
                            <span hidden id="wait" class="btn btn-normal">请稍等……</span>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="buttons" id="manage-buttons">
                        <a class="btn btn-normal" no-pjax target="_blank" href="<?php $this->options->adminUrl(); ?>">后台</a>
                        <a class="btn btn-normal" no-pjax title="登出" onclick="VOID_Ui.rememberPos()" href="<?php $this->options->logoutUrl(); ?>">登出</a>
                    </div>
                <?php endif; ?> 
            </section> 
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
        <script data-manual src="<?php Utils::indexTheme('/assets/bundle-b514182550.js'); ?>"></script>
        <?php if($setting['enableMath']): ?>
        <script src='<?php Utils::indexTheme('/assets/libs/mathjax/2.7.4/MathJax.js'); ?>'></script>
        <?php endif; ?>
        <script src="<?php Utils::indexTheme('/assets/VOID-e344978213.js'); ?>"></script>
        <script>
        if($(".OwO").length > 0){
            new OwO({
                logo: 'OωO',
                container: document.getElementsByClassName('OwO')[0],
                target: document.getElementsByClassName('input-area')[0],
                api: '<?php Utils::indexTheme('/assets/libs/owo/OwO_03.json'); ?>',
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
        <?php $this->footer(); ?>
    </body>
</html>
