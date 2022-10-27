import moment from 'moment/moment';
import { Confirmation } from '../libs/confirmation';
import { Notifications } from '../libs/notifications';
import { Session } from '../libs/session';

export const ContentTableMixin = {
  methods: {
    formatDate: val => moment(new Date(val)).format('D[/]MMM[/]YYYY HH[:]mm'),
    editRoute(id) {
      return window.Helpers.buildUrl(this.routes.edit, id, 2)
    },
    removeRoute(id) {
      return window.Helpers.buildUrl(this.routes.destroy, id, 1)
    },
    removeEvent(e) {
      const el = $(e.target).closest('a')

      const [entity, index] = this.entity.split('.')
      const confirm = new Confirmation(`Ви дійсно хочете видалити ${entity} "${el.closest('tr').find(`td:eq(${index})`).text()}"?`).open()

      confirm.then(
        answer => answer && $.axios
          .delete(el.attr('href'))
          .then(response => 204 === response.status && this.getList())
          .finally(() => $('.overlay, .overlay .preload').hide())
      )
    },
    getList() {
      // Set request URL
      const requestUrl = Session.has(this.module)
        ? window.Helpers.setRequestOrderParams(this.routes.list, Session.get(this.module))
        : this.routes.list

      // Get list
      $.axios
        .get(requestUrl)
        .then(response => {
          // Set entities
          this.models = response.data.collection
          // Set pagination options
          this.pagination = {
            current: parseInt(response.data.page),
            total: Math.ceil(parseInt(response.data.total) / parseInt(response.data.take)),
          }
        })
        .catch(({response}) => {
          if(400 <= response.status && 500 >= response.status) {
            if (response.data.hasOwnProperty('message')) {
              Notifications.push(response.data.message, 'error')
            }
            if(response.data.hasOwnProperty('errors')) {
              for (let field in response.data.errors) {
                const messages = response.data.errors[field]
                for (let i = 0, n = messages.length; i < n; i++) {
                  Notifications.push(messages[i], 'error')
                }
              }
              Notifications.show()
            }
          }
        })
        .finally(() => $('.overlay, .overlay .preload').hide())
    },
  },
  beforeMount() {
    this.routes = $('#contentTable').data('routes')
  },
  mounted() {
    // Elements per page value
    const perPage = $('select[name="perPage"]')
    if (perPage.length) {
      // Get session data
      const data = Session.get(this.module)
      if (null !== data && data.hasOwnProperty('take')) {
        // Set selected element
        perPage.find(`option[value="${data.take}"]`).attr('selected', 'selected')
      }
      // Change "elements per page value"
      perPage.on('change', () => {
        Session.has(this.module) && Session.update(this.module, {take: perPage.val()})
        this.getList()
      })
    }

    this.getList()
  }
}