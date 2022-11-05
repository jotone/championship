const arrayIntersect = (a, b) => [...new Set(a)].filter(item => b.includes(item))

/**
 * Calculate group points
 * @param totalPoints
 * @returns {int}
 */
const groupPoints = totalPoints => {
  const pageContent = $('.page-content-wrap .content-table:first')
  $('.real-score-wrap .real-score:first tbody tr').each(function () {
    const host = $(this).find('td:eq(1)').text().trim();
    const guest = $(this).find('td:eq(2)').text().trim();

    if (host.length && guest.length) {
      const realScore = {
        host: parseInt(host),
        guest: parseInt(guest)
      }

      const index = $(this).index()

      const userRow = pageContent.find(`tbody tr:eq(${index})`)
      const userHost = userRow.find('td:eq(1)').text().trim()
      const guestHost = userRow.find('td:eq(2)').text().trim()

      if (userHost.length && guestHost.length) {
        const userScore = {
          host: parseInt(userHost),
          guest: parseInt(guestHost)
        }

        let points = 0

        if (realScore.host === userScore.host && realScore.guest === userScore.guest) {
          // If user guess Exact score
          points = 3;
        } else if (
          // If user guess winner
          (realScore.host > realScore.guest && userScore.host > userScore.guest)
          || (realScore.host < realScore.guest && userScore.host < userScore.guest)
          || (realScore.host === realScore.guest && userScore.host === userScore.guest)
        ) {
          points = 1;
        }

        totalPoints += points
        userRow.find('td:last').text(points)
      }
    }
  })
  pageContent.find('tfoot .total-points span').text(totalPoints);

  return totalPoints;
}

const hoverAction = (_this, type) => {
  _this = _this.closest('tr')
  const action = type ? 'addClass' : 'removeClass'
  const index = _this.index()
  _this[action]('hovered')

  $(`.real-score-wrap .real-score:first tbody tr:eq(${index})`)[action]('hovered')
}

$(document).ready(() => {
  $('.page-content-wrap .content-table:first tbody')
    .on('mouseover', 'tr', e => hoverAction($(e.target), !0))
    .on('mouseleave', 'tr', e => hoverAction($(e.target), !1))

  let totalPoints = groupPoints(0)

  let realTeams = {}, userTeams = {};
  $('.real-score-wrap .real-score[data-uuid]').each(function () {
    const id = $(this).attr('data-uuid')
    if (typeof realTeams[id] === 'undefined') {
      realTeams[id] = []
    }
    $(this).find('tbody td').each(function () {
      realTeams[id].push($(this).attr('data-uuid'))
    })

    const height = $(`.page-content-wrap .content-table:eq(${$(this).index()}) tfoot td`).height()
    $(this).find('tfoot td').css({height: height + 12 + 'px'});
  })

  $('.page-content-wrap .content-table[data-uuid]').each(function () {
    const id = $(this).attr('data-uuid')
    if (typeof userTeams[id] === 'undefined') {
      userTeams[id] = []
    }
    $(this).find('tbody td').each(function () {
      userTeams[id].push($(this).attr('data-uuid'))
    })
  })

  for (let groupID in userTeams) {
    const match = arrayIntersect(userTeams[groupID], realTeams[groupID])

    let bonusPoints = 0, points = match.length;
    // Highlight winner teams
    for( let i = 0; i < points; i++) {
      $(`.page-content-wrap .content-table[data-uuid="${groupID}"] td[data-uuid="${match[i]}"] span`).addClass('match')
    }
    if (match.length) {
      const wrap = $(`.page-content-wrap .content-table[data-uuid="${groupID}"]`)
      const groupType = wrap.find('tbody tr').length
      if (8 === groupType) {
        switch (match.length) {
          case 12: bonusPoints = 4;break;
          case 13: bonusPoints = 6;break;
          case 14: bonusPoints = 8;break;
          case 15: bonusPoints = 9;break;
          case 16: bonusPoints = 10;break;
        }
      } else if(4 === groupType) {
        switch (match.length) {
          case 6: bonusPoints += 4;break;
          case 7: bonusPoints += 6;break;
          case 8: bonusPoints += 8;break;
        }
      } else if (2 === groupType) {
        points *= 2
        switch (match.length) {
          case 3: bonusPoints += 6;break;
          case 4: bonusPoints += 8;break;
        }
      } else if(1 === groupType) {
        if (2 === wrap.find('tbody tr td').length) {
          points *= 2
          if (2 === match.length) {
            bonusPoints += 8;
          }
        } else {
          if (1 === match.length) {
            points = 8
          }
        }
      }
      totalPoints += match.length + bonusPoints
      wrap.find('tfoot td .commands').text(points)
      wrap.find('tfoot td .bonus').text(bonusPoints)
      wrap.find('.total-points span').text(points + bonusPoints)
    } else {
      $(`.page-content-wrap .content-table[data-uuid="${groupID}"] .total-points span`).text('0')
    }
  }
})