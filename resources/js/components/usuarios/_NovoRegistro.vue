<template>
    <b-row align-h="end">
        <b-col md="3" align="right">
            <b-button variant="secondary" size="sm" @click="cadastrar">
                <i class="fas fa-plus-circle fa-fw"></i>
                Novo Registro
            </b-button>
        </b-col>
    </b-row>
</template>

<script>
export default {    
    name: "NovoRegistro",
    methods: {
        cadastrar() {
            this.$emit('Cadastrar');
        }
    }
}
</script>

