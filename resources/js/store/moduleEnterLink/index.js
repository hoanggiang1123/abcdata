import api from '../../api';
const state = {
    enterLinkObj: {},
    folderLinkObj: {},
    enterLinkFilter: {},
    folderFilter: {},
    enterLinkDetail: {},
    enterLinkHit: []
}
const getters = {
    getEnterLinkList (state) {
        if (state.enterLinkObj.data && state.enterLinkObj.data.length) {
            return state.enterLinkObj.data
        }
        return []
    },
    getEnterLinkPaination (state) {
        if (state.enterLinkObj.last_page) {
            return {
                page_count: state.enterLinkObj.last_page,
                total: state.enterLinkObj.total,
            }
        }
        return {}
    },
    getFolderLinkList (state) {
        if (state.folderLinkObj.data && state.folderLinkObj.data.length) {
            return state.folderLinkObj.data
        }
        return []
    },
    getFolderLinkPagination (state) {
        if (state.folderLinkObj.last_page) {
            return {
                page_count: state.folderLinkObj.last_page,
                total: state.folderLinkObj.total,
            }
        }
        return {}
    },
    getEnterLinkFilter (state) {
        return state.enterLinkFilter;
    },
    getFolderFilter (state) {
        return state.folderFilter
    },
    getEnterLinkDetail (state) {
        return state.enterLinkDetail;
    },
    getEnterLinkHit (state) {
        return state.enterLinkHit
    }
}
const mutations = {
    SET_ENTER_LINK_OBJECT (state, payload) {
        state.enterLinkObj = payload;
    },
    SET_FOLDER_LINK_OBJECT (state, payload) {
        state.folderLinkObj = payload;
    },
    SET_ENTER_LINK_FILTER (state, payload) {
        state.enterLinkFilter = payload;
    },
    SET_FOLDER_FILTER (state, payload) {
        state.folderFilter = payload;
    },
    SET_ENTER_LINK_DETAIL (state, payload) {
        state.enterLinkDetail = payload;
    },
    SET_ENTER_LINK_HIT (state, payload) {
        state.enterLinkHit = payload;
    },
}
const actions = {
    async getEnterLinkList({ commit }, data = {}) {
        const resp = await api.enterLink.getEnterLinkList(data);
        commit('SET_ENTER_LINK_OBJECT', resp.data);
        return resp.data;
    },
    async getFolderLinkList({ commit }, data = {}) {
        const resp = await api.enterLink.getFolderLinkList(data);
        commit('SET_FOLDER_LINK_OBJECT', resp.data);
        return resp.data;
    },
    async getEnterLinkFilter({ commit }, data = {}) {
        const resp = await api.enterLink.getEnterLinkFilter(data);
        commit('SET_ENTER_LINK_FILTER', resp.data);
        return resp.data;
    },
    async updateEnterLink ({ commit }, data = {}) {
        const resp = await api.enterLink.updateEnterLink(data);
        return resp.data;
    },
    async createEnterLink ({ commit }, data = {}) {
        const resp = await api.enterLink.createEnterLink(data);
        return resp.data;
    },
    async createManyEnterLink ({ commit }, data = {}) {
        const resp = await api.enterLink.createManyEnterLink(data);
        return resp.data;
    },
    async getFolderFilter ({ commit }, data = {}) {
        const resp = await api.enterLink.getFolderFilter(data);
        commit('SET_FOLDER_FILTER', resp.data);
        return resp.data;
    },
    async updateFolder  ({ commit }, data = {}) {
        const resp = await api.enterLink.updateFolder(data);
        return resp.data;
    },
    async createFolder ({ commit }, data = {}) {
        const resp = await api.enterLink.createFolder(data);
        return resp.data;
    },
    async getEnterLinkDetail ({ commit }, id) {
        const resp = await api.enterLink.getEnterLinkDetail(id);
        commit('SET_ENTER_LINK_DETAIL', resp.data);
        return resp.data;
    },
    async getEnterLinkHit ({ commit }, params = {}) {
        const resp = await api.enterLink.getEnterLinkHit(params);
        commit('SET_ENTER_LINK_HIT', resp.data);
        return resp.data;
    },
    async updateInitVoteCount ({ commit }, data = {}) {
        const resp = await api.enterLink.updateInitVoteCount(data);
        return resp.data;
    },
    async deleteManyLink ({ commit }, data = {}) {
        const resp = await api.enterLink.deleteManyLink(data);
        return resp.data;
    },
    async deleteLink ({ commit }, id) {
        const resp = await api.enterLink.deleteLink(id);
        return resp.data;
    },
    async deleteFolder ({ commit }, data = {}) {
        const resp = await api.enterLink.deleteFolder(data);
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
