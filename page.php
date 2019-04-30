<?php
/**
 * page.php
 * 
 * 独立页面
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

if(!Utils::isPjax()){
    $this->need('includes/head.php');
    $this->need('includes/header.php');
} 

$this->need('includes/main.php');

if(!Utils::isPjax()){
    $this->need('includes/footer.php');
}