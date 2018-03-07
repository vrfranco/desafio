import Vue from 'vue';
import Vuex from 'vuex';
import api from './api';

Vue.use(Vuex);

export default new Vuex.Store({

    state: {
        urls: [],
    },

    mutations: {

        SET_URLS(state, urls)
        {
            state.urls = urls
        },

        ADD_URL(state, url)
        {
             state.urls.splice(0, 0, url);
        },

        CHANGE_URL(state, url)
        {
            state.urls.forEach((item, index) => {
                if( item.id == url.id )
                    state.urls.splice(index, 1, url);
            });
        },

    },

    actions: {

        GET_URLS(context)
        {
            console.log('GET_URLS');

            return api.url.all().then((data) => {
                context.commit('SET_URLS', data);
            });
        },

        WAIT_UPDATES_URLS(context)
        {
            console.log('WAIT_UPDATES_URLS');

            return Echo.channel('done').listen('Done', (data) => {

                context.commit('CHANGE_URL', data);

            });
        },

        ADD_URL(context, url)
        {
            console.log('ADD_URL');

            return api.url.add(url).then((data) => {

                context.commit('ADD_URL', data);

            });
        },
    }
});