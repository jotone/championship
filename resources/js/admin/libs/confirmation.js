export class Confirmation {
  constructor(text, options = null) {
    this.text = text || 'Confirm your action.'
    this.options = {
      ok: 'OK',
      cancel: 'Cancel'
    }
    if (options) {
      this.options = {...this.options, ...options}
    }
    this.overlay = $('.overlay')
  }

  close() {
    this.overlay.find('.confirmation-popup').fadeOut(250, function () {
      $(this).closest('.overlay').hide()
      $(this).closest('.confirmation-popup').remove()
    })
  }

  open() {
    return new Promise(resolve => {
      this.overlay.find('.popup-wrap, .preload').hide()
      this.overlay.find('.confirmation-popup').remove()
      this.overlay.append(this.template())
      this.overlay.css({display: 'flex'})

      this.overlay.find('.confirmation-popup')
        .on('click', 'button[name="accept"]', () => {
          this.close();
          resolve(!0)
        })
        .on('click', 'button[name="cancel"], .close', () => {
          this.close();
          resolve(!1)
        })
    })
  }

  template() {
    return `
    <div class="confirmation-popup">
      <div class="close">
        <i class="fas fa-times-circle"></i>
      </div>
      <div class="question-text">${this.text}</div>
      <div class="buttons-wrap">
        <button name="accept" type="button" class="btn success">${this.options.ok}</button>
        <button name="cancel" type="button" class="btn cancel">${this.options.cancel}</button>
      </div>
    </div>`
  }
}