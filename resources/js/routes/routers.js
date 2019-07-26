import Vue from 'vue';
import VueRouter from 'vue-router';

import routes from './routers.map';

Vue.use(VueRouter);

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: "active",
    linkExactActiveClass: "exact-active",
});

export default router;
