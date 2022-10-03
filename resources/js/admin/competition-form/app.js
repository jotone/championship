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

      let routes = {}

      if (groupTable.length) {
        routes = $(this.el).data('routes');
        $(this.el).children('div').each(function () {
          formData.append('positions[]', $(this).find('.competition-table[data-id]').attr('data-id'))
        })
      } else {
        routes = $(this.el).closest('#playOffTable').data('routes')
        $(this.el).children('div').each(function () {
          if ($(this).hasClass('stage-item-wrap')) {
            formData.append('stages[]', $(this).find('.group-caption-wrap[data-id]').attr('data-id'))
          }
        })
      }
      console.log(routes)
      $.axios.post(routes.group.upgrade, formData)
    }
  })
})