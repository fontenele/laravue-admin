<template>
    <listagem titulo="Usuários"
              ref="vListagem"
              :headers="headers"
              :filters="filters"
              @addNew="createItem"
              :get-all-items="getAllItems">
        <template slot="filtros">
            <v-flex xs12 md4>
                <v-text-field box :disabled="this.isLoading()" v-model="filters.name" label="Nome"></v-text-field>
            </v-flex>
            <v-flex xs12 md4>
                <v-text-field box :disabled="this.isLoading()" v-model="filters.email" label="E-mail"></v-text-field>
            </v-flex>
            <v-flex xs12 md4>
                <v-select label="Status"
                          :items="statusItems"
                          v-model="filters.status"
                          box
                          item-text="text"
                          item-value="label">
                </v-select>
            </v-flex>
        </template>
        <template slot="datatable" slot-scope="props">
            <tr>
                <td>{{props.item.item.name}}</td>
                <td>{{props.item.item.email}}</td>
                <td class="text-xs-center">
<!--                    <v-tooltip bottom v-if="props.item.item.status">-->
<!--                        <v-icon slot="activator"-->
<!--                                small-->
<!--                                color="success">-->
<!--                            fa-check-circle-o-->
<!--                        </v-icon>-->
<!--                        <span>Ativo</span>-->
<!--                    </v-tooltip>-->
<!--                    <v-tooltip bottom v-if="!props.item.item.status">-->
<!--                        <v-icon slot="activator"-->
<!--                                small-->
<!--                                color="red">-->
<!--                            fa-circle-o-->
<!--                        </v-icon>-->
<!--                        <span>Inativo</span>-->
<!--                    </v-tooltip>-->
                </td>
                <td class="text-xs-center">
                    <v-tooltip bottom>
                        <v-icon slot="activator"
                                small
                                :disabled="loading"
                                class="mr-2"
                                @click="editItem(props.item.item)">
                            edit
                        </v-icon>
                        <span>Editar Usuário</span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <v-icon slot="activator"
                                small
                                :disabled="loading"
                                @click="deleteItem(props.item.item)">
                            delete
                        </v-icon>
                        <span>Excluir Usuário</span>
                    </v-tooltip>
                </td>
            </tr>
        </template>
    </listagem>
</template>

<script>
    import UserService from "../../services/UserService";

    const userService = new UserService();

    export default {
        name: 'ListUsers',
        data() {
            return {
                items: [],
                loading: this.isLoading(),
                headers: [
                    {text: 'Nome', desc: 'Nome Completo', value: 'name'},
                    {text: 'E-mail', desc: 'E-mail de login', value: 'email'},
                    {text: 'Status', desc: 'Status Ativo ou Inativo', value: 'status', align: 'center', width: 60},
                    {
                        text: 'Ações',
                        desc: 'Ações do Item',
                        value: 'email',
                        sortable: false,
                        align: 'center',
                        width: 120
                    },
                ],
                filters: {
                    email: '',
                    status: '',
                    name: '',
                },
                statusItems: [
                    {text: 'Selecione', label: -1},
                    {text: 'Ativo', label: 1},
                    {text: 'Inativo', label: 0},
                ],
            }
        },
        methods: {
            isLoading() {
                return this.$store.getters.isLoading;
            },
            refresh() {
                this.$refs.vListagem.refresh();
            },
            editItem(item) {
                this.$router.push({name: 'editUser', params: item});
            },
            createItem() {
                this.$router.push({name: 'editUser'});
            },
            getAllItems(data) {
                return new Promise((resolve, reject) => {
                    userService.all(data).then(res => {
                        resolve(res);
                    }).catch(err => reject(err));
                })
            },
            deleteItem(item) {
                this.$confirm.show('Tem certeza que deseja excluir esse usuário?', {buttonTrueText: 'Excluir'}).then(res => {
                    if (res) {
                        this.$confirm.loading(true);
                        this.userService.destroy(item).then(() => {
                            this.$toasted.success('Usuário excluído com sucesso', {
                                duration: 5000,
                                fitToScreen: true,
                                theme: 'bubble',
                                icon: {
                                    name: 'check',
                                    after: false,
                                },
                            });
                            this.refresh();
                        }).catch(() => {
                            this.$confirm.loading(false);
                            this.$toasted.error('Erro ao excluir usuário', {
                                duration: 5000,
                                fitToScreen: true,
                                theme: 'bubble',
                                icon: {
                                    name: 'check',
                                    after: false,
                                },
                            });
                        });
                    }
                });
            }
        }
    }
</script>
