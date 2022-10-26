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
          <a class="remove" @click.prevent="groupRemove" :href="groupRemoveRoute(stage[0].id)" title="Видалити групу">
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

      <ul class="play-off-games-list">
        <li v-for="(game) in stage[0].games" :data-id="game.id">
          <div class="game-teams-wrap">
            <span>{{ !!teams[game.host_team] ? teams[game.host_team].ua : '' }}</span>
            <span>vs</span>
            <span>{{ !!teams[game.guest_team] ? teams[game.guest_team].ua : '' }}</span>
          </div>
          <div class="game-date-wrap">
            <DatePicker
              :name="`gameDate[${game.id}]`"
              :value="game.hasOwnProperty('start_at') && null !== game.start_at ? formatDate(game.start_at) : 'The date is not set'"
            ></DatePicker>
          </div>
          <div class="game-place-wrap">
            <input
              class="form-input"
              placeholder="Set game place&hellip;"
              @keyup="gameChangePlace"
              :name="`gamePlace[${game.id}]`"
              :value="game.place || ''"
            >
          </div>
          <form class="game-teams-score-wrap">
            <GameScore :game="game"></GameScore>
          </form>
          <div class="game-accept-wrap">
            <label>
              <input
                name="accept"
                type="checkbox"
                v-model="game.accept"
                @click="gameAccept"
              >
              <span style="margin-left: 10px">Прийняти</span>
            </label>
          </div>

          <div class="game-controls-wrap">
            <a class="remove" :href="gameRemoveRoute(game.id)" title="Видалити гру" @click.prevent="gameRemove">
              <i class="fas fa-times"></i>
            </a>
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
      <button class="btn" name="addStage" type="button" @click="groupAdd">
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
      module: 'play-offs',
      routes: {},
      addGamePopup: null
    }
  },
  methods: {
    /**
     * Remove game
     * @param e
     */
    gameRemove(e) {
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
                for (let j = 0, m = this.stages[i][0].games.length; j < m; j++) {
                  this.stages[i][0].games[j].id === id && this.stages[i][0].games.splice(j, 1)
                }
              }
            }
          }
        })
      )
    },
    /**
     * Create a new stage
     */
    groupAdd() {
      let formData = new FormData()
      formData.append('competition_id', $('#playOffTable').data('id'))
      formData.append('stage', $('#playOffTable').find('.stage-item-wrap').length + 1)

      $.axios
        .post(this.routes.group.store, formData)
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

      const confirm = new Confirmation(`Ви дійсно хочете видалити стадію "${name}"?`).open()

      confirm.then(answer => answer && $.axios
        .delete(_this.attr('href'))
        .then(response => {
          if (204 === response.status) {
            delete this.stages[index]
          }
        })
        .finally(() => $('.overlay, .overlay .preload').hide())
      )
    },
    /**
     * View add game popup
     * @param e
     */
    showGamePopup(e) {
      const groupID = $(e.target).closest('.stage-item-wrap').attr('data-id')

      this.addGamePopup.wrap.find('input[name="group_id"]').val(groupID)
      this.addGamePopup.wrap.find('input[name="entity"]').val(this.entity)

      this.addGamePopup.wrap.find('select[name="host_team"]').html(this.teamsOptionsList())
      this.addGamePopup.wrap.find('select[name="guest_team"]').html(this.teamsOptionsList())
      this.addGamePopup.open()
    },
    /**
     * View teams as select options list
     * @param selected
     * @returns {string}
     */
    teamsOptionsList(selected = null) {
      let options = ''
      for (let id in this.teams) {
        options += `<option value="${id}"${selected === parseInt(id) ? 'selected' : ''}>${this.teams[id].ua}</option>`
      }
      return options
    },
    /**
     * Update games entities
     * @param data
     */
    updateGames(data) {
      for (let i in this.stages) {
        if (this.stages[i][0].id === data.id) {
          this.stages[i][0] = data
        }
      }
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

    // Popup handler
    this.addGamePopup = new Popup($('#add-group-game'))

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
              if (!this.stages[i][0].hasOwnProperty('games')) {
                this.stages[i][0].games = []
              }
              this.stages[i][0].games.push(response.data)
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