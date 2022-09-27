<template>
  <div v-for="(group) in groups">
    <table class="competition-table" :data-id="group.id">
      <thead>
      <tr>
        <th>
          <div class="group-caption-wrap">
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
        </th>
        <th>Games</th>
        <th>Wins</th>
        <th>Draws</th>
        <th>Loses</th>
        <th>Balls</th>
        <th>Score</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(groupTeam) in group.teams" :data-id="groupTeam.entity_id">
        <td>
          <Team
            v-if="typeof teams[groupTeam.entity_id] === 'object' && !!teams[groupTeam.entity_id]"
            :team="teams[groupTeam.entity_id]"
          ></Team>
        </td>
        <td><span>{{ groupTeam.games || 0 }}</span></td>
        <td><span>{{ groupTeam.wins || 0 }}</span></td>
        <td><span>{{ groupTeam.draws || 0 }}</span></td>
        <td><span>{{ groupTeam.loses || 0 }}</span></td>
        <td><span>{{ groupTeam.balls || '0-0' }}</span></td>
        <td><span>{{ groupTeam.score || 0 }}</span></td>
        <td>
          <a class="remove" :href="teamDestroyRoute(groupTeam.id)" @click.prevent="teamRemove">
            <i class="fas fa-times"></i>
          </a>
        </td>
      </tr>
      </tbody>
      <tfoot>
      <tr data-role="add-team">
        <td colspan="8">
          <span class="add fas fa-plus-circle" title="Add Team" @click="showTeamPopup"></span>
        </td>
      </tr>
      </tfoot>
    </table>

    <table class="competition-table" :data-id="group.id">
      <thead>
      <tr>
        <th>Game Date</th>
        <th colspan="3">Teams</th>
        <th>Place</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(game) in group.games" :data-id="game.id">
        <td>
          <DatePicker
            :name="`gameDate[${game.id}]`"
            :value="null !== game.start_at ? formatDate(game.start_at) : 'Not set'"
          ></DatePicker>
        </td>
        <td>
          <Team
            v-if="typeof teams[game.host_team] === 'object' && !!teams[game.host_team]"
            :data-id="game.host_team"
            :team="teams[game.host_team]"
          ></Team>
        </td>
        <td>
          <button
            name="swapTeams"
            type="button"
            title="Swap host and guest teams"
            class="swap-btn"
            @click="swapTeams"
          >
            <i class="fas fa-exchange-alt"></i>
          </button>
        </td>
        <td>
          <Team
            v-if="typeof teams[game.guest_team] === 'object' && !!teams[game.guest_team]"
            :data-id="game.guest_team"
            :team="teams[game.guest_team]"
            :invert="1"
          >
          </Team>
        </td>
        <td>
          <input
            class="form-input"
            @keyup="gameChangePlace"
            :name="`gamePlace[${game.id}]`"
            :value="game.place || ''"
          >
        </td>
        <td>
          <a class="remove" :href="gameRemoveRoute(game.id)" @click.prevent="gameRemove">
            <i class="fas fa-times"></i>
          </a>
        </td>
      </tr>
      </tbody>

      <tfoot>
      <tr data-role="add-team">
        <td colspan="8">
          <span class="add fas fa-plus-circle" title="Add game" @click="showGamePopup"></span>
        </td>
      </tr>
      </tfoot>
    </table>
  </div>
</template>

<script>
import moment from 'moment/moment';
import {Popup} from '../libs/popup';
import DatePicker from "./DatePicker.vue";
import Team from './Team.vue';
import {Confirmation} from "../libs/confirmation";
import {debounce} from "debounce";

