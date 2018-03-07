import Vue from 'vue';
import VueRouter from 'vue-router';
import ListComponent from './components/ListComponent';
import ViewComponent from './components/ViewComponent';

Vue.use(VueRouter);

const routes = [

    { path: '/', component: ListComponent, name: 'list' },
    
    { path: '/:id', component: ViewComponent, name: 'view' },

];

export default new VueRouter({

    routes

});