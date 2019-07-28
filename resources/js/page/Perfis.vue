<template>
    <b-col md="12">
        <b-card
            header="Lista de Perfis"
            header-tag="header"
            header-bg-variant="primary"
            header-text-variant="white"
        >
            <b-container fluid>
                <!-- Novo Registro -->
                <b-row align-h="end">
                    <b-col md="3" align="right">
                        <b-button variant="secondary" size="sm" @click="cadastrar">
                            <i class="fas fa-plus-circle fa-fw"></i>
                            Novo Registro
                        </b-button>
                    </b-col>
                </b-row>

                <!-- Filters -->
                <b-row align-h="between">
                    <b-col md="2">
                        <b-form-group label="Mostrar" label-for="per-page" label-class="mb-0">
                            <b-form-select id="per-page" v-model="perPage" :options="pageOptions" size="sm"></b-form-select>
                        </b-form-group>
                    </b-col>

                    <b-col md="4">
                        <b-form-group label="Filtrar" label-for="filter" label-class="mb-0">
                        <b-input-group>
                            <b-form-input id="filter" v-model="filter" placeholder="Buscar" size="sm"></b-form-input>
                            <b-input-group-append>
                            <b-button
                                variant="outline-secondary"
                                size="sm"
                                :disabled="!filter"
                                @click="filter = ''"
                            >
                                <i class="fas fa-broom"></i>
                            </b-button>
                            </b-input-group-append>
                        </b-input-group>
                        </b-form-group>
                    </b-col>
                </b-row>

                <!-- Table -->
                <b-table
                    ref="tableUsuarios"
                    :busy.sync="isBusy"
                    show-empty
                    stacked="md"
                    responsive
                    :small="true"
                    :hover="true"
                    :items="items"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    @filtered="onFiltered"
                >
                    <template slot="perfis" slot-scope="row">
                        <b-button variant="outline-primary" size="sm" v-if="row.item.perfis.length > 0" @click="row.toggleDetails">
                            <i v-if="row.detailsShowing" class="far fa-eye-slash"></i>
                            <i v-else class="far fa-eye"></i>
                        </b-button>
                        <b-badge v-else variant="info">Sem perfis</b-badge>
                    </template>

                    <template slot="row-details" slot-scope="row">
                        <b-card>
                            <b-card-title class="mb-0">Lista de Perfis:</b-card-title>
                            <ul class="mb-0">
                                <li v-for="(value, key) in row.item.perfis" :key="key">{{ value.nome }}</li>
                            </ul>
                        </b-card>
                    </template>

                    <template slot="status" slot-scope="row">
                        <b-badge v-if="row.value" variant="success">Ativo</b-badge>
                        <b-badge v-else variant="danger">Inativo</b-badge>
                    </template>

                    <template slot="actions" slot-scope="row">
                        <b-button
                            size="sm"
                            variant="outline-info"
                            v-b-tooltip.hover
                            title="Editar"
                            @click="editar(row.item, row.index, $event.target)"
                            class="mr-1"
                        >
                            <i class="fas fa-edit"></i>
                        </b-button>

                        <b-button
                            size="sm"
                            variant="outline-danger"
                            v-b-tooltip.hover
                            title="Excluir"
                            @click="excluir(row.item, row.index, $event.target)"
                            class="mr-1"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </b-button>
                    </template>
                </b-table>

                <!-- Pagination -->
                <b-row>
                    <b-col md="6" class="my-1">
                        <b-pagination
                            v-model="currentPage"
                            :total-rows="totalRows"
                            :per-page="perPage"
                            class="my-0"
                        ></b-pagination>
                    </b-col>
                </b-row>

                <!-- Modal -->
                <modal :id="modal.id" :title="modal.title" @closeModal="closeModal">
                    <b-form>
                        <b-row>
                            <!-- Id -->
                            <input type="hidden" name="id" v-model="form.id">

                            <!-- Nome Completo -->
                            <b-col md="7">
                                <b-form-group
                                    :invalid-feedback="veeErrors.first('nome')"
                                    :state="!veeErrors.has('nome')"
                                    id="nome-usuario"
                                    label="Nome Completo"
                                    label-for="nome"
                                    label-class="mb-0"
                                >
                                    <b-form-input 
                                        :state="veeErrors.has('nome') ? false : form.name ? true : false"
                                        type="text"
                                        id="nome" 
                                        name="nome" 
                                        placeholder="Nome Completo" 
                                        v-model="form.name"
                                        v-validate="'required|alpha_spaces'"
                                        data-vv-as="Nome Completo"
                                        trim
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>

                            <!-- E-mail -->
                            <b-col>
                                <b-form-group
                                    :invalid-feedback="veeErrors.first('email')"
                                    :state="!veeErrors.has('email')"
                                    id="email-usuario"
                                    label="E-mail"
                                    label-for="email"
                                    label-class="mb-0"
                                >
                                    <b-form-input 
                                        :state="veeErrors.has('email') ? false : form.email ? true : false"
                                        id="email" 
                                        name="email"
                                        placeholder="E-mail" 
                                        v-model="form.email" 
                                        v-validate="'required|email'"
                                        data-vv-as="E-mail"
                                        trim
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-row>
                            <!-- Perfis -->
                            <b-col md="10">
                                <b-form-group
                                    id="perfil-usuario"
                                    label="Perfis"
                                    label-for="perfis"
                                    label-class="mb-0"
                                >
                                    <multiselect 
                                        name="perfis" 
                                        v-model="form.perfis" 
                                        :options="selectOptions"
                                        :multiple="true"
                                        label="nome"
                                        track-by="id"
                                        placeholder="Selecione os Perfis"
                                    ></multiselect>
                                </b-form-group>
                            </b-col>

                            <!-- Registro -->
                            <b-col>
                                <b-form-group
                                    id="registro-usuario"
                                    label="Registro"
                                    label-for="registro"
                                    label-class="mb-0"
                                >
                                    <b-form-checkbox
                                        id="registro"
                                        v-model="form.status"
                                        name="registro"
                                        :value="true"
                                        :unchecked-value="false"
                                    >
                                        <strong v-if="form.status">Ativo</strong>
                                        <strong v-else>Inativo</strong>
                                    </b-form-checkbox>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-form>

                    <template slot="footer">
                        <b-button variant="light" @click="closeModal">
                            <i class="fas fa-times-circle"></i>
                            Fechar
                        </b-button>
                        <b-button variant="primary" @click="onSubmit">
                            <i class="fas fa-check-circle"></i>
                            Salvar
                        </b-button>
                    </template>
                </modal>
            </b-container>
        </b-card>
    </b-col>
