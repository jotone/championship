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
      </tr>
      </thead>
      <tbody>
      <tr v-for="(groupTeam) in group.teams">
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
      </tr>
      <tr data-role="add-team">
        <td>
          <span class="add fas fa-plus-circle" title="Add Team" @click="showPopup"></span>
        </td>
      </tr>
      </tbody>
    </table>

    <table class="competition-table">
      <thead>
      <tr>
        <th>Game Date</th>
        <th colspan="3">Teams</th>
        <th>Place</th>
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
          <button name="swapTeams" type="button" class="swap-btn">
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
          <input :name="`gamePlace[${game.id}]`" class="form-input" :value="game.place || ''" @keyup="gameChangePlace">
        </td>
      </tr>
      </tbody>
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
      addRowPopup: null
    }
  },
  methods: {
    gameChangePlace: debounce(function (e) {
      const _this = $(e.target)
      let formData = new FormData()
      formData.append('_method', 'patch')
      formData.append('place', _this.val().trim())

      $.axios.post(window.Helpers.buildUrl(this.routes.game.update, _this.closest('tr').data('id'), 1), formData)
    }, 500),
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
      const confirm = new Confirmation(`Do you really want to remove group "${name}"`).open()

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
          const team = response.data.team
          const group = response.data.group
          for (let i = 0, n = this.groups.length; i < n; i++) {
            if (this.groups[i].id === group.id) {
              this.groups[i] = group
            }
          }
          this.teams[team.id] = team

          this.addRowPopup.close()
        }
      })
    })
  }
}
</script>