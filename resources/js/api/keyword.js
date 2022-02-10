const baseUrl = '/api/keyword';
const keyCodeUrl = '/api/keyword/keycode';
const satiliteDomainUrl = '/api/satilite-domain';
const trackingUrl = '/api/keyword-tracking'

export default axios => ({
    getListKeywords(params = {}, config = {}) {
        return axios.get(baseUrl, { params });
    },
    updateKeyword(data = {}) {
        return axios.put(baseUrl + '/' + data.id, data);
    },
    deleteKeyword (data = {}) {
        return axios.post(baseUrl + '/deletes', data);
    },
    createKeyword (data = {}) {
        return axios.post(baseUrl, data);
    },
    createMany  (data = {}) {
        return axios.post(baseUrl + '/create', data);
    },
    getListSatiliteDomain(params = {}, config = {}) {
        return axios.get(satiliteDomainUrl, { params });
    },
    createSatiliteDomain (data = {}) {
        return axios.post(satiliteDomainUrl, data);
    },
    updateSatiliteDomain(data = {}) {
        return axios.put(satiliteDomainUrl + '/' + data.id, data);
    },
    deleteSatiliteDomain (data = {}) {
        return axios.post(satiliteDomainUrl + '/deletes', data);
    },
    createManySatiliteDomain  (data = {}) {
        return axios.post(satiliteDomainUrl + '/create', data);
    },
    getKeyCode (params = {}) {
        return axios.get(keyCodeUrl, { params });
    },
    updateKeyCode (data = {}) {
        return axios.put(keyCodeUrl + '/' + data.id, data);
    },
    getTracking (params = {}) {
        return axios.get(trackingUrl, { params });
    },
    getFilter (params = {}) {
        return axios.get(trackingUrl + '/filter', { params });
    },
    getClickChart (params = {}) {
        return axios.get(trackingUrl + '/click-chart', { params });
    },
});
