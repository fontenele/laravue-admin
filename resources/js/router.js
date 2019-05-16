import Vue from 'vue';
import Router from 'vue-router';
import store from './store';
import AuthService from "./services/AuthService";

Vue.use(Router);

/**
 * Routes
 * @type {VueRouter}
 */
const router = new Router({
    routes: [
        {
            path: '*',
            redirect: '/login',
        },
        {
            path: '/',
            redirect: '/login',
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('./components/auth/Login.vue'),
            beforeEnter: (to, from, next) => {
                const user = store.getters.getUser;
                const token = store.getters.getToken;
                if (user && token) {
                    next('/home');
                } else {
                    next();
                }
            },
        },
        {
            path: '/home',
            name: 'home',
            component: () => import('./components/Home.vue'),
            meta: {
                requiresAuth: true,
                permissao: 'DASHBOARD',
            },
        },
        {
            path: '/users',
            name: 'users',
            component: () => import('./components/users/ListUsers.vue'),
            meta: {
                requiresAuth: true,
                permissao: 'USERS',
            },
        },
        // {
        //     path: '/usuario/:id?/editar',
        //     name: 'editarUsuario',
        //     component: () => import('./views/Users/EditUser.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'usuario.editar',
        //     },
        // },
        // {
        //     path: '/usuario/:id/roles',
        //     name: 'usuarioRoles',
        //     component: () => import('./views/Users/UserRoles.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'usuario.editar',
        //     },
        // },
        // {
        //     path: '/roles',
        //     name: 'roles',
        //     component: () => import('./views/Roles/ListRoles.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'role.listar',
        //     },
        // },
        // {
        //     path: '/roles/:id?/editar',
        //     name: 'editarRole',
        //     component: () => import('./views/Roles/EditRole.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'role.editar',
        //     },
        // },
        // {
        //     path: '/roles/:id/permissoes',
        //     name: 'verPermissoesDaRole',
        //     component: () => import('./views/Roles/ListEditPermissions.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'role.editar',
        //     },
        // },
        // {
        //     path: '/clientes',
        //     name: 'clientes',
        //     component: () => import('./views/Clientes/ListClientes.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'cliente.listar',
        //     },
        // },
        // {
        //     path: '/clientes/:id?/editar',
        //     name: 'editarCliente',
        //     component: () => import('./views/Clientes/EditClientes.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'cliente.editar',
        //     },
        // },
        // {
        //     path: '/clientes/:id/usuarios',
        //     name: 'clienteUsuarios',
        //     component: () => import('./views/Clientes/ClientUsers.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'cliente.editar',
        //     },
        // },
        // {
        //     path: '/projetos',
        //     name: 'projetos',
        //     component: () => import('./views/Projetos/ListProjetos.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'projeto.listar',
        //     },
        // },
        // {
        //     path: '/projetos/:id?/editar',
        //     name: 'editarProjeto',
        //     component: () => import('./views/Projetos/EditProjetos.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'projeto.editar',
        //     },
        // },
        //
        //
        //
        //
        //
        // {
        //     path: '/profile',
        //     name: 'profile',
        //     component: () => import('./views/Profile.vue'),
        //     meta: {
        //         requiresAuth: true,
        //     },
        // },
        // {
        //     path: '/dashboard',
        //     name: 'dashboard',
        //     component: () => import('./views/Dashboard/Dashboard.vue'),
        //     meta: {
        //         requiresAuth: true,
        //         permissao: 'dashboard.view',
        //     },
        // },
        {
            path: '/logout',
            name: 'logout',
            beforeEnter: (to, from, next) => {
                const authService = new AuthService();
                authService.logout();
                store.commit('clearAll');
                next('/login');
            },
        },
    ],
});

/**
 * AuthMiddleware
 */
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const permissao = to.meta.permissao;
    if (requiresAuth) {
        if (!store.getters.getToken || !store.getters.getUser) {
            next('/login');
            return;
        } else if(permissao) {
            const service = new AuthService();
            if (!service.hasPermission(permissao)) {
                if (service.hasPermission('DASHBOARD')) {
                    next('/home');
                    return;
                }
                next('/');
                return;
            }
        }
        next();
    } else {
        next();
    }
});

export default router
