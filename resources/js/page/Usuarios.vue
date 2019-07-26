<template>
  <b-col md="12">
    <b-card
      header="Lista de Usuários"
      header-tag="header"
      header-bg-variant="primary"
      header-text-variant="white"
    >
      <b-container fluid>
        <!-- User Interface controls -->
        <b-row align-h="between">
          <b-col md="2">
            <b-form-group label="Mostrar" label-for="per-page" label-class="mb-0">
              <b-form-select id="per-page" v-model="perPage" :options="pageOptions"></b-form-select>
            </b-form-group>
          </b-col>

          <b-col md="4">
            <b-form-group label="Filtrar" label-for="filter" label-class="mb-0">
              <b-input-group>
                <b-form-input id="filter" v-model="filter" placeholder="Buscar"></b-form-input>
                <b-input-group-append>
                  <b-button
                    :disabled="!filter"
                    @click="filter = ''"
                    v-b-tooltip.hover
                    title="Limpar"
                  >
                    <i class="fas fa-broom"></i>
                  </b-button>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>

        <!-- Main table element -->
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
            <template slot="status" slot-scope="row">
                <b-badge v-if="row.value" variant="success">Ativo</b-badge>
                <b-badge v-else variant="danger">Inativo</b-badge>
            </template>

            <template slot="actions" slot-scope="row">
                <b-button size="sm" variant="outline-info" v-b-tooltip.hover
                    title="Editar" @click="Editar(row.item, row.index, $event.target)" class="mr-1">
                    <i class="fas fa-user-edit"></i>
                </b-button>

                <b-button size="sm" variant="outline-danger" v-b-tooltip.hover
                    title="Excluir" @click="Excluir(row.item, row.index, $event.target)" class="mr-1">
                    <i class="fas fa-user-times"></i>
                </b-button>
            </template>
        </b-table>

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

        <modal
            :id="modal.id"
            :title="modal.title"
            @closeModal="closeModal"
        ></modal>
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
        {
          key: "id",
          label: "Código",
          sortable: true,
          class: "text-center",
          sortDirection: "asc"
        },
        { key: "name", label: "Nome Completo", sortable: true },
        { key: "email", label: "E-mail", sortable: true },
        {
          key: "status",
          label: "Registro",
          sortable: true,
          filterable: false,
          class: "text-center"
        },
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
          show: false,
          id: "editUsuario",
          title: "Editar Usuário"
      }
    };
  },
  created() {
    this.getDados();
  },
  mounted() {
    this.totalRows = this.items.length;
  },
  methods: {
    async getDados() {
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
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    Editar(item) {
        this.$bvModal.show(this.modal.id);
        console.log(item);
    },
    closeModal() {
        // this.$vbModal.hidden(this.modal.id);
    }
  }
};
</script>
