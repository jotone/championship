import {createApp} from 'vue';
import {Confirmation} from '../libs/confirmation';
import Main from './Main.vue';
import Sortable from 'sortablejs';

$(document).ready(() => {
  window.routes = $('.translations-form').data('routes')

  createApp(Main).mount('.translation-list')

  $('form[name="language-settings"]')
    // Change admin language
    .on('change', 'select[name="admin_lang"]', e => {
      $(e.target).closest('form').trigger('submit')
      setTimeout(() => window.location.reload(), 250)
    })
    // Change site language
    .on('change', 'select[name="main_lang"]', e => $(e.target).closest('form').trigger('submit'))


  $('form[name="language-store"]')
    // Add language to site
    .on('change', 'select[name="lang"]', function () {
      const lang = $(this).find('option:selected').text().trim()
      const confirm = new Confirmation(`Ви дійсно хочете встановити мову "${lang}"?`).open()
      confirm.then(answer => answer && $(this).closest('form').trigger('submit'))
    })
    // Remove language
    .on('click', '.language-list-remove', function () {
      const parent = $(this).closest('li')
      const lang = parent.find('.language-list-name span').text().trim()
      const confirm = new Confirmation(`Ви дійсно хочете видалити мову "${lang}"?`).open()
      confirm.then(answer => answer && $.axios.delete(parent.find('input[name="remove"]').val()).then(() => window.location.reload()))
    })

  // Sortable List
  const sortableList = $('.sortable-list-wrap')
  if (sortableList.length) {
    sortableList.each(function () {
      new Sortable(this, {
        animation: 150,
        handle: '.move-handle',
        group: 'shared',
        onSort: () => $(this).closest('form').trigger('submit')
      })
    })

    sortableList
      // Disable language
      .on('click', '.language-list-remove', function () {
        const lang = $(this).closest('li').find('.language-list-name span').text().trim()
        const confirm = new Confirmation(`Ви дійсно хочете відключити мову "${lang}"?`).open()
        confirm.then(answer => {
          if (answer) {
            const form = $(this).closest('form')
            const parent = $(this).closest('li')

            parent.addClass('disabled')
            parent.find('input[name="lang_list[]"]').attr('disabled', '')

            $(this).closest('.language-list-remove').remove()

            parent.append('<div class="language-list-add"><i class="fas fa-plus edit"></i></div>')
            form.trigger('submit')
          }
        })
      })
      // Enable language
      .on('click', '.language-list-add', function () {
        const form = $(this).closest('form')
        const parent = $(this).closest('li')
        parent.removeClass('disabled')
        parent.find('input[name="lang_list[]"]').removeAttr('disabled', '')

        $(this).closest('.language-list-add').remove()

        parent.append('<div class="language-list-remove"><i class="fas fa-times remove"></i></div>')
        form.trigger('submit')
      })
  }
})