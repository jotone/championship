const changeTimerValues = (timer, timerWrap) => {
  if (timer.d > 0) {
    timerWrap.d.text(timer.d + ' д.')
  } else {
    timerWrap.d.hide()
  }

  timerWrap.h.text((timer.h + '').padStart(2, '0') + ' г.')
  timerWrap.m.text((timer.m + '').padStart(2, '0') + ' хв.')

  // timerWrap.s.text((timer.s + '').padStart(2, '0'))
}

$(document).ready(() => {
  $('.form-wrap .game-timer-wrap').each(function () {
    if (typeof $(this).attr('data-start') !== 'undefined') {
      const _this = $(this)
      const time = parseInt(_this.attr('data-start')) * 1000
      const current = +new Date()

      let timestamp = ((time - current) / 1000).toFixed(0)

      const timerWrap = {
        d: _this.find('.count-down-timer-wrap span[data-role="days"]'),
        h: _this.find('.count-down-timer-wrap span[data-role="hours"]'),
        m: _this.find('.count-down-timer-wrap span[data-role="minutes"]'),
        s: _this.find('.count-down-timer-wrap span[data-role="seconds"]'),
      }

      if (0 <= timestamp) {
        let timer = {
          d: +Math.floor(timestamp / 86400),
          h: +Math.floor(timestamp / 3600) % 60,
          m: +Math.floor(timestamp / 60) % 60,
          s: +Math.floor(timestamp % 60)
        }

        changeTimerValues(timer, timerWrap);
        _this.show();

        const interval = setInterval(() => {
          if (timer.s <= 0) {
            if (timer.m <= 0) {
              timer.h--;
              timer.m = 59;
              if (timer.h <= 0) {
                timer.d--;
                timer.h = 23
              }
            }
            timer.m--;
            timer.s = 59;
          } else {
            timer.s--
          }

          changeTimerValues(timer, timerWrap);

          (timer.h < 12 && timer.h > 6) && _this.addClass('green')
          (timer.h < 6 && timer.h > 1)  && _this.removeClass('green').addClass('yellow')
          (timer.h < 1) && _this.removeClass('green').removeClass('yellow').addClass('red')

          if (1 > timer.s && 0 === timer.h && 0 === timer.m && 0 === timer.d) {
            clearInterval(interval)
          }
        }, 1000)
      }
    }
  })
})