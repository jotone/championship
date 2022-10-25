import { createApp } from 'vue';
import Main from './Main.vue';
import Sortable from 'sortablejs';

window.$.axios.interceptors.request.use(config => {
  const $jwt = $('meta[name="jwt"]')
  if ($jwt.length) {
    config.headers.Authorization = `Bearer ${$jwt.attr('content')}`;
  }
  return config;
});

createApp(Main).mount('#forumList')

const list = $('#forumList .forum-list-wrap')
new Sortable(list[0], {
  animation: 150,
  handle: '.forum-list-move',
  group: 'shared',
  onSort: function () {
    const route = list.attr('data-route')
    if (typeof route !== 'undefined') {

      let formData = new FormData()
      formData.append('_method', 'patch')
      list.children('li').each(function () {
        formData.append('positions[]', $(this).attr('data-id'))
      })

      $.axios.post(route, formData)
    }
  }
})
