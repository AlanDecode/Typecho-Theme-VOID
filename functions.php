<?php
/**
 * functions.php
 *
 * 初始化主题
 *
 * @author      熊猫小A
 * @version     2019-01-15 1.0
 */
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

// 看不见错误就是没有错误
error_reporting(0);

require_once('libs/Utils.php');
require_once('libs/Contents.php');
require_once('libs/Comments.php');

Typecho_Plugin::factory('admin/write-post.php')->bottom = array('Utils', 'addButton');
Typecho_Plugin::factory('admin/write-page.php')->bottom = array('Utils', 'addButton');
// 为防止友链解析与 Markdown 冲突，重写 Markdown 函数
Typecho_Plugin::factory('Widget_Abstract_Contents')->markdown = array('Contents', 'markdown');
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('Contents', 'contentEx');
Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('Contents', 'excerptEx');

/**
 * 主题启用
 */
function themeInit()
{
    Helper::options()->commentsAntiSpam = false;
    Helper::options()->commentsMaxNestingLevels = 999;
    Helper::options()->commentsOrder = 'DESC';
}

$GLOBALS['VOIDPluginREQ'] = 1.2;
$GLOBALS['VOIDVersion'] = 3.51;

/**
 * 主题设置
 */
function themeConfig($form)
{
    echo '<style>
        p.notice {
        line-height: 1.75;
        padding: .5rem;
        padding-left: .75rem;
        border-left: solid 4px #fbbc05;
        background: rgba(0,0,25,.025);
    }</style>';

    if (!Utils::hasVOIDPlugin($GLOBALS['VOIDPluginREQ'])) {
        echo '<p class="notice">未检测到合适的 VOID 插件！主题部分功能依赖插件支持，推荐安装以获得最佳体验。VOID 插件一般会随主题包发布，开发版主题请前往 https://github.com/AlanDecode/VOID-Plugin 获取。</p>';
    }

    echo '<p id="void-check-update" class="notice">正在检查更新……</p>';
    echo '<script>var VOIDVersion='.$GLOBALS['VOIDVersion'].'</script>';
    echo '<script src="'.Helper::options()->themeUrl.'/assets/check_update-143d915008.js"></script>';

    $defaultBanner = new Typecho_Widget_Helper_Form_Element_Text('defaultBanner', null, '', '首页顶部大图', '可以填写随机图 API。');
    $form->addInput($defaultBanner);
    $indexBannerTitle = new Typecho_Widget_Helper_Form_Element_Text('indexBannerTitle', null, '', '首页顶部大标题', '不要太长');
    $form->addInput($indexBannerTitle);
    $indexBannerSubtitle = new Typecho_Widget_Helper_Form_Element_Text('indexBannerSubtitle', null, '', '首页顶部小标题', '');
    $form->addInput($indexBannerSubtitle);

    $colorScheme = new Typecho_Widget_Helper_Form_Element_Radio('colorScheme', array('0' => '自动切换', '1' => '日间模式', '2' => '夜间模式'), '0', '主题颜色模式', '选择主题颜色模式。自动模式下每天 22:00 到次日 06:59 会显示为夜间模式。');
    $form->addInput($colorScheme);

    $indexStyle = new Typecho_Widget_Helper_Form_Element_Radio('indexStyle', array(
        '0' => '双栏',
        '1' => '单栏'), '0', '首页版式', '选择单栏或者双栏瀑布流');
    $form->addInput($indexStyle);

    // 高级设置
    $reward = new Typecho_Widget_Helper_Form_Element_Text('reward', null, '', '打赏二维码', '图片链接，只允许一张图片，更多请自行合成。');
    $form->addInput($reward);
    $serifincontent = new Typecho_Widget_Helper_Form_Element_Radio('serifincontent', array('0' => '不启用', '1' => '启用'), '0', '文章内容使用衬线体', '是否对文章内容启用衬线体（思源宋体）。此服务由 Google Fonts 提供，可能会有加载较慢的情况。');
    $form->addInput($serifincontent);
    $lazyload = new Typecho_Widget_Helper_Form_Element_Radio('lazyload', array('1' => '启用', '0' => '不启用'), '1', '图片懒加载', '是否启用图片懒加载。');
    $form->addInput($lazyload);
    $enableMath = new Typecho_Widget_Helper_Form_Element_Radio('enableMath', array('0' => '不启用', '1' => '启用'), '0', '启用数学公式解析', '是否启用数学公式解析。启用后会多加载 1~2M 的资源。');
    $form->addInput($enableMath);
    $head = new Typecho_Widget_Helper_Form_Element_Textarea('head', null, '', 'head 标签输出内容', '统计代码等。');
    $form->addInput($head);
    $footer = new Typecho_Widget_Helper_Form_Element_Textarea('footer', null, '', 'footer 标签输出内容', '备案号等。');
    $form->addInput($footer);
    $pjax = new Typecho_Widget_Helper_Form_Element_Radio('pjax', array('0' => '不启用', '1' => '启用'), '0', '启用 PJAX (BETA)', '是否启用 PJAX。如果你发现站点有点不对劲，又不知道这个选项是啥意思，请关闭此项。');
    $form->addInput($pjax);
    $pjaxreload = new Typecho_Widget_Helper_Form_Element_Textarea('pjaxreload', null, null, 'PJAX 重载函数', '输入要重载的 JS，如果你发现站点有点不对劲，又不知道这个选项是啥意思，请关闭 PJAX 并留空此项。');
    $form->addInput($pjaxreload);
    $serviceworker = new Typecho_Widget_Helper_Form_Element_Text('serviceworker', null, null, '自定义 Service Worker', '如果你知道这是什么，请把你的 SW 文件（例如主题 assets 文件夹下的 VOIDCacheRule.js）复制一份到<b>站点根目录</b>，并在这里填写文件名（例如 VOIDCacheRule.js）。若不知道该选项含义，请留空此项。');
    $form->addInput($serviceworker);

    // 超高级设置
    $advance = new Typecho_Widget_Helper_Form_Element_Textarea('advance', null, null, 超高级设置, '主题中包含一份 advanceSetting.sample.json，自己仿照着写吧。');
    $form->addInput($advance);
}

