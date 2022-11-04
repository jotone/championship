import '@eastdesire/jscolor';

$(document).ready(() => {
  // Button color picker behavior
  $('.button-color-picker-wrap').on('click', 'li', function () {
    $(this).closest('ul').find('.active').removeClass('active')
    $(this).addClass('active')
    const type = $(this).data('show')
    const parent = $(this).closest('.button-color-picker-wrap')

    parent.find('.button-color-picker').removeClass('active')
    parent.find(`.button-color-picker[data-type="${type}"]`).addClass('active')
  })

  $('.form-wrap').on('change', '.jscolor', function () {
    const name = $(this).attr('name')

    const related = $(`.form-wrap [data-related="${name}"]`)
    if (related.length) {
      const color = $(this).val()

      related.each(function () {
        const rule = $(this).data('rule')

        $(this).css({[rule]: color})
      })
    }
  })
})