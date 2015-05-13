var gulp        = require("gulp"),
    connect     = require('gulp-connect'),
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
        .pipe(gulp.dest( public_path + "/dist/core"));
});

// --------------------------------------------------------------------------------

gulp.task('default', function() {
    gulp.run('connect','watch','compile');
});

