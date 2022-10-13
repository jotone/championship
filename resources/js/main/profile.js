// import FroalaEditor from 'froala-editor'

$(document).ready(() => {
  /*let editor = null
  new Promise(resolve => {
    setTimeout(() => {
      if (typeof FroalaEditor !== 'undefined') {
        editor = new FroalaEditor($('.editor')[0], {
          htmlAllowedTags: ['.*'],
        })
        resolve({})
      }
    }, 50)
  })
    .then(() => {
      setTimeout(() => {
        if ($('.fr-second-toolbar').length) {
          $('.editor .fr-element.fr-view').css({'min-height': '200px'})
          $('.fr-second-toolbar #fr-logo').remove()
        }
      }, 50)
    })*/

  $('form').on('submit', function (e) {
    e.preventDefault()

    const formData = new FormData($(this)[0])

    // formData.append('info', editor.html.get())

    $.axios.post($(this).attr('action'), formData)
      .then(response => {
        if (200 === response.status) {
          for (let type in response.data.messages) {
            for (let i = 0, n = response.data.messages[type].length; i < n; i++) {
              $('.messages-wrap ul').append(
                `<li class="${type}">
                  <div class="close"><i class="fas fa-times-circle"></i></div>
                  <div class="message-text">${response.data.messages[type][i]}</div>
                </li>`)
            }
          }
        }
      })
      .catch(error => {
        if (!!error.response.data.errors) {
          for (let i = 0, n = error.response.data.errors.length; i < n; i++) {
            $('.messages-wrap ul').append(
              `<li class="error">
                  <div class="close"><i class="fas fa-times-circle"></i></div>
                  <div class="message-text">${error.response.data.errors[i]}</div>
                </li>`)
          }
        }
      })
  })
})