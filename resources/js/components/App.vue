<template>
    <div>
        <navigation-right :hasMenu="hasMenuRight" :logged="logged" :menus="menusRight"></navigation-right>
        <topbar :logged="logged"
                :hasMenuLeft="hasMenuLeft"
                :hasMenuRight="hasMenuRight"
                :drawerLeft="drawerLeftToggle"
                :drawerRight="drawerRightToggle"></topbar>
        <navigation-left :hasMenu="hasMenuLeft" :logged="logged" :menus="menusLeft"></navigation-left>
        <v-content app>
            <router-view/>
        </v-content>
        <v-footer class="px-2" app>
            <span><a href="https://fontesolutions.com.br" target="_blank">FonteSolutions</a></span>
            <v-spacer></v-spacer>
            <span>&copy; 2019</span>
        </v-footer>
    </div>
</template>
<script>
    export default {
        data: () => ({
            drawerLeft: false,
            drawerRight: false,
            user: null,
            logged: false,
            hasMenuLeft: true,
            hasMenuRight: true,
        }),
        mounted() {
            if (this.$store.getters.getUser && this.$store.getters.getToken) {
                this.logged = true;
                this.user = this.$store.getters.getUser;
            }
            this.hasMenuLeft = this.$store.getters.getHasDrawerLeft;
            this.hasMenuRight = this.$store.getters.getHasDrawerRight;
            this.$store.subscribe((mutation, state) => {
                switch (mutation.type) {
                    case 'setUser':
                        if (state.user !== null) {
                            this.logged = true;
                            this.user = state.user;
                        } else {
                            this.logged = false;
                            this.user = null;
                        }
                        break;
                    case 'setHasDrawerLeft':
                        this.hasMenuLeft = state.hasDrawerLeft;
                        break;
                    case 'setHasDrawerRight':
                        this.hasMenuRight = state.hasDrawerRight;
                        break;
                    case 'clearAll':
                        this.logged = false;
                        this.user = null;
                        break;
                }
            });
        },
        methods: {
            drawerLeftToggle() {
                this.drawerLeft = !this.drawerLeft;
            },
            drawerRightToggle() {
                this.drawerRight = !this.drawerRight;
            }
        },
        props: {
            menusLeft: Array,
            menusRight: Array,
        }
    }
</script>
