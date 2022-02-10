import api from '../../api';
const state = {
    teleSaleObj: {},
    teleSaleLineObj: {},
    teleSaleUser: [],
    teleSaleAgentObj: {},
    teleSaleVipObj: {},
    teleSaleCategoryObj: {},
    teleSaleAgentListLog: [],
    teleSource: [],
    teleSaleRecordObj: {},
    teleSaleRecordStatisticObj: {},
    teleSaleRecordFilterObj: {}
}

const getters = {
    getTeleSaleList (state) {
        if (state.teleSaleObj.data && state.teleSaleObj.data.length) {
            return state.teleSaleObj.data
        }
        return []
    },
    getTeleSalePagination (state) {
        if (state.teleSaleObj.last_page) {
            return {
                page_count: state.teleSaleObj.last_page,
                total: state.teleSaleObj.total,
            }
        }
        return {}
    },

    getTeleSaleLineList (state) {
        if (state.teleSaleLineObj.data && state.teleSaleLineObj.data.length) {
            return state.teleSaleLineObj.data
        }
        return []
    },
    getTeleSaleLinePagination (state) {
        if (state.teleSaleLineObj.last_page) {
            return {
                page_count: state.teleSaleLineObj.last_page,
                total: state.teleSaleLineObj.total,
            }
        }
        return {}
    },
    getTeleSaleUser (state) {
        return state.teleSaleUser;
    },

    getTeleSaleAgentList (state) {
        if (state.teleSaleAgentObj.data && state.teleSaleAgentObj.data.length) {
            return state.teleSaleAgentObj.data
        }
        return []
    },
    getTeleSaleAgentPagination (state) {
        if (state.teleSaleAgentObj.last_page) {
            return {
                page_count: state.teleSaleAgentObj.last_page,
                total: state.teleSaleAgentObj.total,
            }
        }
        return {}
    },
    getTeleSaleVip (state) {
        if (state.teleSaleVipObj.data && state.teleSaleVipObj.data.length) {
            return state.teleSaleVipObj.data
        }
        return []
    },
    getTeleSaleVipPagination (state) {
        if (state.teleSaleVipObj.last_page) {
            return {
                page_count: state.teleSaleVipObj.last_page,
                total: state.teleSaleVipObj.total,
            }
        }
        return {}
    },

    getTeleSaleCategory (state) {
        if (state.teleSaleCategoryObj.data && state.teleSaleCategoryObj.data.length) {
            return state.teleSaleCategoryObj.data
        }
        return []
    },
    getTeleSaleCategoryPagination (state) {
        if (state.teleSaleCategoryObj.last_page) {
            return {
                page_count: state.teleSaleCategoryObj.last_page,
                total: state.teleSaleCategoryObj.total,
            }
        }
        return {}
    },
    getTeleSaleAgentListLog (state) {
        return state.teleSaleAgentListLog;
    },
    getTeleSourceFrom (state) {
        return state.teleSource;
    },

    getTeleSaleRecord (state) {
        if (state.teleSaleRecordObj.data && state.teleSaleRecordObj.data.length) {
            return state.teleSaleRecordObj.data
        }
        return []
    },
    getTeleSaleRecordPagination (state) {
        if (state.teleSaleRecordObj.last_page) {
            return {
                page_count: state.teleSaleRecordObj.last_page,
                total: state.teleSaleRecordObj.total,
            }
        }
        return {}
    },

    getTeleSaleRecordStatisticCall (state) {
        if (state.teleSaleRecordStatisticObj.statistic_call && Object.keys(state.teleSaleRecordStatisticObj.statistic_call).length) {
            return state.teleSaleRecordStatisticObj.statistic_call
        }
        return {}
    },

    getTeleSaleRecordStatisticChart (state) {
        if (state.teleSaleRecordStatisticObj.statistic_chart && Object.keys(state.teleSaleRecordStatisticObj.statistic_chart).length) {
            return state.teleSaleRecordStatisticObj.statistic_chart
        }
        return {}
    },

    getTeleSaleRecordFilter (state) {
        if (state.teleSaleRecordFilterObj && Object.keys(state.teleSaleRecordFilterObj).length) {
            return state.teleSaleRecordFilterObj
        }
        return {}
    }
}

