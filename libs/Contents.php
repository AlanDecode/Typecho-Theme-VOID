<?php
/**
 * Contents.php
 * 
 * 解析器等内容处理相关
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.01
 */


global $toc;
global $curid;
function parseTOC_callback($matchs){
    $GLOBALS['curid']=$GLOBALS['curid']+1;
    $GLOBALS['toc'].='<li><a href="#TOC-'.(string)$GLOBALS['curid'].'" onclick="$.scrollTo(`#TOC-'.(string)$GLOBALS['curid'].'`,500);" class="toc-item toc-level-'.$matchs[1].'">'.$matchs[2].'</a></li>';
    return '<h'.$matchs[1].' id="TOC-'.(string)$GLOBALS['curid'].'">'.$matchs[2].'</h'.$matchs[1].'>';
}
function parseBoard_callback($matchs){
    return '<a target="_blank" href="'.$matchs[2].'" class="board-item link-item"><div class="board-thumb" style="background-image:url('.$matchs[3].')"></div><div class="board-title">'.$matchs[1].'</div></a>';
}
Class Contents
{
    /**
     * 根据 cid 返回文章对象
     * 
     * @return Widget_Abstract_Contents
     */
    public static function getPost($cid)
    {
        return Helper::widgetById('contents', $cid);
    }

    /**
     * 根据 cid 返回评论对象
     * 
     * @return Widget_Abstract_Comments
     */
    public static function getComment($coid)
    {
        return Helper::widgetById('contents', $coid);
    }

    /**
     * 输出完备的标题
     * 
     * @return void
     */
    public static function title(Widget_Archive $archive)
    {
        $archive->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - ');
        Helper::options()->title();
    }

    /**
     * 输出 head 标签中的 meta
     * 
     * @return void
     */
    public static function exportHead(Widget_Archive $archive,$img)
    {
        echo '<title>';
		self::title($archive);
		echo '</title>';
        $html = '';
        $site=Helper::options()->title;
        $description='';
        $createTime = date('c', $archive->created);
        $modifyTime = date('c', $archive->modified);
        $link=$archive->permalink;
        $type='';
        $author=$archive->author->screenName;
        if($archive->is("index")){
            $description=Helper::options()->description;
            $type='website';
        }
        elseif ($archive->is("post") || $archive->is("page")) {
            if($archive->fields->excerpt && $archive->fields->excerpt!=''){
                $description=$archive->fields->excerpt;
            }
            else{
                $description = Typecho_Common::subStr(strip_tags($archive->excerpt), 0, 100, "...");
            }
            $type='article';
        }

        echo '<meta name="description" content="';
        echo $description;
        echo '" />
<meta property="og:title" content="';
        self::title($archive);
        $html = <<< EOF
" />
<meta name="author" content="{$author}" />
<meta property="og:site_name" content="{$site}" />
<meta property="og:type" content="{$type}" />
<meta property="og:description" content="{$description}" />
<meta property="og:url" content="{$link}" />
<meta property="og:image" content="{$img}" />
<meta property="article:published_time" content="{$createTime}" />
<meta property="article:modified_time" content="{$modifyTime}" />
<meta name="twitter:title" content="
EOF;
        echo $html;
        self::title($archive);
        $html = <<<EOF
" />
<meta name="twitter:description" content="{$description}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:image" content="{$img}" />\n
EOF;
        echo $html;
    }

    /**
     * 解析器：文章内容
     * 
     * @return array
     */
    static public function parseAll($content, $parseTOC = false)
    {
        $result = array();
        $newContent = self::parseNotice(self::parsePhotoSet(self::parseBiaoQing(self::parseFancyBox(self::parseRuby($content)))));
        if($parseTOC)
        {
            global $toc;
            $GLOBALS['curid']=0;
            $GLOBALS['toc']='<ul id="toc-ul">';
            $result['content'] = preg_replace_callback('/<h([2-6]).*?>(.*?)<\/h.*?>/s', 'parseTOC_callback', $newContent);
            $GLOBALS['toc'].='</ul>';
            $result['toc'] = $toc;
        }
        else
        {
            $result['content'] = $newContent;
            $result['toc'] = '';
        }
        
        return $result;
    }

    /**
     * 解析提示块
     * 
     * @return string
     */
    static public function parseNotice($content){
        $reg='/\[notice.*?\](.*?)\[\/notice\]/s';
        $rp='<p class="notice">${1}</p>';
        $new=preg_replace($reg,$rp,$content);
        return $new;
    }

    /**
     * 解析照片集
     *
     * @return string
     */
    static public function parsePhotoSet($content){
        $reg='/\[photos.*?des="(.*?)"\](.*?)\[\/photos\]/s';
        $rp='<div class="photos" data-des="${1}">${2}</div>';
        $new=preg_replace($reg,$rp,$content);
        return $new;
    }

    /**
     * 解析表情
     * 
     * @return string
     */
    static public function parseBiaoQing($content){
        $content = preg_replace_callback('/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血)\s*\)/is',
            array('Contents', 'parsePaopaoBiaoqingCallback'), $content);
        $content = preg_replace_callback('/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is',
            array('Contents', 'parseAruBiaoqingCallback'), $content);

        return $content;
    }

    /**
     * 泡泡表情回调函数
     * 
     * @return string
     */
    private static function parsePaopaoBiaoqingCallback($match){
        return '<img class="biaoqing" src="/usr/themes/VOID/assets/owo/biaoqing/paopao/'. str_replace('%', '', urlencode($match[1])) . '_2x.png">';
    }

    /**
     * 阿鲁表情回调函数
     * 
     * @return string
     */
    private static function parseAruBiaoqingCallback($match){
        return '<img class="biaoqing" src="/usr/themes/VOID/assets/owo/biaoqing/aru/'. str_replace('%', '', urlencode($match[1])) . '_2x.png">';
    }

    /**
     * 解析 fancybox
     * 
     * @return string
     */
    static public function parseFancyBox($content){
        $reg='/<img(.*?)src="(.*?)"(.*?)>/s';
        $rp='<a data-fancybox="gallery" href="${2}"><img${1}src="${2}"${3}></a>';
        $new=preg_replace($reg,$rp,$content);
        return $new;
    }

    /**
     * 解析友情链接
     * 
     * @return string
     */
    static public function parseBoard($string){
        $reg='/\[(.*?)\]\((.*?)\)\+\((.*?)\)/s';
        $new=preg_replace_callback($reg, 'parseBoard_callback', $string);
        return $new;
    }

    /**
     * 解析 ruby
     * 
     * @return string
     */
    static public function parseRuby($string){
        $reg='/\{\{(.*?):(.*?)\}\}/s';
        $rp='<ruby>${1}<rp>(</rp><rt>${2}</rt><rp>)</rp></ruby>';
        $new=preg_replace($reg,$rp,$string);
        return $new;
    }

    /**
     * 文章上一篇
     */
    public static function thePrev($archive){
        $db = Typecho_Db::get();
        $content = $db->fetchRow($db->select()->from('table.contents')->where('table.contents.created < ?', $archive->created)
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.type = ?', $archive->type)
        ->where('table.contents.password IS NULL')
        ->order('table.contents.created', Typecho_Db::SORT_DESC)
        ->limit(1));

        if ($content) {
            $content = $archive->filter($content);
            echo '<a href="'.$content['permalink'].'">← 上一篇</a>';
        } else {
            echo '<span>没有啦~</span>';
        }

    }

    /**
     * 文章下一篇
     */
    public static function theNext($archive){
        $db = Typecho_Db::get();
        $content = $db->fetchRow($db->select()->from('table.contents')->where('table.contents.created > ? AND table.contents.created < ?',
            $archive->created, Helper::options()->gmtTime)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $archive->type)
            ->where('table.contents.password IS NULL')
            ->order('table.contents.created', Typecho_Db::SORT_ASC)
            ->limit(1));

        if ($content) {
            $content = $archive->filter($content);
            echo '<a href="'.$content['permalink'].'">下一篇 →</a>';
        } else {
            echo '<span>没有啦~</span>';
        }
    }

    /**
     * 内容归档
     * 
     * @return array
     */
    public static function archives($excerpt = false){
        error_reporting(E_ALL & ~E_NOTICE);
        $db = Typecho_Db::get();
        $cids = $db->fetchAll($db->select('table.contents.cid')
                    ->from('table.contents')
                    ->order('table.contents.created', Typecho_Db::SORT_DESC)
                    ->where('table.contents.type = ?', 'post')
                    ->where('table.contents.status = ?', 'publish'));
        $stat = array();
        foreach ($cids as $cid) {
            $post = Helper::widgetById('contents', $cid);
            $arr = array(
                'title' => $post->title,
                'permalink' => $post->permalink,
                'words' => mb_strlen(preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $post->content), 'UTF-8'));
            if($excerpt){
                $arr['excerpt'] = substr($post->content, 30);
            }
            $stat[date('Y', $post->created)][$post->created] = $arr;
        }
        return $stat;
    }
}