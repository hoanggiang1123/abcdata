import api from '../../api';
const state = {
    popupObj: {}
}

const getters = {
    getListPopup (state) {
        if (state.popupObj.data && state.popupObj.data.length) {
            return state.popupObj.data
        }
        return []
    },
    getListPopupPagination (state) {
        if (state.popupObj.last_page) {
            return {
                page_count: state.popupObj.last_page,
                total: state.popupObj.total,
            }
        }
        return {}
    }
}

const mutations = {
    SET_LIST_POPUP (state, payload) {
        state.popupObj = payload;
    },
}

const actions = {
    async getListPopup ({ commit }, params = {}) {
        const resp = await api.popup.getListPopup(params);
        commit('SET_LIST_POPUP', resp.data);
        return resp.data;
    },
    async updatePopup  ({ commit }, data = {}) {
        const resp = await api.popup.updatePopup(data);
        return resp.data;
    },
    async deletePopup  ({ commit }, data = {}) {
        const resp = await api.popup.deletePopup(data);
        return resp.data;
    },
    async createPopup ({ commit }, data = {}) {
        const resp = await api.popup.createPopup(data);
        return resp.data
    },
    async createMany ({ commit }, data = {}) {
        const resp = await api.popup.createMany(data);
        return resp.data
    },
    async updateAllPopup ({ commit }, data = {}) {
        const resp = await api.popup.updateAllPopup(data);
        return resp.data;
    }
}

export default {
    state,
    actions,
    mutations,
    getters,
    namespaced: true
}
