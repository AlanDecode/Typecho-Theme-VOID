# Typecho Theme VOID 2.0

> ✏ 一款简洁优雅的单栏 Typecho 主题

作为计算机术语时，VOID 的意思是「无类型」。

![](https://raw.githubusercontent.com/AlanDecode/Typecho-Theme-VOID/master/screenshot.png)

[![build status](https://img.shields.io/travis/AlanDecode/Typecho-Theme-VOID/source.svg?style=flat-square)](https://travis-ci.org/AlanDecode/Typecho-Theme-VOID)

## 特性

> 演示站点：[熊猫小A的博客](https://blog.imalan.cn)，介绍文章：[VOID：现在可以公开的情报](https://blog.imalan.cn/archives/247/)。

* PJAX 无刷新体验
* AJAX 评论
* 夜间模式自动切换
* 响应式设计
* 优秀的可读性
* 代码高亮
* MathJax 公式
* 表情解析（文章、评论可用）
* 图片排版（可用作相册）
* 灵活的头图设置
* 目录解析
* 完整的结构化数据支持
* 够用的后台设置与丰富的高级设置
* ...

以及其他很多细节，总之用起来还算舒服。我建立了一个示例页面，在这里你可以看到 VOID 对常用写作元素的支持以及一些特色功能演示：[示例页面](https://blog.imalan.cn/archives/194/)。

## 开始使用

1. 下载主题：[发布版](https://github.com/AlanDecode/Typecho-Theme-VOID/releases) | [开发版](https://github.com/AlanDecode/Typecho-Theme-VOID/archive/nightly.zip)
2. 解压
3. **把解压后的文件夹重命名为 VOID**
4. 检查文件夹名是否为 VOID，不是的话改成 VOID
5. 检查文件夹名是否为 VOID，不是的话改成 VOID
6. 检查文件夹名是否为 VOID，不是的话改成 VOID
7. 上传文件夹至站点 /usr/themes 目录下
8. 后台启用主题

* 可选：将主题 `assets` 文件夹下的 `VOIDCacheRule.js` 复制一份到站点根目录，并在主题设置中启用 Service Worker 缓存。
* 可选：主题文件夹下 advanceSetting.sample.json 中有一些高级设置，可以看看。

## **常见问题（请务必仔细阅读）**

<details><summary>下载安装后样式不对？</summary><br>

仓库中的是未压缩的源代码，包含大量实际使用中不需要的文件，并且可能无法直接使用。请一定通过这两个链接下载主题：[发布版](https://github.com/AlanDecode/Typecho-Theme-VOID/releases) | [开发版](https://github.com/AlanDecode/Typecho-Theme-VOID/archive/nightly.zip)

</details>

<details><summary>添加归档页面</summary><br>

新建独立页面，自定义模板选择 `Archives`，内容留空。

</details>

<details><summary>添加友情链接</summary><br>

新建独立页面，然后如此书写：

```
[links]
[熊猫小A](https://www.imalan.cn)+(https://secure.gravatar.com/avatar/1741a6eef5c824899e347e4afcbaa75d?s=200&r=G&d=)
[熊猫小A的博客](https://blog.imalan.cn)+(https://secure.gravatar.com/avatar/1741a6eef5c824899e347e4afcbaa75d?s=64&r=G&d=)
[/links]
```

文章中、独立页面中都可以通过该语法插入类似的展示块。

</details>

<details><summary>图片排版</summary><br>

在文章中，使用 `[photos][/photos]` 包起来的图片可显示在同一行。例如：

```
[photos]
![](https://cdn.imalan.cn/img/post/2018-10-26/IMG_0073.jpeg)
![](https://cdn.imalan.cn/img/post/2018-10-26/IMG_0053.jpeg)
[/photos]

[photos]
![](https://cdn.imalan.cn/img/post/2018-10-26/IMG_0039.jpeg)
![](https://cdn.imalan.cn/img/post/2018-10-26/IMG_0051.jpeg)
![](https://cdn.imalan.cn/img/post/2018-10-26/IMG_0005.jpeg)
[/photos]
```

</details>

<details><summary>浏览量统计</summary><br>

使用插件：[TePostViews](https://github.com/AlanDecode/TePostViews)

</details>

<details><summary>文章点赞</summary><br>

使用插件：[Like](https://github.com/AlanDecode/Like)

</details>

## 更新

同[开始使用](#开始使用)，区别是你可以直接覆盖主题文件，不禁用主题，这样你的主题设置就不会丢失。

## 开发

<details><summary>展开详情</summary><br>

如果你有不错的想法，可以定制自己的版本。首先你需要准备好 NodeJS 环境，然后 clone 这个 repo：

```bash
git clone https://github.com/AlanDecode/Typecho-Theme-VOID ./VOID && cd ./VOID
```

安装依赖：

```bash
npm install -g gulp
npm install
```

然后将依赖打包：

```bash
gulp dev
```

你可以使用自己喜欢的方式编译 SCSS，或者使用：

```bash
gulp sass
```

监听 SCSS 更改然后实时编译。尽请添加自己想要的功能，满意后就提交代码。然后：

```bash
gulp build
```

构建你的主题，生成的主题位于 `./build` 目录下。如果你对自己的更改很满意，**欢迎提出 Pull Request**。

</details>

## 更新日志

**2019-03-26 Version 2.0.1**

* 修复夜间模式自动切换的 bug
* 开启夜间模式时显示提示
* 高级设置增加更多导航栏模式设置
* 修改背景配色
* 一些其它细小的优化与修复

**2019-03-18 Version 2.0**

🎉 MAKE VOID GREAT AGAIN!

2.0 是一次删繁就简、回归本质的更新。我在这个版本中对代码进行了精简，移除了许多实用性不足的功能。同时对已有的功能、样式进行了细致的优化，只为了能够更舒心地写作与阅读。

* 添加了夜间模式，支持自动切换
* 添加导航栏颜色模式设置（暗色、透明色）
* 导航栏可随滚动显隐
* 优化了头图设置逻辑
* 优化了摘要样式
* 优化了大量细节
* 消灭了大量臭虫

**移除**了以下特性：

* 卡片版首页
* 首页加载更多与无限滚动
* 提示语与欢迎语
* 导航栏位置设置
* 强制首页无大图

更多：[change-log.md](https://github.com/AlanDecode/Typecho-Theme-VOID/blob/master/change-log.md)

## 鸣谢

### 开源项目

[JQuery](https://github.com/jquery/jquery) | [highlight.js](https://highlightjs.org/) | [MathJax](https://www.mathjax.org/) | [fancyBox](http://fancyapps.com/fancybox/3/) | [bigfoot.js](http://www.bigfootjs.com/) | [OwO](https://github.com/DIYgod/OwO) | [pjax](https://github.com/defunkt/jquery-pjax) | [yue.css](https://github.com/lepture/yue.css) | [tocbot](https://tscanlin.github.io/tocbot/) | [pangu.js](https://github.com/vinta/pangu.js) | [social](https://github.com/lepture/social) | [Headroom.js](http://wicky.nillia.ms/headroom.js/)

### 其他

[RAW](https://github.com/AlanDecode/Typecho-Theme-RAW) | [Mirages](https://get233.com/archives/mirages-intro.html) | [handsome](https://www.ihewro.com/archives/489/) | [Card](https://blog.shuiba.co/bitcron-theme-card) | [Casper](https://github.com/TryGhost/Casper) | [Typlog](https://typlog.com/)

## 捐助

**如果本项目对你有所帮助，请考虑捐助我**

![谢谢支持](https://wx1.sinaimg.cn/large/0060lm7Tly1g0c4cbi71lj30sc0iv453.jpg)

## License

MIT © [AlanDecode](https://github.com/AlanDecode)