import axios from 'axios';

const customAxios = axios.create({
    baseURL: 'http://localhost:8000',
    headers:{
        "Content-Type": "application/json",
        "accept": 'application/json',
    }
});

customAxios.interceptors.request.use(
    (config) => {
        // Modify config before sending the request
        return config;
    },
    (error) => {
        // Handle request error
        return Promise.reject(error);
    }
);

customAxios.interceptors.response.use(
    (response) => {
        // Modify response data before passing it to the calling function
        return response.data;
    },
    (error) => {
        // Handle response error
        return Promise.reject(error);
    }
);

const api = {
    get: customAxios.get,
    post: customAxios.post,
}

export default api;