</template>

<script>
import Modal from "../components/Modal";
import Multiselect from "vue-multiselect";

export default {
    name: "Usuarios",
    components: {
        Modal,
        Multiselect
    },
    data() {
        return {
            isBusy: false,
            items: [],
            fields: [
                { key: "id", label: "Código", sortable: true, class: "text-center", sortDirection: "asc" },
                { key: "nome", label: "Nome Completo", sortable: true },
                { key: "email", label: "E-mail", sortable: true },
                { key: "perfis", label: "Perfis", sortable: true, filterable: true, class: "text-center" },
                { key: "status", label: "Registro", sortable: true, filterable: false, class: "text-center" },
                { key: "actions", label: "", sortable: true, filterable: false, class: "text-center" }
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 25, 50],
            sortBy: null,
            sortDesc: false,
            filter: null,
            modal: {
                id: "addUsuario",
                title: "Cadastrar Usuário"
            },
            form: [],
            selectOptions: []
        };
    },
    created() {
        this.getListaUsuarios();
        this.getListaPerfis();
    },
    mounted() {
        this.totalRows = this.items.length;
    },
    methods: {
        async getListaUsuarios() {
            try {
                this.isBusy = true;

                const response = await axios.get(
                    this.$apiLaroute.route("usuarios.index")
                );

                this.totalRows = response.data.length;
                this.items = response.data;

                this.isBusy = false;
            } catch (e) {
                this.$bvToast.toast("Não foi possível carregar os Usuários.", {
                    title: "Lista de Usuários",
                    variant: "danger",
                    solid: true
                });

                this.isBusy = false;
            }
        },
        async getListaPerfis() {
            this.selectOptions = [];

            try {
                const response = await axios.get(
                    this.$apiLaroute.route("perfis.index")
                );

                this.selectOptions = response.data;
            } catch (e) {
                this.$bvToast.toast("Não foi possível carregar os Perfis.", {
                    title: "Perfis de Usuários",
                    variant: "danger",
                    solid: true
                });
            }
        },
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        cadastrar() {
            this.modal = {
                id: "addUsuario",
                title: "Cadastrar Novo Usuário"
            };

            this.showModal(this.modal.id);
        },
        editar(usuario) {
            this.modal = {
                id: "editUsuario",
                title: "Editar Usuário"
            };
            
            let perfis = [];
            usuario.perfis.forEach(perfil => {
                perfis.push(...this.selectOptions.filter(opt => {
                    if (perfil.id === opt.id) {
                        return opt;
                    }
                }));
            });

            this.form = {
                id: usuario.id,
                name: usuario.name,
                email: usuario.email,
                perfis: perfis,
                status: usuario.status
            };
            
            this.showModal(this.modal.id);  
        },
        excluir(usuario) {
            this.$swal({
                title: `Excluir o usuário\n${usuario.name}?`,
                text: "Essa operação não poderá ser desfeita!",
                type: 'warning',
                showCancelButton: true,
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-light',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    this.delete(usuario.id);
                }
            });
        },
        showModal(id) {
            setTimeout(() => {
                this.$bvModal.show(id);
            }, 100);
        },
        closeModal() {
            this.$bvModal.hide(this.modal.id);
            this.form = [];
        },
        onSubmit() {
            if (this.form.hasOwnProperty('id')) {
                return this.update();
            }

            return this.create();
        },
        create() {
            this.$validator.validateAll().then(result => {
                if (!result) {
                    return;
                }

                axios.post(this.$apiLaroute.route("usuarios.create"), this.prepareFormData())
                .then(response => {
                    this.refreshTable();

                    this.$bvToast.toast("Usuário cadastrado com sucesso!", {
                        title: "Cadastro de Usuários",
                        variant: "success",
                        solid: true
                    });
                })
                .catch(error => {
                    this.$bvToast.toast("Não foi possível cadastro o usuário.", {
                        title: "Cadastro de Usuários",
                        variant: "danger",
                        solid: true
                    });
                });

                this.closeModal();
            });
        },
        update() {
            this.$validator.validateAll().then(result => {
                if (!result) {
                    return;
                }

                let formData = this.prepareFormData();
                    formData.append('_method', 'PUT');

                axios.post(this.$apiLaroute.route("usuarios.update", { id: this.form.id }), formData)
                .then(response => {
                    this.refreshTable();

                    this.$bvToast.toast("Usuário atualizado com sucesso!", {
                        title: "Atualização de Usuários",
                        variant: "success",
                        solid: true
                    });
                })
                .catch(error => {
                    this.$bvToast.toast("Não foi possível atualizar o usuário.", {
                        title: "Atualização de Usuários",
                        variant: "danger",
                        solid: true
                    });
                });

                this.closeModal();
            });
        },
        delete(id) {
            axios.delete(this.$apiLaroute.route("usuarios.delete", { id: id }))
                .then(response => {
                    this.refreshTable();

                    this.$bvToast.toast("Usuário excluído com sucesso!", {
                        title: "Exclusão de Usuários",
                        variant: "success",
                        solid: true
                    });
                })
                .catch(error => {
                    this.$bvToast.toast("Não foi possível excluir o usuário.", {
                        title: "Exclusão de Usuários",
                        variant: "danger",
                        solid: true
                    });
                });
        },
        prepareFormData() {
            const formData = new FormData;

            Object.keys(this.form).forEach(key => {
                if (typeof this.form[key] === "object") {
                    this.form[key].forEach(obj => {
                        formData.append(`${key}[]`, obj.id);
                    });
                } else {
                    formData.append(key, this.form[key]);
                }
            });

            return formData;
        },
        refreshTable() {
            this.getListaUsuarios();
            this.$refs.tableUsuarios.refresh();
        }
    }
};
</script>
