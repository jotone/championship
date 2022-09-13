import Axios from 'axios';
import jQuery from 'jquery';

window.$ = jQuery;
window.$.axios = Axios

$(document).ready(() => {
  $('.login-form-menu li[data-show="login-form"]').click(function () {
    $('.login-form').toggleClass('active')
  })
})