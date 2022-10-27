<template>
  <div class="stages-wrap">
    <div class="stage-item-wrap" v-for="(stage) in stages" :data-id="stage[0].id">
      <div class="group-caption-wrap">
        <div class="move-group">
          <i class="fas fa-ellipsis-v"></i>
          <i class="fas fa-ellipsis-v"></i>
        </div>

        <span>{{ stage[0].name }}</span>

        <div class="group-controls">
          <a class="edit" @click.prevent="showGroupNameEditor" title="Редагувати назву групи">
            <i class="fas fa-edit"></i>
          </a>
          <a class="remove" @click.prevent="stageRemove" :href="groupRemoveRoute(stage[0].id)" title="Видалити групу">
            <i class="fas fa-times"></i>
          </a>
          <a
            class="accept"
            style="display: none; padding-right: 20px"
            title="Прийняти зміни"
            :href="groupUpdateRoute(stage[0].id)"
            @click.prevent="groupUpdateName"
          >
            <i class="fas fa-check"></i>
          </a>
        </div>
      </div>

      <ul class="play-off-games-list" v-if="stage[0].games.length > 0">
        <li v-for="(gameTeamID) in stage[0].games[0].score" :data-id="gameTeamID">
          <div class="game-teams-wrap">
            <div class="game-teams-name">{{ !!teamsByID[gameTeamID] ? teamsByID[gameTeamID].ua : '' }}</div>
            <div class="game-controls-wrap">
              <a
                class="remove"
                title="Видалити гру"
                :href="teamRemoveRoute(stage[0].games[0].id, gameTeamID)"
                @click.prevent="teamRemove"
              >
                <i class="fas fa-times"></i>
              </a>
            </div>
          </div>
        </li>
      </ul>

      <div class="add-game-wrap">
        <button class="btn success" name="addGame" type="button" @click="showGamePopup">
          Додати гру плей-офф
        </button>
      </div>
    </div>
    <div class="add-stage-wrap">
      <button class="btn" name="addStage" type="button" @click="stageAdd">
        Додати стадію плей-офу
      </button>
    </div>
  </div>
</template>

<script>

import {CompetitionMixin} from './competition-mixin';
import {Confirmation} from '../libs/confirmation';
import {Popup} from '../libs/popup';
import DatePicker from './DatePicker.vue';
import GameScore from './GameScore.vue';

