<template>
  <div>
    <table>
      <thead>
      <tr>
        <th data-field="id">
          <TableDirections text="#"></TableDirections>
        </th>
        <th data-field="name">
          <TableDirections text="Name"></TableDirections>
        </th>
        <th data-field="email">
          <TableDirections text="Email"></TableDirections>
        </th>
        <th data-field="role_id">
          <TableDirections text="Role"></TableDirections>
        </th>
        <th data-field="created_at">
          <TableDirections text="Created"></TableDirections>
        </th>
        <th>
          <div class="col-name"><i class="fas fa-cogs"></i></div>
        </th>
      </tr>
      </thead>

      <tbody>
      <tr v-for="model in models" :data-id="model.id">
        <td><span>{{ model.id }}</span></td>
        <td><span>{{ model.name }}</span></td>
        <td><span>{{ model.email }}</span></td>
        <td><a :href="editRoleRoute(model.role_id)">{{ model.role.name }}</a></td>
        <td><span>{{ formatDate(model.created_at) }}</span></td>
        <td>
          <div class="controls">
            <a class="edit" :href="editRoute(model.id)">
              <i class="fas fa-edit"></i>
            </a>
            <a class="remove" :href="removeRoute(model.id)" @click.prevent="removeEvent">
              <i class="fas fa-times"></i>
            </a>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
    <Pagination :pagination="pagination"></Pagination>
  </div>
</template>

<script>

import Pagination from '../components/Pagination.vue'
import TableDirections from '../components/TableDirections.vue'
import {ContentTableMixin} from "../components/content-table-mixin";

export default {
  components: {
    Pagination,
    TableDirections
  },
  data() {
    return {
      entity: 'user.1',
      models: [],
      module: 'users',
      pagination: {},
      routes: {}
    }
  },
  methods: {
    editRoleRoute(id) {
      return window.Helpers.buildUrl(this.routes.role, id, 2)
    },
  },
  mixins: [ContentTableMixin]
}
</script>