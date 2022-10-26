import {initCKE} from "./libs/common"

const showCommentForm = (_this, parent) => {
  const commentForm = $('#comment-form')

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

  $('#comment-form').on('submit', function (e) {
    e.preventDefault()

    const url = $(this).attr('action')

    if (typeof url !== 'undefined') {
      const topic = window.location.pathname
      let formData = new FormData($(this)[0])

      formData.append('message', CKEDITOR.instances.message.getData())
      formData.append('parent', $(this).closest('li').attr('data-post') || 0)
      formData.append('topic', topic.substring(topic.lastIndexOf('/') + 1))

      $.axios.post(url, formData).then(response => {
        if (201 === response.status) {
          const parent = $(`.comment-list-wrap li[data-post="${response.data.parent}"]`)
          console.log(parent)
          const container = parent.length ? parent.children('ul') : $('.comment-list-wrap > ul')

          container.append(commentTemplate(response.data))
        }
        $('#comment-form').hide(150)
        CKEDITOR.instances.message.setData('')
      })
    }
  })
})