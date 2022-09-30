<template>
  <div class="stages-wrap">
    <div class="stage-item-wrap" v-for="(stage, i) in stages">
      <div class="group-caption-wrap" v-for="(group) in stage" :data-index="i">
        <div class="move-group">
          <i class="fas fa-ellipsis-v"></i>
          <i class="fas fa-ellipsis-v"></i>
        </div>

        <span>{{ group.name }}</span>

        <div class="group-controls">
          <a class="edit" @click.prevent="showGroupNameEditor" title="Edit group name">
            <i class="fas fa-edit"></i>
          </a>
          <a class="remove" @click.prevent="groupRemove" :href="groupRemoveRoute(group.id)" title="Remove group">
            <i class="fas fa-times"></i>
          </a>
          <a
            class="accept"
            style="display: none; padding-right: 20px"
            title="Accept changes"
            :href="groupUpdateRoute(group.id)"
            @click.prevent="groupUpdateName"
          >
            <i class="fas fa-check"></i>
          </a>
        </div>
      </div>

      <div class="play-off-games-list">

      </div>

      <div class="add-game-wrap">
        <button class="btn success" name="addGame" type="button">
          Add Play-off Game
        </button>
      </div>
    </div>
    <div class="add-stage-wrap">
      <button class="btn" name="addStage" type="button" @click="groupAdd">
        Add Play-off Step
      </button>
    </div>
  </div>
</template>

<script>

import { CompetitionMixin } from './competition-mixin';
import { Confirmation } from '../libs/confirmation';

export default {
  data() {
    return {
      stages: {},
      teams: [],
      module: 'play-offs',
      routes: {}
    }
  },
  methods: {
    /**
     * Create a new stage
     */
    groupAdd() {
      let formData = new FormData()
      formData.append('competition_id', $('#playOffTable').data('id'))
      formData.append('stage', $('#playOffTable').find('.stage-item-wrap').length + 1)

      $.axios.post(this.routes.group.store, formData)
        .then(response => {
          if (201 === response.status) {
            if (typeof this.stages[response.data.stage] === 'undefined') {
              this.stages[response.data.stage] = []
            }
            this.stages[response.data.stage].push(response.data)
          }
        })
    },
    /**
     * Remove a stage
     * @param e
     */
    groupRemove(e) {
      const _this = $(e.target).closest('a')

      const parent = _this.closest('.stage-item-wrap')
      const index = parent.index() + 1

      const name = parent.find('.group-caption-wrap span').text().trim()

      const confirm = new Confirmation(`Do you really want to remove stage "${name}"?`).open()

      confirm.then(answer => answer && $.axios
        .delete(_this.attr('href'))
        .then(response => {
          if (204 === response.status) {
            delete this.stages[index]
          }
        })
        .finally(() => $('.overlay, .overlay .preload').hide())
      )
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
            if (0 < group.stage) {
              if (typeof this.stages[group.stage] === 'undefined') {
                this.stages[group.stage] = []
              }

              this.stages[group.stage].push(group)

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

          // Array unique values
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
  },
  mixins: [CompetitionMixin]
}
</script>