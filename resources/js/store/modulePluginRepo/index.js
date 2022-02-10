import api from '../../api';
const state = {
    pluginRepoList: []
}

const getters = {
    getPluginRepo (state) {
        return state.pluginRepoList;
    }
}

const mutations = {
    SET_PLUGIN_REPO_LIST (state, payload) {
        state.pluginRepoList = payload;
    }
}

const actions = {
    async getPluginRepo ({ commit }, params = {}) {
        const resp = await api.pluginRepo.getPluginRepo(params);
        commit('SET_PLUGIN_REPO_LIST', resp.data);
        return resp.data;
    },
    async createPluginRepo ({ commit }, data = {}) {
        const resp = await api.pluginRepo.createPluginRepo(data);
        return resp.data;
    },
    async uploadPluginFile ({ commit }, data) {
        const resp = await api.pluginRepo.uploadPluginFile(data);
        return resp.data;
    },
    async updatePluginRepo ({ commit }, data) {
        const resp = await api.pluginRepo.updatePluginRepo(data);
        return resp.data;
    },
}

export default {
    state,
    actions,
    mutations,
    getters,
    namespaced: true
}
