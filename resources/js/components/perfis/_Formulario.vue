<template>
    <b-form>
        <b-row>
            <!-- Id -->
            <input type="hidden" name="id" v-model="form.id">

            <!-- Nome do Perfil -->
            <b-col>
                <b-form-group
                    :invalid-feedback="veeErrors.first('perfil')"
                    :state="!veeErrors.has('perfil')"
                    id="nome-perfil"
                    label="Nome do Perfil"
                    label-for="perfil"
                    label-class="mb-0"
                >
                    <b-form-input 
                        :state="veeErrors.has('perfil') ? false : form.nome ? true : false"
                        type="text"
                        id="perfil" 
                        name="perfil" 
                        placeholder="Nome do Perfil" 
                        v-model="form.nome"
                        v-validate="'required|alpha_spaces'"
                        data-vv-as="Nome do Perfil"
                        trim
                    ></b-form-input>
                </b-form-group>
            </b-col>
        </b-row>

        <b-row>
            <!-- Usuarios -->
            <b-col>
                <b-form-group
                    id="usuario-perfil"
                    label="Usuários"
                    label-for="usuarios"
                    label-class="mb-0"
                >
                    <multiselect 
                        name="usuarios" 
                        v-model="form.usuarios" 
                        :options="selectOptions"
                        :multiple="true"
                        label="name"
                        track-by="id"
                        placeholder="Selecione os Usuários"
                    ></multiselect>
                </b-form-group>
            </b-col>
        </b-row>

        <span hidden>{{ getDadosPerfil }}</span>
        <span hidden>{{ getResponseForm }}</span>
        <span hidden>{{ getLimparForm }}</span>
    </b-form>
</template>

<script>
import Multiselect from "vue-multiselect";

export default {
    name: "FormularioPerfis",
    components: {
        Multiselect
    },
    props: {
        dadosForm: {
            type: [Object, Array],
            required: false
        },
        responseForm: {
            type: Boolean,
            required: false
        },
        limparForm: {
            type: Boolean,
            required: false
        }
    },
    data() {
        return {
            form: [],
            selectOptions: []
        };
    },
    created() {
        this.getListaUsuarios();
    },
    computed: {
        getDadosPerfil() {
            if (Object.keys(this.dadosForm).length > 0) {
                this.dadosEditar(this.dadosForm);
            }
        },
        getResponseForm() {
            if (this.responseForm) {
                this.$emit('formulario', this.form);
            }
        },
        getLimparForm() {
            if (this.limparForm) {
                this.limparFormulario();
            }
        }
    },
    methods: {
        async getListaUsuarios() {
            this.selectOptions = [];

            try {
                const response = await axios.get(
                    this.$apiLaroute.route("usuarios.index")
                );

                this.selectOptions = response.data;
            } catch (e) {
                this.$bvToast.toast("Não foi possível carregar os Usuários.", {
                    title: "Usuários",
                    variant: "danger",
                    solid: true
                });
            }
        },
        dadosEditar(perfil) {
            let usuarios = [];
            perfil.usuarios.forEach(usuario => {
                usuarios.push(...this.selectOptions.filter(opt => {
                    if (usuario.id === opt.id) {
                        return opt;
                    }
                }));
            });

            this.form = {
                id: perfil.id,
                nome: perfil.nome,
                usuarios: usuarios
            };
        },
        limparFormulario() {
            this.form = [];
        }
    }
}
</script>