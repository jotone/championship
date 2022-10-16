/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/js/main/results.js ***!
  \**************************************/
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }
var arrayIntersect = function arrayIntersect(a, b) {
  return _toConsumableArray(new Set(a)).filter(function (item) {
    return b.includes(item);
  });
};

/**
 * Calculate group points
 * @param totalPoints
 * @returns {int}
 */
var groupPoints = function groupPoints(totalPoints) {
  var pageContent = $('.page-content-wrap .content-table:first');
  $('.real-score-wrap .real-score:first tbody tr').each(function () {
    var host = $(this).find('td:eq(1)').text().trim();
    var guest = $(this).find('td:eq(2)').text().trim();
    if (host.length && guest.length) {
      var realScore = {
        host: parseInt(host),
        guest: parseInt(guest)
      };
      var index = $(this).index();
      var userRow = pageContent.find("tbody tr:eq(".concat(index, ")"));
      var userHost = userRow.find('td:eq(1)').text().trim();
      var guestHost = userRow.find('td:eq(2)').text().trim();
      if (userHost.length && guestHost.length) {
        var userScore = {
          host: parseInt(userHost),
          guest: parseInt(guestHost)
        };
        var points = 0;
        if (realScore.host === userScore.host && realScore.guest === userScore.guest) {
          // If user guess Exact score
          points = 3;
        } else if (
        // If user guess winner
        realScore.host > realScore.guest && userScore.host > userScore.guest || realScore.host < realScore.guest && userScore.host < userScore.guest || realScore.host === realScore.guest && userScore.host === userScore.guest) {
          points = 1;
        }
        totalPoints += points;
        userRow.find('td:last').text(points);
      }
    }
  });
  pageContent.find('tfoot .total-points span').text(totalPoints);
  return totalPoints;
};
var hoverAction = function hoverAction(_this, type) {
  _this = _this.closest('tr');
  var action = type ? 'addClass' : 'removeClass';
  var index = _this.index();
  _this[action]('hovered');
  $(".real-score-wrap .real-score:first tbody tr:eq(".concat(index, ")"))[action]('hovered');
};
$(document).ready(function () {
  $('.page-content-wrap .content-table:first tbody').on('mouseover', 'tr', function (e) {
    return hoverAction($(e.target), !0);
  }).on('mouseleave', 'tr', function (e) {
    return hoverAction($(e.target), !1);
  });
  var totalPoints = groupPoints(0);
  var realTeams = {},
    userTeams = {};
  $('.real-score-wrap .real-score[data-uuid]').each(function () {
    var id = $(this).attr('data-uuid');
    if (typeof realTeams[id] === 'undefined') {
      realTeams[id] = [];
    }
    $(this).find('tbody td').each(function () {
      realTeams[id].push($(this).attr('data-uuid'));
    });
    var height = $(".page-content-wrap .content-table:eq(".concat($(this).index(), ") tfoot td")).height();
    $(this).find('tfoot td').css({
      height: height + 12 + 'px'
    });
  });
  $('.page-content-wrap .content-table[data-uuid]').each(function () {
    var id = $(this).attr('data-uuid');
    if (typeof userTeams[id] === 'undefined') {
      userTeams[id] = [];
    }
    $(this).find('tbody td').each(function () {
      userTeams[id].push($(this).attr('data-uuid'));
    });
  });
  for (var groupID in userTeams) {
    var match = arrayIntersect(userTeams[groupID], realTeams[groupID]);
    var bonusPoints = 0,
      points = match.length;
    // Highlight winner teams
    for (var i = 0; i < points; i++) {
      $(".page-content-wrap .content-table[data-uuid=\"".concat(groupID, "\"] td[data-uuid=\"").concat(match[i], "\"] span")).addClass('match');
    }
    if (match.length) {
      var wrap = $(".page-content-wrap .content-table[data-uuid=\"".concat(groupID, "\"]"));
      var groupType = wrap.find('tbody tr').length;
      if (8 === groupType) {
        switch (match.length) {
          case 12:
            bonusPoints = 4;
            break;
          case 13:
            bonusPoints = 6;
            break;
          case 14:
            bonusPoints = 8;
            break;
          case 15:
            bonusPoints = 9;
            break;
          case 16:
            bonusPoints = 10;
            break;
        }
      } else if (4 === groupType) {
        switch (match.length) {
          case 6:
            bonusPoints += 4;
            break;
          case 7:
            bonusPoints += 6;
            break;
          case 8:
            bonusPoints += 8;
            break;
        }
      } else if (2 === groupType) {
        switch (match.length) {
          case 3:
            bonusPoints += 6;
            break;
          case 4:
            bonusPoints += 8;
            break;
        }
      } else if (1 === groupType) {
        if (2 === wrap.find('tbody tr td').length) {
          points *= 2;
          if (2 === match.length) {
            bonusPoints += 8;
          }
        } else {
          if (1 === match.length) {
            points = 2;
            bonusPoints += 8;
          }
        }
      }
      totalPoints += match.length + bonusPoints;
      wrap.find('tfoot td .commands').text(points);
      wrap.find('tfoot td .bonus').text(bonusPoints);
      wrap.find('.total-points span').text(points + bonusPoints);
    } else {
      $(".page-content-wrap .content-table[data-uuid=\"".concat(groupID, "\"] .total-points span")).text('0');
    }
  }
});
/******/ })()
;