const mutations = {
    SET_TELESALE_OBJ (state, payload) {
        state.teleSaleObj = payload;
    },
    SET_TELESALE_LINE_OBJ (state, payload) {
        state.teleSaleLineObj = payload;
    },
    SET_TELESALE_USER (state, payload) {
        state.teleSaleUser = payload;
    },
    SET_TELESALE_AGENT_OBJ (state, payload) {
        state.teleSaleAgentObj = payload;
    },
    SET_TELESALE_VIP_OBJ (state, payload) {
        state.teleSaleVipObj = payload;
    },
    SET_TELESALE_CATEGORY_OBJ (state, payload) {
        state.teleSaleCategoryObj = payload;
    },
    SET_TELESALE_AGENT_LIST (state, payload) {
        state.teleSaleAgentListLog = payload
    },
    SET_TELESALE_SOURCE (state, payload) {
        state.teleSource = payload
    },
    SET_TELESALE_RECORD_OBJ (state, payload) {
        state.teleSaleRecordObj = payload
    },
    SET_TELESALE_RECORD_STATISTIC_OBJ (state, payload) {
        state.teleSaleRecordStatisticObj = payload
    },
    SET_TELESALE_RECORD_FILTER_OBJ(state, payload) {
        state.teleSaleRecordFilterObj = payload
    },
}

