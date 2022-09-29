<template>

</template>

<script>
export default {
  data() {
    return {
      tours: [],
      teams: [],
      module: 'play-offs',
      routes: {}
    }
  },
  beforeMount() {
    this.routes = $('#playOffTable').data('routes')
  },
  mounted() {
    $.axios
      .get(this.routes.group.list)
      .then(response => {
        if (200 === response.status) {
          console.log(response.data)
        }
      })
  }
}
</script>