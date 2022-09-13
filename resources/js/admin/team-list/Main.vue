<template>
  <div>
    <table>
      <thead>
      <tr>
        <th data-field="id">
          <TableDirections text="#"></TableDirections>
        </th>
        <th data-field="ua">
          <TableDirections text="UA"></TableDirections>
        </th>
        <th data-field="en">
          <TableDirections text="EN"></TableDirections>
        </th>
        <th data-field="country">
          <TableDirections text="Country"></TableDirections>
        </th>
        <th>
          <div class="col-name">Image</div>
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
        <td><span>{{ model.ua }}</span></td>
        <td><span>{{ model.en }}</span></td>
        <td><a :href="editCountryRoute(model.country_id)">{{ model.country }}</a></td>
        <td>
          <img :src="model.img_url" style="max-height: 40px" alt="">
        </td>
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
import { ContentTableMixin } from "../components/content-table-mixin";

export default {
  components: {
    Pagination,
    TableDirections
  },
  data() {
    return {
      entity: 'country.1',
      models: [],
      module: 'roles',
      pagination: {},
      routes: {}
    }
  },
  methods: {
    editCountryRoute(id) {
      return Helpers.buildUrl(this.routes.country, id, 2)
    },
  },
  mixins: [ContentTableMixin]
}
</script>