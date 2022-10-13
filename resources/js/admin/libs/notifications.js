export class Notifications {
  /**
   * Add message to session
   *
   * @param msg
   * @param type
   * @returns {Notifications}
   */
  static push(msg = '', type = 'success') {
    // Get messages list
    let messages = Object.keys(localStorage).indexOf('notificationMessages') >= 0
      ? JSON.parse(localStorage.getItem('notificationMessages'))
      : {}
    // Check messages of type exist
    if (!messages.hasOwnProperty(type)) {
      messages[type] = []
    }
    // Add message of type
    messages[type].push(msg)
    // Add messages to session
    localStorage.setItem('notificationMessages', JSON.stringify(messages))
    // Chain method
    return this
  }

  /**
   * View messages
   */
  static show() {
    // Get messages list
    let messages = Object.keys(localStorage).indexOf('notificationMessages') >= 0
      ? JSON.parse(localStorage.getItem('notificationMessages'))
      : {}
    // Check messages exist
    if (!$.isEmptyObject(messages)) {
      for (let type in messages) {
        for (let i = 0, n = messages[type].length; i < n; i++) {
          // View message
          $('.notifications-wrap').append(this.template(messages[type][i], type))
        }
      }
      $('.notifications-wrap li').each(function () {
        $(this).show(500).delay(2500).fadeOut(500, function () {
          $(this).off('click').remove()
        })
      })
      // Clear messages
      localStorage.removeItem('notificationMessages')
    }
    // Chain method
    return this
  }
  /**
   * Message template
   *
   * @param msg
   * @param type
   * @returns {string}
   */
  static template(msg = '', type = 'success') {
    return `
    <li class="${type}">
      <div class="text-wrap">${msg}</div>
      <div class="close"><i class="fas fa-times-circle"></i></div>
    </li>`
  }
}