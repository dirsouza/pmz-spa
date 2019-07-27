<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="secondary">
            <b-container>
                <b-navbar-brand to="/"><strong>PMZ</strong></b-navbar-brand>
                <Header/>
            </b-container>
        </b-navbar>

        <b-container class="pt-3">
            <transition name="slide" mode="out-in">
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

<style scoped>

    .slide-enter, .slide-leave-to {
        transform: translateX(-50px);
        opacity: 0;
    }

    .slide-enter-active, .slide-leave-active {
        transition: all 0.3s;
    }

</style>