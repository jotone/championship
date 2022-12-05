<template>
  <div class="games-list-wrap">
    <div class="show-block active" @click="hideUserBlock">
      <span>Список користувачів</span>
      <i class="fas fa-angle-right"></i>
    </div>

    <ul class="participant-list-wrap active">
      <li v-for="user in users">
        <label>
          <input name="viewUser" :value="user.id" type="checkbox" checked @change="changeUserVisibility">
          <span>{{ user.name }}</span>
        </label>
      </li>
    </ul>

    <ul class="game-list active">
      <li :data-group="group.group_id" v-for="group in groups">
        <div class="group-name">
          {{ group.name }}
        </div>

        <ul v-if="typeof group.games !== 'undefined'">
          <li v-for="(game, gameID) in group.games" class="group-games-list" :data-game="gameID">
            <div class="game-team-names">
              <span :data-uuid="game.host.id">{{ game.host.name }}</span>
              <span>:</span>
              <span :data-uuid="game.guest.id">{{ game.guest.name }}</span>
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
    <div class="expand-box active"></div>
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
        <tr v-for="item in gameData">
          <td
            v-for="user in users"
            :data-uuid="user.id"
            :style="`display: ${user.show ? 'table-cell' : 'none'}`"
          >
            <div class="user-list-value">
              <div class="user-list-score">
                <template
                  v-if="typeof user.games[item.game] !== 'undefined'"
                >
                  <span>{{ user.games[item.game].score[item.host] }}</span>
                  <span>-</span>
                  <span>{{ user.games[item.game].score[item.guest] }}</span>
                </template>
              </div>
              <div class="user-list-points">
                <span>{{ user.games[item.game].points }}</span>
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

        <tr v-for="(teamID, i) in teams" :data-i="JSON.stringify(teams)">
          <td
            v-for="user in users"
            :data-uuid="user.id"
            :data-points="user.teams[groupID].points"
            :style="`display: ${user.show ? 'table-cell' : 'none'}`"
            :class="typeof finalists[groupID] !== 'undefined' && finalists[groupID].indexOf(user.teams[groupID].teams[i].id) >= 0 ? 'green' : ''"
          >
            <div class="user-list-value">
              <div v-if="typeof user.teams[groupID] !== 'undefined'" class="user-list-team">
                {{ user.teams[groupID].teams[i].name }}
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
      finalists: {},
      height: 0,
      groups: {},
      users: {}
    }
  },
  methods: {
    /**
     * View or hide user column
     * @param e
     */
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
     * Change height of Expand box
     * @returns {*|jQuery}
     */
    expandBoxHeight: () => $('.expand-box').css({'height': ($('.participant-list-wrap').height() - 50) + 'px'}),
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
          teams: {}
        }
      }

      this.users[user.id].total += user.points

      if (groupID) {
        // Play off
        if (typeof this.users[user.id].teams[groupID] === 'undefined') {
          this.users[user.id].teams[groupID] = {}
        }

        this.users[user.id].teams[groupID] = {
          teams: this.objectToFlatArr(user.teams),
          points: user.points
        }
      } else {
        // Group games
        this.users[user.id].games[gameID] = {
          score: user.score,
          points: user.points
        }
      }
    },
    /**
     * Get group name by it ID
     * @param id
     * @returns {*|jQuery}
     */
    getGroupName: id => $(`.games-list-wrap > ul > li[data-group="${id}"] .group-name`).text().trim(),
    /**
     * Hide / show participant list
     * @param e
     */
    hideUserBlock(e) {
      const _this = $(e.target).closest('.show-block')
      _this.toggleClass('active')
      $('.participant-list-wrap, .game-list').toggleClass('active')
      $('.expand-box').toggleClass('active')

      $('.expand-box').hasClass('active') && this.expandBoxHeight()
    },
    /**
     * Convert object of type id => value to {id:id, name: value}
     * @param obj
     * @returns {*[]}
     */
    objectToFlatArr: obj => {
      let result = []
      for (let i in obj) {
        result.push({id: i, name: obj[i]})
      }
      return result;
    },
    /**
     * Convert iterable object to array
     * @param obj
     * @returns {[]}
     */
    objectToArray: obj => Object.keys(obj).map((key) => obj[key])
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
        this.expandBoxHeight()

        let finalists = {}, backbone = {
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

          if ($(this).find('.game-teams-list').length) {
            if (typeof finalists[groupID] === 'undefined') {
              finalists[groupID] = []
            }

            $(this).find('.game-teams-list').each(function () {
              finalists[groupID].push($(this).data('team'))
            })
          }

          $(this).find('ul li').each(function () {
            if (typeof $(this).attr('data-game') === 'undefined' && typeof $(this).attr('data-team') === 'undefined') {
              throw new Error('No game set')
            }
            // Fill group games
            typeof $(this).attr('data-game') !== 'undefined' && backbone.gameData[groupID].push({
              game: $(this).attr('data-game'),
              host: $(this).find('.game-team-names span:first').attr('data-uuid'),
              guest: $(this).find('.game-team-names span:last').attr('data-uuid')
            })
            // Fill playoff games
            typeof $(this).attr('data-team') !== 'undefined' && backbone.teamData[groupID].push($(this).attr('data-team'))
          })
        })

        if (!!backbone.teamData && this.users.length) {
          for (let groupID in this.users[0].teams) {
            backbone.teamData[groupID] = this.objectToArray(this.users[0].teams[groupID].teams)
          }
        }

        this.backbone = backbone
        this.finalists = finalists

        return {};
      })
      // Wait until user-list table is filled
      .then(() => {
        const tableLoadInterval = setInterval(() => {
          if ($('.user-list tbody tr').length && $('.games-list-wrap > ul').length) {
            clearInterval(tableLoadInterval)
            return ({})
          }
        }, 500)
      })
      // Fix empty groups height
      .then(() => $('.game-list ul').each(function () {
        const listWrap = $(this)
        if(listWrap.find('.game-teams-list').length || !listWrap.height()) {
          const index = listWrap.closest('li').index()

          const row = $(`.user-list tbody tr.user-list-heading:eq(${index})`)

          new Promise(resolve => {
            let rowsCount = 0
            row.nextAll('tr').each(function () {
              if ($(this).hasClass('user-list-heading')) {
                resolve(rowsCount)
              } else {
                rowsCount++
              }
            })
            resolve(rowsCount)
          }).then(rowsCount => {
            const colHeight = 1 + row.next('tr').height()

            listWrap.css({height: (colHeight * rowsCount) + 'px'})
          })
        }
      }))
      // Figure out user score points
      .then((totalGroupPoints = {}, rowIndex = {group: 0, pos: 0}) => $('.user-list tbody tr.user-list-heading').each(function () {
        const row = $(this)
        $(this).nextAll('tr').each(function () {
          if ($(this).hasClass('user-list-heading')) {
            for (let userID in totalGroupPoints) {
              row.find(`td[data-uuid="${userID}"] em`).text(totalGroupPoints[userID])
            }
            // Increase group position
            rowIndex.group++
            // Reset positions number
            rowIndex.pos = 0
            // Reset group points value
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
      }))
  }
}
</script>
