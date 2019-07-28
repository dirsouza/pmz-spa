<template>
    <div>
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
            ref="tableAparelhos"
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
            <div slot="table-busy" class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
            </div>

            <template slot="usuarios" slot-scope="row">
                <b-button variant="outline-primary" size="sm" v-if="row.item.usuarios.length > 0" @click="row.toggleDetails">
                    <i v-if="row.detailsShowing" class="far fa-eye-slash"></i>
                    <i v-else class="far fa-eye"></i>
                </b-button>
                <b-badge v-else variant="info">Sem usuários</b-badge>
            </template>

            <template slot="row-details" slot-scope="row">
                <b-card>
                    <b-card-title class="mb-0">Lista de Usuários:</b-card-title>
                    <ul class="mb-0">
                        <li v-for="(value, key) in row.item.usuarios" :key="key">{{ value.name }}</li>
                    </ul>
                </b-card>
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

        <span hidden>{{ getIsRefresh }}</span>
    </div>
</template>

<script>
export default {
    name: "TabelaAparelhos",
    props: {
        refreshTable: {
            type: Boolean,
            required: true
        },
    },
    data() {
        return {
            isBusy: false,
            items: [],
            fields: [
                { key: "id", thClass: 'd-none', tdClass: 'd-none' },
                { key: "codigo", label: "Código", sortable: true, class: "text-center", sortDirection: "asc" },
                { key: "descricao", label: "Descrição", sortable: true },
                { key: "usuarios", label: "Usuários", sortable: true, filterable: true, class: "text-center" },
                { key: "actions", label: "", sortable: true, filterable: false, class: "text-center" }
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 25, 50],
            sortBy: null,
            sortDesc: false,
            filter: null,
        }
    },
    created() {
        this.getListaAparelhos();
    },
    mounted() {
        this.totalRows = this.items.length;
    },
    computed: {
        getIsRefresh() {
            return this.refreshTable;
        }
    },
    watch: {
        getIsRefresh(value) {
            console.log(value);
            if (value) {
                this.refresh();
            }
        },
    },
    methods: {
        async getListaAparelhos() {
            try {
                this.isBusy = true;

                const response = await axios.get(
                    this.$apiLaroute.route("aparelhos.index")
                );

                this.totalRows = response.data.length;
                this.items = response.data;

                this.isBusy = false;
            } catch (e) {
                this.$bvToast.toast("Não foi possível carregar os Aparelhos.", {
                    title: "Lista de Aparelhos",
                    variant: "danger",
                    solid: true
                });

                this.isBusy = false;
            }
        },
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        refresh() {
            this.getListaAparelhos();
            this.$refs.tableAparelhos.refresh();
        },
        editar(aparelho) {
            this.$emit('editar', aparelho);
        },
        excluir(aparelho) {
            this.$emit('excluir', aparelho);
        },
    }
}
</script>