const actions = {
    async getTeleSaleList ({ commit }, params = {}) {
        const resp = await api.teleSale.getTeleSaleList(params);
        commit('SET_TELESALE_OBJ', resp.data);
        return resp.data;
    },
    async updateTeleSale ({ commit }, data = {}) {
        const resp = await api.teleSale.updateTeleSale(data);
        return resp.data;
    },
    async getTeleSaleLine ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleLine(params);
        commit('SET_TELESALE_LINE_OBJ', resp.data);
        return resp.data;
    },
    async getTeleSaleLineOwn ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleLineOwn(params);
        commit('SET_TELESALE_LINE_OBJ', resp.data);
        return resp.data;
    },
    async getTeleSaleUser ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleUser(params);
        commit('SET_TELESALE_USER', resp.data);
        return resp.data;
    },
    async getDuplicate ({ commit }, params = {}) {
        const resp  = await api.teleSale.getDuplicate(params);
        commit('SET_TELESALE_OBJ', resp.data);
        return resp.data;
    },
    async updateAssignToUser ({ commit }, data = {}) {
        const resp = await api.teleSale.updateAssignToUser(data);
        return resp.data;
    },
    async deleteTelesales ({ commit }, data = {}) {
        const resp = await api.teleSale.deleteTelesales(data);
        return resp.data;
    },
    async createTeleSale ({ commit }, data = {}) {
        const resp = await api.teleSale.createTeleSale(data);
        return resp.data;
    },
    async updateTeleSaleLine ({ commit }, data = {}) {
        const resp = await api.teleSale.updateTeleSaleLine(data);
        return resp.data;
    },
    async deleteTeleSaleLine ({ commit }, data = {}) {
        const resp = await api.teleSale.deleteTeleSaleLine(data);
        return resp.data;
    },
    async createTeleSaleLine ({ commit }, data = {}) {
        const resp = await api.teleSale.createTeleSaleLine(data);
        return resp.data;
    },
    async importTeleSale ({ commit }, data = {}) {
        const resp = await api.teleSale.importTeleSale(data);
        return resp.data;
    },
    async updateLine  ({ commit }, data = {}) {
        const resp = await api.teleSale.updateLine(data);
        return resp.data;
    },
    async updateAgent  ({ commit }, data = {}) {
        const resp = await api.teleSale.updateAgent(data);
        return resp.data;
    },

    async getTeleSaleAgent ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleAgent(params);
        commit('SET_TELESALE_AGENT_OBJ', resp.data);
        return resp.data;
    },
    async updateTeleSaleAgent ({ commit }, data = {}) {
        const resp = await api.teleSale.updateTeleSaleAgent(data);
        return resp.data;
    },
    async createTeleSaleAgent ({ commit }, data = {}) {
        const resp = await api.teleSale.createTeleSaleAgent(data);
        return resp.data;
    },
    async deleteTeleSaleAgent ({ commit }, data = {}) {
        const resp = await api.teleSale.deleteTeleSaleAgent(data);
        return resp.data;
    },
    async getListAgentFilter ({ commit },data = {}) {
        const resp = await api.teleSale.getListAgentFilter(data);
        commit('SET_TELESALE_AGENT_LIST', resp.data);
        return resp.data;
    },
    async bulkEditAction ({ commit },data = {}) {
        const resp = await api.teleSale.bulkEditAction(data);
        return resp.data;
    },



    async getTeleSaleVip ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleVip(params);
        commit('SET_TELESALE_VIP_OBJ', resp.data);
        return resp.data;
    },
    async updateTeleSaleVip ({ commit }, data = {}) {
        const resp = await api.teleSale.updateTeleSaleVip(data);
        return resp.data;
    },
    async createTeleSaleVip ({ commit }, data = {}) {
        const resp = await api.teleSale.createTeleSaleVip(data);
        return resp.data;
    },
    async deleteTeleSaleVip ({ commit }, data = {}) {
        const resp = await api.teleSale.deleteTeleSaleVip(data);
        return resp.data;
    },


    async getTeleSaleCategory ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleCategory(params);
        commit('SET_TELESALE_CATEGORY_OBJ', resp.data);
        return resp.data;
    },
    async updateTeleSaleCategory ({ commit }, data = {}) {
        const resp = await api.teleSale.updateTeleSaleCategory(data);
        return resp.data;
    },
    async createTeleSaleCategory ({ commit }, data = {}) {
        const resp = await api.teleSale.createTeleSaleCategory(data);
        return resp.data;
    },
    async deleteTeleSaleCategory ({ commit }, data = {}) {
        const resp = await api.teleSale.deleteTeleSaleCategory(data);
        return resp.data;
    },

    async bulkActionUpdate ({ commit }, data = {}) {
        const resp = await api.teleSale.bulkActionUpdate(data);
        return resp.data;
    },


    async getTeleSourceFrom ({ commit }, data = {}) {
        const resp = await api.teleSale.getTeleSourceFrom(data);
        commit('SET_TELESALE_SOURCE', resp.data);
        return resp.data;
    },

    async getVoiceToken({ commit }, data = {}) {
        const resp = await api.teleSale.getVoiceToken(data);
        // commit('SET_VOICE_TOKEN', resp.data);
        return resp.data;
    },

    async saveCallRecord ({ commit }, data = {}) {
        const resp = await api.teleSale.saveCallRecord(data);
        // commit('SET_VOICE_TOKEN', resp.data);
        return resp.data;
    },


    async getTeleSaleRecord ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleRecord(params);
        commit('SET_TELESALE_RECORD_OBJ', resp.data);
        return resp.data;
    },
    async updateTeleSaleRecord ({ commit }, data = {}) {
        const resp = await api.teleSale.updateTeleSaleRecord(data);
        return resp.data;
    },
    async createTeleSaleRecord ({ commit }, data = {}) {
        const resp = await api.teleSale.createTeleSaleRecord(data);
        return resp.data;
    },
    async deleteTeleSaleRecord ({ commit }, data = {}) {
        const resp = await api.teleSale.deleteTeleSaleRecord(data);
        return resp.data;
    },

    async getTeleSaleRecordStatistic ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleRecordStatistic(params);
        commit('SET_TELESALE_RECORD_STATISTIC_OBJ', resp.data);
        return resp.data;
    },

    async getTeleSaleRecordFilter ({ commit }, params = {}) {
        const resp  = await api.teleSale.getTeleSaleRecordFilter(params);
        commit('SET_TELESALE_RECORD_FILTER_OBJ', resp.data);
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
