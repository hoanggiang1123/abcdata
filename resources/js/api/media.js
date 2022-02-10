const baseUrl = '/api/media';

export default axios => ({
    uploadMedia(data, config) {
        return axios.post(baseUrl + '/', data, config);
    },
    getMediaList (params = {}) {
        return axios.get(baseUrl, { params });
    },
    editMedia(data, config) {
        return axios.put(baseUrl + '/' + data.id, data);
    },
    deleteMedia (data) {
        return axios.delete(baseUrl + '/' + data.id);
    }
});
