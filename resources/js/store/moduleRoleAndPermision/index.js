import api from '../../api';

const state = {
    listRole: [],
    listPermision: []
}

const getters = {
    getListRole (state) {
        return state.listRole;
    },
    getListPermision (state) {
        return state.listPermision;
    }
}

const mutations = {
    SET_LIST_ROLE (state, payload) {
        state.listRole = payload;
    },
    SET_LIST_PERMISION (state, payload) {
        state.listPermision = payload;
    },
}

const actions = {
    async getListRole ({ commit }, params = {}) {
        const resp = await api.roleAndPermision.getListRole(params);
        commit('SET_LIST_ROLE', resp.data);
        return resp.data;
    },
    async getListPermision ({ commit }, params = {}) {
        const resp = await api.roleAndPermision.getListPermision(params);
        commit('SET_LIST_PERMISION', resp.data);
        return resp.data;
    },
    async createRoleAndPermision ({ commit }, data = {}) {
        const resp = await api.roleAndPermision.createRoleAndPermision(data);
        return resp.data;
    },
    async updateRoleAndPermision ({ commit }, data = {}) {
        const resp = await api.roleAndPermision.updateRoleAndPermision(data);
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
