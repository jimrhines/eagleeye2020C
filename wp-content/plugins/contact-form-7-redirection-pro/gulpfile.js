// jshint esversion: 6

// Run this command first:
// npm install gulp -g
// npm install gulpjs/gulp-cli -g
// npm install gulp-uglify gulp-rename gulp-clean-css gulp-autoprefixer gulp-concat gulp-rtlcss gulp-notify

const gulp                  = require("gulp");
const { series, src, dest } = require('gulp');

const uglify                = require("gulp-uglify");
const rename                = require("gulp-rename");
const cleanCSS              = require("gulp-clean-css");
const autoprefixer          = require("gulp-autoprefixer");
const concat                = require("gulp-concat");
const notify                = require("gulp-notify");

/**
 * JS Frontend scripts
 * @type {Array}
 */
var frontend_source_scripts = [
    './assets/js/wpcf7-redirect-frontend-script.js'
];

/**
 * JS Frontend scripts
 * @type {Array}
 */
var backend_source_scripts = [
    './node_modules/jquery-validation/dist/jquery.validate.min.js'
];

function scripts() {
  return gulp.src(frontend_source_scripts)
      .pipe(concat('assets.js'))                          // create main.js file
      .pipe(gulp.dest('./build/js/'))                     // move it to build/js/ directory
      .pipe(rename('build.min.js'))                      // rename it
      .pipe(uglify())                                     // minify js
      .pipe(gulp.dest('./build/js/'))                     // move it again to build/js/ directory
      .pipe(notify("Scripts compliled + minified"));      // notify message
}

gulp.task( 'create', gulp.series( scripts ) );
