<template>
    <v-container fluid grid-list-xl>
        <v-toolbar class="mb-2 elevation-4" v-if="hasPageHeader">
            <v-toolbar-title v-if="titulo">{{titulo}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <slot name="toolbar-buttons-left"></slot>
                <v-btn icon flat @click="_getAllItems" :disabled="loading" v-if="hasRefreshBtn">
                    <v-tooltip bottom>
                        <span slot="activator">
                            <v-icon :class="loading ? 'fa-spin': ''">refresh</v-icon>
                        </span>
                        <span>
                            Atualizar dados da listagem
                        </span>
                    </v-tooltip>
                </v-btn>
                <v-btn icon flat @click="_addNew" :disabled="loading" v-if="hasAddBtn">
                    <v-tooltip bottom>
                        <span slot="activator">
                            <v-icon :class="loading ? 'fa-spin': ''">add</v-icon>
                        </span>
                        <span>
                            Adicionar novo item
                        </span>
                    </v-tooltip>
                </v-btn>
                <v-btn icon flat @click="$router.go(-1)" :disabled="loading" v-if="hasBackBtn">
                    <v-tooltip bottom>
                        <span slot="activator">
                            <v-icon>undo</v-icon>
                        </span>
                        <span>
                            Voltar
                        </span>
                    </v-tooltip>
                </v-btn>
                <v-btn icon flat @click="_salvarSelecionados" :disabled="loading" v-if="hasSaveBtn">
                    <v-tooltip bottom>
                        <span slot="activator">
                            <v-icon>save</v-icon>
                        </span>
                        <span>
                            Salvar selecionados
                        </span>
                    </v-tooltip>
                </v-btn>
                <slot name="toolbar-buttons-right"></slot>
            </v-toolbar-items>
        </v-toolbar>
        <v-card v-if="hasFilters">
            <v-card-text>
                <v-form ref="formFilters">
                    <v-layout wrap>
                        <v-flex xs12>
                            <span class="headline">Filtros de Pesquisa</span>
                        </v-flex>
                        <slot name="filtros"></slot>
                        <v-flex xs12 class="text-xs-right">
                            <v-tooltip bottom>
                                <v-btn :loading="loading"
                                       @click="_getAllItems"
                                       color="primary"
                                       slot="activator"
                                       :disabled="loading">
                                    Pesquisar
                                </v-btn>
                                <span>Pesquisar</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <v-btn :loading="loading"
                                       @click="_clearFilters"
                                       slot="activator"
                                       :disabled="loading">
                                    Limpar filtros
                                </v-btn>
                                <span>Limpar Filtros da pesquisa</span>
                            </v-tooltip>
                        </v-flex>
                    </v-layout>
                </v-form>
            </v-card-text>
        </v-card>
        <v-layout row wrap>
            <v-flex>
                <v-data-table no-data-text="Não possui registros"
                              rows-per-page-text="Items por página"
                              :disable-page-reset="true"
                              :select-all="hasSelect"
                              v-model="selected"
                              :hide-actions="!hasPaginator"
                              :pagination.sync="pagination"
                              :total-items="totalItems"
                              :rows-per-page-items="[rowsPerPage,rowsPerPage * 2, rowsPerPage * 4, rowsPerPage * 8, rowsPerPage * 16]"
                              :loading="loading"
                              :items="items"
                              :headers="headers"
                              class="elevation-1">
                    <template slot="headerCell" slot-scope="props">
                        <v-tooltip bottom>
                            <span slot="activator">
                              {{ props.header.text }}
                            </span>
                            <span>
                              {{ props.header.desc }}
                            </span>
                        </v-tooltip>
                    </template>

                    <template slot="items" slot-scope="props">
                        <slot name="datatable" v-bind:item="props"></slot>
                    </template>
                </v-data-table>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    name: 'Listagem',
    props: {
        titulo: {
            type: String,
        },
        headers: {
            type: Array,
            required: true,
        },
        filters: {
            type: Object,
        },
        getAllItems: {
            type: Function,
            required: true,
        },
        salvarSelecionados: {
            type: Function,
        },
        hasPageHeader: {
            type: Boolean,
            default: true,
        },
        hasAddBtn: {
            type: Boolean,
            default: true,
        },
        hasBackBtn: {
            type: Boolean,
            default: false,
        },
        hasSaveBtn: {
            type: Boolean,
            default: false,
        },
        hasRefreshBtn: {
            type: Boolean,
            default: true,
        },
        hasFilters: {
            type: Boolean,
            default: true,
        },
        hasSelect: {
            type: Boolean,
            default: false,
        },
        rowsPerPage: {
            type: Number,
            default: 10,
        },
        hasPaginator: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            loading: this.isLoading(),
            items: [],
            selected: [],
            totalItems: 0,
            pagination: {
                page: 1,
                rowsPerPage: this.rowsPerPage,
                totalItems: 0,
            },
            filtersDefault: {},
        }
    },
    watch: {
        pagination: {
            handler() {
                this._getAllItems()
            },
            deep: true,
        },
    },
    methods: {
        isLoading() {
            return this.$store.getters.isLoading
        },
        _getAllItems() {
            this.$confirm.loading(true)
            const { sortBy, descending, page, rowsPerPage } = this.pagination
            let { filters, hasFilters, getAllItems } = this.$props
            if (!hasFilters) {
                filters = null
            }
            getAllItems({ sortBy, descending, page, rowsPerPage, filters }).then(res => {
                this.$confirm.loading(false)
                this.items = res.data
                this.totalItems = res.total
            }).catch((message) => {
                this.$confirm.loading(false)
                this.items = []
                this.totalItems = 0
                // this.$toasted.error('Erro ao tentar recuperar dados', {
                //     duration: 5000,
                //     fitToScreen: true,
                //     theme: 'bubble',
                //     icon: {
                //         name: 'check',
                //         after: false,
                //     },
                // })
            })
        },
        refresh() {
            this._getAllItems()
        },
        _resetForm(filters) {
            for (let i in filters) {
                if (typeof filters[i] === 'object') {
                    this._resetForm(filters[i])
                } else {
                    filters[i] = ''
                }
            }
        },
        _clearFilters() {
            this._resetForm(this.filters)
            this._getAllItems()
        },
        _addNew() {
            this.$emit('addNew', {})
        },
        addSelected(item) {
            let temItem = false
            for (let _item of this.selected) {
                if (temItem) {
                    return
                }
                if (item.id === _item.id) {
                    temItem = true
                }
            }
            if (!temItem) {
                this.selected.push(item)
            }
        },
        _salvarSelecionados() {
            const { sortBy, descending, page, rowsPerPage } = this.pagination
            const { filters, salvarSelecionados } = this.$props
            if (!salvarSelecionados) {
                throw new Error('Deve definir o bind salvarSelecionados no component v-listagem')
            }
            this.$confirm.loading(true)
            const selected = this.selected.map((item) => {
                return item.id
            })
            salvarSelecionados({
                sortBy,
                descending,
                page,
                rowsPerPage,
                filters,
                selected,
            }).then(() => {
                this.$confirm.loading(false)
                this.refresh()
            }).catch(() => {
                this.$confirm.loading(false)
                this.items = []
                this.totalItems = 0
                this.$toasted.error('Erro ao tentar salvar dados', {
                    duration: 5000,
                    fitToScreen: true,
                    theme: 'bubble',
                    icon: {
                        name: 'check',
                        after: false,
                    },
                })
            })
        },
    },
}
</script>

<style scoped>

</style>
