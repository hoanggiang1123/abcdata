const baseUrl = '/api/fbstat';

const leagues = baseUrl + '/leagues';

export default axios => ({
    getLeagues(params = {}, config = {}) {
        return axios.get(leagues, { params });
    },
    updateLeague(data = {}) {
        return axios.put(leagues + '/' + data.id, data);
    },
    deleteLeagues(data = {}) {
        return axios.post(leagues + '-delete', data);
    },
    createLeague (data = {}) {
        return axios.post(leagues, data);
    },
});
