import Axios from 'axios';
import jQuery from 'jquery';

window.$ = jQuery;
window.$.axios = Axios

$(document).ready(() => {
  $('.login-form-menu li[data-show="login-form"]').click(function () {
    $('.login-form').toggleClass('active')
  })

  // Image uploader
  $('.content-wrap .image-upload-wrap')
    .on('click', 'button[name="uploadImage"]', e => $(e.target).prev('input[type="file"]').trigger('click'))
    .on('change', 'input[type="file"]', function () {
      const reader = new FileReader()
      reader.onload = e => {
        $(this).closest('.image-upload-wrap')
          .find('.image-upload-preview img')
          .attr('src', e.target.result)
      }

      reader.readAsDataURL($(this).prop('files')[0])
    })

  // Remove notification message
  $('.messages-wrap').on('click', 'li .close', function () {
    $(this).closest('li').hide(350, function () {
      $(this).remove()
    })
  })

  // Hide login form on miss
  $(document).on('click', function (e) {
    const _this = $(e.target);
    if ($('.login-form').hasClass('active') && !_this.closest('.login-form').length && !_this.closest('.login-form-menu').length) {
      $('.login-form').removeClass('active')
    }
  })
})