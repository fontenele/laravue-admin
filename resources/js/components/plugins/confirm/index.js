import Confirm from './Confirm.vue'
import Loading from './Loading'
import store from './../../../store'

const VuetifyConfirm = {
    install(Vue, options) {

        Vue.prototype.$confirm = {
            loadingCmp: null,
            options: options || {},
            show: (message, options = {}) => {
                options.message = message
                return new Promise(resolve => {
                    const cmp = new Vue(Object.assign(Confirm, {
                        destroyed: (c) => {
                            document.body.removeChild(cmp.$el)
                            resolve(cmp.value)
                        },
                    }))
                    Object.assign(cmp, Vue.prototype.$confirm.options || {}, options)
                    document.body.appendChild(cmp.$mount().$el)
                })
            },
            loading: (loadingStatus) => {
                if (loadingStatus) {
                    if (store.getters.isLoading) {
                        return
                    }
                    store.commit('setIsLoading', {loading: true})
                    this.loadingCmp = new Vue(Object.assign(Loading, {
                        destroyed: (c) => {
                            document.body.removeChild(this.loadingCmp.$el)
                        },
                        mounted() {
                            store.subscribe((mutation, state) => {
                                switch (mutation.type) {
                                    case 'setIsLoading':
                                        if (!state.loading) {
                                            this.$destroy()
                                        }
                                        break
                                }
                            })
                        }
                    }))
                    Object.assign(this.loadingCmp, Vue.prototype.$confirm.options || {}, options)
                    document.body.appendChild(this.loadingCmp.$mount().$el)
                } else {
                    store.commit('setIsLoading', {loading: false})
                }
            },
        }

    },
}

export default VuetifyConfirm
