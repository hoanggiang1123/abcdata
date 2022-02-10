const baseUrl = '/api/';

const link = baseUrl + 'link';
const linkDirectory = baseUrl + 'link-directory';
const linkEmbeded = baseUrl + 'link-embed';

export default axios => ({
    getLinkList (params = {}, config = {}) {
        return axios.get(link, { params });
    },

    updateLink (data = {}) {
        return axios.put(link + '/' + data.id, data);
    },

    createLink (data = {}) {
        return axios.post(link, data);
    },
    createManyLink (data = {}) {
        return axios.post(link + '/create', data);
    },
    getLinkDetail(id) {
        return axios.get(link + '/' + id);
    },
    uploadImage (data) {
        return axios.post(link + '/upload-image', data);
    },

    getLinkHit (params = {}) {
        return axios.get(link + '/hit/' + params.id, { params });
    },
    getLinkHitList(params = {}) {
        return axios.get(link + '/hit', { params });
    },
    getLinkHitFilter() {
        return axios.get(link + '/hit/filter');
    },
    //directory
    getCategoryList (params = {}, config = {}) {
        return axios.get(linkDirectory, { params });
    },
    updateCategory (data) {
        return axios.put(linkDirectory + '/' + data.id, data);
    },
    createCategory (data) {
        return axios.post(linkDirectory, data);
    },
    createEmbedLink (data) {
        return axios.post(linkEmbeded, data);
    },
    getLinkEmbedList (data) {
        return axios.get(linkEmbeded, data);
    },
    getLinkEmbedListTrash (data) {
        return axios.get(linkEmbeded + '/trash', data);
    },
    updateLinkEmbed (data) {
        return axios.put(linkEmbeded + '/' + data.id, data);
    },
    deleteLinkEmbed (id) {
        return axios.delete(linkEmbeded + '/' + id);
    },
    restoreEmbedLink (id) {
        return axios.put(linkEmbeded + '/trash/' + id);
    },
    deleteLinkEmbedPermanent (id) {
        return axios.delete(linkEmbeded + '/trash/' + id);
    },
    getLinkFilter (params) {
        return axios.get(link + '/filter', { params });
    },
    getCategoryFilter (data = {}) {
        return axios.get(linkDirectory + '/filter');
    },
    getChartClick (params = {}) {
        return axios.get(link + '/hit/click-chart', { params });
    },
    deleteLinkBanner (data = {}) {
        return axios.delete(link + '/' + data.id);
    },
    deleteLinkDirectoryBanner (data = {}) {
        return axios.delete(linkDirectory + '/' + data.id);
    },
});
