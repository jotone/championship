<template>
  <input name="_method" type="hidden" value="patch">
  <input
      class="score-input"
      type="number"
      min="0"
      :name="`score[${game.host_team}]`"
      :value="game.score[game.host_team] || 0"
      @keyup="gameSetScore"
      @mouseup="gameSetScore"
  >
  <span style="margin: 0 8px">:</span>
  <input
      class="score-input"
      type="number"
      min="0"
      :name="`score[${game.guest_team}]`"
      :value="game.score[game.guest_team] || 0"
      @keyup="gameSetScore"
      @mouseup="gameSetScore"
  >
</template>

<script>
import {debounce} from 'debounce';

export default {
  props: {
    game: null
  },
  methods: {
    /**
     * Change score for game
     * @param e
     */
    gameSetScore: debounce(function(e) {
      const _this = $(e.target)
      let formData = new FormData($(e.target).closest('form')[0])

      const id = _this.closest('tr').length
        ? parseInt(_this.closest('tr').attr('data-id'))
        : parseInt(_this.closest('li').attr('data-id'))

      $.axios
        .post(this.$parent.gameUpdateRoute(id), formData)
        .then(response => 200 === response.status && this.$parent.updateGames(response.data))
    }, 500),
  }
}
</script>