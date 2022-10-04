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

        const parent = $(e.datepicker.$el).closest('tr').length
            ? $(e.datepicker.$el).closest('tr')
            : $(e.datepicker.$el).closest('li')

        const id = parseInt(parent.data('id'))

        $.axios
          .post(this.$parent.gameUpdateRoute(id), formData)
          .then(response => {
            if (200 === response.status) {
              if (typeof this.$parent.groups === 'undefined') {
                for (let i in this.$parent.stages) {
                  if (this.$parent.stages[i][0].id === response.data.id) {
                    this.$parent.stages[i][0] = response.data
                  }
                }
              } else {
                for (let i = 0, n = this.$parent.groups.length; i < n; i++) {
                  if (this.$parent.groups[i].id === response.data.id) {
                    this.$parent.groups[i] = response.data
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