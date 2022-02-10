const baseUrl = '/api/role-permision';

const roleUrl = baseUrl + '/roles';

const permisionUrl = baseUrl + '/permisions';

export default axios => ({
    getListRole (params = {}) {
        return axios.get(roleUrl, { params });
    },
    getListPermision (params = {}) {
        return axios.get(permisionUrl, { params });
    },
    createRoleAndPermision (data = {}) {
        return axios.post(baseUrl, data);
    },
    updateRoleAndPermision (data = {}) {
        return axios.put(baseUrl + '/' + data.id, data);
    }
});
