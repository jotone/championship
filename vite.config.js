import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  server: {
    host: '0.0.0.0'
  },
  plugins: [
    laravel({
      input: [
        'node_modules/air-datepicker/air-datepicker.css',
        'resources/css/font-awesome/fontawesome.scss',
        'resources/css/font-awesome/solid.scss',
        'resources/css/reset.scss',
        'resources/css/admin/competition.scss',
        'resources/css/admin/app.scss',
        'resources/css/main/app.scss',
        'resources/js/admin/main.js',
        'resources/js/admin/app.js',
        'resources/js/admin/competition-form/app.js',
        'resources/js/admin/competition-list/app.js',
        'resources/js/admin/country-list/app.js',
        'resources/js/admin/role-list/app.js',
        'resources/js/admin/user-list/app.js',
        'resources/js/admin/team-list/app.js',
        'resources/js/main/app.js'
      ],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          // The Vue plugin will re-write asset URLs, when referenced
          // in Single File Components, to point to the Laravel web
          // server. Setting this to `null` allows the Laravel plugin
          // to instead re-write asset URLs to point to the Vite
          // server instead.
          base: null,

          // The Vue plugin will parse absolute URLs and treat them
          // as absolute paths to files on disk. Setting this to
          // `false` will leave absolute URLs un-touched, so they can
          // reference assets in the public directly as expected.
          includeAbsolute: false,
        },
      },
    }),
  ],
});
