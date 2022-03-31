var gulp = require('gulp');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var jshint = require('gulp-jshint');

// Compile, combine and minify scss files.
exports.cssBundle = function() {
  return gulp.src('./sass/**/*.scss')
    // Compile sass file.
    .pipe(sass())
    // Execute the task to minify the files
    .pipe(cleanCSS())
    .pipe(gulp.dest('./css'));
}

gulp.task('jshint', function () {
    return gulp.src('./js/*-validated.js')
    .pipe(jshint({esnext:true}))
    .pipe(jshint.reporter('default'));
});

// Combine and minify js files.
exports.jsBundle = function() {
  //return gulp.src(['js/*.js', '!js/script.js'])
  return gulp.src('./js/*.js')
    .pipe(concat('script.js'))
    // The gulp-uglify to minify js files.
    .pipe(uglify())
    .pipe(gulp.dest('./js'));
}

// Generate Image Sprite.
exports.imageSprite = function() {
   var spriteData = gulp.src('./images/icons/*.png')
    // Generate sprite image and css for sprite image.
    .pipe(spritesmith({
        imgName: 'sprite.png',
        cssName: 'sprite.css'
    }));
    spriteData.img.pipe(gulp.dest('./dist'));
    return spriteData.css.pipe(gulp.dest('./dist/css'));
}

// Watch css, js and image folder changes.
gulp.task('watch', function(){
    gulp.watch('js/*.js',  gulp.series('jsBundle'));
    gulp.watch('./sass/**/*.scss',  gulp.series('cssBundle'));
    gulp.watch('images/icons/*.png',  gulp.series('imageSprite'));
});

gulp.task('lint', function () {
    return gulp.src('./js/es6-validated.js')
      .pipe(jshint())
      .pipe(jshint.reporter('default')) // linting passed
      .pipe(jshint.reporter('fail')); // linting failed
});
