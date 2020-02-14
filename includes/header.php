<?php
/**
 * header.php
 * 
 * 顶部导航条
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
$banner = $setting['defaultBanner'];
if($this->is('post') || $this->is('page')) {
    $banner = $this->fields->bannerStyle < 2 ? $this->fields->banner : '';
}
?>

<body fontsize="<?php echo Utils::getTextSize($setting); ?>" class="<?php 
        if($setting['colorScheme'] == 0){
            echo((isset($_COOKIE['theme_dark']) && $_COOKIE['theme_dark'] == '1') ? 'theme-dark' : '');
        } elseif ($setting['colorScheme'] == 2) {
            echo 'theme-dark';
        }
        if($setting['macStyleCodeBlock']) {
            echo ' macStyleCodeBlock';
        }
        if ($setting['lineNumbers']) {
            echo ' line-numbers';
        }
        if(Utils::isSerif($setting)) {
            echo ' serif';
        }
        if(Utils::isMobile()) {
            echo ' mobile';
        }
        if(Utils::isIosSafari()) {
            echo ' ios-safari';
        }
        if ($setting['indexStyle'] == 1) { // 强制不显示
            echo ' single-col';
        }
        if ($setting['bluredLazyload']) { // 强制不显示
            echo ' bluredLazyload';
        }
        if(Helper::options()->lazyload == '1') {
            echo ' lazyload-img';
        }
    ?>">
    
    <header class="header-mode-<?php echo $setting['headerMode']; ?> <?php if(empty($banner)) echo 'force-dark no-banner'; ?>">
        <div class="container wider">
            <nav>
                <a role=button aria-label="展开导航" class="toggle" target="_self" href="javascript:void(0);" onclick="VOID_Ui.toggleNav(this);">
                    <span></span>
                </a>
                <a class="brand" href="<?php Utils::index(''); ?>"><?php if($setting['name']) echo $setting['name']; else echo $this->options->title; ?></a>
                <a href="<?php Utils::index(''); ?>">首页</a>
                <?php $this->widget('Widget_Contents_Page_List')->parse('<a href="{permalink}">{title}</a>'); ?>
                <span class="dropdown">分类
                    <ul>
                        <?php $this->widget('Widget_Metas_Category_List')->parse('<li><a href="{permalink}">{name}</a></li>'); ?>
                    </ul>
                </span>
                <?php if($setting['nav']){ foreach ($setting['nav'] as $listItem): ?>
                <span class="dropdown"><?php echo $listItem['name']; ?>
                    <ul>
                        <?php foreach ($listItem['items'] as $item) {
                            $target = '_blank';
                            if(isset($item['target'])) $target = $item['target'];
                            echo "<li><a target=\"{$target}\" href=\"{$item['link']}\">{$item['title']}</a></li>";
                        }?>
                    </ul>
                </span>
                <?php endforeach; } ?>
                <?php if(!Utils::isPluginAvailable('ExSearch')): ?>
                    <span class="hidden-xs search-form-desktop">
                        <label for="search">搜索</label>
                        <input onkeydown="VOID.enterSearch(this);" type="text" name="search-content" id="search" required />
                    </span>
                <?php endif; ?>
                <a <?php if(Utils::isPluginAvailable('ExSearch')) echo 'class="search-form-input" style="display:flex"'; ?> 
                    role=button aria-label="展开搜索" id="toggle-mobile-search" target="_self" 
                    href="javascript:void(0);" 
                    onclick="<?php if(!Utils::isPluginAvailable('ExSearch')) echo 'VOID_Ui.toggleSearch(this);'; ?>">
                    <i class="voidicon-search"></i>
                </a>
                <a target="_self" href="javascript:void(0);" id="toggle-setting" onclick="VOID_Ui.toggleSettingPanel();"><i class="voidicon-cog"></i></a>
            </nav>
        </div>
        <div class="mobile-search-form">
            <label for="search_new">搜索</label>
            <input onkeydown="VOID.enterSearch(this);" type="text" name="search-content" id="search_new" required placeholder="输入内容然后 Go!" />
            <button onclick="VOID.startSearch('#search_new');">Go!</button>
        </div>
    </header>
    <div id="nav-mobile">
        <section id="pages" data-title="PAGES">
            <nav>
                <?php $this->widget('Widget_Contents_Page_List')->parse('<a href="{permalink}">{title}</a>'); ?>
            </nav>
        </section>
        <section id="categories" data-title="CATEGORIES">
            <nav>
                <?php $this->widget('Widget_Metas_Category_List')->parse('<a href="{permalink}">{name}</a>'); ?>
            </nav>
        </section>
        <?php if($setting['nav']){ foreach ($setting['nav'] as $listItem): ?>
        <section data-title="<?php echo $listItem['name']; ?>">
            <nav>
                <?php foreach ($listItem['items'] as $item) {
                    $target = '_blank';
                    if(isset($item['target'])) $target = $item['target'];
                    echo "<a target=\"{$target}\" href=\"{$item['link']}\">{$item['title']}</a>";
                }?>
            </nav>
        </section>
        <?php endforeach;} ?>
    </div>