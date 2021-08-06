const {gulp, src, dest} = require('gulp');
const uglify = require('gulp-uglify-es').default;
const concat = require('gulp-concat');
const rename = require('gulp-rename');

function build () {
    var path = './src/assets/src/js/';

	return src([
            path + 'jquery.multipleInput.js'
        ])
        .pipe(concat('jquery.multipleInput.js'))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(dest(path));
};

exports.default = build;
