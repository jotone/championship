$(document).ready(() => {
  CKEDITOR.replace('info', {
    removePlugins: 'sourcearea',
    // Define the toolbar groups as it is a more accessible solution.
    toolbarGroups: [
      {
        "name": "basicstyles",
        "groups": ["basicstyles"]
      },
      {
        "name": "paragraph",
        "groups": ["list", "blocks"]
      },
      {
        "name": "document",
        "groups": ["mode"]
      },
      {
        "name": "styles",
        "groups": ["styles"]
      },
      {
        "name": "about",
        "groups": ["about"]
      }
    ],
    // Remove the redundant buttons from toolbar groups defined above.
    removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord'
  })

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