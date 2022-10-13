<template>
  <td :class="`country-wrap ${invert ? 'invert' : ''}`">
    <span>{{ team.ua }}</span>
    <img
      class="flag-img"
      :src="team.img_url"
      :alt="team.code"
    >
  </td>
</template>

<script>
export default {
  props: {
    invert: false,
    team: {}
  }
}
</script>