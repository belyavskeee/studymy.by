<template>
    <my-header v-if="isAuthenticated"></my-header>
    <report-error-component/>
    <my-footer/>
</template>

<script>
import ReportErrorComponent from "@/components/ReportErrorComponent.vue";
import { mapGetters, mapActions } from 'vuex';
export default {
    components: {
        ReportErrorComponent
    },
    computed: {
    ...mapGetters(['isAuthenticated']),
    },
    mounted() {
		document.title = 'Сообщить об ошибке - beStudy';
    }
}
</script>

<style>

</style>