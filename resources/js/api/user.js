const baseUrl = '/api/user';
export default axios => ({
    userLogin(data = {}, config = {}) {
        return axios.post(baseUrl + '/login', data, config);
    },
    checkLogin (data = {}, config = {}) {
        return axios.get(baseUrl + '/me');
    },
    userLogout (data = {}, config = {}) {
        return axios.post(baseUrl + '/logout');
    },
    refreshTokens(data = {}) {
        return axios.get(baseUrl + '/refresh', { params: data });
    },
    getListUser (params = {}) {
        return axios.get(baseUrl, params);
    },
    updateUser (data = {}) {
        return axios.put(baseUrl + '/' + data.id, data);
    },
    createUser (data = {}) {
        return axios.post(baseUrl, data);
    }

});
