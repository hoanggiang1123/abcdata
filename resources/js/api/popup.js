const baseUrl = '/api/popup';

export default axios => ({
    getListPopup(params = {}, config = {}) {
        return axios.get(baseUrl, { params });
    },
    updatePopup(data = {}) {
        return axios.put(baseUrl + '/' + data.id, data);
    },
    deletePopup (data = {}) {
        return axios.post(baseUrl + '/deletes', data);
    },
    createPopup (data = {}) {
        return axios.post(baseUrl, data);
    },
    createMany  (data = {}) {
        return axios.post(baseUrl + '/create', data);
    },
    updateAllPopup(data = {}) {
        return axios.post(baseUrl + '/update-all', data);
    }
});
