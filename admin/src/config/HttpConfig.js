import Axios from 'axios';
import store from '../store';
import Router from '../router';

const HttpConfig = function(token = '') {

    let axios = Axios.create({
        baseURL: 'http://127.0.0.1:8000/api', // SERVIDOR LOCAL HOST
         //baseURL: 'https://zumba.lugarcreativo.com.mx/service/public/api/',         // SERVIDOR DEMO1
        // baseURL: 'https://demof.lugarcreativo.mx/services/public/api/',            // SERVIDOR DEMO2
        // baseURL: 'https://',                                                       // SERVIDOR DE PRODUCCIÓN

        headers: {
            'Authorization': `Bearer ${token}`
        }
    });

    axios.interceptors.response.use(
        function(response) {
            return response;
        },
        function(error) {

            if (error.response.status === 403) {

                store.dispatch("logout");
                Router.push({ name: "login" });
                console.log('Error Of Token :: Config');
            } else {
                return Promise.reject(error);
            }

        }
    );

    return axios;
}

export default HttpConfig;
