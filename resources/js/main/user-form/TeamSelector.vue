<template>
  <select
    class="team-selector"
    required
    :data-group="group.id"
    :name="`group[${group.id}][]`"
    @change="removeSelectorTeam"
  >
    <option disabled selected value>Виберіть команду</option>
    <option :value="team.id" v-if="null !== iter" v-for="(team) in teams">
      {{ team.ua }}
    </option>
  </select>
</template>

<script>
export default {
  props: {
    group: {},
    iter: null
  },
  data() {
    return {
      iteration: 0,
      playOffSelectors: {},
      teams: {},
    }
  },
  methods: {
    removeSelectorTeam(e) {
      const _this = $(e.target).closest('select')

    }
  },
  mounted: function () {
    this.teams = this.$parent.teams
  }
}
</script>