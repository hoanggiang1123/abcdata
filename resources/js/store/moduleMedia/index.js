import api from '../../api';

const state = {
    mediaListObj: {}
}

const getters = {
    getMediaList (state) {
        if (state.mediaListObj.data && state.mediaListObj.data.length) {
            return state.mediaListObj.data
        }
        return []
    },
    getPaination (state) {
        if (state.mediaListObj.last_page) {
            return {
                page_count: state.mediaListObj.last_page
            }
        }
        return {}
    },
}

const mutations = {
    SET_MEDIA_LIST_OBJ (state, payload) {
        state.mediaListObj = payload;
    }
}

const actions = {
    async uploadMedia ({ commit }, data, config) {
        const resp = await api.media.uploadMedia(data, config);
        return resp;
    },
    async getMediaList  ({ commit }, params) {
        const resp = await api.media.getMediaList(params);
        commit('SET_MEDIA_LIST_OBJ', resp.data);
        return resp.data;
    },
    async editMedia ({ commit }, data) {
        const resp = await api.media.editMedia(data);
        return resp.data;
    },
    async deleteMedia ({ commit }, data) {
        const resp = await api.media.deleteMedia(data);
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
