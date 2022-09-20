<template>
  <div>
    <table v-for="(group) in groups" class="competition-table" :data-id="group.id">
      <thead>
      <tr>
        <th>
          Group {{ group.name }}
        </th>
        <th v-for="(team) in teams[group.id]" class="border">
          <span>{{ team.ua }}</span>
          <img class="flag-img" :src="team.img_url" :alt="team.code">
        </th>
        <th>Games</th>
        <th>Wins</th>
        <th>Draws</th>
        <th>Loses</th>
        <th>Balls</th>
        <th>Score</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(groupTeam) in group.teams">
        <td :data-i="JSON.stringify(groupTeam)">
          <span v-if="typeof teams[group.id] === 'object' && !!teams[group.id][groupTeam.position]">
            {{ teams[group.id][groupTeam.position].ua }}
          </span>
          <img
              v-if="typeof teams[group.id] === 'object' && !!teams[group.id][groupTeam.position]"
              class="flag-img"
              :src="teams[group.id][groupTeam.position].img_url"
              :alt="teams[group.id][groupTeam.position].code"
          >
        </td>
        <td v-for="(gameTeam) in teams[group.id]">
          <span
              v-if="teams[group.id][groupTeam.position].id !== gameTeam.id"
              class="set-date"
              :data-id="games[group.id][teams[group.id][groupTeam.position].id][gameTeam.id].id"
          >
            {{
              games[group.id][teams[group.id][groupTeam.position].id][gameTeam.id].start === null
                  ? 'Not set'
                  : games[group.id][teams[group.id][groupTeam.position].id][gameTeam.id].start
            }}
          </span>
        </td>
        <td><span>{{ groupTeam.games || 0}}</span></td>
        <td><span>{{ groupTeam.wins || 0}}</span></td>
        <td><span>{{ groupTeam.draws || 0}}</span></td>
        <td><span>{{ groupTeam.loses || 0}}</span></td>
        <td><span>{{ groupTeam.balls || '0-0'}}</span></td>
        <td><span>{{ groupTeam.score || 0 }}</span></td>
      </tr>
      <tr data-role="add-team">
        <td>
          <span class="add fas fa-plus-circle" title="Add Team" @click="showPopup"></span>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import {Popup} from "../libs/popup";

export default {
  data() {
    return {
      games: [],
      groups: [],
      teams: [],
      module: 'groups',
      routes: {},
      addRowPopup: null
    }
  },
  methods: {
    /**
     * Set Competition game value
     * @param games
     * @param groupID
     * @param hostID
     * @param guestID
     * @param obj
     * @returns {*}
     */
    setGameValue(games, groupID, hostID, guestID, obj) {
      if (typeof games[groupID] == 'undefined') {
        games[groupID] = {}
      }

      if (typeof games[groupID][hostID] == 'undefined') {
        games[groupID][hostID] = {}
      }

      games[groupID][hostID][guestID] = {
        id: obj.id,
        start: obj.start_at
      }

      return games
    },
    /**
     * Set competition team value
     * @param teams
     * @param groupID
     * @param position
     * @param team
     * @returns {*}
     */
    setTeam(teams, groupID, position, team) {
      if (typeof teams[groupID] == 'undefined') {
        teams[groupID] = []
      }
      if (typeof teams[groupID][position] === 'undefined') {
        teams[groupID][position] = team
      }

      return teams
    },
    /**
     * Show Add Team Popup
     * @param e
     */
    showPopup(e) {
      this.addRowPopup.wrap.find('input[name="group_id"]').val($(e.target).closest('.competition-table').attr('data-id'))
      this.addRowPopup.wrap.find('input[name="entity_id"]').val('')
      this.addRowPopup.wrap.find('input[name="searchSelect"]').val('')
      this.addRowPopup.open()
    }
  },
  beforeMount() {
    this.routes = $('#groupsTable').data('routes')
  },
  mounted() {
    $.axios.get(this.routes.group.list).then(response => {
      if (200 === response.status) {
        // Init variables
        let games = {}, teamIDs = [], teams = [], type = null
        // Convert games and teams values into prover view
        for (let i = 0; i < response.data.collection.length; i++) {
          const group = response.data.collection[i]
          // Set games values
          for (let j = 0; j < group.games.length; j++) {
            const game = group.games[j]
            games = this.setGameValue(games, group.id, game.host_team, game.guest_team, game)
          }
          // Set team values
          for (let j = 0; j < group.teams.length; j++) {
            const team = group.teams[j]
            if (null === type) {
              type = team.entity
            }

            teams.push({
              group: group.id,
              pos: team.position,
              id: team.entity_id
            })
            // This is need to get teams data
            teamIDs.push(team.entity_id)
          }
        }
        // Teams are countries or clubs
        const teamsRequestUrl = type === 'App\\Models\\Country' ? this.routes.country.list : this.routes.teams.list;
        // Set groups
        this.groups = response.data.collection
        // Set games
        this.games = games

        // Get teams data
        $.axios.get(`${teamsRequestUrl}&where[id]=${teamIDs.join(',')}`).then(response => {
          if (200 === response.status) {
            // Result teams values
            let result = {}
            // Convert teams data into proper view groupID -> teamPosition -> teamData
            for (let i = 0; i < response.data.collection.length; i++) {
              const team = response.data.collection[i]

              for (let j = 0; j < teams.length; j++) {
                const teamOfGroup = teams[j]
                if (team.id === teamOfGroup.id) {
                  result = this.setTeam(result, teamOfGroup.group, teamOfGroup.pos, team)
                }
              }
            }
            // Set teams
            this.teams = result
          }
        })
      }
    })
    // Popup handler
    this.addRowPopup = new Popup($('#append-team'))
    // Popup form submit event
    this.addRowPopup.wrap.find('form').on('submit', e => {
      e.preventDefault()

      const _this = $(e.target)
      // Get form data
      const formData = new FormData(_this[0])
      // Form method
      const method = _this.attr('method') || 'get';
      // Send request
      $.axios[method.toLowerCase()](_this.attr('action'), formData).then(response => {
        if (201 === response.status) {
          const model = response.data.model
          // Set games
          for (let i = 0; i < response.data.group.games.length; i++) {
            const game = response.data.group.games[i]
            this.games = this.setGameValue(this.games, game.group_id, game.host_team.id, game.guest_team.id, game)
          }
          // Set new teams
          this.teams = this.setTeam(this.teams, parseInt(model.group_id), parseInt(model.position), response.data.team)

          // Set new teams on groups
          let groups = this.groups
          for (let i = 0; i < groups.length; i++) {
            if (response.data.group.id === groups[i].id) {
              groups[i].teams.push(response.data.model);
            }
          }

          this.groups = groups

          this.addRowPopup.close()
        }
      })
    })
  }
}
</script>