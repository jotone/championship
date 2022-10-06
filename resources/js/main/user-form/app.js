import { createApp } from 'vue';
import Axios from 'axios';
import Main from './Main.vue';

window.$.axios = Axios

createApp(Main).mount('#userForm')