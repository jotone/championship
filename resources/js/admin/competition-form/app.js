import { createApp } from 'vue';
import Groups from './Groups.vue';
import PlayOffs from './PlayOffs.vue';
import '@eastdesire/jscolor';
import AirDatepicker from 'air-datepicker';
import enLocale from 'air-datepicker/locale/en';
import {SearchSelect} from '../libs/search-select';
import Sortable from 'sortablejs';
import ro from "air-datepicker/locale/ro";

const groupTable = $('#groupsTable')
const playOffTable = $('#playOffTable')
groupTable.length && createApp(Groups).mount(`#${groupTable.attr('id')}`);
playOffTable.length && createApp(PlayOffs).mount(`#${playOffTable.attr('id')}`);

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

  $('#add-team form select[name="entity"]').on('change', function () {
    const url = $(this).find('option:selected').data('url')
    $(this).closest('form').find('.team-selector').attr('data-url', url)
  })


  new Sortable(groupTable.length ? groupTable[0] : playOffTable.find('.stages-wrap')[0], {
    animation: 150,
    handle: '.move-group',
    group: 'shared',
    onSort: function () {
      let formData = new FormData()
      formData.append('_method', 'patch')

      let routes = groupTable.length ? groupTable.data('routes') : playOffTable.data('routes')

      if (groupTable.length) {
        $(this.el).children('div').each(function () {
          formData.append('positions[]', $(this).find('.competition-table[data-id]').attr('data-id'))
        })
      } else {
        $(this.el).children('.stage-item-wrap[data-id]').each(function () {
          formData.append('stages[]', $(this).attr('data-id'))
        })
      }

      console.log(routes)

      $.axios.post(routes.group.upgrade, formData)
    }
  })
})