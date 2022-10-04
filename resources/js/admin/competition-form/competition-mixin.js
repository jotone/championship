import moment from 'moment';
import {debounce} from "debounce";

export const CompetitionMixin = {
  methods: {
    /**
     * Convert a date value to the proper view
     * @param val
     * @returns {string}
     */
    formatDate: val => moment(new Date(val)).format('D[/]MMM[/]YYYY HH[:]mm'),
    /**
     * Game change accept value
     * @param e
     */
    gameAccept(e) {
      const _this = $(e.target)
      const id = _this.closest('tr').length
        ? parseInt(_this.closest('tr').data('id'))
        : parseInt(_this.closest('li').data('id'));

      let formData = new FormData()
      formData.append('_method', 'patch')
      formData.append('accept', _this.prop('checked') ? 1 : 0)

      $.axios
        .post(this.gameUpdateRoute(id), formData)
        .then(response => 200 === response.status && this.updateGames(response.data))
    },
    /**
     *
     * @param id
     * @returns {string}
     */
    gameRemoveRoute(id) {
      return window.Helpers.buildUrl(this.routes.game.destroy, id, 1)
    },
    /**
     *
     * @param id
     * @returns {string}
     */
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
     * @param id
     * @returns {string}
     */
    groupUpdateRoute(id) {
      return window.Helpers.buildUrl(this.routes.group.update, id, 1)
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
    }
  }
}