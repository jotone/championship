<template>
  <div class="tours-wrap">
    <div class="tour-item-wrap" v-for="(tour) in tours">
      <div class="tour-group-caption" v-for="(group) in tour">
        {{ group.name }}
      </div>

      <div class="play-off-games-list">

      </div>

      <div class="add-game-wrap">
        <button class="btn success" name="addGame" type="button">
          Add Play-off Game
        </button>
      </div>
    </div>
    <div class="add-tour-wrap">
      <button class="btn" name="addTour" type="button">
        Add Play-off Step
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tours: {},
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
          let teamIDs = [], type = null
          for (let i = 0, n = response.data.collection.length; i < n; i++) {
            const group = response.data.collection[i]
            if (0 < group.tour) {
              if (typeof this.tours[group.tour] === 'undefined') {
                this.tours[group.tour] = []
              }

              this.tours[group.tour].push(group)

              for (let j = 0; j < group.games.length; j++) {
                const game = group.games[j]
                if (null === type) {
                  type = game.entity
                }

                // This is need to get teams data
                teamIDs.push(game.host_team)
                teamIDs.push(game.guest_team)
              }
            }
          }

          teamIDs = [...new Set(teamIDs)]

          // Teams are countries or clubs
          const teamsRequestUrl = type === 'App\\Models\\Country' ? this.routes.country.list : this.routes.team.list;

          // Get teams data
          $.axios
            .get(`${teamsRequestUrl}&where[id]=${teamIDs.join(',')}`)
            .then(response => {
              if (200 === response.status) {
                // Result teams values
                let result = {}

                // Convert teams data into proper view groupID -> teamPosition -> teamData
                for (let i = 0; i < response.data.collection.length; i++) {
                  result[response.data.collection[i].id] = response.data.collection[i]
                }
                // Set teams
                this.teams = result
              }
            })
        }
      })
  }
}
</script>