const gulp = require('gulp'),
    autoprefixer = require('autoprefixer'),
    composer = require('gulp-uglify/composer'),
    concat = require('gulp-concat'),
    cssnano = require('cssnano'),
    footer = require('gulp-footer'),
    format = require('date-format'),
    header = require('@fomantic/gulp-header'),
    postcss = require('gulp-postcss'),
    rename = require('gulp-rename'),
    replace = require('gulp-replace'),
    sass = require('gulp-sass')(require('sass')),
    uglifyjs = require('uglify-js'),
    uglify = composer(uglifyjs, console),
    pkg = require('./_build/config.json');

const banner = '/*!\n' +
    ' * <%= pkg.name %> - <%= pkg.description %>\n' +
    ' * Version: <%= pkg.version %>\n' +
    ' * Build date: ' + format("yyyy-MM-dd", new Date()) + '\n' +
    ' */';
const year = new Date().getFullYear();

const scriptsMgr = function () {
    return gulp.src([
        'source/js/mgr/babel.js',
        'source/js/mgr/helper/combo.js',
        'source/js/ux/LockingGridView/LockingGridView.js',
        'source/js/mgr/widgets/resourcematrix.grid.js',
        'source/js/mgr/widgets/home.panel.js',
        'source/js/mgr/widgets/settings.panel.js',
        'source/js/mgr/sections/index.js'
    ])
        .pipe(concat('babel.min.js'))
        .pipe(uglify())
        .pipe(header(banner + '\n', {pkg: pkg}))
        .pipe(gulp.dest('assets/components/babel/js/mgr/'))
};
const scriptsResourcebutton = function () {
    return gulp.src([
        'source/js/mgr/babel.js'
    ])
        .pipe(concat('resourcebutton.min.js'))
        .pipe(uglify())
        .pipe(header(banner + '\n', {pkg: pkg}))
        .pipe(gulp.dest('assets/components/babel/js/mgr/'))
};
gulp.task('scripts', gulp.series(scriptsMgr, scriptsResourcebutton));

const sassMgr = function () {
    return gulp.src([
        'source/sass/mgr/babel.scss'
    ])
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss([
            autoprefixer()
        ]))
        .pipe(gulp.dest('source/css/mgr/'))
        .pipe(concat('babel.css'))
        .pipe(postcss([
            cssnano({
                preset: ['default', {
                    discardComments: {
                        removeAll: true
                    }
                }]
            })
        ]))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(footer('\n' + banner, {pkg: pkg}))
        .pipe(gulp.dest('assets/components/babel/css/mgr/'))
};
const sassResourcebutton = function () {
    return gulp.src([
        'source/sass/mgr/resourcebutton.scss',
    ])
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss([
            autoprefixer()
        ]))
        .pipe(gulp.dest('source/css/mgr/'))
        .pipe(concat('resourcebutton.css'))
        .pipe(postcss([
            cssnano({
                preset: ['default', {
                    discardComments: {
                        removeAll: true
                    }
                }]
            })
        ]))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(footer('\n' + banner, {pkg: pkg}))
        .pipe(gulp.dest('assets/components/babel/css/mgr/'))
};
gulp.task('sass', gulp.series(sassMgr, sassResourcebutton));

const bumpCopyright = function () {
    return gulp.src([
        'core/components/babel/model/babel/babel.class.php',
        'core/components/babel/src/Babel.php',
    ], {base: './'})
        .pipe(replace(/Copyright 2010(-\d{4})? by/g, 'Copyright ' + (year > 2010 ? '2010-' : '') + year + ' by'))
        .pipe(gulp.dest('.'));
};
const bumpVersion = function () {
    return gulp.src([
        'core/components/babel/src/Babel.php',
    ], {base: './'})
        .pipe(replace(/version = '\d+\.\d+\.\d+[-a-z0-9]*'/ig, 'version = \'' + pkg.version + '\''))
        .pipe(gulp.dest('.'));
};
const bumpAbout = function () {
    return gulp.src([
        'source/js/mgr/babel.js',
    ], {base: './'})
        .pipe(replace(/&copy; 2010(-\d{4})?/g, '&copy; ' + (year > 2010 ? '2010-' : '') + year))
        .pipe(gulp.dest('.'));
};
gulp.task('bump', gulp.series(bumpCopyright, bumpVersion, bumpAbout));

gulp.task('watch', function () {
    // Watch .js files
    gulp.watch(['./source/js/**/*.js'], gulp.series('scripts'));
    // Watch .scss files
    gulp.watch(['./source/sass/**/*.scss'], gulp.series('sass'));
});

// Default Task
gulp.task('default', gulp.series('bump', 'scripts', 'sass'));
