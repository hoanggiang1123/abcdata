const baseUrl = '/api/category';
export default axios => ({
    getCategoryList (params = {}, config = {}) {
        return axios.get(baseUrl, { params });
    },
    updateCategory (data) {
        return axios.put(baseUrl + '/' + data.id, data);
    },
    createCategory (data) {
        return axios.post(baseUrl, data);
    },
    getCategoryFilter (data = {}) {
        return axios.get(baseUrl + '/filter');
    }
});
