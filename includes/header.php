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
?>

<body>
    <?php $extConfig = false; if(file_exists(__DIR__.'/header.json'))  $extConfig = json_decode(file_get_contents(__DIR__.'/header.json')); ?>
    <header>
        <div class="container">
            <a class="toggle" href="javascript:void(0);" onclick="toggleNav(this);">
                <span></span>
            </a>
            <nav>
                <a class="brand" href="<?php Utils::index(''); ?>"><?php if($extConfig && $extConfig->name) echo $extConfig->name; else echo $this->options->title; ?></a>
                <?php $this->widget('Widget_Contents_Page_List')->parse('<a href="{permalink}">{title}</a>'); ?>
                <span class="dropdown">分类
                    <ul>
                        <?php $this->widget('Widget_Metas_Category_List')->parse('<li><a href="{permalink}">{name}</a></li>'); ?>
                    </ul>
                </span>
                <?php if($extConfig && property_exists($extConfig, 'nav')){ foreach ($extConfig->nav as $listItem): ?>
                <span class="dropdown"><?php echo $listItem->name; ?>
                    <ul>
                        <?php foreach ($listItem->items as $item) {
                            echo "<li><a href=\"{$item->link}\">{$item->title}</a></li>";
                        }?>
                    </ul>
                </span>
                <?php endforeach; } ?>
            </nav>
            <input onkeydown="enterSearch(this);" type="text" name="search-content" id="search" class="text" required placeholder="Search..." />
        </div>
    </header>
    <div id="nav-mobile">
        <div class="search">
            <input onkeydown="enterSearch(this);" type="text" name="search-content" id="search_new" class="text" required placeholder="Search..." />
        </div>
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
        <?php if($extConfig && property_exists($extConfig, 'nav')){ foreach ($extConfig->nav as $listItem): ?>
        <section data-title="<?php echo $listItem->name; ?>">
            <nav>
                <?php foreach ($listItem->items as $item) {
                    echo "<a href=\"{$item->link}\">{$item->title}</a>";
                }?>
            </nav>
        </section>
        <?php endforeach;} ?>
    </div>