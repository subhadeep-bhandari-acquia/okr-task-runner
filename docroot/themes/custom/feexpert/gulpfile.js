var gulp = require('gulp');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var spritesmith = require('gulp.spritesmith');

// Function to minify and combine js files.
function combineJs(cb) {
  gulp.src('./js/*.js')
    .pipe(concat('script.js'))
    // The gulp-uglify to minify js files.
    .pipe(uglify())
    .pipe(gulp.dest('./asset/js'));
  cb();
}

// Compile, combine and minify scss files.
function combineCss(cb) {
  gulp.src('./sass/**/*.scss')
    // Compile sass file.
    .pipe(sass())
    // Concat all the files into style.css file.
    .pipe(concat('style.css'))
    // Execute the task to minify the files
    .pipe(cleanCSS())
    .pipe(gulp.dest('./asset/css'));
  cb();
}

// Generate Image Sprite.
function imageSprite(cb) {
  var spriteData = gulp.src('./images/icons/*.png')
   // Generate sprite image and css for sprite image.
   .pipe(spritesmith({
       imgName: 'testsprite.png',
       cssName: 'testsprite.css'
   }));
   spriteData.img.pipe(gulp.dest('./asset'));
   spriteData.css.pipe(gulp.dest('./asset/css'));
  cb();
}

// Watch css, js and image folder changes.
gulp.task('watch', function() {
    gulp.watch('js/*.js',  gulp.series('combineJs'));
    gulp.watch('./sass/**/*.scss',  gulp.series('combineCss'));
    gulp.watch('images/icons/*.png',  gulp.series('imageSprite'));
});

exports.combineJs = combineJs;
exports.combineCss = combineCss;
exports.imageSprite = imageSprite;
