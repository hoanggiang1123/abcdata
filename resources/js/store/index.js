import Vue from 'vue';
import Vuex from 'vuex';

import user from './moduleUser';
import link from './moduleLink';
import category from './moduleCategory';
import media from './moduleMedia';
import enterLink from './moduleEnterLink';
import roleAndPermision from './moduleRoleAndPermision';
import pluginRepo from './modulePluginRepo';
import teleSale from './moduleTeleSale';
import post from './modulePost';
import keyword from './moduleKeyword';
import fbStat from './moduleFbStat';
import popup from './modulePopup';

Vue.use(Vuex);


const store = new Vuex.Store ({
    strict: process.env.NODE_ENV !== 'production',
    state: {
        setting: {}
    },
    modules: {
        user,
        link,
        category,
        media,
        enterLink,
        roleAndPermision,
        pluginRepo,
        teleSale,
        post,
        keyword,
        fbStat,
        popup
    }
});


export default store;
