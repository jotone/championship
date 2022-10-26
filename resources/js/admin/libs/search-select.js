import {debounce} from "debounce";

export class SearchSelect {
  options = {
    items: [],
    name: null,
    url: null,
    field: 'title',
    preventOverlay: false,
    placeholder: 'Введіть принаймні 2 символи, щоб почати пошук&hellip;'
  }

  constructor(wrap, options = {}) {
    this.options = {...this.options, ...options}

    if (!wrap.length) {
      throw new Error('Object not found')
    }

    if ((null === this.options.name || typeof this.options.name === 'undefined') && typeof wrap.attr('data-name') === 'undefined') {
      throw new RangeError('Selector name does not set')
    }

    if (typeof wrap.attr('data-name') !== 'undefined') {
      this.options.name = wrap.attr('data-name')
    }
    if (typeof wrap.attr('data-url') !== 'undefined') {
      this.options.url = wrap.attr('data-url')
    }

    wrap.attr('data-url', this.options.url)

    new Promise(resolve => {
      wrap.empty().append(this.templates.list())
      resolve({})
    }).then(() => {
      const list = wrap.find('.search-select-wrap ul')

      // Start input sought-for text
      wrap.on('keyup', 'input[name="searchSelect"]', debounce(e => {
        const _this = $(e.target)
        const value = _this.val().trim() + ''

        if (value.length > 2) {
          const url =  wrap.attr('data-url') + (this.options.url.indexOf('?') >= 0 ? '&' : '?') + 'search=' + value
          $.axios.get(url, {
            preventOverlay: this.options.preventOverlay
          })
            .then(response => 200 === response.status && list
              .show()
              .empty()
              .append(response.data.collection.reduce((sum, cur) => sum + this.templates.listItem(cur), ''))
            )
            .finally(() => {
              if (!this.options.preventOverlay) {
                $('.overlay, .overlay .preload').hide()
              }
            })
        }
      }, 205))
      // Switch item
      list.on('click', 'li', e => {
        const _this = $(e.target).closest('li')

        const id = _this.attr('data-id')
        const text = _this.text().trim()
        wrap.find(`input[name="${this.options.name}"]`).val(id)
        wrap.find(`input[name="searchSelect"]`).val(text)
        list.hide()
      })

      $(document).on('click', e => !$(e.target).closest('.search-select-wrap').length && list.hide())
    })
  }

  templates = {
    /**
     * List item template
     * @param cur
     * @returns {string}
     */
    listItem: cur => `<li ${cur.hasOwnProperty('id') ? `data-id="${cur.id}"` : ''}>${cur[this.options.field]}</li>`,
    /**
     * List template
     * @returns {string}
     */
    list: () => `<div class="search-select-wrap">
      <input name="${this.options.name}" type="hidden" autocomplete="off">
      <input
       autocomplete="off"
       name="searchSelect"
       class="search-select-input"
       placeholder="${this.options.placeholder}"
      >
      <ul>${this.options.items.reduce((sum, cur) => sum + this.templates.listItem(cur), '')}</ul>
    </div>`
  }
}