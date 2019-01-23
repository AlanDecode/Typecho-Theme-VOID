/* eslint-disable no-undef */
var gulp = require('gulp');
var sass = require('gulp-sass');
var prefix = require('gulp-autoprefixer');
var minify = require('gulp-clean-css');
var del = require('del');
var rev = require('gulp-rev');
var revCollector = require('gulp-rev-collector');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

// 清理旧版文件
gulp.task('clean', function (){
    return del(['*.md', '*.png','*.php', 'assets', 'includes', 'libs', 'temp']);
});

var prefixerOptions = {
    browsers: ['last 2 versions']
};

// 依赖 CSS minify、打包，除 MathJax
gulp.task('pack:css:dep', function (){
    return  gulp.src(['src/assets/libs/**/*.css', '!src/assets/libs/mathjax/*'])
        .pipe(concat('bundle.css'))
        .pipe(minify())
        .pipe(rev())
        .pipe(gulp.dest('assets/'))
        .pipe(rev.manifest())
        .pipe(gulp.dest('temp/rev/css_bundle'));
});

// 主 CSS 编译、autoprefix、minify
gulp.task('pack:css:main', function(){
    return  gulp.src('src/assets/VOID.scss')
        .pipe(sass())
        .pipe(prefix(prefixerOptions))
        .pipe(minify())
        .pipe(rev())
        .pipe(gulp.dest('assets/'))
        .pipe(rev.manifest())
        .pipe(gulp.dest('temp/rev/css_main'));
});

// 依赖 JS 压缩混淆，除 Mathjax
gulp.task('pack:js:dep', function(){
    return  gulp.src(['src/assets/libs/**/*.js', '!src/assets/libs/mathjax', '!src/assets/libs/mathjax/**/*', '!src/assets/libs/jquery', '!src/assets/libs/jquery/**/*'])
        .pipe(concat('bundle.js'))
        .pipe(uglify())
        .pipe(rev())
        .pipe(gulp.dest('assets/'))
        .pipe(rev.manifest())
        .pipe(gulp.dest('temp/rev/js_bundle'));
});

// 主 JS 压缩混淆
gulp.task('pack:js:main', function(){
    return  gulp.src(['src/assets/*.js', '!src/assets/sw-toolbox.js', '!src/assets/VOIDCacheRule.js'])
        .pipe(uglify())
        .pipe(rev())
        .pipe(gulp.dest('assets/'))
        .pipe(rev.manifest())
        .pipe(gulp.dest('temp/rev/js_main'));
});

// 静态文件加戳
gulp.task('md5', function(){
    return  gulp.src(['temp/rev/**/*.json', 'src/**/*.php'])
        .pipe(revCollector())
        .pipe(gulp.dest('./'));
});

// 无需处理的文件
gulp.task('move', function(){
    gulp.src(['src/assets/libs/owo/**/*','src/assets/libs/mathjax/**/*', 'src/assets/libs/jquery/**/*'],{base: 'src/assets/libs/'})
        .pipe(gulp.dest('assets/'));
    gulp.src(['src/assets/sw-toolbox.js', 'src/assets/VOIDCacheRule.js'])
        .pipe(gulp.dest('assets/'));
    return  gulp.src(['src/LICENSE', 'src/README.md', 'src/screenshot.png'])
        .pipe(gulp.dest('./'));
});

gulp.task('build', gulp.series('clean', gulp.parallel('pack:css:main', 'pack:css:dep', 'pack:js:main', 'pack:js:dep'), 'md5', 'move'));