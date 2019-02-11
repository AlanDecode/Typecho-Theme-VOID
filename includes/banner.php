<?php
/** 
 * banner.php
 *  
 * @author      熊猫小A
 * @version     2019-01-17 0.1
 * 
*/ 
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
?>

<?php if(!Utils::isWeixin()): ?>
    <?php $lazyID = rand(1,10000); ?>
    <div class="lazy-wrap loading"><div id="banner" data-lazy-id=<?php echo $lazyID; ?> class="lazy"></div></div>
    <?php 
        if($this->is('post') || $this->is('page')){
            Utils::registerLazyImg($this->fields->banner != '' ? $this->fields->banner : $setting['defaultBanner'], $lazyID); 
        }else{
            Utils::registerLazyImg($setting['defaultBanner'], $lazyID);
        }
    ?>
<?php else: ?>
    <div class="lazy-wrap"><div id="banner" style="background-image:url(<?php echo $this->fields->banner != '' ? $this->fields->banner : $setting['defaultBanner']; ?>)" class="lazy loaded"></div></div>
<?php endif; ?>