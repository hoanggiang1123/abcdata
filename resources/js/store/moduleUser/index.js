import api from '../../api';
import jwt_decode from 'jwt-decode';
const getScreen = (userLogInfo) => {
    const roles = userLogInfo.roles;
    if (roles && roles.length) {
        // const screens = []
        const mapScreen = {
            'LinkController': 'banner',
            'LinkHitController': 'banner',
            'LinkDirectoryController': 'banner',
            'MediaController': 'media',
            'EnterLinkController': 'link',
            'FolderController': 'link',
            'RoleController': 'settings',
            'PermisionController': 'settings',
            'UserController': 'settings',
            'PluginRepoController': 'plugin',
            'TeleSaleLineController': 'telesale',
            'TeleSaleController': 'telesale',
            'PostController': 'post',
            'KeywordController': 'keyword',
            'SatiliteDomainController': 'keyword',
            'KeywordTrackingController': 'keyword',
            'TeleSaleAgentController': 'telesale',
            'TeleSaleVipController': 'telesale',
            'FbLeagueController': 'leagues',
            'TeleSaleCategoryController': 'telesale',
            'PopupController': 'popup',
            'TeleSaleRecordController': 'telesale'
        };
        const mapScreenSub = {
            'LinkController': 'banner',
            'LinkHitController': 'banner',
            'LinkDirectoryController': 'category',
            'MediaController': 'media',
            'EnterLinkController': 'link',
            'FolderController': 'folder',
            'RoleController': 'roleandpermision',
            'PermisionController': 'roleandpermision',
            'UserController': 'user',
            'PluginRepoController': 'plugin',
            'TeleSaleLineController': 'line',
            'TeleSaleController': 'telesale',
            'PostController': 'post',
            'KeywordController': 'keyword',
            'SatiliteDomainController': 'satilite',
            'KeywordTrackingController': 'tracking',
            'TeleSaleAgentController': 'agent',
            'TeleSaleVipController': 'vip',
            'FbLeagueController': 'leagues',
            'TeleSaleCategoryController': 'category',
            'PopupController': 'popup',
            'TeleSaleRecordController': 'record'
        }
        const screens = {}
        roles.forEach(role => {
            if (role.permisions && role.permisions.length) {
                role.permisions.forEach(permision => {
                    const controllers = permision.controller;
                    if (controllers) {
                        const controlName = (controllers.split('@'))[0];
                        const screen = mapScreen[controlName];
                        const subScreen = mapScreenSub[controlName];
                        if (!screens[screen]) screens[screen] = [];
                        if (!screens[screen].includes(subScreen)) screens[screen].push(subScreen);
                        //if (!screens.includes(screen)) screens.push(screen);
                    }
                })
            }
        })
        return screens;
    }
    return []
}

const state = {
    userLogInfo: {},
    token: localStorage.getItem('token') ? localStorage.getItem('token') : '',
    refreshTask: null,
    listUser: [],
    screens: localStorage.getItem('screen') ? JSON.parse(localStorage.getItem('screen')) : []
}
const getters = {
    isLogin (state) {
        return state.token;
    },
    getUserLogInfo () {
        return state.userLogInfo;
    },
    getListUser (state) {
        return state.listUser;
    },
    getScreen (state) {
        return state.screens;
    }
}
const mutations = {
    SET_USER_INFO (state, payload) {
        state.userLogInfo = payload.user;
        state.token = payload.access_token;
    },
    SET_REFRESH_TASK (state, payload) {
        state.refreshTask = payload;
    },
    SET_LIST_USER (state, payload) {
        state.listUser = payload;
    },
    SET_USER_SCREEN (state, payload) {
        state.screens = payload;
    }
}
const actions = {
    async userLogin({ state, commit }, data = {}) {
        const resp = await api.user.userLogin(data);
        commit('SET_USER_INFO', resp.data);
        localStorage.setItem('token', resp.data.access_token);
        const screens = getScreen(resp.data.user);
        commit("SET_USER_SCREEN", screens);
        localStorage.setItem('screen', JSON.stringify(screens));
        return resp.data;
    },
    async checkLogin ({ commit }, data = {}) {
        const token = localStorage.getItem('token');
        const resp = await api.user.checkLogin();
        resp.data.access_token = token;
        commit('SET_USER_INFO', resp.data);
        return resp.data;
    },
    async userLogout ({ commit }, data = {}) {
        const resp = await api.user.userLogout();
        localStorage.removeItem('token');
        localStorage.removeItem('screen');
        commit('SET_USER_INFO', { user: {}, access_token: '' });
        commit("SET_USER_SCREEN", []);
        return resp;
    },
    autoRefresh ({ state, commit, dispatch }) {
        const token = state.token;
        if (token) {
            const { exp } = jwt_decode(token);
            const now = Date.now() / 1000;
            let timeUntilRefresh = exp - now;
            timeUntilRefresh -= (5 * 60);
            const refreshTask = setTimeout(() => dispatch('refreshTokens'), timeUntilRefresh * 1000);
            commit('SET_REFRESH_TASK', refreshTask)
        }
    },
    async refreshTokens ({ state, commit, dispatch }) {
        try {
            const resp = await api.user.refreshTokens();
            commit('SET_USER_INFO', resp.data);
            localStorage.setItem('token', resp.data.access_token);
            const screens = getScreen(resp.data.user);
            commit("SET_USER_SCREEN", screens);
            localStorage.setItem('screen', JSON.stringify(screens));
            dispatch('autoRefresh');
        } catch (err) {
            console.log(err)
        }
    },
    async getListUser ({ commit }, params = {}) {
        const resp = await api.user.getListUser(params);
        commit('SET_LIST_USER', resp.data);
        return resp.data;
    },
    async updateUser ({ commit }, data = {}) {
        const resp = await api.user.updateUser(data);
        return resp.data;
    },

    async createUser ({ commit }, data = {}) {
        const resp = await api.user.createUser(data);
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
