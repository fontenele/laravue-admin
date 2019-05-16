<template>
    <v-toolbar fixed
               app
               clipped-left
               clipped-right>
        <v-toolbar-side-icon v-if="hasMenuLeft" @click.stop="drawerLeft"></v-toolbar-side-icon>
        <v-toolbar-title>Laravue</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-side-icon v-if="hasMenuRight" @click.stop="drawerRight"></v-toolbar-side-icon>
    </v-toolbar>
</template>

<script>
    export default {
        name: 'Topbar',
        data: () => ({}),
        props: {
            drawerLeft: {
                type: Function
            },
            drawerRight: {
                type: Function
            },
            logged: {
                type: Boolean,
            },
            hasMenuLeft: {
                type: Boolean,
            },
            hasMenuRight: {
                type: Boolean,
            },
        }
    }
</script>

<style scoped>

</style>
