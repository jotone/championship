import "@eastdesire/jscolor";
import AirDatepicker from "air-datepicker";
import uaLocale from 'air-datepicker/locale/uk';
import {SearchSelect} from "../libs/search-select";

$(document).ready(() => {
  $('.datepicker').each(function () {
    new AirDatepicker(this, {
      locale: uaLocale
    })
  })

  new SearchSelect($('.selector'), {
    field: 'ua',
    preventOverlay: true
  })
})