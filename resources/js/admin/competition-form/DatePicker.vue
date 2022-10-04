<template>
  <input class="set-date" :name="name" :value="value || 'Not set'">
</template>

<script>
import {debounce} from 'debounce';
import AirDatepicker from "air-datepicker";
import enLocale from "air-datepicker/locale/en";

export default {
  props: {
    name: '',
    value: ''
  },
  mounted() {
    new AirDatepicker(this.$el, {
      locale: enLocale,
      timepicker: true,
      firstDay: 1,
      dateFormat: 'd/MMM/yyyy',
      timeFormat: 'HH:mm',
      onSelect: debounce(e => {
        let formData = new FormData()
        formData.append('_method', 'patch')
        formData.append('start_at', e.formattedDate || null)

        const id = parseInt($(e.datepicker.$el).closest('tr').data('id'))

        $.axios
          .post(this.$parent.gameUpdateRoute(id), formData)
          .then(response => {
            if (200 === response.status) {
              for (let i = 0, n = this.$parent.groups.length; i < n; i++) {
                if (this.$parent.groups[i].id === response.data.group_id) {
                  for (let j = 0, m = this.$parent.groups[i].games.length; j < m; j++) {
                    if (id === this.$parent.groups[i].games[j].id) {
                      this.$parent.groups[i].games[j] = response.data
                    }
                  }
                }
              }
            }
          })
      }, 500)
    })
  }
}
</script>