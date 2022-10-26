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
        <th data-field="code">
          <TableDirections text="Код"></TableDirections>
        </th>
        <th>
          <div class="col-name">Зображення</div>
        </th>
        <th data-field="created_at">
          <TableDirections text="Створено"></TableDirections>
        </th>
        <th>
          <div class="col-name"><i class="fas fa-cogs"></i></div>
        </th>
      </tr>
      </thead>

      <tbody>
      <tr v-for="model in models" :data-id="model.id">
        <td><span>{{ model.id }}</span></td>
        <td><span>{{ model.code }}</span></td>
        <td><span>{{ model.ua }}</span></td>
        <td><span>{{ model.en }}</span></td>
        <td>
          <img :src="model.img_url" style="max-height: 40px" alt="">
        </td>
        <td><span>{{ formatDate(model.created_at) }}</span></td>
        <td>
          <div class="controls">
            <a class="edit" :href="editRoute(model.id)" title="Редагувати">
              <i class="fas fa-edit"></i>
            </a>
            <a class="remove" :href="removeRoute(model.id)" @click.prevent="removeEvent" title="Видалити">
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
      entity: 'країну.1',
      models: [],
      module: 'countries',
      pagination: {},
      routes: {}
    }
  },
  mixins: [ContentTableMixin]
}
</script>