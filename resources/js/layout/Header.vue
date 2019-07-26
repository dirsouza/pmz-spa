<template>
    <nav>
        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav>
                <b-nav-item to="/usuarios">Usuarios</b-nav-item>
                <b-nav-item to="/perfis">Perfis</b-nav-item>
                <b-nav-item to="/aparelhos">Aparelhos</b-nav-item>
            </b-navbar-nav>
        </b-collapse>
    </nav>
</template>

<script>
    export default {
        name: "Header"
    }
</script>

<style scoped>

</style>
