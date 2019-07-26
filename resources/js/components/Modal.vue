<template>
    <b-modal
        :id="id"
        :title="title"
        :size="size"
        centered 
        :no-close-on-backdrop="true" 
        :no-close-on-esc="true"
        @hidden="closeModal"
    >
        <b-container fluid>
            <slot name="body"></slot>
        </b-container>
        <div slot="modal-footer" slot-scope="{cancel, ok}">
            <slot name="footer"></slot>
        </div>
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