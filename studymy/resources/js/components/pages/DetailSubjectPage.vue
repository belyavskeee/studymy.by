<template>
    <my-header/>
    <detail-subject-component/>
    <my-footer/>
</template>

<script>
import DetailSubjectComponent from "@/components/DetailSubjectComponent.vue";

export default {
    components: {
        DetailSubjectComponent,
    },
    mounted() {
		document.title = 'Журнал - beStudy';
    }
}
</script>

<style>

</style>