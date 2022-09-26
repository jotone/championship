import { createApp } from 'vue';
import Main from './Main.vue';

createApp(Main).mount('#groupsTable')

import "@eastdesire/jscolor";
import AirDatepicker from 'air-datepicker';
import enLocale from 'air-datepicker/locale/en';
import {SearchSelect} from '../libs/search-select';
import Sortable from 'sortablejs';

$(document).ready(() => {
  $('.datepicker').each(function () {
    new AirDatepicker(this, {
      locale: enLocale,
      firstDay: 1,
      dateFormat: 'd/MMM/yyyy',
      timeFormat: 'HH:mm',
    })
  })

  new SearchSelect($('.team-selector'), {
    field: 'ua',
    preventOverlay: true
  })

  $('#append-team form select[name="entity"]').on('change', function () {
    const url = $(this).find('option:selected').data('url')
    $(this).closest('form').find('.team-selector').attr('data-url', url)
  })

  new Sortable($('#groupsTable')[0], {
    animation: 150,
    handle: '.move-group',
    group: 'shared',
    onSort: function () {
      const routes = $(this.el).data('routes');

      let formData = new FormData()
      formData.append('_method', 'patch')
      $(this.el).children('div').each(function () {
        formData.append('positions[]', $(this).find('.competition-table[data-id]').attr('data-id'))
      })

      $.axios.post(routes.group.upgrade, formData)
    }
  })
})