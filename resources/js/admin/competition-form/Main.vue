<template>
  <div v-for="(group) in groups">
    <table class="competition-table" :data-id="group.id">
      <thead>
      <tr>
        <th class="caption-big">
          Group {{ group.name }}
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

    <table class="competition-table">
      <thead>
      <tr>
        <th>Game Date</th>
        <th>Teams</th>
        <th>Place</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(game) in group.games">
        <td>
          <input
            class="set-date"
            :name="`gameDate[${game.id}]`"
            :value="null !== game.start_at ? formatDate(game.start_at) : 'Not set'"
          >
        </td>
        <td>
          <div class="teams-swap">
            <div
              v-if="typeof teams[game.host_team] === 'object' && !!teams[game.host_team]"
              :data-id="game.host_team"
            >
              <Team :team="teams[game.host_team]"></Team>
            </div>
            <div>
              <button name="swapTeams" type="button" class="swap-btn">
                <i class="fas fa-exchange-alt"></i>
              </button>
            </div>
            <div
              v-if="typeof teams[game.guest_team] === 'object' && !!teams[game.guest_team]"
              :data-id="game.guest_team"
            >
              <Team :team="teams[game.guest_team]" :invert="1"></Team>
            </div>
          </div>
        </td>
        <td>
          <input :name="`gamePlace[${game.id}]`" class="form-input" :value="game.place || ''">
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import AirDatepicker from 'air-datepicker';
import enLocale from 'air-datepicker/locale/en';
import moment from 'moment/moment';
import {Popup} from '../libs/popup';
import Team from './Team.vue';

export default {
  components: {Team},
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
    datePickerInit() {
      $('.set-date').each(function () {
        new AirDatepicker(this, {
          locale: enLocale,
          timepicker: true,
          firstDay: 1,
          dateFormat: 'd/MMM/yyyy',
          timeFormat: 'HH:mm',
        })
      })
    },
    formatDate: val => moment(new Date(val)).format('D[/]MMM[/]YYYY HH[:]mm'),
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
        $.axios.get(`${teamsRequestUrl}&where[id]=${teamIDs.join(',')}`).then(response => {
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
    // Games date pickers
    // let i = 0
    // const datePicker = setInterval(() => {
    //   if ($('.set-date').length) {
    //     this.datePickerInit()
    //     clearInterval(datePicker)
    //   }
    //
    //   i++
    //   if (i > 1000) {
    //     clearInterval(datePicker)
    //   }
    // }, 50)

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