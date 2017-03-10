###
Base imports and vars
###
path = require 'path'
gulp = require 'gulp'
sass = require 'gulp-ruby-sass'
compass = require 'gulp-compass'
prefix = require 'gulp-autoprefixer'
util = require 'gulp-util'
concat = require 'gulp-concat'
uglify = require 'gulp-uglify'
coffee = require 'gulp-coffee'
imagemin    = require 'gulp-imagemin'
minifyCSS   = require 'gulp-minify-css'


# get the theme name
theme = path.basename(path.dirname(__dirname))

projectRoot = __dirname.slice(0, __dirname.indexOf('/resources/'))

console.log(projectRoot);

handleError = (err)->
  util.log err.toString()


dev_path =
  images: __dirname.concat('/images/**')
  coffee:__dirname.concat('/coffee/**.coffee')
  js:__dirname.concat('/js/**.js')
  sass: __dirname.concat('/sass/')
  fonts: __dirname.concat('/fonts/**/*')
  font: __dirname.concat('/font/**/*')
  ccss: __dirname.concat('/css/**/*')

prod_path =
  images: projectRoot.concat('/public/assets/themes/' + theme + '/images/')
  js:     projectRoot.concat('/public/assets/themes/' + theme + '/js/')
  css:    projectRoot.concat('/public/assets/themes/' + theme + '/css/')
  fonts:  projectRoot.concat('/public/assets/themes/' + theme + '/fonts/')
  font:   projectRoot.concat('/public/assets/themes/' + theme + '/font/')




# Export tasks #

module.exports =
  default: [theme.concat('::css'), theme.concat('::coffee'), theme.concat('::purejs'), theme.concat('::images'), theme.concat('::ccss'), theme.concat('::fonts'), theme.concat('::font')]
  dev: [theme.concat('::css:dev'), theme.concat('::coffee:dev'), theme.concat('::purejs'), theme.concat('::images'), theme.concat('::ccss'), theme.concat('::fonts'), theme.concat('::font')]
  watch: [theme.concat('::css:watch'), theme.concat('::coffee:watch'), theme.concat('::js:watch'), theme.concat('::images:watch'), theme.concat('::ccss:watch'), theme.concat('::fonts:watch'), theme.concat('::font:watch')]


# SASS #
gulp.task theme.concat("::css"), ->
  gulp.src(dev_path.sass.concat("*.sass"))
    .pipe(sass({ style: 'expanded' }).on('error', handleError))
    .pipe(prefix().on('error', handleError))
    .pipe(minifyCSS(removeEmpty: true).on('error', handleError))
    .pipe(concat("styles.css"))
    .pipe(gulp.dest(prod_path.css))
    .on("error", handleError)

gulp.task theme.concat('::css:dev'), ->
  gulp.src(dev_path.sass.concat('*.sass'))
    .pipe(sass({ style: 'expanded' }).on('error', handleError))
    .pipe(prefix().on('error', handleError))
    .pipe(concat('styles.css'))
    .pipe(gulp.dest(prod_path.css))
    .on('error', handleError)

gulp.task theme.concat('::css:watch'), ->
  gulp.watch dev_path.sass.concat('**/*.sass') , [theme.concat('::css:dev')]



# COFFEE #
gulp.task theme.concat('::coffee'), ->
  gulp.src(dev_path.coffee)
    .pipe(concat 'main.js')
    .pipe(coffee({bare: true}).on('error', handleError))
    .pipe(uglify({outSourceMap: true}).on('error', handleError))
    .pipe(gulp.dest(prod_path.js))
    .on('error', handleError)

gulp.task theme.concat('::coffee:dev'), ->
  gulp.src(dev_path.coffee)
    .pipe(concat 'main.js')
    .pipe(coffee({bare: true}).on('error', handleError))
    .pipe(gulp.dest(prod_path.js))
    .on('error', handleError)

gulp.task theme.concat('::purejs'), ->
  gulp.src(dev_path.js)
    .pipe(uglify({outSourceMap: true}).on('error', handleError))
    .pipe(gulp.dest(prod_path.js))
    .on('error', handleError)

gulp.task theme.concat('::coffee:watch'), ->
  gulp.watch dev_path.coffee, [theme.concat('::coffee:dev')]

gulp.task theme.concat('::js:watch'), ->
  gulp.watch dev_path.js, [theme.concat('::purejs')]


# IMAGES #
gulp.task theme.concat('::images'), ->
  gulp.src(dev_path.images)
    .pipe(gulp.dest(prod_path.images))
    .on('error', handleError)

gulp.task theme.concat('::images:watch'), ->
  gulp.watch dev_path.images , [theme.concat('::images')]



# CSS #
gulp.task theme.concat('::ccss'), ->
  gulp.src(dev_path.ccss)
    .pipe(minifyCSS(removeEmpty: true).on('error', handleError))
    .pipe(gulp.dest(prod_path.css))
    .on('error', handleError)

gulp.task theme.concat('::ccss:watch'), ->
  gulp.watch dev_path.ccss , [theme.concat('::ccss')]



# FONTS #
gulp.task theme.concat('::fonts'), ->
  gulp.src(dev_path.fonts)
    .pipe(gulp.dest(prod_path.fonts))
    .on('error', handleError)

gulp.task theme.concat('::fonts:watch'), ->
  gulp.watch dev_path.fonts , [theme.concat('::fonts')]



# FONT #
gulp.task theme.concat('::font'), ->
  gulp.src(dev_path.font)
    .pipe(gulp.dest(prod_path.font))
    .on('error', handleError)

gulp.task theme.concat('::font:watch'), ->
  gulp.watch dev_path.font , [theme.concat('::font')]