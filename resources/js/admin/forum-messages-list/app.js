import {Confirmation} from '../libs/confirmation';
import {Helpers} from '../libs/helpers';
import {Notifications} from '../libs/notifications';

$(document).ready(() => {
  $('.comment-list-wrap')
    .on('keyup', 'textarea[name="message"]', function () {
      $(this).attr('data-changed', 1)
    })
    // Update comment
    .on('click', 'a.update', function (e) {
      e.preventDefault()

      const form = $(this).closest('form')
      if (typeof form.find('textarea[name="message"]').attr('data-changed') !== 'undefined') {
        let formData = new FormData()
        formData.append('_method', 'patch')
        formData.append('message', form.find('textarea[name="message"]').val().trim())

        $.axios
          .post($(this).attr('href'), formData)
          .then(response => 200 === response.status && Notifications.push('Коментар успішно змінено.').show())
      }
    })
    // Restore comment content
    .on('click', 'a.restore', function (e) {
      e.preventDefault()

      let formData = new FormData()
      formData.append('_method', 'patch')

      $.axios
        .post($(this).attr('href'), formData)
        .then(response => 200 === response.status && window.location.reload())
    })
    // Pin comment
    .on('click', 'a.pin', function (e) {
      e.preventDefault()

      const pin = $(this).find('i')

      let formData = new FormData()
      formData.append('_method', 'patch')
      formData.append('pinned', pin.hasClass('far'))

      $.axios
        .post($(this).attr('href'), formData)
        .then(
          response => 200 === response.status
            && pin.removeClass('fas')
              .removeClass('far')
              .addClass(response.data.pinned ? 'fas' : 'far')
        )
    })
    // Remove comment
    .on('click', 'a.remove', function (e) {
      e.preventDefault()

      const confirm = new Confirmation(`Ви дійсно хочете видалити цей коментар?`).open()
      confirm.then(
        answer => answer && $.axios
          .delete($(this).attr('href'))
          .then(response => 204 === response.status && window.location.reload())
      )
    })

  !!window.location.hash && Helpers.goto($('li' + window.location.hash))
})