<?php
/** 
 * 404.php
 *  
 * @author      熊猫小A
 * @version     2019-01-17 0.1
 * 
*/ 
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
?>

<main id="pjax-container">
    <title hidden>
        <?php Contents::title($this); ?>
    </title>

    <?php $this->need('includes/banner.php'); ?>
    <style>
        main {background: #000}
        footer {display:none}
        body {overflow: hidden}
    </style>
</main>
