// Bookmark menu
if ($('ul.bookmark-wrap').length) {
  $('.form-wrap [data-show]').hide()
  const active = $('ul.bookmark-wrap li.active').attr('data-show')
  $(`.form-wrap [data-show="${active}"]`).show()

  $('ul.bookmark-wrap').on('click', 'li', function () {
    // Highlight bookmark item
    $(this).closest('ul').find('li.active').removeClass('active')
    $(this).addClass('active')
    // View bounded element
    $('.form-wrap [data-show]').hide()
    const item = $(this).attr('data-show')
    $(`.form-wrap [data-show="${item}"]`).show()
  })
}