export default {
  components: {DatePicker, Team},
  data() {
    return {
      groups: [],
      teams: [],
      module: 'groups',
      routes: {},
      addGamePopup: null,
      addTeamPopup: null,
    }
  },
  methods: {
    gameChangePlace: debounce(function (e) {
      const _this = $(e.target)
      let formData = new FormData()
      formData.append('_method', 'patch')
      formData.append('place', _this.val().trim())

      const id = _this.closest('tr').data('id')

      $.axios
        .post(this.gameUpdateRoute(), formData)
        .then(response => {
          if (200 === response.status) {
            this.updateGames(response.data, id)
          }
        })
    }, 500),
    gameRemove(e) {
      const _this = $(e.target).closest('a')

      const id = parseInt(_this.closest('tr').attr('data-id'));
      const groupID = parseInt(_this.closest('.competition-table').attr('data-id'))
      const confirm = new Confirmation(`Do you really want to remove this game?`).open()

      confirm.then(answer => answer && $.axios
        .delete(_this.attr('href'))
        .then(response => {
          if (204 === response.status) {
            for (let i = 0, n = this.groups.length; i < n; i++) {
              if (this.groups[i].id === groupID) {
                for (let j = 0, m = this.groups[i].games.length; j < m; j++) {
                  this.groups[i].games[j].id === id && this.groups[i].games.splice(j, 1)
                }
              }
            }
          }
        })
      )
    },
    gameRemoveRoute(id) {
      return window.Helpers.buildUrl(this.routes.game.destroy, id, 1)
    },
    gameUpdateRoute(id) {
      return window.Helpers.buildUrl(this.routes.game.update, id, 1)
    },
    /**
     * Remove group route
     * @param id
     * @returns {string}
     */
    groupRemoveRoute(id) {
      return window.Helpers.buildUrl(this.routes.group.destroy, id, 1)
    },
    /**
     * Remove group
     * @param e
     */
    groupRemove(e) {
      const _this = $(e.target).closest('a')

      const id = parseInt(_this.closest('.competition-table').attr('data-id'))

      const name = _this.closest('.group-caption-wrap').children('span').text().trim()
      const confirm = new Confirmation(`Do you really want to remove group "${name}"?`).open()

      confirm.then(answer => answer && $.axios
        .delete(_this.attr('href'))
        .then(response => {
          if (204 === response.status) {
            for (let i = 0, n = this.groups.length; i < n; i++) {
              !!this.groups[i] && this.groups[i].id === id && this.groups.splice(i, 1);
            }
          }
        })
        .finally(() => $('.overlay, .overlay .preload').hide())
      )
    },
    /**
     * Send request to update the group name
     * @param e
     */
    groupUpdateName(e) {
      const _this = $(e.target).closest('a')
      const name = _this.closest('.group-caption-wrap').find('input[name="groupName"]').val().trim()

      let formData = new FormData()
      formData.append('_method', 'patch')
      formData.append('name', name)

      $.axios
        .post(_this.attr('href'), formData)
        .then(response => {
          if (200 === response.status) {
            _this.closest('.group-caption-wrap').children('input').replaceWith(function () {
              return $(`<span>${name}</span>`);
            })
            _this.closest('.group-controls').find('.accept').hide()
            _this.closest('.group-controls').find('.edit, .remove').show()
          }
        })
    },
    /**
     * @param id
     * @returns {string}
     */
    groupUpdateRoute(id) {
      return window.Helpers.buildUrl(this.routes.group.update, id, 1)
    },
    /**
     * Convert a date value to the proper view
     * @param val
     * @returns {string}
     */
    formatDate: val => moment(new Date(val)).format('D[/]MMM[/]YYYY HH[:]mm'),
    /**
     * View group name input
     * @param e
     */
    showGroupNameEditor(e) {
      const _this = $(e.target)
      _this.closest('.group-caption-wrap').children('span').replaceWith(function () {
        return $(`<input class="form-input" name="groupName" value="${$(this).text()}">`);
      })
      _this.closest('.group-controls').find('.edit, .remove').hide()
      _this.closest('.group-controls').find('.accept').show()
    },
    /**
     * Show Add game popup
     * @param e
     */
    showGamePopup(e) {
      const groupID = $(e.target).closest('.competition-table').attr('data-id')

      const url = this.routes.group.list + '&where[id]=' + groupID
      $.axios.get(url).then(response => {
        if (200 === response.status && !!response.data.collection[0]) {

          const teams = response.data.collection[0].teams
          this.addGamePopup.wrap.find('input[name="group_id"]').val(groupID)
          this.addGamePopup.wrap
            .find('select[name="host_team"]')
            .html(teams.reduce((sum, cur) => sum + `<option value="${cur.entity_id}">${this.teams[cur.entity_id].ua}</option>`, ''))
          this.addGamePopup.wrap
            .find('select[name="guest_team"]')
            .html(teams.reduce((sum, cur) => sum + `<option value="${cur.entity_id}">${this.teams[cur.entity_id].ua}</option>`, ''))
          this.addGamePopup.open()
        }
      })
    },
    /**
     * Show Add Team Popup
     * @param e
     */
    showTeamPopup(e) {
      const groupID = $(e.target).closest('.competition-table').attr('data-id')
      this.addTeamPopup.wrap.find('input[name="group_id"]').val(groupID)
      this.addTeamPopup.wrap.find('input[name="entity_id"]').val('')
      this.addTeamPopup.wrap.find('input[name="searchSelect"]').val('')
      this.addTeamPopup.open()
    },
    /**
     * Swap host amd guest teams in match
     * @param e
     */
    swapTeams(e) {
      const _this = $(e.target)

      const parent = _this.closest('tr')

      let formData = new FormData()
      formData.append('_method', 'patch')
      formData.append('host_team', parent.find('td:eq(1) .country-wrap').attr('data-id'))
      formData.append('guest_team', parent.find('td:eq(3) .country-wrap').attr('data-id'))

      $.axios
        .post(this.gameUpdateRoute(parent.attr('data-id')), formData)
        .then(response => {
          if (200 === response.status) {
            this.updateGames(response.data, parent.attr('data-id'))

            const host = parent.find('td:eq(1)').clone()
            const guest = parent.find('td:eq(3)').clone()

            parent.find('td:eq(1)').html(guest.html())
            parent.find('td:eq(3)').html(host.html())
          }
        })
    },
    /**
     *
     * @param id
     * @returns {string}
     */
    teamDestroyRoute(id) {
      return window.Helpers.buildUrl(this.routes.team.destroy, id, 1)
    },
    teamRemove(e) {
      const _this = $(e.target).closest('a')

      const name = _this.closest('tr').find('td:eq(0) span').text().trim()

      const confirm = new Confirmation(`Do you really want to remove team "${name}"?`).open()

      confirm.then(answer =>
        answer && $.axios.delete(_this.attr('href')).then(
          response => 204 === response.status && window.location.reload())
      )
    },
    /**
     * Update games entities
     * @param data
     * @param id
     */
    updateGames(data, id) {
      for (let i = 0, n = this.groups.length; i < n; i++) {
        if (this.groups[i].id === data.group_id) {
          for (let j = 0, m = this.groups[i].games.length; j < m; j++) {
            if (id === this.groups[i].games[j].id) {
              this.groups[i].games[j] = data
            }
          }
        }
      }
    }
  },
  beforeMount() {
    this.routes = $('#groupsTable').data('routes')
  },
  mounted() {
    $.axios
      .get(this.routes.group.list)
      .then(response => {
        if (200 === response.status) {
          // Init variables
          let teamIDs = [], teams = [], type = null
          // Convert games and teams values into prover view
          for (let i = 0; i < response.data.collection.length; i++) {
            const group = response.data.collection[i]
            // Set team values
            for (let j = 0; j < group.teams.length; j++) {
              const team = group.teams[j]
              if (null === type) {
                type = team.entity
              }

              teams.push({
                group: group.id,
                id: team.entity_id
              })
              // This is need to get teams data
              teamIDs.push(team.entity_id)
            }
          }
          // Teams are countries or clubs
          const teamsRequestUrl = type === 'App\\Models\\Country' ? this.routes.country.list : this.routes.team.list;
          // Set groups
          this.groups = response.data.collection

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
    this.addGamePopup = new Popup($('#add-group'))
    this.addTeamPopup = new Popup($('#add-team'))
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
          for (let i = 0, n = this.groups.length; i < n; i++) {
            if (this.groups[i].id === parseInt(response.data.group_id)) {
              this.groups[i].games.push(response.data)
              break;
            }
          }
          this.addGamePopup.close()
        }
      })
    })
    // Popup team form submit event
    this.addTeamPopup.wrap.find('form').on('submit', e => {
      e.preventDefault()

      const _this = $(e.target)
      // Get form data
      const formData = new FormData(_this[0])
      // Form method
      const method = _this.attr('method') || 'get';
      // Send request
      $.axios[method.toLowerCase()](_this.attr('action'), formData).then(response => {
        if (201 === response.status) {
          const team = response.data.team
          const group = response.data.group
          for (let i = 0, n = this.groups.length; i < n; i++) {
            if (this.groups[i].id === group.id) {
              this.groups[i] = group
              break;
            }
          }
          this.teams[team.id] = team

          this.addTeamPopup.close()
        }
      })
    })
  }
}
</script>