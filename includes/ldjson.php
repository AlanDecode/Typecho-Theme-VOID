<?php
/**
 * main.php
 * 
 * 内容页面主要区域，PJAX 作用区域
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
?>

<?php if ($this->is('post')): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "publisher": {
        "@type": "Organization",
        "name": "<?php Helper::options()->title() ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php Utils::gravatar($this->author->mail, 200); ?>"
        }
    },
    "author": {
        "@type": "Person",
        "name": "<?php $this->author->screenName(); ?>",
        "image": {
            "@type": "ImageObject",
            "url": "<?php Utils::gravatar($this->author->mail, 400); ?>",
            "width": 400,
            "height": 400
        },
        "url": "<?php $this->author->permalink(); ?>"
    },
    "headline": "<?php Contents::title($this); ?>",
    "url": "<?php $this->permalink(); ?>",
    "datePublished": "<?php echo date('c', $this->created); ?>",
    "dateModified": "<?php echo date('c', $this->modified); ?>",
    "image": {
        "@type": "ImageObject",
        <?php $banner = $this->fields->banner; if(!$banner) $banner = $setting['defaultBanner']; ?>
        "url": "<?php echo $banner; ?>"
    },
    "description": "<?php echo $this->fields->excerpt; ?>",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php Utils::index("/"); ?>"
    }
}
</script>
<?php elseif ($this->is('page')): ?>
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "WebPage",
    "name": "<?php Contents::title($this); ?>",
    "description": "<?php echo $this->fields->excerpt; ?>",
    "publisher": {
        "@type": "Organization",
        "name": "<?php Helper::options()->title() ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php Utils::gravatar($this->author->mail, 200); ?>"
        }
    }
}
</script>
<?php elseif ($this->is('index')): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "publisher": {
        "@type": "Organization",
        "name": "<?php Helper::options()->title() ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php Utils::gravatar($this->author->mail, 200); ?>"
        }
    },
    "url": "<?php Utils::index("/"); ?>",
    "image": {
        "@type": "ImageObject",
        "url": "<?php echo $setting['defaultBanner'] ?>"
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php Utils::index("/"); ?>"
    },
    "description": "<?php echo Helper::options()->description; ?>"
}
</script>
<?php elseif ($this->is('archive')): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Series",
    "url": "<?php Utils::index($_SERVER['PHP_SELF']); ?>",
    "name": "<?php Contents::title($this); ?>",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php Utils::index("/"); ?>"
    }
}
</script>
<?php endif; ?>