import api from '../../api';
const state = {
    leagueObj: {}
}

const getters = {
    getLeagues (state) {
        if (state.leagueObj.data && state.leagueObj.data.length) {
            return state.leagueObj.data
        }
        return []
    },
    getLeaguesPagination (state) {
        if (state.leagueObj.last_page) {
            return {
                page_count: state.leagueObj.last_page,
                total: state.leagueObj.total,
            }
        }
        return {}
    },


}

const mutations = {
    SET_LEAGUE_OBJ (state, payload) {
        state.leagueObj = payload;
    }
}

const actions = {
    async getLeagues ({ commit }, params = {}) {
        const resp = await api.fbStat.getLeagues(params);
        commit('SET_LEAGUE_OBJ', resp.data);
        return resp.data;
    },
    async updateLeague ({ commit }, params = {}) {
        const resp = await api.fbStat.updateLeague(params);
        return resp.data;
    },
    async deleteLeagues ({ commit }, params = {}) {
        const resp = await api.fbStat.deleteLeagues(params);
        return resp.data;
    },

    async createLeague ({ commit }, data = {}) {
        const resp = await api.fbStat.createLeague(data);
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
