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
?>

<body>
    <header>
        <div class="container">
            <a role=button aria-label="展开导航" class="toggle" target="_self" href="javascript:void(0);" onclick="toggleNav(this);">
                <span></span>
            </a>
            <nav aria-label="导航链接">
                <a aria-label="返回主页" class="brand" href="<?php Utils::index(''); ?>"><?php if($setting['name']) echo $setting['name']; else echo $this->options->title; ?></a>
                <?php $this->widget('Widget_Contents_Page_List')->parse('<a aria-label="独立页面链接" href="{permalink}">{title}</a>'); ?>
                <span aria-label="分类下拉列表" class="dropdown">分类
                    <ul>
                        <?php $this->widget('Widget_Metas_Category_List')->parse('<li><a href="{permalink}">{name}</a></li>'); ?>
                    </ul>
                </span>
                <?php if($setting['nav']){ foreach ($setting['nav'] as $listItem): ?>
                <span aria-label="<?php echo $listItem->name; ?>下拉列表" class="dropdown"><?php echo $listItem->name; ?>
                    <ul>
                        <?php foreach ($listItem->items as $item) {
                            echo "<li><a href=\"{$item->link}\">{$item->title}</a></li>";
                        }?>
                    </ul>
                </span>
                <?php endforeach; } ?>
            </nav>
            <?php if(!Utils::isPluginAvailable('ExSearch')): ?>
            <span style="position:relative" class="hidden-xs">
                <label for="search">搜索</label>
                <input aria-label="搜索框" onkeydown="enterSearch(this);" type="text" name="search-content" id="search" class="text" required />
            </span>
            <?php endif; ?>
            <a <?php if(Utils::isPluginAvailable('ExSearch')) echo 'class="search-form-input" style="display:block"'; ?> role=button aria-label="展开搜索" id="toggle-mobile-search" target="_self" href="javascript:void(0);" onclick="<?php if(!Utils::isPluginAvailable('ExSearch')) echo 'toggleSearch(this);'; ?>">
                <div></div>
                <span></span>
            </a>
        </div>
        <div class="mobile-search">
            <label for="search">搜索</label>
            <input aria-label="搜索框" onkeydown="enterSearch(this);" type="text" name="search-content" id="search_new" class="text" required placeholder="输入内容然后 Go!" />
            <button aria-label="开始搜索" onclick="startSearch('#search_new');">Go!</button>
        </div>
    </header>
    <div id="nav-mobile">
        <section id="pages" data-title="PAGES">
            <nav aria-label="页面导航">
                <?php $this->widget('Widget_Contents_Page_List')->parse('<a href="{permalink}">{title}</a>'); ?>
            </nav>
        </section>
        <section id="categories" data-title="CATEGORIES">
            <nav aria-label="分类导航">
            <?php $this->widget('Widget_Metas_Category_List')->parse('<a href="{permalink}">{name}</a>'); ?>
            </nav>
        </section>
        <?php if($setting['nav']){ foreach ($setting['nav'] as $listItem): ?>
        <section data-title="<?php echo $listItem->name; ?>">
            <nav aria-label="<?php echo $listItem->name; ?>导航">
                <?php foreach ($listItem->items as $item) {
                    echo "<a href=\"{$item->link}\">{$item->title}</a>";
                }?>
            </nav>
        </section>
        <?php endforeach;} ?>
    </div>