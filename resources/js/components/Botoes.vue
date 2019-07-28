<template>
    <b-row align-h="end">
        <b-button variant="secondary" size="sm" @click="cadastrar">
            <i class="fas fa-plus-circle fa-fw"></i>
            Novo Registro
        </b-button>
    
        <b-button class="ml-1 mr-3" variant="secondary" size="sm" @click="relatorio">
            <i class="fas fa-file-pdf fa-fw"></i>
            Relatório
        </b-button>
    </b-row>
</template>

<script>
export default {    
    name: "Botoes",
    methods: {
        cadastrar() {
            this.$emit('Cadastrar');
        },
        relatorio() {
            this.$emit('Relatorio');
        }
    }
}
</script>

