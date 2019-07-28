<template>
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

        <span hidden>{{ getDadosUsuario }}</span>
        <span hidden>{{ getResponseForm }}</span>
    </b-form>
</template>

<script>
import Multiselect from "vue-multiselect";

export default {
    name: "FormularioUsuarios",
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
        }
    },
    data() {
        return {
            form: [],
            selectOptions: []
        };
    },
    created() {
        this.getListaPerfis();
    },
    computed: {
        getDadosUsuario() {
            if (Object.keys(this.dadosForm).length > 0) {
                this.dadosEditar(this.dadosForm);
            }
        },
        getResponseForm() {
            if (this.responseForm) {
                this.$emit('formulario', this.form);
            }
        }
    },
    methods: {
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
        dadosEditar(usuario) {
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
        },
    }
}
</script>