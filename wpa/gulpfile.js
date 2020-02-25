'use strict';

const gulp = require('gulp');
const notify = require('gulp-notify');
const runSequence = require('run-sequence');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
// const imagemin = require('gulp-imagemin');


// development workflow task
gulp.task('dev', function (cb) {
    runSequence(['uglify-js'], cb);
});

// default task
gulp.task('default', ['dev']);


/* ============================================================================================================
================================================= For Production ==============================================
=============================================================================================================*/

gulp.task('uglify-js', function () {
    return gulp.src('static/_static/live/js/recorderjs/**.js')
        .pipe(uglify())
        .pipe(rename({
          suffix: '.min'
        }))
        .pipe(gulp.dest('static/_static/live/js/recorderjs/'));
});


/* ===============================================
 ================== Functions ====================
 ================================================*/

// handle errors
function errorAlert(error){
    notify.onError({
        title: "Error in plugin '" + error.plugin + "'",
        message: 'Check your terminal',
        sound: 'Sosumi'
    })(error);
    console.log(error.toString());
    this.emit('end');
}
