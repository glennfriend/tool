var gulp        = require("gulp"),
    connect     = require('gulp-connect'),
    notify      = require('gulp-notify'),
    babel       = require("gulp-babel");

var public_path = './public';

// listen
gulp.task('connect', function() {
    connect.server({
        root: public_path,
        livereload: true
    });
});

gulp.task('html', function () {
    gulp.src( public_path + '/*.html');
});
gulp.task('js', function () {
    gulp.src( public_path + '/dist/core.src/*.js');
});
gulp.task('watch', function () {
    gulp.watch([ public_path + '/*.*'],               ['html', 'compile']);
    gulp.watch([ public_path + '/dist/core.src/*.*'], ['js',   'compile']);
});

gulp.task('compile', function () {
    return gulp.src( public_path + "/dist/core.src/*.js")
        .pipe(babel())
        // .on('error', console.error.bind(console))   // TODO: 防止錯誤中斷 node, 但是 compile 功能在發生錯誤之後就會失消, 該問題未獲得解決
        .on('error', notify.onError({
            title: 'babel to ES5:',
            message: 'Failed'
        }))
        .pipe(gulp.dest( public_path + "/dist/core"));
});

// --------------------------------------------------------------------------------

gulp.task('default', function() {
    gulp.run('connect','watch','compile');
});

