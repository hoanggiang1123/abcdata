const baseUrl = '/api/post';

export default axios => ({
    getListPost(params = {}, config = {}) {
        return axios.get(baseUrl, { params });
    },
    updatePost(data = {}) {
        return axios.put(baseUrl + '/' + data.id, data);
    },
    deletePost (data = {}) {
        return axios.post(baseUrl + '/deletes', data);
    },
    createPost (data = {}) {
        return axios.post(baseUrl, data);
    },
    createMany  (data = {}) {
        return axios.post(baseUrl + '/create', data);
    },
    updateAllPostContent (data = {}) {
        return axios.post(baseUrl + '/update-all-post-content', data);
    }
});