export default {
  components: {GameScore, DatePicker},
  data() {
    return {
      entity: null,
      stages: {},
      teams: [],
      teamsByID: {},
      module: 'play-offs',
      routes: {},
      addGamePopup: null
    }
  },
  methods: {
    /**
     * View add game popup
     * @param e
     */
    showGamePopup(e) {
      // Stage group id
      const groupID = $(e.target).closest('.stage-item-wrap').attr('data-id')
      // Add variables to game popup
      this.addGamePopup.wrap.find('input[name="group_id"]').val(groupID)
      this.addGamePopup.wrap.find('input[name="entity"]').val(this.entity)
      this.addGamePopup.wrap.find('.stage-teams-list select[name="team"]').html(this.teamsOptionsList())
      // Show popup
      this.addGamePopup.open()
    },
    /**
     * Create a new stage
     */
    stageAdd() {
      // Fill form data
      let formData = new FormData()
      formData.append('competition_id', $('#playOffTable').data('id'))
      formData.append('stage', $('#playOffTable').find('.stage-item-wrap').length + 1)
      // Send request
      $.axios.post(this.routes.group.store, formData).then(response => {
        // 201 - Created
        if (201 === response.status) {
          // Check if states exist
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
    stageRemove(e) {
      // Link object
      const _this = $(e.target).closest('a')
      // Parent item
      const parent = _this.closest('.stage-item-wrap')
      // Stage ID
      const id = parseInt(parent.attr('data-id'))
      // Stage name
      const name = parent.find('.group-caption-wrap span').text().trim()
      // Set Confirmation message and open its window
      const confirm = new Confirmation(`Ви дійсно хочете видалити стадію "${name}"?`).open()

      confirm.then(answer => answer && $.axios.delete(_this.attr('href'))
        .then(response => {
          // 204 - Removed
          if (204 === response.status) {
            // Remove stage item from list
            for (let i in this.stages) {
              this.stages[i][0].id === id && delete this.stages[i]
            }
          }
        })
        .finally(() => $('.overlay, .overlay .preload').hide())
      )
    },
    /**
     * Remove game
     * @param e
     */
    teamRemove(e) {
      const _this = $(e.target).closest('a')

      const id = parseInt(_this.closest('li').attr('data-id'));
      const groupID = parseInt(_this.closest('.stage-item-wrap').attr('data-id'))
      const confirm = new Confirmation(`Ви дійсно хочете видалити цей матч?`).open()

      confirm.then(answer => answer && $.axios
        .delete(_this.attr('href'))
        .then(response => {
          if (204 === response.status) {
            for (let i in this.stages) {
              if (groupID === this.stages[i][0].id) {
                for (let j = 0, m = this.stages[i][0].games[0].score; j < m; j++) {
                  // Remove team from play-off
                  this.stages[i][0].games[0].score[j] === id && this.stages[i][0].games[0].score.splice(j, 1)
                }
              }
            }
          }
        })
      )
    },
    /**
     * Team remove route
     * @param gameId
     * @param teamId
     * @returns {string}
     */
    teamRemoveRoute(gameId, teamId) {
      return window.Helpers.buildUrl(window.Helpers.buildUrl(this.routes.game.delete, gameId, 2), teamId, 1)
    },
    /**
     * View teams as select options list
     * @param selected
     * @returns {string}
     */
    teamsOptionsList(selected = null) {
      return this.teams.reduce(
        (sum, cur) => sum +
          `<option value="${cur.id}" ${selected === parseInt(cur.id) ? 'selected' : ''}>${cur.ua}</option>`,
        ''
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
          let teamIDs = [];

          for (let i = 0, n = response.data.collection.length; i < n; i++) {
            const group = response.data.collection[i]
            // Fill stages
            if (0 < group.stage) {
              if (typeof this.stages[group.stage] === 'undefined') {
                this.stages[group.stage] = []
              }
              this.stages[group.stage].push(group)
            }

            for (let j = 0; j < group.games.length; j++) {
              // Get teams entity type
              const game = group.games[j]
              if (null === this.entity) {
                this.entity = game.entity
              }
              // This is need to get teams data
              teamIDs.push(game.host_team)
              teamIDs.push(game.guest_team)
            }
          }

          // Array unique values
          teamIDs = [...new Set(teamIDs)]

          // Teams are countries or clubs
          const teamsRequestUrl = this.entity === 'App\\Models\\Country'
            ? this.routes.country.list
            : this.routes.team.list;

          // Get teams data
          $.axios
            .get(`${teamsRequestUrl}&where[id]=${teamIDs.join(',')}`)
            .then(response => {
              if (200 === response.status) {
                // Result teams values
                let result = []

                // Convert teams data into proper view groupID -> teamPosition -> teamData
                for (let i = 0; i < response.data.collection.length; i++) {
                  const team = response.data.collection[i]
                  this.teamsByID[team.id] = team
                  result.push(team)
                }
                // Set teams
                result.sort((a, b) => a.ua.localeCompare(b.ua))
                this.teams = result
              }
            })
        }
      })

    // Popup handler
    this.addGamePopup = new Popup($('#add-play-off-game'))

    // Popup game form submit event
    this.addGamePopup.wrap.find('form').on('submit', e => {
      e.preventDefault()

      const _this = $(e.target)
      // Get form data
      const formData = new FormData(_this[0])
      // Form method
      const method = _this.attr('method') || 'get';

      // Send request
      $.axios[method.toLowerCase()](_this.attr('action'), formData).then(response => {
        if (201 === response.status) {
          for (let i in this.stages) {
            if (this.stages[i][0].id === parseInt(response.data.group_id)) {
              // Create stage games if not exist
              if (!this.stages[i][0].hasOwnProperty('games')) {
                this.stages[i][0].games = []
              }
              // Create game score if not exists
              !this.stages[i][0].games.length && this.stages[i][0].games.push({score: []})
              // Set game score
              this.stages[i][0].games[0].score = response.data.score
            }
          }
        }

        this.addGamePopup.close()
      })
    })
  },
  mixins: [CompetitionMixin]
}
</script>