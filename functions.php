<?php
/**
 * functions.php
 * 
 * 初始化主题
 * 
 * @author      熊猫小A
 * @version     2019-01-15 1.0
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

<?php

// 看不见错误就是没有错误
error_reporting(0);

require_once("libs/Utils.php");
require_once("libs/Contents.php");
require_once("libs/Comments.php");

Typecho_Plugin::factory('admin/write-post.php')->bottom = array('Utils', 'addButton');
Typecho_Plugin::factory('admin/write-page.php')->bottom = array('Utils', 'addButton');
// 为防止友链解析与 Markdown 冲突，重写 Markdown 函数
Typecho_Plugin::factory('Widget_Abstract_Contents')->markdown = array('Contents','markdown');
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('Contents','parseContent');
Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('Contents','parseContent');

/**
 * 主题启用
 */
function themeInit(){
    Helper::options()->commentsAntiSpam = false;
    Helper::options()->commentsMaxNestingLevels = 999;
    Helper::options()->commentsOrder = 'DESC';
}

/**
 * 主题设置
 */
function themeConfig($form) {
    $defaultBanner=new Typecho_Widget_Helper_Form_Element_Text('defaultBanner', NULL, 'https://i.loli.net/2019/01/16/5c3e0b5c98bfd.jpeg', '默认顶部大图', '可以填写随机图 API。');
    $form->addInput($defaultBanner);
    $ajaxIndex=new Typecho_Widget_Helper_Form_Element_Select('ajaxIndex',array('0'=>'分页','1'=>'加载更多'),'0','首页分页样式','选择首页分页样式：普通分页或者加载更多。');
    $form->addInput($ajaxIndex);
    $titleinbanner=new Typecho_Widget_Helper_Form_Element_Select('titleinbanner',array('0'=>'否','1'=>'是'),'1','将标题显示在头图中','是否将标题显示在头图中。');
    $form->addInput($titleinbanner);
    $indexBannerTitle=new Typecho_Widget_Helper_Form_Element_Text('indexBannerTitle', NULL, '', '首页顶部大图内文字', '你随意，但不建议太长');
    $form->addInput($indexBannerTitle);

    // 高级设置
    $lazyload=new Typecho_Widget_Helper_Form_Element_Select('lazyload',array('0'=>'不启用','1'=>'启用'),'0','图片懒加载','是否启用图片懒加载。');
    $form->addInput($lazyload);
    $enableMath=new Typecho_Widget_Helper_Form_Element_Select('enableMath',array('0'=>'不启用','1'=>'启用'),'0','启用数学公式解析','是否启用数学公式解析。启用后会多加载 1~2M 的资源。');
    $form->addInput($enableMath);
    $head=new Typecho_Widget_Helper_Form_Element_Textarea('head', NULL, '', 'head 标签输出内容', '统计代码等。');
    $form->addInput($head);
    $footer=new Typecho_Widget_Helper_Form_Element_Textarea('footer', NULL, '', 'footer 标签输出内容', '备案号等。');
    $form->addInput($footer);
    $pjax=new Typecho_Widget_Helper_Form_Element_Select('pjax',array('0'=>'不启用','1'=>'启用'),'0','启用 PJAX (BETA)','是否启用 PJAX。如果你发现站点有点不对劲，又不知道这个选项是啥意思，请关闭此项。');
    $form->addInput($pjax);
    $pjaxreload=new Typecho_Widget_Helper_Form_Element_Textarea('pjaxreload', NULL, NULL, 'PJAX 重载函数', '输入要重载的 JS，如果你发现站点有点不对劲，又不知道这个选项是啥意思，请关闭 PJAX 并留空此项。');
    $form->addInput($pjaxreload);
    $serviceworker=new Typecho_Widget_Helper_Form_Element_Select('serviceworker',array('0'=>'不启用','1'=>'启用'),'0','Service Worker','是否启用 Service Worker。Service Worker 可以使浏览器主动缓存静态文件，达到加速效果，但是可能导致某些异常。若要启用，请把主题 assets 文件夹下的 sw-toolbox.js 复制一份到<b>站点根目录</b>。');
    $form->addInput($serviceworker);

    // 超高级设置
    $advance=new Typecho_Widget_Helper_Form_Element_Textarea('advance', NULL, NULL, 超高级设置, '主题中包含一份 advanceSetting.sample.json，自己仿照着写吧。');
    $form->addInput($advance);
}

/**
 * 文章自定义字段
 */
function themeFields(Typecho_Widget_Helper_Layout $layout) {
    $banner = new Typecho_Widget_Helper_Form_Element_Text('banner', NULL, NULL, '文章主图', '输入图片URL，该图片会用于主页文章列表的显示。');
    $layout->addItem($banner);
    $bannerasheadimg = new Typecho_Widget_Helper_Form_Element_Select('bannerasheadimg',array('1'=>'是','0'=>'否'),'1','主图显示在文章顶部','默认显示。但你可以强行不显示，转而使用默认头图。');
    $layout->addItem($bannerasheadimg);
    $bannerascover = new Typecho_Widget_Helper_Form_Element_Select('bannerascover',array('1'=>'是','0'=>'否'),'1','主图作为首页文章封面','默认显示。但你可以强行不显示。');
    $layout->addItem($bannerascover);
    $showTOC=new Typecho_Widget_Helper_Form_Element_Select('showTOC',array('0'=>'不显示目录','1'=>'显示目录'),'0','文章目录','是否显示文章目录。');
    $layout->addItem($showTOC);
    $excerpt = new Typecho_Widget_Helper_Form_Element_Textarea('excerpt', NULL, NULL, '文章摘要', '输入自定义摘要。留空自动从文章截取。');
    $layout->addItem($excerpt);
}

$GLOBALS['VOIDSetting'] = Utils::getVOIDSettings();