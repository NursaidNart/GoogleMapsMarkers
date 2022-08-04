import './bootstrap';
import '../sass/app.scss'



import App from './components/App.vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import { routes } from './routes';
import * as VueRouter from 'vue-router';
import * as Vue from 'vue'




const router =  VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes: routes
});


const app = Vue.createApp(App);
app.use(router)
app.use(VueAxios, axios)
app.config.globalProperties.data = data;

app.mount('#app');
