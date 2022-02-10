import api from '../../api';
const state = {
    linkObj: {},
    linkDetail: {},
    linkHit: [],
    categoryObj: {},
    embededUrl: {},
    linkEmbedList: [],
    linkEmbedListTrash: [],
    linkFilter: {},
    categoryFilter: {},
    linkHitObj: {},
    linkHitFilter: {},
    chartClick: []
}
const getters = {
    getLinkList (state) {
        if (state.linkObj.data && state.linkObj.data.length) {
            return state.linkObj.data
        }
        return []
    },
    getPaination (state) {
        if (state.linkObj.last_page) {
            return {
                page_count: state.linkObj.last_page,
                total: state.linkObj.total,
            }
        }
        return {}
    },
    getLinkDetail (state) {
        return state.linkDetail
    },
    getLinkHit (state) {
        return state.linkHit;
    },
    getLinkHitList (state) {
        if (state.linkHitObj.data && state.linkHitObj.data.length) {
            return state.linkHitObj.data
        }
        return []
    },
    getLinkHitPaination (state) {
        if (state.linkHitObj.last_page) {
            return {
                page_count: state.linkHitObj.last_page,
                total: state.linkHitObj.total,
            }
        }
        return {}
    },
    getLinkHitFilter (state) {
        return state.linkHitFilter
    },
    getCategoryList (state) {

        if (state.categoryObj.data && state.categoryObj.data.length) {
            return state.categoryObj.data
        }
        return []
    },
    getCategoryPagination (state) {
        if (state.categoryObj.last_page) {
            return {
                page_count: state.categoryObj.last_page,
                total: state.categoryObj.total,
            }
        }
        return {}
    },
    getEmbededUrl (state) {
        return state.embededUrl;
    },
    getLinkEmbedList (state) {
        return state.linkEmbedList;
    },
    getLinkEmbedListTrash (state) {
        return state.linkEmbedListTrash;
    },
    getLinkFilter () {
        return state.linkFilter;
    },
    getCategoryFilter (state) {
        return state.categoryFilter;
    },
    getChartClick (state) {
        return state.chartClick;
    }
}
const mutations = {
    SET_LINK_OBJECT (state, payload) {
        state.linkObj = payload;
    },
    SET_LINK_DETAIL (state, payload) {
        state.linkDetail = payload;
    },
    SET_LINK_HIT (state, payload) {
        state.linkHit = payload;
    },
    SET_CATEGORY_OBJ (state, payload) {
        state.categoryObj = payload;
    },
    SET_EMBEDED_URL (state, payload) {
        state.embededUrl = payload;
    },
    SET_LINK_EMBEDED_LIST (state, payload) {
        state.linkEmbedList = payload;
    },
    SET_LINK_EMBEDED_LIST_TRASH (state, payload) {
        state.linkEmbedListTrash = payload;
    },
    SET_LINK_FILTER (state, payload) {
        state.linkFilter = payload;
    },
    SET_CATEGORY_FILTER (state, payload) {
        state.categoryFilter = payload
    },
    SET_LINK_HIT_OBJ (state, payload) {
        state.linkHitObj = payload;
    },
    SET_LINK_HIT_FILTER (state, payload) {
        state.linkHitFilter = payload;
    },
    SET_CHART_CLICK (state, payload) {
        state.chartClick = payload
    }
}
const actions = {
    async getLinkList({ commit }, data = {}) {
        const resp = await api.link.getLinkList(data);
        commit('SET_LINK_OBJECT', resp.data);
    },
    async updateLink ({ commit }, data = {}) {
        const resp = await api.link.updateLink(data);
        return resp;
    },
    async createLink ({ commit }, data = {}) {
        const resp = await api.link.createLink(data);
        return resp;
    },
    async createManyLink ({ commit }, data = []) {
        const resp = await api.link.createManyLink(data);
        return resp;
    },
    async getLinkDetail ({ commit }, id) {
        const resp = await api.link.getLinkDetail(id);
        commit('SET_LINK_DETAIL', resp.data)
        return resp.data;
    },
    async uploadImage ({ commit }, data) {
        const resp = await api.link.uploadImage(data);
        return resp.data;
    },
    async getLinkHit ({ commit }, data) {
        const resp = await api.link.getLinkHit(data);
        commit('SET_LINK_HIT', resp.data);
        return resp.data;
    },

    async getLinkHitList ({ commit }, data) {
        const resp = await api.link.getLinkHitList(data);
        commit('SET_LINK_HIT_OBJ', resp.data);
        return resp.data;
    },

    async getLinkHitFilter ({ commit }) {
        const resp = await api.link.getLinkHitFilter();
        commit('SET_LINK_HIT_FILTER', resp.data);
        return resp.data;
    },

    async getCategoryList({ commit }, data = {}) {
        const resp = await api.link.getCategoryList(data);
        commit('SET_CATEGORY_OBJ', resp.data);
        return resp.data
    },
    async updateCategory ({ commit }, data = {}) {
        const resp = await api.link.updateCategory(data);
        return resp.data
    },
    async createCategory ({ commit }, data = {}) {
        const resp = await api.link.createCategory(data);
        return resp.data
    },

    async createEmbedLink ({ commit }, data = {}) {
        const resp = await api.link.createEmbedLink(data);
        commit('SET_EMBEDED_URL', resp.data)
        return resp.data
    },
    async getLinkEmbedList ({ commit }, data = {}) {
        const resp = await api.link.getLinkEmbedList(data);
        commit('SET_LINK_EMBEDED_LIST', resp.data)
        return resp.data
    },
    async getLinkEmbedListTrash ({ commit }, data = {}) {
        const resp = await api.link.getLinkEmbedListTrash(data);
        commit('SET_LINK_EMBEDED_LIST_TRASH', resp.data)
        return resp.data
    },
    async updateLinkEmbed ({ commit }, data = {}) {
        const resp = await api.link.updateLinkEmbed(data);
        return resp.data
    },
    async deleteLinkEmbed ({ commit }, id) {
        const resp = await api.link.deleteLinkEmbed(id);
        return resp.data
    },

    async restoreEmbedLink ({ commit }, id) {
        const resp = await api.link.restoreEmbedLink(id);
        return resp.data
    },

    async deleteLinkEmbedPermanent ({ commit }, id) {
        const resp = await api.link.deleteLinkEmbedPermanent(id);
        return resp.data
    },
    async getLinkFilter  ({ commit }, data = {}) {
        const resp = await api.link.getLinkFilter(data);
        commit('SET_LINK_FILTER', resp.data)
        return resp.data
    },
    async getCategoryFilter ({ commit }, data = {}) {
        const resp = await api.link.getCategoryFilter(data);
        commit('SET_CATEGORY_FILTER', resp.data);
        return resp.data
    },

    async getChartClick ({ commit }, params = {}) {
        const resp = await api.link.getChartClick(params);
        commit ('SET_CHART_CLICK', resp.data);
        return resp.data;
    },
    async deleteLinkBanner ({ commit }, data = {}) {
        const resp = await api.link.deleteLinkBanner(data);
        return resp.data
    },

    async deleteLinkDirectoryBanner  ({ commit }, data = {}) {
        const resp = await api.link.deleteLinkDirectoryBanner(data);
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
