'use strict';

let gulp = require('gulp');
let sass = require('gulp-sass');
let concat = require('gulp-concat');
let plumber = require('gulp-plumber');
let cssnano = require('cssnano');
let sourcemaps = require('gulp-sourcemaps');
let uglify = require('gulp-uglify');
let replace = require('gulp-replace');
let autoprefixer = require('autoprefixer');
let postcss = require('gulp-postcss');
let browserSync = require('browser-sync').create();

let nodePath = 'node_modules';
let resPath = 'src';
let distPath = 'dist';

let theme = {
  css: {
    in: `${resPath}/styles/theme.scss`,
    watch: `${resPath}/styles/**/*.scss`,
    includes: []
  },
  js: {
    in: `${resPath}/scripts/**/*.js`,
    watch: `${resPath}/scripts/**/*.js`
  },
  vendorCSS: {
    in: [`${nodePath}/aos/dist/aos.css`]
  },
  // Add your vendor js plugins location here
  vendorJS: {
    in: [`${nodePath}/aos/dist/aos.js`]
  },
  images: {
    in: `${resPath}/images/**/*.{jpg,jpeg,png,gif,ico,svg}`,
    toOptimize: ''
  }
};

let admin = {
  css: {
    in: `${resPath}/styles/admin.scss`,
    watch: `${resPath}/styles/admin.scss`,
    includes: []
  },
  js: {
    in: `${resPath}/scripts/**/*.js`,
    watch: `${resPath}/scripts/**/*.js`
  }
};

let cssPlugins = [
  autoprefixer({ overrideBrowserslist: ['last 2 versions'] }),
  cssnano()
];

// Theme Task
gulp.task('theme-css', function() {
  gulp
    .src(theme.css.in)
    .pipe(plumber())
    // .pipe(sourcemaps.init())
    .pipe(
      sass({
        style: 'compressed',
        errLogToConsole: true,
        includePaths: theme.css.includes
      })
    )
    .pipe(replace('/*!', '/*'))
    .pipe(postcss(cssPlugins))
    .pipe(concat('theme.min.css'))
    // .pipe(sourcemaps.write(''))
    .pipe(gulp.dest(distPath))
    .pipe(browserSync.stream());
});

gulp.task('theme-js', function() {
  gulp
    .src(theme.js.in)
    .pipe(plumber())
    // .pipe(sourcemaps.init())
    .pipe(uglify({ mangle: true }))
    .pipe(concat('theme.min.js'))
    // .pipe(sourcemaps.write(''))
    .pipe(gulp.dest(distPath));
});

gulp.task('vendor-css', function() {
  gulp
    .src(theme.vendorCSS.in)
    .pipe(plumber())
    .pipe(replace('/*!', '/*'))
    .pipe(postcss(cssPlugins))
    .pipe(concat('vendor.min.css'))
    .pipe(gulp.dest(distPath));
});

gulp.task('vendor-js', function() {
  gulp
    .src(theme.vendorJS.in)
    .pipe(plumber())
    .pipe(uglify({ mangle: true }))
    .pipe(concat('vendor.min.js'))
    .pipe(gulp.dest(distPath));
});

gulp.task('theme-serve', ['theme-css'], function() {
  browserSync.init({
    proxy: 'your-url.test',
    reloadOnRestart: true,
    open: false
  });
  gulp.watch(theme.css.watch, ['theme-css']);
  gulp.watch(theme.js.watch, ['theme-js']);
  gulp.watch('*.php').on('change', browserSync.reload);
});

gulp.task(
  'theme',
  ['theme-js', 'vendor-css', 'vendor-js', 'theme-serve'],
  function() {
    gulp.watch(theme.css.watch, ['theme-css']);
    gulp.watch(theme.js.watch, ['theme-js']);
  }
);

// Admin Task
gulp.task('admin-css', function() {
  gulp
    .src(admin.css.in)
    .pipe(plumber())
    // .pipe(sourcemaps.init())
    .pipe(
      sass({
        style: 'compressed',
        errLogToConsole: true,
        includePaths: admin.css.includes
      })
    )
    // .pipe(postcss(plugins))
    .pipe(replace('/*!', '/*'))
    .pipe(postcss(cssPlugins))
    .pipe(concat('admin.min.css'))
    // .pipe(sourcemaps.write(''))
    .pipe(gulp.dest(distPath));
});

// gulp.task('admin-js', function() {
//   gulp
//     .src(admin.js.in)
//     .pipe(plumber())
//     // .pipe(sourcemaps.init())
//     .pipe(uglify({ mangle: true }))
//     .pipe(concat('admin.min.js'))
//     // .pipe(sourcemaps.write(''))
//     .pipe(gulp.dest(`${distPath}`));
// });

gulp.task(
  'admin',
  [
    'admin-css'
    // 'admin-js'
  ],
  function() {
    gulp.watch(admin.css.watch, ['admin-css']);
    // gulp.watch(admin.js.watch, ['admin-js']);
  }
);

// Clean all files in dist
gulp.task('clean', function() {
  gulp.src(dest).pipe(clean({ force: true }));
});
