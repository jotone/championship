<template>
  <div class="col-name">{{ text }}</div>
  <div class="directions-wrap">
    <i class="fas fa-caret-up asc" @click="sort"></i>
    <i class="fas fa-caret-down desc" @click="sort"></i>
  </div>
</template>

<script>
import {Session} from '../libs/session';

export default {
  props: ['text'],
  methods: {
    sort(e) {
      // Parent sorting element
      const el = $(e.target)
      // Check the sort field exists
      if (typeof el.closest('th').attr('data-field') !== 'undefined') {
        // Order values
        const order = {
          by: el.closest('th').attr('data-field'),
          dir: el.hasClass('asc')
              ? 'asc'
              : 'desc'
        }
        // Set class to selected item
        el.closest('thead').find('.active').removeClass('active')
        el.addClass('active')
        // Build request URL
        const requestUrl = window.Helpers.setRequestOrderParams(this.$parent.routes.list, order)
        // Set order to local storage
        Session.update(this.$parent.module, order)
        // Run request
        $.axios.get(requestUrl)
            // Set table collection
            .then(response => this.$parent.models = response.data.collection)
            .finally(() => $('.overlay, .overlay .preload').hide())
      }
    }
  },
  mounted() {
    if (Session.has(this.$parent.module)) {
      // Get session order value
      const order = Session.get(this.$parent.module)
      // Find table header cell
      const el = $(this.$parent.$el).find(`thead th[data-field="${order.by}"]`)
      // Remove all active elements
      el.find('.active').removeClass('active')
      // Set active direction class
      el.find(`.${order.dir}`).addClass('active')
    } else {
      $('.content-table-wrap th[data-field="id"] .asc').addClass('active')
    }
  }
}
</script>