<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="secondary">
            <b-container>
                <b-navbar-brand to="/"><strong>PMZ</strong></b-navbar-brand>
                <Header/>
            </b-container>
        </b-navbar>

        <b-container class="pt-3">
            <transition>
                <router-view/>
            </transition>
        </b-container>
    </div>
</template>

<script>
    import Header from "./Header";

    export default {
        name: "App",
        components: {
            Header
        },
    }
</script>
