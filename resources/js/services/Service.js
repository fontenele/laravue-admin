import axios from 'axios'
import store from './../store'
import router from './../router'

/**
 * Abstract Service Class
 * @package services
 */
export default class Service {
    /**
     * Vuex
     * @type {Store<{token: null, user: null}>}
     */
    $store = store;
    /**
     * Is Loading?
     * @type {boolean}
     */
    isLoading = false;
    /**
     * Resource RESTful pathname
     * @type {string}
     */
    resourceName = '';

    constructor() {
        this.$store.subscribe((mutation, state) => {
            switch (mutation.type) {
                case 'setIsLoading':
                    this.isLoading = state.loading
                    break
            }
        });
    }

    /**
     * Get HTTP Client
     * @param token
     * @returns {AxiosInstance}
     */
    http(token) {
        token = token ? token : this.$store.getters.getToken;
        const headers = {
            'Content-Type': 'application/json, application/x-www-form-urlencoded',
            'Accept': 'application/json, text/plain, application/x-www-form-urlencoded',
            Authorization: token ? 'Bearer ' + token : '',
        };
        const client = axios.create({
            baseURL: process.env.VUE_APP_API_HOST,
            headers,
        });
        client.interceptors.response.use(
            (response) => {
                return response
            },
            (error) => {
                // @TODO verificar erros e preparar resposta
                // @TODO tradutor
                switch(true) {
                    case error.response.data.error === 'User is not logged in.':
                        router.app.$toasted.error('Você não está logado.', {
                            duration: 5000,
                            fitToScreen: true,
                            theme: 'bubble',
                            icon: {
                                name: 'close',
                                after: false,
                            },
                        });
                        router.replace({name: 'logout'});
                        error.response.data.code = -1;
                        return Promise.reject(error.response.data);
                    default:
                        return Promise.reject(error.response.data);
                }
            },
        );
        return client;
    }

    /**
     * Get all Items
     * @param filter
     * @returns {Promise<any>}
     */
    all(filter) {
        return new Promise((resolve, reject) => {
            console.log('all', filter);
            this.http()
                .get('/' + this.resourceName, { params: filter })
                .then((res) => {
                    if (res.data.error) {
                        reject(res.data.error);
                    }
                    resolve(res.data);
                })
                .catch((message) => {
                    reject(message);
                });
        });
    }

    combo(filter) {
        return new Promise((resolve, reject) => {
            this.http()
                .get('/' + this.resourceName + '/combo' , { params: filter })
                .then((res) => {
                    if (res.data.error) {
                        reject(res.data.error);
                    }
                    resolve(res.data);
                })
                .catch((message) => {
                    reject(message);
                });
        });
    }

    /**
     * Save Item
     * @param item
     * @returns {Promise<any>}
     */
    save(item) {
        if (item.id) {
            return this.update(item);
        }
        return this.create(item);
    }

    /**
     * Create new Item
     * @param item
     * @returns {Promise<any>}
     */
    create(item) {
        return new Promise((resolve, reject) => {
            this.http()
                .post('/' + this.resourceName, item)
                .then((res) => {
                    if (res.data.error) {
                        reject(res.data.error);
                    }
                    resolve(res.data);
                })
                .catch((message) => {
                    reject(message);
                });
        });
    }

    /**
     * Update Item
     * @param item
     * @returns {Promise<any>}
     */
    update(item) {
        return new Promise((resolve, reject) => {
            this.http()
                .put('/' + this.resourceName + '/' + item.id, item)
                .then((res) => {
                    if (res.data.error) {
                        reject(res.data.error);
                    }
                    resolve(res.data);
                })
                .catch((message) => {
                    reject(message);
                })
        });
    }

    /**
     * Get Item info
     * @param id
     * @returns {Promise<any>}
     */
    show(id) {
        return new Promise((resolve, reject) => {
            this.http()
                .get('/' + this.resourceName + '/' + id)
                .then((res) => {
                    if (res.data.error) {
                        reject(res.data.error)
                    }
                    resolve(res.data)
                })
                .catch((message) => {
                    reject(message)
                })
        })
    }

    /**
     * Delete Item
     * @param item
     * @returns {Promise<any>}
     */
    destroy(item) {
        return new Promise((resolve, reject) => {
            this.http()
                .delete('/' + this.resourceName + '/' + item.id)
                .then((res) => {
                    resolve(res.data)
                })
                .catch((message) => {
                    reject(message)
                })
        })
    }
}
