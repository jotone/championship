import FroalaEditor from 'froala-editor'

$(document).ready(() => {
  new Promise(resolve => {
    setTimeout(() => {
      if (typeof FroalaEditor !== 'undefined') {
        new FroalaEditor($('.editor')[0])
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
    })
})