/**
 * 文章自定义字段
 */
function themeFields(Typecho_Widget_Helper_Layout $layout)
{
    $excerpt = new Typecho_Widget_Helper_Form_Element_Textarea('excerpt', null, null, '文章摘要', '输入自定义摘要。留空自动从文章截取。');
    $layout->addItem($excerpt);
    $banner = new Typecho_Widget_Helper_Form_Element_Text('banner', null, null, '文章主图', '输入图片URL，该图片会用于主页文章列表的显示。');
    $layout->addItem($banner);
    $bannerStyle = new Typecho_Widget_Helper_Form_Element_Select('bannerStyle', array(
        0 => '显示在顶部',
        1 => '显示在顶部并添加模糊效果',
        2 => '不显示'), 0, '文章主图样式', '');
    $layout->addItem($bannerStyle);
    $bannerascover = new Typecho_Widget_Helper_Form_Element_Select('bannerascover', array('1' => '主图显示在标题上方', '2' => '主图作为标题背景', '0' => '不显示'), '1', '首页主图样式', '主图作为标题背景时会添加暗色遮罩，但仍然建议仅对暗色的主图采用该方式展示。否则请选择「主图显示在标题上方」。');
    $layout->addItem($bannerascover);
    $posttype = new Typecho_Widget_Helper_Form_Element_Select('posttype', array('0' => '一般文章', '1' => 'Landscape'), '0', '文章类型', '选择展示方式');
    $layout->addItem($posttype);
    $showfullcontent = new Typecho_Widget_Helper_Form_Element_Select('showfullcontent', array('0' => '否', '1' => '是'), '0', '在首页显示完整内容', '是否在首页展示完整内容。适合比较短的文章。');
    $layout->addItem($showfullcontent);
    $showTOC = new Typecho_Widget_Helper_Form_Element_Select('showTOC', array('0' => '不显示目录', '1' => '显示目录'), '0', '文章目录', '是否显示文章目录。');
    $layout->addItem($showTOC);
}

$GLOBALS['VOIDSetting'] = Utils::getVOIDSettings();
