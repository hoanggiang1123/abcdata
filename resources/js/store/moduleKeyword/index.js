import api from '../../api';
const state = {
    keywordObj: {},
    satiliteDomainObj: {},
    keycode: {},
    keywordTracking: {},
    trackfilter: {},
    chartClick: []

}

const getters = {
    getListKeywords (state) {
        if (state.keywordObj.data && state.keywordObj.data.length) {
            return state.keywordObj.data
        }
        return []
    },
    getListKeywordPagination (state) {
        if (state.keywordObj.last_page) {
            return {
                page_count: state.keywordObj.last_page,
                total: state.keywordObj.total,
            }
        }
        return {}
    },
    getListSatiliteDomain  (state) {
        if (state.satiliteDomainObj.data && state.satiliteDomainObj.data.length) {
            return state.satiliteDomainObj.data
        }
        return []
    },
    getListSatiliteDomainPagination (state) {
        if (state.satiliteDomainObj.last_page) {
            return {
                page_count: state.satiliteDomainObj.last_page,
                total: state.satiliteDomainObj.total,
            }
        }
        return {}
    },
    getKeyCode (state) {
        return state.keycode;
    },

    getTracking (state) {
        if (state.keywordTracking.data && state.keywordTracking.data.length) {
            return state.keywordTracking.data
        }
        return []
    },
    getTrackingPagination (state) {
        if (state.keywordTracking.last_page) {
            return {
                page_count: state.keywordTracking.last_page,
                total: state.keywordTracking.total,
            }
        }
        return {}
    },
    getFilter (state) {
        return state.trackfilter;
    },
    getClickChart (state) {
        return state.chartClick
    }
}

const mutations = {
    SET_LIST_KEYWORD (state, payload) {
        state.keywordObj = payload;
    },
    SET_LIST_SATILITEDOMAIN (state, payload) {
        state.satiliteDomainObj = payload;
    },
    SET_KEYCODE (state, payload) {
        state.keycode = payload
    },
    SET_KEYWORD_TRACKING (state, payload) {
        state.keywordTracking = payload
    },
    SET_TRACKING_FILTER (state, payload) {
        state.trackfilter = payload
    },
    SET_CLICK_CHART (state, payload) {
        state.chartClick = payload
    },
}

const actions = {
    async getListKeywords ({ commit }, params = {}) {
        const resp = await api.keyword.getListKeywords(params);
        commit('SET_LIST_KEYWORD', resp.data);
        return resp.data;
    },
    async updateKeyword ({ commit }, data = {}) {
        const resp = await api.keyword.updateKeyword(data);
        return resp.data;
    },
    async deleteKeyword  ({ commit }, data = {}) {
        const resp = await api.keyword.deleteKeyword(data);
        return resp.data;
    },
    async createKeyword ({ commit }, data = {}) {
        const resp = await api.keyword.createKeyword(data);
        return resp.data
    },
    async createMany ({ commit }, data = {}) {
        const resp = await api.keyword.createMany(data);
        return resp.data
    },
    async getListSatiliteDomain ({ commit }, params = {}) {
        const resp = await api.keyword.getListSatiliteDomain(params);
        commit('SET_LIST_SATILITEDOMAIN', resp.data);
        return resp.data;
    },
    async createSatiliteDomain ({ commit }, data = {}) {
        const resp = await api.keyword.createSatiliteDomain(data);
        return resp.data
    },
    async updateSatiliteDomain ({ commit }, data = {}) {
        const resp = await api.keyword.updateSatiliteDomain(data);
        return resp.data;
    },
    async deleteSatiliteDomain  ({ commit }, data = {}) {
        const resp = await api.keyword.deleteSatiliteDomain(data);
        return resp.data;
    },
    async createManySatiliteDomain ({ commit }, data = {}) {
        const resp = await api.keyword.createManySatiliteDomain(data);
        return resp.data
    },
    async getKeyCode ({ commit }, params = {}) {
        const resp = await api.keyword.getKeyCode(params);
        commit('SET_KEYCODE', resp.data);
        return resp.data
    },
    async updateKeyCode ({ commit }, data = {}) {
        const resp = await api.keyword.updateKeyCode(data);
        return resp.data
    },
    async getTracking ({ commit }, params = {}) {
        const resp = await api.keyword.getTracking(params);
        commit ('SET_KEYWORD_TRACKING', resp.data);
        return resp.data
    },
    async getFilter ({ commit }, params = {}) {
        const resp = await api.keyword.getFilter(params);
        commit ('SET_TRACKING_FILTER', resp.data);
        return resp.data
    },
    async getClickChart ({ commit }, params = {}) {
        const resp = await api.keyword.getClickChart(params);
        commit ('SET_CLICK_CHART', resp.data);
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
