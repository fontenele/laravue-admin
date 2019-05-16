<template>
    <v-navigation-drawer v-if="hasMenu"
                         v-model="drawer"
                         fixed
                         dark
                         clipped
                         app>
        <v-list dense>
            <div v-for="(menu, j) in items" :key="j">
                <v-toolbar flat>
                    <v-list>
                        <v-list-tile>
                            <v-list-tile-title class="title">
                                {{menu.text}}
                            </v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-toolbar>
                <div v-for="(item, i) in menu.items" :key="i">

                    <div v-if="hasPermission(item.permission)">
                        <v-list-tile
                            v-if="item.show_if_logged === true ? logged : (item.show_if_logged === false ? !logged : true)"
                            :key="i"
                            :to="item.route">
                            <v-list-tile-action>
                                <v-tooltip right>
                                    <v-icon slot="activator">{{ item.icon }}</v-icon>
                                    <span>{{item.text}}</span>
                                </v-tooltip>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title class="grey--text">{{ item.text }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </div>
                </div>
            </div>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import AuthService from "../../services/AuthService";

    const authService = new AuthService();

    export default {
        name: 'NavigationLeft',
        data: () => ({
            items: []
        }),
        mounted() {
            this.items = this.menus;
        },
        computed: {
            drawer: {
                get: function () {
                    return this.$parent.drawerLeft;
                },
                set: function (val) {
                    this.$parent.drawerLeft = val;
                },
            }
        },
        methods: {
            hasPermission(permission) {
                if (!permission) {
                    return true;
                }
                return authService.hasPermission(permission);
            }
        },
        props: {
            hasMenu: {
                type: Boolean
            },
            logged: {
                type: Boolean
            },
            menus: {
                type: Array
            },
        }
    }
</script>

<style scoped>

</style>
