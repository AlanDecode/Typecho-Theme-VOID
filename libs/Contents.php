<?php
/**
 * Contents.php
 * 
 * 解析器等内容处理相关
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.01
 */

Class Contents
{
    /**
     * 根据 cid 返回文章对象
     * 
     * @return Widget_Abstract_Contents
     */
    public static function getPost($cid)
    {
        $db = Typecho_Db::get();
        $post = new Widget_Abstract_Contents(Typecho_Request::getInstance(), Typecho_Widget_Helper_Empty::getInstance());
        $db->fetchRow($post->select()
            ->where("cid = ?", $cid)
            ->limit(1),
            array($post, 'push'));
        return $post;
    }

    /**
     * 根据 cid 返回评论对象
     * 
     * @return Widget_Abstract_Comments
     */
    public static function getComment($coid)
    {
        $db = Typecho_Db::get();
        $comment = new Widget_Abstract_Comments(Typecho_Request::getInstance(), Typecho_Widget_Helper_Empty::getInstance());
        $db->fetchRow($comment->select()
            ->where("coid = ?", $coid)
            ->limit(1),
            array($comment, 'push'));
        return $comment;
    }

    /**
     * 根据 mid 返回 meta 对象
     * 
     * @return Widget_Abstract_Metas
     */
    public static function getMeta($mid)
    {
        $db = Typecho_Db::get();
        $meta = new Widget_Abstract_Metas(Typecho_Request::getInstance(), Typecho_Widget_Helper_Empty::getInstance());
        $db->fetchRow($meta->select()
            ->where("mid = ?", $mid)
            ->limit(1),
            array($meta, 'push'));
        return $meta;
    }

    /**
     * 输出完备的标题
     * 
     * @return void
     */
    public static function title(Widget_Archive $archive)
    {
        $archive->archiveTitle(array(
            'category'  =>  '分类 %s 下的文章',
            'search'    =>  '包含关键字 %s 的文章',
            'tag'       =>  '标签 %s 下的文章',
            'author'    =>  '%s 发布的文章'
        ), '', ' - ');
        Helper::options()->title();
    }

    /**
     * 内容解析点钩子
     * 目录解析移至前端完成
     */
    static public function contentEx($data, $widget, $last)
    {
        $text = empty($last)?$data:$last;
        if ($widget instanceof Widget_Archive) {
            $text = self::parseRuby($text);
            $text = self::parseFancyBox($text, $widget->parameter->__get('type') == 'feed');
            $text = self::parseBiaoQing($text);
            $text = self::parsePhotoSet($text);
            $text = self::parseNotice($text);
            $text = self::parseHeader($text);
        }
        return $text;
    }

    /**
     * 摘要解析点钩子
     */
    static public function excerptEx($data, $widget, $last)
    {
        $text = empty($last)?$data:$last;
        if ($widget instanceof Widget_Archive) {
            $text = self::parseRuby($text);
            $text = self::parseBiaoQing($text);
            $text = self::parseNotice($text);
            // 去除照片集标记
            $text = str_replace('[photos]', '', $text);
            $text = str_replace('[/photos]', '', $text);
        }
        return $text;
    }

    /**
     * 解析文章内 h2 ~ h5 元素
     * 
     * @return string
     */
    static public function parseHeader($content)
    {
        $reg='/\<h([2-6])(.*?)\>(.*?)\<\/h.*?\>/s';
        $new = preg_replace_callback($reg, array('Contents', 'parseHeaderCallback'), $content);
        return $new;
    }

    /**
     * 为内容中的 h2-h6 元素编号
     */
    static private $CurrentTocID = 0;
    static public function parseHeaderCallback($matchs)
    {
        // 增加单独标记，否则冲突
        $id = 'toc_'.(self::$CurrentTocID++);
        return '<h'.$matchs[1].$matchs[2].' id="'.$id.'">'.$matchs[3].'</h'.$matchs[1].'>';
    }

    /**
     * 解析提示块
     * 
     * @return string
     */
    static public function parseNotice($content)
    {
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
    static public function parsePhotoSet($content)
    {
        $setting = $GLOBALS['VOIDSetting'];

        // 清除无用 tag
        $reg = '/\[photos(.*?)\/photos\]/s';
        $new = preg_replace_callback($reg, array('Contents', 'parsePhotoSetCallBack'), $content);
        $reg='/<p>\[photos.*?\](.*?)\[\/photos\]<\/p>/s';
        $rp='';

        if($setting['largePhotoSet']) {
            $rp = '<div class="photos large">${1}</div>';
        }
        else {
            $rp = '<div class="photos">${1}</div>';
        }

        $new=preg_replace($reg, $rp, $new);
        return $new;
    }

    /**
     * 解析照片集回调函数
     * 
     * @return string
     */
    private static function parsePhotoSetCallBack($match)
    {
        return '[photos'. str_replace(['<br>', '<p>', '</p>'], '', $match[1]) .'/photos]';
    }

    /**
     * 解析表情
     * 
     * @return string
     */
    static public function parseBiaoQing($content)
    {
        $content = preg_replace_callback('/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血)\s*\)/is',
            array('Contents', 'parsePaopaoBiaoqingCallback'), $content);
        $content = preg_replace_callback('/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is',
            array('Contents', 'parseAruBiaoqingCallback'), $content);
        $content = preg_replace_callback('/\:\&\(\s*(.*?)\s*\)/is',
            array('Contents', 'parseQuyinBiaoqingCallback'), $content);
        $content = preg_replace_callback('/\:\$\(\s*(.*?)\s*\)/is',
            array('Contents', 'parse2233BiaoqingCallback'), $content);

        return $content;
    }

    /**
     * 泡泡表情回调函数
     * 
     * @return string
     */
    private static function parsePaopaoBiaoqingCallback($match)
    {
        return '<img class="biaoqing" src="/usr/themes/VOID/assets/libs/owo/biaoqing/paopao/'. str_replace('%', '', urlencode($match[1])) . '_2x.png">';
    }

    /**
     * 阿鲁表情回调函数
     * 
     * @return string
     */
    private static function parseAruBiaoqingCallback($match)
    {
        return '<img class="biaoqing" src="/usr/themes/VOID/assets/libs/owo/biaoqing/aru/'. str_replace('%', '', urlencode($match[1])) . '_2x.png">';
    }

    /**
     * 蛆音娘表情回调函数
     * 
     * @return string
     */
    private static function parseQuyinBiaoqingCallback($match)
    {
        return '<img class="biaoqing" src="/usr/themes/VOID/assets/libs/owo/biaoqing/quyin/'. str_replace('%', '', urlencode($match[1])) . '.png">';
    }

    /**
     * 2233娘表情回调函数
     *
     * @return string
     */
    private static function parse2233BiaoqingCallback($match)
    {
        return '<img class="biaoqing" src="/usr/themes/VOID/assets/libs/owo/biaoqing/2233/'. str_replace('%', '', urlencode($match[1])) . '.png">';
    }

    /**
     * 解析 fancybox
     * 
     * @return string
     * @param photoMode false: 普通解析，true: RSS(不包裹 a 标签)
     */
    static private $photoMode = false;
    static public function parseFancyBox($content, $photoMode = false)
    {
        $reg = '/<img.*?src="(.*?)".*?alt="(.*?)".*?>/s';
        self::$photoMode = $photoMode;
        $new = preg_replace_callback($reg, array('Contents', 'parseFancyBoxCallback'), $content);
        return $new;
    }

    /**
     * 根据 CDN 类型生成占位图片
     */
    public static function genBluredPlaceholderSrc($src)
    {
        $setting = $GLOBALS['VOIDSetting'];
        $cdn_config = $setting['CDNType'];
        $addons = array(
            "UPYUN" => '!/max/64',
            "QINIU" => '?imageView2/2/w/64/q/75'
        );

        $components = parse_url($src);
        $cdn = '';
        if (array_key_exists($components['host'], $cdn_config)) {
            $cdn = $cdn_config[$components['host']];
        }

        $addon = '';
        if (array_key_exists($cdn, $addons)) {
            $addon = $addons[$cdn];
        }

        return str_replace('#'.parse_url($src)['fragment'], '', $src).$addon;
    }

    /**
     * 解析图片（正常文章）
     * 
     * @return string
     */
    private static function parseFancyBoxCallback($match)
    {
        $setting = $GLOBALS['VOIDSetting'];
        $src_ori = $match[1];
        $src = $src_ori;
        $classList = '';

        // 这里，若图片已获取长宽基础信息，则直接计算后输出
        $attrAddOnA = '';
        $attrAddOnFigure = '';
        $matches;
        if (strpos($src_ori, 'vwid') != false) {
            preg_match("/vwid=(\d{0,5})/i", $src_ori, $matches);
            $width = floatval($matches[1]);
            preg_match("/vhei=(\d{0,5})/i", $src_ori, $matches);
            $height = floatval($matches[1]);

            $ratio = $height / $width * 100;
            $flex_grow = $width * 50 / $height;

            $attrAddOnA = 'style="padding-top: '.$ratio.'%"';
            $attrAddOnFigure = 'class="size-parsed" style="flex-grow: '.$flex_grow.'; width: '.$width.'px"';
        }

        $figcaption = '';
        if ($match[2] != '' && $setting['parseFigcaption'])
            $figcaption = '<figcaption>'.$match[2].'</figcaption>';

        // 普通解析且开启懒加载
        $placeholder = '';
        if(!self::$photoMode && Helper::options()->lazyload == '1') {
            $src = '';
            $classList = 'lazyload';
            if ($setting['bluredLazyload'])
                $placeholder = '<img class="blured-placeholder remove-after" src="'.self::genBluredPlaceholderSrc($src_ori).'">';

            $attrAddOnA .= ' class="lazyload-container" ';
        }

        $img = $placeholder.'<img class="'.$classList.'" alt="'.$match[2].'" data-src="'.$src_ori.'" src="'.$src.'">';

        if (!self::$photoMode) {
            return '<figure '.$attrAddOnFigure.' ><a '.$attrAddOnA.' no-pjax data-fancybox="gallery" data-caption="'.$match[2].'" href="'.$src_ori.'">'.$img.'</a>'.$figcaption.'</figure>';
        } else {
            return '<figure>'.$img.$figcaption.'</figure>';
        }
    }

    /**
     * 解析友情链接
     * 
     * @return string
     */
    static public function markdown($text)
    {
        // 去除换行
        $reg = '/\[links.*?\](.*?)\[\/links\]/s';
        $text = preg_replace_callback($reg, array('Contents', 'parseBoardCallback1'), $text);

        // 向前兼容
        $reg = '/<div class="board-list link-list">(.*?)<\/div>/s';
        $text = preg_replace_callback($reg, array('Contents', 'parseBoardCallback1'), $text);

        $reg = '/\[links.*?\](.*?)\[\/links\]/s';
        $text = preg_replace_callback($reg, array('Contents', 'parseBoardCallback2'), $text);

        if (0 == strpos($text, '<!--markdown-->')) {
            $text = str_replace("```objective-c", "```objectivec", $text);
            $text = str_replace("```c++", "```cpp", $text);
            $text = str_replace("```c#", "```csharp", $text);
            $text = str_replace("```f#", "```fsharp", $text);
            $text = str_replace("```F#", "```Fsharp", $text);
            $text = Markdown::convert($text);
        }

        return $text;
    }

    /**
     * 去除换行
     * 
     * @return string
     */
    static function parseBoardCallback1($matchs)
    {
        $text =  str_replace(array("\r\n", "\r", "\n"), "", $matchs[1]);
        return '[links]'.$text.'[/links]';
    }

    /**
     * 解析友链列表
     * 
     * @return string
     */
    static function parseBoardCallback2($matchs)
    {
        $text = '<div class="board-list link-list">%boards%</div>';

        $reg='/\[(.*?)\]\((.*?)\)\+\((.*?)\)/s';
        $rp = '<a target="_blank" href="${2}" class="board-item link-item"><div class="board-thumb" data-thumb="${3}"></div><div class="board-title">${1}</div></a>';
        $boards = preg_replace($reg,$rp,$matchs[1]);

        return  str_replace('%boards%', $boards, $text);
    }

    /**
     * 解析 ruby
     * 
     * @return string
     */
    static public function parseRuby($string)
    {
        $reg='/\{\{(.*?):(.*?)\}\}/s';
        $rp='<ruby>${1}<rp>(</rp><rt>${2}</rt><rp>)</rp></ruby>';
        $new=preg_replace($reg,$rp,$string);
        return $new;
    }

    /**
     * 最近评论，过滤引用通告，过滤博主评论
     * 
     * @return array
     */
    public static function getRecentComments($num = 10)
    {
        $output = array();

        $db = Typecho_Db::get();
        $rows = $db->fetchAll($db->select()->from('table.comments')->where('table.comments.status = ?', 'approved')
        ->where('type = ?', 'comment')
        ->where('ownerId <> authorId')
        ->order('table.comments.created', Typecho_Db::SORT_DESC)
        ->limit($num));

        foreach ($rows as $row) {
            $comment = self::getComment($row['coid']);
            $output[] = array(
                'permalink' => $comment->permalink,
                'mail' => $row['mail'],
                'author' => $row['author'],
            );
        }

        return $output;
    }

    /**
     * 文章上一篇
     */
    public static function thePrev($archive)
    {
        $db = Typecho_Db::get();
        $content = $db->fetchRow($db->select()->from('table.contents')->where('table.contents.created < ?', $archive->created)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $archive->type)
            ->where('table.contents.password IS NULL')
            ->order('table.contents.created', Typecho_Db::SORT_DESC)
            ->limit(1));

        if ($content) {
            return self::getPost($content['cid']);    
        } else {
            return null;
        }
    }

    /**
     * 文章下一篇
     */
    public static function theNext($archive)
    {
        $db = Typecho_Db::get();
        $content = $db->fetchRow($db->select()->from('table.contents')->where('table.contents.created > ? AND table.contents.created < ?',
            $archive->created, Helper::options()->gmtTime)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $archive->type)
            ->where('table.contents.password IS NULL')
            ->order('table.contents.created', Typecho_Db::SORT_ASC)
            ->limit(1));

        if ($content) {
            return self::getPost($content['cid']);    
        } else {
            return null;
        }
    }

    /**
     * 内容归档
     * 
     * @return array
     */
    public static function archives($widget, $excerpt = false)
    {
        $db = Typecho_Db::get();
        $rows = $db->fetchAll($db->select()
                    ->from('table.contents')
                    ->order('table.contents.created', Typecho_Db::SORT_DESC)
                    ->where('table.contents.type = ?', 'post')
                    ->where('table.contents.status = ?', 'publish')
                    ->where('table.contents.created < ?', time()));

        $stat = array();
        foreach ($rows as $row) {
            $row = $widget->filter($row);
            $arr = array(
                'title' => $row['title'],
                'permalink' => $row['permalink']);

            if(Utils::isPluginAvailable('VOID')) {
                $arr['words'] = $row['wordCount'];
            }
            
            if($excerpt){
                $arr['excerpt'] = substr($row['content'], 30);
            }
            $stat[date('Y', $row['created'])][$row['created']] = $arr;
        }
        return $stat;
    }

    /**
     * 文章标签
     * 
     * @return array
     */
    public static function getTags($cid)
    {
        $db = Typecho_Db::get();
        $rows = $db->fetchAll($db->select('mid')
            ->from('table.relationships')
            ->where("cid = ?", $cid));
        
        $metas = array();
        foreach ($rows as $row) {
            $meta = self::getMeta($row['mid']);
            if ($meta->type == 'tag') {
                $meta = array('name' => $meta->name,
                    'permalink' => $meta->permalink);
                $metas[] = $meta;
            }
        }

        return $metas;
    }
}