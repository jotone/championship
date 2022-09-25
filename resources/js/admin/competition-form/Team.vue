<template>
  <div :class="`country-wrap ${invert ? 'invert' : ''}`">
    <span>{{ team.ua }}</span>
    <img
      class="flag-img"
      :src="team.img_url"
      :alt="team.code"
    >
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