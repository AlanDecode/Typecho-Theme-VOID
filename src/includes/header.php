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
    <header>
        <div class="container">
            <a class="toggle" href="javascript:void(0);" onclick="toggleNav(this);">
                <span></span>
            </a>
            <nav>
                <a class="brand" href="<?php Utils::index(''); ?>"><?php echo $this->options->title; ?></a>
                <?php $this->widget('Widget_Contents_Page_List')->parse('<a href="{permalink}">{title}</a>'); ?>
            </nav>
            <input onkeydown="enterSearch(this);" type="text" name="search-content" id="search" class="text" required placeholder="Search..." />
        </div>
    </header>
    <div id="nav-mobile">
        <div class="search">
            <input onkeydown="enterSearch(this);" type="text" name="search-content" id="search_new" class="text" required placeholder="Search..." />
        </div>
        <section id="pages" data-title="PAGES" style="border-bottom: 1px solid rgba(255,255,255,0.34);">
            <nav>
                <?php $this->widget('Widget_Contents_Page_List')->parse('<a href="{permalink}">{title}</a>'); ?>
            </nav>
        </section>
        <section id="categories" data-title="CATEGORIES">
            <nav>
            <?php $this->widget('Widget_Metas_Category_List')->parse('<a href="{permalink}">{name}</a>'); ?>
            </nav>
        </section>
    </div>