<template>
  <nav class="pagination" v-if="this.pagination.total > 1">
    <ul>
      <li v-if="this.pagination.current > 4">
        <a :href="this.$parent.routes.index + `?page=1`">
          1
        </a>
      </li>
      <li v-if="this.pagination.current > 4">
        <span>&hellip;</span>
      </li>
      <li v-for="item in list">
        <a :href="item.link" :class="{ 'active' : item.active }">
          {{ item.point }}
        </a>
      </li>
      <li v-if="(this.pagination.current + 3) < this.pagination.total">
        <span>&hellip;</span>
      </li>
      <li v-if="(this.pagination.current + 3) < this.pagination.total">
        <a :href="this.$parent.routes.index + `?page=${this.pagination.total}`">
          {{ this.pagination.total }}
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  props: ['pagination'],
  data: function () {
    return {
      items: [],
      current: 1,
      total: 0
    }
  },
  computed: {
    list () {
      // Function result
      let result = []
      // Pagination first item
      let start = this.pagination.current - 3;
      // Pagination last item
      let last = this.pagination.current + 3;
      // First item cannot be less than 1
      if (start < 1) {
        // Shift last item
        last += Math.abs(start) + 1;
        // Set first item as 1
        start = 1
      }
      // Last item cannot be greater than total pages number
      if (last > this.pagination.total) {
        // Set last item as total pages number
        last = this.pagination.total;
      }
      // Check for search request
      const urlData = window.Helpers.parseUrl()
      const search = typeof urlData.search.search !== 'undefined'
          ? `&search=${urlData.search.search}`
          : ''
      // Build pagination items
      for (let i = start; i <= last; i++) {
        result.push({
          link: this.$parent.routes.index + `?page=${i}${search}`,
          point: i,
          active: i === this.pagination.current
        })
      }
      return result
    }
  }
}
</script>