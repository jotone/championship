import {initCKE} from "./libs/common"

const commentForm = $('#comment-form')
const saveUrl = commentForm.attr('action')

const showCommentForm = (_this, parent) => {
  // Remove method field
  commentForm.find('input[name="_method"]').remove()
  // Reset form action
  commentForm.attr('action', saveUrl)

  if (_this.hasClass('active')) {
    _this.removeClass('active')
    commentForm.hide(150)
  } else {
    !parent.find('#comment-form').length && commentForm.appendTo(parent)

    $('.comment-list-wrap button[name="showCommentForm"]').removeClass('active')
    $('.comment-list-wrap a.answer-action').removeClass('active')

    _this.addClass('active')
    commentForm.show(150)
  }
}

const commentTemplate = data => `
<li data-post="${data.id}" class="comment-author">
  <div class="comment-text-wrap">
    <div class="comment-text">${data.message}</div>
    <div class="comment-etc-wrap">
      <span class="comment-misc" title="Автор коменатря">Написав: ${data.author}</span>
      <span class="comment-misc" title="Дата створення коментаря">${data.created}</span>        
      <a class="answer-action" href="#" title="Написати відповідь до цього коментаря">Відповісти</a>
      <a class="comment-link" href="${data.routes.show}" title="Редагувати коментар"><i class="fas fa-edit"></i></a>
    </div>
    <div class="comment-form-wrap"></div>
  </div>
  <ul></ul>
</li>`


$(document).ready(() => {
  initCKE($('textarea[name="message"]'))

  $('.comment-list-wrap')
    // Click "comment" button
    .on('click', 'button[name="showCommentForm"]', function () {
      showCommentForm($(this), $(this).closest('.add-comment-wrap'))
    })
    // Click "answer" button
    .on('click', 'a.answer-action', function (e) {
      e.preventDefault()
      showCommentForm($(this), $(this).closest('.comment-text-wrap').find('.comment-form-wrap'))
    })
    // Click edit comment
    .on('click', 'a.comment-link', function (e) {
      e.preventDefault()

      const url = $(this).attr('href')

      if (typeof url !== 'undefined') {
        $.axios.get(url).then(response => {
          if (200 === response.status) {
            CKEDITOR.instances.message.setData(response.data.message);
            commentForm.appendTo($(this).closest('.comment-text-wrap').find('.comment-form-wrap'))
            commentForm.show(150)

            commentForm.append('<input name="_method" type="hidden" value="patch">')
            commentForm.attr('action', response.data.routes.update)
          }
        })
      }
    })
    // Remove comment
    .on('click', 'a.remove-comment-link', function (e) {
      e.preventDefault()

      const res = confirm('Ви дійсно хочете видалити цей коментар?')

      if (res) {
        const _this = $(this)
        const url = $(this).attr('href')

        if (typeof url !== 'undefined') {
          $.axios
            .delete(url)
            .then(response => 204 === response.status && _this.closest('.comment-text-wrap').parent('li').remove())
        }
      }
    })

  commentForm.on('submit', function (e) {
    e.preventDefault()

    const topic = window.location.pathname
    let formData = new FormData($(this)[0])

    formData.append('message', CKEDITOR.instances.message.getData())
    formData.append('parent', $(this).closest('li').attr('data-post') || 0)
    formData.append('topic', topic.substring(topic.lastIndexOf('/') + 1))

    const url = commentForm.find('input[name="_method"]').length > 0 ? $(this).attr('action') : saveUrl
    $.axios.post(url, formData).then(response => {
      if (201 === response.status) {
        const parent = $(`.comment-list-wrap li[data-post="${response.data.parent}"]`)
        const container = parent.length ? parent.children('ul') : $('.comment-list-wrap > ul')
        container.append(commentTemplate(response.data))
        CKEDITOR.instances.message.setData('')
      } else if (200 === response.status) {
        const parent = $(`.comment-list-wrap li[data-post="${response.data.id}"]`)
        parent.find('.comment-text').html(
          `${response.data.message}
          <em
           title="Редаговано"
           class="${response.data.author !== response.data.author ? 'comment-reason' : 'comment-edited' }"
          >(ред.)</em>`
        )
      }
      commentForm.hide(150)
    })
  })
})