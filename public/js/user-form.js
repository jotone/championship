/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************!*\
  !*** ./resources/js/main/user-form.js ***!
  \****************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/**
 * Create selector options
 * @param teams
 * @param exclude
 * @param selected
 * @returns {string}
 */
var buildTeams = function buildTeams(teams) {
  var exclude = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
  var selected = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  // Default options
  var options = '<option disabled selected value>Оберіть команду</option>';
  // If there is selected option
  if (null !== selected && _typeof(selected) === 'object') {
    options += "<option value=\"".concat(selected.id, "\" selected>").concat(selected.text, "</option>");
  }
  // Append teams to options
  for (var id in teams) {
    if (exclude.indexOf(id) < 0) {
      options += "<option value=\"".concat(id, "\">").concat(teams[id], "</option>");
    }
  }
  return options;
};
$(document).ready(function () {
  // Get team list
  var teams = JSON.parse(atob($('form[name="userForm"]').attr('data-teams')));

  // Fill selectors
  $('form[name="userForm"] .content-table select[name^="group"]').each(function () {
    var _this = this;
    $(this).html(buildTeams(teams));
    var selected = $(this).attr('data-selected');
    if (typeof selected !== 'undefined') {
      setTimeout(function () {
        return $(_this).find("option[value=\"".concat(selected, "\"]")).attr('selected', 'selected');
      }, 50);
    }
  });

  // Selector change event
  $('form[name="userForm"] .content-table select[name^="group"]').on('change', function () {
    // Get used values
    var values = [];
    $(this).closest('tbody').find('select').each(function () {
      var val = $(this).val();
      if (null !== val) {
        values.push(val);
      }
    });
    // Filter values and rebuild selector options
    $(this).closest('tbody').find('select').each(function () {
      var val = $(this).val();
      var selected = null !== val ? {
        id: val,
        text: $(this).find('option:selected').text().trim()
      } : null;
      $(this).html(buildTeams(teams, values, selected));
    });
  });
});
/******/ })()
;