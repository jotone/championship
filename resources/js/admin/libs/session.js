/**
 * Session Storage manipulations
 *
 * @type {object}
 */
export const Session = {
  // Get a session value by the key
  get: key => JSON.parse(sessionStorage.getItem(key)),
  // Check the key exists in a session
  has: key => Object.keys(sessionStorage).indexOf(key) >= 0,
  // Get session keys
  keys: () => Object.keys(sessionStorage),
  // Remove a session value by the key
  remove: key => sessionStorage.removeItem(key),
  // Set the session key => value pair
  set: (key, value) => sessionStorage.setItem(key, JSON.stringify(value)),
  // Update the session data with the given values
  update: (key, values) => {
    let data = Session.get(key)
    if (null === data) data = {}
    for(let key in values) {
      data[key] = values[key]
    }
    Session.set(key, data)
  },
}