const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  /*.styles('./node_modules/air-datepicker/air-datepicker.css', './public/css/air-datepicker.css')
  .copy('./node_modules/jquery/dist/jquery.min.js', './public/js/jquery.js')
  .copy('./node_modules/ckeditor4/lang', './public/js/ckeditor4/lang')
  .copy('./node_modules/ckeditor4/plugins', './public/js/ckeditor4/plugins')
  .copy('./node_modules/ckeditor4/ckeditor.js', './public/js/ckeditor4/ckeditor.js')
  .copy('./node_modules/ckeditor4/config.js', './public/js/ckeditor4/config.js')
  .copy('./node_modules/ckeditor4/styles.js', './public/js/ckeditor4/styles.js')
  .copy('./node_modules/ckeditor4/contents.css', './public/js/ckeditor4/contents.css')
  .copy('./node_modules/ckeditor4/skins/moono-lisa', './public/js/ckeditor4/skins/moono-lisa')*/
  // Admin
  .sass('./resources/css/reset.scss', './public/css')
  .sass('./resources/css/font-awesome/fontawesome.scss', './public/css')
  .sass('./resources/css/font-awesome/solid.scss', './public/css')
  .sass('./resources/css/admin/app.scss', './public/css/admin')
  .sass('./resources/css/admin/competition.scss', './public/css/admin')
  .js('./resources/js/admin/competition-form/app.js', './public/js/admin/competition-form.js').vue()
  .js('./resources/js/admin/competition-list/app.js', './public/js/admin/competition-list.js').vue()
  .js('./resources/js/admin/country-list/app.js', './public/js/admin/country-list.js').vue()
  .js('./resources/js/admin/custom-pages-form/app.js', './public/js/admin/custom-pages-form.js')
  .js('./resources/js/admin/custom-pages-list/app.js', './public/js/admin/custom-pages-list.js').vue()
  .js('./resources/js/admin/role-list/app.js', './public/js/admin/role-list.js').vue()
  .js('./resources/js/admin/team-list/app.js', './public/js/admin/team-list.js').vue()
  .js('./resources/js/admin/user-list/app.js', './public/js/admin/user-list.js').vue()
  .js('./resources/js/admin/app.js', './public/js/admin/app.js')
  .js('./resources/js/admin/main.js', './public/js/admin/main.js')
  // Main
  .sass('./resources/css/main/app.scss', './public/css/')
  .js('./resources/js/main/app.js','./public/js/app.js')
  .js('./resources/js/main/profile.js','./public/js/profile.js')
  .js('./resources/js/main/results.js','./public/js/results.js')
  .js('./resources/js/main/user-form.js','./public/js/user-form.js')

  // Compilation Settings
  .options({
    postCss: [
      require('postcss-discard-comments')({removeAll: true})
    ],
    terser: {
      terserOptions: {
        format: {
          comments: false
        }
      },
      extractComments: false,
    }
  })
  .disableNotifications()
