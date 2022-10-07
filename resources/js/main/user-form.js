/**
 * Create selector options
 * @param teams
 * @param exclude
 * @param selected
 * @returns {string}
 */
const buildTeams = (teams, exclude = [], selected = null) => {
  // Default options
  let options = '<option disabled selected value>Виберіть команду</option>'
  // If there is selected option
  if (null !== selected) {
    options += `<option value="${selected.id}" selected>${selected.text}</option>`
  }
  // Append teams to options
  for (let id in teams) {
    if (exclude.indexOf(id) < 0) {
      options += `<option value="${id}">${teams[id]}</option>`
    }
  }

  return options
}

$(document).ready(() => {
  // Get team list
  const teams = JSON.parse(atob($('form[name="userForm"]').attr('data-teams')))
  // Fill selectors
  $('form[name="userForm"] .content-table select[name^="group"]').each(function () {
    $(this).html(buildTeams(teams))
  })

  // Selector change event
  $('form[name="userForm"] .content-table select[name^="group"]').on('change', function () {
    // Get used values
    let values = []
    $(this).closest('tbody').find('select').each(function () {
      const val = $(this).val()
      if (null !== val) {
        values.push(val)
      }
    })
    // Filter values and rebuild selector options
    $(this).closest('tbody').find('select').each(function () {
      const val = $(this).val()

      const selected = null !== val
        ? {
          id: val,
          text: $(this).find('option:selected').text().trim()
        }
        : null;

      $(this).html(buildTeams(teams, values, selected))
    })
  })
})