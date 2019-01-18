<?php
/**
 * comments.php
 * 
 * 评论区
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

<section id="comments">
    <div class="section-title">Comments</div>
    <?php
    $parameter = array(
        'parentId'      => $this->hidden ? 0 : $this->cid,
        'parentContent' => $this->row,
        'respondId'     => $this->respondId,
        'commentPage'   => $this->request->filter('int')->commentPage,
        'allowComment'  => $this->allow('comment')
    );
    $this->widget('VOID_Widget_Comments_Archive', $parameter)->to($comments);
    ?>
    <?php $this->header('commentReply=1&description=0&keywords=0&generator=0&template=0&pingback=0&xmlrpc=0&wlw=0&rss2=0&rss1=0&antiSpam=0&atom'); ?>
    <div id="<?php $this->respondId(); ?>" class="respond">
        <div class="cancel-comment-reply">
            <?php $comments->cancelReply(); ?>
        </div>
        <h3 id="response" class="widget-title text-left">添加新评论</h3>
        <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form">
            <?php if($this->user->hasLogin()): ?>
            <p id="logged-in" data-name="<?php $this->user->screenName(); ?>" data-url="<?php $this->user->url(); ?>" data-email="<?php $this->user->mail(); ?>" ><?php _e('登录身份: '); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>. <a no-pjax href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a></p>
            <?php else: ?>
                <div class="comment-info-input">
                <input type="text" name="author" id="author" placeholder="称呼(必填)" value="<?php $this->remember('author'); ?>" />
                <input type="email" name="mail" id="mail" placeholder="电子邮件<?php echo Helper::options()->commentsRequireMail? '(必填，将保密)' : '(选填)' ?>" value="<?php $this->remember('mail'); ?>" />
                <input type="url" name="url" id="url" placeholder="网站<?php echo Helper::options()->commentsRequireURL? '(必填)' : '(选填)' ?>"  value="<?php $this->remember('url'); ?>" />
                </div>
            <?php endif; ?>
            <p style="margin-top:0">
                <textarea class="input-area" rows="5" name="text" id="textarea" placeholder="在这里输入你的评论..." style="resize:none;"><?php $this->remember('text'); ?></textarea>
            </p>
            <p class="comment-buttons">
                <span class="OwO"></span>
                <span class="comment-mail-me">
                    <input name="receiveMail" type="checkbox" value="yes" id="receiveMail" checked />
                    <label for="receiveMail"><strong>接收</strong>邮件通知</label>
                </span>
                <button id="comment-submit-button" type="submit" class="submit btn btn-normal"><?php _e('提交评论'); ?></button>
            </p>
        </form>
    </div>
    <?php if ($comments->have()): ?>
        <h3 class="comment-separator">
            <div class="comment-tab-current">
                <span class="comment-num"><?php $this->commentsNum(_t('评论列表'), _t('已有 1 条评论'), _t('已有 %d 条评论')); ?></span>
            </div>
        </h3>
        <?php $comments->listComments(array(
        'before'        =>  '<div class="comment-list">',
        'after'         =>  '</div>'
        )); ?>
        <?php $comments->pageNav('←', '→', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
    <?php endif; ?>
    <script>
    var owo = new OwO({
        logo: 'OωO表情',
        container: document.getElementsByClassName('OwO')[0],
        target: document.getElementsByClassName('input-area')[0],
        api: '<?php Utils::indexTheme('/assets/owo/OwO.json'); ?>',
        position: 'down',
        width: '400px',
        maxHeight: '250px'
    });
    </script>
</section>