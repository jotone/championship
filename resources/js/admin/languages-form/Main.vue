<template>
  <ul>
    <li v-for="(item, i) in items">
      <label class="inline-caption col-100">
        <span class="left">{{ i }}:</span>

        <input
          v-if="typeof item === 'string'"
          class="form-input"
          :name="`lang[${i}]`"
          :value="item"
          @keyup="changeTranslation"
        >
      </label>

      <ul v-if="typeof item !== 'string'">
        <li v-for="(inner, j) in item">
          <label class="inline-caption col-100">
            <span class="left">{{ j }}:</span>

            <input
              class="form-input"
              :name="`lang[${i}][${j}]`"
              :value="inner"
              @keyup="changeTranslation"
            >
          </label>
        </li>
      </ul>
    </li>
  </ul>
</template>

<script>

import {debounce} from 'debounce';

export default {
  data() {
    return {
      items: {},
      lang: 'en',
    }
  },
  methods: {
    changeTranslation: debounce(function (e) {
      const lang = $('.translations-form > ul li.active').attr('data-show')

      const _this = $(e.target)

      let formData = new FormData();
      formData.append('_method', 'PATCH')
      formData.append('file', $('.file-list-wrap li.active a').attr('data-file'))
      formData.append(_this.attr('name'), _this.val())

      const url = window.Helpers.buildUrl(_this.closest('form').attr('action'), lang, 1)

      $.axios.post(url, formData).then(response => {
        console.log(response)
      })
    }, 250),
    /**
     * Get translation request
     * @param url
     * @param callback
     */
    getTranslations(url, callback = null) {
      $.axios.get(url)
        .then(response => {
          if (200 === response.status) {
            this.items = response.data

            null !== callback && callback()
          }
        })
    },
    /**
     * Get translations route
     * @param id
     * @returns {string}
     */
    showRoute: id => window.Helpers.buildUrl(window.routes.show, id, 1),
  },
  mounted() {
    const file = $('.file-list-wrap .active a').attr('data-file')

    this.getTranslations(this.showRoute(this.lang) + '?file=' + file)

    // Switch language
    $('.translations-form > ul li').on('click', 'a', e => {
      e.preventDefault()

      const url = $(e.target).attr('href')
      const file = $('.file-list-wrap .active a').attr('data-file')

      this.getTranslations(url + '?file=' + file, function () {
        const _this = $(e.target).closest('li')
        _this.closest('ul').find('.active').removeClass('active')
        _this.addClass('active')
        const type = _this.data('show')
        const parent = _this.closest('.button-color-picker-wrap')

        parent.find('.button-color-picker').removeClass('active')
        parent.find(`.button-color-picker[data-type="${type}"]`).addClass('active')
      })
    })
    // Switch file
    $('.file-list-wrap li').on('click', 'a', e => {
      e.preventDefault()

      const lang = $('.translations-form li.active').attr('data-show')
      const _this = $(e.target).closest('li')

      const url = this.showRoute(lang) + '?file=' + _this.find('a').attr('data-file')

      this.getTranslations(url, function () {
        _this.closest('ul').find('.active').removeClass('active')
        _this.addClass('active')
      })
    })
  }
}
</script>