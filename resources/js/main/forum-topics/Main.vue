<template>
  <ul class="forum-list-wrap">
    <li data-pin v-for="(topic) in pinned" :data-id="topic.id">
      <Topic :topic="topic"></Topic>
    </li>
    <li v-for="(topic) in unpinned" :data-id="topic.id">
      <Topic :topic="topic"></Topic>
    </li>
  </ul>
</template>

<script>

import Topic from './Topic.vue';

export default {
  components: {Topic},
  data() {
    return {
      pinned: [],
      unpinned: [],
      routes: {}
    }
  },
  beforeMount() {
    this.routes = $('#forumList').data('routes')
  },
  mounted() {
    window.$.axios.interceptors.request.use(config => {
      const $jwt = $('meta[name="jwt"]')
      if ($jwt.length) {
        config.headers.Authorization = `Bearer ${$jwt.attr('content')}`;
      }

      return config;
    });

    $.axios.get(this.routes.list).then(response => {
      const topics = response.data.collection
      topics.sort((a, b) => a.position > b.position ? 1 : -1)

      for (let i = 0, n = topics.length; i < n; i++) {
        this[topics[i].pinned ? 'pinned' : 'unpinned'].push(topics[i])
      }
    })
  }
}
</script>