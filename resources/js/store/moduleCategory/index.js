import api from '../../api';
const state = {
    categoryList: [],
    categoryFilter: {}
}
const getters = {
    getCategoryList (state) {
        return state.categoryList
    },
    getCategoryFilter (state) {
        return state.categoryFilter;
    }
}
const mutations = {
    SET_CATEGORY_LIST (state, payload) {
        state.categoryList = payload;
    },
    SET_CATEGORY_FILTER (state, payload) {
        state.categoryFilter = payload
    }
}
const actions = {
    async getCategoryList({ commit }, data = {}) {
        const resp = await api.category.getCategoryList(data);
        commit('SET_CATEGORY_LIST', resp.data);
    },
    async updateCategory ({ commit }, data = {}) {
        const resp = await api.category.updateCategory(data);
        return resp.data
    },
    async createCategory ({ commit }, data = {}) {
        const resp = await api.category.createCategory(data);
        return resp.data
    },

}
export default {
    state,
    actions,
    mutations,
    getters,
    namespaced: true
}
