import api from '../../api';
const state = {
    postObj: {}
}

const getters = {
    getListPost (state) {
        if (state.postObj.data && state.postObj.data.length) {
            return state.postObj.data
        }
        return []
    },
    getListPostPagination (state) {
        if (state.postObj.last_page) {
            return {
                page_count: state.postObj.last_page,
                total: state.postObj.total,
            }
        }
        return {}
    }
}

const mutations = {
    SET_LIST_POST (state, payload) {
        state.postObj = payload;
    },
}

const actions = {
    async getListPost ({ commit }, params = {}) {
        const resp = await api.post.getListPost(params);
        commit('SET_LIST_POST', resp.data);
        return resp.data;
    },
    async updatePost ({ commit }, data = {}) {
        const resp = await api.post.updatePost(data);
        return resp.data;
    },
    async deletePost  ({ commit }, data = {}) {
        const resp = await api.post.deletePost(data);
        return resp.data;
    },
    async createPost ({ commit }, data = {}) {
        const resp = await api.post.createPost(data);
        return resp.data
    },
    async createMany ({ commit }, data = {}) {
        const resp = await api.post.createMany(data);
        return resp.data
    },
    async updateAllPostContent ({ commit }, data = {}) {
        const resp = await api.post.updateAllPostContent(data);
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
