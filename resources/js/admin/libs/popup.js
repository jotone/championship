export class Popup {
  wrap = null

  constructor(wrap) {
    if (!wrap.length) {
      throw new Error('Object not found')
    }

    this.wrap = typeof wrap === 'string' ? $(wrap) : wrap

    this.wrap.on('click', '.popup-close', () => this.close())
  }

  open() {
    $('.overlay').fadeIn(100)
    $('.overlay .preload, .overlay .popup-wrap').hide()

    this.wrap.fadeIn(250)
  }

  close() {
    this.wrap.fadeOut(125, () => $('.overlay').fadeOut(50))
  }
}