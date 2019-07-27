<template>
    <b-modal
        :id="id"
        :title="title"
        :size="size"
        :no-close-on-backdrop="true" 
        :no-close-on-esc="true"
        @hidden="closeModal"
    >
        <slot></slot>
        
        <template slot="modal-footer">
            <slot name="footer"></slot>
        </template>
    </b-modal>
</template>
<script>
    export default {
        props: {
            id: {
                type: String,
                required: true
            },
            title: {
                type: String,
                required: true
            },
            size: {
                type: String,
                required: false,
                default: "lg"
            }
        },
        methods: {
            closeModal() {
                this.$emit('closeModal');
            }
        }
    }
</script>