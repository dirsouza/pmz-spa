<template>
    <b-col md="12">
        <b-card
            header="Lista de Usuários"
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

                <!-- Tabela Usuários -->
                <tabela
                    :refreshTable="refreshTable"
                    @editar="editar"
                    @excluir="excluir"
                ></tabela>

                <!-- Modal -->
                <modal :id="modal.id" :title="modal.title" @closeModal="closeModal">

                    <!-- Formulário -->
                    <formulario
                        :dadosForm="usuario"
                        :responseForm="requestForm"
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
import Tabela from "../components/usuarios/_Tabela";
import Formulario from "../components/usuarios/_Formulario";
import Modal from "../components/Modal";

export default {
    name: "Usuarios",
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
            form: [],
            usuario: [],
            modal: {
                id: "addUsuario",
                title: "Cadastrar Usuário"
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
        }
    },
    methods: {
        formulario(dadosFormulario) {
            this.form = dadosFormulario;
        },
        cadastrar() {
            this.modal = {
                id: "addUsuario",
                title: "Cadastrar Novo Usuário"
            };

            this.showModal(this.modal.id);
        },
        relatorio() {
            axios.get(this.$apiLaroute.route("usuarios.relatorio"), {
                responseType: 'blob'
            })
            .then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');

                link.href = url;
                link.setAttribute('download', 'RelUsuarios.pdf');
                document.body.appendChild(link);
                link.click();

                this.$bvToast.toast("Relatório de usuários gerado com sucesso!", {
                    title: "Lista de Usuários",
                    variant: "success",
                    solid: true
                });
            })
            .catch(error => {
                this.$bvToast.toast("Não foi possível gerar o relatório de usuários.", {
                    title: "Lista de Usuários",
                    variant: "danger",
                    solid: true
                });
            });
        },
        editar(usuario) {
            this.modal = {
                id: "editUsuario",
                title: "Editar Usuário"
            };
            
            this.usuario = usuario;
            
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
            this.limparForm = true;
            this.usuario = [];
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

                axios.post(this.$apiLaroute.route("usuarios.create"), this.prepareFormData())
                .then(response => {
                    this.refresh();

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
                    this.refresh();

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
                    this.refresh();

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
        refresh() {
            this.refreshTable = true;
        }
    }
};
</script>
