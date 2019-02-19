# Typecho Theme VOID

> 🐒 猴子打字机原理的产物

[![build status](https://img.shields.io/travis/AlanDecode/Typecho-Theme-VOID/source.svg?style=flat-square)](https://travis-ci.org/AlanDecode/Typecho-Theme-VOID)

作为计算机术语时，VOID 的意思是「无类型」。

## 概览

![](https://raw.githubusercontent.com/AlanDecode/Typecho-Theme-VOID/master/screenshot.png)

## 特性

> 演示站点：[熊猫小A的博客](https://blog.imalan.cn)，介绍文章：[VOID：现在可以公开的情报](https://blog.imalan.cn/archives/247/)。

* PJAX 无刷新体验
* AJAX 评论
* 响应式设计
* 卡片式
* 代码高亮
* MathJax 公式
* 表情解析
* 图片排版
* 目录解析
* ...

总之用起来还算舒服。

## 开始使用

### 安装

**方法一：使用构建好的版本（推荐）**

1. 到 Release 页面下载构建包：[点击前往](https://github.com/AlanDecode/Typecho-Theme-VOID/releases)
2. 解压
3. **把解压后的文件夹重命名为 VOID**
4. 检查文件夹名是否为 VOID，不是的话改成 VOID
5. 检查文件夹名是否为 VOID，不是的话改成 VOID
6. 检查文件夹名是否为 VOID，不是的话改成 VOID
7. 上传文件夹至站点 /usr/themes 目录下
8. 后台启用主题

可选：将主题 `assets` 文件夹下的 `VOIDCacheRule.js` 复制一份到站点根目录，以启用 Service Worker 缓存。
可选：主题文件夹下 advanceSetting.sample.json 中有一些高级设置，可以看看。

**方法二：自己构建**

> 需要安装好 NodeJS

首先，clone 本仓库到本地：

```bash
git clone git@github.com:AlanDecode/Typecho-Theme-VOID.git ./VOID && cd ./VOID
git checkout source
```

然后安装依赖：

```bash
npm install -g gulp
npm install
```

最后构建打包：

```bash
gulp build
```

到此时，新鲜的主题就出现在了 build 文件夹下，把 build 文件夹上传到主题目录，重命名为 VOID，然后启用即可。本方法使你可以使用到最新的主题，但是它可能包含未知问题，适合愿意折腾的人。

### 添加归档页面

新建独立页面，自定义模板选择 `Archives`，内容留空。

### 添加友情链接

新建独立页面，然后如此书写：

```
<div class="board-list link-list">
[熊猫小A](https://www.imalan.cn)+(https://secure.gravatar.com/avatar/1741a6eef5c824899e347e4afcbaa75d?s=200&r=G&d=)
[熊猫小A的博客](https://blog.imalan.cn)+(https://secure.gravatar.com/avatar/1741a6eef5c824899e347e4afcbaa75d?s=64&r=G&d=)
</div>
```

在某些 Typecho 版本中，你需要使用 `!!!` 包裹以上代码，例如：

```
!!!
<div class="board-list link-list">
[熊猫小A](https://www.imalan.cn)+(https://secure.gravatar.com/avatar/1741a6eef5c824899e347e4afcbaa75d?s=200&r=G&d=)
[熊猫小A的博客](https://blog.imalan.cn)+(https://secure.gravatar.com/avatar/1741a6eef5c824899e347e4afcbaa75d?s=64&r=G&d=)
</div>
!!!
```

## 更新

同[开始使用](#开始使用)，区别是你可以直接覆盖主题文件，不禁用主题，这样你的主题设置就不会丢失。

## 开发

如果你要定制自己的版本，首先按照[安装](#安装)中的方法二装好环境。然后切换至源码分支：

```bash
git checkout source
```

然后：

```bash
gulp dev
```

这会将依赖打包。你可以使用自己喜欢的方式编译 SCSS，或者使用：

```bash
gulp sass
```

监听 SCSS 更改然后实时编译。你可以添加自己想要的功能，满意后就提交代码。然后：

```bash
gulp build
```

构建你的主题。如果你对自己的更改很满意，**欢迎提出 Pull Request**。

**如果本项目对你有所帮助，请考虑捐助我**

![谢谢支持](https://wx1.sinaimg.cn/large/0060lm7Tly1g0c4cbi71lj30sc0iv453.jpg)

## 更新日志

**2019-02-16 Version 1.5.1**

* 可设置使用加载更多代替首页分页
* 完善 AJAX 评论提示
* 重写移动端搜索框
* 优化 PC 端目录
* 优化欢迎语
* 取消评论区气泡样式
* bug fix

高级设置

* `"welcomeWord" : false` 现在会完全关闭提示语
* `infiniteLoad` 设置首页随滚动无限加载（当打开加载更多时有效）

**2019-02-11 Version 1.4**

* 去除自适应导航条
* OWO 表情调整为懒加载
* 目录树优化，现在可以随滚动折叠
* 无头图时首页默认显示摘要
* 性能优化
* 其它细节调整
* bug fix

高级设置

原默认首页封面图设置移动至高级设置 `defaultCover`。

**2019-02-04 Version 1.3**

🎉 首先祝大家新年快乐！

* AJAX 评论！
* 重写了消息提示框，替代丑陋的 alert()
* 导航栏调整为固定位置

高级设置：

* 博客欢迎语
* 提示框背景色与文字色
* 自适应导航条颜色

**2019-01-31 Version 1.2**

* 完善 aria-label 属性，增加可访问性
* 完善页面结构化数据，优化 SEO
* 优化文章图片显示，优化照片集
* 去除极简首页
* 调整细节
* bug fix

**2019-01-25 Version 1.1**

* 重新设计的归档页面
* 重新设计的分类、搜索页面
* 顶部增加分类下拉菜单
* 文章注脚添加了 popover 效果
* 主题静态资源大幅优化

增加了高级设置，见 advanceSetting.sample.json 文件：

* 超简洁首页
* 站点名称
* 自定义顶部下拉栏

**2019-01-21 Version 1.0**

* 开始旅程

## 鸣谢

### 开源项目

[JQuery](https://github.com/jquery/jquery) | [highlight.js](https://highlightjs.org/) | [MathJax](https://www.mathjax.org/) | [fancyBox](http://fancyapps.com/fancybox/3/) | [bigfoot.js](http://www.bigfootjs.com/) | [OwO](https://github.com/DIYgod/OwO) | [pjax](https://github.com/defunkt/jquery-pjax) | [yue.css](https://github.com/lepture/yue.css) | [tocbot](https://tscanlin.github.io/tocbot/)

### 其他

[RAW](https://github.com/AlanDecode/Typecho-Theme-RAW) | [Mirages](https://get233.com/archives/mirages-intro.html) | [handsome](https://www.ihewro.com/archives/489/)

## License

MIT © [AlanDecode](https://github.com/AlanDecode)