<template>
    <b-col md="12">
        <b-card
            header="Lista de Aparelhos"
            header-tag="header"
            header-bg-variant="primary"
            header-text-variant="white"
        >
            <b-container fluid>
                <!-- Botoes -->
                <botoes
                    @Cadastrar="cadastrar"
                    @Relatorio="relatorio"
                ></botoes>

                <!-- Tabela Aparelhos -->
                <tabela
                    :refreshTable="refreshTable"
                    @editar="editar"
                    @excluir="excluir"
                ></tabela>

                <!-- Modal -->
                <modal :id="modal.id" :title="modal.title" @closeModal="closeModal">

                    <!-- Formulário -->
                    <formulario
                        :dadosForm="aparelho"
                        :responseForm="requestForm"
                        :limparForm="limparForm"
                        @formulario="formulario"
                    ></formulario>

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
import Botoes from "../components/Botoes";
import Tabela from "../components/aparelhos/_Tabela";
import Formulario from "../components/aparelhos/_Formulario";
import Modal from "../components/Modal";

export default {
    name: "Aparelhos",
    components: {
        Botoes,
        Tabela,
        Formulario,
        Modal
    },
    data() {
        return {
            refreshTable: false,
            requestForm: false,
            limparForm: false,
            form: [],
            aparelho: [],
            modal: {
                id: "",
                title: ""
            },
        };
    },
    watch: {
        refreshTable(value) {
            if (value) {
                setTimeout(() => {
                    this.refreshTable = false;
                }, 100);
            }
        },
        requestForm(value) {
            if (value) {
                setTimeout(() => {
                    this.requestForm = false;
                }, 100);
            }
        },
        limparForm(value) {
            if (value) {
                setTimeout(() => {
                    this.limparForm = false;
                }, 100);
            }
        }
    },
    methods: {
        formulario(dadosFormulario) {
            this.form = dadosFormulario;
        },
        cadastrar() {
            this.modal = {
                id: "addAparelho",
                title: "Cadastrar Novo Aparelho"
            };
            
            this.showModal(this.modal.id);
        },
        relatorio() {

        },
        editar(aparelho) {
            this.modal = {
                id: "editAparelho",
                title: "Editar Aparelho"
            };
            
            this.aparelho = aparelho;
            
            this.showModal(this.modal.id);  
        },
        excluir(aparelho) {
            this.$swal({
                title: `Excluir o aparelho\ncódigo: ${aparelho.codigo}?`,
                text: "Essa operação não poderá ser desfeita!",
                type: 'warning',
                showCancelButton: true,
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-light',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    this.delete(aparelho.id);
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
            this.limparForm = true;
            this.aparelho = [];
        },
        onSubmit() {
            this.requestForm = true;

            let setForm = setInterval(() => {
                if (this.form.length > 0 || Object.keys(this.form).length > 0) {
                    clearInterval(setForm);

                    if (this.form.hasOwnProperty('id')) {
                        return this.update();
                    }

                    return this.create();
                }
            }, 10);
            
        },
        create() {
            this.$validator.validateAll().then(result => {
                if (!result) {
                    return;
                }

                axios.post(this.$apiLaroute.route("aparelhos.create"), this.prepareFormData())
                .then(response => {
                    this.refresh();

                    this.$bvToast.toast("Aparelho cadastrado com sucesso!", {
                        title: "Cadastro de Aparelho",
                        variant: "success",
                        solid: true
                    });
                })
                .catch(error => {
                    this.$bvToast.toast("Não foi possível cadastro o aparelho.", {
                        title: "Cadastro de Aparelho",
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

                axios.post(this.$apiLaroute.route("aparelhos.update", { id: this.form.id }), formData)
                .then(response => {
                    this.refresh();

                    this.$bvToast.toast("Aparelho atualizado com sucesso!", {
                        title: "Atualização de Aparelho",
                        variant: "success",
                        solid: true
                    });
                })
                .catch(error => {
                    this.$bvToast.toast("Não foi possível atualizar o aparelho.", {
                        title: "Atualização de Aparelho",
                        variant: "danger",
                        solid: true
                    });
                });

                this.closeModal();
            });
        },
        delete(id) {
            axios.delete(this.$apiLaroute.route("aparelhos.delete", { id: id }))
                .then(response => {
                    this.refresh();

                    this.$bvToast.toast("Aparelho excluído com sucesso!", {
                        title: "Exclusão de Aparelho",
                        variant: "success",
                        solid: true
                    });
                })
                .catch(error => {
                    this.$bvToast.toast("Não foi possível excluir o aparelho.", {
                        title: "Exclusão de Aparelho",
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
        refresh() {
            this.refreshTable = true;
        }
    }
};
</script>
