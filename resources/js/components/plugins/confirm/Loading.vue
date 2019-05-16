<template>
    <v-dialog v-model="dialog"
              persistent
              width="300">
        <v-card :color="bgColor"
                dark>
            <v-card-text>
                {{loadingText}}
                <v-progress-linear indeterminate
                                   :color="progressColor"
                                   class="mb-0"
                ></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'Loading',
    props: {
        loadingText: {
            type: String,
            default: 'Carregando...',
        },
        bgColor: {
            type: String,
            default: 'primary'
        },
        progressColor: {
            type: String,
            default: 'white'
        },
    },
    data () {
        return {
            dialog: true
        }
    },
}
</script>

<style scoped>

</style>
