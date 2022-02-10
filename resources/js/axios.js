import axios from 'axios';

const axiosInstance = axios.create({
    headers: {
        'accept': 'application/json',
    }
});

axiosInstance.interceptors.request.use(config => {
    config.headers.common.Authorization = `Bearer ${localStorage.getItem('token') ? localStorage.getItem('token') : ''}`;
    return config;
})

export default axiosInstance;
