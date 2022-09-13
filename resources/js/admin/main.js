import Axios from 'axios';
import jQuery from 'jquery';

import { debounce } from 'debounce';
import { Helpers } from './libs/helpers';
import { Notifications } from './libs/notifications';

window.$ = jQuery;
window.$.axios = Axios
window.Helpers = Helpers
window.$.axios.interceptors.request.use(config => {
  $('.overlay').css({display: 'flex'})
  $('.overlay .preload').show()

  const $jwt = $('meta[name="jwt-token"]')
  if ($jwt.length) {
    config.headers.Authorization = `Bearer ${$jwt.attr('content')}`;
  }

  return config;
});


$(document).ready(() => {
  // Show current notifications
  Notifications.show()

  // Click side-menu element
  $('.side-menu').on('click', 'li', function () {
    if ($(this).children('ul').length) {
      $(this).toggleClass('active')
    }
  })
  // Highlight active side-menu item
  let url = window.location.href
  let urlPath = url.split('/')
  if (urlPath[urlPath.length - 1] === 'create') {
    url = urlPath.slice(0, -1).join('/')
  } else if (urlPath[urlPath.length - 1] === 'edit') {
    url = urlPath.slice(0, -2).join('/')
  }
  $(`.side-menu a[href="${url}"]`).parents('li').addClass('active')

  // Convert text to slug
  $('[data-slug]').each(function () {
    // Get converter options
    let [tag, name] = $(this).attr('data-slug').split('.')
    // Target element
    const target = $(`${tag}[name="${name}"]`)
    // Host key print event
    $(this).on('keyup', debounce(function () {
      if (typeof target.attr('data-changed') === 'undefined') {
        // Set ascii value to target element
        target.val(Helpers.toAscii($(this).val()).replace(/-+/g, '-'))
      }
    }, 250))
    // If the slug data was entered manually
    target.on('keyup', function () {$(this).attr('data-changed', '1')})
  })

  // Image uploader
  $('.image-upload-wrap')
    // Upload image trigger
    .on('click', 'button[name="upload"]', function (e) {
      e.preventDefault()
      $(this).closest('.buttons-wrap').find('input[type="file"]').trigger('click')
    })
    // Clear image
    .on('click', 'button[name="clear"]', function (e) {
      e.preventDefault()
      $(this).closest('.buttons-wrap').find('input[type="file"]').val('')
      $(this).closest('.image-upload-wrap').find('.image-upload-preview').empty()
    })
    // Image upload event
    .on('change', 'input[type="file"]', function () {
      const file = $(this).prop('files')[0]
      const wrap = $(this).closest('.image-upload-wrap').find('.image-upload-preview')
      const reader = new FileReader()

      reader.onload = e => {
        !wrap.find('img').length && wrap.append('<img src="" alt="No image&hellip;">')
        wrap.find('img').attr('src', e.target.result)
      }

      reader.readAsDataURL(file)
    })

  // Form submit
  $('form[data-xhr]').on('submit', function (e) {
    e.preventDefault()
    // Form data
    const formData = new FormData(this)
    // Form method
    const method = typeof $(this).attr('method') !== 'undefined' ? $(this).attr('method') : 'get';
    // Send request
    $.axios[method.toLowerCase()]($(this).attr('action'), formData)
      .then(response => {
        if (typeof $(this).attr('data-msg') !== 'undefined') {
          const [entity, field] = $(this).attr('data-msg').split('.')

          const message = `${entity} "${field}" was successfully ${201 === response.status ? 'created' : 'modified'}.`

          Notifications.push(message)

          if (201 === response.status) {
            window.location.reload()
          } else {
            Notifications.show()
          }
        }
      })
      .catch(({response}) => {
        if(400 <= response.status && 500 >= response.status) {
          if (response.data.hasOwnProperty('message')) {
            Notifications.push(response.data.message, 'error')
          }
          if(response.data.hasOwnProperty('errors')) {
            for (let field in response.data.errors) {
              const messages = response.data.errors[field]
              for (let i = 0, n = messages.length; i < n; i++) {
                Notifications.push(messages[i], 'error')
              }
            }
            Notifications.show()
          }
        }
      })
      .finally(() => $('.overlay, .overlay .preload').hide())
  })
})