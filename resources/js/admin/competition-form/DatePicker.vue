<template>
  <input class="set-date" :name="name" :value="value">
</template>

<script>
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
    })
  }
}
</script>