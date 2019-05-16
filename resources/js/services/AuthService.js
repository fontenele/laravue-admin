import Service from './Service';
export default class AuthService extends Service {
    /**
     * Autenticar com usuário e senha
     * @param email
     * @param password
     * @returns {Promise<any>}
     */
    auth(email, password) {
        return new Promise((resolve, reject) => {
            this.http()
                .post('/api/login', { email, password })
                .then((res) => {
                    this.$store.commit('setUser', {
                        user: res.data.user,
                    });
                    this.$store.commit('setToken', {
                        token: res.data.token,
                    });
                    this.$store.commit('setPermissions', {
                        permissions: res.data.permissions,
                    });
                    this.$store.commit('setHasDrawerLeft', {
                        hasDrawerLeft: true,
                    });
                    this.$store.commit('setHasDrawerRight', {
                        hasDrawerRight: false,
                    });
                    resolve(res.data)
                }).catch((message) => reject(message));
        });
    }

    logout() {
        return new Promise((resolve, reject) => {
            this.http()
                .get('/api/logout')
                .then((res) => {
                    resolve(res.data)
                }).catch((message) => reject(message));
        });
    }

    hasPermission(permission) {
        const permissions = this.$store.getters.getPermissions;
        for (let i in permissions) {
            if(permissions[i] === permission) {
                return true;
            }
        }
        return false;
    }
}
