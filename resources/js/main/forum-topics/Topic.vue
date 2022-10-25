<template>
  <div class="forum-list-item" :data-i="JSON.stringify(topic)">
    <a href="#" v-if="!!topic.img_url">
      <img :src="topic.img_url" alt="Зображення відсутнє&hellip;">
    </a>
    <div class="forum-list-body">
      <a class="forum-list-title" href="#">
        {{ topic.name }}
      </a>

      <div class="forum-list-description">
        {{ topic.description }}
      </div>
    </div>
  </div>

  <div class="forum-list-info">
    <span class="forum-list-text">Cтворив: {{ topic.author.name }}</span>
    <span class="forum-list-text">Комментарів: {{ topic.messages_count }}</span>

    <a
      :href="updateRoute(topic.id)"
      class="forum-list-pin"
      title="Закріпити тему"
      @click.prevent="pinTopic"
    >
      <i :class="`${topic.pinned == 1 ? 'fas' : 'far'} fa-thumbtack`"></i>
    </a>
    <a :href="editRoute(topic.id)" class="forum-list-edit">
      <i class="far fa-edit"></i>
    </a>
    <span class="forum-list-move">
      <i class="fas fa-ellipsis-v"></i>
      <i class="fas fa-ellipsis-v"></i>
    </span>
  </div>
</template>

<script>
export default {
  props: {
    topic: {}
  },
  methods: {
    /**
     * Set id to remove link
     *
     * @param {string} link
     * @param {string|int} id
     * @param {int} position
     * @returns {string}
     */
    buildUrl: (link, id, position = 1) => {
      let resultLink = link.split('/');
      resultLink[resultLink.length - position] = id;
      resultLink = resultLink.join('/');
      return resultLink;
    },
    /**
     * Edit forum topic link
     *
     * @param id
     * @returns {string}
     */
    editRoute(id) {
      return this.buildUrl(this.$parent.routes.edit, id, 2)
    },
    /**
     * Update forum topic link
     *
     * @param id
     * @returns {string}
     */
    updateRoute(id) {
      return this.buildUrl(this.$parent.routes.update, id, 1)
    },
    pinTopic(e) {
      const _this = $(e.target).closest('a')

      const id = parseInt(_this.closest('li').attr('data-id'))
      const nextPinStatus = typeof _this.closest('li').attr('data-pin') === 'undefined' ? 1 : 0

      let formData = new FormData()

      formData.append('_method', 'patch');
      formData.append('pinned', nextPinStatus)

      $.axios.post(_this.attr('href'), formData).then(response => {
        if (200 === response.status) {
          const removeList = nextPinStatus ? 'unpinned' : 'pinned';
          const addList = nextPinStatus ? 'pinned' : 'unpinned';

          for (let i = 0, n = this.$parent[removeList].length; i < n; i++) {
            if (typeof this.$parent[removeList][i] !== 'undefined' && this.$parent[removeList][i].id === id) {
              this.$parent[removeList].splice(i, 1)
            }
          }

          this.$parent[addList].push(response.data)

          this.$parent.unpinned.sort((a, b) => a.position > b.position ? 1 : -1)
          this.$parent.pinned.sort((a, b) => a.position > b.position ? 1 : -1)
        }
      })
    }
  }
}
</script>