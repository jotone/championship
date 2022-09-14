import "@eastdesire/jscolor";
import AirDatepicker from "air-datepicker";
import uaLocale from 'air-datepicker/locale/uk';

$(document).ready(() => {
  $('.datepicker').each(function () {
    new AirDatepicker(this, {
      locale: uaLocale
    })
  })
})