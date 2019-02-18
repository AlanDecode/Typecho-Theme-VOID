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
    $defaultBanner=new Typecho_Widget_Helper_Form_Element_Text('defaultBanner', NULL, 'https://i.loli.net/2019/02/11/5c614078f2263.png', '默认顶部大图', '可以填写随机图 API。');
    $form->addInput($defaultBanner);
    $desktopBannerHeight=new Typecho_Widget_Helper_Form_Element_Text('desktopBannerHeight', NULL, NULL, '桌面端顶部大图高度', '填写数值，数值代表了高度相对屏幕高度的百分比。例如填写 70 即代表头图高度是屏幕高度的 70%。');
    $form->addInput($desktopBannerHeight);
    $mobileBannerHeight=new Typecho_Widget_Helper_Form_Element_Text('mobileBannerHeight', NULL, NULL, '移动端顶部大图高度', '填写数值，数值代表了高度相对屏幕高度的百分比。例如填写 70 即代表头图高度是屏幕高度的 70%。');
    $form->addInput($mobileBannerHeight);
    $ajaxIndex=new Typecho_Widget_Helper_Form_Element_Select('ajaxIndex',array('0'=>'分页','1'=>'加载更多'),'0','首页分页样式','选择首页分页样式：普通分页或者加载更多。');
    $form->addInput($ajaxIndex);
    
    // 高级设置
    $enableMath=new Typecho_Widget_Helper_Form_Element_Select('enableMath',array('0'=>'不启用','1'=>'启用'),'0','启用数学公式解析','是否启用数学公式解析。启用后会多加载 1~2M 的资源。');
    $form->addInput($enableMath);
    $head=new Typecho_Widget_Helper_Form_Element_Textarea('head', NULL, '', 'head 标签输出内容', '统计代码等');
    $form->addInput($head);
    $footer=new Typecho_Widget_Helper_Form_Element_Textarea('footer', NULL, '', 'footer 标签输出内容', '备案号等');
    $form->addInput($footer);
    $pjax=new Typecho_Widget_Helper_Form_Element_Select('pjax',array('0'=>'不启用','1'=>'启用'),'0','启用 PJAX (BETA)','是否启用 PJAX。如果你发现站点有点不对劲，又不知道这个选项是啥意思，请关闭此项。');
    $form->addInput($pjax);
    $pjaxreload=new Typecho_Widget_Helper_Form_Element_Textarea('pjaxreload', NULL, NULL, 'PJAX 重载函数', '输入要重载的 JS，如果你发现站点有点不对劲，又不知道这个选项是啥意思，请关闭 PJAX 并留空此项。');
    $form->addInput($pjaxreload);

    // 超高级设置
    $advance=new Typecho_Widget_Helper_Form_Element_Textarea('advance', NULL, NULL, 超高级设置, '主题中包含一份 advanceSetting.sample.json，自己仿照着写吧。');
    $form->addInput($advance);
}

/**
 * 文章自定义字段
 */
function themeFields(Typecho_Widget_Helper_Layout $layout) {
    $banner = new Typecho_Widget_Helper_Form_Element_Text('banner', NULL, NULL, '文章主图', '输入图片URL，该图片会用于主页文章列表的显示');
    $layout->addItem($banner);
    $showTOC=new Typecho_Widget_Helper_Form_Element_Select('showTOC',array('0'=>'不显示目录','1'=>'显示目录'),'0','文章目录','是否显示文章目录');
    $layout->addItem($showTOC);
}

$GLOBALS['VOIDSetting'] = Utils::getVOIDSettings();