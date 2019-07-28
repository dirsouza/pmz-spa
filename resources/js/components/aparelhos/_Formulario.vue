<template>
    <b-form>
        <b-row>
            <!-- Id -->
            <input type="hidden" name="id" v-model="form.id">

            <!-- Código do Aparelho -->
            <b-col>
                <b-form-group
                    :invalid-feedback="veeErrors.first('codigo')"
                    :state="!veeErrors.has('codigo')"
                    id="nome-codigo"
                    label="Código"
                    label-for="codigo"
                    label-class="mb-0"
                >
                    <b-form-input 
                        :state="veeErrors.has('codigo') ? false : form.codigo ? true : false"
                        type="text"
                        id="codigo" 
                        name="codigo" 
                        placeholder="Código do Aparelho" 
                        v-model="form.codigo"
                        v-validate="'required|alpha_num'"
                        data-vv-as="Código do Aparelho"
                        trim
                    ></b-form-input>
                </b-form-group>
            </b-col>

            <!-- Descrição -->
            <b-col md="9">
                <b-form-group
                    :invalid-feedback="veeErrors.first('descricao')"
                    :state="!veeErrors.has('descricao')"
                    id="nome-descricao"
                    label="Descrição do Aparelho"
                    label-for="descricao"
                    label-class="mb-0"
                >
                    <b-form-input 
                        :state="veeErrors.has('descricao') ? false : form.descricao ? true : false"
                        type="text"
                        id="descricao" 
                        name="descricao" 
                        placeholder="Descrição do Aparelho" 
                        v-model="form.descricao"
                        v-validate="'required'"
                        data-vv-as="Descrição do Aparelho"
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

        <span hidden>{{ getDadosAparelho }}</span>
        <span hidden>{{ getResponseForm }}</span>
        <span hidden>{{ getLimparForm }}</span>
    </b-form>
</template>

<script>
import Multiselect from "vue-multiselect";

export default {
    name: "FormularioAparelhos",
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
        getDadosAparelho() {
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
                this.$bvToast.toast("Não foi possível carregar os Aparelhos.", {
                    title: "Aparelhos",
                    variant: "danger",
                    solid: true
                });
            }
        },
        dadosEditar(aparelho) {
            let usuarios = [];
            aparelho.usuarios.forEach(usuario => {
                usuarios.push(...this.selectOptions.filter(opt => {
                    if (usuario.id === opt.id) {
                        return opt;
                    }
                }));
            });

            this.form = {
                id: aparelho.id,
                codigo: aparelho.codigo,
                descricao: aparelho.descricao,
                usuarios: usuarios
            };
        },
        limparFormulario() {
            this.form = [];
        }
    }
}
</script>