require('./bootstrap');

import Vue from "vue";
import BootstrapVue from "bootstrap-vue";
import VueLaroute from 'vue-laroute';
import VeeValidate from 'vee-validate';
import VueI18n from 'vue-i18n';
import VueSweetalert2 from 'vue-sweetalert2';

import App from "./layout/App";
import validationMessages from 'vee-validate/dist/locale/pt_BR';

Vue.use(BootstrapVue);
Vue.use(VueI18n);
Vue.use(VueSweetalert2);

Vue.use(VueLaroute, {
    routes: require('./apiLaroute'),
    accessor: '$apiLaroute', // Optional: the global variable for accessing the router
});

const i18n = new VueI18n();
i18n.locale = "pt_BR";

Vue.use(VeeValidate, {
    inject: true,
    fieldsBagName: 'veeFields',
    errorBagName: 'veeErrors',
    i18nRootKey: 'validations',
    i18n,
    dictionary: {
        pt_BR: validationMessages
    }
})

import router from './routes/routers'

const app = new Vue({
    el: '#app',
    router,
    ...App
});
