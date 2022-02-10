const baseUrl = '/api';
const enterLink = baseUrl + '/enter-link';
const folderLink = baseUrl + '/folder';

const filter = enterLink + '/filter';
const folderFilter = folderLink + '/filter';
const enterLinkHit = enterLink + '/hit';

export default axios => ({
    getEnterLinkList (params = {}, config = {}) {
        return axios.get(enterLink, { params });
    },
    getFolderLinkList (params = {}, config = {}) {
        return axios.get(folderLink, { params });
    },
    getEnterLinkFilter (params = {}) {
        return axios.get(filter, {params});
    },
    updateEnterLink (data = {}) {
        return axios.put(enterLink + '/' + data.id, data);
    },
    createEnterLink (data = {}) {
        return axios.post(enterLink, data);
    },
    createManyEnterLink (data = {}) {
        return axios.post(enterLink + '/create', data);
    },
    getFolderFilter (params = {}) {
        return axios.get(folderFilter, { params });
    },
    updateFolder (data = {}) {
        return axios.put(folderLink + '/' + data.id, data);
    },
    createFolder (data = {}) {
        return axios.post(folderLink, data);
    },
    getEnterLinkDetail (id) {
        return axios.get(enterLink + '/' + id);
    },
    getEnterLinkHit (params = {}) {
        return axios.get(enterLinkHit + '/' + params.id, { params });
    },
    updateInitVoteCount (data = {}) {
        return axios.post(enterLink + '/edit-vote', data);
    },
    deleteManyLink (data = {}) {
        return axios.post(enterLink + '/delete-many', data);
    },
    deleteLink (id) {
        return axios.delete(enterLink + '/' + id);
    },
    deleteFolder (data = {}) {
        return axios.post(folderLink + '/deletes', data);
    }

});
