import Vue from 'vue';
import VueRouter from 'vue-router';
import ListComponent from './components/ListComponent';

Vue.use(VueRouter);

const routes = [

    { path: '/', component: ListComponent },

];

export default new VueRouter({

    routes

});