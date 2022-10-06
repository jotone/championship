<template>
  <table class="content-table" v-for="(group) in groups">
    <thead>
    <tr>
      <th colspan="4">
        <span>{{ group.name }}</span>
      </th>
    </tr>
    </thead>
    <tbody>

    <tr v-if="group.stage > 0 && group.games_number > 0" v-for="(i) in group.games_number">
      <td colspan="2">
        <TeamSelector :group="group"></TeamSelector>
      </td>
      <td colspan="2">
        <TeamSelector :group="group"></TeamSelector>
      </td>
    </tr>

    <tr v-if="group.stage > 0 && group.games_number < 1">
      <td colspan="4" style="text-align: center">
        <TeamSelector :group="group" :iter="0"></TeamSelector>
      </td>
    </tr>

    <tr v-if="group.stage === 0" v-for="(game) in group.games">
      <td><span >{{ teamsByID[game.host_team].ua }}</span></td>
      <td>
        <input
          class="team-score-input "
          min="0"
          :name="`game[${game.id}][${game.host_team}]`"
          required
          type="number"
        >
      </td>
      <td>
        <input
          class="team-score-input "
          min="0"
          :name="`game[${game.id}][${game.host_team}]`"
          required
          type="number"
        >
      </td>
      <td><span>{{ teamsByID[game.guest_team].ua }}</span></td>
    </tr>
    </tbody>
  </table>
</template>

<script>
import TeamSelector from './TeamSelector.vue';

export default {
  components: {TeamSelector},
  data() {
    return {
      groups: [],
      teams: [],
      teamsByID: {},
      jwt: null,
      routes: {}
    }
  },
  beforeMount() {
    this.routes = JSON.parse(atob($('#userForm').attr('data-routes')))
  },
  mounted() {
    // Set JWT token to Axios config
    window.$.axios.interceptors.request.use(config => {
      config.headers.Authorization = `Bearer ${atob($('#userForm').prev('input[name="_json"]').val().trim())}`;
      return config;
    })
    // Request group list
    $.axios.get(this.routes.group.list).then(response => {
      if (200 === response.status) {
        // Retrieve groups from request
        const groups = response.data.collection
        // Init team IDs and a competition teams entity type
        let teamID = [], entity = null;
        // Set entity and team IDs
        for (let i = 0, n = groups.length; i < n; i++) {
          for (let j = 0, m = groups[i].games.length; j < m; j++) {
            // Get a competition teams entity type
            if (null === entity) {
              entity = groups[i].games[j].entity
            }
            // Fill team IDs
            teamID.push(groups[i].games[j].host_team)
            teamID.push(groups[i].games[j].guest_team)
          }
        }
        // Get team IDs unique values
        teamID = [...new Set(teamID)]
        // Set url by entity value
        const url = entity === 'App\\Models\\Country'
          ? this.routes.country.list
          : this.routes.team.list
        // Get countries or teams
        $.axios.get(`${url}&where[id]=${teamID.join(',')}`)
          .then(response => {
            if (200 === response.status) {
              // Fill teams to Vue object
              let teamsByID = {}
              let teamList = response.data.collection

              for (let i = 0, n = teamList.length; i < n; i++) {
                const team = teamList[i]
                teamsByID[team.id] = team
              }
              this.teamsByID = teamsByID
              this.teams = teamList

              // Set groups to Vue object
              this.groups = groups
            }
          })
      }
    })
  }
}
</script>