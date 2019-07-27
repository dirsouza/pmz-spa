<template>
    <b-col md="12">
        <b-card
            header="Lista de Usuários"
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
                    <template slot="perfil" slot-scope="row">
                        <template v-if="row.value">{{ row.value }}</template>
                        <b-badge v-else variant="info">Sem perfil</b-badge>
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
                            <!-- Nome Completo -->
                            <b-col md="8">
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

                            <!-- Perfil -->
                            <b-col md="4">
                                <b-form-group
                                    :invalid-feedback="veeErrors.first('perfil')"
                                    :state="!veeErrors.has('perfil')"
                                    id="perfil-usuario"
                                    label="Perfil"
                                    label-for="perfil"
                                    label-class="mb-0"
                                >
                                   <b-form-select 
                                        :state="veeErrors.has('perfil') ? false : form.perfil ? true : false"
                                        name="perfil"
                                        v-model="form.perfil" 
                                        :options="selectOptions"
                                        v-validate="'required|numeric'"
                                        data-vv-as="Perfil"
                                    ></b-form-select>
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-row>
                            <!-- E-mail -->
                            <b-col md="8">
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

                            <!-- Registro -->
                            <b-col md="4">
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
                        <b-button variant="primary">
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

export default {
    name: "Usuarios",
    components: {
        Modal
    },
    data() {
        return {
            items: [],
            fields: [
                { key: "id", label: "Código", sortable: true, class: "text-center", sortDirection: "asc" },
                { key: "name", label: "Nome Completo", sortable: true },
                { key: "email", label: "E-mail", sortable: true },
                { key: "perfil", label: "Perfil", sortable: true, filterable: true },
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
            selectOptions: [{value: null, text: 'Selecione um perfil'}]
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
                const response = await axios.get(
                    this.$apiLaroute.route("usuarios.index")
                );

                this.totalRows = response.data.length;
                this.items = response.data;
            } catch (e) {
                console.log(e.response);
            }
        },
        async getListaPerfis() {
            try {
                const response = await axios.get(
                    this.$apiLaroute.route("perfis.index")
                );

                this.selectOptions.push(...response.data.map(perfil => {
                    return {
                        value: perfil.id,
                        text: perfil.nome
                    }
                }));
            } catch (e) {
                console.log(e.response);
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

            this.form.perfil = null;

            this.$bvModal.show(this.modal.id);
        },
        editar(item) {
            this.modal = {
                id: "editUsuario",
                title: "Editar Usuário"
            };
            
            let perfil = this.selectOptions.filter(opt => opt.text === item.perfil);
            
            if (perfil.length > 0) {
                perfil = perfil[0].value;
            } else {
                perfil = null;
            }

            this.form = {
                name: item.name,
                perfil: perfil,
                email: item.email,
                status: item.status
            };
            
            this.$bvModal.show(this.modal.id);
        },
        excluir(item) {

        },
        closeModal() {
            this.$bvModal.hide(this.modal.id);
            this.form = [];
        },
        onSubmit() {

        },
        onReset() {

        }
    }
};
</script>

