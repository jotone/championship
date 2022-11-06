import Sortable from 'sortablejs';
import { Confirmation } from './confirmation';

$(document).ready(() => {
  // Sortable List
  const sortableList = $('.sortable-list-wrap')
  if (sortableList.length) {
    sortableList.each(function () {
      new Sortable(this, {
        animation: 150,
        handle: '.move-handle',
        group: 'shared',
      })
    })

    sortableList.on('click', '.sortable-list-remove', function () {
      const confirm = new Confirmation(`Ви справді хочете видалити цей елемент?`).open()
      confirm.then(answer => {
        if (answer) {
          console.log($(this))
        }
      })
    })
  }
})
