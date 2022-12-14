import Axios from 'axios';

import { Helpers } from './libs/helpers';

window.$.axios = Axios
window.Helpers = Helpers
window.$.axios.interceptors.request.use(config => {
  if (config.hasOwnProperty('preventOverlay') && !config.preventOverlay) {
    $('.overlay').css({display: 'flex'})
    $('.overlay .preload').show()
  }

  const $jwt = $('meta[name="jwt-token"]')
  if ($jwt.length) {
    config.headers.Authorization = `Bearer ${$jwt.attr('content')}`;
  }

  return config;
});