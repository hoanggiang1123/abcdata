const baseUrl = '/api/telesales';

const lineUrl = baseUrl + '/lines';

const userUrl = baseUrl + '/users';

const duplicateUrl = baseUrl + '/duplicates';

const updateAssignUrl = baseUrl + '/update-assign'
const updateLineUrl = baseUrl + '/update-line'
const updateAgentUrl = baseUrl + '/update-agent'

const deleteTeleUrl = baseUrl + '/delete';

const deleteTeleLineUrl = lineUrl + '/delete';

const importUrl = baseUrl + '/import';
const teleLineOwnUrl = baseUrl + '/tele-line'

const teleSaleAgentUrl = '/api/telesaleagent';

const teleSaleAgentList = baseUrl + '/agents';

const teleSaleBulkEdit = baseUrl + '/bulk-edit';

const teleSaleVipUrl = '/api/telesalevip';

const teleSaleCategoryUrl = '/api/telesalecategory';

const updateBulkActionUrl = baseUrl + '/bulk-action';

const teleSourceFromUrl = baseUrl + '/sources';

const voiceTokenUrl = '/api/calling/token';

const voiceSaveUrl = '/api/calling/save';

const teleSaleRecordUrl = '/api/telesalerecord';

const teleSaleRecordStatisticUrl = '/api/telesalerecord/statistic';

const teleSaleRecordFilterUrl = '/api/telesalerecord/filter';

export default axios => ({
    getTeleSaleList(params = {}, config = {}) {
        return axios.get(baseUrl, { params });
    },
    updateTeleSale(data = {}) {
        return axios.put(baseUrl + '/' + data.id, data);
    },
    getTeleSaleLine (params = {}, config = {}) {
        return axios.get(lineUrl, { params });
    },
    getTeleSaleUser (params = {}, config = {}) {
        return axios.get(userUrl, { params });
    },
    getDuplicate(params = {}, config = {}) {
        return axios.get(duplicateUrl, { params });
    },
    updateAssignToUser (data = {}) {
        return axios.post(updateAssignUrl, data);
    },
    deleteTelesales (data = {}) {
        return axios.post(deleteTeleUrl, data);
    },
    createTeleSale (data = {}) {
        return axios.post(baseUrl, data);
    },
    updateTeleSaleLine (data = {}) {
        return axios.put(lineUrl + '/' + data.id, data);
    },
    deleteTeleSaleLine (data = {}) {
        return axios.post(deleteTeleLineUrl, data);
    },
    createTeleSaleLine (data = {}) {
        return axios.post(lineUrl, data);
    },
    importTeleSale (data = {}) {
        return axios.post(importUrl, data);
    },
    getTeleSaleLineOwn (params = {}, config = {}) {
        return axios.get(teleLineOwnUrl, { params });
    },

    updateLine (data = {}) {
        return axios.post(updateLineUrl, data);
    },
    updateAgent(data = {}) {
        return axios.post(updateAgentUrl, data);
    },


    getTeleSaleAgent (params = {}, config = {}) {
        return axios.get(teleSaleAgentUrl, { params });
    },
    updateTeleSaleAgent (data = {}) {
        return axios.put(teleSaleAgentUrl + '/' + data.id, data);
    },
    deleteTeleSaleAgent (data = {}) {
        return axios.post(teleSaleAgentUrl + '/delete', data);
    },
    createTeleSaleAgent (data = {}) {
        return axios.post(teleSaleAgentUrl, data);
    },

    getListAgentFilter (data = {}) {
        return axios.post(teleSaleAgentList, data);
    },
    bulkEditAction  (data = {}) {
        return axios.post(teleSaleBulkEdit, data);
    },


    getTeleSaleVip (params = {}, config = {}) {
        return axios.get(teleSaleVipUrl, { params });
    },
    updateTeleSaleVip (data = {}) {
        return axios.put(teleSaleVipUrl + '/' + data.id, data);
    },
    deleteTeleSaleVip (data = {}) {
        return axios.post(teleSaleVipUrl + '/delete', data);
    },
    createTeleSaleVip (data = {}) {
        return axios.post(teleSaleVipUrl, data);
    },


    getTeleSaleCategory (params = {}, config = {}) {
        return axios.get(teleSaleCategoryUrl, { params });
    },
    updateTeleSaleCategory (data = {}) {
        return axios.put(teleSaleCategoryUrl + '/' + data.id, data);
    },
    deleteTeleSaleCategory (data = {}) {
        return axios.post(teleSaleCategoryUrl + '/delete', data);
    },
    createTeleSaleCategory (data = {}) {
        return axios.post(teleSaleCategoryUrl, data);
    },


    bulkActionUpdate (data = {}) {
        return axios.post(updateBulkActionUrl, data);
    },

    getTeleSourceFrom (params = {}) {
        return axios.get(teleSourceFromUrl, { params })
    },

    getVoiceToken (params = {}) {
        return axios.get(voiceTokenUrl, { params })
    },

    saveCallRecord (data = {}) {
        return axios.post(voiceSaveUrl, data);
    },


    getTeleSaleRecord (params = {}, config = {}) {
        return axios.get(teleSaleRecordUrl, { params });
    },
    updateTeleSaleRecord (data = {}) {
        return axios.put(teleSaleRecordUrl + '/' + data.id, data);
    },
    deleteTeleSaleRecord (data = {}) {
        return axios.post(teleSaleRecordUrl + '/delete', data);
    },
    createTeleSaleRecord (data = {}) {
        return axios.post(teleSaleRecordUrl, data);
    },

    getTeleSaleRecordStatistic (params = {}, config = {}) {
        return axios.get(teleSaleRecordStatisticUrl, { params });
    },

    getTeleSaleRecordFilter (params = {}, config = {}) {
        return axios.get(teleSaleRecordFilterUrl, { params });
    },

});
