require('./bootstrap');

import Vue from "vue";
import Vuex from "vuex";
import BootstrapVue from "bootstrap-vue";
import VueLaroute from 'vue-laroute';

import App from "./layout/App";

Vue.use(Vuex);
Vue.use(BootstrapVue);

// import routes from './apiLaroute.js';
Vue.use(VueLaroute, {
  routes: require('./apiLaroute'),
  accessor: '$apiLaroute', // Optional: the global variable for accessing the router
});

import StoreData from "./store";
const store = new Vuex.Store(StoreData);

import router from './routes/routers'

const app = new Vue({
    el: '#app',
    router,
    store,
    ...App
});
