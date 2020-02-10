<?php
/** 
 * archives
 *  
 * @author      熊猫尝A
 * @version     2019-01-17 0.1
 * 
*/ 
if (!defined('__TYPECHO_ROOT_DIR__')) exit; 

if(!Utils::isPjax()){
    $this->need('includes/head.php');
    $this->need('includes/header.php');
}

if($this->have()){
    $this->need('includes/archives.php');
}else{
    $this->need('includes/404.php');
}

if(!Utils::isPjax()){
    $this->need('includes/footer.php');
}