<template>
  <div>
    <span v-if="!invert">{{ team.ua }}</span>
    <img
      class="flag-img"
      :src="team.img_url"
      :alt="team.code"
    >
    <span v-if="invert">{{ team.ua }}</span>
  </div>
</template>

<script>
export default {
  props: {
    invert: false,
    team: {}
  }
}
</script>

<style scoped>

</style>