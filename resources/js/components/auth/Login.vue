<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12">
                    <v-toolbar dark color="#004667" class="white--text">
                        <v-toolbar-title>Entrar</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form v-model="validLogin" ref="formLogin" lazy-validation>
                            <v-text-field prepend-icon="person"
                                          name="email"
                                          v-model="email"
                                          label="E-mail/Usuário"
                                          :loading="loading"
                                          :disabled="loading"
                                          :rules="validator.emailRules"
                                          type="text"></v-text-field>
                            <v-text-field id="password"
                                          v-model="password"
                                          prepend-icon="lock"
                                          :loading="loading"
                                          :disabled="loading"
                                          name="password"
                                          :rules="validator.senhaRules"
                                          label="Senha"
                                          type="password"></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-layout>
                            <v-flex>
                                <v-tooltip bottom>
                                    <v-btn color="primary"
                                           slot="activator"
                                           :loading="loading"
                                           :disabled="!validLogin || loading"
                                           @click="autenticar()"
                                           class="mr-2">
                                        <v-icon left dark>fa-lock</v-icon>
                                        Entrar
                                    </v-btn>
                                    <span>Entrar</span>
                                </v-tooltip>
                            </v-flex>
                        </v-layout>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import AuthService from "../../services/AuthService";

    export default {
        name: "Login",
        data() {
            return {
                loading: false,
                validLogin: false,
                email: '',
                password: '',
                validator: {
                    emailRules: [
                        (v) => !!v || 'E-mail/Usuário é obrigatório',
                    ],
                    passwordRules: [
                        (v) => !!v || 'Senha é obrigatória',
                    ],
                },
            }
        },
        mounted() {
            this.$store.commit('setHasDrawerLeft', {
                hasDrawerLeft: false,
            });
        },
        methods: {
            autenticar() {
                if (!this.$refs.formLogin.validate()) {
                    return;
                }

                this.loading = true;
                this.$confirm.loading(true);

                const service = new AuthService();
                service.auth(this.email, this.password).then(data => {
                    this.loading = false;
                    this.$confirm.loading(false);
                    this.$toasted.success('Bem vindo ' + data.user.name, {
                        duration: 3000,
                        theme: 'bubble',
                        icon: {
                            name: 'check',
                            after: false,
                        },
                    });
                    this.$router.push({ path: 'home' })
                }).catch((e) => {
                    this.loading = false;
                    this.$confirm.loading(false);
                    this.$toasted.error(e, {
                        duration: 3000,
                        theme: 'bubble',
                        icon: {
                            name: 'check',
                            after: false,
                        },
                    });
                });
            }
        }
    }
</script>

<style scoped>

</style>
