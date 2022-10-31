<template>
  <div class="games-list-wrap">
    <ul class="participant-list-wrap">
      <li v-for="user in users">
        <label>
          <input name="viewUser" :value="user.id" type="checkbox" checked @change="changeUserVisibility">
          <span>{{ user.name }}</span>
        </label>
      </li>
    </ul>
    <ul class="game-list">
      <li :data-group="group.group_id" v-for="group in groups">
        <div class="group-name">
          {{ group.name }}
        </div>

        <ul v-if="typeof group.games !== 'undefined'">
          <li v-for="(game, gameID) in group.games" class="group-games-list" :data-game="gameID">
            <div class="game-team-names">
              <span>{{ game.host.ua }}</span>
              <span>:</span>
              <span>{{ game.guest.ua }}</span>
            </div>
            <div class="game-score">
              <span>{{ game.score[game.host.id] }}</span>
              <span>-</span>
              <span>{{ game.score[game.guest.id] }}</span>
            </div>
          </li>
        </ul>

        <ul v-if="typeof group.playOff !== 'undefined'">
          <li class="game-teams-list" :data-team="teamID" v-for="(team, teamID) in group.playOff.teams">
            {{ team }}
          </li>
        </ul>
      </li>
    </ul>
  </div>

  <div class="user-list-wrap">
    <table class="user-list">
      <thead>
      <tr>
        <th
          v-for="user in users"
          class="rotate"
          :style="`display: ${user.show ? 'table-cell' : 'none'}`"
        >
          <div>
            <span>{{ user.name }}</span>
          </div>
        </th>
      </tr>
      </thead>

      <tbody>
      <template v-for="(gameData, i) in backbone.gameData">
        <tr class="user-list-heading">
          <td
            v-for="user in users"
            :data-uuid="user.id"
            :style="`display: ${user.show ? 'table-cell' : 'none'}`"
            :title='`${user.name}: кількість очок за "${getGroupName(i)}"`'
          >
            <div>
              <span>Очки: <em></em></span>
            </div>
          </td>
        </tr>
        <tr v-for="item in gameData" >
          <td
            v-for="user in users"
            :data-uuid="user.id"
            :style="`display: ${user.show ? 'table-cell' : 'none'}`"
          >
            <div class="user-list-value">
              <div class="user-list-score">
                <template
                  v-if="typeof user.games[item] !== 'undefined'"
                >
                  <span>{{ objectToArray(user.games[item].score)[0] }}</span>
                  <span>-</span>
                  <span>{{ objectToArray(user.games[item].score)[1] }}</span>
                </template>
              </div>
              <div class="user-list-points">
                <span>{{ user.games[item].points }}</span>
              </div>
            </div>
          </td>
        </tr>
      </template>

      <template v-for="(teams, groupID) in backbone.teamData">
        <tr class="user-list-heading">
          <td
            v-for="user in users"
            :data-uuid="user.id"
            :style="`display: ${user.show ? 'table-cell' : 'none'}`"
            :title='`${user.name}: кількість очок за "${getGroupName(groupID)}"`'
          >
            <div>
              <span>Очки: <em></em></span>
            </div>
          </td>
        </tr>

        <tr v-for="(teamID, i) in teams">
          <td
            v-for="user in users"
            :class="user.teamsByID[groupID].indexOf(teamID) > 0 ? 'green' : ''"
            :data-uuid="user.id"
            :data-points="user.teams[groupID].points"
            :style="`display: ${user.show ? 'table-cell' : 'none'}`"
          >
            <div class="user-list-value">
              <div v-if="typeof user.teams[groupID] !== 'undefined'" class="user-list-team">
                {{ user.teams[groupID].teams[i] }}
              </div>
            </div>
          </td>
        </tr>
      </template>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      backbone: {},
      groups: {},
      users: {}
    }
  },
  methods: {
    changeUserVisibility(e) {
      const input = $(e.target)
      for (let i = 0; i < this.users.length; i++) {
        if (this.users[i].id === input.val()) {
          this.users[i].show = input.prop('checked') ? !0 : !1;
          break;
        }
      }
    },
    /**
     * Convert iterable object to array
     * @param obj
     * @returns {[]}
     */
    objectToArray: obj => Object.keys(obj).map((key) => obj[key]),
    /**
     * Get group name by it ID
     * @param id
     * @returns {*|jQuery}
     */
    getGroupName: id => $(`.games-list-wrap > ul > li[data-group="${id}"] .group-name`).text().trim(),
    /**
     * Fill user object
     * @param {object} user
     * @param {string} gameID
     * @param {int} groupID
     */
    fillUserList: function (user, gameID, groupID = 0) {
      // Default user object
      if (typeof this.users[user.id] === 'undefined') {
        this.users[user.id] = {
          id: user.id,
          name: user.name,
          show: !0,
          total: 0,
          games: {},
          teams: {},
          teamsByID: {}
        }
      }

      this.users[user.id].total += user.points

      if (groupID) {
        // Play off
        if (typeof this.users[user.id].teams[groupID] === 'undefined') {
          this.users[user.id].teams[groupID] = {}
        }

        this.users[user.id].teams[groupID] = {
          teams: this.objectToArray(user.teams),
          points: user.points
        }
        this.users[user.id].teamsByID[groupID] = Object.keys(user.teams)
      } else {
        // Group games
        this.users[user.id].games[gameID] = {
          score: user.score,
          points: user.points
        }
      }
    }
  },
  beforeMount() {
    this.groups = JSON.parse(atob($('textarea[name="models"]').val().trim()))

    for (let i in this.groups) {
      const group = this.groups[i]

      if ('games' in group) {
        for (let j in group.games) {
          for (let u in group.games[j].users) {
            this.fillUserList(group.games[j].users[u], j)
          }
        }
      }
      if ('playOff' in group) {
        for (let u in group.playOff.users) {
          this.fillUserList(group.playOff.users[u], group.playOff.game_id, group.group_id)
        }
      }
    }

    this.users = this.objectToArray(this.users)
    this.users.sort((a, b) => a.total < b.total ? 1 : -1)

    this.groups = this.objectToArray(this.groups)
    this.groups.sort((a, b) => a.position > b.position ? 1 : -1)
  },
  mounted() {
    // Check game list filled
    new Promise(resolve => {
      const listLoadedInterval = setInterval(() => {
        if ($('.games-list-wrap > ul').length) {
          clearInterval(listLoadedInterval)
          resolve({})
        }
      }, 50)
    })
      // Fill user-list table backbone
      .then(() => {
        let backbone = {
          gameData: {},
          teamData: {}
        };
        $('.games-list-wrap > ul > li').each(function () {
          const groupID = $(this).attr('data-group')

          if ($(this).find('li[data-team]').length) {
            backbone.teamData[groupID] = []
          }
          if ($(this).find('li[data-game]').length) {
            backbone.gameData[groupID] = []
          }

          $(this).find('ul li').each(function () {
            if (typeof $(this).attr('data-game') === 'undefined' && typeof $(this).attr('data-team') === 'undefined') {
              throw new Error('No game set')
            }
            // Fill group games
            typeof $(this).attr('data-game') !== 'undefined' && backbone.gameData[groupID].push($(this).attr('data-game'))
            // Fill playoff games
            typeof $(this).attr('data-team') !== 'undefined' && backbone.teamData[groupID].push($(this).attr('data-team'))
          })
        })

        this.backbone = backbone

        return {};
      })
      // Wait until user-list table is filled
      .then(() => {
        const tableLoadInterval = setInterval(() => {
          if ($('.user-list tbody tr').length) {
            clearInterval(tableLoadInterval)
            return ({})
          }
        }, 500)
      })
      // Figure out user score points
      .then(() => {
        const height = $('.participant-list-wrap').height() - 70
        $('.user-list-wrap').css({'padding-top': height + 'px'})

        let totalGroupPoints = {}
        let rowIndex = {
          group: 0,
          pos: 0
        }
        $('.user-list tbody tr.user-list-heading').each(function () {
          const row = $(this)
          $(this).nextAll('tr').each(function () {
            if ($(this).hasClass('user-list-heading')) {
              for (let userID in totalGroupPoints) {
                row.find(`td[data-uuid="${userID}"] em`).text(totalGroupPoints[userID])
              }
              rowIndex.group++
              rowIndex.pos = 0
              totalGroupPoints = {}
              return false;
            } else {
              const gameWrap = $(`.games-list-wrap > ul > li:eq(${rowIndex.group}) li:eq(${rowIndex.pos}) .game-team-names`)
              rowIndex.pos++;

              $(this).find('td').each(function () {
                // Set game title to cell
                if (gameWrap) {
                  $(this).attr('title', gameWrap.text())
                }
                // Get user id
                const userID = $(this).attr('data-uuid')

                if (typeof userID !== 'undefined') {
                  if ($(this).find('.user-list-points').length) {
                    // User bet points
                    const points = parseInt($(this).find('.user-list-points span').text().trim());
                    // Cell background color
                    const bgColorClass = points > 0 ? (points > 1 ? 'green' : 'yellow') : ''
                    $(this).addClass(bgColorClass)
                    // Total group user points
                    totalGroupPoints[userID] = userID in totalGroupPoints
                      ? totalGroupPoints[userID] + points
                      : points
                  } else {
                    // Set playOff points
                    const prevHeading = $(this).closest('tr').prev('.user-list-heading')
                    if (prevHeading.length) {
                      prevHeading.find(`td[data-uuid="${userID}"] em`).text($(this).attr('data-points'))
                    }
                  }
                }
              })
            }
          })
        })
      })
  }
}
</script>
