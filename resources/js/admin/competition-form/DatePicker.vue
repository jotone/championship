<template>
  <input class="set-date" :name="name" :value="value">
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

        const url = window.Helpers.buildUrl(this.$parent.routes.game.update, $(e.datepicker.$el).closest('tr').data('id'), 1)

        $.axios.post(url, formData)
      }, 500)
    })
  }
}
</script>