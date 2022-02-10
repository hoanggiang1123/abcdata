const baseUrl = '/api/plugin-repo';

export default axios => ({
    getPluginRepo (params = {}) {
        return axios.get(baseUrl, { params });
    },
    createPluginRepo(data = {}) {
        return axios.post(baseUrl, data);
    },
    uploadPluginFile(data) {
        return axios.post(baseUrl + '/upload', data);
    },
    updatePluginRepo (data) {
        return axios.put(baseUrl + '/' + data.id, data);
    },
});
